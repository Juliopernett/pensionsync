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

// Obtener el parámetro de periodo desde la URL, si está presente
$par_periodo = isset($_GET['par_periodo']) ? mysqli_real_escape_string($con, $_GET['par_periodo']) : '';

// Consulta SQL con filtro por el parámetro 'par_periodo'
$query = "SELECT gt_novedades.id, 
    gt_novedades.id_empleado, 
    gt_empleado.nombre_completo, 
    gt_novedades.id_concepto, 
    pm_conceptos.Codigo as codigo_concepto, 
    pm_conceptos.descripcion as descripcion_concepto, 
    gt_novedades.id_periodo, 
    pm_periodo.codigo as codigo_periodo, 
    gt_novedades.Valor, 
    gt_novedades.estado, 
    gt_novedades.usuario_registro, 
    gt_novedades.fecha_registro, 
    gt_novedades.resolucion_id, 
    gt_resoluciones.numero as numero_resolucion, 
    gt_resoluciones.fecha_resolucion as fecha_resolucion,
    CASE gt_novedades.estado
        WHEN '0' THEN 'Inactivo'
        WHEN '1' THEN 'Activo'
    END AS estado_descripcion
FROM gt_novedades 
LEFT JOIN gt_resoluciones ON gt_resoluciones.id = gt_novedades.resolucion_id
JOIN pm_periodo ON pm_periodo.id = gt_novedades.id_periodo
JOIN gt_empleado ON gt_empleado.id = gt_novedades.id_empleado
JOIN pm_conceptos ON pm_conceptos.id = gt_novedades.id_concepto";

// Si el parámetro 'par_periodo' está presente, agregar el filtro por periodo
if (!empty($par_periodo)) {
    $query .= " WHERE gt_novedades.id_periodo = '$par_periodo'";
}

$query_cant = "SELECT COUNT(*) cantidad FROM gt_novedades";
if (!empty($par_periodo)) {
    $query_cant .= " WHERE id_periodo = '$par_periodo'";
}

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
$sheet->setCellValue('B1', 'Empleado');
$sheet->setCellValue('C1', 'Concepto');
$sheet->setCellValue('D1', 'Código Concepto');
$sheet->setCellValue('E1', 'Descripción Concepto');
$sheet->setCellValue('F1', 'Periodo');
$sheet->setCellValue('G1', 'Código Periodo');
$sheet->setCellValue('H1', 'Valor');
$sheet->setCellValue('I1', 'Estado');
$sheet->setCellValue('J1', 'Usuario Registro');
$sheet->setCellValue('K1', 'Fecha Registro');
$sheet->setCellValue('L1', 'Resolución ID');
$sheet->setCellValue('M1', 'Número Resolución');
$sheet->setCellValue('N1', 'Fecha Resolución');
$sheet->setCellValue('O1', 'Estado Descripción');

$headerStyle = [
    'font' => [
        'bold' => true,
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Centrar horizontalmente
    ],
];

$footerStyle = [
    'font' => [
        'bold' => true,
    ],
];

$styleArray = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'],
        ],
    ],
];

// Aplicar el estilo a las celdas del encabezado
$sheet->getStyle('A1:O1')->applyFromArray($headerStyle); // Ajusta el rango según tus encabezados

// Ajustar el ancho de las columnas automáticamente desde A hasta O
$columns = range('A', 'O'); // Columnas de la A a la O
foreach ($columns as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Escribir los datos de la consulta en el archivo Excel
$row = 2; // Iniciar en la segunda fila
while ($fila = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue('A' . $row, $fila['id']);
    $sheet->setCellValue('B' . $row, $fila['nombre_completo']);
    $sheet->setCellValue('C' . $row, $fila['id_concepto']);
    $sheet->setCellValue('D' . $row, $fila['codigo_concepto']);
    $sheet->setCellValue('E' . $row, $fila['descripcion_concepto']);
    $sheet->setCellValue('F' . $row, $fila['id_periodo']);
    $sheet->setCellValue('G' . $row, $fila['codigo_periodo']);
    $sheet->setCellValue('H' . $row, $fila['Valor']);
    $sheet->setCellValue('I' . $row, $fila['estado']);
    $sheet->setCellValue('J' . $row, $fila['usuario_registro']);
    $sheet->setCellValue('K' . $row, $fila['fecha_registro']);
    $sheet->setCellValue('L' . $row, $fila['resolucion_id']);
    $sheet->setCellValue('M' . $row, $fila['numero_resolucion']);
    $sheet->setCellValue('N' . $row, $fila['fecha_resolucion']);
    $sheet->setCellValue('O' . $row, $fila['estado_descripcion']);
    $row++;
}

$row--;
// Aplicar el estilo a las celdas
$sheet->getStyle('A1:'.'O'.$row)->applyFromArray($styleArray);

if ($result_cant) {
    $row_count = mysqli_fetch_assoc($result_cant)['cantidad'];
} else {
    $row_count = 0; // En caso de que haya un error, asigna 0 o el valor que consideres adecuado.
}

// PIE DEL REPORTE
$row = $row + 2;
$sheet->setCellValue('B' . $row, 'PensionSync');
$row2 = $row + 1;
$sheet->setCellValue('B' . $row2, 'Reporte de Novedades');
$row2++;
$sheet->setCellValue('B' . $row2, 'Fecha de generación: ' . date('Y-m-d H:i:s')); // Fecha actual
$row2++;
$sheet->setCellValue('B' . $row2, 'Usuario de generación: ' . $user); // Usuario que genera el reporte
$row2++;
$sheet->setCellValue('B' . $row2, 'Cantidad de registros: ' . $row_count); // Número de registros

// Aplicar el estilo a las celdas del pie de página
$sheet->getStyle('B' . $row . ':' . 'B' . $row2)->applyFromArray($footerStyle); // Ajusta el rango según tus encabezados

ob_clean(); // Limpia el búfer de salida para evitar contenido previo

// Descargar el archivo Excel
if (!empty($par_periodo)) {
    $header_descripcion = 'Content-Disposition: attachment; filename="reporte_novedades_filtro_periodo.xlsx"';
}else{
    //sin filtro periodo
    $header_descripcion = 'Content-Disposition: attachment; filename="reporte_novedades_historico.xlsx"';
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header($header_descripcion);
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
