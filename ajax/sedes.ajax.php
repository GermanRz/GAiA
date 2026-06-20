<?php
 
require_once "../controladores/sedes.controlador.php";
require_once "../modelos/sedes.modelo.php";

class AjaxSedes
{

    public $idSedeEstado; // usado para cambiar el estado
    public $estado; // usado para cambiar el estado
    
    public function ajaxCambiarEstado()
    {
        $idSede = $this->idSedeEstado;
        $estado = $this->estado;
        $respuesta = ControladorSedes::ctrCambiarEstadoSede($idSede, $estado);
        echo $respuesta ? 'ok' : 'error';
    }

    public $idSede; // usado para editar/consultar sede

    public function ajaxEditarSede()
    {
        $item = "id_sede";
        $valor = $this->idSede;
        $respuesta = ControladorSedes::ctrMostrarSedes($item, $valor);
        echo json_encode($respuesta);
    }

    public $validarNombre;
    public function ajaxValidarNombreSede()
    {
        $item = "descripcion_sede";
        $valor = $this->validarNombre;
        $respuesta = ControladorSedes::ctrMostrarSedes($item, $valor);
        echo json_encode($respuesta);
    }

    public $validarDireccion;
    public function ajaxValidarDireccionSede()
    {
        $item = "direccion_sede";
        $valor = $this->validarDireccion;
        $respuesta = ControladorSedes::ctrMostrarSedes($item, $valor);
        echo json_encode($respuesta);
    }

    // INACTIVACIÓN RECURSIVA: SEDE → FICHAS → APRENDICES
    public $idSedeInactivar;
    public function ajaxInactivarSedeRecursiva()
    {
        $idSede = $this->idSedeInactivar;
        $respuesta = ControladorSedes::ctrInactivarSedeRecursiva($idSede);
        echo $respuesta === "ok" ? 'ok' : $respuesta;
    }

    // CALCULAR IMPACTO ANTES DE INACTIVAR
    public $idSedeImpacto;
    public function ajaxCalcularImpacto()
    {
        $idSede = $this->idSedeImpacto;
        $respuesta = ControladorSedes::ctrCalcularImpactoInactivacion($idSede);
        echo json_encode($respuesta);
    }
}

if (isset($_POST["idSedeEstado"]) && isset($_POST["estado"])) {
    $actSede = new AjaxSedes();
    $actSede->idSedeEstado = $_POST["idSedeEstado"];
    $actSede->estado = $_POST["estado"];
    $actSede->ajaxCambiarEstado();
}

if (isset($_POST["idSede"])) {
    $editar = new AjaxSedes();
    $editar->idSede = $_POST["idSede"];
    $editar->ajaxEditarSede();
}

if (isset($_POST["validarNombre"])) {
    $valNombre = new AjaxSedes();
    $valNombre->validarNombre = $_POST["validarNombre"];
    $valNombre->ajaxValidarNombreSede();
}

if (isset($_POST["validarDireccion"])) {
    $valDireccion = new AjaxSedes();
    $valDireccion->validarDireccion = $_POST["validarDireccion"];
    $valDireccion->ajaxValidarDireccionSede();
}

if (isset($_POST["idSedeInactivar"])) {
    $inactivar = new AjaxSedes();
    $inactivar->idSedeInactivar = $_POST["idSedeInactivar"];
    $inactivar->ajaxInactivarSedeRecursiva();
}

if (isset($_POST["idSedeImpacto"])) {
    $impacto = new AjaxSedes();
    $impacto->idSedeImpacto = $_POST["idSedeImpacto"];
    $impacto->ajaxCalcularImpacto();
}
