<?php
extract($_POST);
include_once 'class_solicitud.php';
$disc       = new regSolicitud();	
try
{
	$disc->reg_solicitud($user_id,$id_tiposolicitud, $descripcion_solicitud,$fecha);
	$disc->reg_seguimiento();
	echo json_encode(array('guardado' => TRUE));
}
catch (Exception $ex)
{
	 echo json_encode(array('guardado' => FALSE));
}
?>