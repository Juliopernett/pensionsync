<?php
require_once ("../../../config/db.php");
require_once ("../../../config/conexion.php");
include('../../../is_logged.php');

if (isset($_POST['id'])) {
    $id = intval($_POST['id']); // Aseguramos de que sea un entero

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
    empleado.estado, 
    empleado.usuario_registro, 
    empleado.fecha_registro
FROM gt_empleado empleado 
JOIN pm_tipo_identificacion tipo_ide 
    ON tipo_ide.tipo_identificacion = empleado.id_tipo_identificacion 
JOIN pm_cargos cargo 
    ON cargo.id = empleado.id_cargo 
JOIN pm_tipo_empleado tipo_emp 
    ON tipo_emp.id = empleado.id_tipo_empleado 
WHERE empleado.id = $id;
"; 

    $result = mysqli_query($con, $query);
 
    // Verificar si la consulta se ejecutó correctamente
    if (!$result) {
        echo json_encode(array("error" => "Error en la consulta: " . mysqli_error($con)));
        exit;
    }
    
    if ($row = mysqli_fetch_assoc($result)) {
        echo json_encode($row); // Devuelve los datos en formato JSON
    } else {
        echo json_encode(array("error" => "No se encontraron datos."));
    }
} else {
    echo json_encode(array("error" => "ID no válido."));
}
?>
