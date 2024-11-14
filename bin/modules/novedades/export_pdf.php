<?php
ob_start(); // Inicia el buffer de salida
ini_set('display_errors', 0);   // Mostrar errores en la pantalla
error_reporting(0);         // Reportar todos los tipos de errores
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once ("../../../config/db.php");
require_once ("../../../config/conexion.php");
include('../../../is_logged.php');

// Iniciar la sesión si no está activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$DIRECCION1;
$DIRECCION2;
$PAG_WEB;

// Verificar si ya existe una sesión activa
if (isset($_SESSION['user_name'])) {
    $user = $_SESSION['user_name'];
} else {
    header("Location: login.php");
    exit();
}

if (!$con) {
    echo json_encode(array("error" => "Error de conexión a la base de datos."));
    exit;
}

// Obtener el parámetro de periodo desde la URL, si está presente
$par_periodo = isset($_GET['par_periodo']) ? mysqli_real_escape_string($con, $_GET['par_periodo']) : '';

/*******************CONSULTAS*******************/
// Consulta SQL para obtener el mes y el año concatenados
$query = "SELECT CONCAT(mes, ' de ', YEAR(fecha_final)) AS MES_ANO,
    (SELECT valor FROM pm_definiciones WHERE codigo='NOMDIR') NOM_DIRECTOR,
    (SELECT valor FROM pm_definiciones WHERE codigo='CARGDI') CARGO_DIRECTOR,
    (SELECT valor FROM pm_definiciones WHERE codigo='DESENT') DES_ENTIDAD,
    (SELECT valor FROM pm_definiciones WHERE codigo='FRNPRY') proyecto,
    (SELECT valor FROM pm_definiciones WHERE codigo='FRNRV1') reviso1,
    (SELECT valor FROM pm_definiciones WHERE codigo='FRNRV2') reviso2,
    (SELECT valor FROM pm_definiciones WHERE codigo='FRNRV3') reviso3,
    (SELECT valor FROM pm_definiciones WHERE codigo='FRNRV4') reviso4,
    (SELECT valor FROM pm_definiciones WHERE codigo='DIREC1') DIRECCION1,
    (SELECT valor FROM pm_definiciones WHERE codigo='DIREC2') DIRECCION2,
    (SELECT valor FROM pm_definiciones WHERE codigo='PAGWEB') PAG_WEB
FROM pm_periodo WHERE id = '$par_periodo'";  // Corregido: especificar la columna 'periodo'


// Ejecutar la consulta
$result = mysqli_query($con, $query);

// Verificar si la consulta devolvió resultados
if ($result && mysqli_num_rows($result) > 0) {
    // Obtener el valor de la columna 'MES_ANO' del resultado
    $row = mysqli_fetch_assoc($result);
    $MES_ANO = $row['MES_ANO'];
    $NOM_DIRECTOR = $row['NOM_DIRECTOR'];
    $CARGO_DIRECTOR = $row['CARGO_DIRECTOR'];
    $DES_ENTIDAD = $row['DES_ENTIDAD'];
    $proyecto = $row['proyecto'];
    $reviso1 = $row['reviso1'];
    $reviso2 = $row['reviso2'];
    $reviso3 = $row['reviso3'];
    $reviso4 = $row['reviso4'];
    $DIRECCION1 = $row['DIRECCION1'];
    $DIRECCION2 = $row['DIRECCION2'];
    $PAG_WEB = $row['PAG_WEB'];
    
} else {
    $MES_ANO = "hubo error, query: ".$query; // Mensaje por defecto si no hay resultados
}
/*******************F_CONSULTAS*******************/


// Crear el PDF
class MYPDF extends TCPDF {

    // Esta propiedad guardará la última posición Y
    private $lastY;

    // Este método es ejecutado al inicio de cada página
    public function Header() {
        // Logo izquierdo (si estamos en la primera página)
        if ($this->getPage() == 1) {
            $this->Image(__DIR__ . '/../../../imagenes/logobq.png', 15, 10, 40); // Ajusta la ruta y tamaño del logo

            // Calcular la posición para la segunda imagen (LogoDDL2014)
            $pageWidth = $this->GetPageWidth(); // Obtener el ancho de la página
            $imageWidth = 30; // Ancho de la imagen (ajustar según el tamaño real de la imagen)

            // Usar el margen derecho de la página para colocar el logo a la derecha
            $logoRightX = $pageWidth - $imageWidth - 15;
            $this->Image(__DIR__ . '/../../../imagenes/LogoDDL2014.png', $logoRightX, 10, $imageWidth); // Ajuste de altura de la imagen

            // Establecer la posición Y para el texto debajo del logo derecho
            $logoHeight = 10; // Altura del logo, ajustar si es necesario
            $this->SetY(10 + $logoHeight); // Ajusta la Y para colocar el texto justo debajo del logo de la derecha
            /*
            // Agregar el texto debajo del logo de la derecha
            $this->SetX($logoRightX); // Coloca el texto alineado con el logo derecho
            $this->Cell($imageWidth, 10, "Dirección distrital de liquidaciones", 0, 1, 'C'); // Puedes cambiar el texto o ajustarlo
            */
            // Guardamos la última posición Y después de agregar el texto
            $this->lastY = $this->GetY(); // Guarda la posición Y después del texto
        } else {
            // En páginas siguientes, no agregamos los logos y simplemente usamos la posición Y
            $this->SetY($this->lastY + 5); // Continúa desde la última posición Y con un pequeño margen
        }
    }

