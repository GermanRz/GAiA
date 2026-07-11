<?php

require_once "conexion.php";

class ModeloVerificacion {

    // ==============================================
    // LISTAR APRENDICES INSCRITOS A UNA CONVOCATORIA
    // ==============================================
    static public function mdlListarInscritosConvocatoria($convocatoriaId) {
        $stmt = Conexion::conectar()->prepare("SELECT i.id as inscripcion_id, i.usuario_id, i.puntaje_total, i.estado as inscripcion_estado, i.fecha_postulacion,
                                                      u.documento_id, u.nombres, u.apellidos 
                                               FROM inscripciones i 
                                               INNER JOIN usuarios u ON i.usuario_id = u.id 
                                               WHERE i.convocatoria_id = :convocatoria_id
                                               AND i.estado != 'PENDIENTE'
                                               ORDER BY u.apellidos ASC, u.nombres ASC");
        $stmt->bindParam(":convocatoria_id", $convocatoriaId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==============================================
    // OBTENER HISTORIAL DE REVISIONES DE UN DOCUMENTO
    // ==============================================
    static public function mdlObtenerHistorialDocumento($documentoId) {
        $stmt = Conexion::conectar()->prepare("SELECT h.*, u.nombres as gestora_nombres, u.apellidos as gestora_apellidos 
                                               FROM historial_revisiones_docs h
                                               LEFT JOIN usuarios u ON h.evaluador_id = u.id
                                               WHERE h.documento_id = :documento_id 
                                               ORDER BY h.fecha_revision DESC");
        $stmt->bindParam(":documento_id", $documentoId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==============================================
    // ACTUALIZAR ESTADO Y OBSERVACIÓN DEL REQUISITO
    // ==============================================
    static public function mdlActualizarEstadoDocumento($documentoId, $estado, $observacion, $evaluadorId) {
        $stmt = Conexion::conectar()->prepare("UPDATE inscripcion_documentos 
                                               SET estado = :estado, observacion_gestora = :observacion, evaluador_id = :evaluador_id 
                                               WHERE id = :id");
        $stmt->bindParam(":estado", $estado, PDO::PARAM_STR);
        $stmt->bindParam(":observacion", $observacion, PDO::PARAM_STR);
        $stmt->bindParam(":evaluador_id", $evaluadorId, PDO::PARAM_INT);
        $stmt->bindParam(":id", $documentoId, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
    }

    // ==============================================
    // REGISTRAR HISTORIAL DE REVISIÓN (INTENTOS FALLIDOS)
    // ==============================================
    static public function mdlRegistrarHistorialRevision($documentoId, $motivo, $urlArchivo, $evaluadorId) {
        $stmt = Conexion::conectar()->prepare("INSERT INTO historial_revisiones_docs (documento_id, motivo_rechazo, url_archivo_rechazado, evaluador_id) 
                                               VALUES (:documento_id, :motivo, :url_archivo_rechazado, :evaluador_id)");
        $stmt->bindParam(":documento_id", $documentoId, PDO::PARAM_INT);
        $stmt->bindParam(":motivo", $motivo, PDO::PARAM_STR);
        $stmt->bindParam(":url_archivo_rechazado", $urlArchivo, PDO::PARAM_STR);
        $stmt->bindParam(":evaluador_id", $evaluadorId, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
    }

    // ==============================================
    // INSERTAR O ACTUALIZAR EVALUACIÓN DE PUNTAJE BAREMO
    // ==============================================
    static public function mdlRegistrarEvaluacion($inscripcionId, $baremoId, $puntajeAsignado) {
        $stmtCheck = Conexion::conectar()->prepare("SELECT id FROM inscripcion_evaluacion 
                                                    WHERE inscripcion_id = :inscripcion_id AND baremo_id = :baremo_id");
        $stmtCheck->bindParam(":inscripcion_id", $inscripcionId, PDO::PARAM_INT);
        $stmtCheck->bindParam(":baremo_id", $baremoId, PDO::PARAM_INT);
        $stmtCheck->execute();
        $existe = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($existe) {
            $stmt = Conexion::conectar()->prepare("UPDATE inscripcion_evaluacion 
                                                   SET puntaje_asignado = :puntaje 
                                                   WHERE id = :id");
            $stmt->bindParam(":puntaje", $puntajeAsignado, PDO::PARAM_STR);
            $stmt->bindParam(":id", $existe["id"], PDO::PARAM_INT);
        } else {
            $stmt = Conexion::conectar()->prepare("INSERT INTO inscripcion_evaluacion (inscripcion_id, baremo_id, puntaje_asignado) 
                                                   VALUES (:inscripcion_id, :baremo_id, :puntaje)");
            $stmt->bindParam(":inscripcion_id", $inscripcionId, PDO::PARAM_INT);
            $stmt->bindParam(":baremo_id", $baremoId, PDO::PARAM_INT);
            $stmt->bindParam(":puntaje", $puntajeAsignado, PDO::PARAM_STR);
        }

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
    }

    // ==============================================
    // ELIMINAR EVALUACIÓN DE PUNTAJE (PARA CORRECCIONES / RECHAZO)
    // ==============================================
    static public function mdlEliminarEvaluacionDocumento($inscripcionId, $baremoId) {
        $stmt = Conexion::conectar()->prepare("DELETE FROM inscripcion_evaluacion 
                                               WHERE inscripcion_id = :inscripcion_id AND baremo_id = :baremo_id");
        $stmt->bindParam(":inscripcion_id", $inscripcionId, PDO::PARAM_INT);
        $stmt->bindParam(":baremo_id", $baremoId, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
    }

    // ==============================================
    // RECALCULAR Y ACTUALIZAR EL PUNTAJE TOTAL DE LA INSCRIPCIÓN
    // ==============================================
    static public function mdlActualizarPuntajeInscripcion($inscripcionId) {
        // 1. Obtener la suma total de puntos aprobados
        $stmtSum = Conexion::conectar()->prepare("SELECT SUM(puntaje_asignado) as total 
                                                  FROM inscripcion_evaluacion 
                                                  WHERE inscripcion_id = :inscripcion_id");
        $stmtSum->bindParam(":inscripcion_id", $inscripcionId, PDO::PARAM_INT);
        $stmtSum->execute();
        $res = $stmtSum->fetch(PDO::FETCH_ASSOC);
        $total = isset($res["total"]) ? $res["total"] : 0.00;

        // 2. Actualizar el total en la tabla principal de inscripciones
        $stmtUpdate = Conexion::conectar()->prepare("UPDATE inscripciones SET puntaje_total = :total WHERE id = :id");
        $stmtUpdate->bindParam(":total", $total, PDO::PARAM_STR);
        $stmtUpdate->bindParam(":id", $inscripcionId, PDO::PARAM_INT);
        
        if ($stmtUpdate->execute()) {
            return "ok";
        } else {
            return "error";
        }
    }

    // ==============================================
    // OBTENER UN DOCUMENTO REQUISITO POR SU ID
    // ==============================================
    static public function mdlObtenerDocumentoPorId($documentoId) {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM inscripcion_documentos WHERE id = :id");
        $stmt->bindParam(":id", $documentoId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ==============================================
    // OBTENER UNA INSCRIPCIÓN POR SU ID
    // ==============================================
    static public function mdlObtenerInscripcionPorId($inscripcionId) {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM inscripciones WHERE id = :id");
        $stmt->bindParam(":id", $inscripcionId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ==============================================
    // OBTENER REQUISITO DE BAREMO POR CONVOCATORIA Y NOMBRE DE DOCUMENTO
    // ==============================================
    static public function mdlObtenerBaremoRequisito($convocatoriaId, $nombreItem) {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM baremo_config WHERE convocatoria_id = :convocatoria_id AND nombre_item = :nombre_item");
        $stmt->bindParam(":convocatoria_id", $convocatoriaId, PDO::PARAM_INT);
        $stmt->bindParam(":nombre_item", $nombreItem, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}
