<?php

class ControladorSedes
{

    // ************************************
    // LISTA DE SEDES EN LA VENTANA PRINCIPAL
    // ************************************   
    static public function ctrListarSedes()
    {
        $respuesta = ModeloSedes::mdlListarSede();
        return $respuesta;
    } //fin del metodo ctrListarSedes

    // ************************************
    // AGREGAR SEDE A LA BD
    // ************************************
    public function ctrAgregarSede()
    {


        if (  
            isset($_POST["nuevaDireccionSede"]) &&
            isset($_POST["nuevaDescripcionSede"])
        ) {
            if (
                preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ0-9 ]+$/', $_POST["nuevaDescripcionSede"]) &&
                preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚÑñ0-9#\-\., ]+$/', $_POST["nuevaDireccionSede"])
            ) {

                $tabla = "sedes";
                $datos = array(
                    "descripcionSede" => $_POST["nuevaDescripcionSede"],
                    "direccionSede" => $_POST["nuevaDireccionSede"]
                );
                $respuesta = ModeloSedes::mdlAgregarSede($tabla, $datos);

                if ($respuesta == "ok") {
                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'La sede ha sido registrada correctamente',
                            showConfirmButton: true,
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = 'sedes';
                            }
                        });
                                
                        
                    </script>";
                    // echo "<br><div class='alert alert-success'>La sede ha sido registrado correctamente</div>";
                } else {
                    echo "<br><div class='alert alert-danger'>Error al agregar la sede</div>";
                }




            }
        }  // fin del isset
    }

    // ************************************
    // TRAER UNA SEDE ESPECIFICA DE LA BD
    // ************************************
    static public function ctrMostrarSedes($item, $valor)
    {
        $tabla = "sedes";
        $respuesta = ModeloSedes::mdlMostrarSedes($tabla, $item, $valor);
        return $respuesta;
    }

}//fin de la clase ControladorSedes