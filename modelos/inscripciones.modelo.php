<?php

require_once "conexion.php";

class ModeloInscripciones {


    // ==============================================
    // LISTAR POSTULACIONES REALIZADAS POR UN APRENDIZ
    // ==============================================
    static public function mdlListarPostulacionesUsuario($tablaInscripciones, $tablaConvocatorias, $tablaApoyos, $usuarioId) {
        $stmt = Conexion::conectar()->prepare("SELECT i.*, c.fecha_inicio, c.fecha_fin, a.descripcion_apoyo, a.apoyo_icono 
                                               FROM $tablaInscripciones i 
                                               INNER JOIN $tablaConvocatorias c ON i.convocatoria_id = c.id 
                                               INNER JOIN $tablaApoyos a ON c.apoyo_id = a.id_apoyo 
                                               WHERE i.usuario_id = :usuario_id 
                                               ORDER BY i.fecha_postulacion DESC");
        $stmt->bindParam(":usuario_id", $usuarioId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ==============================================
    // MOSTRAR INSCRIPCION ESPECIFICA DE USUARIO Y CONVOCATORIA
    // ==============================================
    static public function mdlMostrarInscripcionUsuario($tabla, $usuarioId, $convocatoriaId) {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE usuario_id = :usuario_id AND convocatoria_id = :convocatoria_id");
        $stmt->bindParam(":usuario_id", $usuarioId, PDO::PARAM_INT);
        $stmt->bindParam(":convocatoria_id", $convocatoriaId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // ==============================================
    // MOSTRAR INSCRIPCION POR ID
    // ==============================================
    static public function mdlMostrarInscripcionPorId($tabla, $id) {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE id = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // ==============================================
    // CREAR INSCRIPCION INICIAL (ESTADO PENDIENTE)
    // ==============================================
    static public function mdlCrearInscripcion($tabla, $datos) {
        $conexion = Conexion::conectar();
        $stmt = $conexion->prepare("INSERT INTO $tabla (usuario_id, convocatoria_id, ficha_id, puntaje_total, estado) 
                                    VALUES (:usuario_id, :convocatoria_id, :ficha_id, 0.00, 'PENDIENTE')");
        
        $stmt->bindParam(":usuario_id", $datos["usuario_id"], PDO::PARAM_INT);
        $stmt->bindParam(":convocatoria_id", $datos["convocatoria_id"], PDO::PARAM_INT);
        $stmt->bindParam(":ficha_id", $datos["ficha_id"], PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $conexion->lastInsertId();
        } else {
            return false;
        }
    }

    // ==============================================
    // REGISTRAR / ACTUALIZAR DOCUMENTO DE INSCRIPCIÓN
    // ==============================================
    static public function mdlRegistrarDocumento($tabla, $datos) {
        // Verificar si ya existe este documento requisito para la inscripción
        $stmtCheck = Conexion::conectar()->prepare("SELECT id FROM $tabla WHERE inscripcion_id = :inscripcion_id AND nombre_doc = :nombre_doc");
        $stmtCheck->bindParam(":inscripcion_id", $datos["inscripcion_id"], PDO::PARAM_INT);
        $stmtCheck->bindParam(":nombre_doc", $datos["nombre_doc"], PDO::PARAM_STR);
        $stmtCheck->execute();
        $existe = $stmtCheck->fetch();

        if ($existe) {
            // Si ya existe, actualiza la URL de la copia, y restablece el estado a PENDIENTE y las observaciones a NULL
            $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET url_copia = :url_copia, estado = 'PENDIENTE', observacion_gestora = NULL WHERE id = :id");
            $stmt->bindParam(":url_copia", $datos["url_copia"], PDO::PARAM_STR);
            $stmt->bindParam(":id", $existe["id"], PDO::PARAM_INT);
        } else {
            // Si no existe, inserta un nuevo registro de documento asociado
            $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (inscripcion_id, nombre_doc, url_copia, estado, observacion_gestora) 
                                                   VALUES (:inscripcion_id, :nombre_doc, :url_copia, 'PENDIENTE', NULL)");
            $stmt->bindParam(":inscripcion_id", $datos["inscripcion_id"], PDO::PARAM_INT);
            $stmt->bindParam(":nombre_doc", $datos["nombre_doc"], PDO::PARAM_STR);
            $stmt->bindParam(":url_copia", $datos["url_copia"], PDO::PARAM_STR);
        }

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
    }

    // ==============================================
    // OBTENER DOCUMENTO POR ID
    // ==============================================
    static public function mdlObtenerDocumentoPorId($idDoc) {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM inscripcion_documentos WHERE id = :id");
        $stmt->bindParam(":id", $idDoc, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ==============================================
    // OBTENER TODOS LOS DOCUMENTOS ASOCIADOS A UNA INSCRIPCION
    // ==============================================
    static public function mdlListarDocumentosInscripcion($tabla, $inscripcionId) {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE inscripcion_id = :inscripcion_id");
        $stmt->bindParam(":inscripcion_id", $inscripcionId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ==============================================
    // LIMPIAR DOCUMENTO (ELIMINACIÓN LÓGICA DE ARCHIVO)
    // ==============================================
    static public function mdlLimpiarDocumento($tabla, $idDoc) {
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET url_copia = NULL, estado = 'PENDIENTE', observacion_gestora = NULL WHERE id = :id");
        $stmt->bindParam(":id", $idDoc, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
    }

    // ==============================================
    // OBTENER ESTADO Y SUBSANACION DE UNA INSCRIPCION
    // ==============================================
    static public function mdlObtenerEstadoYSubsanacion($inscripcionId) {
        $stmt = Conexion::conectar()->prepare("SELECT id, estado, subsanacion_consumida, convocatoria_id FROM inscripciones WHERE id = :id");
        $stmt->bindParam(":id", $inscripcionId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ==============================================
    // ACTUALIZAR ESTADO DE POSTULACION (CON SUBSANACION)
    // ==============================================
    static public function mdlActualizarEstadoPostulacion($inscripcionId, $estado, $subsanacionConsumida = null) {
        if ($subsanacionConsumida !== null) {
            $stmt = Conexion::conectar()->prepare("UPDATE inscripciones SET estado = :estado, subsanacion_consumida = :subsanacion, fecha_postulacion = NOW() WHERE id = :id");
            $stmt->bindParam(":estado", $estado, PDO::PARAM_STR);
            $stmt->bindParam(":subsanacion", $subsanacionConsumida, PDO::PARAM_INT);
        } else {
            $stmt = Conexion::conectar()->prepare("UPDATE inscripciones SET estado = :estado, fecha_postulacion = NOW() WHERE id = :id");
            $stmt->bindParam(":estado", $estado, PDO::PARAM_STR);
        }
        $stmt->bindParam(":id", $inscripcionId, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return "ok";
        }
        return "error";
    }

    // ==============================================
    // VALIDAR DOCUMENTOS CRITICOS ANTES DEL ENVIO
    // Retorna los nombres de items criticos faltantes o anomalos
    // ==============================================
    static public function mdlValidarDocumentosCriticos($inscripcionId, $convocatoriaId) {
        $stmt = Conexion::conectar()->prepare("
            SELECT b.nombre_item
            FROM baremo_config b
            LEFT JOIN inscripcion_documentos d 
                   ON d.nombre_doc = b.nombre_item 
                  AND d.inscripcion_id = :inscripcion_id
            WHERE b.convocatoria_id = :convocatoria_id
              AND b.es_critico = 1
              AND (
                    d.id IS NULL
                    OR d.url_copia IS NULL
                    OR d.estado = 'PARA_CORREGIR'
                  )
        ");
        $stmt->bindParam(":inscripcion_id", $inscripcionId, PDO::PARAM_INT);
        $stmt->bindParam(":convocatoria_id", $convocatoriaId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==============================================
    // CONTAR DOCUMENTOS ANOMALOS (PARA_CORREGIR O NULL)
    // ==============================================
    static public function mdlContarDocumentosAnomalos($inscripcionId) {
        $stmt = Conexion::conectar()->prepare("SELECT COUNT(*) as total FROM inscripcion_documentos WHERE inscripcion_id = :id AND (estado = 'PARA_CORREGIR' OR url_copia IS NULL)");
        $stmt->bindParam(":id", $inscripcionId, PDO::PARAM_INT);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ? (int)$res["total"] : 0;
    }

    // ==============================================
    // ACTUALIZAR DATOS BANCARIOS
    // ==============================================
    static public function mdlActualizarDatosBancarios($tabla, $datos) {
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET 
            banco = :banco, 
            numero_cuenta = :numero_cuenta, 
            documento_bancario_url = :documento_bancario_url, 
            estado = :estado 
            WHERE id = :id");
        
        $stmt->bindParam(":banco", $datos["banco"], PDO::PARAM_STR);
        $stmt->bindParam(":numero_cuenta", $datos["numero_cuenta"], PDO::PARAM_STR);
        $stmt->bindParam(":documento_bancario_url", $datos["documento_bancario_url"], PDO::PARAM_STR);
        $stmt->bindParam(":estado", $datos["estado"], PDO::PARAM_STR);
        $stmt->bindParam(":id", $datos["id_inscripcion"], PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
    }

}
