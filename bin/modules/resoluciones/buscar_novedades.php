<?php
	require_once ("../../../config/db.php");
	require_once ("../../../config/conexion.php");
	include('../../../is_logged.php');

	$action = (isset($_REQUEST['action']) && $_REQUEST['action'] != NULL) ? $_REQUEST['action'] : '';

	// Eliminar concepto
	if (isset($_GET['id'])) {
		$id = intval($_GET['id']);
		$query = mysqli_query($con, "SELECT * FROM pm_conceptos WHERE id = '$id'");
		$rw = mysqli_fetch_array($query);
		$count = $rw['id'];

		if ($count == 0) {
			if ($delete1 = mysqli_query($con, "DELETE FROM gt_resoluciones WHERE id = '$id'")) {
				echo '<div class="alert alert-success">Datos eliminados correctamente.</div>';
			} else {
				echo '<div class="alert alert-danger">Ocurrió un error al eliminar los datos.</div>';
			}
		} else {
			echo '<div class="alert alert-danger">No se puede eliminar el administrador.</div>';
		}
	}


	// Listado de resoluciones con paginación
	if ($action == 'ajax') {
		$q = mysqli_real_escape_string($con, (strip_tags($_REQUEST['q'], ENT_QUOTES)));
		$aColumns = array('gt_resoluciones.id', 'gt_resoluciones.numero', 
						  'gt_resoluciones.detalle', 'gt_resoluciones.fecha_resolucion', 
						  'gt_resoluciones.fecha_registro', 'gt_resoluciones.usuario_registro', 
						  'gt_resoluciones.estado'
						);

		$sTable = "gt_resoluciones";
		$sWhere = "";


		if ($_GET['q'] != "") {
			$sWhere = "WHERE (";
			for ($i = 0; $i < count($aColumns); $i++) {
				$sWhere .= $aColumns[$i] . " LIKE '%$q%' OR ";
			}
			$sWhere = substr_replace($sWhere, "", -3);
			$sWhere .= ')';
		}
		$sWhere .= " ORDER BY id ASC";
		
		include 'pagination.php'; // Llamar al archivo de paginación

		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
		$per_page = 4; // Número de registros por página
		$adjacents  = 4; // Adyacentes de paginación
		$offset = ($page - 1) * $per_page;

		// Contar el total de filas
		$count_query = mysqli_query($con, "SELECT COUNT(*) AS numrows FROM $sTable $sWhere");
		
		if (!$count_query) {
			die("Error en la consulta: " . mysqli_error($con));
		}
		
		$row = mysqli_fetch_array($count_query);
		$numrows = $row['numrows'];
		$total_pages = ceil($numrows / $per_page);
		$reload = 'resoluciones.php';

		// Consulta con paginación
		$sql = "SELECT id, numero, detalle, fecha_resolucion, 
						fecha_registro, usuario_registro, estado, 
						case gt_resoluciones.estado
							when '0' then 'Inactivo'
							when '1' then 'Activo'
						end as estado_descripcion
					FROM gt_resoluciones
		  $sWhere LIMIT $offset, $per_page";

		$query = mysqli_query($con, $sql);
		if (!$query) {
			// Mostrar el error de MySQL
			echo "Error en la consulta SQL: " . mysqli_error($con);
			exit;
		}
		// Mostrar los resultados
		if ($numrows > 0) {
			?>
			<div class="table-responsive">
				<table class="table">
					<tr>
						<th>#ID</th>
						<th>Número</th>
						<th>Detalle de la resolución</th>
						<th>Fecha Documento</th> 
						<th>Estado</th>
						<th>Usuario</th>
						<th>Editar</th>
					</tr>
					<?php
					
					while ($row = mysqli_fetch_array($query)) {

						$id = $row['id'];
						$numero = $row['numero'];
						$detalle = $row['detalle'];
						$fecha_resolucion = $row['fecha_resolucion'];
						$estado = $row['estado'];
						$estado_descripcion = $row['estado_descripcion'];
						$usuario_registro = $row['usuario_registro'];
						$fecha_registro = $row['fecha_registro'];

						?>
						<!---pasar parametros para editar inf-->
						<input type="hidden" value="<?php echo $row['id'];?>" id="id<?php echo $id;?>">
						<input type="hidden" value="<?php echo $row['numero'];?>" id="numero<?php echo $id;?>">
						<input type="hidden" value="<?php echo $row['detalle'];?>" id="detalle<?php echo $id;?>">
						<input type="hidden" value="<?php echo $row['fecha_resolucion'];?>" id="fecha_resolucion<?php echo $id;?>">
						<input type="hidden" value="<?php echo $row['estado'];?>" id="estado<?php echo $id;?>">
						<!------------------------------------->
						
						<tr>
							<td><?php echo $id; ?></td>
							<td><?php echo $numero; ?></td>
							<td><?php echo $detalle; ?></td>
							<td><?php echo $fecha_resolucion; ?></td>

							<td><?php echo $estado_descripcion; ?></td>
							<td><?php echo $usuario_registro; ?></td>
							<td style="display: flex;">
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
								<a href="export_excel.php" class="btn btn-link">
									<img src="../../../img/excel.png" alt="Excel Logo" style="width: 20px; height: 20px; vertical-align: middle; margin-right: 5px;">
									Exportar listado de resoluciones
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
		}
	}
?>

<!-- Añadir el script de paginación -->
<script>
function load(page) {
    var q = $("#q").val();  // Valor de búsqueda si existe
    $.ajax({
        url: 'buscar_novedades.php?action=ajax&page=' + page + '&q=' + q,
        beforeSend: function() {
            $("#loader").html("");
        },
        success: function(data) {
            $(".table-responsive").html(data);
        }
    });
}


</script> 
