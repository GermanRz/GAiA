<?php

class ControladorUsuarios{
    public function ctrIngresoUsuario(){
        if (isset($_POST["ingDocumento"])){
            if (
                preg_match('/^[0-9]+$/', $_POST["ingDocumento"]) &&
                preg_match('/^[a-z A-Z 0-9]+$/', $_POST["ingPassword"])
            ){
                $documento = $_POST["ingDocumento"];
                $respuesta = ModeloUsuarios::mdlIngresarUsuario($documento);
                // var_dump($respuesta["password"]);

                if (is_array($respuesta)){
                    if ($respuesta["password"] == $_POST["ingPassword"] && $respuesta["documento_id"] == $documento){
                        $_SESSION["iniciarSesion"] = "ok";
                        echo "<script> window.location = 'inicio'</script>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Usuario y/o Contraseña incorrectos</div>";
                    return;
                }
            }
        }
    }
}