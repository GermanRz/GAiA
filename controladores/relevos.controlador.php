<?php

class ControladorRelevos
{
    /*=============================================
    MOSTRAR HISTORIAL DE RELEVOS
    =============================================*/
    static public function ctrMostrarHistorialRelevos()
    {
        $respuesta = ModeloRelevos::mdlMostrarHistorialRelevos();
        return $respuesta;
    }
}
