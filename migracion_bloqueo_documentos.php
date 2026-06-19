<?php
require_once "modelos/conexion.php";

try {
    $conexion = Conexion::conectar();

    // 1. Ampliar ENUM de estados: mantener PENDIENTE,SELECCIONADO + agregar EN_REVISION,DEVUELTA
    $sqlEnum = "ALTER TABLE inscripciones MODIFY COLUMN estado ENUM('PENDIENTE', 'EN_REVISION', 'DEVUELTA', 'SELECCIONADO') DEFAULT 'PENDIENTE'";
    $conexion->exec($sqlEnum);
    echo "[OK] ENUM de estados actualizado: PENDIENTE, EN_REVISION, DEVUELTA, SELECCIONADO\n";

    // 2. Agregar columna subsanacion_consumida si no existe
    try {
        $sqlCol = "ALTER TABLE inscripciones ADD COLUMN subsanacion_consumida TINYINT(1) DEFAULT 0";
        $conexion->exec($sqlCol);
        echo "[OK] Columna subsanacion_consumida añadida correctamente.\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), "Duplicate column name") !== false) {
            echo "[OK] La columna subsanacion_consumida ya existe.\n";
        } else {
            throw $e;
        }
    }

    echo "\nMigración completada exitosamente.\n";

} catch (Exception $e) {
    echo "[ERROR] " . $e->getMessage() . "\n";
}