    // Método para obtener la última posición Y guardada
    public function getLastY() {
        return $this->lastY;
    }

    // Agregar pie de página en todas las páginas
    public function Footer() {
        // Posición a 15 mm de la parte inferior
        $this->SetY(-15);

        // Establecer el tipo de fuente
        $this->SetFont('helvetica', 'I', 8);

        // Obtener el número de página actual
        $pageNum = $this->getPage();
        // Obtener el número total de páginas
        $totalPages = $this->getAliasNbPages();

        global $DIRECCION1;
        global $DIRECCION2;
        global $PAG_WEB;

        $leftText = $DIRECCION1;
        $leftText2 = $DIRECCION2;
        $url_web = $PAG_WEB;

        // Líneas de contacto a la izquierda
        //$leftText = "Calle 34 No. 43 - 79 | Barranquilla, Colombia";
        //$leftText2 = "Edificio BCH Piso 5 | teléfono: 3707833";

        // Mostrar la información en el pie de página
        $this->SetX(15);  // Alineamos el texto a la izquierda (márgenes)
        $this->Cell(90, 10, $leftText, 0, 0, 'L'); // Coloca el primer texto a la izquierda

        // Salto de línea para separar las dos líneas de contacto
        $this->Ln(4); // 4 mm de separación entre las líneas de contacto

        // Segunda línea de contacto
        $this->SetX(15);  // Alineamos de nuevo el cursor a la izquierda
        $this->Cell(90, 10, $leftText2, 0, 0, 'L'); // Coloca la segunda línea de texto a la izquierda

        // Espacio para el texto "www.dirliquidaciones.com" encima de la paginación
        $this->Ln(4);  // 4 mm de separación

        // Agregar el texto "www.dirliquidaciones.com" centrado
        $this->SetX(0);  // Centrado en el centro de la página
        $this->Cell(0, 10, $url_web, 0, 0, 'C'); // Coloca el texto centrado

        // Número de página a la derecha
        $this->SetY(-15); // Asegura que la posición esté al fondo de la página
        $this->SetX(-15); // Mover el cursor a la derecha para el número de página
        $this->Cell(0, 10, "Página $pageNum de $totalPages", 0, 0, 'R'); // Coloca el número de página a la derecha
    }


}



// Crear instancia de MYPDF
$pdf = new MYPDF();
$pdf->SetMargins(15, 15, 15);  // Asegúrate de que los márgenes son los adecuados
$pdf->AddPage();  // Añadir la primera página antes de escribir el contenido

// Aquí usamos la posición guardada en lastY para ajustar la Y correctamente
$pdf->SetY($pdf->getLastY() + 5);  // Continuamos justo después de la cabecera, con un pequeño margen extra de 5

$pdf->SetFont('helvetica', '', 10);

// Información de destinatario y referencia
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Barranquilla, D.E.I.P, '.$MES_ANO, 0, 1);
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(0, 5, "Señores\nFIDUCIARIA LA PREVISORA S.A\nNIT: 860.525.148-5\nAtt: Juan Carlos González\nCalle 72 No. 10-03 Piso 5\nBogotá", 0, 'L');
$pdf->Ln(5);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'REF: REPORTE DE NOVEDADES MES DE '.strtoupper($MES_ANO), 0, 1);
$pdf->Ln(5);
$pdf->SetFont('helvetica', '', 10);
$pdf->MultiCell(0, 5, "Cordial saludo:\n\nRelacionamos las novedades a tener en cuenta para la elaboración de la nómina del mes de ".$MES_ANO, 0, 'L');
$pdf->Ln(10);

// Novedades con resolución (tabla)

