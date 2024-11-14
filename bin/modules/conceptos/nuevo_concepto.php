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

    function validarCampo($campo, $mensaje, &$errores, $min = 2, $max = 255) {
        if (empty($campo) || strlen($campo) < $min || strlen($campo) > $max) {
            $errores[] = $mensaje;
        }
    }

    validarCampo($_POST['new_descripcion'], "Descripción vacía o fuera de límites permitidos", $errors);
    validarCampo($_POST['new_codigo'], "Código vacío o fuera de límites permitidos", $errors, 2, 10);
    
    if (empty($_POST['new_tipo_movimiento']) || $_POST['new_tipo_movimiento'] === '') {
        $errors[] = "Tipo movimiento vacío";
    }

    if (!isset($_POST['new_tipo_concepto']) || $_POST['new_tipo_concepto'] === '') {
        $errors[] = "Tipo concepto vacío";
    }

    if (!isset($_POST['new_estado']) || $_POST['new_estado'] === '') {
        $errors[] = "Estado vacío";
    }

    if (empty($errors)) {
        require_once("../../../config/db.php");
        require_once("../../../config/conexion.php");

        // Sanitización y validación de datos
        $new_codigo = mysqli_real_escape_string($con, strip_tags($_POST["new_codigo"], ENT_QUOTES));
        $new_descripcion = mysqli_real_escape_string($con, strip_tags($_POST["new_descripcion"], ENT_QUOTES));
        $new_tipo_movimiento = intval($_POST['new_tipo_movimiento']);
        $new_tipo_concepto = intval($_POST['new_tipo_concepto']);
        $new_estado = intval($_POST['new_estado']);

        session_start();
        if (!isset($_SESSION['user_name'])) {
            header("Location: login.php");
            exit();
        }

        $user = $_SESSION['user_name'];
        $fechaActual = date('Y-m-d H:i:s');

        // Usar prepared statements para la consulta SQL
        $stmt = $con->prepare("INSERT INTO pm_conceptos (Codigo, descripcion, Tipo_Movimiento, Tipo_concepto, estado, usuario_registro, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param(
            'ssiiiss',
            $new_codigo, $new_descripcion, $new_tipo_movimiento, $new_tipo_concepto, $new_estado,
            $user, $fechaActual
        );

        if ($stmt->execute()) {
            $messages[] = "El concepto ha sido agregado exitosamente.";
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
                    $("#myModalInsert").modal("hide"); // Cerrar la modal
                    location.reload(); // Refresca la página
                }, 1000); // Tiempo antes de mostrar el mensaje
            });
        </script>
    </div>
    <?php
}
?>
