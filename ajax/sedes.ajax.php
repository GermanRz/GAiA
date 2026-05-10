<?php

require_once "../controladores/sedes.controlador.php";
require_once "../modelos/sedes.modelo.php";

class AjaxSedes{

    public $nuevoNombre;

    public function ajaxValidarNombre(){

        $item = "nombre";
        $valor = $this->nuevoNombre;

        $respuesta = ControladorSedes::ctrMostrarSedes($item, $valor);

        echo json_encode($respuesta);

    }


}

if (isset($_POST["nuevoNombre"])) {
    $valNombre = new AjaxSedes();
    $valNombre->nuevoNombre = $_POST["nuevoNombre"];
    $valNombre->ajaxValidarNombre();
}
