<?php

class ControladorUsuarios{

    // ************************************
    // LOGIN DE USUARIO 
    // ************************************
    public function ctrIngresarUsuario(){
        if (isset($_POST["ingDocumento"])){
            if (
                preg_match('/^[0-9]+$/', $_POST["ingDocumento"]) &&
                preg_match('/^[a-zA-Z0-9]+$/', $_POST["ingPassword"])
            ){
                $documento = $_POST["ingDocumento"];
                $respuesta = ModeloUsuarios::mdlIngresarUsuario($documento);

                // $tempo=crypt("admin123",'$2a$07$asdfsdvafdsgf04sdfsadfGAiADeveloper$');
                // var_dump($tempo);
                // exit;

                $passEncriptado=crypt($_POST["ingPassword"],'$2a$07$asdfsdvafdsgf04sdfsadfGAiADeveloper$');

                if (is_array($respuesta)){
                    //preguntar si el usuario esta activo
                    if ($respuesta["estado"]== "activo"){
                        if ($respuesta["password"] == $passEncriptado && $respuesta["documento_id"]== $documento){
                            $_SESSION["iniciarSesion"] = "ok";
                            $_SESSION["id"] = $respuesta["id"];
                            $_SESSION["documento"] = $respuesta["documento_id"];
                            $_SESSION["nombres"] = $respuesta["nombres"];
                            $_SESSION["apellidos"] = $respuesta["apellidos"];
                            $_SESSION["rol"] = $respuesta["rol"];
                            $_SESSION["ficha_id"] = $respuesta["ficha_id"];
                            $_SESSION["foto"] = $respuesta["foto"];
                            echo "<script>window.location = 'identificacion';</script>";
                        } else{
                        // var_dump($respuesta);
                        echo  "<br><div class='alert alert-danger'>Usuario o contraseña incorrecto</div>";
                        return;
                        }   
                    } else {
                        echo  "<br><div class='alert alert-warning'>El usuario esta inactivo</div>";
                        return;
                    }                   
                } else {
                    // El usuario no existe en la base de datos
                    echo "<script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'Usuario no registrado',
                            text: 'El documento ingresado no existe en el sistema. Por favor, regístrese como nuevo usuario.',
                            showConfirmButton: true,
                            confirmButtonText: 'Registrarse'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#modal-registroAprendiz').modal('show');
                            }
                        });
                    </script>";
                    return;
                }

            }    
            
        }
    } //fin del metodo de ingresar usuario

    
     // ************************************
    // LISA DE DE USUARIOS EN LA VENTANA PRINCIPAL
    // ************************************   
    static public function ctrListarUsuarios(){
        $respuesta= ModeloUsuarios::mdlListarUsuarios();
        return $respuesta;
    } //fin del metodo ctrListarUsuarios

    // ************************************
    // LISTA DE FICHAS
    // ************************************   
    static public function ctrListarFichas(){
        $respuesta= ModeloUsuarios::mdlListarFichas();
        return $respuesta;
    } //fin del metodo ctrListarFichas

    // ************************************
    // AGREGAR USUARIO A LA BD
    // ************************************
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
                // $passEncriptado=$_POST["nuevoDocumento"];

                $passEncriptado=crypt($_POST["nuevoDocumento"],'$2a$07$asdfsdvafdsgf04sdfsadfGAiADeveloper$');

                $fichaId = null;
                if ($_POST["nuevoRol"] == "Aprendiz" && isset($_POST["nuevaFicha"])) {
                    $fichaId = $_POST["nuevaFicha"];
                }

                // ==========================================
                // VALIDAR IMAGEN
                // ==========================================
                $ruta = self::procesarSubidaFoto($_FILES["nuevaFoto"], $_POST["nuevoDocumento"]);

                $datos = array(
                  "tipoDocumento" => $_POST["nuevoTipoDocumento"],
                  "documentoId" => $_POST["nuevoDocumento"],
                  "nombres" => $_POST["nuevoNombre"],
                  "apellidos" => $_POST["nuevoApellido"],
                  "correo" => $_POST["nuevoCorreo"],
                  "fechaNacimiento" => $_POST["nuevoFechaNacimiento"],
                  "rol" => $_POST["nuevoRol"],
                  "password"=> $passEncriptado,
                  "ficha_id" => $fichaId,
                  "foto" => $ruta
                );
                $respuesta= ModeloUsuarios::mdlAgregarUsuario($tabla, $datos);

                if($respuesta == "ok"){
                    $usuarioCreado = ModeloUsuarios::mdlMostrarUsuarios("usuarios", "documento_id", $_POST["nuevoDocumento"]);
                    if ($usuarioCreado) {
                        $contacto = array(
                            "direccion" => isset($_POST["nuevaDireccion"]) ? $_POST["nuevaDireccion"] : "",
                            "telefono" => isset($_POST["nuevoTelefono"]) ? $_POST["nuevoTelefono"] : "",
                            "codigo_dep" => isset($_POST["nuevoDepartamento"]) ? $_POST["nuevoDepartamento"] : "",
                            "codigo_ciu" => isset($_POST["nuevaCiudad"]) ? $_POST["nuevaCiudad"] : ""
                        );
                        if(strtoupper($_POST["nuevoRol"]) == "APRENDIZ" || (!empty($contacto["direccion"]) && !empty($contacto["codigo_dep"]))) {
                            ModeloUsuarios::mdlGuardarContacto($usuarioCreado["id"], $contacto);
                        }
                    }

                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'El usuario ha sido registrado correctamente',
                            showConfirmButton: true,
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = 'Usuarios';
                            }
                        });
                                
                        
                    </script>";
                    // echo "<br><div class='alert alert-success'>El usuario ha sido registrado correctamente</div>";
                }else{
                    echo "<br><div class='alert alert-danger'>Error al agregar el usuario</div>";
                }

        


              }
        }  // fin del isset
    }

    // ************************************
    // AGREGAR APRENDIZ DESDE EL LOGIN
    // ************************************
    public function ctrRegistroAprendiz(){
        
        if (isset($_POST["nuevoTipoDocumento"])  && 
            isset($_POST["nuevoDocumento"])  && 
            isset($_POST["nuevoNombre"])  && 
            isset($_POST["nuevoApellido"])  && 
            isset($_POST["nuevoCorreo"])  && 
            isset($_POST["nuevoFechaNacimiento"])  && 
            isset($_POST["nuevoRol"]))
            {
                if (
                preg_match('/^[0-9]+$/', $_POST["nuevoDocumento"]) &&
                preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]+$/', $_POST["nuevoNombre"]) &&
                preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]+$/', $_POST["nuevoApellido"])
              ) {

                $tabla="usuarios";

                $passEncriptado=crypt($_POST["nuevoDocumento"],'$2a$07$asdfsdvafdsgf04sdfsadfGAiADeveloper$');

                $fichaId = null;
                if ($_POST["nuevoRol"] == "Aprendiz" && isset($_POST["nuevaFicha"])) {
                    $fichaId = $_POST["nuevaFicha"];
                }

                // ==========================================
                // VALIDAR IMAGEN
                // ==========================================
                $ruta = self::procesarSubidaFoto($_FILES["nuevaFoto"], $_POST["nuevoDocumento"]);

                $datos = array(
                  "tipoDocumento" => $_POST["nuevoTipoDocumento"],
                  "documentoId" => $_POST["nuevoDocumento"],
                  "nombres" => $_POST["nuevoNombre"],
                  "apellidos" => $_POST["nuevoApellido"],
                  "correo" => $_POST["nuevoCorreo"],
                  "fechaNacimiento" => $_POST["nuevoFechaNacimiento"],
                  "rol" => $_POST["nuevoRol"],
                  "password"=> $passEncriptado,
                  "ficha_id" => $fichaId,
                  "foto" => $ruta
                );
                $respuesta= ModeloUsuarios::mdlAgregarUsuario($tabla, $datos);

                if($respuesta == "ok"){
                    $usuarioCreado = ModeloUsuarios::mdlMostrarUsuarios("usuarios", "documento_id", $_POST["nuevoDocumento"]);
                    if ($usuarioCreado) {
                        $contacto = array(
                            "direccion" => isset($_POST["nuevaDireccion"]) ? $_POST["nuevaDireccion"] : "",
                            "telefono" => isset($_POST["nuevoTelefono"]) ? $_POST["nuevoTelefono"] : "",
                            "codigo_dep" => isset($_POST["nuevoDepartamento"]) ? $_POST["nuevoDepartamento"] : "",
                            "codigo_ciu" => isset($_POST["nuevaCiudad"]) ? $_POST["nuevaCiudad"] : ""
                        );
                        if(strtoupper($_POST["nuevoRol"]) == "APRENDIZ" || (!empty($contacto["direccion"]) && !empty($contacto["codigo_dep"]))) {
                            ModeloUsuarios::mdlGuardarContacto($usuarioCreado["id"], $contacto);
                        }
                    }

                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: '¡Registro exitoso!',
                            text: 'Se ha registrado correctamente. Ya puede iniciar sesión.',
                            showConfirmButton: true,
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = 'inicio';
                            }
                        });
                    </script>";
                }else{
                    echo "<br><div class='alert alert-danger'>Error al agregar el usuario</div>";
                }
              }
        }  // fin del isset
    }

    // ************************************
    // TRAER UN USUARIO ESPECIFICO DE LA BD
    // ************************************
    static public function ctrMostrarUsuarios($item, $valor){
        $tabla = "usuarios";
        $respuesta = ModeloUsuarios::mdlMostrarUsuarios($tabla, $item, $valor);
        return $respuesta;
    }

    // ************************************
    // EDITAR USUARIO
    // ************************************
    public function ctrEditarUsuario(){
        if (isset($_POST["editarDocumento"]) && isset($_POST["idUsuarioEditar"])) {
            if (
                preg_match('/^[0-9]+$/', $_POST["editarDocumento"]) &&
                preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]+$/', $_POST["editarNombre"]) &&
                preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]+$/', $_POST["editarApellido"])
            ) {
                $tabla = "usuarios";

                if ($_POST["editarPassword"] != "") {
                    if (preg_match('/^[a-zA-Z0-9]+$/', $_POST["editarPassword"])) {
                        $passEncriptado = crypt($_POST["editarPassword"], '$2a$07$asdfsdvafdsgf04sdfsadfGAiADeveloper$');
                    } else {
                        echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: '¡La contraseña no puede ir vacía o llevar caracteres especiales!',
                                showConfirmButton: true,
                                confirmButtonText: 'Cerrar'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location = 'Usuarios';
                                }
                            });
                        </script>";
                        return;
                    }
                } else {
                    $passEncriptado = $_POST["passwordActual"];
                }

                $fichaId = null;
                if (strtoupper($_POST["editarRol"]) == "APRENDIZ" && isset($_POST["editarFicha"]) && $_POST["editarFicha"] != "") {
                    $fichaId = $_POST["editarFicha"];
                }

                $foto = $_POST["fotoActualEditar"];

                if (isset($_FILES["editarFoto"]["tmp_name"]) && !empty($_FILES["editarFoto"]["tmp_name"])) {
                    $foto = self::procesarSubidaFoto($_FILES["editarFoto"], $_POST["editarDocumento"], $_POST["fotoActualEditar"]);
                } else if (isset($_POST["eliminarFotoUsuario"]) && $_POST["eliminarFotoUsuario"] == "si") {
                    $foto = "documentos/anonimo/anonimo.png";
                    if ($_POST["fotoActualEditar"] != "" && $_POST["fotoActualEditar"] != "documentos/anonimo/anonimo.png") {
                        if (file_exists($_POST["fotoActualEditar"])) {
                            unlink($_POST["fotoActualEditar"]);
                        }
                    }
                }

                $datos = array(
                    "id" => $_POST["idUsuarioEditar"],
                    "tipoDocumento" => $_POST["editarTipoDocumento"],
                    "documentoId" => $_POST["editarDocumento"],
                    "nombres" => $_POST["editarNombre"],
                    "apellidos" => $_POST["editarApellido"],
                    "correo" => $_POST["editarCorreo"],
                    "fechaNacimiento" => $_POST["editarFechaNacimiento"],
                    "rol" => $_POST["editarRol"],
                    "password" => $passEncriptado,
                    "ficha_id" => $fichaId,
                    "foto" => $foto
                );

                $respuesta = ModeloUsuarios::mdlEditarUsuario($tabla, $datos);

                if ($respuesta == "ok") {
                    $contacto = array(
                        "direccion" => isset($_POST["editarDireccion"]) ? $_POST["editarDireccion"] : "",
                        "telefono" => isset($_POST["editarTelefono"]) ? $_POST["editarTelefono"] : "",
                        "codigo_dep" => isset($_POST["editarDepartamento"]) ? $_POST["editarDepartamento"] : "",
                        "codigo_ciu" => isset($_POST["editarCiudad"]) ? $_POST["editarCiudad"] : ""
                    );
                    if(strtoupper($_POST["editarRol"]) == "APRENDIZ" || (!empty($contacto["direccion"]) && !empty($contacto["codigo_dep"]))) {
                        ModeloUsuarios::mdlGuardarContacto($_POST["idUsuarioEditar"], $contacto);
                    }

                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'El usuario ha sido editado correctamente',
                            showConfirmButton: true,
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = 'Usuarios';
                            }
                        });
                    </script>";
                } else {
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: '¡Error al editar el usuario!',
                            showConfirmButton: true,
                            confirmButtonText: 'Cerrar'
                        });
                    </script>";
                }
            } else {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: '¡El nombre o apellidos no pueden ir vacíos o llevar caracteres especiales!',
                        showConfirmButton: true,
                        confirmButtonText: 'Cerrar'
                    });
                </script>";
            }
        }
    }

    // ************************************
    // EDITAR PERFIL
    // ************************************
    public function ctrEditarPerfil(){
        if (isset($_POST["idPerfil"]) && isset($_POST["editarNombrePerfil"])) {
            if (
                preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]+$/', $_POST["editarNombrePerfil"]) &&
                preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]+$/', $_POST["editarApellidoPerfil"])
            ) {
                $tabla = "usuarios";

                if ($_POST["editarPasswordPerfil"] != "") {
                    if (preg_match('/^[a-zA-Z0-9]+$/', $_POST["editarPasswordPerfil"])) {
                        $passEncriptado = crypt($_POST["editarPasswordPerfil"], '$2a$07$asdfsdvafdsgf04sdfsadfGAiADeveloper$');
                    } else {
                        echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: '¡La contraseña no puede llevar caracteres especiales!',
                                showConfirmButton: true,
                                confirmButtonText: 'Cerrar'
                            });
                        </script>";
                        return;
                    }
                } else {
                    // we need to get current password from DB or assume it's kept. 
                    // To do this we can fetch current user:
                    $usuarioDB = ModeloUsuarios::mdlMostrarUsuarios("usuarios", "id", $_POST["idPerfil"]);
                    $passEncriptado = $usuarioDB["password"];
                }

                $ruta = $_POST["fotoActual"];

                if (isset($_FILES["editarFotoPerfil"]["tmp_name"]) && !empty($_FILES["editarFotoPerfil"]["tmp_name"])) {
                    $ruta = self::procesarSubidaFoto($_FILES["editarFotoPerfil"], $_POST["documentoPerfil"], $_POST["fotoActual"]);
                }


                $datos = array(
                    "id" => $_POST["idPerfil"],
                    "nombres" => $_POST["editarNombrePerfil"],
                    "apellidos" => $_POST["editarApellidoPerfil"],
                    "password" => $passEncriptado,
                    "foto" => $ruta
                );

                $respuesta = ModeloUsuarios::mdlEditarPerfil($tabla, $datos);

                if ($respuesta == "ok") {
                    
                    $contacto = array(
                        "direccion" => isset($_POST["editarDireccionPerfil"]) ? $_POST["editarDireccionPerfil"] : "",
                        "telefono" => isset($_POST["editarTelefonoPerfil"]) ? $_POST["editarTelefonoPerfil"] : "",
                        "codigo_dep" => isset($_POST["editarDepartamentoPerfil"]) ? $_POST["editarDepartamentoPerfil"] : "",
                        "codigo_ciu" => isset($_POST["editarCiudadPerfil"]) ? $_POST["editarCiudadPerfil"] : ""
                    );
                    if(!empty($contacto["direccion"]) && !empty($contacto["codigo_dep"])) {
                        ModeloUsuarios::mdlGuardarContacto($_POST["idPerfil"], $contacto);
                    }

                    // Actualizar variables de sesion
                    $_SESSION["nombres"] = $_POST["editarNombrePerfil"];
                    $_SESSION["apellidos"] = $_POST["editarApellidoPerfil"];
                    $_SESSION["foto"] = $ruta;

                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Perfil actualizado correctamente',
                            showConfirmButton: true,
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = 'inicio';
                            }
                        });
                    </script>";
                } else {
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: '¡Error al editar el perfil!',
                            showConfirmButton: true,
                            confirmButtonText: 'Cerrar'
                        });
                    </script>";
                }
            } else {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: '¡El nombre o apellidos no pueden ir vacíos o llevar caracteres especiales!',
                        showConfirmButton: true,
                        confirmButtonText: 'Cerrar'
                    });
                </script>";
            }
        }
    }


    // ************************************
    // ACTUALIZAR ESTADO DE UN USUARIO
    // ************************************
    static public function ctrCambiarEstadoUsuario($idUsuario, $estado){
        $tabla = "usuarios";
        $respuesta = ModeloUsuarios::mdlCambiarEstadoUsuario($tabla, $idUsuario, $estado);
        return $respuesta;
    }   

    /**
     * Procesa la subida de una foto de usuario, aplicando redimensión si GD está habilitado,
     * o guardando el archivo directamente como fallback.
     */
    private static function procesarSubidaFoto($fileInput, $documentoId, $fotoActual = null) {
        $ruta = !empty($fotoActual) ? $fotoActual : "documentos/anonimo/anonimo.png";

        if (isset($fileInput["tmp_name"]) && !empty($fileInput["tmp_name"])) {
            $directorio = "documentos/" . $documentoId;
            if (!is_dir($directorio)) {
                mkdir($directorio, 0755, true);
            }

            // Validar formato
            if ($fileInput["type"] == "image/jpeg" || $fileInput["type"] == "image/png" || $fileInput["type"] == "image/jpg") {
                // Validar peso (4MB max)
                if ($fileInput["size"] <= 4194304) {

                    // Eliminar foto anterior si no es la anónima por defecto
                    if (!empty($fotoActual) && $fotoActual != "documentos/anonimo/anonimo.png") {
                        if (file_exists($fotoActual)) {
                            unlink($fotoActual);
                        }
                    }

                    $extension = ($fileInput["type"] == "image/png") ? ".png" : ".jpg";
                    $aleatorio = mt_rand(100, 999);
                    $rutaNueva = $directorio . "/" . $aleatorio . $extension;

                    // Fallback si la librería GD no está activa
                    if (!extension_loaded('gd') || !function_exists('imagecreatefromjpeg')) {
                        if (move_uploaded_file($fileInput["tmp_name"], $rutaNueva)) {
                            $ruta = $rutaNueva;
                        }
                    } else {
                        // Procesar con GD (redimensionar a 500x500)
                        list($ancho, $alto) = getimagesize($fileInput["tmp_name"]);
                        $nuevoAncho = 500;
                        $nuevoAlto = 500;
                        $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

                        if ($fileInput["type"] == "image/png") {
                            $origen = imagecreatefrompng($fileInput["tmp_name"]);
                            imagealphablending($destino, false);
                            imagesavealpha($destino, true);
                            $transparent = imagecolorallocatealpha($destino, 255, 255, 255, 127);
                            imagefilledrectangle($destino, 0, 0, $nuevoAncho, $nuevoAlto, $transparent);
                            imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                            imagepng($destino, $rutaNueva);
                        } else {
                            $origen = imagecreatefromjpeg($fileInput["tmp_name"]);
                            imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                            imagejpeg($destino, $rutaNueva);
                        }
                        imagedestroy($origen);
                        imagedestroy($destino);
                        $ruta = $rutaNueva;
                    }

                } else {
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: '¡Error al subir la imagen!',
                            text: 'La imagen no debe pesar más de 4MB',
                            showConfirmButton: true,
                            confirmButtonText: 'Cerrar'
                        });
                    </script>";
                    exit;
                }
            } else {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error al subir la imagen!',
                        text: 'La imagen debe estar en formato JPG o PNG',
                        showConfirmButton: true,
                        confirmButtonText: 'Cerrar'
                    });
                </script>";
                exit;
            }
        }

        return $ruta;
    }

}//fin de la clase ControladorUsuarios