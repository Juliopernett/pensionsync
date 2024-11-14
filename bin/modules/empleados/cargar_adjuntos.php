<?php
include('../../../is_logged.php');

$errors = [];
$messages = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Función para validar campos
    function validarCampo($campo, $mensaje, &$errores, $min = 2, $max = 50) {
        if (empty($campo) || strlen($campo) < $min || strlen($campo) > $max) {
            $errores[] = $mensaje;
        }
    }

    // Validación de campos
    if (!isset($_POST['car_id']) || empty($_POST['car_id'])) {
        $errors[] = "No se recibió el ID del empleado.";
    }
    if (!isset($_FILES['archivo']) || $_FILES['archivo']['error'] != 0) {
        $errors[] = "Hubo un error al subir el archivo.";
    }

    // Validación de archivo (tipo y tamaño)
    $archivo = $_FILES['archivo'];
    $tipos_permitidos = ['image/jpeg', 'image/png', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    if (!in_array($archivo['type'], $tipos_permitidos)) {
        $errors[] = "Tipo de archivo no permitido.";
    }
    $tamano_maximo = 5 * 1024 * 1024; // 5MB
    if ($archivo['size'] > $tamano_maximo) {
        $errors[] = "El archivo es demasiado grande. El tamaño máximo permitido es 5MB.";
    }

    if (empty($errors)) {
        require_once("../../../config/db.php");
        require_once("../../../config/conexion.php");

        // Sanitización de los datos recibidos
        $car_id = intval($_POST['car_id']);  // ID del empleado
        $titulo = isset($_POST['titulo']) ? mysqli_real_escape_string($con, strip_tags($_POST['titulo'], ENT_QUOTES)) : null;
        //$descripcion = isset($_POST['car_descripcion']) ? mysqli_real_escape_string($con, strip_tags($_POST['car_descripcion'], ENT_QUOTES)) : null;
        $usuario = $_SESSION['user_name'];  // Usuario que realiza la carga
        $fechaActual = date('Y-m-d H:i:s');
        $descripcion = $archivo['name'];
        // Generar un nombre único para el archivo
        $nombre_archivo = uniqid('doc_', true) . '.' . pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $directorio_destino = 'adjuntos/';  // Directorio donde se guardará el archivo (ruta absoluta)

        // Mover el archivo a su destino
        if (move_uploaded_file($archivo['tmp_name'], $directorio_destino . $nombre_archivo)) {
            // Insertar datos en la base de datos
            $query = "INSERT INTO gt_documentos (tipo, descripcion, tamanio, ruta, nombre_archivo, usuario, id_empleado)
                      VALUES (?, ?, ?, ?, ?, ?, ?)";

            // Preparar la consulta y ejecutar
            if ($stmt = mysqli_prepare($con, $query)) {
                $tamanio = $archivo['size'];
                $tipo = $archivo['type'];  // Tipo MIME del archivo
                $ruta = $directorio_destino . $nombre_archivo;  // Ruta completa del archivo

                // Vincular los parámetros y ejecutar
                mysqli_stmt_bind_param($stmt, "ssisssi", $tipo, $descripcion, $tamanio, $ruta, $nombre_archivo, $usuario, $car_id);

                if (mysqli_stmt_execute($stmt)) {
                    $messages[] = "El archivo se ha cargado correctamente.";
                } else {
                    // Si falla la ejecución, mostrar el error de MySQL
                    $errors[] = "Error al guardar los datos en la base de datos: " . mysqli_error($con);
                }

                mysqli_stmt_close($stmt);
            } else {
                // Si falla la preparación de la consulta, mostrar el error
                $errors[] = "Error al preparar la consulta SQL: " . mysqli_error($con);
            }
        } else {
            $errors[] = "Error al mover el archivo al servidor.";
        }
    }
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
                    $("#myModalCargaDoc").modal("hide"); // Cerrar la modal
                    location.reload(); // Refresca la página
                }, 1000); // Tiempo antes de mostrar el mensaje
            });
        </script>
    </div>
    <?php
}
?>
