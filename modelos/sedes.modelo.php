<?php

require_once "conexion.php";

class ModeloSedes
{
    // LISTAR SEDES
    static public function mdlListarSedes()
    {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM sedes");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // AGREGAR SEDE
    static public function mdlAgregarSede($tabla, $datos)
    {
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (descripcion_sede, direccion_sede) VALUES (:descripcion_sede, :direccion_sede)");
        $stmt->bindParam(":descripcion_sede", $datos["descripcion_sede"], PDO::PARAM_STR);
        $stmt->bindParam(":direccion_sede", $datos["direccion_sede"], PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
    }

    // MOSTRAR SEDES
    static public function mdlMostrarSedes($tabla, $item, $valor)
    {
        if($item != null){
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :valor");
            $stmt->bindParam(":valor", $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();
        }else{
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
            $stmt->execute();
            return $stmt->fetchAll();
        }
    }

    // EDITAR SEDE
    static public function mdlEditarSede($tabla, $datos)
    {
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET descripcion_sede = :descripcion_sede, direccion_sede = :direccion_sede WHERE id_sede = :id_sede");
        
        $stmt->bindParam(":descripcion_sede", $datos["descripcion_sede"], PDO::PARAM_STR);
        $stmt->bindParam(":direccion_sede", $datos["direccion_sede"], PDO::PARAM_STR);
        $stmt->bindParam(":id_sede", $datos["id_sede"], PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
    }

    // CAMBIAR ESTADO SEDE
    static public function mdlCambiarEstadoSede($tabla, $idSede, $estado)
    {
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET estado = :estado WHERE id_sede = :id");
        $stmt->bindParam(":estado", $estado, PDO::PARAM_STR);
        $stmt->bindParam(":id", $idSede, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // INACTIVACIÓN RECURSIVA: SEDE → FICHAS → APRENDICES
    static public function mdlInactivarSedeRecursiva($idSede)
    {
        $conexion = Conexion::conectar();
        try {
            $conexion->beginTransaction();

            // 1. Inactivar sede
            $stmt = $conexion->prepare("UPDATE sedes SET estado = 'inactivo' WHERE id_sede = :id");
            $stmt->execute([':id' => $idSede]);

            // 2. Inactivar fichas de la sede
            $stmt = $conexion->prepare("UPDATE fichas SET estado = 'inactivo' WHERE sede_id = :idSede");
            $stmt->execute([':idSede' => $idSede]);

            // 3. Inactivar aprendices de esas fichas (solo rol APRENDIZ)
            $stmt = $conexion->prepare("
                UPDATE usuarios u 
                INNER JOIN fichas f ON u.ficha_id = f.id_ficha 
                SET u.estado = 'inactivo' 
                WHERE f.sede_id = :idSede AND u.rol = 'APRENDIZ'
            ");
            $stmt->execute([':idSede' => $idSede]);

            $conexion->commit();
            return "ok";
        } catch (Exception $e) {
            $conexion->rollBack();
            return "error: " . $e->getMessage();
        }
    }

    // CALCULAR IMPACTO ANTES DE INACTIVAR
    static public function mdlCalcularImpactoInactivacion($idSede)
    {
        $conexion = Conexion::conectar();
        
        // Fichas activas afectadas
        $stmt = $conexion->prepare("SELECT COUNT(*) as total FROM fichas WHERE sede_id = :id AND estado = 'activo'");
        $stmt->execute([':id' => $idSede]);
        $fichas = $stmt->fetch()['total'];
        
        // Aprendices activos afectados
        $stmt = $conexion->prepare("
            SELECT COUNT(*) as total FROM usuarios u 
            INNER JOIN fichas f ON u.ficha_id = f.id_ficha 
            WHERE f.sede_id = :idSede AND u.rol = 'APRENDIZ' AND u.estado = 'activo'
        ");
        $stmt->execute([':idSede' => $idSede]);
        $aprendices = $stmt->fetch()['total'];
        
        return [
            'fichas' => (int)$fichas,
            'aprendices' => (int)$aprendices
        ];
    }

}
