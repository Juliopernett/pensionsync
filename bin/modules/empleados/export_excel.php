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
$query = "SELECT empleado.id, 
    tipo_ide.descripcion AS id_tipo_identificacion, 
    empleado.identificacion, 
    empleado.primer_apellido, 
    empleado.segundo_apellido, 
    empleado.primer_nombre, 
    empleado.segundo_nombre, 
    empleado.nombre_completo, 
    empleado.sexo, 
    empleado.telefono, 
    empleado.direccion, 
    empleado.correo_electronico, 
    cargo.descripcion AS id_cargo, 
    (SELECT EP2.descripcion 
        FROM pm_tipo_riesgo EP2 
        WHERE EP2.id = empleado.id_riesgo_arl) AS id_riesgo_arl, 
    (SELECT CONCAT(EP2.NIT, '-', EP2.DV, '  ', CONCAT(UPPER(LEFT(EP2.NOMBRE, 1)), LOWER(SUBSTRING(EP2.NOMBRE, 2)))) 
        FROM pm_entidades EP2 
        WHERE EP2.id = empleado.id_eps) AS id_eps, 
    (SELECT CONCAT(EP2.NIT, '-', EP2.DV, '  ', CONCAT(UPPER(LEFT(EP2.NOMBRE, 1)), LOWER(SUBSTRING(EP2.NOMBRE, 2)))) 
        FROM pm_entidades EP2 
        WHERE EP2.id = empleado.id_fondo_pension) AS id_fondo_pension, 
    (SELECT CONCAT(EP2.NIT, '-', EP2.DV, '  ', CONCAT(UPPER(LEFT(EP2.NOMBRE, 1)), LOWER(SUBSTRING(EP2.NOMBRE, 2)))) 
        FROM pm_entidades EP2 
        WHERE EP2.id = empleado.id_fondo_cesantias) AS id_fondo_cesantias, 
    (SELECT CONCAT(EP2.NIT, '-', EP2.DV, '  ', CONCAT(UPPER(LEFT(EP2.NOMBRE, 1)), LOWER(SUBSTRING(EP2.NOMBRE, 2)))) 
        FROM pm_entidades EP2 
        WHERE EP2.id = empleado.id_arl) AS id_arl, 
    (SELECT CONCAT(EP2.NIT, '-', EP2.DV, '  ', CONCAT(UPPER(LEFT(EP2.NOMBRE, 1)), LOWER(SUBSTRING(EP2.NOMBRE, 2)))) 
        FROM pm_entidades EP2 
        WHERE EP2.id = empleado.id_caja_compensacion) AS id_caja_compensacion,
    (SELECT EP2.nombre_completo 
        FROM gt_empleado EP2 
        WHERE EP2.id = empleado.id_Empleado_sustituto) AS id_Empleado_sustituto, 
    tipo_emp.descripcion AS id_tipo_empleado, 
    empleado.fcha_cumpleano, 
    empleado.dato_pension, 
    empleado.fecha_pension, 
    empleado.fecha_sustitucion, 
    empleado.porcentaje_sustitucion, 
    CASE 
            WHEN empleado.estado = 1 THEN 'Activo'
            WHEN empleado.estado = 2 THEN 'Inactivo'
            ELSE empleado.estado
        END AS estado, 
    empleado.usuario_registro, 
    empleado.fecha_registro
FROM gt_empleado empleado 
JOIN pm_tipo_identificacion tipo_ide 
    ON tipo_ide.tipo_identificacion = empleado.id_tipo_identificacion 
JOIN pm_cargos cargo 
    ON cargo.id = empleado.id_cargo 
JOIN pm_tipo_empleado tipo_emp 
    ON tipo_emp.id = empleado.id_tipo_empleado;";

$query_cant = "select COUNT(*) cantidad
FROM gt_empleado empleado ";

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
$sheet->setCellValue('B1', 'Tipo Identificación');
$sheet->setCellValue('C1', 'Identificación');
$sheet->setCellValue('D1', 'Primer Apellido');
$sheet->setCellValue('E1', 'Segundo Apellido');
$sheet->setCellValue('F1', 'Primer Nombre');
$sheet->setCellValue('G1', 'Segundo Nombre');
$sheet->setCellValue('H1', 'Nombre Completo');
$sheet->setCellValue('I1', 'Sexo');
$sheet->setCellValue('J1', 'Teléfono');
$sheet->setCellValue('K1', 'Dirección');
$sheet->setCellValue('L1', 'Correo Electrónico');
$sheet->setCellValue('M1', 'Cargo');
$sheet->setCellValue('N1', 'Riesgo ARL');
$sheet->setCellValue('O1', 'EPS');
$sheet->setCellValue('P1', 'Fondo de Pensión');
$sheet->setCellValue('Q1', 'Fondo de Cesantías');
$sheet->setCellValue('R1', 'ARL');
$sheet->setCellValue('S1', 'Caja de Compensación');
$sheet->setCellValue('T1', 'Empleado Sustituto');
$sheet->setCellValue('U1', 'Tipo Empleado');
$sheet->setCellValue('V1', 'Fecha de Cumpleaños');
$sheet->setCellValue('W1', 'Dato Pensión');
$sheet->setCellValue('X1', 'Fecha Pensión');
$sheet->setCellValue('Y1', 'Fecha Sustitución');
$sheet->setCellValue('Z1', '% Sustitución');
$sheet->setCellValue('AA1', 'Estado');
$sheet->setCellValue('AB1', 'Usuario');
$sheet->setCellValue('AC1', 'Fecha Registro / actualización');


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
$sheet->getStyle('A1:AC1')->applyFromArray($headerStyle); // Ajusta el rango según tus encabezados


