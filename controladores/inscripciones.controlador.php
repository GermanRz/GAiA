<?php

class ControladorInscripciones {


    // ==============================================
    // LISTAR POSTULACIONES REALIZADAS POR EL APRENDIZ
    // ==============================================
    static public function ctrListarPostulacionesUsuario() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION["id"])) {
            $tablaInscripciones = "inscripciones";
            $tablaConvocatorias = "convocatorias";
            $tablaApoyos = "apoyos";
            $usuarioId = $_SESSION["id"];

            $respuesta = ModeloInscripciones::mdlListarPostulacionesUsuario($tablaInscripciones, $tablaConvocatorias, $tablaApoyos, $usuarioId);
            return $respuesta;
        }

        return array();
    }

    // ==============================================
    // CARGAR UN ARCHIVO E INTEGRAR EN BASE DE DATOS
    // ==============================================
    static public function ctrSubirDocumento($file, $convocatoriaId, $nombreDoc) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION["id"])) {
            return array("status" => "error", "message" => "Sesión de usuario no válida.");
        }

        $usuarioId = $_SESSION["id"];
        $cedula = $_SESSION["documento"];
        $nombres = $_SESSION["nombres"];
        $apellidos = $_SESSION["apellidos"];
        
        // Ficha ID de respaldo en caso de que esté vacía
        $fichaId = isset($_SESSION["ficha_id"]) && $_SESSION["ficha_id"] != "" ? $_SESSION["ficha_id"] : null;
        if ($fichaId === null) {
            // Consulta de respaldo por base de datos
            $usuario = ModeloUsuarios::mdlMostrarUsuarios("usuarios", "id", $usuarioId);
            $fichaId = ($usuario && isset($usuario["ficha_id"])) ? $usuario["ficha_id"] : 1; // Default a 1 si no tiene asignada
        }

        // --- 1. VALIDACIÓN DEL ARCHIVO ---
        
        // Validar que sea PDF
        $ext = pathinfo($file["name"], PATHINFO_EXTENSION);
        if (strtolower($ext) != "pdf" || $file["type"] != "application/pdf") {
            return array("status" => "error", "message" => "Formato no permitido. Solo se admiten documentos PDF.");
        }

        // Validar tamaño (máximo 5MB = 5 * 1024 * 1024 bytes)
        $maxSize = 5 * 1024 * 1024;
        if ($file["size"] > $maxSize) {
            return array("status" => "error", "message" => "El archivo supera el tamaño máximo permitido de 5 MB.");
        }

        // --- 2. PREPARAR CARPETAS Y RUTAS ---
        
        // Carpeta raíz en el proyecto
        $raizProyecto = dirname(__DIR__); // C:\xampp\htdocs\GAiA
        $carpetaDocumentos = $raizProyecto . "/documentos";
        $carpetaCedula = $carpetaDocumentos . "/" . $cedula;

        // Crear carpeta general si no existe
        if (!file_exists($carpetaDocumentos)) {
            mkdir($carpetaDocumentos, 0777, true);
        }

        // Crear carpeta de la cédula del aprendiz si no existe
        if (!file_exists($carpetaCedula)) {
            mkdir($carpetaCedula, 0777, true);
        }

        // --- 3. SANITIZAR Y RENOMBRAR ARCHIVO ---
        
        // Sanitizar el nombre del requisito
        $nombreDocSanitizado = self::sanitizarCadena($nombreDoc);
        
        // Sanitizar el nombre completo del aprendiz
        $nombreAprendizCompleto = self::sanitizarCadena($nombres . " " . $apellidos);

        // Armar nombre del archivo: nombre_documento_cedula_nombreAprendiz.pdf
        $nombreArchivo = $nombreDocSanitizado . "_" . $cedula . "_" . $nombreAprendizCompleto . ".pdf";
        $rutaCompletaDestino = $carpetaCedula . "/" . $nombreArchivo;

        // Ruta relativa que se guardará en la base de datos
        $rutaDB = "documentos/" . $cedula . "/" . $nombreArchivo;

        // --- 4. PERSISTENCIA DE INSCRIPCIÓN ---
        
        // Verificar si el aprendiz ya tiene una inscripción a esta convocatoria
        $inscripcion = ModeloInscripciones::mdlMostrarInscripcionUsuario("inscripciones", $usuarioId, $convocatoriaId);

        if ($inscripcion) {
            $inscripcionId = $inscripcion["id"];

            // --- VALIDACIÓN DE ESTADO ---
            $estado = $inscripcion["estado"];
            $subsanacion = isset($inscripcion["subsanacion_consumida"]) ? $inscripcion["subsanacion_consumida"] : 0;

            if ($estado === "EN_REVISION") {
                return array("status" => "error", "message" => "No se pueden modificar documentos mientras la postulación esté en revisión.");
            }

            if ($estado === "DEVUELTA" && $subsanacion == 1) {
                return array("status" => "error", "message" => "La oportunidad de subsanación ya fue consumida. No puedes modificar documentos.");
            }

            if ($estado === "DEVUELTA" && $subsanacion == 0) {
                // Solo permitir modificar documentos marcados como PARA_CORREGIR
                $stmtDoc = Conexion::conectar()->prepare("SELECT id, estado FROM inscripcion_documentos WHERE inscripcion_id = :iid AND nombre_doc = :ndoc");
                $stmtDoc->bindParam(":iid", $inscripcionId, PDO::PARAM_INT);
                $stmtDoc->bindParam(":ndoc", $nombreDoc, PDO::PARAM_STR);
                $stmtDoc->execute();
                $docExistente = $stmtDoc->fetch(PDO::FETCH_ASSOC);

                if ($docExistente && $docExistente["estado"] !== "PARA_CORREGIR") {
                    return array("status" => "error", "message" => "Solo puedes reemplazar documentos marcados para corrección.");
                }
            }
        } else {
            // Crear inscripción inicial en estado PENDIENTE
            $datosInscripcion = array(
                "usuario_id" => $usuarioId,
                "convocatoria_id" => $convocatoriaId,
                "ficha_id" => $fichaId
            );
            $inscripcionId = ModeloInscripciones::mdlCrearInscripcion("inscripciones", $datosInscripcion);
            
            if (!$inscripcionId) {
                return array("status" => "error", "message" => "Error al registrar la postulación en la base de datos.");
            }
        }

        // --- 5. ALMACENAR ARCHIVO FÍSICAMENTE ---
        if (move_uploaded_file($file["tmp_name"], $rutaCompletaDestino)) {
            
            // --- 6. REGISTRAR DOCUMENTO EN BASE DE DATOS ---
            $datosDoc = array(
                "inscripcion_id" => $inscripcionId,
                "nombre_doc" => $nombreDoc,
                "url_copia" => $rutaDB
            );

            $registroDB = ModeloInscripciones::mdlRegistrarDocumento("inscripcion_documentos", $datosDoc);

            if ($registroDB == "ok") {
                return array(
                    "status" => "success", 
                    "message" => "Archivo subido y registrado correctamente.",
                    "url" => $rutaDB,
                    "nombre_archivo" => $nombreArchivo,
                    "inscripcion_id" => $inscripcionId
                );
            } else {
                // Eliminar archivo físico en caso de fallo en BD
                if (file_exists($rutaCompletaDestino)) {
                    unlink($rutaCompletaDestino);
                }
                return array("status" => "error", "message" => "Error al guardar la ruta del archivo en la base de datos.");
            }

        } else {
            return array("status" => "error", "message" => "Error al mover el archivo al servidor. Verifica permisos de escritura.");
        }
    }

    // ==============================================
    // LIMPIAR DOCUMENTO (ELIMINACIÓN DE ARCHIVO)
    // ==============================================
    static public function ctrEliminarDocumento($idDoc, $rutaArchivo) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION["id"])) {
            return "error";
        }

        // Obtener inscripción asociada al documento para validar estado
        $doc = ModeloInscripciones::mdlObtenerDocumentoPorId($idDoc);
        if (!$doc) {
            return "error";
        }

        $inscripcion = ModeloInscripciones::mdlMostrarInscripcionPorId("inscripciones", $doc["inscripcion_id"]);
        if (!$inscripcion) {
            return "error";
        }

        $estado = $inscripcion["estado"];

        // Bloquear eliminación si la postulación está en revisión
        if ($estado === "EN_REVISION") {
            return "error";
        }

        // Bloquear eliminación si la postulación está devuelta (solo reemplazo permitido)
        if ($estado === "DEVUELTA") {
            return "error";
        }

        $raizProyecto = dirname(__DIR__);
        $rutaFisica = $raizProyecto . "/" . $rutaArchivo;

        // 1. Eliminar archivo físico si existe
        if (file_exists($rutaFisica)) {
            unlink($rutaFisica);
        }

        // 2. Limpiar registros en la base de datos (volver URL = NULL)
        $respuesta = ModeloInscripciones::mdlLimpiarDocumento("inscripcion_documentos", $idDoc);
        return $respuesta;
    }

    // ==============================================
    // ENVIAR POSTULACION (PENDIENTE -> EN_REVISION / DEVUELTA -> EN_REVISION)
    // ==============================================
    static public function ctrEnviarPostulacion($inscripcionId) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION["id"])) {
            return array("status" => "error", "message" => "Sesión de usuario no válida.");
        }

        // 1. Obtener estado actual y convocatoria
        $data = ModeloInscripciones::mdlObtenerEstadoYSubsanacion($inscripcionId);
        if (!$data) {
            return array("status" => "error", "message" => "La postulación no existe.");
        }

        $estadoActual = $data["estado"];
        $convocatoriaId = $data["convocatoria_id"];
        $subsanacionConsumida = (int)$data["subsanacion_consumida"];

        // 2. Validar estado transitable
        if ($estadoActual === "EN_REVISION") {
            return array("status" => "error", "message" => "La postulación ya está en revisión.");
        }

        if ($estadoActual === "DEVUELTA" && $subsanacionConsumida === 1) {
            return array("status" => "error", "message" => "La oportunidad de subsanación ya fue consumida.");
        }

        if ($estadoActual !== "PENDIENTE" && $estadoActual !== "DEVUELTA") {
            return array("status" => "error", "message" => "No se puede enviar la postulación en el estado actual.");
        }

        // 3. Validar documentos críticos (siempre, tanto en envío inicial como en reenvío)
        $criticosFaltantes = ModeloInscripciones::mdlValidarDocumentosCriticos($inscripcionId, $convocatoriaId);
        if (count($criticosFaltantes) > 0) {
            $nombres = array_column($criticosFaltantes, "nombre_item");
            return array(
                "status" => "error",
                "message" => "No puedes enviar la postulación. Los siguientes documentos críticos están pendientes o requieren corrección: " . implode(", ", $nombres) . "."
            );
        }

        // 4. Si es reenvío (DEVUELTA), validar que no queden documentos anómalos
        if ($estadoActual === "DEVUELTA") {
            $anomalos = ModeloInscripciones::mdlContarDocumentosAnomalos($inscripcionId);
            if ($anomalos > 0) {
                return array("status" => "error", "message" => "Aún tienes documentos sin archivo o marcados para corrección. Corrige todos antes de reenviar.");
            }
        }

        // 5. Ejecutar transición de estado
        $consumir = ($estadoActual === "DEVUELTA") ? 1 : null;
        $resultado = ModeloInscripciones::mdlActualizarEstadoPostulacion($inscripcionId, "EN_REVISION", $consumir);

        if ($resultado === "ok") {
            $msg = ($estadoActual === "DEVUELTA")
                ? "Postulación reenviada correctamente. Tu oportunidad de subsanación ha sido consumida."
                : "Postulación enviada a revisión correctamente.";
            return array("status" => "success", "message" => $msg, "subsanacion_consumida" => $consumir);
        }

        return array("status" => "error", "message" => "Error al actualizar el estado de la postulación.");
    }

    // ==============================================
    // AUXILIAR: SANITIZAR CADENAS DE TEXTO
    // ==============================================
    static private function sanitizarCadena($cadena) {
        // Reemplazar caracteres especiales y acentos
        $originales = array('Á', 'É', 'Í', 'Ó', 'Ú', 'á', 'é', 'í', 'ó', 'ú', 'Ñ', 'ñ', 'ü', 'Ü');
        $reemplazos = array('A', 'E', 'I', 'O', 'U', 'a', 'e', 'i', 'o', 'u', 'N', 'n', 'u', 'U');
        $cadena = str_replace($originales, $reemplazos, $cadena);
        
        // Convertir a minúsculas
        $cadena = strtolower($cadena);
        
        // Reemplazar todo lo que no sea letras, números o espacios por guiones bajos
        $cadena = preg_replace('/[^a-z0-9\s]/', '', $cadena);
        
        // Reemplazar múltiples espacios por un guión bajo único
        $cadena = preg_replace('/\s+/', '_', trim($cadena));
        
        return $cadena;
    }

    // ==============================================
    // SUBIR CERTIFICACIÓN BANCARIA (DOBLE PERFIL)
    // ==============================================
    static public function ctrSubirCertificacionBancaria($idInscripcion, $banco, $cuenta, $file) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION["id"]) || !isset($_SESSION["documento"])) {
            return array("status" => "error", "message" => "Sesión de usuario no válida.");
        }

        $cedula = $_SESSION["documento"];

        // --- 1. VALIDACIÓN DEL ARCHIVO ---
        $ext = pathinfo($file["name"], PATHINFO_EXTENSION);
        if (strtolower($ext) != "pdf" || $file["type"] != "application/pdf") {
            return array("status" => "error", "message" => "Formato no permitido. Solo se admiten documentos PDF.");
        }

        $maxSize = 2 * 1024 * 1024; // 2MB
        if ($file["size"] > $maxSize) {
            return array("status" => "error", "message" => "El archivo supera el tamaño máximo permitido de 2 MB.");
        }

        // --- 2. PREPARAR CARPETAS Y RUTAS ---
        $raizProyecto = dirname(__DIR__); // C:\xampp\htdocs\GAiA
        $carpetaCedula = $raizProyecto . "/documentos/" . $cedula;

        if (!file_exists($carpetaCedula)) {
            mkdir($carpetaCedula, 0777, true);
        }

        // Obtener convocatoria_id para el nombre del archivo
        $datosInscripcion = ModeloInscripciones::mdlMostrarInscripcionPorId("inscripciones", $idInscripcion);
        $convocatoriaId = $datosInscripcion ? $datosInscripcion["convocatoria_id"] : "0";

        // --- 3. SANITIZAR Y RENOMBRAR ARCHIVO ---
        $nombreArchivo = "certificado_bancario_" . $cedula . "_" . $convocatoriaId . ".pdf";
        $rutaCompletaDestino = $carpetaCedula . "/" . $nombreArchivo;
        $rutaDB = "documentos/" . $cedula . "/" . $nombreArchivo;

        // --- 4. MOVER ARCHIVO ---
        if (move_uploaded_file($file["tmp_name"], $rutaCompletaDestino)) {
            
            // --- 5. ACTUALIZAR BASE DE DATOS ---
            $datosBD = array(
                "id_inscripcion" => $idInscripcion,
                "banco" => $banco,
                "numero_cuenta" => $cuenta,
                "documento_bancario_url" => $rutaDB,
                "estado" => "DOCUMENTO_BANCARIO_CARGADO"
            );

            $registroDB = ModeloInscripciones::mdlActualizarDatosBancarios("inscripciones", $datosBD);

            if ($registroDB == "ok") {
                return array(
                    "status" => "success", 
                    "message" => "Certificación bancaria cargada y en revisión.",
                );
            } else {
                if (file_exists($rutaCompletaDestino)) {
                    unlink($rutaCompletaDestino);
                }
                return array("status" => "error", "message" => "Error al actualizar los datos en la base de datos.");
            }

        } else {
            return array("status" => "error", "message" => "Error al mover el archivo al servidor.");
        }
    }

}
