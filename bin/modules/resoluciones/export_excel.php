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
    id, 
    numero, 
    detalle, 
    fecha_resolucion, 
    fecha_registro, 
    usuario_registro, 
    estado, 
    CASE gt_resoluciones.estado
        WHEN '0' THEN 'Inactivo'
        WHEN '1' THEN 'Activo'
    END AS estado_descripcion
FROM 
    gt_resoluciones;";

$query_cant = "SELECT COUNT(*) AS cantidad FROM gt_resoluciones;";

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
$sheet->setCellValue('B1', 'Número');
$sheet->setCellValue('C1', 'Detalle');
$sheet->setCellValue('D1', 'Fecha Resolución');
$sheet->setCellValue('E1', 'Fecha Registro');
$sheet->setCellValue('F1', 'Usuario Registro');
$sheet->setCellValue('G1', 'Estado');
$sheet->setCellValue('H1', 'Estado Descripción');

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
$sheet->getStyle('A1:H1')->applyFromArray($headerStyle);

// Ajustar el ancho de las columnas automáticamente desde A hasta B y D hasta H
foreach (range('A', 'B') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}
foreach (range('D', 'H') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Establecer un ancho fijo para la columna C (Detalle) y habilitar el ajuste de texto
$sheet->getColumnDimension('C')->setWidth(110); // Ajusta según tus necesidades
$sheet->getStyle('C')->getAlignment()->setWrapText(true);

// Escribir los datos de la consulta en el archivo Excel
$row = 2; // Iniciar en la segunda fila
while ($fila = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue('A' . $row, $fila['id']);
    $sheet->setCellValue('B' . $row, $fila['numero']);
    $sheet->setCellValue('C' . $row, $fila['detalle']);
    $sheet->setCellValue('D' . $row, $fila['fecha_resolucion']);
    $sheet->setCellValue('E' . $row, $fila['fecha_registro']);
    $sheet->setCellValue('F' . $row, $fila['usuario_registro']);
    $sheet->setCellValue('G' . $row, $fila['estado']);
    $sheet->setCellValue('H' . $row, $fila['estado_descripcion']);
    $row++;
}

// Aplicar el estilo a las celdas
$sheet->getStyle('A1:H' . ($row - 1))->applyFromArray($styleArray);

// Contar registros
if ($result_cant) {
    $row_count = mysqli_fetch_assoc($result_cant)['cantidad'];
} else {
    $row_count = 0;
}

// PIE DEL REPORTE
$row = $row + 2;
$sheet->setCellValue('C' . $row, 'PensionSync');
$row2 = $row + 1;
$sheet->setCellValue('C' . $row2, 'Reporte de Resoluciones');
$row2++;
$sheet->setCellValue('C' . $row2, 'Fecha de generación: ' . date('Y-m-d H:i:s'));
$row2++;
$sheet->setCellValue('C' . $row2, 'Usuario de generación: ' . $user);
$row2++;
$sheet->setCellValue('C' . $row2, 'Cantidad de registros: ' . $row_count);

// Aplicar el estilo a las celdas del footer
$sheet->getStyle('C' . $row . ':C' . $row2)->applyFromArray($footerStyle);

ob_clean(); // Limpia el búfer de salida para evitar contenido previo

// Descargar el archivo Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="reporte_resoluciones.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