// Ajustar el ancho de las columnas automáticamente desde A hasta AC
$columns = range('A', 'Z'); // Columnas de la A a la Z
foreach ($columns as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}
$sheet->getColumnDimension('AA')->setAutoSize(true);
$sheet->getColumnDimension('AB')->setAutoSize(true);
$sheet->getColumnDimension('AC')->setAutoSize(true);


// Escribir los datos de la consulta en el archivo Excel
$row = 2; // Iniciar en la segunda fila
while ($fila = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue('A' . $row, $fila['id']);
    $sheet->setCellValue('B' . $row, $fila['id_tipo_identificacion']);
    $sheet->setCellValue('C' . $row, $fila['identificacion']);
    $sheet->setCellValue('D' . $row, $fila['primer_apellido']);
    $sheet->setCellValue('E' . $row, $fila['segundo_apellido']);
    $sheet->setCellValue('F' . $row, $fila['primer_nombre']);
    $sheet->setCellValue('G' . $row, $fila['segundo_nombre']);
    $sheet->setCellValue('H' . $row, $fila['nombre_completo']);
    $sheet->setCellValue('I' . $row, $fila['sexo']);
    $sheet->setCellValue('J' . $row, $fila['telefono']);
    $sheet->setCellValue('K' . $row, $fila['direccion']);
    $sheet->setCellValue('L' . $row, $fila['correo_electronico']);
    $sheet->setCellValue('M' . $row, $fila['id_cargo']);
    $sheet->setCellValue('N' . $row, $fila['id_riesgo_arl']);
    $sheet->setCellValue('O' . $row, $fila['id_eps']);
    $sheet->setCellValue('P' . $row, $fila['id_fondo_pension']);
    $sheet->setCellValue('Q' . $row, $fila['id_fondo_cesantias']);
    $sheet->setCellValue('R' . $row, $fila['id_arl']);
    $sheet->setCellValue('S' . $row, $fila['id_caja_compensacion']);
    $sheet->setCellValue('T' . $row, $fila['id_Empleado_sustituto']);
    $sheet->setCellValue('U' . $row, $fila['id_tipo_empleado']);
    $sheet->setCellValue('V' . $row, $fila['fcha_cumpleano']);
    $sheet->setCellValue('W' . $row, $fila['dato_pension']);
    $sheet->setCellValue('X' . $row, $fila['fecha_pension']);
    $sheet->setCellValue('Y' . $row, $fila['fecha_sustitucion']);
    $sheet->setCellValue('Z' . $row, $fila['porcentaje_sustitucion']);
    $sheet->setCellValue('AA' . $row, $fila['estado']);
    $sheet->setCellValue('AB' . $row, $fila['usuario_registro']);
    $sheet->setCellValue('AC' . $row, $fila['fecha_registro']);
    $row++;
}
$row--;
// Aplicar el estilo a las celdas
$sheet->getStyle('A1:'.'AC'.$row)->applyFromArray($styleArray);

if ($result_cant) {
    $row_count = mysqli_fetch_assoc($result_cant)['cantidad'];
} else {
    $row_count = 0; // En caso de que haya un error, asigna 0 o el valor que consideres adecuado.
}


// PIE DEL REPORTE
$row=$row+2;
$sheet->setCellValue('B'.$row, 'PensionSync');
$row2=$row+1;
$sheet->setCellValue('B'.$row2, 'Reporte de empleados');
$row2++;
$sheet->setCellValue('B'.$row2, 'Fecha de generación: ' . date('Y-m-d H:i:s')); // Fecha actual
$row2++;
$sheet->setCellValue('B'.$row2, 'Usuario de generación: ' . $user); // Fecha actual
$row2++;
$sheet->setCellValue('B'.$row2, 'Cantidad de registros: ' . $row_count); // Fecha actual

// Aplicar el estilo a las celdas del footer
$sheet->getStyle('B'.$row.':'.'B'.$row2)->applyFromArray($footerStyle); // Ajusta el rango según tus encabezados


ob_clean(); // Limpia el búfer de salida para evitar contenido previo

// Descargar el archivo Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="reporte_empleado.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;

?>
