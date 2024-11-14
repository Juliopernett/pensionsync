<?php
session_start();
//var_dump($_SESSION['perfil']);
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1 ) {
        header("location: ../../../login.php");
		exit;
        }
        if($_SESSION['perfil'] != 'Administrador' && $_SESSION['perfil'] != 'Empleado')
        {
        	die('No tiene los permisos para esta opción');

        }

	require_once ("../../../config/db.php");
	require_once ("../../../config/conexion.php");
	$active_facturas="";
	$active_productos="";
	$active_clientes="";
	$active_entidades="active";	
	$title="Administración de entidades";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("../../../plantilla/head.php");?>

    <script src="../../../lib/js/jquery.js?v=<?php echo str_replace('.', '', microtime(true)); ?>"></script>
    <script src="../../../lib/jquery/jquery-2.2.3.min.js"></script>
    <script src="../../../lib/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/entidades.js"></script>
</head>

<body>
    <?php 	
	include("../../../plantilla/navbar.php");
	?>
    <!--<div class="container">-->
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4> Administración de entidades</h4>
            </div>
            <div class="panel-body">
                <?php
					include("modal/registro_entidades.php");
					include("modal/editar_entidades.php");
					?>
                <form class="form-horizontal" role="form" id="datos_cotizacion">
                    <div class="form-group row">
                        <label for="q" class="col-md-2 control-label">Buscar entidad por:</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="q"
                                placeholder="Nit, nombre, usuario o fecha de registro" onkeyup='load(1);'>
                        </div>
                        <button type='button' class="btn btn-success"  data-toggle="modal" data-target="#myModal"></span>
                            Crear entidad</button>
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
$("#guardar_entidad").submit(function(event) {
    $('#guardar_datos').attr("disabled", true);

    var parametros = $(this).serialize();
    $.ajax({
        type: "POST",
        url: "nuevo_entidad.php",
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

$("#editar_entidad").submit(function(event) {
    $('#actualizar_datos').attr("disabled", true);

    var parametros = $(this).serialize();
    $.ajax({
        type: "POST",
        url: "editar_entidad.php",
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
    var nombre = $("#nombre" + id).val();
    var nit = $("#nit" + id).val();
    var dv = $("#dv" + id).val();
    var tipo_entidad = $("#tipo_entidad" + id).val();


    //alert(tipo_entidad);
    $("#mod_id").val(id);
    $("#mod_estado").val(estado);
    $("#mod_nombre").val(nombre);
    $("#mod_nit").val(nit);
    $("#mod_dv").val(dv);
    $("#mod_tipo_entidad").val(tipo_entidad);

}

function refreshPage() {
    location.reload(); // Refresca la página
}



</script>