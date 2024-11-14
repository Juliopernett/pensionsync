<?php

include '../../../core.php';
require_once("../../../login/Login.php");
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    
    require_once("../../../lib/password_compatibility_library.php");
}		
		if (empty($_POST['new_descripcion'])){
			$errors[] = "la descripción NO puede guardarse vacia";
		} elseif (!empty($_POST['new_descripcion'])) {
            require_once ("../../../config/db.php");
			require_once ("../../../config/conexion.php");
			
				
                $new_descripcion = mysqli_real_escape_string($con,(strip_tags($_POST["new_descripcion"],ENT_QUOTES)));
                session_start();
                $new_user = ($_SESSION['user_name']);
                
                
                $sql = "SELECT * FROM pm_cargos WHERE UPPER(descripcion) = UPPER('" . $new_descripcion . "');";
                $query_check_user_name = mysqli_query($con,$sql);
				$query_check_user=mysqli_num_rows($query_check_user_name);
                if ($query_check_user == 1) {
                    $errors[] = "Lo sentimos , el cargo con la descripción ingresada ya existe en la base de datos.";
                } else {
                    $sql = "INSERT INTO pm_cargos (descripcion, usuario_registro)
                            VALUES('".$new_descripcion."','".$new_user."');";
                    $query_new_cargo_insert = mysqli_query($con,$sql);

                    // if user has been added successfully
                    if ($query_new_cargo_insert) { 
                        $messages[] = "El cargo ha sido creada con éxito.";  

                    } else {
                        $errors[] = "Lo sentimos , el registro falló. Por favor, regrese y vuelva a intentarlo.";
                    }
                }
            
        } else {
            $errors[] = "Un error desconocido ocurrió.";
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