<?php
include('../../../is_logged.php');

if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    require_once("../../../lib/password_compatibility_library.php");
}

$errors = [];
$messages = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    function validarCampo($campo, $mensaje, &$errores, $min = 2, $max = 50) {
        if (empty($campo) || strlen($campo) < $min || strlen($campo) > $max) {
            $errores[] = $mensaje;
        }
    }

    validarCampo($_POST['new_primer_nombre'], "Primer nombre vacío o fuera de límites", $errors);
    validarCampo($_POST['new_primer_apellido'], "Primer apellido vacío o fuera de límites", $errors);
    validarCampo($_POST['new_direccion'], "Dirección vacía o fuera de límites", $errors, 2, 100);

    if (!filter_var($_POST['new_correo'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Correo inválido";
    }

    if (empty($_POST['new_documento'])) {
        $errors[] = "Documento vacío";
    }

    if (!isset($_POST['new_telefono']) || $_POST['new_telefono'] === '') {
        $errors[] = "Número de teléfono vacío";
    }

    if (!isset($_POST['new_estado']) || $_POST['new_estado'] === '') {
        $errors[] = "Estado vacío";
    }

    if (!empty($_POST["new_dato_pension"]) && (strlen($_POST['new_dato_pension']) > 50 || strlen($_POST['new_dato_pension']) < 2)) {
        $errors[] = "El dato pensión debe contener entre 2 y 50 caracteres";
    }

    if (empty($errors)) {
        require_once("../../../config/db.php");
        require_once("../../../config/conexion.php");

        // Sanitización y validación de datos
        $new_id_tipo_identificacion = mysqli_real_escape_string($con, strip_tags($_POST["new_id_tipo_identificacion"], ENT_QUOTES));
        $new_documento = mysqli_real_escape_string($con, strip_tags($_POST["new_documento"], ENT_QUOTES));
        $new_primer_nombre = mysqli_real_escape_string($con, strip_tags($_POST["new_primer_nombre"], ENT_QUOTES));
        $new_segundo_nombre = mysqli_real_escape_string($con, strip_tags($_POST["new_segundo_nombre"], ENT_QUOTES));
        $new_primer_apellido = mysqli_real_escape_string($con, strip_tags($_POST["new_primer_apellido"], ENT_QUOTES));
        $new_segundo_apellido = mysqli_real_escape_string($con, strip_tags($_POST["new_segundo_apellido"], ENT_QUOTES));
        $new_estado = intval($_POST['new_estado']);
        $new_id_tipo_empleado = intval($_POST['new_id_tipo_empleado']);
        $new_sexo = mysqli_real_escape_string($con, strip_tags($_POST["new_sexo"], ENT_QUOTES));
        $new_telefono = intval($_POST['new_telefono']);
        $new_direccion = mysqli_real_escape_string($con, strip_tags($_POST["new_direccion"], ENT_QUOTES));
        $new_cumpleanos = $_POST["new_cumpleanos"];
        $new_correo = mysqli_real_escape_string($con, strip_tags($_POST["new_correo"], ENT_QUOTES));
        $new_id_cargo = intval($_POST['new_id_cargo']);

        /* Afiliaciones */
        $new_id_fondo_pension = intval($_POST['new_id_fondo_pension']);
        $new_id_eps = intval($_POST['new_id_eps']);
        $new_id_fondo_cesantias = !empty($_POST['new_id_fondo_cesantias']) ? intval($_POST['new_id_fondo_cesantias']) : null;
        $new_id_arl = !empty($_POST['new_id_arl']) ? intval($_POST['new_id_arl']) : null;
        $new_id_riesgo_arl = !empty($_POST['new_id_riesgo_arl']) ? intval($_POST['new_id_riesgo_arl']) : null;
        $new_id_caja_compensacion = !empty($_POST['new_id_caja_compensacion']) ? intval($_POST['new_id_caja_compensacion']) : null;
        

     /* Opcionales */
        $new_dato_pension = !empty($_POST["new_dato_pension"]) ? mysqli_real_escape_string($con, strip_tags($_POST["new_dato_pension"], ENT_QUOTES)) : null;
        $new_fecha_pension = !empty($_POST["new_fecha_pension"]) ? $_POST["new_fecha_pension"] : null;
        $new_id_Empleado_sustituto = !empty($_POST['new_id_Empleado_sustituto']) ? intval($_POST['new_id_Empleado_sustituto']) : null;
        $new_fecha_sustitucion = !empty($_POST["new_fecha_sustitucion"]) ? $_POST["new_fecha_sustitucion"] : null;
        $new_porcentaje_sustitucion = !empty($_POST['new_porcentaje_sustitucion']) ? floatval($_POST['new_porcentaje_sustitucion']) : null;

        session_start();
        if (!isset($_SESSION['user_name'])) {
            header("Location: login.php");
            exit();
        }

        $user = $_SESSION['user_name'];
        $fechaActual = date('Y-m-d H:i:s');

        // Usar prepared statements para la consulta SQL de inserción
        $stmt = $con->prepare("INSERT INTO gt_empleado (
                id_tipo_identificacion, identificacion, primer_nombre, segundo_nombre, 
                primer_apellido, segundo_apellido, nombre_completo, sexo, telefono, 
                direccion, correo_electronico, id_tipo_empleado, id_cargo, 
                fcha_cumpleano, id_fondo_pension, id_eps, id_fondo_cesantias, 
                id_arl, id_riesgo_arl, id_caja_compensacion, dato_pension, 
                fecha_pension, id_empleado_sustituto, fecha_sustitucion, 
                porcentaje_sustitucion, estado, usuario_registro, fecha_registro
            ) VALUES (?, ?, ?, ?, ?, ?, CONCAT(?, ' ', ?, ' ', ?, ' ', ?), ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
       
       //Construcción de la consulta
        $query = "INSERT INTO gt_empleado (
            id_tipo_identificacion, identificacion, primer_nombre, segundo_nombre, 
            primer_apellido, segundo_apellido, nombre_completo, sexo, telefono, 
            direccion, correo_electronico, id_tipo_empleado, id_cargo, 
            fcha_cumpleano, id_fondo_pension, id_eps, id_fondo_cesantias, 
            id_arl, id_riesgo_arl, id_caja_compensacion, dato_pension, 
            fecha_pension, id_empleado_sustituto, fecha_sustitucion, 
            porcentaje_sustitucion, estado, usuario_registro, fecha_registro
        ) VALUES (
            '{$new_id_tipo_identificacion}', '{$new_documento}', '{$new_primer_nombre}', '{$new_segundo_nombre}', 
            '{$new_primer_apellido}', '{$new_segundo_apellido}', CONCAT('{$new_primer_nombre}', ' ', '{$new_segundo_nombre}', ' ', '{$new_primer_apellido}', ' ', '{$new_segundo_apellido}'), 
            '{$new_sexo}', '{$new_telefono}', '{$new_direccion}', '{$new_correo}', 
            '{$new_id_tipo_empleado}', '{$new_id_cargo}', '{$new_cumpleanos}', 
            '{$new_id_fondo_pension}', '{$new_id_eps}', '{$new_id_fondo_cesantias}', 
            '{$new_id_arl}', '{$new_id_riesgo_arl}', '{$new_id_caja_compensacion}', 
            '{$new_dato_pension}', '{$new_fecha_pension}', '{$new_id_Empleado_sustituto}', 
            '{$new_fecha_sustitucion}', '{$new_porcentaje_sustitucion}', '{$new_estado}', 
            '{$user}', '{$fechaActual}'
        )";

        // Imprimir consulta para depuración
        error_log("Consulta SQL: " . $query);
        
        if (!$stmt) {
            die("Error en la preparación de la consulta: " . $con->error);
        }

        
        $stmt->bind_param(
            'sssssssssssissiisiiiiiissisdiss',
            $new_id_tipo_identificacion, $new_documento, $new_primer_nombre, $new_segundo_nombre,
            $new_primer_apellido, $new_segundo_apellido, $new_primer_nombre, $new_segundo_nombre, 
            $new_primer_apellido, $new_segundo_apellido, $new_sexo, $new_telefono, $new_direccion, 
            $new_correo, $new_id_tipo_empleado, $new_id_cargo, $new_cumpleanos, $new_id_fondo_pension, 
            $new_id_eps, $new_id_fondo_cesantias, $new_id_arl, $new_id_riesgo_arl, $new_id_caja_compensacion, 
            $new_dato_pension, $new_fecha_pension, $new_id_Empleado_sustituto, $new_fecha_sustitucion, 
            $new_porcentaje_sustitucion, $new_estado, $user, $fechaActual
        );

        if ($stmt->execute()) {
            $messages[] = "El empleado ha sido agregado exitosamente.";
        } else {
            error_log("Error al insertar empleado: " . $stmt->error); // Guardar en log
            $errors[] = "Error en la inserción. Por favor, inténtelo de nuevo más tarde.";
        }

        $stmt->close();
    }
} else {
    $errors[] = "sin datos post";
}

// Manejo de errores
if (!empty($errors)) {
    ?>
    <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php foreach ($errors as $error) {
            echo $error . "<br>";
        } ?>
    </div>
    <?php
}

// Manejo de mensajes
if (!empty($messages)) {
    ?>
    <div class="alert alert-success" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php foreach ($messages as $message) {
            echo $message . "<br>";
        } ?>
        <script>
            $(document).ready(function() {
                setTimeout(function() {
                    $("#myModal").modal("hide"); // Cerrar la modal
                    location.reload(); // Refresca la página
                }, 1000); // Tiempo antes de mostrar el mensaje
            });
        </script>
    </div>
    <?php
}
?>
