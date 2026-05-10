<?php

require_once "conexion.php";


class ModeloSedes{
    
    // ************************************
    // LISTA DE SEDES EN LA VENTANA PRINCIPAL
    // ************************************    
    static public function mdlListarSede(){
        $stmt = Conexion::conectar()->prepare("SELECT * FROM sedes");
        $stmt->execute();
        return $stmt->fetchAll();    
    }

    // ************************************
    // AGREGAR SEDE A LA BD
    // ************************************    
    static public function mdlAgregarSede($tabla, $datos){
        
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla ( nombre, descripcion_sede, direccion_sede) VALUES ( :nombre, :descripcionSede, :direccionSede)");
        $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":descripcionSede", $datos["descripcionSede"], PDO::PARAM_STR);
        $stmt->bindParam(":direccionSede", $datos["direccionSede"], PDO::PARAM_STR);
        if ($stmt->execute()){
            return "ok";

        }else{
            return "error";
        }
    }

    static public function mdlMostrarSedes($tabla, $item, $valor){
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :valor");
        $stmt->bindParam(":valor", $valor, PDO::PARAM_STR);
        error_log("valor en el modelo:" . $tabla);
        $stmt->execute();
        return $stmt->fetch();
    }

} // fin de la clase ModeloSedes