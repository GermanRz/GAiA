<?php

require_once "../controladores/financiera.controlador.php";
require_once "../modelos/financiera.modelo.php";

class AjaxFinanciera {

    public $idInscripcion;
    public $mesesOtorgados;
    public $fechaInicio;
    public $observacion;
    public $idConvocatoria;
    public $documento;
    public $idInscripcionEntrante;
    public $idAsignacionSaliente;

    // ==============================================
    // APROBAR DOCUMENTO BANCARIO AJAX
    // ==============================================
    public function ajaxAprobarDocumentoBancario() {
        $respuesta = ControladorFinanciera::ctrAprobarDocumentoBancario($this->idInscripcion, $this->mesesOtorgados, $this->fechaInicio);
        echo json_encode(["status" => $respuesta]);
    }

    // ==============================================
    // RECHAZAR DOCUMENTO BANCARIO AJAX
    // ==============================================
    public function ajaxRechazarDocumentoBancario() {
        $respuesta = ControladorFinanciera::ctrRechazarDocumentoBancario($this->idInscripcion, $this->observacion);
        echo json_encode(["status" => $respuesta]);
    }

    // ==============================================
    // OBTENER BENEFICIARIO INDIVIDUAL AJAX
    // ==============================================
    public function ajaxMostrarBeneficiario() {
        $respuesta = ControladorFinanciera::ctrMostrarBeneficiario($this->idInscripcion);
        echo json_encode($respuesta);
    }

    // ==============================================
    // OBTENER SELECCIONADOS POR CONVOCATORIA AJAX
    // ==============================================
    public function ajaxMostrarSeleccionadosRelevo() {
        $respuesta = ControladorFinanciera::ctrMostrarSeleccionadosRelevo($this->idConvocatoria);
        echo json_encode($respuesta);
    }

    // ==============================================
    // BUSCAR APRENDIZ ENTRANTE POR DOCUMENTO AJAX
    // ==============================================
    public function ajaxBuscarEntrantePorDocumento() {
        $respuesta = ControladorFinanciera::ctrBuscarEntrantePorDocumento($this->documento, $this->idConvocatoria);
        echo json_encode($respuesta);
    }

    // ==============================================
    // OBTENER CONTACTO DE APRENDIZ AJAX
    // ==============================================
    public function ajaxObtenerContactoAprendiz() {
        $respuesta = ControladorFinanciera::ctrObtenerContactoAprendiz($this->idInscripcion);
        echo json_encode($respuesta);
    }

    // ==============================================
    // PROCESAR RELEVO AJAX
    // ==============================================
    public function ajaxProcesarRelevo() {
        if(!isset($_SESSION)){ session_start(); }
        $idGestor = $_SESSION["id"];
        $respuesta = ControladorFinanciera::ctrProcesarRelevo(
            $this->idInscripcion,
            $this->idInscripcionEntrante,
            $this->idAsignacionSaliente,
            $this->observacion,
            $idGestor
        );
        echo json_encode($respuesta);
    }

}

// ==============================================
// MANEJO DE PETICIONES
// ==============================================

if (isset($_POST["idInscripcion"])) {
    $ajax = new AjaxFinanciera();
    $ajax->idInscripcion = $_POST["idInscripcion"];
    $ajax->ajaxMostrarBeneficiario();
}

if (isset($_POST["action"])) {

    $ajax = new AjaxFinanciera();

    // Acción: Aprobar Documento Bancario
    if ($_POST["action"] == "aprobarDocumentoBancario" && isset($_POST["id_inscripcion"])) {
        $ajax->idInscripcion = $_POST["id_inscripcion"];
        $ajax->mesesOtorgados = $_POST["meses_otorgados"];
        $ajax->fechaInicio = $_POST["fecha_inicio"];
        $ajax->ajaxAprobarDocumentoBancario();
    }

    // Acción: Rechazar Documento Bancario
    if ($_POST["action"] == "rechazarDocumentoBancario" && isset($_POST["id_inscripcion"])) {
        $ajax->idInscripcion = $_POST["id_inscripcion"];
        $ajax->observacion = $_POST["observacion"];
        $ajax->ajaxRechazarDocumentoBancario();
    }

    // Acción: Obtener Seleccionados para Relevo
    if ($_POST["action"] == "obtenerSeleccionados" && isset($_POST["id_convocatoria"])) {
        $ajax->idConvocatoria = $_POST["id_convocatoria"];
        $ajax->ajaxMostrarSeleccionadosRelevo();
    }

    // Acción: Buscar Aprendiz Entrante por Documento
    if ($_POST["action"] == "buscarEntrantePorDocumento" && isset($_POST["documento"]) && isset($_POST["id_convocatoria"])) {
        $ajax->documento = $_POST["documento"];
        $ajax->idConvocatoria = $_POST["id_convocatoria"];
        $ajax->ajaxBuscarEntrantePorDocumento();
    }

    // Acción: Obtener Contacto de Aprendiz
    if ($_POST["action"] == "obtenerContactoAprendiz" && isset($_POST["id_inscripcion"])) {
        $ajax->idInscripcion = $_POST["id_inscripcion"];
        $ajax->ajaxObtenerContactoAprendiz();
    }

    // Acción: Procesar Relevo
    if ($_POST["action"] == "procesarRelevo" && isset($_POST["id_inscripcion_saliente"])) {
        $ajax->idInscripcion = $_POST["id_inscripcion_saliente"];
        $ajax->idInscripcionEntrante = $_POST["id_inscripcion_entrante"];
        $ajax->idAsignacionSaliente = $_POST["id_asignacion_saliente"];
        $ajax->observacion = $_POST["motivo"];
        $ajax->ajaxProcesarRelevo();
    }

}

