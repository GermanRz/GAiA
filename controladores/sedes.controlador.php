<?php

class ControladorSedes{

    static public function ctrListarSedes(){
        $respuesta = ModeloSedes::mdlListarSedes();
        return $respuesta;
    }

    public function ctrAgregarSede(){
        if (isset($_POST["nuevoNombreSede"]) && isset($_POST["nuevaDireccionSede"])) {
            if (preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ0-9 \.]+$/', $_POST["nuevoNombreSede"]) &&
                preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ0-9 \.,\#\-]+$/', $_POST["nuevaDireccionSede"])) {
                
                $tabla = "sedes";

                $datos = array(
                  "descripcion_sede" => $_POST["nuevoNombreSede"],
                  "direccion_sede" => $_POST["nuevaDireccionSede"]
                );

                $respuesta = ModeloSedes::mdlAgregarSede($tabla, $datos);

                if($respuesta == "ok"){
                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'La sede ha sido guardada correctamente',
                            showConfirmButton: true,
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = 'sedes';
                            }
                        });
                    </script>";
                }else{
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: '¡Error al agregar la sede!',
                            showConfirmButton: true,
                            confirmButtonText: 'Cerrar'
                        });
                    </script>";
                }
            } else {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: '¡Los campos no pueden ir vacíos o llevar caracteres especiales no permitidos!',
                        showConfirmButton: true,
                        confirmButtonText: 'Cerrar'
                    });
                </script>";
            }
        }
    }

    static public function ctrMostrarSedes($item, $valor){
        $tabla = "sedes";
        $respuesta = ModeloSedes::mdlMostrarSedes($tabla, $item, $valor);
        return $respuesta;
    }

    public function ctrEditarSede(){
        if (isset($_POST["editarNombreSede"]) && isset($_POST["idSedeEditar"])) {
            if (preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ0-9 \.]+$/', $_POST["editarNombreSede"]) &&
                preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ0-9 \.,\#\-]+$/', $_POST["editarDireccionSede"])) {
                
                $tabla = "sedes";

                $datos = array(
                  "id_sede" => $_POST["idSedeEditar"],
                  "descripcion_sede" => $_POST["editarNombreSede"],
                  "direccion_sede" => $_POST["editarDireccionSede"]
                );

                $respuesta = ModeloSedes::mdlEditarSede($tabla, $datos);

                if($respuesta == "ok"){
                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'La sede ha sido editada correctamente',
                            showConfirmButton: true,
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = 'sedes';
                            }
                        });
                    </script>";
                }else{
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: '¡Error al editar la sede!',
                            showConfirmButton: true,
                            confirmButtonText: 'Cerrar'
                        });
                    </script>";
                }
            } else {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: '¡Los campos no pueden ir vacíos o llevar caracteres especiales no permitidos!',
                        showConfirmButton: true,
                        confirmButtonText: 'Cerrar'
                    });
                </script>";
            }
        }
    }

    static public function ctrCambiarEstadoSede($idSede, $estado){
        $tabla = "sedes";
        $respuesta = ModeloSedes::mdlCambiarEstadoSede($tabla, $idSede, $estado);
        return $respuesta;
    }

    static public function ctrInactivarSedeRecursiva($idSede){
        $respuesta = ModeloSedes::mdlInactivarSedeRecursiva($idSede);
        return $respuesta;
    }

    static public function ctrCalcularImpactoInactivacion($idSede){
        $respuesta = ModeloSedes::mdlCalcularImpactoInactivacion($idSede);
        return $respuesta;
    }
}
