<?php
include_once '../utiles/pdf_helper.php';
include_once '../utiles/code128.php';
include_once 'clases/reporte.php';
include '../../../core.php';

//echo ($_GET['id_estado']);
class reportePDF extends PDFReport  
{
	public function Render($datos)
	{
				
		$this->SetFont('Arial','B',16);
		$this->Image('../../../imagenes/LogoDDL2014.png', 10 ,10, 60 , 20,'PNG');
		$this->Image('../../../imagenes/feedback-logo.png', 150 ,10, 60 , 13,'PNG');
		$this->Cell(70,5,"",0,0,'c');
		$this->Cell(200,10,"SOPORTE PQRS #".($_GET['id_estado']) ,0,0,'c');//primer cero indica que no lleve borde
		$this->Ln();
		$this->Ln();
		$this->Ln();
       
       if(count($datos) > 0){
		foreach ($datos as $d) {

		$this->SetFont('Arial','B',12);
		$this->Cell(30, 5, utf8_decode("INFORMACIÓN DEL SOLICITANTE:"), 0, 0, 'L');
		$this->Ln();
		$this->Cell(30, 5, utf8_decode("Identificación: "), 0, 0, 'L');
		$this->SetFont('Arial','',12);
		$this->Cell(40, 5, "{$d['tipo_id']}".". "."{$d['identificacion']}", 0, 0, 'L');
		$this->Ln();
		$this->SetFont('Arial','B',12);
		$this->Cell(35, 5, "Tipo de persona: ", 0, 0, 'L');
		$this->SetFont('Arial','',12);
		$this->Cell(40, 5, "{$d['tipo_persona']}", 0, 0, 'L');
		$this->Ln();
		$this->Ln();
		$this->SetFont('Arial','B',12);
		$this->Cell(30, 5, "Nombre de usuario:", 0, 0, 'L');
		$this->Ln();
		$this->SetFont('Arial','',12);
		$this->Cell(40, 5, utf8_decode("{$d['nombre']}"), 0, 0, 'L');
		$this->SetFont('Arial','B',12);
		$this->Ln();
		$this->Ln();
		$this->SetFont('Arial','B',12);
		$this->Cell(30, 5, "Correo:", 0, 0, 'L');
		$this->Ln();
		$this->SetFont('Arial','',12);
		$this->Cell(40, 5, "{$d['user_email']}", 0, 0, 'L');
		$this->SetFont('Arial','B',12);
		$this->Ln();
		$this->Ln();
		$this->SetFont('Arial','B',12);
		$this->Cell(30, 5, utf8_decode("Teléfono:"), 0, 0, 'L');$this->Cell(40, 5, "Direccion:", 0, 0, 'L');
		$this->Ln();
		$this->SetFont('Arial','',12);
		$this->Cell(40, 5, "{$d['telefono']}", 0, 0, 'L');$this->Cell(40, 5, "{$d['direccion']}", 0, 0, 'L');
		$this->SetFont('Arial','B',12);
		
		$this->Ln();
		$this->Ln();
		$this->Ln();
		$this->SetFont('Arial','B',12);
		$this->Cell(30, 5, utf8_decode("INFORMACIÓN DE LA SOLICITUD:"), 0, 0, 'L');
		
		$this->Ln();
		$this->SetFont('Arial','B',12);
		$this->Cell(70, 5, "Id de solicitud:", 0, 0, 'L');$this->Cell(70, 5, "Estado actual", 0, 0, 'L');
		$this->Ln();
		$this->SetFont('Arial','',12);
		$this->Cell(70, 5, "{$d['sufijo_solicitud']}", 0, 0, 'L');$this->Cell(40, 5, "{$d['des_estado']}", 0, 0, 'L');
	
		
		$this->Ln();
		$this->Ln();
		$this->SetFont('Arial','B',12);
		$this->Cell(30, 5, "Tipo de solicitud", 0, 0, 'L');
		$this->Ln();
		$this->SetFont('Arial','',12);
		$this->Cell(40, 5, "{$d['tipo_de_solicitud']}", 0, 0, 'L');
		$this->SetFont('Arial','B',12);
		$this->Ln();
		$this->Ln();
		$this->SetFont('Arial','B',12);
		$this->Cell(30, 5, utf8_decode("Descripción de la solicitud"), 0, 0, 'L');
		$this->Ln();
		$this->SetFont('Arial','',12);
		//$this->Cell(40, 5, "{$d['descripcion_solicitud']}", 0, 0, 'L');

		$this->Multicell(200,5,utf8_decode("{$d['descripcion_solicitud']}"),0, 'L');

		$this->SetFont('Arial','B',12);

		 

		$this->Ln();
		$this->Ln();
		$this->Cell(30, 5, utf8_decode("Observación de estado actual"), 0, 0, 'L');
		$this->Ln();
		$this->SetFont('Arial','',12);
		$this->Cell(40, 5, "{$d['descripcion_estado']}", 0, 0, 'L');
		//$this->Cell(40, 5, "{$d['estado_solicitud']}", 0, 0, 'L');
		$this->Ln();
		$this->Ln();
		}
	      }
	     else
	     {
	     	$this->Cell(195, 5, "Selecciona por favor una solicitud", 0, 0, 'L');
		$this->Ln();
	     }
		
		
	}

	//Pie de página
		function Footer()
		{

		$this->SetY(-10);

		$this->SetFont('Arial','I',8);
		$this->Cell(0,10,'FeedbackNow V1.0 - Todos los derechos reservados -'.date('Y'),0,0,'L');
		$this->Cell(0,10,utf8_decode('Página ').$this->PageNo().utf8_decode(', Fecha de impresión: ').date("d-m-Y h:i:s"),0,0,'R');

	}

}

$pdf = new reportePDF('P','mm','Letter');
$reporte= new reporte();
$idsUltimoSeguimiento=$reporte->get_ids_ultimo_seguimiento();
//var_dump($_GET['id_estado']);
//var_dump($_GET['id_estado'],$_GET['fecha_ini'],$_GET['fecha_fin']);
$solicitudes=$reporte->get_solicitudes($_GET['id_estado'],$_GET['fecha_ini'],$_GET['fecha_fin']);

$finales=array();
foreach ($idsUltimoSeguimiento as $id) {
	foreach ($solicitudes as $solicitud) {
		if($id==$solicitud['id_seguimiento'])
		{
			$finales[]=$solicitud;
		}
	}
}

//var_dump($finales);
$pdf->render($finales);
$pdf->Output();	
?>