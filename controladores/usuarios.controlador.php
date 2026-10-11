<?php

class ControladorUsuarios
{

    private const MAX_INTENTOS_LOGIN = 5;
    private const TIEMPO_BLOQUEO_SEGUNDOS = 300; // 5 minutos

    /**
     * Genera un hash seguro usando Bcrypt con sal aleatoria criptográfica y costo 12.
     */
    private static function generarHashPassword($passwordPlano)
    {
        return password_hash($passwordPlano, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * Verifica la contraseña contra hashes Bcrypt modernos o el hash legacy en tiempo constante.
     */
    private static function verificarPasswordUsuario($passwordPlano, $hashAlmacenado)
    {
        if (empty($hashAlmacenado)) {
            return false;
        }

        if (password_verify($passwordPlano, $hashAlmacenado)) {
            return true;
        }

        $hashLegacy = crypt($passwordPlano, '$2a$07$asdfsdvafdsgf04sdfsadfGAiADeveloper$');
        return hash_equals($hashAlmacenado, $hashLegacy);
    }

    /**
     * Valida el token CSRF enviado por formulario POST contra la sesión activa.
     */
    private static function validarTokenCsrf()
    {
        return isset($_POST["csrf_token"], $_SESSION["csrf_token"]) &&
               hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"]);
    }

    /**
     * Registra un intento fallido de inicio de sesión y activa bloqueo temporal si supera el umbral.
     */
    private static function registrarIntentoFallido()
    {
        $_SESSION["login_intentos"] = ($_SESSION["login_intentos"] ?? 0) + 1;
        if ($_SESSION["login_intentos"] >= self::MAX_INTENTOS_LOGIN) {
            $_SESSION["login_bloqueo_hasta"] = time() + self::TIEMPO_BLOQUEO_SEGUNDOS;
        }
    }

    // ************************************
    // LOGIN DE USUARIO 
    // ************************************
    public function ctrIngresarUsuario()
    {
        if (isset($_POST["ingDocumento"])) {

            // 1. Verificar bloqueo temporal por fuerza bruta (Rate Limiting)
            if (isset($_SESSION["login_bloqueo_hasta"])) {
                $tiempoRestante = $_SESSION["login_bloqueo_hasta"] - time();
                if ($tiempoRestante > 0) {
                    $minutos = ceil($tiempoRestante / 60);
                    echo "<br><div class='alert alert-danger'>Demasiados intentos fallidos. Por seguridad, intente nuevamente en {$minutos} minuto(s).</div>";
                    return;
                } else {
                    unset($_SESSION["login_bloqueo_hasta"]);
                    $_SESSION["login_intentos"] = 0;
                }
            }

            // 2. Validar token CSRF
            if (!self::validarTokenCsrf()) {
                echo "<br><div class='alert alert-danger'>Sesión expirada o solicitud inválida. Recargue la página e intente de nuevo.</div>";
                return;
            }

            $documento = trim($_POST["ingDocumento"]);
            $password = $_POST["ingPassword"] ?? "";

            // 3. Validar formato de entrada permitiendo caracteres especiales en la contraseña
            if (
                preg_match('/^[0-9]{5,20}$/', $documento) &&
                strlen($password) >= 1 &&
                strlen($password) <= 72
            ) {
                $respuesta = ModeloUsuarios::mdlIngresarUsuario($documento);

                if (is_array($respuesta)) {
                    if ($respuesta["estado"] == "activo") {
                        if (
                            $respuesta["documento_id"] === $documento &&
                            self::verificarPasswordUsuario($password, $respuesta["password"])
                        ) {
                            // Reiniciar contadores de intentos fallidos
                            $_SESSION["login_intentos"] = 0;
                            unset($_SESSION["login_bloqueo_hasta"]);

                            // Migración transparente a Bcrypt cost 12 con sal única si aún usa hash antiguo
                            if (password_needs_rehash($respuesta["password"], PASSWORD_BCRYPT, ['cost' => 12])) {
                                $nuevoHash = self::generarHashPassword($password);
                                ModeloUsuarios::mdlActualizarPassword($respuesta["id"], $nuevoHash);
                            }

                            // Prevenir Session Fixation regenerando el ID de sesión al autenticar
                            session_regenerate_id(true);
                            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));

                            $_SESSION["iniciarSesion"] = "ok";
                            $_SESSION["id"] = $respuesta["id"];
                            $_SESSION["documento"] = $respuesta["documento_id"];
                            $_SESSION["nombres"] = $respuesta["nombres"];
                            $_SESSION["apellidos"] = $respuesta["apellidos"];
                            $_SESSION["rol"] = $respuesta["rol"];
                            $_SESSION["ficha_id"] = $respuesta["ficha_id"];
                            $_SESSION["foto"] = $respuesta["foto"];
                            echo "<script>window.location = 'identificacion';</script>";
                            return;
                        } else {
                            self::registrarIntentoFallido();
                            echo "<br><div class='alert alert-danger'>Usuario o contraseña incorrecto</div>";
                            return;
                        }
                    } else {
                        echo "<br><div class='alert alert-warning'>El usuario esta inactivo</div>";
                        return;
                    }
                } else {
                    self::registrarIntentoFallido();
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
            } else {
                self::registrarIntentoFallido();
                echo "<br><div class='alert alert-danger'>Formato de documento o contraseña inválido</div>";
                return;
            }
        }
    } //fin del metodo de ingresar usuario


    // ************************************
    // LISA DE DE USUARIOS EN LA VENTANA PRINCIPAL
    // ************************************   
    static public function ctrListarUsuarios()
    {
        $respuesta = ModeloUsuarios::mdlListarUsuarios();
        return $respuesta;
    } //fin del metodo ctrListarUsuarios

    // ************************************
    // LISTA DE FICHAS
    // ************************************   
    static public function ctrListarFichas()
    {
        $respuesta = ModeloUsuarios::mdlListarFichas();
        return $respuesta;
    } //fin del metodo ctrListarFichas

    // ************************************
    // AGREGAR USUARIO A LA BD
    // ************************************
    public function ctrAgregarUsuario()
    {
        if (
            isset($_POST["nuevoTipoDocumento"])  &&
            isset($_POST["nuevoDocumento"])  &&
            isset($_POST["nuevoNombre"])  &&
            isset($_POST["nuevoApellido"])  &&
            isset($_POST["nuevoCorreo"])  &&
            isset($_POST["nuevoFechaNacimiento"])  &&
            isset($_POST["nuevoRol"])
        ) {
            if (
                preg_match('/^[0-9]+$/', $_POST["nuevoDocumento"]) &&
                preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]+$/', $_POST["nuevoNombre"]) &&
                preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]+$/', $_POST["nuevoApellido"]) &&
                filter_var($_POST["nuevoCorreo"], FILTER_VALIDATE_EMAIL)
            ) {

                $tabla = "usuarios";
                $passEncriptado = self::generarHashPassword($_POST["nuevoDocumento"]);

                $fichaId = null;
                if (strtoupper($_POST["nuevoRol"]) == "APRENDIZ" && isset($_POST["nuevaFicha"]) && $_POST["nuevaFicha"] !== "") {
                    $fichaId = $_POST["nuevaFicha"];
                }

                // ==========================================
                // VALIDAR IMAGEN
                // ==========================================
                $ruta = self::procesarSubidaFoto($_FILES["nuevaFoto"] ?? null, $_POST["nuevoDocumento"]);

                $datos = array(
                    "tipoDocumento" => $_POST["nuevoTipoDocumento"],
                    "documentoId" => $_POST["nuevoDocumento"],
                    "nombres" => $_POST["nuevoNombre"],
                    "apellidos" => $_POST["nuevoApellido"],
                    "correo" => $_POST["nuevoCorreo"],
                    "fechaNacimiento" => $_POST["nuevoFechaNacimiento"],
                    "rol" => $_POST["nuevoRol"],
                    "password" => $passEncriptado,
                    "ficha_id" => $fichaId,
                    "foto" => $ruta
                );
                $contacto = array(
                    "direccion" => isset($_POST["nuevaDireccion"]) ? $_POST["nuevaDireccion"] : "",
                    "telefono" => isset($_POST["nuevoTelefono"]) ? $_POST["nuevoTelefono"] : "",
                    "codigo_dep" => isset($_POST["nuevoDepartamento"]) ? $_POST["nuevoDepartamento"] : "",
                    "codigo_ciu" => isset($_POST["nuevaCiudad"]) ? $_POST["nuevaCiudad"] : ""
                );

                $respuesta = ModeloUsuarios::mdlAgregarUsuario($tabla, $datos, $contacto);

                if ($respuesta == "ok") {

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
                } else if ($respuesta == "duplicate") {
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: '¡Error!',
                            text: 'El documento o correo ya se encuentra registrado.',
                            showConfirmButton: true,
                            confirmButtonText: 'Aceptar'
                        });
                    </script>";
                } else {
                    echo "<br><div class='alert alert-danger'>Error al agregar el usuario</div>";
                }
            }
        }  // fin del isset
    }

    // ************************************
    // AGREGAR APRENDIZ DESDE EL LOGIN
    // ************************************
    public function ctrRegistroAprendiz()
    {

        if (
            isset($_POST["registroAprendizPublico"]) &&
            isset($_POST["nuevoTipoDocumento"])  &&
            isset($_POST["nuevoDocumento"])  &&
            isset($_POST["nuevoNombre"])  &&
            isset($_POST["nuevoApellido"])  &&
            isset($_POST["nuevoCorreo"])  &&
            isset($_POST["nuevoFechaNacimiento"])
        ) {
            if (!self::validarTokenCsrf()) {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Sesión expirada',
                        text: 'Por favor recargue la página e intente nuevamente.',
                        showConfirmButton: true,
                        confirmButtonText: 'Aceptar'
                    });
                </script>";
                return;
            }

            $tiposPermitidos = array("TI", "CC", "CE", "PEP", "PPT");

            if (
                in_array($_POST["nuevoTipoDocumento"], $tiposPermitidos, true) &&
                preg_match('/^[0-9]{5,20}$/', $_POST["nuevoDocumento"]) &&
                preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]+$/', $_POST["nuevoNombre"]) &&
                preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]+$/', $_POST["nuevoApellido"]) &&
                filter_var($_POST["nuevoCorreo"], FILTER_VALIDATE_EMAIL)
            ) {

                $tabla = "usuarios";

                // Hash seguro con sal aleatoria; rol forzado estrictamente a APRENDIZ
                $passEncriptado = self::generarHashPassword($_POST["nuevoDocumento"]);
                $rolForzado = "APRENDIZ";

                $fichaId = (!empty($_POST["nuevaFicha"]) && preg_match('/^[0-9]+$/', $_POST["nuevaFicha"]))
                    ? (int)$_POST["nuevaFicha"]
                    : null;

                // ==========================================
                // VALIDAR IMAGEN
                // ==========================================
                $ruta = self::procesarSubidaFoto($_FILES["nuevaFoto"] ?? null, $_POST["nuevoDocumento"]);

                $datos = array(
                    "tipoDocumento" => $_POST["nuevoTipoDocumento"],
                    "documentoId" => $_POST["nuevoDocumento"],
                    "nombres" => trim($_POST["nuevoNombre"]),
                    "apellidos" => trim($_POST["nuevoApellido"]),
                    "correo" => trim($_POST["nuevoCorreo"]),
                    "fechaNacimiento" => $_POST["nuevoFechaNacimiento"],
                    "rol" => $rolForzado,
                    "password" => $passEncriptado,
                    "ficha_id" => $fichaId,
                    "foto" => $ruta
                );
                $contacto = array(
                    "direccion" => isset($_POST["nuevaDireccion"]) ? trim($_POST["nuevaDireccion"]) : "",
                    "telefono" => isset($_POST["nuevoTelefono"]) ? trim($_POST["nuevoTelefono"]) : "",
                    "codigo_dep" => isset($_POST["nuevoDepartamento"]) ? $_POST["nuevoDepartamento"] : "",
                    "codigo_ciu" => isset($_POST["nuevaCiudad"]) ? $_POST["nuevaCiudad"] : ""
                );

                $respuesta = ModeloUsuarios::mdlAgregarUsuario($tabla, $datos, $contacto);

                if ($respuesta == "ok") {

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
                } else if ($respuesta == "duplicate") {
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: '¡Error al registrarse!',
                            text: 'El documento o correo ya se encuentra registrado en el sistema.',
                            showConfirmButton: true,
                            confirmButtonText: 'Aceptar'
                        });
                    </script>";
                } else {
                    echo "<br><div class='alert alert-danger'>Error al agregar el usuario</div>";
                }
            }
        }  // fin del isset
    }

    // ************************************
    // TRAER UN USUARIO ESPECIFICO DE LA BD
    // ************************************
    static public function ctrMostrarUsuarios($item, $valor)
    {
        $tabla = "usuarios";
        $respuesta = ModeloUsuarios::mdlMostrarUsuarios($tabla, $item, $valor);
        return $respuesta;
    }

    // ************************************
    // EDITAR USUARIO
    // ************************************
    public function ctrEditarUsuario()
    {
        if (isset($_POST["editarDocumento"]) && isset($_POST["idUsuarioEditar"])) {
            if (
                preg_match('/^[0-9]+$/', $_POST["editarDocumento"]) &&
                preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]+$/', $_POST["editarNombre"]) &&
                preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]+$/', $_POST["editarApellido"])
            ) {
                $tabla = "usuarios";
                $usuarioDB = ModeloUsuarios::mdlMostrarUsuarios("usuarios", "id", $_POST["idUsuarioEditar"]);
                if (!$usuarioDB) {
                    return;
                }

                if ($_POST["editarPassword"] != "") {
                    if (strlen($_POST["editarPassword"]) >= 4 && strlen($_POST["editarPassword"]) <= 72) {
                        $passEncriptado = self::generarHashPassword($_POST["editarPassword"]);
                    } else {
                        echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: '¡La contraseña debe tener entre 4 y 72 caracteres!',
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
                    $passEncriptado = $usuarioDB["password"];
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
                        "codigo_dep" => isset($_POST["editarDepartamento"]) ? $_POST["nuevoDepartamento"] ?? $_POST["editarDepartamento"] : "",
                        "codigo_ciu" => isset($_POST["editarCiudad"]) ? $_POST["editarCiudad"] : ""
                    );
                    ModeloUsuarios::mdlGuardarContacto($_POST["idUsuarioEditar"], $contacto);

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
                } else if ($respuesta == "duplicate") {
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: '¡Error!',
                            text: 'El correo electrónico editado ya está en uso por otro usuario.',
                            showConfirmButton: true,
                            confirmButtonText: 'Cerrar'
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
    public function ctrEditarPerfil()
    {
        if (isset($_POST["idPerfil"]) && isset($_POST["editarNombrePerfil"])) {

            // Prevenir IDOR: solo el propio usuario en sesión puede editar su perfil
            if (!isset($_SESSION["id"]) || (int)$_POST["idPerfil"] !== (int)$_SESSION["id"]) {
                return;
            }

            if (
                preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]+$/', $_POST["editarNombrePerfil"]) &&
                preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]+$/', $_POST["editarApellidoPerfil"])
            ) {
                $tabla = "usuarios";
                $usuarioDB = ModeloUsuarios::mdlMostrarUsuarios("usuarios", "id", $_SESSION["id"]);
                if (!$usuarioDB) {
                    return;
                }

                if ($_POST["editarPasswordPerfil"] != "") {
                    if (strlen($_POST["editarPasswordPerfil"]) >= 4 && strlen($_POST["editarPasswordPerfil"]) <= 72) {
                        $passEncriptado = self::generarHashPassword($_POST["editarPasswordPerfil"]);
                    } else {
                        echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: '¡La contraseña debe tener entre 4 y 72 caracteres!',
                                showConfirmButton: true,
                                confirmButtonText: 'Cerrar'
                            });
                        </script>";
                        return;
                    }
                } else {
                    $passEncriptado = $usuarioDB["password"];
                }

                $ruta = $usuarioDB["foto"] ?: $_POST["fotoActual"];

                if (isset($_FILES["editarFotoPerfil"]["tmp_name"]) && !empty($_FILES["editarFotoPerfil"]["tmp_name"])) {
                    $ruta = self::procesarSubidaFoto($_FILES["editarFotoPerfil"], $_SESSION["documento"], $ruta);
                }


                $datos = array(
                    "id" => $_SESSION["id"],
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
                    ModeloUsuarios::mdlGuardarContacto($_SESSION["id"], $contacto);

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
                                window.location = 'identificacion';
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
    static public function ctrCambiarEstadoUsuario($idUsuario, $estado)
    {
        $tabla = "usuarios";
        $respuesta = ModeloUsuarios::mdlCambiarEstadoUsuario($tabla, $idUsuario, $estado);
        return $respuesta;
    }

    /**
     * Procesa la subida de una foto de usuario validando su firma binaria real (getimagesize)
     * y aplicando redimensión si GD está habilitado.
     */
    private static function procesarSubidaFoto($fileInput, $documentoId, $fotoActual = null)
    {
        $ruta = !empty($fotoActual) ? $fotoActual : "documentos/anonimo/anonimo.png";

        if (isset($fileInput["tmp_name"]) && !empty($fileInput["tmp_name"])) {
            $documentoSanitizado = preg_replace('/[^0-9]/', '', (string)$documentoId);
            if ($documentoSanitizado === "") {
                return $ruta;
            }

            $directorio = "documentos/" . $documentoSanitizado;
            if (!is_dir($directorio)) {
                mkdir($directorio, 0755, true);
            }

            // Validar contenido real de la imagen (previene falsificación de cabecera MIME)
            $infoImagen = @getimagesize($fileInput["tmp_name"]);
            $esImagenValida = ($infoImagen !== false) && in_array($infoImagen[2], array(IMAGETYPE_JPEG, IMAGETYPE_PNG), true);

            if ($esImagenValida) {
                // Validar peso (4MB max)
                if ($fileInput["size"] <= 4194304) {

                    // Eliminar foto anterior si no es la anónima por defecto
                    if (!empty($fotoActual) && $fotoActual != "documentos/anonimo/anonimo.png") {
                        if (file_exists($fotoActual)) {
                            unlink($fotoActual);
                        }
                    }

                    $esPng = ($infoImagen[2] === IMAGETYPE_PNG);
                    $extension = $esPng ? ".png" : ".jpg";
                    $aleatorio = mt_rand(100, 999);
                    $rutaNueva = $directorio . "/" . $aleatorio . $extension;

                    // Fallback si la librería GD no está activa
                    if (!extension_loaded('gd') || !function_exists('imagecreatefromjpeg')) {
                        if (move_uploaded_file($fileInput["tmp_name"], $rutaNueva)) {
                            $ruta = $rutaNueva;
                        }
                    } else {
                        // Procesar con GD (redimensionar a 500x500)
                        $ancho = $infoImagen[0];
                        $alto = $infoImagen[1];
                        $nuevoAncho = 500;
                        $nuevoAlto = 500;
                        $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

                        if ($esPng) {
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
                        text: 'La imagen debe estar en formato JPG o PNG válido',
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