$query_resolucion = "
    SELECT 
        gt_resoluciones.id AS id_resolucion, 
        gt_resoluciones.numero AS numero_resolucion, 
        gt_resoluciones.detalle AS detalle_resolucion, 
        gt_resoluciones.fecha_resolucion
    FROM 
        gt_novedades
    JOIN 
        gt_resoluciones ON gt_resoluciones.id = gt_novedades.resolucion_id
    JOIN 
        pm_periodo ON pm_periodo.id = gt_novedades.id_periodo
    JOIN 
        gt_empleado ON gt_empleado.id = gt_novedades.id_empleado
    JOIN 
        pm_conceptos ON pm_conceptos.id = gt_novedades.id_concepto
    WHERE 
        gt_novedades.estado = 1 
        AND gt_empleado.estado = 1 
        AND pm_conceptos.estado = 1 
        AND pm_periodo.id = '$par_periodo'
        GROUP BY gt_resoluciones.id, 
        gt_resoluciones.numero, 
        gt_resoluciones.detalle, 
        gt_resoluciones.fecha_resolucion
";

$resultado_res = $con->query($query_resolucion);

// Verificar si hay resultados
if ($resultado_res->num_rows > 0) {

    // Iterar sobre cada fila de resultados
    while ($row200 = $resultado_res->fetch_assoc()) {
        $pdf->SetX(25);  // Esto moverá el cursor 20 unidades a la derecha

        // Obtener los datos de la fila
        $numero_resolucion = $row200['numero_resolucion'];
        $detalle_resolucion = $row200['detalle_resolucion'];
        $fecha_resolucion = $row200['fecha_resolucion'];
        $par_resolucion = $row200['id_resolucion'];

        // Intenta establecer la localización en español (España)
        setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'spanish');

        // Si no se establece correctamente, intenta con una alternativa como 'es_MX.UTF-8' o 'es_CL.UTF-8'
        if (!setlocale(LC_TIME, 'es_ES.UTF-8')) {
            setlocale(LC_TIME, 'es_MX.UTF-8');
        }

        // Usa strftime para formatear la fecha en español
        $fecha_resolucion_formateada = strftime("%d de %B de %Y", strtotime($fecha_resolucion));


        // Construir el mensaje con HTML para mayor control
        $mensaje_html = "
            <p style='text-align: justify; margin-bottom: 10px;'>° Adjuntamos la resolución No. $numero_resolucion de fecha $fecha_resolucion_formateada expedida por la dirección distrital de liquidaciones. $detalle_resolucion.
            </p>
        ";

        // Usar writeHTMLCell en lugar de MultiCell para un mejor control del estilo
        $pdf->writeHTMLCell(0, 0, '', '', $mensaje_html, 0, 1, false, true, 'J', true);
        $pdf->Ln(2);
        $query_resolucion_det = "
                            SELECT gt_empleado.id_tipo_identificacion, gt_empleado.identificacion, gt_empleado.nombre_completo, 	CONCAT( pm_conceptos.Codigo, ' - ', pm_conceptos.descripcion) as concepto, gt_novedades.Valor
                                    FROM gt_novedades 
                                        JOIN gt_resoluciones ON gt_resoluciones.id = gt_novedades.resolucion_id
                                        JOIN pm_periodo ON pm_periodo.id = gt_novedades.id_periodo
                                        JOIN gt_empleado ON gt_empleado.id = gt_novedades.id_empleado
                                        JOIN pm_conceptos ON pm_conceptos.id = gt_novedades.id_concepto
                            where gt_novedades.estado=1 
                                    AND gt_empleado.estado=1 
                                    AND pm_conceptos.estado=1 
                                    AND pm_periodo.id='$par_periodo'
                                    AND gt_resoluciones.id ='$par_resolucion'
                        ";
        $resultado_res_det = $con->query($query_resolucion_det);
        // Verificar si hay resultados
        if ($resultado_res_det->num_rows > 0) {
            // Iniciar la tabla HTML
            $html_novedad_resol_det = '
            <table border="1" cellspacing="0" cellpadding="4" >
                <tr style="background-color: #f2f2f2;">
                        <th>Tipo id</th>
                        <th>Identificación</th>
                        <th>Nombre</th>
                        <th>Concepto</th>
                        <th>Valor</th>
                    </tr>';
            // Iterar sobre cada fila de resultados
            while ($row2000 = $resultado_res_det->fetch_assoc()) {
                // Crear una fila para cada resultado
                $html_novedad_resol_det .= '
                <tr>
                    <td>' . htmlspecialchars($row2000['id_tipo_identificacion']) . '</td>
                    <td>' . htmlspecialchars($row2000['identificacion']) . '</td>
                    <td>' . htmlspecialchars($row2000['nombre_completo']) . '</td>
                    <td>' . htmlspecialchars($row2000['concepto']) . '</td>
                    <td>' . number_format($row2000['Valor'], 2, '.', ',') . '</td>
                </tr>';
            }
             
            // Cerrar la tabla HTML
            $html_novedad_resol_det .= '</table>';
            $pdf->writeHTMLCell(0, 0, 25, '', $html_novedad_resol_det, 0, 1, false, true, 'J', true);
            //$pdf->writeHTML($html_novedad_resol_det, true, false, true, false, '');
            $pdf->Ln(10);
        }
    }    
}




