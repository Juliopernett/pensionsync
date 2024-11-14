<?php
include('../../../is_logged.php');

// Verificar la versión de PHP
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, PHP version must be 5.3.7 or higher.");
} elseif (version_compare(PHP_VERSION, '5.5.0', '<')) {
    require_once("../../../lib/password_compatibility_library.php");
}

$errors = [];
$messages = [];

// Función para validar campos
function validarCampo($campo, $mensaje, &$errores, $min = 2, $max = 255) {
    if (empty($campo) || strlen($campo) < $min || strlen($campo) > $max) {
        $errores[] = $mensaje;
    }
}

// Procesar solicitud POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validar los campos de entrada
    $requiredFields = [
        'mod_id_empleado' => "Empleado vacío",
        'mod_id_concepto' => "Concepto vacío",
        'mod_valor' => "Valor vacío"
        /*'mod_estado' => "Estado vacío",*/
    ];

    foreach ($requiredFields as $field => $errorMessage) {
        if (empty($_POST[$field])) {
            $errors[] = $errorMessage;
        }
    }

    // Validar el valor
    if (empty($_POST['mod_valor']) || !is_numeric($_POST['mod_valor']) || $_POST['mod_valor'] == 0) {
        $errors[] = "Valor no puede ser 0 o no numérico";
    }

    // Si no hay errores, proceder con la actualización
    if (empty($errors)) {
        require_once("../../../config/db.php");
        require_once("../../../config/conexion.php");

        // Sanitización y validación de datos
        $mod_id_empleado = intval($_POST['mod_id_empleado']);
        $mod_id_concepto = intval($_POST['mod_id_concepto']);
        //$mod_resolucion_id = intval($_POST['mod_resolucion_id']);
        $mod_resolucion_id = !empty($_POST['mod_resolucion_id']) ? intval($_POST['mod_resolucion_id']) : null;
        $mod_valor = floatval($_POST['mod_valor']);
        $mod_estado = intval($_POST['mod_estado']);

        session_start();
        if (!isset($_SESSION['user_name'])) {
            header("Location: login.php");
            exit();
        }

                $user = $_SESSION['user_name'];
                $novedad_id = intval($_POST['mod_id']);
                $fechaActual = date('Y-m-d H:i:s');
                
               /* $sql = "UPDATE gt_novedades SET 
                id_empleado='$mod_id_empleado', 
                id_concepto='$mod_id_concepto',
                Valor='$mod_valor',
                estado='$mod_estado',
                usuario_registro='$user',
                fecha_registro='$fechaActual',
                resolucion_id='$mod_resolucion_id' 
                WHERE id='$novedad_id'";

                 echo $sql; // Imprime la consulta para depuración*/
        // Usar prepared statements para la consulta SQL
         $stmt = $con->prepare("UPDATE gt_novedades SET 
                                                id_empleado=?, 
                                                id_concepto=?,
                                                Valor=?,
                                                estado=?,
                                                usuario_registro=?,
                                                fecha_registro=?,
                                                resolucion_id=? 
                                                WHERE id=?");

        if ($stmt === false) {
            error_log("Error al preparar la consulta: " . $con->error); // Guardar en log
            $errors[] = "Error al preparar la consulta SQL.";
        } else {
            // Bind de parámetros
            $stmt->bind_param(
                'iidissii',
                $mod_id_empleado, $mod_id_concepto, $mod_valor, $mod_estado, $user, $fechaActual, $mod_resolucion_id, $novedad_id
            );

            // Ejecutar la consulta
            if ($stmt->execute()) {
                $messages[] = "El concepto ha sido actualizado exitosamente.";
            } else {
                error_log("Error al actualizar concepto: " . $stmt->error); // Guardar en log
                $errors[] = "Error en la actualización. Por favor, inténtelo de nuevo más tarde.";
            }

            $stmt->close();
        }
    }
} else {
    $errors[] = "No se recibieron datos POST.";
}
// Mostrar errores
if (!empty($errors)) {
    ?>
    <div class="alert alert-danger" role="alert" id="error-message">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php foreach ($errors as $error) {
            echo htmlspecialchars($error) . "<br>";
        } ?>
    </div>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $("#error-message").fadeOut(); // Cerrar el mensaje de error después de 5 segundos
            
            }, 2000); // 5000 ms = 5 segundos
        });
    </script>
    <?php
}

// Mostrar mensajes de éxito
if (!empty($messages)) {
    ?>
    <div class="alert alert-success" role="alert" id="success-message">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php foreach ($messages as $message) {
            echo htmlspecialchars($message) . "<br>";
        } ?>
        <script>
            $(document).ready(function() {
                setTimeout(function() {
                    $("#success-message").fadeOut(); // Cerrar el mensaje de éxito después de 5 segundos
                    $("#myModal2").modal("hide"); // Cerrar la modal
                }, 2000); // 5000 ms = 5 segundos
            });
        </script>
    </div>
    <?php
}
?>
