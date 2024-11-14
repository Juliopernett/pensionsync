<?php
// Incluir la librería PHPSpreadsheet
require '../../../vendor/autoload.php'; // Asegúrate de tener instalado PHPSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;

session_start();
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user_name'];

// Conectar a la base de datos
require_once ("../../../config/db.php");
require_once ("../../../config/conexion.php");
include('../../../is_logged.php');

// Asegúrate de que la conexión sea válida
if (!$con) {
    echo json_encode(array("error" => "Error de conexión a la base de datos."));
    exit;
}

// Consulta SQL
$query = "SELECT 
    pm_conceptos.id, 
    pm_conceptos.codigo, 
    CONCAT(UPPER(SUBSTRING(pm_conceptos.descripcion, 1, 1)), LOWER(SUBSTRING(pm_conceptos.descripcion, 2))) AS descripcion, 
    pm_conceptos.tipo_movimiento, 
    CASE pm_conceptos.tipo_movimiento
        WHEN '1' THEN 'Devengos'
        WHEN '2' THEN 'Descuentos'
        WHEN '3' THEN 'Total Devengos'
        WHEN '4' THEN 'Total Descuento'
        WHEN '5' THEN 'Valor Neto'
        WHEN '6' THEN 'Informativo'
        WHEN '7' THEN 'Aporte Patrono'
        WHEN '8' THEN 'PARAFISCALES'
        WHEN '9' THEN 'Provision'
    END AS tipo_movimiento_descripcion,
    pm_conceptos.tipo_concepto,
    CASE pm_conceptos.tipo_concepto
        WHEN '1' THEN 'Pensionado'
        WHEN '2' THEN 'Activo'
        WHEN '3' THEN 'Sustituto'
    END AS tipo_concepto_descripcion,
    pm_conceptos.estado, 
    CASE pm_conceptos.estado
        WHEN '0' THEN 'Inactivo'
        WHEN '1' THEN 'Activo'
    END AS estado_descripcion,
    pm_conceptos.usuario_registro, 
    pm_conceptos.fecha_registro 
FROM 
    pm_conceptos;";

$query_cant = "SELECT COUNT(*) AS cantidad FROM pm_conceptos;";

$result = mysqli_query($con, $query);
$result_cant = mysqli_query($con, $query_cant);

// Verificar si la consulta se ejecutó correctamente
if (!$result) {
    echo json_encode(array("error" => "Error en la consulta: " . mysqli_error($con)));
    exit;
}

// Crear una nueva hoja de cálculo
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Escribir los encabezados en la primera fila
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Código');
$sheet->setCellValue('C1', 'Descripción');
$sheet->setCellValue('D1', 'Tipo Movimiento');
$sheet->setCellValue('E1', 'Tipo Movimiento Descripción');
$sheet->setCellValue('F1', 'Tipo Concepto');
$sheet->setCellValue('G1', 'Tipo Concepto Descripción');
$sheet->setCellValue('H1', 'Estado');
$sheet->setCellValue('I1', 'Estado Descripción');
$sheet->setCellValue('J1', 'Usuario Registro');
$sheet->setCellValue('K1', 'Fecha Registro');

// Estilos para los encabezados
$headerStyle = [
    'font' => [
        'bold' => true,
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
];

// Estilo para el contenido
$styleArray = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
];

// Estilo para el pie del reporte
$footerStyle = [
    'font' => [
        'bold' => true,
    ],
];

// Aplicar el estilo a las celdas del encabezado
$sheet->getStyle('A1:K1')->applyFromArray($headerStyle);

// Ajustar el ancho de las columnas automáticamente desde A hasta K
foreach (range('A', 'K') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Escribir los datos de la consulta en el archivo Excel
$row = 2; // Iniciar en la segunda fila
while ($fila = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue('A' . $row, $fila['id']);
    $sheet->setCellValue('B' . $row, $fila['codigo']);
    $sheet->setCellValue('C' . $row, $fila['descripcion']);
    $sheet->setCellValue('D' . $row, $fila['tipo_movimiento']);
    $sheet->setCellValue('E' . $row, $fila['tipo_movimiento_descripcion']);
    $sheet->setCellValue('F' . $row, $fila['tipo_concepto']);
    $sheet->setCellValue('G' . $row, $fila['tipo_concepto_descripcion']);
    $sheet->setCellValue('H' . $row, $fila['estado']);
    $sheet->setCellValue('I' . $row, $fila['estado_descripcion']);
    $sheet->setCellValue('J' . $row, $fila['usuario_registro']);
    $sheet->setCellValue('K' . $row, $fila['fecha_registro']);
    $row++;
}

// Aplicar el estilo a las celdas
$sheet->getStyle('A1:K' . ($row - 1))->applyFromArray($styleArray);

// Contar registros
if ($result_cant) {
    $row_count = mysqli_fetch_assoc($result_cant)['cantidad'];
} else {
    $row_count = 0;
}

// PIE DEL REPORTE
$row = $row + 2;
$sheet->setCellValue('B' . $row, 'PensionSync');
$row2 = $row + 1;
$sheet->setCellValue('B' . $row2, 'Reporte de Conceptos');
$row2++;
$sheet->setCellValue('B' . $row2, 'Fecha de generación: ' . date('Y-m-d H:i:s'));
$row2++;
$sheet->setCellValue('B' . $row2, 'Usuario de generación: ' . $user);
$row2++;
$sheet->setCellValue('B' . $row2, 'Cantidad de registros: ' . $row_count);

// Aplicar el estilo a las celdas del footer
$sheet->getStyle('B' . $row . ':B' . $row2)->applyFromArray($footerStyle);

ob_clean(); // Limpia el búfer de salida para evitar contenido previo

// Descargar el archivo Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="reporte_conceptos.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
