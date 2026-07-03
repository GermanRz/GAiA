<?php

require_once "controladores/plantilla.controlador.php";
require_once "controladores/usuarios.controlador.php";
require_once "controladores/sedes.controlador.php";
require_once "controladores/fichas.controlador.php";
require_once "controladores/apoyos.controlador.php";
require_once "controladores/iconos.controlador.php";
require_once "controladores/convocatorias.controlador.php";
require_once "controladores/inscripciones.controlador.php";
require_once "controladores/financiera.controlador.php";
require_once "controladores/relevos.controlador.php";

require_once "modelos/usuarios.modelo.php";
require_once "modelos/sedes.modelo.php";
require_once "modelos/fichas.modelo.php";
require_once "modelos/apoyos.modelo.php";
require_once "modelos/iconos.modelo.php";
require_once "modelos/convocatorias.modelo.php";
require_once "modelos/inscripciones.modelo.php";
require_once "modelos/financiera.modelo.php";
require_once "modelos/relevos.modelo.php";

$plantilla = new ControladorPlantilla();
$plantilla->ctrTraerPlantilla();
