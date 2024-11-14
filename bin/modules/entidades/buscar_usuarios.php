<?php
	require_once ("../../../config/db.php");
	require_once ("../../../config/conexion.php");
	include('../../../is_logged.php');

	$action = (isset($_REQUEST['action']) && $_REQUEST['action'] != NULL) ? $_REQUEST['action'] : '';

	// Eliminar entidad
	if (isset($_GET['id'])) {
		$user_id = intval($_GET['id']);
		$query = mysqli_query($con, "SELECT * FROM pm_entidades WHERE id = '$user_id'");
		$rw_user = mysqli_fetch_array($query);
		$count = $rw_user['id'];

		if ($count == 0) {
			if ($delete1 = mysqli_query($con, "DELETE FROM pm_entidades WHERE id = '$user_id'")) {
				echo '<div class="alert alert-success">Datos eliminados correctamente.</div>';
			} else {
				echo '<div class="alert alert-danger">Ocurrió un error al eliminar los datos.</div>';
			}
		} else {
			echo '<div class="alert alert-danger">No se puede eliminar el administrador.</div>';
		}
	}

	// Listado de usuarios con paginación
	if ($action == 'ajax') {
		$q = mysqli_real_escape_string($con, (strip_tags($_REQUEST['q'], ENT_QUOTES)));
		$aColumns = array('pm_entidades.id', 'pm_entidades.nit', 'pm_entidades.dv', 'pm_entidades.nombre',  'pm_entidades.tipo_entidad','pm_entidades.estado', 'pm_entidades.usuario_registro', 'pm_entidades.fecha_registro');
		$sTable = "pm_entidades";
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
		$row = mysqli_fetch_array($count_query);
		$numrows = $row['numrows'];
		$total_pages = ceil($numrows / $per_page);
		$reload = 'entidades.php';

		// Consulta con paginación
		$sql = "SELECT pm_entidades.*, pm_tipo_entidad.descripcion AS tipo_descripcion
        FROM pm_entidades 
          LEFT JOIN pm_tipo_entidad ON pm_entidades.tipo_entidad = pm_tipo_entidad.id 
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
						<th>Nit</th>
						<th>Nombre</th>
						<th>Tipo entidad</th>
						<th>Estado</th>
						<th>Usuario de registro</th>
						<th>Fecha de registro / Actualización</th>
						<th>Acciones</th>
					</tr>
					<?php
					
					while ($row = mysqli_fetch_array($query)) {

						/* OPTENIENDO DESCRIPCION DEL TIPO ENTIDAD 
						$sql2 = "SELECT descripcion AS DESCRIPCION_TIPO_ENTIDAD
						FROM PM_TIPO_ENTIDAD 
						WHERE ID=".$row['tipo_entidad'];

						$query2 = mysqli_query($con, $sql2);
						$row2 =  mysqli_fetch_array($query2);
					   /* OPTENIENDO DESCRIPCION DEL TIPO ENTIDAD */

						$id = $row['id'];
						$nit = $row['nit'].'-'.$row['dv'];
						$nombre = $row['nombre'];
						$tipo_entidad = $row['tipo_entidad'];
						$estado_n = $row['estado'];
						$estado = ($row['estado'] == 0) ? 'Inactivo' : 'Activo';
						$usuario_registro = $row['usuario_registro'];
						$fecha_registro = $row['fecha_registro'];
						$des_tipo_entidad =$row['tipo_descripcion'];

						?>
						<!---pasar parametros para editar-->
						<input type="hidden" value="<?php echo $estado_n;?>" id="estado<?php echo $id;?>">
						<input type="hidden" value="<?php echo $row['id'];?>" id="id<?php echo $id;?>">
						<input type="hidden" value="<?php echo $row['nombre'];?>" id="nombre<?php echo $id;?>">
						<input type="hidden" value="<?php echo $row['nit'];?>" id="nit<?php echo $id;?>">
						<input type="hidden" value="<?php echo $row['tipo_entidad'];?>" id="tipo_entidad<?php echo $id;?>">
						<input type="hidden" value="<?php echo $row['estado'];?>" id="estado<?php echo $id;?>">
						<input type="hidden" value="<?php echo $row['dv'];?>" id="dv<?php echo $id;?>">
						
						<tr>
							<td><?php echo $id; ?></td>
							<td><?php echo $nit; ?></td>
							<td><?php echo $nombre; ?></td>
							<td><?php echo $des_tipo_entidad; ?></td>
							<td><?php echo $estado; ?></td>
							<td><?php echo $usuario_registro; ?></td>
							<td><?php echo $fecha_registro; ?></td>
							<td>
								<a href="#" class='btn btn-secondary' title='Editar entidad' onclick="obtener_datos('<?php echo $id; ?>');" data-toggle="modal" data-target="#myModal2">
									<i class="glyphicon glyphicon-pencil"></i>
								</a>
							</td>
						</tr>
					<?php
					}
					?>
					<tr>
						<td colspan=6><span class="pull-right">
						<?php echo paginate($reload, $page, $total_pages, $adjacents); ?>
						</span></td>
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
        url: 'buscar_usuarios.php?action=ajax&page=' + page + '&q=' + q,
        beforeSend: function() {
            $("#loader").html("");
        },
        success: function(data) {
            $(".table-responsive").html(data);
        }
    });
}
</script> 
