<?php

require_once "conexion.php";

class ModeloRelevos
{
    /*=============================================
    MOSTRAR HISTORIAL DE RELEVOS
    =============================================*/
    static public function mdlMostrarHistorialRelevos()
    {
        $stmt = Conexion::conectar()->prepare("
            SELECT 
                hr.id,
                CONCAT(u_sal.nombres, ' ', u_sal.apellidos) AS aprendiz_saliente,
                CONCAT(u_ent.nombres, ' ', u_ent.apellidos) AS aprendiz_entrante,
                hr.fecha_relevo,
                hr.motivo_salida,
                hr.meses_restantes
            FROM historial_relevos hr
            LEFT JOIN asignaciones a_sal ON hr.asignacion_saliente_id = a_sal.id
            LEFT JOIN inscripciones i_sal ON a_sal.inscripcion_id = i_sal.id
            LEFT JOIN usuarios u_sal ON i_sal.usuario_id = u_sal.id
            LEFT JOIN inscripciones i_ent ON hr.inscripcion_entrante_id = i_ent.id
            LEFT JOIN usuarios u_ent ON i_ent.usuario_id = u_ent.id
            ORDER BY hr.id DESC
        ");

        $stmt->execute();

        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = null;

        return $resultados;
    }
}
