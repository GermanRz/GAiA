<?php

require_once "conexion.php";

class ModeloFinanciera
{

    /*=============================================
    MOSTRAR BENEFICIARIOS POR CONVOCATORIA
    =============================================*/
    static public function mdlMostrarBeneficiarios($idConvocatoria)
    {
        $stmt = Conexion::conectar()->prepare("
            SELECT 
                i.id AS inscripcion_id,
                ap.descripcion_apoyo AS tipo_apoyo,
                c.id AS nro_convocatoria,
                u.documento_id AS identificacion,
                u.tipo_documento,
                u.nombres,
                u.apellidos,
                CONCAT(u.nombres, ' ', u.apellidos) AS aprendiz,
                u.correo,
                f.codigo AS codigo_ficha,
                f.programa_ficha AS programa_formacion,
                a.meses_otorgados AS meses_beneficio,
                a.fecha_inicio_real AS fecha_inicio_pago,
                DATE_ADD(a.fecha_inicio_real, INTERVAL a.meses_otorgados MONTH) AS fecha_fin_pago,
                a.estado AS estado_asignacion,
                i.banco,
                i.numero_cuenta
            FROM asignaciones a
            JOIN inscripciones i ON a.inscripcion_id = i.id
            JOIN usuarios u ON i.usuario_id = u.id
            JOIN fichas f ON i.ficha_id = f.id_ficha  
            JOIN convocatorias c ON i.convocatoria_id = c.id
            JOIN apoyos ap ON c.apoyo_id = ap.id_apoyo
            WHERE c.id = :idConvocatoria AND a.estado = 'ACTIVO'
            ORDER BY aprendiz ASC
        ");

        $stmt->bindParam(":idConvocatoria", $idConvocatoria, PDO::PARAM_INT);
        $stmt->execute();

        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null;

        return $resultados;
    }

    /*=============================================
    MOSTRAR BENEFICIARIO INDIVIDUAL POR ID DE INSCRIPCION
    =============================================*/
    static public function mdlMostrarBeneficiario($idInscripcion)
    {
        $stmt = Conexion::conectar()->prepare("
            SELECT 
                a.id AS asignacion_id,
                i.id AS inscripcion_id,
                ap.descripcion_apoyo AS tipo_apoyo,
                c.id AS nro_convocatoria,
                u.documento_id AS identificacion,
                u.tipo_documento,
                u.nombres,
                u.apellidos,
                CONCAT(u.nombres, ' ', u.apellidos) AS aprendiz,
                u.correo,
                f.codigo AS codigo_ficha,
                f.programa_ficha AS programa_formacion,
                a.meses_otorgados AS meses_beneficio,
                a.fecha_inicio_real AS fecha_inicio_pago,
                DATE_ADD(a.fecha_inicio_real, INTERVAL a.meses_otorgados MONTH) AS fecha_fin_pago,
                a.estado AS estado_asignacion,
                i.banco,
                i.numero_cuenta
            FROM asignaciones a
            JOIN inscripciones i ON a.inscripcion_id = i.id
            JOIN usuarios u ON i.usuario_id = u.id
            JOIN fichas f ON i.ficha_id = f.id_ficha  
            JOIN convocatorias c ON i.convocatoria_id = c.id
            JOIN apoyos ap ON c.apoyo_id = ap.id_apoyo
            WHERE i.id = :idInscripcion
        ");

        $stmt->bindParam(":idInscripcion", $idInscripcion, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = null;

        return $resultado;
    }


    /*=============================================
    LISTAR CONVOCATORIAS CON BENEFICIARIOS (ASIGNACIONES)
    =============================================*/
    static public function mdlListarConvocatoriasFinanciera()
    {
        $stmt = Conexion::conectar()->prepare("
            SELECT DISTINCT c.id, c.apoyo_id, ap.descripcion_apoyo, ap.apoyo_icono
            FROM convocatorias c
            JOIN apoyos ap ON c.apoyo_id = ap.id_apoyo
            JOIN inscripciones i ON i.convocatoria_id = c.id
            JOIN asignaciones a ON a.inscripcion_id = i.id
            WHERE c.estado_en_convocatoria = 'CERRADA'
            ORDER BY c.id DESC
        ");

        $stmt->execute();

        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null;

        return $resultados;
    }

    /*=============================================
    LISTAR PENDIENTES BANCARIOS (FINANCIERA)
    =============================================*/
    static public function mdlListarPendientesBancarios()
    {
        $stmt = Conexion::conectar()->prepare("
            SELECT 
                i.id AS inscripcion_id,
                u.documento_id AS identificacion,
                CONCAT(u.nombres, ' ', u.apellidos) AS aprendiz,
                f.programa_ficha AS programa_formacion,
                i.banco,
                i.numero_cuenta,
                i.documento_bancario_url,
                ap.descripcion_apoyo
            FROM inscripciones i
            JOIN usuarios u ON i.usuario_id = u.id
            JOIN fichas f ON i.ficha_id = f.id_ficha  
            JOIN convocatorias c ON i.convocatoria_id = c.id
            JOIN apoyos ap ON c.apoyo_id = ap.id_apoyo
            WHERE i.estado = 'DOCUMENTO_BANCARIO_CARGADO'
            ORDER BY i.fecha_postulacion DESC
        ");

        $stmt->execute();

        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null;

        return $resultados;
    }

    /*=============================================
    APROBAR DOCUMENTO BANCARIO Y CREAR ASIGNACION
    =============================================*/
    static public function mdlAprobarDocumentoBancario($idInscripcion, $mesesOtorgados, $fechaInicio)
    {
        $conexion = Conexion::conectar();

        try {
            $conexion->beginTransaction();

            // 1. Actualizar estado de inscripcion
            $stmt1 = $conexion->prepare("UPDATE inscripciones SET estado = 'APROBADO_FINANCIERA' WHERE id = :id");
            $stmt1->bindParam(":id", $idInscripcion, PDO::PARAM_INT);
            $stmt1->execute();

            // 2. Crear registro en asignaciones
            $stmt2 = $conexion->prepare("INSERT INTO asignaciones (inscripcion_id, meses_otorgados, fecha_inicio_real, estado) 
                                         VALUES (:inscripcion_id, :meses_otorgados, :fecha_inicio, 'ACTIVO')");
            $stmt2->bindParam(":inscripcion_id", $idInscripcion, PDO::PARAM_INT);
            $stmt2->bindParam(":meses_otorgados", $mesesOtorgados, PDO::PARAM_INT);
            $stmt2->bindParam(":fecha_inicio", $fechaInicio, PDO::PARAM_STR);
            $stmt2->execute();

            $conexion->commit();
            return "ok";
        } catch (Exception $e) {
            $conexion->rollBack();
            return "error";
        }
    }

    /*=============================================
    RECHAZAR DOCUMENTO BANCARIO
    =============================================*/
    static public function mdlRechazarDocumentoBancario($idInscripcion, $observacion)
    {
        $stmt = Conexion::conectar()->prepare("UPDATE inscripciones SET 
            estado = 'BENEFICIADO_PENDIENTE_DOC',
            observacion_rechazo_financiera = :observacion,
            banco = NULL,
            numero_cuenta = NULL,
            documento_bancario_url = NULL
            WHERE id = :id");

        $stmt->bindParam(":observacion", $observacion, PDO::PARAM_STR);
        $stmt->bindParam(":id", $idInscripcion, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
    }

    /*=============================================
    MOSTRAR 5 APRENDICES SELECCIONADOS POR CONVOCATORIA (PARA RELEVO)
    =============================================*/
    static public function mdlMostrarSeleccionadosRelevo($idConvocatoria)
    {
        $stmt = Conexion::conectar()->prepare("
            SELECT 
                i.id AS inscripcion_id,
                u.documento_id AS identificacion,
                CONCAT(u.nombres, ' ', u.apellidos) AS aprendiz,
                f.codigo AS codigo_ficha,
                f.programa_ficha AS programa_formacion,
                ap.descripcion_apoyo AS convocatoria_nombre
            FROM inscripciones i
            JOIN usuarios u ON i.usuario_id = u.id
            JOIN fichas f ON i.ficha_id = f.id_ficha
            JOIN convocatorias c ON i.convocatoria_id = c.id
            JOIN apoyos ap ON c.apoyo_id = ap.id_apoyo
            WHERE i.convocatoria_id = :idConvocatoria AND i.estado = 'SELECCIONADO'
            ORDER BY i.id DESC
            LIMIT 5
        ");

        $stmt->bindParam(":idConvocatoria", $idConvocatoria, PDO::PARAM_INT);
        $stmt->execute();

        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null;

        return $resultados;
    }

    /*=============================================
    BUSCAR APRENDIZ ENTRANTE SELECCIONADO POR DOCUMENTO Y CONVOCATORIA
    =============================================*/
    static public function mdlBuscarEntrantePorDocumento($documento, $idConvocatoria)
    {
        $stmt = Conexion::conectar()->prepare("
            SELECT 
                i.id AS inscripcion_id,
                u.documento_id AS identificacion,
                CONCAT(u.nombres, ' ', u.apellidos) AS aprendiz,
                f.codigo AS codigo_ficha,
                f.programa_ficha AS programa_formacion
            FROM inscripciones i
            JOIN usuarios u ON i.usuario_id = u.id
            JOIN fichas f ON i.ficha_id = f.id_ficha
            WHERE u.documento_id = :documento 
              AND i.convocatoria_id = :idConvocatoria 
              AND i.estado = 'SELECCIONADO'
            LIMIT 1
        ");

        $stmt->bindParam(":documento", $documento, PDO::PARAM_STR);
        $stmt->bindParam(":idConvocatoria", $idConvocatoria, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = null;

        return $resultado;
    }

    /*=============================================
    OBTENER DATOS DE CONTACTO DE UN APRENDIZ POR ID DE INSCRIPCION
    =============================================*/
    static public function mdlObtenerContactoAprendiz($idInscripcion)
    {
        $stmt = Conexion::conectar()->prepare("
            SELECT 
                CONCAT(u.nombres, ' ', u.apellidos) AS aprendiz,
                uc.telefono,
                uc.direccion,
                dep.nombre AS departamento,
                ciu.nombre AS ciudad
            FROM inscripciones i
            JOIN usuarios u ON i.usuario_id = u.id
            LEFT JOIN usuarios_contacto uc ON u.id = uc.usuario_id
            LEFT JOIN departamentos dep ON uc.codigo_dep = dep.codigo_dep
            LEFT JOIN ciudades ciu ON uc.codigo_ciu = ciu.codigo_ciu
            WHERE i.id = :idInscripcion
            LIMIT 1
        ");

        $stmt->bindParam(":idInscripcion", $idInscripcion, PDO::PARAM_INT);
        $stmt->execute();

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = null;

        return $resultado;
    }

    /*=============================================
    PROCESAR RELEVO DE APRENDIZ
    =============================================*/
    static public function mdlProcesarRelevo($idSaliente, $idEntrante, $idAsignacionSaliente, $motivo, $idGestor)
    {
        $conexion = Conexion::conectar();

        try {
            $conexion->beginTransaction();

            // 1. Obtener la asignación del aprendiz saliente
            $stmtAsig = $conexion->prepare("SELECT meses_otorgados, fecha_inicio_real FROM asignaciones WHERE id = :idAsignacion");
            $stmtAsig->bindParam(":idAsignacion", $idAsignacionSaliente, PDO::PARAM_INT);
            $stmtAsig->execute();
            $asignacionSaliente = $stmtAsig->fetch(PDO::FETCH_ASSOC);

            if (!$asignacionSaliente) {
                throw new Exception("No se encontró la asignación del aprendiz saliente.");
            }

            // 1.5 Validar si el aprendiz entrante ya tiene una asignación registrada
            $stmtCheckEntrante = $conexion->prepare("SELECT id FROM asignaciones WHERE inscripcion_id = :idEntrante");
            $stmtCheckEntrante->bindParam(":idEntrante", $idEntrante, PDO::PARAM_INT);
            $stmtCheckEntrante->execute();
            if ($stmtCheckEntrante->fetch()) {
                throw new Exception("El aprendiz entrante ya cuenta con una asignación registrada en el sistema.");
            }

            // 2. Calcular meses consumidos y restantes
            $fechaInicio = new DateTime($asignacionSaliente["fecha_inicio_real"]);
            $fechaHoy = new DateTime(); // fecha actual

            if ($fechaHoy < $fechaInicio) {
                $mesesConsumidos = 0;
            } else {
                $diferencia = $fechaInicio->diff($fechaHoy);
                $mesesConsumidos = ($diferencia->y * 12) + $diferencia->m;
                // Si hay días transcurridos adicionales, se considera el mes actual como iniciado/consumido
                if ($diferencia->d > 0) {
                    $mesesConsumidos += 1;
                }
            }

            $mesesOtorgados = (int)$asignacionSaliente["meses_otorgados"];
            $mesesConsumidos = min($mesesConsumidos, $mesesOtorgados);
            $mesesRestantes = $mesesOtorgados - $mesesConsumidos;

            // 3. Actualizar estado de la inscripción saliente a 'RETIRADO'
            $stmtUpdSaliente = $conexion->prepare("UPDATE inscripciones SET estado = 'RETIRADO' WHERE id = :idSaliente");
            $stmtUpdSaliente->bindParam(":idSaliente", $idSaliente, PDO::PARAM_INT);
            $stmtUpdSaliente->execute();

            // 4. Actualizar estado de la asignación saliente a 'INTERRUMPIDO'
            $stmtUpdAsigSaliente = $conexion->prepare("UPDATE asignaciones SET estado = 'INTERRUMPIDO' WHERE id = :idAsignacion");
            $stmtUpdAsigSaliente->bindParam(":idAsignacion", $idAsignacionSaliente, PDO::PARAM_INT);
            $stmtUpdAsigSaliente->execute();

            // 5. Actualizar estado de la inscripción entrante a 'BENEFICIADO'
            $stmtUpdEntrante = $conexion->prepare("UPDATE inscripciones SET estado = 'BENEFICIADO' WHERE id = :idEntrante");
            $stmtUpdEntrante->bindParam(":idEntrante", $idEntrante, PDO::PARAM_INT);
            $stmtUpdEntrante->execute();

            // 6. Crear asignación para el aprendiz entrante con los meses restantes
            $stmtNewAsig = $conexion->prepare("INSERT INTO asignaciones (inscripcion_id, meses_otorgados, fecha_inicio_real, estado) 
                                               VALUES (:inscripcion_entrante_id, :meses_otorgados, :fecha_inicio, 'ACTIVO')");
            
            $fechaInicioEntrante = date("Y-m-d");
            $stmtNewAsig->bindParam(":inscripcion_entrante_id", $idEntrante, PDO::PARAM_INT);
            $stmtNewAsig->bindParam(":meses_otorgados", $mesesRestantes, PDO::PARAM_INT);
            $stmtNewAsig->bindParam(":fecha_inicio", $fechaInicioEntrante, PDO::PARAM_STR);
            $stmtNewAsig->execute();

            // 7. Insertar registro en historial_relevos
            $stmtHist = $conexion->prepare("INSERT INTO historial_relevos (asignacion_saliente_id, inscripcion_entrante_id, gestor_id, motivo_salida, meses_consumidos, meses_restantes) 
                                            VALUES (:asignacion_saliente_id, :inscripcion_entrante_id, :gestor_id, :motivo_salida, :meses_consumidos, :meses_restantes)");
            
            $stmtHist->bindParam(":asignacion_saliente_id", $idAsignacionSaliente, PDO::PARAM_INT);
            $stmtHist->bindParam(":inscripcion_entrante_id", $idEntrante, PDO::PARAM_INT);
            $stmtHist->bindParam(":gestor_id", $idGestor, PDO::PARAM_INT);
            $stmtHist->bindParam(":motivo_salida", $motivo, PDO::PARAM_STR);
            $stmtHist->bindParam(":meses_consumidos", $mesesConsumidos, PDO::PARAM_INT);
            $stmtHist->bindParam(":meses_restantes", $mesesRestantes, PDO::PARAM_INT);
            $stmtHist->execute();

            $conexion->commit();
            return array("status" => "ok");

        } catch (Exception $e) {
            if ($conexion->inTransaction()) {
                $conexion->rollBack();
            }
            return array("status" => "error", "message" => $e->getMessage());
        }
    }

}
