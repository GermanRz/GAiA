<?php

class ControladorVerificacion {

    // ==============================================
    // LISTAR APRENDICES Y EL ESTADO DETALLADO DE SUS DOCUMENTOS
    // ==============================================
    static public function ctrListarPostulacionesGestor($convocatoriaId) {
        // 1. Obtener aprendices inscritos a esta convocatoria
        $inscritos = ModeloVerificacion::mdlListarInscritosConvocatoria($convocatoriaId);
        
        // 2. Obtener requisitos del baremo para esta convocatoria
        $baremo = ControladorConvocatorias::ctrMostrarBaremo($convocatoriaId);

        $resultado = array();

        foreach ($inscritos as $ins) {
            $fila = array(
                "inscripcion_id" => $ins["inscripcion_id"],
                "usuario_id" => $ins["usuario_id"],
                "documento_id" => $ins["documento_id"],
                "nombres" => $ins["nombres"],
                "apellidos" => $ins["apellidos"],
                "puntaje_total" => parseFloat($ins["puntaje_total"]),
                "inscripcion_estado" => $ins["inscripcion_estado"],
                "fecha_postulacion" => $ins["fecha_postulacion"],
                "documentos" => array()
            );

            // Buscar documentos subidos de esta inscripción
            $docsSubidos = ModeloInscripciones::mdlListarDocumentosInscripcion("inscripcion_documentos", $ins["inscripcion_id"]);

            // Mapear cada requisito del baremo al documento subido (o marcar como pendiente)
            foreach ($baremo as $req) {
                $docMatch = null;
                foreach ($docsSubidos as $doc) {
                    if ($doc["nombre_doc"] == $req["nombre_item"]) {
                        $docMatch = $doc;
                        break;
                    }
                }

                if ($docMatch) {
                    // Contar intentos previos en el historial
                    $historial = ModeloVerificacion::mdlObtenerHistorialDocumento($docMatch["id"]);
                    $intentos = count($historial);

                    $fila["documentos"][] = array(
                        "baremo_id" => $req["id"],
                        "nombre_doc" => $req["nombre_item"],
                        "es_critico" => $req["es_critico"],
                        "puntaje_valor" => $req["puntaje_valor"],
                        "documento_id_db" => $docMatch["id"],
                        "url_copia" => $docMatch["url_copia"],
                        "estado" => $docMatch["estado"],
                        "observacion_gestora" => $docMatch["observacion_gestora"],
                        "intentos_fallidos" => $intentos,
                        "historial" => $historial
                    );
                } else {
                    // No se ha subido aún
                    $fila["documentos"][] = array(
                        "baremo_id" => $req["id"],
                        "nombre_doc" => $req["nombre_item"],
                        "es_critico" => $req["es_critico"],
                        "puntaje_valor" => $req["puntaje_valor"],
                        "documento_id_db" => null,
                        "url_copia" => null,
                        "estado" => "PENDIENTE",
                        "observacion_gestora" => null,
                        "intentos_fallidos" => 0,
                        "historial" => array()
                    );
                }
            }

            $resultado[] = $fila;
        }

        return $resultado;
    }

