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

        validarCampo($_POST['mod_primer_nombre'], "Primer nombre vacío o fuera de límites", $errors);
        validarCampo($_POST['mod_primer_apellido'], "Primer apellido vacío o fuera de límites", $errors);
        validarCampo($_POST['mod_direccion'], "Dirección vacía o fuera de límites", $errors, 2, 100);
        
        if (!filter_var($_POST['mod_correo'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Correo inválido";
        }

        if (empty($_POST['mod_documento'])) {
            $errors[] = "Documento vacío";
        }

        if (!isset($_POST['mod_telefono']) || $_POST['mod_telefono'] === '') {
            $errors[] = "Número de teléfono vacío";
        }

        if (!isset($_POST['mod_estado']) || $_POST['mod_estado'] === '') {
            $errors[] = "Estado vacío";
        }

        if (!empty($_POST["mod_dato_pension"]) && (strlen($_POST['mod_dato_pension']) > 50 || strlen($_POST['mod_dato_pension']) < 2)) {
            $errors[] = "El dato pensión debe contener entre 2 y 50 caracteres";
        }

        if (empty($errors)) {
            require_once("../../../config/db.php");
            require_once("../../../config/conexion.php");

            // Sanitización y validación de datos
            $mod_id_tipo_identificacion = mysqli_real_escape_string($con, strip_tags($_POST["mod_id_tipo_identificacion"], ENT_QUOTES));
            $mod_documento = mysqli_real_escape_string($con, strip_tags($_POST["mod_documento"], ENT_QUOTES));
            $mod_primer_nombre = mysqli_real_escape_string($con, strip_tags($_POST["mod_primer_nombre"], ENT_QUOTES));
            $mod_segundo_nombre = mysqli_real_escape_string($con, strip_tags($_POST["mod_segundo_nombre"], ENT_QUOTES));
            $mod_primer_apellido = mysqli_real_escape_string($con, strip_tags($_POST["mod_primer_apellido"], ENT_QUOTES));
            $mod_segundo_apellido = mysqli_real_escape_string($con, strip_tags($_POST["mod_segundo_apellido"], ENT_QUOTES));
            $mod_estado = intval($_POST['mod_estado']);
            $mod_id_tipo_empleado = intval($_POST['mod_id_tipo_empleado']);
            $mod_sexo = mysqli_real_escape_string($con, strip_tags($_POST["mod_sexo"], ENT_QUOTES));
            $mod_telefono = intval($_POST['mod_telefono']);
            $mod_direccion = mysqli_real_escape_string($con, strip_tags($_POST["mod_direccion"], ENT_QUOTES));
            $mod_cumpleanos = $_POST["mod_cumpleanos"];
            $mod_correo = mysqli_real_escape_string($con, strip_tags($_POST["mod_correo"], ENT_QUOTES));
            $mod_id_cargo = intval($_POST['mod_id_cargo']);

            /* Afiliaciones */
            $mod_id_fondo_pension = intval($_POST['mod_id_fondo_pension']);
            $mod_id_eps = intval($_POST['mod_id_eps']);
            $mod_id_fondo_cesantias = !empty($_POST['mod_id_fondo_cesantias']) ? intval($_POST['mod_id_fondo_cesantias']) : null;
            $mod_id_arl = !empty($_POST['mod_id_arl']) ? intval($_POST['mod_id_arl']) : null;
            $mod_id_riesgo_arl = !empty($_POST['mod_id_riesgo_arl']) ? intval($_POST['mod_id_riesgo_arl']) : null;
            $mod_id_caja_compensacion = !empty($_POST['mod_id_caja_compensacion']) ? intval($_POST['mod_id_caja_compensacion']) : null;
            
            /* Opcionales */
            $mod_dato_pension = !empty($_POST["mod_dato_pension"]) ? mysqli_real_escape_string($con, strip_tags($_POST["mod_dato_pension"], ENT_QUOTES)) : null;
            $mod_fecha_pension = !empty($_POST["mod_fecha_pension"]) ? $_POST["mod_fecha_pension"] : null;
            $mod_id_Empleado_sustituto = !empty($_POST['mod_id_Empleado_sustituto']) ? intval($_POST['mod_id_Empleado_sustituto']) : null;
            $mod_fecha_sustitucion = !empty($_POST["mod_fecha_sustitucion"]) ? $_POST["mod_fecha_sustitucion"] : null;
            $mod_porcentaje_sustitucion = !empty($_POST['mod_porcentaje_sustitucion']) ? floatval($_POST['mod_porcentaje_sustitucion']) : null;

            /*$mod_dato_pension = ($_POST["mod_dato_pension"] === '') ? null : mysqli_real_escape_string($con, strip_tags($_POST["mod_dato_pension"], ENT_QUOTES));
            $mod_fecha_sustitucion = ($_POST["mod_fecha_sustitucion"] === '') ? null : $_POST["mod_fecha_sustitucion"];
            $mod_porcentaje_sustitucion = ($_POST['mod_porcentaje_sustitucion'] === '') ? null : floatval($_POST['mod_porcentaje_sustitucion']);
            $mod_id_Empleado_sustituto = ($_POST['mod_id_Empleado_sustituto'] === '') ? null : intval($_POST['mod_id_Empleado_sustituto']);
            */
            session_start();
            if (!isset($_SESSION['user_name'])) {
                header("Location: login.php");
                exit();
            }

            $user = $_SESSION['user_name'];
            $empleado_id = intval($_POST['mod_id']);
            $fechaActual = date('Y-m-d H:i:s');

            /*Construcción de la consulta SQL
            $sql = "UPDATE gt_empleado SET 
            id_tipo_identificacion = '$mod_id_tipo_identificacion', 
            identificacion = '$mod_documento', 
            primer_nombre = '$mod_primer_nombre', 
            segundo_nombre = '$mod_segundo_nombre', 
            primer_apellido = '$mod_primer_apellido', 
            segundo_apellido = '$mod_segundo_apellido', 
            nombre_completo = CONCAT('$mod_primer_nombre', ' ', '$mod_segundo_nombre', ' ', '$mod_primer_apellido', ' ', '$mod_segundo_apellido'),
            sexo = '$mod_sexo', 
            telefono = '$mod_telefono', 
            direccion = '$mod_direccion', 
            correo_electronico = '$mod_correo', 
            id_tipo_empleado = '$mod_id_tipo_empleado', 
            id_cargo = '$mod_id_cargo', 
            fcha_cumpleano = '$mod_cumpleanos', 
            id_fondo_pension = '$mod_id_fondo_pension', 
            id_eps = '$mod_id_eps', 
            dato_pension = '$mod_dato_pension', 
            fecha_pension = '$mod_fecha_pension', 
            id_empleado_sustituto = '$mod_id_Empleado_sustituto', 
            fecha_sustitucion = '$mod_fecha_sustitucion', 
            porcentaje_sustitucion = '$mod_porcentaje_sustitucion', 
            estado = '$mod_estado', 
            usuario_registro = '$user', 
            fecha_registro = '$fechaActual' 
            WHERE id = '$empleado_id'";

            // Registrar la consulta SQL en el log para depuración
            error_log("Consulta SQL: " . $sql);

            // Puedes descomentar esta línea para imprimir directamente en la página (solo para depuración)
            echo "Consulta SQL: " . $sql;*/

            $stmt = $con->prepare("UPDATE gt_empleado SET 
                id_tipo_identificacion=?, identificacion=?, primer_nombre=?, segundo_nombre=?, primer_apellido=?, 
                segundo_apellido=?, nombre_completo=CONCAT(?, ' ', ?, ' ', ?, ' ', ?), 
                sexo=?, telefono=?, direccion=?, correo_electronico=?, id_tipo_empleado=?, 
                id_cargo=?, fcha_cumpleano=?, id_fondo_pension=?, id_eps=?, dato_pension=?, 
                fecha_pension=?, id_empleado_sustituto=?, fecha_sustitucion=?, porcentaje_sustitucion=?, estado=?, 
                usuario_registro=?, fecha_registro=? 
                WHERE id=?");

            $stmt->bind_param(
                'sssssssssssissiisiissisdissi',
            $mod_id_tipo_identificacion, $mod_documento, $mod_primer_nombre, $mod_segundo_nombre, $mod_primer_apellido, 
                  $mod_segundo_apellido, $mod_primer_nombre, $mod_segundo_nombre, $mod_primer_apellido, $mod_segundo_apellido, 
                  $mod_sexo, $mod_telefono, $mod_direccion, $mod_correo, $mod_id_tipo_empleado, 
                  $mod_id_cargo, $mod_cumpleanos, $mod_id_fondo_pension, $mod_id_eps, $mod_dato_pension, 
                  $mod_fecha_pension, $mod_id_Empleado_sustituto, $mod_fecha_sustitucion, $mod_porcentaje_sustitucion, $mod_estado,
                  $user, $fechaActual,
                  $empleado_id
            );    

            if ($stmt->execute()) {
                $messages[] = "El empleado ha sido actualizado exitosamente.";
            } else {
                error_log("Error al actualizar empleado: " . $stmt->error); // Guardar en log
                $errors[] = "Error en la actualización. Por favor, inténtelo de nuevo más tarde.";
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
                    $("#myModal2").modal("hide"); // Cerrar la modal
                    location.reload(); // Refresca la página
                }, 1000); // Tiempo antes de mostrar el mensaje
            });
        </script>
    </div>
    <?php
}
?>
