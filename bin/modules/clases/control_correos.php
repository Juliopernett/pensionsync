<?php
include_once 'notificaciones.php';
$id = $_POST['id'];
$disc       = new listCorreo();
$disc->buscar_fechas();
?>