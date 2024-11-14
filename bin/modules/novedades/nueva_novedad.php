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
        'new_id_empleado' => "Empleado vacío",
        'new_id_concepto' => "Concepto vacío",
        'new_id_periodo' => "Periodo vacío",
        'new_valor' => "Valor vacío"
        /*'new_estado' => "Estado vacío",*/
    ];

    foreach ($requiredFields as $field => $errorMessage) {
        if (empty($_POST[$field])) {
            $errors[] = $errorMessage;
        }
    }

    // Validar el valor
    if (empty($_POST['new_valor']) || !is_numeric($_POST['new_valor']) || $_POST['new_valor'] == 0) {
        $errors[] = "Valor no puede ser 0 o no numérico";
    }

    // Si no hay errores, proceder con la inserción
    if (empty($errors)) {
        require_once("../../../config/db.php");
        require_once("../../../config/conexion.php");

        // Sanitización y validación de datos
        $new_id_empleado = intval($_POST['new_id_empleado']);
        $new_id_concepto = intval($_POST['new_id_concepto']);
        $new_resolucion_id = !empty($_POST['new_resolucion_id']) ? intval($_POST['new_resolucion_id']) : null;
        $new_valor = floatval($_POST['new_valor']);
        $new_estado = intval($_POST['new_estado']);
        $new_id_periodo = intval($_POST['new_id_periodo']);

        session_start();
        if (!isset($_SESSION['user_name'])) {
            header("Location: login.php");
            exit();
        }

        $user = $_SESSION['user_name'];
        $fechaActual = date('Y-m-d H:i:s');
        
        // Usar prepared statements para la consulta SQL
        $stmt = $con->prepare("INSERT INTO 
                                            gt_novedades (id_empleado, 
                                                          id_concepto, 
                                                          id_periodo,
                                                          Valor, 
                                                          estado, 
                                                          usuario_registro, 
                                                          fecha_registro, 
                                                          resolucion_id) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        if ($stmt === false) {
            error_log("Error al preparar la consulta: " . $con->error); // Guardar en log
            $errors[] = "Error al preparar la consulta SQL.";
        } else {
            // Bind de parámetros
            $stmt->bind_param(
                'iiidissi',
                $new_id_empleado, $new_id_concepto, $new_id_periodo,
                      $new_valor, $new_estado, $user, 
                      $fechaActual, $new_resolucion_id
            );

            // Ejecutar la consulta
            if ($stmt->execute()) {
                $messages[] = "La novedad ha sido insertada exitosamente.";
            } else {
                error_log("Error al insertar concepto: " . $stmt->error); // Guardar en log
                $errors[] = "Error al insertar los datos. Por favor, inténtelo de nuevo más tarde.";
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
            }, 2000); // 2000 ms = 2 segundos
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
                    $("#myModal").modal("hide"); // Cerrar la modal
                }, 2000); // 2000 ms = 2 segundos
            });
        </script>
    </div>
    <?php
}
?>
