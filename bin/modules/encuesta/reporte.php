<?php
include_once '../utiles/pdf_helper.php';
include_once '../utiles/code128.php';
include_once 'clases/reporte.php';
include '../../../core.php';

class reportePDF extends PDFReport  
{
	public function Render($datos)
	{
				
		$this->SetFont('Arial','',12);
		$this->Cell(195,5,"REPORTE DE RESPUESTAS",0,0,'c');//primer cero indica que no lleve borde
		$this->Ln();
		$this->Ln();
		$this->Cell(195,5,"Preguntas",0,0,'L');
		$this->Ln();
		$this->Ln();
		
		foreach ($datos as $d) {
			$descripcion = str_split($d['descripcion'], 100);
                foreach ($descripcion as $des) {
        	     $de=utf8_decode($des);
                 $this->Cell(195, 5, "{$de}", 0, 0, 'L');
                 $this->Ln();           
                                        }
            
            rsort($d['opciones']);
			foreach ($d['opciones'] as $dopciones) {

				
				//$porcentaje=$this->calcular_porcentaje($dopciones);
				$this->Cell(70,5,"{$dopciones['descripcion']}",0,0,'L');$this->Cell(25,5,"{$dopciones['sumatoria']}",0,0,'L');$this->Cell(25,5,"{$dopciones['porcentaje']}%",0,0,'L');
   			    $this->Ln();
				
			}
			$this->Ln();			

		}
	}

	private function calcular_porcentaje($datos)
	{
        $porcentaje=($datos['sumatoria']*100)/$datos['total_respuestas'];

        return round($porcentaje);
	}
	
}

$pdf = new reportePDF('P','mm','Letter');
$reporte= new reporte();
$preguntasOpciones=$reporte->preguntasxencuesta_con_opciones($_GET['id_encuesta']);
$preguntasRespuestas=$reporte->respuestas_predefinidas($_GET['id_encuesta']);
//dd($preguntasRespuestas);
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
//var_dump($preguntasOpciones);
$pdf->render($preguntasOpciones);
$pdf->Output();	
?>