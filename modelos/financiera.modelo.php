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
            WHERE c.id = :idConvocatoria
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

}