    // ==============================================
    // EVALUAR UN DOCUMENTO (APROBAR, PARA CORREGIR, RECHAZADO)
    // ==============================================
    static public function ctrEvaluarDocumento($documentoId, $estado, $observacion, $evaluadorId) {
        
        // 1. Obtener datos del documento actual
        $documento = ModeloVerificacion::mdlObtenerDocumentoPorId($documentoId);
        if (!$documento) {
            return array("status" => "error", "message" => "El documento especificado no existe.");
        }

        // 2. Obtener datos de la inscripción asociada
        $inscripcion = ModeloVerificacion::mdlObtenerInscripcionPorId($documento["inscripcion_id"]);
        if (!$inscripcion) {
            return array("status" => "error", "message" => "La postulación asociada no existe.");
        }

        $inscripcionId = $inscripcion["id"];
        $convocatoriaId = $inscripcion["convocatoria_id"];

        // 3. Obtener el baremo del requisito
        $baremo = ModeloVerificacion::mdlObtenerBaremoRequisito($convocatoriaId, $documento["nombre_doc"]);
        if (!$baremo) {
            return array("status" => "error", "message" => "No se encontró la regla de evaluación del baremo.");
        }

        $baremoId = $baremo["id"];
        $puntajeValor = $baremo["puntaje_valor"];

        // 4. Validar reglas de "un intento de corrección"
        $historial = ModeloVerificacion::mdlObtenerHistorialDocumento($documentoId);
        $intentosPrevios = count($historial);

        if ($estado === "PARA_CORREGIR" && $intentosPrevios >= 1) {
            return array("status" => "error", "message" => "El aprendiz ya utilizó su intento de corrección para este documento. Debe ser aprobado o rechazado permanentemente.");
        }

        // 5. Procesar según el nuevo estado de evaluación
        if ($estado === "APROBADO") {
            // Guardar puntaje en la tabla de evaluación
            $evalRes = ModeloVerificacion::mdlRegistrarEvaluacion($inscripcionId, $baremoId, $puntajeValor);
            if ($evalRes != "ok") {
                return array("status" => "error", "message" => "Error al registrar el puntaje del baremo.");
            }

            // Actualizar estado del documento
            $docRes = ModeloVerificacion::mdlActualizarEstadoDocumento($documentoId, "APROBADO", null, $evaluadorId);
        } 
        else if ($estado === "PARA_CORREGIR") {
            // Eliminar puntaje si existía previamente
            ModeloVerificacion::mdlEliminarEvaluacionDocumento($inscripcionId, $baremoId);

            // Registrar revisión en el historial
            ModeloVerificacion::mdlRegistrarHistorialRevision($documentoId, $observacion, $documento["url_copia"], $evaluadorId);

            // Actualizar estado del documento
            $docRes = ModeloVerificacion::mdlActualizarEstadoDocumento($documentoId, "PARA_CORREGIR", $observacion, $evaluadorId);
        } 
        else if ($estado === "RECHAZADO") {
            // Eliminar puntaje si existía
            ModeloVerificacion::mdlEliminarEvaluacionDocumento($inscripcionId, $baremoId);

            // Registrar revisión definitiva en el historial
            ModeloVerificacion::mdlRegistrarHistorialRevision($documentoId, "[RECHAZO DEFINITIVO] " . $observacion, $documento["url_copia"], $evaluadorId);

            // Actualizar estado del documento
            $docRes = ModeloVerificacion::mdlActualizarEstadoDocumento($documentoId, "RECHAZADO", $observacion, $evaluadorId);
        } 
        else {
            return array("status" => "error", "message" => "Estado de evaluación inválido.");
        }

        if ($docRes === "ok") {
            // 6. Recalcular y actualizar puntaje total de la postulación
            ModeloVerificacion::mdlActualizarPuntajeInscripcion($inscripcionId);

            // 7. Evaluar el estado global de la inscripción basado en todos sus documentos
            self::actualizarEstadoGlobalInscripcion($inscripcionId);

            return array(
                "status" => "success", 
                "message" => "Documento evaluado correctamente y puntaje actualizado."
            );
        } else {
            return array("status" => "error", "message" => "Error al guardar el estado del documento.");
        }
    }

    // ==============================================
    // EVALUAR ESTADO GLOBAL DE LA INSCRIPCION
    // ==============================================
    static private function actualizarEstadoGlobalInscripcion($inscripcionId) {
        $docs = ModeloInscripciones::mdlListarDocumentosInscripcion("inscripcion_documentos", $inscripcionId);
        
        $todosEvaluados = true;
        $tieneParaCorregir = false;

        foreach ($docs as $doc) {
            if ($doc["estado"] === "PARA_CORREGIR") {
                $tieneParaCorregir = true;
            }
            if ($doc["estado"] === "PENDIENTE" || $doc["estado"] === "EN_REVISION") {
                $todosEvaluados = false;
            }
        }

        // Si hay al menos un documento para corregir, la inscripción pasa a DEVUELTA
        if ($tieneParaCorregir) {
            $nuevoEstado = "DEVUELTA";
        } 
        // Si todos los documentos están evaluados (APROBADO o RECHAZADO, y no hay pendientes ni en revisión ni para corregir)
        else if ($todosEvaluados) {
            $nuevoEstado = "REVISADO";
        } 
        // En caso contrario, sigue EN_REVISION (mientras se revisan otros docs)
        else {
            $nuevoEstado = "EN_REVISION";
        }

        $datosBD = array(
            "id_inscripcion" => $inscripcionId,
            "estado" => $nuevoEstado
        );
        ModeloInscripciones::mdlActualizarEstado("inscripciones", $datosBD);
    }

}

// Auxiliar para parsear puntajes decimales a float de PHP
if (!function_exists('parseFloat')) {
    function parseFloat($val) {
        return floatval($val);
    }
}
