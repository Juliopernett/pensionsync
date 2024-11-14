<?php

include '../../../core.php';
require_once("../../../login/Login.php");
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    
    require_once("../../../lib/password_compatibility_library.php");
}	


$errors = [];
$messages = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //var_dump($_POST); // Verificar datos recibidos
	//var_dump($_POST['mod_dv']); // Ver el valor recibido
    if (!isset($_POST['new_dv']) || $_POST['new_dv'] === '') {
        $errors[] = "Dígito de verificación vacío";
    }elseif (!isset($_POST['new_nit']) || $_POST['new_nit'] === '') {
		$errors[] = "Nit vacío";
	}elseif (!isset($_POST['new_tipo_entidad']) || $_POST['new_tipo_entidad'] === '') {
		$errors[] = "Tipo de entidad vacío";
	}elseif (empty($_POST['new_nombre'])) {
		$errors[] = "Nombre vacío";
	}elseif (strlen($_POST['new_nombre']) > 30 || strlen($_POST['new_nombre']) < 2) {
		$errors[] = "El nombre de la entidad debe contener entre 2 y 30 caracteres";
	}else {
		require_once("../../../config/db.php");
		require_once("../../../config/conexion.php");

		$nombre = mysqli_real_escape_string($con, strip_tags($_POST["new_nombre"], ENT_QUOTES));
		$estado = 1;
		$nit = mysqli_real_escape_string($con, strip_tags($_POST["new_nit"], ENT_QUOTES));
		$dv = mysqli_real_escape_string($con, strip_tags($_POST["new_dv"], ENT_QUOTES));
		$tipo_entidad = intval(strip_tags($_POST["new_tipo_entidad"], ENT_QUOTES));
        session_start();
        $new_user = ($_SESSION['user_name']);

        $sql = "SELECT * FROM pm_entidades WHERE nit = '".$nit."' AND tipo_entidad = ".$tipo_entidad;
                $query_check_user_name = mysqli_query($con,$sql);
				$query_check_user=mysqli_num_rows($query_check_user_name);
                if ($query_check_user == 1) {
                    $errors[] = "Lo sentimos , el nit ingresado en conjunto con el tipo de entidad ya existe en la base de datos.";
                } else {
                    $sql = "INSERT INTO pm_entidades (nombre, usuario_registro, estado, tipo_entidad, nit, dv)
                            VALUES('".$nombre."','".$new_user."','".$estado."','".$tipo_entidad."','".$nit."','".$dv."');";
                    $query_new_entidad_insert = mysqli_query($con,$sql);
                    //echo ($sql);
                    if ($query_new_entidad_insert) { 
                        $messages[] = "El cargo ha sido creada con éxito.";  

                    } else {
                        $errors[] = "Lo sentimos , el registro falló. Por favor, regrese y vuelva a intentarlo.";
                    }
                }
		
    }
}
		
//manejo de errores
if (!empty($errors)) {
    ?>
    <div class="alert alert-danger" role="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>¡Error!</strong> 
            <?php
                foreach ($errors as $error) {
                        echo $error;
                    }
                ?>
    </div>
    <?php
    }
if (!empty($messages)){
        
        ?>
        <div class="alert alert-success" role="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>¡Bien hecho!</strong>
                <?php
                    foreach ($messages as $message) {
                            echo $message;
                        }
                        echo '<script>
                                    $(document).ready(function() {
                                        
                                        setTimeout(function() {
                                            $("#myModal").modal("hide"); // Cerrar la modal
                                            location.reload(); // Refresca la página
                                        }, 1000); // Tiempo antes de mostrar el mensaje
                                    });
                                </script>';    
                        
                        //header("location: bin/modules/cargos/cargos.php");    
                    ?>
                    
        </div>
        <script>

        <?php
    }

?>