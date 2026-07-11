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
        //si el nombre del archivo es muy largo este debe truncarse a 20 caracteres
        if (strlen($nombreDocSanitizado) > 20) {
            $nombreDocSanitizado = substr($nombreDocSanitizado, 0, 20);
        }
        
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
    // ==============================================
    // CONFIRMAR POSTULACIÓN (CAMBIAR ESTADO A EN_REVISION)
    // ==============================================
    static public function ctrConfirmarPostulacion($idConvocatoria) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION["id"])) {
            return array("status" => "error", "message" => "Sesión de usuario no válida.");
        }

        $usuarioId = $_SESSION["id"];

        // Obtener la inscripción
        $inscripcion = ModeloInscripciones::mdlMostrarInscripcionUsuario("inscripciones", $usuarioId, $idConvocatoria);

        if (!$inscripcion) {
            return array("status" => "error", "message" => "No se encontró una inscripción para esta convocatoria.");
        }

        // Solo permitir si el estado es PENDIENTE o DEVUELTA
        if ($inscripcion["estado"] == "PENDIENTE" || $inscripcion["estado"] == "DEVUELTA") {
            $datosBD = array(
                "id_inscripcion" => $inscripcion["id"],
                "estado" => "EN_REVISION"
            );
            $respuesta = ModeloInscripciones::mdlActualizarEstado("inscripciones", $datosBD);
            if ($respuesta == "ok") {
                return array("status" => "success", "message" => "La postulación ha sido enviada a revisión.");
            } else {
                return array("status" => "error", "message" => "Error al actualizar el estado de la postulación.");
            }
        } else {
            return array("status" => "error", "message" => "El estado actual de la postulación no permite enviarla a revisión.");
        }
    }

}
