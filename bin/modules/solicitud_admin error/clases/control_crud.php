<?php
include_once 'class_solicitud.php';
$opcion = $_POST['opcion'];
$id     = $_POST['id'];
$id_r   = $_POST['id_r'];
$nuevo     = $_POST['nuevo_estado'];
$solicitud    = $_POST['id_sol'];
$estado = $_POST['estado'];
$disc   = new regSolicitud();
//var_dump($estado);
switch ($opcion) {
	case '1':

		$disc->reg_seguimiento($id_r,$solicitud ,$estado, $nuevo);
		break;
	case '2':	
		$res = $disc->buscar($id);
		echo json_encode($res);		
		break;
		case '3':
			$disc->editar($id,$desc,$letra, $num);
			break;
	default:
		# code...
		break;
}

?>