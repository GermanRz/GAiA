<?php

require_once "conexion.php";


class ModeloUsuarios{

    static public function mdlIngresarUsuario($documento){
        $stmt = Conexion::conectar()->prepare("SELECT * FROM usuarios WHERE documento_id = :documento");
        $stmt->bindParam(":documento", $documento, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }  //fin del metodo mdlIngresarUsuario

    static public function mdlListarUsuarios(){
        $stmt = Conexion::conectar()->prepare("SELECT * FROM usuarios");
        $stmt->execute();
        return $stmt->fetchAll();    
    }

    static public function mdlAgregarUsuario($tabla, $datos){
        
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (tipo_documento, documento_id, nombres, apellidos, correo, fecha_nacimiento, rol, password) VALUES (:tipoDocumento, :documentoId, :nombres, :apellidos, :correo, :fechaNacimiento, :rol, :documentoId)");
        $stmt->bindParam(":tipoDocumento", $datos["tipoDocumento"], PDO::PARAM_STR);
        $stmt->bindParam(":documentoId", $datos["documentoId"], PDO::PARAM_STR);
        $stmt->bindParam(":nombres", $datos["nombres"], PDO::PARAM_STR);
        $stmt->bindParam(":apellidos", $datos["apellidos"], PDO::PARAM_STR);
        $stmt->bindParam(":correo", $datos["correo"], PDO::PARAM_STR);
        $stmt->bindParam(":fechaNacimiento", $datos["fechaNacimiento"], PDO::PARAM_STR);
        $stmt->bindParam(":rol", $datos["rol"], PDO::PARAM_STR);
        if ($stmt->execute()){
            return "ok";

        }else{
            return "error";
        }
    }



} // fin de la clase ModeloUsuarios