// NOVEDADES SIN RESOLUCION
$query2 = "
SELECT 
    gt_empleado.id_tipo_identificacion, 
    gt_empleado.identificacion, 
    gt_empleado.nombre_completo, 
    CONCAT(pm_conceptos.Codigo, ' - ', pm_conceptos.descripcion) AS concepto, 
    gt_novedades.Valor 
FROM 
    gt_novedades 
JOIN 
    pm_periodo ON pm_periodo.id = gt_novedades.id_periodo 
JOIN 
    gt_empleado ON gt_empleado.id = gt_novedades.id_empleado 
JOIN 
    pm_conceptos ON pm_conceptos.id = gt_novedades.id_concepto 
WHERE 
    gt_novedades.estado = 1  AND gt_empleado.estado=1 
    AND pm_conceptos.estado=1
    AND gt_novedades.resolucion_id IS NULL 
    AND pm_periodo.id = '$par_periodo'
";

// Ejecutar el query y obtener los resultado2s
$resultado2 = $con->query($query2);

// Verificar si hay resultado2s
if ($resultado2->num_rows > 0) {
        // Información adicional u otras novedades sin resolución
        $pdf->Ln(5);
        $pdf->MultiCell(0, 5, "Se relacionan novedades periodo ".$MES_ANO." sin resolución asociada:", 0, 'L');

        // Tabla de totales (ajusta estos datos según tu consulta SQL)
        $pdf->Ln(5);
        // Iniciar la tabla HTML
        $html_novedad_sin_resol = '
        <table border="1" cellspacing="0" cellpadding="4">
            <tr style="background-color: #f2f2f2;">
                    <th>Tipo id</th>
                    <th>Identificación</th>
                    <th>Nombre</th>
                    <th>Concepto</th>
                    <th>Valor</th>
                </tr>';

           // Recorrer los resultados y llenar la tabla
            while ($row2 = $resultado2->fetch_assoc()) {
                // Crear una fila para cada resultado
                $html_novedad_sin_resol .= '
                <tr>
                    <td>' . htmlspecialchars($row2['id_tipo_identificacion']) . '</td>
                    <td>' . htmlspecialchars($row2['identificacion']) . '</td>
                    <td>' . htmlspecialchars($row2['nombre_completo']) . '</td>
                    <td>' . htmlspecialchars($row2['concepto']) . '</td>
                    <td>' . number_format($row2['Valor'], 2, '.', ',') . '</td>
                </tr>';
            }

            // Cerrar la tabla HTML
            $html_novedad_sin_resol .= '</table>';
        
        $pdf->writeHTML($html_novedad_sin_resol, true, false, true, false, '');
}
// Pie de página
$pdf->Ln(10);

// Verificar si hay suficiente espacio en la página
$spaceLeft = $pdf->getPageHeight() - $pdf->GetY() - 20; // Verifica el espacio desde la posición actual hasta el fondo de la página (20mm de margen)
if ($spaceLeft < 60) {  // Si el espacio disponible es menor que el espacio necesario para el pie de página
    $pdf->AddPage();  // Añadir una nueva página
}

// Ahora se puede imprimir el pie de página sin que se divida entre páginas
$pdf->MultiCell(0, 5, "Este reporte fue elaborado con base en la información suministrada hasta el último día habil del mes de ".$MES_ANO.".", 0, 'L');
$pdf->Cell(0, 10, 'Atentamente,', 0, 1);
$pdf->Ln(10);
$pdf->Cell(0, 7, $NOM_DIRECTOR, 0, 1);
$pdf->Cell(0, 7, $CARGO_DIRECTOR, 0, 1);
$pdf->Cell(0, 7, $DES_ENTIDAD, 0, 1);
$pdf->Ln(10);
$pdf->Cell(0, 7, 'Proyectó: '.$proyecto, 0, 1);
$pdf->Cell(0, 7, 'Revisó: '.$reviso1, 0, 1);
$pdf->Cell(0, 7, 'Revisó: '.$reviso2, 0, 1);
$pdf->Cell(0, 7, 'Revisó: '.$reviso3, 0, 1);
$pdf->Cell(0, 7, 'Revisó: '.$reviso4, 0, 1);

// Finaliza el buffer de salida antes de enviar el PDF
ob_end_clean();
// Salida del PDF
$pdf->Output('pdf-novedades-periodo-'.$MES_ANO.'.pdf', 'D');
?>
