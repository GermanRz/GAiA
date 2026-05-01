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

    static public function ctrListarUsuarios(){
        $respuesta= ModeloUsuarios::mdlListarUsuarios();
        return $respuesta;
    } //fin del metodo ctrListarUsuarios

    public function ctrAgregarUsuario(){

        
        if (isset($_POST["nuevoTipoDocumento"])  && 
        isset($_POST["nuevoDocumento"])  && 
        isset($_POST["nuevoNombre"])  && 
        isset($_POST["nuevoApellido"])  && 
        isset($_POST["nuevoCorreo"])  && 
        isset($_POST["nuevoFechaNacimiento"])  && 
            isset($_POST["nuevoRol"]))




            {
                // echo "entrando a agregar usuario";
                // exit;
                if (
                preg_match('/^[0-9]+$/', $_POST["nuevoDocumento"]) &&
                preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]+$/', $_POST["nuevoNombre"]) &&
                preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]+$/', $_POST["nuevoApellido"])
              ) {

                $tabla="usuarios";
                $datos = array(
                  "tipoDocumento" => $_POST["nuevoTipoDocumento"],
                  "documentoId" => $_POST["nuevoDocumento"],
                  "nombres" => $_POST["nuevoNombre"],
                  "apellidos" => $_POST["nuevoApellido"],
                  "correo" => $_POST["nuevoCorreo"],
                  "fechaNacimiento" => $_POST["nuevoFechaNacimiento"],
                  "rol" => $_POST["nuevoRol"]
                );
                $respuesta= ModeloUsuarios::mdlAgregarUsuario($tabla, $datos);
                if($respuesta == "ok"){
                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'El usuario ha sido registrado correctamente',
                            showConfirmButton: true,
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = 'usuarios';
                            }
                        })
                    </script>";
                    // echo "<br><div class='alert alert-success'>El usuario ha sido registrado correctamente</div>";
                }else{
                    echo "<br><div class='alert alert-danger'>Error al agregar el usuario</div>";
                }

        


              }
        }  // fin del isset
    }

}//fin de la clase ControladorUsuarios