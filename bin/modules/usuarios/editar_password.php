<?php
include('../../../is_logged.php');

if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    
    require_once("../../../lib/password_compatibility_library.php");
}		
		if (empty($_POST['user_id_mod'])){
			$errors[] = "ID vacío";
		}  elseif (empty($_POST['user_password_new3']) || empty($_POST['user_password_repeat3'])) {
            $errors[] = "Contraseña vacía";
        } elseif ($_POST['user_password_new3'] !== $_POST['user_password_repeat3']) {
            $errors[] = "la contraseña y la repetición de la contraseña no son lo mismo";
        }  elseif (
			 !empty($_POST['user_id_mod'])
			&& !empty($_POST['user_password_new3'])
            && !empty($_POST['user_password_repeat3'])
            && ($_POST['user_password_new3'] === $_POST['user_password_repeat3'])
        ) {
            require_once ("../../../config/db.php");
			require_once ("../../../config/conexion.php");
			
				$user_id=intval($_POST['user_id_mod']);
				$user_password = $_POST['user_password_new3'];
				
                
				$user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);
					
               
					
                    $sql = "UPDATE users SET user_password_hash='".$user_password_hash."' WHERE user_id='".$user_id."'";
                    $query = mysqli_query($con,$sql);

                   
                    if ($query) {
                        $messages[] = "Se ha cambiado la contraseña";
                    } else {
                        $errors[] = "Registro fallido";
                    }
                
            
        } else {
            $errors[] = "error desconocido";
        }
		
		if (isset($errors)){
			
			?>
			<div class="alert alert-danger" role="alert">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error!</strong> 
					<?php
						foreach ($errors as $error) {
								echo $error;
							}
						?>
			</div>
			<?php
			}
			if (isset($messages)){
				
				?>
				<div class="alert alert-success" role="alert">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong></strong>
						<?php
							foreach ($messages as $message) {
									echo $message;
								}
									echo '<script>
									$(document).ready(function() {
										
										setTimeout(function() {
											$("#myModal3").modal("hide"); // Cerrar la modal
											location.reload(); // Refresca la página
										}, 1000); // Tiempo antes de mostrar el mensaje
											});
									</script>';   
							?>
				</div>
				<?php
			}

?>