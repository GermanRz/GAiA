<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../controladores/usuarios.controlador.php";
require_once "../modelos/usuarios.modelo.php";

class AjaxUsuarios
{

    public $nuevoDocumento;
    public $idUsuarioEstado; // usado para cambiar el estado
    public $estado; // usado para cambiar el estado

    public function ajaxValidarDocumento()
    {
        $item = "documento_id";
        $valor = preg_replace('/[^0-9]/', '', (string)$this->nuevoDocumento);

        if ($valor === "") {
            echo json_encode(false);
            return;
        }

        $respuesta = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);

        // No exponer datos del usuario ni hash de contraseña; devolver únicamente indicador de existencia
        echo json_encode($respuesta ? array("existe" => true) : false);
    }

    public function ajaxCambiarEstado()
    {
        if (
            !isset($_SESSION["iniciarSesion"]) ||
            $_SESSION["iniciarSesion"] !== "ok" ||
            strtoupper($_SESSION["rol"] ?? "") !== "ADMIN"
        ) {
            echo "error";
            return;
        }

        $idUsuario = (int)$this->idUsuarioEstado;
        $estado = ($this->estado === "activo") ? "activo" : "inactivo";

        // Impedir que el administrador se desactive a sí mismo por accidente
        if ($idUsuario === (int)$_SESSION["id"] && $estado === "inactivo") {
            echo "error";
            return;
        }

        $respuesta = ControladorUsuarios::ctrCambiarEstadoUsuario($idUsuario, $estado);
        echo $respuesta ? 'ok' : 'error';
    }

    public $idUsuario; // usado para editar usuario

    public function ajaxEditarUsuario()
    {
        if (!isset($_SESSION["iniciarSesion"]) || $_SESSION["iniciarSesion"] !== "ok") {
            echo json_encode(false);
            return;
        }

        $valor = (int)$this->idUsuario;
        $rolSesion = strtoupper($_SESSION["rol"] ?? "");
        $esAutorizado = ($rolSesion === "ADMIN" || $rolSesion === "GESTORA");
        $esPropioUsuario = ((int)($_SESSION["id"] ?? 0) === $valor);

        if (!$esAutorizado && !$esPropioUsuario) {
            echo json_encode(false);
            return;
        }

        $item = "id";
        $respuesta = ControladorUsuarios::ctrMostrarUsuarios($item, $valor);

        if (is_array($respuesta)) {
            // Jamás enviar el hash de contraseña al navegador
            unset($respuesta["password"], $respuesta[7]);

            $contacto = ModeloUsuarios::mdlObtenerContactoUsuario($valor);
            if ($contacto) {
                $respuesta["contacto"] = $contacto;
            }
        }

        echo json_encode($respuesta);
    }

    public $codigo_dep;
    public function ajaxObtenerCiudades()
    {
        $respuesta = ModeloUsuarios::mdlObtenerCiudades($this->codigo_dep);
        echo json_encode($respuesta);
    }
}

if (isset($_POST["nuevoDocumento"])) {
    $valDocumento = new AjaxUsuarios();
    $valDocumento->nuevoDocumento = $_POST["nuevoDocumento"];
    $valDocumento->ajaxValidarDocumento();
}

if (isset($_POST["idUsuarioEstado"]) && isset($_POST["estado"])) {
    $actUsuario = new AjaxUsuarios();
    $actUsuario->idUsuarioEstado = $_POST["idUsuarioEstado"];
    $actUsuario->estado = $_POST["estado"];
    $actUsuario->ajaxCambiarEstado();
}

if (isset($_POST["idUsuario"])) {
    $editar = new AjaxUsuarios();
    $editar->idUsuario = $_POST["idUsuario"];
    $editar->ajaxEditarUsuario();
}

if (isset($_POST["codigo_dep"])) {
    $ciudades = new AjaxUsuarios();
    $ciudades->codigo_dep = $_POST["codigo_dep"];
    $ciudades->ajaxObtenerCiudades();
}

