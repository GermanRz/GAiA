<?php

require_once "controladores/plantilla.controlador.php";
require_once "controladores/usuarios.controlador.php";
require_once "controladores/sedes.controlador.php";

require_once "modelos/usuarios.modelo.php";
require_once "modelos/sedes.modelo.php";

$plantilla = new ControladorPlantilla();
$plantilla->ctrTraerPlantilla();
