<?php
session_start();
//var_dump($_SESSION['perfil']);
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1 ) {
        header("location: ../../../login.php");
		exit;
        }
        if($_SESSION['perfil'] != 'Administrador')
        {
        	die('No tiene los permisos para esta opción');

        }

	require_once ("../../../config/db.php");
	require_once ("../../../config/conexion.php");
	$active_facturas="";
	$active_productos="";
	$active_clientes="";	
	$title="Administración de cargos";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("../../../plantilla/head.php");?>

    <script src="../../../lib/js/jquery.js?v=<?php echo str_replace('.', '', microtime(true)); ?>"></script>
    <script src="../../../lib/jquery/jquery-2.2.3.min.js"></script>
    <script src="../../../lib/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/cargos.js"></script>
</head>

<body>
    <?php 	
	include("../../../plantilla/navbar.php");
	?>
    <!--<div class="container">-->
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4> Administración de cargos</h4>
            </div>
            <div class="panel-body">
                <?php
					include("modal/registro_cargos.php");
					include("modal/editar_cargos.php");
					?>
                <form class="form-horizontal" role="form" id="datos_cotizacion">
                    <div class="form-group row">
                        <label for="q" class="col-md-2 control-label">Buscar cargo por:</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="q"
                                placeholder="nombre del cargo, usuario de registro, fecha de registro" onkeyup='load(1);'>
                        </div>
                        <button type='button' class="btn btn-success"  data-toggle="modal" data-target="#myModal"></span>
                            Crear cargo</button>
                        <div class="col-md-4">
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
        url: "nuevo_cargo.php",
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

$("#editar_cargo").submit(function(event) {
    $('#actualizar_datos').attr("disabled", true);

    var parametros = $(this).serialize();
    $.ajax({
        type: "POST",
        url: "editar_cargo.php",
        data: parametros,
        beforeSend: function(objeto) {
            $("#resultados_ajax2").html("Mensaje: Cargando...");
        },
        success: function(datos) {
            $("#resultados_ajax2").html(datos);
            $('#actualizar_datos').attr("disabled", false);
            load(1);
        }
    });
    event.preventDefault();
})


function get_user_id(id) {
    $("#mod_id").val(id);
}

function obtener_datos(id) {
    var estado = $("#estado" + id).val();
    var descripcion = $("#descripcion" + id).val();

   // alert(estado);
    $("#mod_id").val(id);
    $("#mod_estado").val(estado);
    $("#mod_descripcion").val(descripcion);

}

function refreshPage() {
    location.reload(); // Refresca la página
}



</script>