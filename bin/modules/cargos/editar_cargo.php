<?php
include('../../../is_logged.php');
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    
    require_once("../../../lib/password_compatibility_library.php");
}		
/*echo ($_POST['mod_descripcion']);
echo ($_POST['mod_estado']);*/

		if (empty($_POST['mod_descripcion'])){
			$errors[] = "Descripción vacia";
		
        } elseif (!empty($_POST['mod_descripcion']))
         {
            require_once ("../../../config/db.php");
			require_once ("../../../config/conexion.php");
			
				
                $descripcion = mysqli_real_escape_string($con,(strip_tags($_POST["mod_descripcion"],ENT_QUOTES)));
				$estado = mysqli_real_escape_string($con,(strip_tags($_POST["mod_estado"],ENT_QUOTES)));
				session_start();
				$user = ($_SESSION['user_name']);
				$cargo_id=intval($_POST['mod_id']);
				$fechaActual = date('Y-m-d H:i:s'); 
					
               
                    $sql = "UPDATE pm_cargos SET descripcion='".$descripcion."', estado='".$estado."', usuario_registro='".$user."', fecha_registro='".$fechaActual."'
                            WHERE id='".$cargo_id."';";
                    $query_update = mysqli_query($con,$sql);
                    if ($query_update) {
                        $messages[] = "el cargo ha sido actualizado.";
                    } else {
                        $errors[] = "El registro falló. Por favor, regrese y vuelva a intentarlo.";
                    }
                
            
        } else {
            $errors[] = "Error desconocido.";
        }
		
		if (isset($errors)){
			
			?>
			<div class="alert alert-danger" role="alert">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong></strong> 
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
                                                    $("#myModal2").modal("hide"); // Cerrar la modal
                                                    location.reload(); // Refresca la página
                                                }, 1000); // Tiempo antes de mostrar el mensaje
                                            });
                                       </script>';    	
							?>
				</div>
				<?php
			}

?>