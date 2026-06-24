<?php

require_once "conexion.php";


class ModeloUsuarios
{


    // ************************************
    // LOGIN DE USUARIO 
    // ************************************
    static public function mdlIngresarUsuario($documento)
    {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM usuarios WHERE documento_id = :documento");
        $stmt->bindParam(":documento", $documento, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }  //fin del metodo mdlIngresarUsuario


    // ************************************
    // LISA DE DE USUARIOS EN LA VENTANA PRINCIPAL
    // ************************************    
    static public function mdlListarUsuarios()
    {
        $stmt = Conexion::conectar()->prepare("SELECT u.*, f.codigo FROM usuarios u LEFT JOIN fichas f ON f.id_ficha = u.ficha_id WHERE u.rol<>'Administrador';");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ************************************
    // LISTA DE FICHAS
    // ************************************    
    static public function mdlListarFichas()
    {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM fichas");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ************************************
    // AGREGAR USUARIO A LA BD
    // ************************************    
    static public function mdlAgregarUsuario($tabla, $datos, $contacto = null)
    {
        $conexion = Conexion::conectar();

        try {
            $conexion->beginTransaction();

            $stmt = $conexion->prepare("INSERT INTO $tabla (tipo_documento, documento_id, nombres, apellidos, correo, fecha_nacimiento, rol, password, ficha_id, foto) VALUES (:tipoDocumento, :documentoId, :nombres, :apellidos, :correo, :fechaNacimiento, :rol, :password, :ficha_id, :foto)");
            $stmt->bindParam(":tipoDocumento", $datos["tipoDocumento"], PDO::PARAM_STR);
            $stmt->bindParam(":documentoId", $datos["documentoId"], PDO::PARAM_STR);
            $stmt->bindParam(":nombres", $datos["nombres"], PDO::PARAM_STR);
            $stmt->bindParam(":apellidos", $datos["apellidos"], PDO::PARAM_STR);
            $stmt->bindParam(":correo", $datos["correo"], PDO::PARAM_STR);
            $stmt->bindParam(":fechaNacimiento", $datos["fechaNacimiento"], PDO::PARAM_STR);
            $stmt->bindParam(":rol", $datos["rol"], PDO::PARAM_STR);
            $stmt->bindParam(":password", $datos["password"], PDO::PARAM_STR);
            $stmt->bindParam(":ficha_id", $datos["ficha_id"], PDO::PARAM_INT);
            $stmt->bindParam(":foto", $datos["foto"], PDO::PARAM_STR);
            
            if ($stmt->execute()) {
                $idUsuario = $conexion->lastInsertId();

                if ($contacto != null) {
                    $stmtContacto = $conexion->prepare("INSERT INTO usuarios_contacto (usuario_id, direccion, telefono, codigo_dep, codigo_ciu) VALUES (:usuario_id, :direccion, :telefono, :codigo_dep, :codigo_ciu)");
                    
                    $direccion = isset($contacto["direccion"]) ? $contacto["direccion"] : "";
                    $telefono = isset($contacto["telefono"]) ? $contacto["telefono"] : "";
                    $codigo_dep = !empty($contacto["codigo_dep"]) ? $contacto["codigo_dep"] : null;
                    $codigo_ciu = !empty($contacto["codigo_ciu"]) ? $contacto["codigo_ciu"] : null;

                    $stmtContacto->bindParam(":usuario_id", $idUsuario, PDO::PARAM_INT);
                    $stmtContacto->bindParam(":direccion", $direccion, PDO::PARAM_STR);
                    $stmtContacto->bindParam(":telefono", $telefono, PDO::PARAM_STR);
                    $stmtContacto->bindParam(":codigo_dep", $codigo_dep, PDO::PARAM_STR);
                    $stmtContacto->bindParam(":codigo_ciu", $codigo_ciu, PDO::PARAM_STR);

                    if (!$stmtContacto->execute()) {
                        $conexion->rollBack();
                        return "error";
                    }
                }

                $conexion->commit();
                return "ok";
            } else {
                $conexion->rollBack();
                return "error";
            }
        } catch (PDOException $e) {
            if ($conexion->inTransaction()) {
                $conexion->rollBack();
            }
            if ($e->getCode() == 23000) {
                return "duplicate";
            }
            return "error";
        }
    }

    static public function mdlMostrarUsuarios($tabla, $item, $valor)
    {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :valor");
        $stmt->bindParam(":valor", $valor, PDO::PARAM_STR);
        error_log("valor en el modelo:" . $tabla);
        $stmt->execute();
        return $stmt->fetch();
    }

    // ************************************
    // EDITAR USUARIO EN LA BD
    // ************************************    
    static public function mdlEditarUsuario($tabla, $datos)
    {
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET tipo_documento = :tipoDocumento, documento_id = :documentoId, nombres = :nombres, apellidos = :apellidos, correo = :correo, fecha_nacimiento = :fechaNacimiento, rol = :rol, password = :password, ficha_id = :ficha_id, foto = :foto WHERE id = :id");
        
        $stmt->bindParam(":tipoDocumento", $datos["tipoDocumento"], PDO::PARAM_STR);
        $stmt->bindParam(":documentoId", $datos["documentoId"], PDO::PARAM_STR);
        $stmt->bindParam(":nombres", $datos["nombres"], PDO::PARAM_STR);
        $stmt->bindParam(":apellidos", $datos["apellidos"], PDO::PARAM_STR);
        $stmt->bindParam(":correo", $datos["correo"], PDO::PARAM_STR);
        $stmt->bindParam(":fechaNacimiento", $datos["fechaNacimiento"], PDO::PARAM_STR);
        $stmt->bindParam(":rol", $datos["rol"], PDO::PARAM_STR);
        $stmt->bindParam(":password", $datos["password"], PDO::PARAM_STR);
        $stmt->bindParam(":ficha_id", $datos["ficha_id"], PDO::PARAM_INT);
        $stmt->bindParam(":foto", $datos["foto"], PDO::PARAM_STR);
        $stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);

        try {
            if ($stmt->execute()) {
                return "ok";
            } else {
                return "error";
            }
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                return "duplicate";
            }
            return "error";
        }
    }

    // ************************************
    // EDITAR PERFIL EN LA BD
    // ************************************    
    static public function mdlEditarPerfil($tabla, $datos)
    {
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET nombres = :nombres, apellidos = :apellidos, password = :password, foto = :foto WHERE id = :id");
        
        $stmt->bindParam(":nombres", $datos["nombres"], PDO::PARAM_STR);
        $stmt->bindParam(":apellidos", $datos["apellidos"], PDO::PARAM_STR);
        $stmt->bindParam(":password", $datos["password"], PDO::PARAM_STR);
        $stmt->bindParam(":foto", $datos["foto"], PDO::PARAM_STR);
        $stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
    }

    // ************************************
    // ACTUALIZAR ESTADO DE UN USUARIO
    // ************************************
    static public function mdlCambiarEstadoUsuario($tabla, $idUsuario, $estado)
    {
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET estado = :estado WHERE id = :id");
        $stmt->bindParam(":estado", $estado, PDO::PARAM_STR);
        $stmt->bindParam(":id", $idUsuario, PDO::PARAM_STR);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }  // fin del metodo mdlCambiarEstadoUsuario

    // ************************************
    // OBTENER DEPARTAMENTOS
    // ************************************
    static public function mdlObtenerDepartamentos()
    {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM departamentos ORDER BY nombre ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ************************************
    // OBTENER CIUDADES
    // ************************************
    static public function mdlObtenerCiudades($codigo_dep)
    {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM ciudades WHERE codigo_dep = :codigo_dep ORDER BY nombre ASC");
        $stmt->bindParam(":codigo_dep", $codigo_dep, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ************************************
    // OBTENER CONTACTO DE USUARIO
    // ************************************
    static public function mdlObtenerContactoUsuario($usuario_id)
    {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM usuarios_contacto WHERE usuario_id = :usuario_id");
        $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // ************************************
    // GUARDAR O ACTUALIZAR CONTACTO (1:1)
    // ************************************
    static public function mdlGuardarContacto($usuario_id, $datos)
    {
        $stmt = Conexion::conectar()->prepare("INSERT INTO usuarios_contacto (usuario_id, direccion, telefono, codigo_dep, codigo_ciu) VALUES (:usuario_id, :direccion, :telefono, :codigo_dep, :codigo_ciu) ON DUPLICATE KEY UPDATE direccion = :direccion, telefono = :telefono, codigo_dep = :codigo_dep, codigo_ciu = :codigo_ciu");
        
        $direccion = isset($datos["direccion"]) ? $datos["direccion"] : "";
        $telefono = isset($datos["telefono"]) ? $datos["telefono"] : "";
        $codigo_dep = !empty($datos["codigo_dep"]) ? $datos["codigo_dep"] : null;
        $codigo_ciu = !empty($datos["codigo_ciu"]) ? $datos["codigo_ciu"] : null;

        $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
        $stmt->bindParam(":direccion", $direccion, PDO::PARAM_STR);
        $stmt->bindParam(":telefono", $telefono, PDO::PARAM_STR);
        $stmt->bindParam(":codigo_dep", $codigo_dep, PDO::PARAM_STR);
        $stmt->bindParam(":codigo_ciu", $codigo_ciu, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "ok";
        } else {
            // Uncomment to debug if needed
            // error_log(print_r($stmt->errorInfo(), true));
            return "error";
        }
    }

} // fin de la clase ModeloUsuarios