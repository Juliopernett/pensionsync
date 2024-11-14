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

        validarCampo($_POST['mod_numero'], "Numero de resolución vacía o fuera de límites permitidos", $errors, 2, 10);
        validarCampo($_POST['mod_detalle'], "Detalle de resolución vacío o fuera de límites permitidos", $errors);
        
        if (empty($_POST['mod_fecha_resolucion']) || $_POST['mod_fecha_resolucion'] === '') {
            $errors[] = "Fecha resolución vacía";
        }

        if (!isset($_POST['mod_estado']) || $_POST['mod_estado'] === '') {
            $errors[] = "Estado vacío";
        }

        if (empty($errors)) {
            require_once("../../../config/db.php");
            require_once("../../../config/conexion.php");

            // Sanitización y validación de datos
            $mod_numero = mysqli_real_escape_string($con, strip_tags($_POST["mod_numero"], ENT_QUOTES));
            $mod_detalle = mysqli_real_escape_string($con, strip_tags($_POST["mod_detalle"], ENT_QUOTES));
            $mod_fecha_resolucion = mysqli_real_escape_string($con, strip_tags($_POST["mod_fecha_resolucion"], ENT_QUOTES));
            $mod_estado = intval($_POST['mod_estado']);

            session_start();
            if (!isset($_SESSION['user_name'])) {
                header("Location: login.php");
                exit();
            }

            $user = $_SESSION['user_name'];
            $resolucion_id = intval($_POST['mod_id']);
            $fechaActual = date('Y-m-d H:i:s');

            // Usar prepared statements para la consulta SQL
            $stmt = $con->prepare("UPDATE gt_resoluciones  SET 
                                            numero =?, 
                                            detalle =?, 
                                            fecha_resolucion =?, 
                                            fecha_registro =?, 
                                            usuario_registro =?, 
                                            estado =?
                                            WHERE id = ?");

            $stmt->bind_param(
                'sssssii',
                $mod_numero, 
                      $mod_detalle, 
                      $mod_fecha_resolucion,
                      $fechaActual,
                      $user,
                      $mod_estado,
                      $resolucion_id);

            if ($stmt->execute()) {
                $messages[] = "La resolución ha sido actualizado exitosamente.";
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
