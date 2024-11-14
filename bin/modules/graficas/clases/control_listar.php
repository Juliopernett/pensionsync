<?php
include_once 'reporte.php';

$reporte   = new Reporte();


switch ($_POST['opcion']) {
	case 'datos':
	$preguntasOpciones=$reporte->preguntasxencuesta_con_opciones($_POST['id_encuesta']);
$preguntasRespuestas=$reporte->respuestas_predefinidas($_POST['id_encuesta']);

for ($i=0; $i < count($preguntasOpciones) ; $i++) { 
	for ($j=0; $j < count($preguntasOpciones[$i]['opciones']) ; $j++) { 
	    for ($k=0; $k < count($preguntasRespuestas); $k++) { 	    	
	    	for ($l=0; $l <count($preguntasRespuestas[$k]); $l++) { 
	    		//var_dump($preguntasRespuestas[$k][$l]['id_pregunta'],$preguntasOpciones[$i]['id_preguna']);
	    		//var_dump($preguntasOpciones[$i]['opciones'][$j]['id_opcion'],$preguntasRespuestas[$k][$l]['id_opcion']);
	    		
	    		if($preguntasRespuestas[$k][$l]['id_pregunta']==$preguntasOpciones[$i]['id_preguna']  and $preguntasOpciones[$i]['opciones'][$j]['id_opcion']==$preguntasRespuestas[$k][$l]['id_opcion'])
			{
				$preguntasOpciones[$i]['opciones'][$j]['sumatoria']++;

			}
            
            $porcentaje=($preguntasOpciones[$i]['opciones'][$j]['sumatoria']*100)/count($preguntasRespuestas[$k]); 
			$preguntasOpciones[$i]['opciones'][$j]['porcentaje']= round($porcentaje);

	    	}
                
	    	
	    }
	}
}

echo json_encode($preguntasOpciones);

	break;
	case 'estados':
	echo json_encode($reporte->listar_estados());
	break;

	case 'datos_solicitudes':
	$idsUltimoSeguimiento=$reporte->get_ids_ultimo_seguimiento();
    $solicitudes=$reporte->get_solicitudes($_POST['fecha_ini'],$_POST['fecha_fin']);

$finales=array();
foreach ($idsUltimoSeguimiento as $id) {
	foreach ($solicitudes as $solicitud) {
		if($id==$solicitud['id_seguimiento'])
		{
			$finales[]=$solicitud;
		}
	}
}
	echo json_encode($finales);
	break;

	
	
}

?>