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
			if ($delete1 = mysqli_query($con, "DELETE FROM pm_conceptos WHERE id = '$id'")) {
				echo '<div class="alert alert-success">Datos eliminados correctamente.</div>';
			} else {
				echo '<div class="alert alert-danger">Ocurrió un error al eliminar los datos.</div>';
			}
		} else {
			echo '<div class="alert alert-danger">No se puede eliminar el administrador.</div>';
		}
	}

	// Listado de conceptos con paginación
	if ($action == 'ajax') {
		$q = mysqli_real_escape_string($con, (strip_tags($_REQUEST['q'], ENT_QUOTES)));
		$aColumns = array('pm_conceptos.id', 'pm_conceptos.codigo', 
							'pm_conceptos.descripcion', 'pm_conceptos.tipo_movimiento', 
							'pm_conceptos.tipo_concepto', 'pm_conceptos.estado', 
							'pm_conceptos.usuario_registro', 'pm_conceptos.fecha_registro'
						);

		$sTable = "pm_conceptos";
		$sWhere = "";

		if ($_GET['q'] != "") {
			$sWhere = "WHERE (";
			for ($i = 0; $i < count($aColumns); $i++) {
				$sWhere .= $aColumns[$i] . " LIKE '%$q%' OR ";
			}
			// Añadir las descripciones
			$sWhere .= "CASE pm_conceptos.tipo_movimiento
							WHEN '1' THEN 'Devengos'
							WHEN '2' THEN 'Descuentos'
							WHEN '3' THEN 'Total Devengos'
							WHEN '4' THEN 'Total Descuento'
							WHEN '5' THEN 'Valor Neto'
							WHEN '6' THEN 'Informativo'
							WHEN '7' THEN 'Aporte Patrono'
							WHEN '8' THEN 'PARAFISCALES'
							WHEN '9' THEN 'Provision'
						END LIKE '%$q%' OR ";
						
			$sWhere .= "CASE pm_conceptos.tipo_concepto
							WHEN '1' THEN 'Pensionado'
							WHEN '2' THEN 'Activo'
							WHEN '3' THEN 'Sustituto'
						END LIKE '%$q%' OR ";
						
			// Limpiar el último "OR"
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
		$reload = 'conceptos.php';

		// Consulta con paginación
		$sql = "SELECT 
					pm_conceptos.id, 
					pm_conceptos.codigo, 
					CONCAT(UPPER(SUBSTRING(pm_conceptos.descripcion, 1, 1)), LOWER(SUBSTRING(pm_conceptos.descripcion, 2))) AS descripcion, 
					pm_conceptos.tipo_movimiento, 
					case pm_conceptos.tipo_movimiento
						when '1' then 'Devengos'
						when '2' then 'Descuentos'
						when '3' then 'Total Devengos'
						when '4' then 'Total Descuento'
						when '5' then 'Valor Neto'
						when '6' then 'Informativo'
						when '7' then 'Aporte Patrono'
						when '8' then 'PARAFISCALES'
						when '9' then 'Provision'
					end as tipo_movimiento_descripcion,
					pm_conceptos.tipo_concepto,
					case pm_conceptos.tipo_concepto
						when '1' then 'Pensionado'
						when '2' then 'Activo'
						when '3' then 'Sustituto'
					end as tipo_concepto_descripcion,
					pm_conceptos.estado, 
					case pm_conceptos.estado
						when '0' then 'Inactivo'
						when '1' then 'Activo'
					end as estado_descripcion,
					pm_conceptos.usuario_registro, 
					pm_conceptos.fecha_registro 
				FROM 
					pm_conceptos
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
						<th>Codigo</th>
						<th>descripción</th>
						<th>Tipo Movimiento</th> 
						<th>Tipo concepto / Finalidad</th>
						<th>Estado</th>
						<th>Usuario de registro</th>
						<th>Fecha de registro o actualización</th>
						<th>Editar</th>
					</tr>
					<?php
					
					while ($row = mysqli_fetch_array($query)) {

						$id = $row['id'];
						$codigo = $row['codigo'];
						$descripcion = $row['descripcion'];
						$tipo_movimiento = $row['tipo_movimiento'];
						$tipo_movimiento_descripcion = $row['tipo_movimiento_descripcion'];
						$tipo_concepto = $row['tipo_concepto'];
						$tipo_concepto_descripcion = $row['tipo_concepto_descripcion'];
						$estado = $row['estado'];
						$estado_descripcion = $row['estado_descripcion'];
						$usuario_registro = $row['usuario_registro'];
						$fecha_registro = $row['fecha_registro'];

						?>
						<!---pasar parametros para editar inf-->
						<input type="hidden" value="<?php echo $row['id'];?>" id="id<?php echo $id;?>">
						<input type="hidden" value="<?php echo $row['codigo'];?>" id="codigo<?php echo $id;?>">
						<input type="hidden" value="<?php echo $row['descripcion'];?>" id="descripcion<?php echo $id;?>">
						<input type="hidden" value="<?php echo $row['tipo_movimiento'];?>" id="tipo_movimiento<?php echo $id;?>">
						<input type="hidden" value="<?php echo $row['tipo_concepto'];?>" id="tipo_concepto<?php echo $id;?>">
						<input type="hidden" value="<?php echo $row['estado'];?>" id="estado<?php echo $id;?>">
						<!------------------------------------->
						
						<tr>
							<td><?php echo $id; ?></td>
							<td><?php echo $codigo; ?></td>
							<td><?php echo $descripcion; ?></td>
							<td><?php echo $tipo_movimiento_descripcion; ?></td>
							<td><?php echo $tipo_concepto_descripcion; ?></td>
							<td><?php echo $estado_descripcion; ?></td>
							<td><?php echo $usuario_registro; ?></td>
							<td><?php echo $fecha_registro; ?></td>
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
									Exportar listado de conceptos
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
        url: 'buscar_conceptos.php?action=ajax&page=' + page + '&q=' + q,
        beforeSend: function() {
            $("#loader").html("");
        },
        success: function(data) {
            $(".table-responsive").html(data);
        }
    });
}

$('#viewEmployeeModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Botón que abre el modal
    var empleadoId = $(button).attr('data-id'); // Suponiendo que también tienes un data-id
    //alert(empleadoId); 
	loadEmpleadosData(empleadoId); // Llama a la función para cargar los datos
});

</script> 
