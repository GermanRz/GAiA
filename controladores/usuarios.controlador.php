<?php

class ControladorUsuarios{

    public function ctrIngresarUsuario(){
        if (isset($_POST["ingDocumento"])){
            if (
                preg_match('/^[0-9]+$/', $_POST["ingDocumento"]) &&
                preg_match('/^[a-zA-Z0-9]+$/', $_POST["ingPassword"])
            ){
                $documento = $_POST["ingDocumento"];
                $respuesta = ModeloUsuarios::mdlIngresarUsuario($documento);

                if (is_array($respuesta)){
                    if ($respuesta["password"] == $_POST["ingPassword"] && $respuesta["documento_id"]== $documento){
                        $_SESSION["iniciarSesion"] = "ok";
                        echo "<script>window.location = 'inicio';</script>";
                    } else{
                    // var_dump($respuesta);
                    echo  "<br><div class='alert alert-danger'>Usuario o contraseña incorrecto</div>";
                    return;
                    }                      
                }

            }    
            
        }
    } //fin del metodo de ingresar usuario

}//fin de la clase ControladorUsuarios