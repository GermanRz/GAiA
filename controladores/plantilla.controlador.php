<?php
 
require_once "modelos/usuarios.modelo.php";

class ControladorPlantilla{
    public function ctrTraerPlantilla(){
        // Validar estado de usuario en cada request (middleware de sesión)
        if (isset($_SESSION["iniciarSesion"]) && $_SESSION["iniciarSesion"] == "ok") {
            // Excluir rutas que no requieren validación (login, salir, error404)
            $rutasExcluidas = ["Salir", "error404"];
            $rutaActual = isset($_GET["ruta"]) ? $_GET["ruta"] : "inicio";
            
            if (!in_array($rutaActual, $rutasExcluidas)) {
                $usuario = ModeloUsuarios::mdlMostrarUsuarios("usuarios", "id", $_SESSION["id"]);
                if (!$usuario || $usuario["estado"] != "activo") {
                    session_destroy();
                    echo "<script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'Sesión expirada',
                            text: 'Su cuenta ha sido inactivada. Contacte al administrador.',
                            showConfirmButton: true,
                            confirmButtonText: 'Entendido'
                        }).then(() => { window.location = 'inicio'; });
                    </script>";
                    return;
                }
                // Actualizar datos de sesión por si cambiaron
                $_SESSION["nombres"] = $usuario["nombres"];
                $_SESSION["apellidos"] = $usuario["apellidos"];
                $_SESSION["foto"] = $usuario["foto"];
                $_SESSION["rol"] = $usuario["rol"];
                $_SESSION["ficha_id"] = $usuario["ficha_id"];
            }
        }
        include "vistas/plantilla.php";
    }
}