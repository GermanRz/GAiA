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
            $rolSesion = strtoupper($_SESSION["rol"] ?? "");
            if (!isset($_SESSION["iniciarSesion"]) || $_SESSION["iniciarSesion"] !== "ok" || !in_array($rolSesion, array("ADMIN", "GESTORA"), true)) {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Acceso denegado',
                        text: 'No tiene permisos para registrar usuarios.',
                        showConfirmButton: true,
                        confirmButtonText: 'Cerrar'
                    });
                </script>";
                return;
            }

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
            $rolesPermitidos = array("APRENDIZ", "GESTORA", "ADMIN");
            $rolNormalizado = strtoupper(trim($_POST["nuevoRol"]));

            // Impedir que una GESTORA cree un usuario con rol ADMIN
            if ($rolSesion === "GESTORA" && $rolNormalizado === "ADMIN") {
                echo "<script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Permiso insuficiente',
                        text: 'Solo un Administrador puede asignar el rol ADMIN.',
                        showConfirmButton: true,
                        confirmButtonText: 'Cerrar'
                    });
                </script>";
                return;
            }

            if (
                in_array($_POST["nuevoTipoDocumento"], $tiposPermitidos, true) &&
                in_array($rolNormalizado, $rolesPermitidos, true) &&
                preg_match('/^[0-9]{5,20}$/', trim($_POST["nuevoDocumento"])) &&
                preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]+$/', trim($_POST["nuevoNombre"])) &&
                preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]+$/', trim($_POST["nuevoApellido"])) &&
                filter_var(trim($_POST["nuevoCorreo"]), FILTER_VALIDATE_EMAIL)
            ) {

                $tabla = "usuarios";
                $passEncriptado = self::generarHashPassword(trim($_POST["nuevoDocumento"]));

                $fichaId = null;
                if ($rolNormalizado === "APRENDIZ") {
                    if (empty($_POST["nuevaFicha"]) || !preg_match('/^[0-9]+$/', (string)$_POST["nuevaFicha"]) || !ModeloUsuarios::mdlExisteFicha((int)$_POST["nuevaFicha"])) {
                        echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: '¡Ficha inválida!',
                                text: 'Debe seleccionar una ficha de formación válida de la lista para el aprendiz.',
                                showConfirmButton: true,
                                confirmButtonText: 'Cerrar'
                            });
                        </script>";
                        return;
                    }
                    $fichaId = (int)$_POST["nuevaFicha"];
                }

                // ==========================================
                // VALIDAR IMAGEN
                // ==========================================
                $ruta = self::procesarSubidaFoto($_FILES["nuevaFoto"] ?? null, $_POST["nuevoDocumento"]);

                $datos = array(
                    "tipoDocumento" => $_POST["nuevoTipoDocumento"],
                    "documentoId" => trim($_POST["nuevoDocumento"]),
                    "nombres" => trim($_POST["nuevoNombre"]),
                    "apellidos" => trim($_POST["nuevoApellido"]),
                    "correo" => trim($_POST["nuevoCorreo"]),
                    "fechaNacimiento" => $_POST["nuevoFechaNacimiento"],
                    "rol" => $rolNormalizado,
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
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: '¡Error al agregar el usuario!',
                            text: 'Ocurrió un problema al guardar el registro en la base de datos.',
                            showConfirmButton: true,
                            confirmButtonText: 'Cerrar'
                        });
                    </script>";
                }
            } else {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Datos inválidos',
                        text: 'Verifique que el documento tenga entre 5 y 20 dígitos numéricos, que nombres y apellidos solo contengan letras y que el correo sea válido.',
                        showConfirmButton: true,
                        confirmButtonText: 'Cerrar'
                    });
                </script>";
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
                preg_match('/^[0-9]{5,20}$/', trim($_POST["nuevoDocumento"])) &&
                preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]+$/', trim($_POST["nuevoNombre"])) &&
                preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]+$/', trim($_POST["nuevoApellido"])) &&
                filter_var(trim($_POST["nuevoCorreo"]), FILTER_VALIDATE_EMAIL)
            ) {

                if (empty($_POST["nuevaFicha"]) || !preg_match('/^[0-9]+$/', (string)$_POST["nuevaFicha"]) || !ModeloUsuarios::mdlExisteFicha((int)$_POST["nuevaFicha"])) {
                    echo "<script>
                        Swal.fire({
                            icon: 'warning',
                            title: '¡Ficha inválida!',
                            text: 'Debe seleccionar una ficha de formación válida de la lista.',
                            showConfirmButton: true,
                            confirmButtonText: 'Cerrar'
                        });
                    </script>";
                    return;
                }

                $tabla = "usuarios";

                // Hash seguro con sal aleatoria; rol forzado estrictamente a APRENDIZ
                $passEncriptado = self::generarHashPassword(trim($_POST["nuevoDocumento"]));
                $rolForzado = "APRENDIZ";
                $fichaId = (int)$_POST["nuevaFicha"];

                // ==========================================
                // VALIDAR IMAGEN
                // ==========================================
                $ruta = self::procesarSubidaFoto($_FILES["nuevaFoto"] ?? null, $_POST["nuevoDocumento"]);

                $datos = array(
                    "tipoDocumento" => $_POST["nuevoTipoDocumento"],
                    "documentoId" => trim($_POST["nuevoDocumento"]),
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
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: '¡Error al registrarse!',
                            text: 'Ocurrió un problema al guardar el registro. Intente nuevamente.',
                            showConfirmButton: true,
                            confirmButtonText: 'Cerrar'
                        });
                    </script>";
                }
            } else {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Datos inválidos',
                        text: 'Verifique que el documento tenga entre 5 y 20 dígitos numéricos, que nombres y apellidos solo contengan letras y que el correo sea válido.',
                        showConfirmButton: true,
                        confirmButtonText: 'Cerrar'
                    });
                </script>";
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
            $rolSesion = strtoupper($_SESSION["rol"] ?? "");
            if (!isset($_SESSION["iniciarSesion"]) || $_SESSION["iniciarSesion"] !== "ok" || !in_array($rolSesion, array("ADMIN", "GESTORA"), true)) {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Acceso denegado',
                        text: 'No tiene permisos para editar usuarios.',
                        showConfirmButton: true,
                        confirmButtonText: 'Cerrar'
                    });
                </script>";
                return;
            }

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
            $rolesPermitidos = array("APRENDIZ", "GESTORA", "ADMIN");
            $rolNormalizado = strtoupper(trim($_POST["editarRol"] ?? ""));

            if (
                in_array($_POST["editarTipoDocumento"] ?? "", $tiposPermitidos, true) &&
                in_array($rolNormalizado, $rolesPermitidos, true) &&
                preg_match('/^[0-9]+$/', trim($_POST["editarDocumento"])) &&
                preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]+$/', trim($_POST["editarNombre"])) &&
                preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]+$/', trim($_POST["editarApellido"])) &&
                filter_var(trim($_POST["editarCorreo"]), FILTER_VALIDATE_EMAIL)
            ) {
                $tabla = "usuarios";
                $usuarioDB = ModeloUsuarios::mdlMostrarUsuarios("usuarios", "id", $_POST["idUsuarioEditar"]);
                if (!$usuarioDB) {
                    return;
                }

                // Impedir que una GESTORA edite a un ADMIN o escale privilegios a ADMIN
                if ($rolSesion === "GESTORA" && (strtoupper($usuarioDB["rol"]) === "ADMIN" || $rolNormalizado === "ADMIN")) {
                    echo "<script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'Permiso insuficiente',
                            text: 'Solo un Administrador puede gestionar cuentas con rol ADMIN.',
                            showConfirmButton: true,
                            confirmButtonText: 'Cerrar'
                        });
                    </script>";
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
                if ($rolNormalizado === "APRENDIZ") {
                    if (empty($_POST["editarFicha"]) || !preg_match('/^[0-9]+$/', (string)$_POST["editarFicha"]) || !ModeloUsuarios::mdlExisteFicha((int)$_POST["editarFicha"])) {
                        echo "<script>
                            Swal.fire({
                                icon: 'warning',
                                title: '¡Ficha inválida!',
                                text: 'Debe seleccionar una ficha de formación válida de la lista para el aprendiz.',
                                showConfirmButton: true,
                                confirmButtonText: 'Cerrar'
                            });
                        </script>";
                        return;
                    }
                    $fichaId = (int)$_POST["editarFicha"];
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
                    "documentoId" => trim($_POST["editarDocumento"]),
                    "nombres" => trim($_POST["editarNombre"]),
                    "apellidos" => trim($_POST["editarApellido"]),
                    "correo" => trim($_POST["editarCorreo"]),
                    "fechaNacimiento" => $_POST["editarFechaNacimiento"],
                    "rol" => $rolNormalizado,
                    "password" => $passEncriptado,
                    "ficha_id" => $fichaId,
                    "foto" => $foto
                );

                $respuesta = ModeloUsuarios::mdlEditarUsuario($tabla, $datos);

                if ($respuesta == "ok") {
                    $contacto = array(
                        "direccion" => isset($_POST["editarDireccion"]) ? trim($_POST["editarDireccion"]) : "",
                        "telefono" => isset($_POST["editarTelefono"]) ? trim($_POST["editarTelefono"]) : "",
                        "codigo_dep" => isset($_POST["editarDepartamento"]) ? $_POST["editarDepartamento"] : "",
                        "codigo_ciu" => isset($_POST["editarCiudad"]) ? $_POST["editarCiudad"] : ""
                    );
                    ModeloUsuarios::mdlGuardarContacto($_POST["idUsuarioEditar"], $contacto);

                    // Si el usuario editado es el mismo en sesión, sincronizar variables de sesión
                    if ((int)$_POST["idUsuarioEditar"] === (int)($_SESSION["id"] ?? 0)) {
                        $_SESSION["nombres"] = $datos["nombres"];
                        $_SESSION["apellidos"] = $datos["apellidos"];
                        $_SESSION["rol"] = $datos["rol"];
                        $_SESSION["ficha_id"] = $datos["ficha_id"];
                        $_SESSION["foto"] = $datos["foto"];
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
                        title: '¡El nombre, apellidos o correo no son válidos!',
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

            if (
                preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]+$/', trim($_POST["editarNombrePerfil"])) &&
                preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]+$/', trim($_POST["editarApellidoPerfil"]))
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

    // ************************************
    // IMPORTAR APRENDICES MASIVAMENTE (CSV / EXCEL XLSX)
    // ************************************
    public function ctrImportarAprendices()
    {
        if (!isset($_POST["importarAprendices"])) {
            return;
        }

        $rolSesion = strtoupper($_SESSION["rol"] ?? "");
        if (!isset($_SESSION["iniciarSesion"]) || $_SESSION["iniciarSesion"] !== "ok" || !in_array($rolSesion, array("ADMIN", "GESTORA"), true)) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Acceso denegado',
                    text: 'No tiene permisos para importar aprendices.',
                    showConfirmButton: true,
                    confirmButtonText: 'Cerrar'
                });
            </script>";
            return;
        }

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

        if (!isset($_FILES["archivoImportarUsuarios"]["tmp_name"]) || empty($_FILES["archivoImportarUsuarios"]["tmp_name"])) {
            echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Archivo requerido',
                    text: 'Debe seleccionar un archivo CSV o Excel (.xlsx) válido.',
                    showConfirmButton: true,
                    confirmButtonText: 'Cerrar'
                });
            </script>";
            return;
        }

        $archivo = $_FILES["archivoImportarUsuarios"];
        $extension = strtolower(pathinfo($archivo["name"] ?? "", PATHINFO_EXTENSION));

        if (!in_array($extension, array("csv", "txt", "xlsx"), true)) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Formato no soportado',
                    text: 'Solo se permiten archivos en formato .CSV o Excel .XLSX.',
                    showConfirmButton: true,
                    confirmButtonText: 'Cerrar'
                });
            </script>";
            return;
        }

        if (($archivo["size"] ?? 0) > 5242880) { // 5 MB máximo
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Archivo demasiado grande',
                    text: 'El archivo no debe superar los 5 MB.',
                    showConfirmButton: true,
                    confirmButtonText: 'Cerrar'
                });
            </script>";
            return;
        }

        $filas = self::extraerFilasArchivoImportacion($archivo["tmp_name"], $extension);

        if (empty($filas)) {
            echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Archivo vacío o ilegible',
                    text: 'No se encontraron registros válidos para procesar en el archivo.',
                    showConfirmButton: true,
                    confirmButtonText: 'Cerrar'
                });
            </script>";
            return;
        }

        $tiposPermitidos = array("TI", "CC", "CE", "PEP", "PPT");
        $importados = 0;
        $ignorados = 0;
        $ignoradosDuplicados = 0;
        $ignoradosFicha = 0;
        $ignoradosFormato = 0;
        $cacheFichas = array();

        foreach ($filas as $indice => $fila) {
            // Ignorar filas completamente vacías
            $filaLimpia = array_map(function ($v) {
                return trim((string)$v);
            }, $fila);

            if (implode("", $filaLimpia) === "") {
                continue;
            }

            $colTipoDoc = strtoupper($filaLimpia[0] ?? "");
            $colDocId   = preg_replace('/\s+/', '', $filaLimpia[1] ?? "");

            // Detectar y omitir fila de encabezado si está en la primera fila
            if ($indice === 0 && (
                in_array(strtolower($colTipoDoc), array("tipo_documento", "tipo", "tipodocumento", "tipo doc"), true) ||
                !preg_match('/^[0-9]+$/', $colDocId)
            )) {
                continue;
            }

            $colNombres   = $filaLimpia[2] ?? "";
            $colApellidos = $filaLimpia[3] ?? "";
            $colFechaNac  = self::normalizarFechaImportacion($filaLimpia[4] ?? "");
            $colCorreo    = strtolower($filaLimpia[5] ?? "");
            $colCodFicha  = $filaLimpia[6] ?? "";
            $colDireccion = $filaLimpia[7] ?? "";
            $colTelefono  = $filaLimpia[8] ?? "";
            $colCodDep    = $filaLimpia[9] ?? "";
            $colCodCiu    = $filaLimpia[10] ?? "";

            // 1. Validar formato de campos obligatorios
            if (
                !in_array($colTipoDoc, $tiposPermitidos, true) ||
                !preg_match('/^[0-9]{5,20}$/', $colDocId) ||
                !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]+$/u', $colNombres) ||
                !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ ]+$/u', $colApellidos) ||
                $colFechaNac === null ||
                !filter_var($colCorreo, FILTER_VALIDATE_EMAIL) ||
                $colCodFicha === ""
            ) {
                $ignorados++;
                $ignoradosFormato++;
                continue;
            }

            // 2. Resolver ficha activa por su código (con caché en memoria)
            if (!array_key_exists($colCodFicha, $cacheFichas)) {
                $fichaEncontrada = ModeloUsuarios::mdlObtenerFichaPorCodigo($colCodFicha);
                $cacheFichas[$colCodFicha] = $fichaEncontrada ? (int)$fichaEncontrada["id_ficha"] : null;
            }

            $fichaId = $cacheFichas[$colCodFicha];
            if ($fichaId === null) {
                $ignorados++;
                $ignoradosFicha++;
                continue;
            }

            // 3. Preparar inserción como APRENDIZ
            $datosUsuario = array(
                "tipoDocumento" => $colTipoDoc,
                "documentoId" => $colDocId,
                "nombres" => $colNombres,
                "apellidos" => $colApellidos,
                "correo" => $colCorreo,
                "fechaNacimiento" => $colFechaNac,
                "rol" => "APRENDIZ",
                "password" => self::generarHashPassword($colDocId),
                "ficha_id" => $fichaId,
                "foto" => "documentos/anonimo/anonimo.png"
            );

            $datosContacto = array(
                "direccion" => $colDireccion,
                "telefono" => $colTelefono,
                "codigo_dep" => preg_match('/^[0-9]{2}$/', $colCodDep) ? $colCodDep : "76",
                "codigo_ciu" => preg_match('/^[0-9]{5}$/', $colCodCiu) ? $colCodCiu : "76834"
            );

            $resultado = ModeloUsuarios::mdlAgregarUsuario("usuarios", $datosUsuario, $datosContacto);

            if ($resultado === "ok") {
                $importados++;
            } else if ($resultado === "duplicate") {
                $ignorados++;
                $ignoradosDuplicados++;
            } else {
                $ignorados++;
                $ignoradosFormato++;
            }
        }

        $icono = ($importados > 0) ? "success" : "warning";
        $titulo = ($importados > 0) ? "¡Importación finalizada!" : "Importación sin nuevos registros";
        $detalleIgnorados = "";
        if ($ignorados > 0) {
            $detalleIgnorados = "<br><small class='text-muted'>Detalle ignorados: {$ignoradosDuplicados} duplicado(s), {$ignoradosFicha} por ficha inexistente/inactiva, {$ignoradosFormato} por formato inválido.</small>";
        }

        $htmlResumen = "<b>Registros importados:</b> {$importados}<br><b>Registros ignorados:</b> {$ignorados}{$detalleIgnorados}";

        echo "<script>
            Swal.fire({
                icon: '{$icono}',
                title: '{$titulo}',
                html: " . json_encode($htmlResumen, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . ",
                showConfirmButton: true,
                confirmButtonText: 'Aceptar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = 'Usuarios';
                }
            });
        </script>";
    }

    /**
     * Extrae las filas de un archivo CSV (con coma o punto y coma) o Excel (.xlsx nativo).
     */
    private static function extraerFilasArchivoImportacion($rutaArchivo, $extension)
    {
        if ($extension === "xlsx") {
            return self::leerFilasXlsx($rutaArchivo);
        }
        return self::leerFilasCsv($rutaArchivo);
    }

    /**
     * Lee archivos CSV detectando automáticamente el delimitador (';' o ',') y codificación UTF-8.
     */
    private static function leerFilasCsv($rutaArchivo)
    {
        $filas = array();
        if (!file_exists($rutaArchivo) || ($handle = fopen($rutaArchivo, "r")) === false) {
            return $filas;
        }

        $primeraLinea = fgets($handle);
        if ($primeraLinea === false) {
            fclose($handle);
            return $filas;
        }

        // Eliminar BOM UTF-8 si existe
        $primeraLinea = preg_replace('/^\xEF\xBB\xBF/', '', $primeraLinea);
        $delimitador = (substr_count($primeraLinea, ";") >= substr_count($primeraLinea, ",")) ? ";" : ",";

        rewind($handle);
        $esPrimera = true;
        while (($datos = fgetcsv($handle, 4096, $delimitador)) !== false) {
            if ($esPrimera && isset($datos[0])) {
                $datos[0] = preg_replace('/^\xEF\xBB\xBF/', '', $datos[0]);
                $esPrimera = false;
            }
            // Asegurar codificación UTF-8 válida en cada celda
            foreach ($datos as $k => $valor) {
                if (!mb_check_encoding($valor, 'UTF-8')) {
                    $datos[$k] = mb_convert_encoding($valor, 'UTF-8', 'ISO-8859-1');
                }
            }
            $filas[] = $datos;
        }
        fclose($handle);
        return $filas;
    }

    /**
     * Lee la primera hoja de un archivo Excel .xlsx usando ZipArchive y SimpleXMLElement nativos.
     */
    private static function leerFilasXlsx($rutaArchivo)
    {
        $filas = array();
        if (!class_exists('ZipArchive') || !file_exists($rutaArchivo)) {
            return $filas;
        }

        $zip = new ZipArchive();
        if ($zip->open($rutaArchivo) !== true) {
            return $filas;
        }

        $sharedStrings = array();
        $xmlShared = $zip->getFromName('xl/sharedStrings.xml');
        if ($xmlShared !== false) {
            $sxShared = @simplexml_load_string($xmlShared);
            if ($sxShared && isset($sxShared->si)) {
                foreach ($sxShared->si as $si) {
                    if (isset($si->t)) {
                        $sharedStrings[] = (string)$si->t;
                    } else if (isset($si->r)) {
                        $texto = "";
                        foreach ($si->r as $run) {
                            $texto .= (string)$run->t;
                        }
                        $sharedStrings[] = $texto;
                    } else {
                        $sharedStrings[] = "";
                    }
                }
            }
        }

        $xmlSheet = $zip->getFromName('xl/worksheets/sheet1.xml');
        if ($xmlSheet !== false) {
            $sxSheet = @simplexml_load_string($xmlSheet);
            if ($sxSheet && isset($sxSheet->sheetData->row)) {
                foreach ($sxSheet->sheetData->row as $row) {
                    $filaActual = array();
                    foreach ($row->c as $celda) {
                        $ref = (string)$celda['r'];
                        $letrasCol = preg_replace('/[0-9]/', '', $ref);
                        $indiceCol = 0;
                        $len = strlen($letrasCol);
                        for ($i = 0; $i < $len; $i++) {
                            $indiceCol = $indiceCol * 26 + (ord($letrasCol[$i]) - 64);
                        }
                        $indiceCol--; // 0-based

                        while (count($filaActual) < $indiceCol) {
                            $filaActual[] = "";
                        }

                        $tipo = (string)$celda['t'];
                        $valor = "";
                        if ($tipo === 's') {
                            $idx = (int)$celda->v;
                            $valor = $sharedStrings[$idx] ?? "";
                        } else if ($tipo === 'inlineStr') {
                            $valor = (string)$celda->is->t;
                        } else {
                            $valor = (string)$celda->v;
                        }
                        $filaActual[$indiceCol] = $valor;
                    }
                    $filas[] = $filaActual;
                }
            }
        }

        $zip->close();
        return $filas;
    }

    /**
     * Normaliza fechas en formato YYYY-MM-DD, DD/MM/YYYY, DD-MM-YYYY o número serial de Excel.
     */
    private static function normalizarFechaImportacion($valor)
    {
        $valor = trim((string)$valor);
        if ($valor === "") {
            return null;
        }

        // Formato estándar YYYY-MM-DD
        if (preg_match('/^(\d{4})[-\/](\d{2})[-\/](\d{2})$/', $valor, $m)) {
            if (checkdate((int)$m[2], (int)$m[3], (int)$m[1])) {
                return "{$m[1]}-{$m[2]}-{$m[3]}";
            }
            return null;
        }

        // Formato local DD/MM/YYYY o DD-MM-YYYY
        if (preg_match('/^(\d{2})[\/\-](\d{2})[\/\-](\d{4})$/', $valor, $m)) {
            if (checkdate((int)$m[2], (int)$m[1], (int)$m[3])) {
                return "{$m[3]}-{$m[2]}-{$m[1]}";
            }
            return null;
        }

        // Fecha serial numérica de Excel (ej. 37391)
        if (preg_match('/^\d{4,5}$/', $valor)) {
            $diasExcel = (int)$valor;
            if ($diasExcel > 10000 && $diasExcel < 60000) {
                $unixTimestamp = ($diasExcel - 25569) * 86400;
                return gmdate("Y-m-d", $unixTimestamp);
            }
        }

        return null;
    }
}//fin de la clase ControladorUsuarios