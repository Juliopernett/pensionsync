<?php
include_once 'encuesta.php';
include_once 'pregunta.php';
include_once 'calificacion.php';
include_once 'respuesta.php';

$encuesta   = new Encuesta();
$pregunta   = new Pregunta();
$calificacion = new Calificacion();
$respuesta = new Respuesta();

switch ($_POST['opcion']) {
	case '1e':
	    try
{
	$id=$encuesta->registrar($_POST['descripcion']);
	echo json_encode(array('guardado' => TRUE,'id_encuesta'=>$id));
}
catch (Exception $ex)
{
	 echo json_encode(array('guardado' => FALSE));
}
		
		break;
	case '2e':	
		$res = $encuesta->buscar($id);
		echo json_encode($res);		
		break;
	case '3e':
			$encuesta->editar($id,$desc,$letra, $num);
	break;
	case '1p':
	    try
{
	$pregunta->registrar($_POST['id_encuesta'],$_POST['descripcion'],$_POST['orden'],$_POST['tipo']);
	echo json_encode(array('guardado' => TRUE));
}
catch (Exception $ex)
{
	 echo json_encode(array('guardado' => FALSE));
}
		
		break;
	case '1c':
	    try
{
	$calificacion->registrar($_POST['descripcion']);
	echo json_encode(array('guardado' => TRUE));
}
catch (Exception $ex)
{
	 echo json_encode(array('guardado' => FALSE));
}
		
		break;
		case '2p':
	    try
{
	$pregunta->asignar_posible_calificacion($_POST['id_calificacion'],$_POST['id_pregunta']);
	echo json_encode(array('guardado' => TRUE));
}
catch (Exception $ex)
{
	 echo json_encode(array('guardado' => FALSE));
}
		
		break;
		case '1r':
	    try
{    
	$respuesta->registrar_predefinidas($_POST['rtasPre']);
	$respuesta->registrar_libres($_POST['rtasLib']);
    echo json_encode(array('guardado' => TRUE));
}
catch (Exception $ex)
{
	 echo json_encode(array('guardado' => FALSE));
}
	
}

?>