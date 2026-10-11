<?php
require_once 'serverside.php';
$table_data->get(
    'vista_usuarios',
    'id',
    array('id', 'tipo_documento', 'documento_id', 'nombres', 'apellidos', 'correo', 'codigo', 'rol', 'estado')
);
