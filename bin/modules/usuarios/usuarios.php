<?php
session_start();
//var_dump($_SESSION['perfil']);
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1 ) {
        header("location: ../../../login.php");
		exit;
        }
        if($_SESSION['perfil'] != 'Administrador')
        {
        	die('No tiene los permisos para este modulo');

        }

	require_once ("../../../config/db.php");
	require_once ("../../../config/conexion.php");
	$active_facturas="";
	$active_productos="";
	$active_clientes="";
	$active_usuarios="active";	
	$title="Administración de usuarios";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("../../../plantilla/head.php");?>

    <script src="../../../lib/js/jquery.js?v=<?php echo str_replace('.', '', microtime(true)); ?>"></script>
    <script src="../../../lib/jquery/jquery-2.2.3.min.js"></script>
    <script src="../../../lib/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/usuarios.js"></script>
</head>

<body>
    <?php 	
	include("../../../plantilla/navbar.php");
	?>
    <!--<div class="container">-->
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4> Administración de usuarios del sistema</h4>
            </div>
            <div class="panel-body">
                <?php
					include("modal/registro_usuarios.php");
					include("modal/editar_usuarios.php");
					include("modal/cambiar_password.php");
					?>
                <form class="form-horizontal" role="form" id="datos_cotizacion">
                    <div class="form-group row">
                        <label for="q" class="col-md-2 control-label">Buscar por:</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="q"
                                placeholder="nombres, usuario, perfil o correo" onkeyup='load(1);'>
                        </div>
                        <button type='button' class="btn btn-success"  data-toggle="modal" data-target="#myModal"></span>
                            Crear usuario</button>
                        <div class="col-md-4">
                            <!--<button type="button" class="btn btn-default" onclick='load(1);'>-->
                            <!--<span class="glyphicon glyphicon-search" ></span> Buscar</button>-->
                            <span id="loader"></span>
                        </div>
                    </div>
            </div>
            </form>

 			<!--tabla de usuarios-->
            <div id="resultados"></div>
            <div class='outer_div'></div>

        </div>
    </div>

</body>

</html>
<?php
	include '../../../plantilla/footer1.php';
	?>
<script>
$("#guardar_usuario").submit(function(event) {
    $('#guardar_datos').attr("disabled", true);

    var parametros = $(this).serialize();
    $.ajax({
        type: "POST",
        url: "nuevo_usuario.php",
        data: parametros,
        beforeSend: function(objeto) {
            $("#resultados_ajax").html("Mensaje: Cargando...");
        },
        success: function(datos) {
            $("#resultados_ajax").html(datos);
            $('#guardar_datos').attr("disabled", false);
            load(1);
        }
    });
    event.preventDefault();
})

$("#editar_usuario").submit(function(event) {
    $('#actualizar_datos2').attr("disabled", true);

    var parametros = $(this).serialize();
    $.ajax({
        type: "POST",
        url: "editar_usuario.php",
        data: parametros,
        beforeSend: function(objeto) {
            $("#resultados_ajax2").html("Mensaje: Cargando...");
        },
        success: function(datos) {
            $("#resultados_ajax2").html(datos);
            $('#actualizar_datos2').attr("disabled", false);
            load(1);
        }
    });
    event.preventDefault();
})

$("#editar_password").submit(function(event) {
    $('#actualizar_datos3').attr("disabled", true);

    var parametros = $(this).serialize();
    $.ajax({
        type: "POST",
        url: "editar_password.php",
        data: parametros,
        beforeSend: function(objeto) {
            $("#resultados_ajax3").html("Mensaje: Cargando...");
        },
        success: function(datos) {
            $("#resultados_ajax3").html(datos);
            $('#actualizar_datos3').attr("disabled", false);
            load(1);
        }
    });
    event.preventDefault();
})

function get_user_id(id) {
    $("#user_id_mod").val(id);
}

function obtener_datos(id) {
    var nombres = $("#nombres" + id).val();
    var apellidos = $("#apellidos" + id).val();
    var usuario = $("#usuario" + id).val();
    var email = $("#email" + id).val();
    var perfil = $("#perfil" + id).val();


    $("#mod_id").val(id);
    $("#firstname2").val(nombres);
    $("#lastname2").val(apellidos);
    $("#user_name2").val(usuario);
    $("#user_email2").val(email);
    $("#user_perfil").val(perfil);

}
</script>