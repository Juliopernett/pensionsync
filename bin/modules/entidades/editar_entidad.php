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
    //var_dump($_POST); // Verificar datos recibidos
	//var_dump($_POST['mod_dv']); // Ver el valor recibido

    if (!isset($_POST['mod_dv']) || $_POST['mod_dv'] === '') {
        $errors[] = "Dígito de verificación vacío";
    }elseif (!isset($_POST['mod_nit']) || $_POST['mod_nit'] === '') {
		$errors[] = "Nit vacío";
	}elseif (!isset($_POST['mod_tipo_entidad']) || $_POST['mod_tipo_entidad'] === '') {
		$errors[] = "Tipo de entidad vacío";
	}elseif (empty($_POST['mod_nombre'])) {
		$errors[] = "Nombre vacío";
	}elseif (!isset($_POST['mod_estado']) || $_POST['mod_estado'] === '') {
		$errors[] = "Estado vacío";
	}elseif (strlen($_POST['mod_nombre']) > 30 || strlen($_POST['mod_nombre']) < 2) {
		$errors[] = "El nombre de la entidad debe contener entre 2 y 30 caracteres";
	}else {
		require_once("../../../config/db.php");
		require_once("../../../config/conexion.php");

		$nombre = mysqli_real_escape_string($con, strip_tags($_POST["mod_nombre"], ENT_QUOTES));
		$estado = mysqli_real_escape_string($con, strip_tags($_POST["mod_estado"], ENT_QUOTES));
		$nit = mysqli_real_escape_string($con, strip_tags($_POST["mod_nit"], ENT_QUOTES));
		$dv = mysqli_real_escape_string($con, strip_tags($_POST["mod_dv"], ENT_QUOTES));
		$tipo_entidad = mysqli_real_escape_string($con, strip_tags($_POST["mod_tipo_entidad"], ENT_QUOTES));

		session_start();
		$user = $_SESSION['user_name'];
		$entidad_id = intval($_POST['mod_id']);
		$fechaActual = date('Y-m-d H:i:s');

		// Consulta SQL para actualizar
		$sql = "UPDATE pm_entidades SET 
				nombre='$nombre', 
				estado='$estado', 
				usuario_registro='$user', 
				fecha_registro='$fechaActual', 
				nit='$nit', 
				dv='$dv', 
				tipo_entidad='$tipo_entidad' 
			WHERE id='$entidad_id'";

		$query_update = mysqli_query($con, $sql);
		if ($query_update) {
		$messages[] = "La entidad ha sido actualizada.";
		} else {
		$errors[] = "La actualización falló. Por favor, regrese y vuelva a intentarlo.". mysqli_error($con);
		}
    }
}

// Manejo de errores
if (!empty($errors)) {
    ?>
    <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong></strong> 
        <?php
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
        ?>
    </div>
    <?php
}

// Manejo de mensajes
if (!empty($messages)) {
    ?>
    <div class="alert alert-success" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong></strong>
        <?php
        foreach ($messages as $message) {
            echo $message . "<br>";
        }
        ?>
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
