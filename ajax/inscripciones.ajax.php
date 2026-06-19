<?php

require_once "../controladores/inscripciones.controlador.php";
require_once "../modelos/inscripciones.modelo.php";
require_once "../controladores/convocatorias.controlador.php";
require_once "../modelos/convocatorias.modelo.php";
require_once "../controladores/usuarios.controlador.php";
require_once "../modelos/usuarios.modelo.php";

class AjaxInscripciones {

    public $idConvocatoria;
    public $convocatoriaId;
    public $nombreDoc;
    public $idDoc;
    public $rutaArchivo;
    public $inscripcionId;

    // ==============================================
    // CARGAR REQUISITOS DEL BAREMO Y ARCHIVOS CARGADOS
    // ==============================================
    public function ajaxCargarRequisitosConvocatoria() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $usuarioId = isset($_SESSION["id"]) ? $_SESSION["id"] : null;
        
        // 1. Obtener los requisitos definidos en el baremo de esta convocatoria
        $requisitosBaremo = ControladorConvocatorias::ctrMostrarBaremo($this->idConvocatoria);

        // 2. Obtener datos de inscripción y documentos si ya ha postulado previamente
        $inscripcion = null;
        $documentosCargados = array();

        if ($usuarioId !== null) {
            $inscripcion = ModeloInscripciones::mdlMostrarInscripcionUsuario("inscripciones", $usuarioId, $this->idConvocatoria);
            
            if ($inscripcion) {
                $documentosCargados = ModeloInscripciones::mdlListarDocumentosInscripcion("inscripcion_documentos", $inscripcion["id"]);
            }
        }

        echo json_encode([
            "status" => "success",
            "baremo" => $requisitosBaremo,
            "inscripcion" => $inscripcion,
            "documentos" => $documentosCargados
        ]);
    }

    // ==============================================
    // SUBIR ARCHIVO AJAX
    // ==============================================
    public function ajaxSubirDocumento() {
        $file = $_FILES["file"];
        $convocatoriaId = $this->convocatoriaId;
        $nombreDoc = $this->nombreDoc;

        $respuesta = ControladorInscripciones::ctrSubirDocumento($file, $convocatoriaId, $nombreDoc);
        echo json_encode($respuesta);
    }

    // ==============================================
    // ELIMINAR ARCHIVO AJAX
    // ==============================================
    public function ajaxEliminarDocumento() {
        $idDoc = $this->idDoc;
        $rutaArchivo = $this->rutaArchivo;

        $respuesta = ControladorInscripciones::ctrEliminarDocumento($idDoc, $rutaArchivo);
        echo json_encode(["status" => $respuesta]);
    }
    // ==============================================
    // ENVIAR POSTULACION AJAX
    // ==============================================
    public function ajaxEnviarPostulacion() {
        $respuesta = ControladorInscripciones::ctrEnviarPostulacion($this->inscripcionId);
        echo json_encode($respuesta);
    }

    // ==============================================
    // SUBIR CERTIFICACIÓN BANCARIA AJAX
    // ==============================================
    public function ajaxSubirCertificacionBancaria() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $idInscripcion = $_POST["id_inscripcion_banco"];
        $banco = $_POST["entidad_bancaria"];
        $cuenta = $_POST["numero_cuenta"];
        $archivo = $_FILES["certificacion_pdf"];
        
        $respuesta = ControladorInscripciones::ctrSubirCertificacionBancaria($idInscripcion, $banco, $cuenta, $archivo);
        echo json_encode($respuesta);
    }
}

// ==============================================
// MANEJO DE PETICIONES
// ==============================================

if (isset($_POST["action"])) {

    $ajax = new AjaxInscripciones();

    // Acción: Cargar Requisitos y estados de documentos
    if ($_POST["action"] == "cargarRequisitosConvocatoria" && isset($_POST["idConvocatoria"])) {
        $ajax->idConvocatoria = $_POST["idConvocatoria"];
        $ajax->ajaxCargarRequisitosConvocatoria();
    }

    // Acción: Subir un documento PDF
    if ($_POST["action"] == "subirDocumento" && isset($_FILES["file"]) && isset($_POST["convocatoriaId"]) && isset($_POST["nombreDoc"])) {
        $ajax->convocatoriaId = $_POST["convocatoriaId"];
        $ajax->nombreDoc = $_POST["nombreDoc"];
        $ajax->ajaxSubirDocumento();
    }

    // Acción: Eliminar o limpiar un documento ya subido
    if ($_POST["action"] == "eliminarDocumento" && isset($_POST["idDoc"]) && isset($_POST["rutaArchivo"])) {
        $ajax->idDoc = $_POST["idDoc"];
        $ajax->rutaArchivo = $_POST["rutaArchivo"];
        $ajax->ajaxEliminarDocumento();
    }

    // Acción: Enviar postulación (cambia estado a EN_REVISION)
    if ($_POST["action"] == "enviarPostulacion" && isset($_POST["inscripcionId"])) {
        $ajax->inscripcionId = $_POST["inscripcionId"];
        $ajax->ajaxEnviarPostulacion();
    }

    // Acción: Subir certificación bancaria
    if ($_POST["action"] == "subirCertificacionBancaria") {
        $ajax->ajaxSubirCertificacionBancaria();
    }

}
