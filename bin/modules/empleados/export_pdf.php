<?php
require_once __DIR__ . '../../../vendor/autoload.php';

$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);
$pdf->Write(0, 'Hola, este es un reporte PDF generado con TCPDF en PHP.');
$pdf->Output('reporte.pdf', 'I'); // Muestra el PDF en el navegador
?>
