<?php

class ControladorConvocatorias {

    // ==============================================
    // CREAR O EDITAR CONVOCATORIA
    // ==============================================
    public function ctrCrearConvocatoria() {
        
        // Verifica si viene POST
        if(isset($_POST["apoyo_id"]) && isset($_POST["fecha_inicio"])) {
            
            // Validaciones básicas de que no vengan vacíos los campos numéricos
            if($_POST["apoyo_id"] != "" && $_POST["cupos_personas"] > 0) {
                
                $tabla = "convocatorias";
                
                // Agrupamos la cabecera
                $datos = array(
                    "apoyo_id" => $_POST["apoyo_id"],
                    "fecha_inicio" => $_POST["fecha_inicio"],
                    "fecha_fin" => $_POST["fecha_fin"],
                    "cupos_personas" => $_POST["cupos_personas"],
                    "duracion_meses" => $_POST["duracion_meses"],
                    "estado_en_convocatoria" => $_POST["estado_en_convocatoria"]
                );

                // Agrupamos el baremo que viene con sintaxis de array name="baremo[key][]"
                $baremo = isset($_POST["baremo"]) ? $_POST["baremo"] : array();

                // Llama al modelo transaccional dependiendo si hay ID de edición
                if(!isset($_POST["id_convocatoria_editar"]) || $_POST["id_convocatoria_editar"] == "") {
                    // --- MODO CREAR ---
                    $respuesta = ModeloConvocatorias::mdlCrearConvocatoria($tabla, $datos, $baremo);
                    $mensaje = "creada";
                } else {
                    // --- MODO EDITAR ---
                    $datos["id_convocatoria"] = $_POST["id_convocatoria_editar"];
                    $respuesta = ModeloConvocatorias::mdlEditarConvocatoria($tabla, $datos, $baremo);
                    $mensaje = "actualizada";
                }

                if($respuesta == "ok") {
                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'La convocatoria ha sido ".$mensaje." correctamente',
                            showConfirmButton: true,
                            confirmButtonText: 'Aceptar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = 'convocatorias';
                            }
                        });
                    </script>";
                } else {
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: '¡Error en base de datos!',
                            text: '".$respuesta."',
                            showConfirmButton: true,
                            confirmButtonText: 'Cerrar'
                        });
                    </script>";
                }

            } else {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: '¡Los campos críticos no pueden ir vacíos!',
                        showConfirmButton: true,
                        confirmButtonText: 'Cerrar'
                    });
                </script>";
            }
        }
    }

    // ==============================================
    // LISTAR CONVOCATORIAS
    // ==============================================
    static public function ctrListarConvocatorias() {
        $tabla = "convocatorias";
        $respuesta = ModeloConvocatorias::mdlListarConvocatorias($tabla);
        return $respuesta;
    }

    // ==============================================
    // MOSTRAR UNA SOLA CONVOCATORIA (Para rellenar al editar)
    // ==============================================
    static public function ctrMostrarConvocatoria($item, $valor) {
        $tabla = "convocatorias";
        $respuesta = ModeloConvocatorias::mdlMostrarConvocatoria($tabla, $item, $valor);
        return $respuesta;
    }
    
    // ==============================================
    // MOSTRAR BAREMO (Para rellenar filas al editar)
    // ==============================================
    static public function ctrMostrarBaremo($valor) {
        $respuesta = ModeloConvocatorias::mdlMostrarBaremo($valor);
        return $respuesta;
    }

    // ==============================================
    // LISTAR CONVOCATORIAS ABIERTAS
    // ==============================================
    static public function ctrListarConvocatoriasActivas() {
        $tablaConvocatorias = "convocatorias";
        $tablaApoyos = "apoyos";
        
        $respuesta = ModeloConvocatorias::mdlListarConvocatoriasActivas($tablaConvocatorias, $tablaApoyos);
        return $respuesta;
    }    
}
