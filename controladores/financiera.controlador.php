<?php

class ControladorFinanciera
{

    /*=============================================
    MOSTRAR BENEFICIARIOS POR CONVOCATORIA
    =============================================*/
    static public function ctrMostrarBeneficiarios($idConvocatoria)
    {
        $respuesta = ModeloFinanciera::mdlMostrarBeneficiarios($idConvocatoria);
        return $respuesta;
    }

    /*=============================================
    MOSTRAR BENEFICIARIO INDIVIDUAL POR ID DE INSCRIPCION
    =============================================*/
    static public function ctrMostrarBeneficiario($idInscripcion)
    {
        $respuesta = ModeloFinanciera::mdlMostrarBeneficiario($idInscripcion);
        return $respuesta;
    }

    /*=============================================
    LISTAR CONVOCATORIAS CON BENEFICIARIOS
    =============================================*/
    static public function ctrListarConvocatoriasFinanciera()
    {
        $respuesta = ModeloFinanciera::mdlListarConvocatoriasFinanciera();
        return $respuesta;
    }

    /*=============================================
    LISTAR PENDIENTES BANCARIOS
    =============================================*/
    static public function ctrListarPendientesBancarios()
    {
        $respuesta = ModeloFinanciera::mdlListarPendientesBancarios();
        return $respuesta;
    }

    /*=============================================
    APROBAR DOCUMENTO BANCARIO
    =============================================*/
    static public function ctrAprobarDocumentoBancario($idInscripcion, $mesesOtorgados, $fechaInicio)
    {
        $respuesta = ModeloFinanciera::mdlAprobarDocumentoBancario($idInscripcion, $mesesOtorgados, $fechaInicio);
        return $respuesta;
    }

    /*=============================================
    RECHAZAR DOCUMENTO BANCARIO
    =============================================*/
    static public function ctrRechazarDocumentoBancario($idInscripcion, $observacion)
    {
        $respuesta = ModeloFinanciera::mdlRechazarDocumentoBancario($idInscripcion, $observacion);

        // Si hay un documento fisico, podria eliminarse aqui, pero lo dejaremos asi para no complicar, 
        // igual el aprendiz lo va a sobreescribir.
        return $respuesta;
    }

}
