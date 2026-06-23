<?php

require_once "../controladores/financiera.controlador.php";
require_once "../modelos/financiera.modelo.php";

class AjaxFinanciera {

    public $idInscripcion;
    public $mesesOtorgados;
    public $fechaInicio;
    public $observacion;

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

}

