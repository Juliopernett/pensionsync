<?php
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {    
    require_once("lib/password_compatibility_library.php");
}
require_once("bin/modules/config/db.php");
require_once("login/Login.php");
$login = new Login();

if ($login->isUserLoggedIn() == true) {    
   header("location: bin/modules/inicio/inicio.php");

} else {
 
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no" />
    <title>PensionSync</title>

    <link rel="stylesheet" href="lib/bootstrap.min.css">
    <link href="css/login2.css" type="text/css" rel="stylesheet" media="screen,projection" />
    <script src="lib/js/jquery.js?v=<?php echo str_replace('.', '', microtime(true)); ?>"></script>
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">

                <center><img src="imagenes/LogoDDL2014.png" width=120> 
					<img src="imagenes/PensionSync.jpg"
                        width=120>
                    <!--<h3>Sistema P.Q.R.S</h3>-->
                </center>
                <div class="panel panel-login">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-12">
                                <a href="#" class="active" id="login-form-link">Iniciar sesión</a>
                            </div>

                        </div>
                        <hr>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <form id="login-form" method="post" accept-charset="utf-8" action="login.php"
                                    name="login-form" autocomplete="off" role="form" style="display: block;">
                                    <?php
				// show potential errors / feedback (from login object)
				if (isset($login)) {
					if ($login->errors) {
						?>
                                    <div class="alert alert-danger alert-dismissible" role="alert">
                                        <strong></strong>

                                        <?php 
						foreach ($login->errors as $error) {
							echo $error;
						}
						?>
                                    </div>
                                    <?php
					}
					if ($login->messages) {
						?>
                                    <div class="alert alert-success alert-dismissible" role="alert">
                                        <strong></strong>
                                        <?php
						foreach ($login->messages as $message) {
							echo $message;
						}
						?>
                                    </div>
                                    <?php 
					}
				}
				?>
                                    <span id="reauth-email" class="reauth-email"></span>

                                    <div class="form-group">
                                        <input class="form-control" placeholder="User" name="user_name" maxlength="30"
                                            type="text" value="" autofocus="" required>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="password" name="user_password"
                                            maxlength="30" type="password" value="" autocomplete="off" required>
                                    </div>
                                    <!--	<div class="form-group text-center">
										<input type="checkbox" tabindex="3" class="" name="remember" id="remember">
										<label for="remember"> Recordarme</label>
									</div>-->
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-sm-6 col-sm-offset-3">
                                                <button type="submit"
                                                    class="btn btn-success btn-block btn-signin" name="login"
                                                    id="submit">Ingresar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <!--	<div class="text-center">
													<a href="http://phpoll.com/recover" tabindex="5" class="forgot-password">¿Has olvidado tu contraseña?</a>
												</div>-->
                                            </div>
                                        </div>
                                    </div>

                                </form>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <?php

include 'plantilla/footer1.php';
?>
</body>


</html>

<script>
$(document).ready(function() {
    $('#login-form-link').click(function(e) {
        $("#login-form").delay(100).fadeIn(100);
        $("#guardar_usuario").fadeOut(100);
        $('#register-form-link').removeClass('active');
        $(this).addClass('active');
        e.preventDefault();
    });
    $('#register-form-link').click(function(e) {
        $("#guardar_usuario").delay(100).fadeIn(100);
        $("#login-form").fadeOut(100);
        $('#login-form-link').removeClass('active');
        $(this).addClass('active');
        e.preventDefault();
    });
});

$("#guardar_usuario").submit(function(event) {
    $('#guardar_datos').attr("disabled", true);

    var parametros = $(this).serialize();
    $.ajax({
        type: "POST",
        url: "bin/modules/usuarios/nuevo_usuario.php",
        data: parametros,
        beforeSend: function(objeto) {
            $("#resultados_ajax").html("Mensaje: Cargando...");
        },
        success: function(datos) {
            $("#resultados_ajax").html(datos);
            $('#guardar_datos').attr("disabled", false);
            //location.reload(true)
        }
    });
    event.preventDefault();
})
</script>

<?php
}

?>