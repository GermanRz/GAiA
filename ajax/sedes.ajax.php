<?php

require_once "../controladores/sedes.controlador.php";
require_once "../modelos/sedes.modelo.php";

class AjaxSedes{

    public $nuevaDescripcionSede;

    public function ajaxValidarDescripcion(){

        $item = "descripcion_sede";
        $valor = $this->nuevaDescripcionSede;

        $respuesta = ControladorSedes::ctrMostrarSedes($item, $valor);

        echo json_encode($respuesta);

    }


}

if (isset($_POST["nuevaDescripcion"])) {
    $valDescripcion = new AjaxSedes();
    $valDescripcion->nuevaDescripcionSede = $_POST["nuevaDescripcion"];
    $valDescripcion->ajaxValidarDescripcion();
}
