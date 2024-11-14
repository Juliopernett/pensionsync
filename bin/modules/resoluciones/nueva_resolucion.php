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

    function validarCampo($campo, $mensaje, &$errores, $min = 2, $max = 1000) {
        if (empty($campo) || strlen($campo) < $min || strlen($campo) > $max) {
            $errores[] = $mensaje;
        }
    }

    validarCampo($_POST['new_numero'], "Numero de resolución vacío o fuera de límites permitidos", $errors, 2, 10);
    validarCampo($_POST['new_detalle'], "Detalle de resolución vacío o fuera de límites permitidos", $errors);
    
    if (empty($_POST['new_fecha_resolucion']) || $_POST['new_fecha_resolucion'] === '') {
        $errors[] = "Fecha resolución vacía";
    }

    if (!isset($_POST['new_estado']) || $_POST['new_estado'] === '') {
        $errors[] = "Estado vacío";
    }

    if (empty($errors)) {
        require_once("../../../config/db.php");
        require_once("../../../config/conexion.php");

        // Sanitización y validación de datos
        $new_numero = mysqli_real_escape_string($con, strip_tags($_POST["new_numero"], ENT_QUOTES));
        $new_detalle = mysqli_real_escape_string($con, strip_tags($_POST["new_detalle"], ENT_QUOTES));
        $new_fecha_resolucion = mysqli_real_escape_string($con, strip_tags($_POST["new_fecha_resolucion"], ENT_QUOTES));
        $new_estado = intval($_POST['new_estado']);

        session_start();
        if (!isset($_SESSION['user_name'])) {
            header("Location: login.php");
            exit();
        }

        $user = $_SESSION['user_name'];
        $fechaActual = date('Y-m-d H:i:s');

        // Usar prepared statements para la consulta SQL de inserción
        $stmt = $con->prepare("INSERT INTO gt_resoluciones (numero, detalle, fecha_resolucion, fecha_registro, usuario_registro, estado) 
                               VALUES (?, ?, ?, ?, ?, ?)");

        $stmt->bind_param(
            'sssssi',
            $new_numero, 
            $new_detalle, 
            $new_fecha_resolucion,
            $fechaActual,
            $user,
            $new_estado
        );

        if ($stmt->execute()) {
            $messages[] = "La resolución ha sido registrada exitosamente.";
        } else {
            error_log("Error al insertar concepto: " . $stmt->error); // Guardar en log
            $errors[] = "Error en la inserción. Por favor, inténtelo de nuevo más tarde.";
        }

        $stmt->close();
    }
} else {
    $errors[] = "Sin datos POST";
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
