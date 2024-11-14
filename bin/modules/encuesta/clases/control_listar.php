<?php
include_once 'encuesta.php';
include_once 'pregunta.php';
include_once 'calificacion.php';

$encuesta   = new Encuesta();
$pregunta   = new Pregunta();
$calificacion = new Calificacion();

switch ($_POST['opcion']) {
	case 'listas_todas':
	echo json_encode($encuesta->listar_todas());	
	break;

	case 'preguntasxencuesta':	
		echo $pregunta->tabla_preguntasxencuesta($_POST['id_encuesta']);
	break;

	case 'listar_calificaciones':
	echo json_encode($calificacion->listar_todas());
	break;

	case 'listar_preguntas':	
		echo json_encode($pregunta->preguntasxencuesta_con_opciones($_POST['id_encuesta']));
	break;

	case 'contestada':	
		echo json_encode($encuesta->contestada());
	break;
	
}

?>