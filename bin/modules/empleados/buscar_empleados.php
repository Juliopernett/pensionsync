<?php
require_once ("../../../config/db.php");
require_once ("../../../config/conexion.php");
include('../../../is_logged.php');

$action = (isset($_REQUEST['action']) && $_REQUEST['action'] != NULL) ? $_REQUEST['action'] : '';

// Eliminar EMPLEADOS
if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    $query = mysqli_query($con, "SELECT * FROM gt_empleado WHERE id = '$user_id'");
    $rw_user = mysqli_fetch_array($query);
    $count = $rw_user['id'];

    if ($count == 0) {
        if ($delete1 = mysqli_query($con, "DELETE FROM gt_empleado WHERE id = '$user_id'")) {
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
    $aColumns = array('gt_empleado.id', 'gt_empleado.id_tipo_identificacion', 
                        'gt_empleado.identificacion', 'gt_empleado.primer_apellido', 
                        'gt_empleado.segundo_apellido', 'gt_empleado.primer_nombre', 
                        'gt_empleado.segundo_nombre', 'gt_empleado.id_cargo',  
                        'gt_empleado.id_tipo_empleado', 'gt_empleado.estado', 
                        'gt_empleado.usuario_registro', 'gt_empleado.fecha_registro',
                        'gt_empleado.nombre_completo'
                    );
    $sTable = "gt_empleado";
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
    $reload = 'empleados.php';

    // Consulta con paginación
    $sql = "SELECT gt_empleado.*, 
                    pm_tipo_identificacion.descripcion AS id_tipo_identificacion_desc,
                    pm_tipo_empleado.descripcion as id_tipo_empleado_desc,
                    pm_cargos.descripcion as id_cargo_desc
            FROM gt_empleado 
            JOIN pm_tipo_identificacion 
                ON gt_empleado.id_tipo_identificacion = pm_tipo_identificacion.tipo_identificacion 
            JOIN pm_tipo_empleado 
                ON pm_tipo_empleado.id = gt_empleado.id_tipo_empleado 
            JOIN pm_cargos
                ON pm_cargos.id = gt_empleado.id_cargo 
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
                    <th>Tipo Doc.</th>
                    <th>Documento</th>
                    <th>Nombre completo</th> 
                    <th>Cargo</th>
                    <th>Tipo empleado</th>
                    <th>Estado</th>
                    <th>Usuario de registro</th>
                    <th>Fecha de registro o actualización</th>
                    <th>Adjuntos / Ver + inf. / Editar</th>
                </tr>
                <?php
                while ($row = mysqli_fetch_array($query)) {
                    $id = $row['id'];
                    $id_tipo_identificacion_desc = $row['id_tipo_identificacion_desc'];
                    $identificacion = $row['identificacion'];
                    $nombre_completo = ucfirst(strtolower($row['nombre_completo']));
                    $id_tipo_empleado_desc = ucfirst(strtolower($row['id_tipo_empleado_desc']));
                    $id_cargo_desc = ucfirst(strtolower($row['id_cargo_desc']));
                    $estado_n = $row['estado'];
                    $estado = ($row['estado'] == 0) ? 'Inactivo' : 'Activo';
                    $usuario_registro = $row['usuario_registro'];
                    $fecha_registro = $row['fecha_registro'];

                    // Verificar si el id_empleado tiene documentos asociados
                    $id_empleado = $row['id'];
                    $query_check_documento = mysqli_query($con, "SELECT COUNT(*) as num_docs FROM gt_documentos WHERE id_empleado = '$id_empleado'");
                    $row_check = mysqli_fetch_assoc($query_check_documento);
                    $documento_existe = ($row_check['num_docs'] > 0); // true si existe al menos un documento, false si no

					/** solo se imprime el ultimo que se cargo
					 */
					$query_documento = mysqli_query($con, "SELECT max(id_documento) as doc_id FROM gt_documentos WHERE id_empleado = '$id_empleado'");
					$row_doc = mysqli_fetch_assoc($query_documento);
					$id_documento = $row_doc['doc_id'];

                    ?>
					<!---pasar parametros para editar inf.basica-->
					<input type="hidden" value="<?php echo $row['id'];?>" id="id<?php echo $id;?>">
					<input type="hidden" value="<?php echo $row['estado'];?>" id="estado<?php echo $id;?>">
					<input type="hidden" value="<?php echo $row['id_tipo_identificacion'];?>" id="id_tipo_identificacion<?php echo $id;?>">
					<input type="hidden" value="<?php echo $row['id_tipo_empleado'];?>" id="id_tipo_empleado<?php echo $id;?>">
					<input type="hidden" value="<?php echo $row['identificacion'];?>" id="identificacion<?php echo $id;?>">
					<input type="hidden" value="<?php echo ucfirst(strtolower($row['primer_apellido']));?>" id="primer_apellido<?php echo $id;?>">
					<input type="hidden" value="<?php echo ucfirst(strtolower($row['primer_nombre']));?>" id="primer_nombre<?php echo $id;?>">
					<input type="hidden" value="<?php echo ucfirst(strtolower($row['segundo_apellido']));?>" id="segundo_apellido<?php echo $id;?>">
					<input type="hidden" value="<?php echo ucfirst(strtolower($row['segundo_nombre']));?>" id="segundo_nombre<?php echo $id;?>">

					<!---pasar parametros para editar inf. adicional-->
					<input type="hidden" value="<?php echo $row['sexo'];?>" id="sexo<?php echo $id;?>">
					<input type="hidden" value="<?php echo $row['telefono'];?>" id="telefono<?php echo $id;?>">
					<input type="hidden" value="<?php echo $row['direccion'];?>" id="direccion<?php echo $id;?>">
					<input type="hidden" value="<?php echo $row['correo_electronico'];?>" id="correo_electronico<?php echo $id;?>">
					<input type="hidden" value="<?php echo $row['fcha_cumpleano'];?>" id="fcha_cumpleano<?php echo $id;?>">
					<input type="hidden" value="<?php echo $row['dato_pension'];?>" id="dato_pension<?php echo $id;?>">
					<input type="hidden" value="<?php echo $row['id_Empleado_sustituto'];?>" id="id_Empleado_sustituto<?php echo $id;?>">
					<input type="hidden" value="<?php echo $row['fecha_pension'];?>" id="fecha_pension<?php echo $id;?>">
					<input type="hidden" value="<?php echo $row['fecha_sustitucion'];?>" id="fecha_sustitucion<?php echo $id;?>">
					<input type="hidden" value="<?php echo $row['porcentaje_sustitucion'];?>" id="porcentaje_sustitucion<?php echo $id;?>">

					<!---pasar parametros para editar inf. afiliaciones-->
					<input type="hidden" value="<?php echo $row['id_fondo_pension'];?>" id="id_fondo_pension<?php echo $id;?>">
					<input type="hidden" value="<?php echo $row['id_eps'];?>" id="id_eps<?php echo $id;?>">
					<input type="hidden" value="<?php echo $row['id_fondo_cesantias'];?>" id="id_fondo_cesantias<?php echo $id;?>">
					<input type="hidden" value="<?php echo $row['id_arl'];?>" id="id_arl<?php echo $id;?>">
					<input type="hidden" value="<?php echo $row['id_caja_compensacion'];?>" id="id_caja_compensacion<?php echo $id;?>">
					<input type="hidden" value="<?php echo $row['id_riesgo_arl'];?>" id="id_riesgo_arl<?php echo $id;?>">
					<input type="hidden" value="<?php echo $row['id_cargo'];?>" id="id_cargo<?php echo $id;?>">

                    <input type="hidden" value="<?php echo $row['nombre_completo'];?>" id="inf_empleado<?php echo $id;?>">


                    <tr>
                        <td><?php echo $id; ?></td>
                        <td><?php echo $id_tipo_identificacion_desc; ?></td>
                        <td><?php echo $identificacion; ?></td>
                        <td><?php echo $nombre_completo; ?></td>
                        <td><?php echo $id_cargo_desc; ?></td>
                        <td><?php echo $id_tipo_empleado_desc; ?></td>
                        <td><?php echo $estado; ?></td>
                        <td><?php echo $usuario_registro; ?></td>
                        <td><?php echo $fecha_registro; ?></td>
                        <td style="display: flex; gap: 5px;">
                            <!-- Botón para descargar adjuntos, habilitado o deshabilitado según la existencia del documento -->
                            <a href="descargar_adjuntos.php?id=<?php echo $id_documento; ?>" id="btnDescargar<?php echo $id; ?>" class="btn btn-secondary <?php echo $documento_existe ? '' : 'disabled'; ?>" 
                               title="Descargar adjuntos"  
                               <?php echo $documento_existe ? '' : 'disabled'; ?>>
                               <i class="glyphicon glyphicon-download"></i>
                            </a>

                            <!-- Botón para cargar o reemplazar adjuntos, siempre habilitado -->
                            <a href="#" class="btn btn-secondary" title="Cargar o reemplazar adjuntos" onclick="subir_pdf('<?php echo $id; ?>');" data-toggle="modal" data-target="#myModalCargaDoc">
                               <i class="glyphicon glyphicon-upload"></i>
                            </a>

                            <!-- Otros botones (ver, editar) -->
                            <a href="#" class="btn btn-secondary" title="Ver información completa del empleado" data-id="<?php echo $id; ?>" data-toggle="modal" data-target="#viewEmployeeModal">
                               <i class="glyphicon glyphicon-zoom-in"></i>
                            </a>
                            <a href="#" class="btn btn-secondary" title="Editar información del empleado" onclick="obtener_datos('<?php echo $id; ?>');" data-toggle="modal" data-target="#myModal2">
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
                                Exportar listado de empleados</a>
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

<script>
function load(page) {
    var q = $("#q").val();  // Valor de búsqueda si existe
    $.ajax({
        url: 'buscar_empleados.php?action=ajax&page=' + page + '&q=' + q,
        success: function(data) {
            $(".outer_div").html(data);
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
