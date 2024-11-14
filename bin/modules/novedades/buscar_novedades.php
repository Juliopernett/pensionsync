<?php
require_once ("../../../config/db.php");
require_once ("../../../config/conexion.php");
include('../../../is_logged.php');

if (isset($_GET['action']) && $_GET['action'] == 'ajax') {
    // Verificar si se está recibiendo el parámetro par_periodo
    if (isset($_GET['par_periodo'])) {
        $par_periodo = mysqli_real_escape_string($con, $_GET['par_periodo']);

        $query_periodo2 = "SELECT id, codigo FROM pm_periodo WHERE id= $par_periodo LIMIT 1";
        $result_periodo2 = mysqli_query($con, $query_periodo2);
        $periodo_actual2 = mysqli_fetch_assoc($result_periodo2);
        $act_periodo2 = $periodo_actual2['codigo']; // Suponiendo que usas el id del periodo

        // Campos de búsqueda
        $aColumns = array(
            'gt_novedades.id', 
            'gt_novedades.id_empleado', 
            'gt_empleado.nombre_completo', 
            'gt_novedades.id_concepto', 
            'pm_conceptos.Codigo', 
            'pm_conceptos.descripcion', 
            'gt_novedades.id_periodo', 
            'pm_periodo.codigo', 
            'gt_novedades.Valor', 
            'gt_novedades.estado', 
            'gt_novedades.usuario_registro', 
            'gt_novedades.fecha_registro', 
            'gt_novedades.resolucion_id', 
            'gt_resoluciones.numero', 
            'gt_resoluciones.fecha_resolucion'
        );

        $sTable = "gt_novedades";
        $sWhere = "";

        if ($_GET['q'] != "") {
            $q = mysqli_real_escape_string($con, $_GET['q']); // Escapar la entrada
            $sWhere = "WHERE (";
            foreach ($aColumns as $column) {
                $sWhere .= "$column LIKE '%$q%' OR ";
            }
            $sWhere .="CASE gt_novedades.estado
                        WHEN '1' THEN 'Activo'
                        WHEN '0' THEN 'Inactivo'
                        END LIKE '%$q%' OR";
            $sWhere = substr_replace($sWhere, "", -3); // Eliminar el último "OR"
            $sWhere .= ')';
        }

        // Filtrar por periodo si se ha seleccionado uno
        if ($par_periodo != '') {
            if ($sWhere == "") {
                $sWhere = "WHERE gt_novedades.id_periodo = '$par_periodo'";
            } else {
                $sWhere .= " AND gt_novedades.id_periodo = '$par_periodo'";
            }
        }

        $sWhere .= " ORDER BY gt_novedades.id ASC";
        //echo ($sWhere );
        include 'pagination.php'; // Archivo de paginación

        $page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
        $per_page = 4; // Registros por página
        $adjacents  = 4; // Adyacentes de paginación
        $offset = ($page - 1) * $per_page;
    } else {
        echo "No se recibió el parámetro par_periodo.";
    }
    
    // Contar filas
    $count_query = mysqli_query($con, "
                                    SELECT COUNT(*) AS numrows 
                                    FROM $sTable 
                                        LEFT JOIN gt_resoluciones ON gt_resoluciones.id = gt_novedades.resolucion_id
                                        JOIN pm_periodo ON pm_periodo.id = gt_novedades.id_periodo
                                        JOIN gt_empleado ON gt_empleado.id = gt_novedades.id_empleado
                                        JOIN pm_conceptos ON pm_conceptos.id = gt_novedades.id_concepto
                                    $sWhere
                                ");
    if (!$count_query) {
        die("Error en la consulta: " . mysqli_error($con));
    }

    $row = mysqli_fetch_array($count_query);
    $numrows = $row['numrows'];
    $total_pages = ceil($numrows / $per_page);
    $reload = 'novedades.php';

    // Consulta con paginación
    $sql = "SELECT gt_novedades.id, gt_novedades.id_empleado, gt_empleado.nombre_completo, 
               gt_novedades.id_concepto, pm_conceptos.Codigo as codigo_concepto, 
               pm_conceptos.descripcion as descripcion_concepto, gt_novedades.id_periodo, 
               pm_periodo.codigo as codigo_periodo, gt_novedades.Valor, gt_novedades.estado, 
               gt_novedades.usuario_registro, gt_novedades.fecha_registro, 
               gt_novedades.resolucion_id, gt_resoluciones.numero as numero_resolucion, 
               gt_resoluciones.fecha_resolucion as fecha_resolucion,
               
               case gt_novedades.estado
                            when '0' then 'Inactivo'
                            when '1' then 'Activo'
                        end as estado_descripcion
        FROM gt_novedades 
            LEFT JOIN gt_resoluciones ON gt_resoluciones.id = gt_novedades.resolucion_id
            JOIN pm_periodo ON pm_periodo.id = gt_novedades.id_periodo
            JOIN gt_empleado ON gt_empleado.id = gt_novedades.id_empleado
            JOIN pm_conceptos ON pm_conceptos.id = gt_novedades.id_concepto
        $sWhere 
        LIMIT $offset, $per_page
    ";

    $query = mysqli_query($con, $sql);
    if (!$query) {
        echo "Error en la consulta SQL: " . mysqli_error($con);
        exit;
    }

    if ($numrows > 0) {
        ?>
        <div class="table-responsive">
            <table class="table">
                <tr>
                    <th>#ID</th>
                    <th>Empleado</th>
                    <th>Concepto</th>
                    <th>Resolución</th>
                    <th>Valor</th>
                    <th>Estado</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                    <th>Editar</th>
                </tr>
                <?php
                while ($row = mysqli_fetch_array($query)) {
                    $id = $row['id'];
                    $empleado = $row['nombre_completo'];
                    $concepto = $row['codigo_concepto']." - ".$row['descripcion_concepto']; 
                    $resolucion = (!empty($row['numero_resolucion']) && !empty($row['fecha_resolucion'])) 
                                    ? $row['numero_resolucion']." de ".$row['fecha_resolucion'] 
                                    : null;
                    $periodo = $row['codigo_periodo'];
                    $valor = $row['Valor'];
                    $estado_descripcion = $row['estado_descripcion'];
                    $usuario_registro = $row['usuario_registro'];
                    $fecha_registro = $row['fecha_registro'];
                    ?>
                   <!---pasar parametros para editar inf.basica-->
                    <input type="hidden" value="<?php echo $row['id'];?>" id="id<?php echo $id;?>">
                    <input type="hidden" value="<?php echo $row['id_empleado'];?>" id="id_empleado<?php echo $id;?>">
                    <input type="hidden" value="<?php echo $row['id_concepto'];?>" id="id_concepto<?php echo $id;?>">
                    <input type="hidden" value="<?php echo $row['id_periodo'];?>" id="id_periodo<?php echo $id;?>">
                    <input type="hidden" value="<?php echo $row['Valor'];?>" id="Valor<?php echo $id;?>">
                    <input type="hidden" value="<?php echo $row['estado'];?>" id="estado<?php echo $id;?>">
                    <input type="hidden" value="<?php echo $row['resolucion_id'];?>" id="resolucion_id<?php echo $id;?>">
                    <input type="hidden" value="<?php echo $row['codigo_periodo'];?>" id="codigo_periodo<?php echo $id;?>">
                    <tr>
                        <td><?php echo $id; ?></td>
                        <td><?php echo $empleado; ?></td>
                        <td><?php echo $concepto; ?></td>
                        <td><?php echo $resolucion; ?></td>
                        <td><?php echo '$' . number_format($valor, 2, ',', '.'); ?></td>
                        <td><?php echo $estado_descripcion; ?></td>
                        <td><?php echo $usuario_registro; ?></td>
                        <td><?php echo $fecha_registro; ?></td>
                        <td>
                            <a href="#" class='btn btn-secondary' title='Editar información del concepto' onclick="obtener_datos('<?php echo $id; ?>');" data-toggle="modal" data-target="#myModal2" style="margin-left: 5px;">
                                <i class="glyphicon glyphicon-pencil"></i>
                            </a>
                        </td>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <td colspan="9">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <!-- Pasar el parámetro par_periodo en la URL -->
                            <a href="export_excel.php?par_periodo=<?php echo urlencode($par_periodo); ?>" class="btn btn-link">
                                <img src="../../../img/excel.png" alt="Excel Logo" style="width: 20px; height: 20px; vertical-align: middle; margin-right: 5px;">
                                Exportar novedades del periodo: <?php echo htmlspecialchars($act_periodo2); ?>
                            </a>
                            <a href="export_excel.php" class="btn btn-link">
                                <img src="../../../img/excel.png" alt="Excel Logo" style="width: 20px; height: 20px; vertical-align: middle; margin-right: 5px;">
                                Exportar novedades histotico
                            </a>
                            <a href="export_pdf.php?par_periodo=<?php echo urlencode($par_periodo); ?>" class="btn btn-link">
                                <img src="../../../img/pdf.jpg" alt="Pdf Logo" style="width: 20px; height: 20px; vertical-align: middle; margin-right: 5px;">
                                Generar PDF de novedades
                            </a>
                            <span class="pull-right">
                                <?php echo paginate($reload, $page, $total_pages, $adjacents); ?>
                            </span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    }else{
    
            ?>
            <div class="alert alert-info" role="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php
                    echo "No hay datos para el periodo seleccionado o parámetros de busqueda";
                 ?>
            </div>
            <?php
    }


}
?>

<script>

function load(page) {
        var q = $("#q").val();
        var par_periodo = $("#sel_periodo").val(); // Obtener el valor del periodo seleccionado
       
        // Validar que se haya seleccionado un periodo
        if (par_periodo === "" || par_periodo == null) {
            console.log("No se ha seleccionado un periodo");
            return; // Evitar la ejecución de la búsqueda si no hay periodo seleccionado
        } else {
            console.log("Cargando con el periodo:", par_periodo);  // Verificar el valor de 'periodo'
            console.log("Buscando con los parámetros:", q, par_periodo);
            
            $.ajax({
                url: 'buscar_novedades.php?action=ajax&page=' + page + '&q=' + q + '&par_periodo=' + par_periodo,
                beforeSend: function() {
                    $("#loader").fadeIn('slow');
                },
                success: function(data) {
                    $(".outer_div").html(data).fadeIn('slow');
                    $("#loader").fadeOut('slow');
                }
            })
        }
    }
</script>
