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

        validarCampo($_POST['mod_descripcion'], "Descripción vacía o fuera de límites permitidos", $errors);
        validarCampo($_POST['mod_codigo'], "código vacío o fuera de límites permitidos", $errors, 2, 10);
        
        if (empty($_POST['mod_tipo_movimiento']) || $_POST['mod_tipo_movimiento'] === '') {
            $errors[] = "Tipo movimiento vacío";
        }

        if (!isset($_POST['mod_tipo_concepto']) || $_POST['mod_tipo_concepto'] === '') {
            $errors[] = "Tipo concepto vacío";
        }

        if (!isset($_POST['mod_estado']) || $_POST['mod_estado'] === '') {
            $errors[] = "Estado vacío";
        }

        if (empty($errors)) {
            require_once("../../../config/db.php");
            require_once("../../../config/conexion.php");

            // Sanitización y validación de datos
            $mod_codigo = mysqli_real_escape_string($con, strip_tags($_POST["mod_codigo"], ENT_QUOTES));
            $mod_descripcion = mysqli_real_escape_string($con, strip_tags($_POST["mod_descripcion"], ENT_QUOTES));
            $mod_tipo_movimiento = intval($_POST['mod_tipo_movimiento']);
            $mod_tipo_concepto = intval($_POST['mod_tipo_concepto']);
            $mod_estado = intval($_POST['mod_estado']);


            session_start();
            if (!isset($_SESSION['user_name'])) {
                header("Location: login.php");
                exit();
            }

            $user = $_SESSION['user_name'];
            $concepto_id = intval($_POST['mod_id']);
            $fechaActual = date('Y-m-d H:i:s');

            // Usar prepared statements para la consulta SQL
            $stmt = $con->prepare("UPDATE pm_conceptos  SET 
                                            Codigo=?,
                                            descripcion=?,
                                            Tipo_Movimiento=?,
                                            Tipo_concepto=?,
                                            estado=?,
                                            usuario_registro=?,
                                            fecha_registro=?
                                            WHERE id = ?");

            $stmt->bind_param(
                'ssiiissi',
                $mod_codigo, $mod_descripcion, $mod_tipo_movimiento, $mod_tipo_concepto, $mod_estado,
                    $user, $fechaActual, $concepto_id
            );

            if ($stmt->execute()) {
                $messages[] = "El concepto ha sido actualizado exitosamente.";
            } else {
                error_log("Error al actualizar concepto: " . $stmt->error); // Guardar en log
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
