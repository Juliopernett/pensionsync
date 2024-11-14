<?php
session_start();
//var_dump($_SESSION['perfil']);
if (!isset($_SESSION['user_login_status']) and $_SESSION['user_login_status'] != 1) {
    header("location: ../../../login.php");
    exit;
}
if ($_SESSION['perfil'] != 'Administrador' && $_SESSION['perfil'] != 'Empleado') {
    die('No tiene los permisos para esta opción');

}

require_once("../../../config/db.php");
require_once("../../../config/conexion.php");
$active_facturas = "";
$active_productos = "";
$active_clientes = "";
$active_empleados = "active";
$title = "Administración de empleados";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("../../../plantilla/head.php"); ?>

    <script src="../../../lib/js/jquery.js?v=<?php echo str_replace('.', '', microtime(true)); ?>"></script>
    <script src="../../../lib/jquery/jquery-2.2.3.min.js"></script>
    <script src="../../../lib/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/empleados.js"></script>
</head>

<body>
    <?php
    include("../../../plantilla/navbar.php");
    ?>
    <!--<div class="container">-->
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4> Administración de empleados</h4>
            </div>
            <div class="panel-body">
                <?php
                include("modal/registro_empleados.php");
                include("modal/editar_empleados.php");
                include("modal/ver_inf_adicional.php");
                include("modal/cargar_documento.php");
                ?>
                <form class="form-horizontal" role="form" id="datos_cotizacion">
                    <div class="form-group row">
                        <label for="q" class="col-md-2 control-label">Buscar empleado:</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="q"
                                placeholder="Documento, Nombre, usuario o fecha de registro" onkeyup='load(1);'>
                        </div>
                        <button type='button' class="btn btn-success" data-toggle="modal" data-target="#myModal"></span>
                            Crear empleado</button>
                        <div class="col-md-4">
                            <span id="loader"></span>
                        </div>
                    </div>

                </form>
            </div>

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
    $("#guardar_empleado").submit(function (event) {
        $('#guardar_datos').attr("disabled", true);

        var parametros = $(this).serialize();
        $.ajax({
            type: "POST",
            url: "nuevo_empleado.php",
            data: parametros,
            beforeSend: function (objeto) {
                $("#resultados_ajax").html("Mensaje: Cargando...");
            },
            success: function (datos) {
                $("#resultados_ajax").html(datos);
                $('#guardar_datos').attr("disabled", false);
                load(1);
            }
        });
        event.preventDefault();
    })

  /*  $(document).ready(function() {
        $("#editar_empleado").submit(function(event) {
            event.preventDefault(); // Detiene el envío del formulario

            $('#actualizar_datos').prop("disabled", true); // Deshabilita el botón

            var parametros = $(this).serialize();
            console.log("Parametros: ", parametros);

            $.ajax({
                type: "POST",
                url: "editar_empleado.php",
                data: parametros,
                success: function(datos) {
                    $("#resultados_ajax2").html(datos);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Error en la solicitud: ", textStatus, errorThrown);
                    $("#resultados_ajax2").html("Error al cargar los datos.");
                }
            });
        });
    });
*/
    $("#cargar_adjuntos").submit(function (event) {
        $('#subir_archivo').attr("disabled", true);
        var formData = new FormData(this);  // Usar FormData para manejar el archivo
        console.log("Parametros: ", formData);
        $.ajax({
            type: "POST",
            url: "cargar_adjuntos.php",
            data: formData,
            processData: false,  // No procesar los datos
            contentType: false,  // No establecer el contentType
            beforeSend: function (objeto) {
                $("#resultados_ajax4").html("Mensaje: Cargando...");
            },
            success: function (datos) {
                $("#resultados_ajax4").html(datos);
                $('#subir_archivo').attr("disabled", false);
                load(1);  // Suponiendo que este método recarga algún listado
            }
        });
        event.preventDefault();
    })


    $("#editar_empleado").submit(function (event) {
        $('#actualizar_datos').attr("disabled", true);
        //alert("mensaje");
        var parametros = $(this).serialize();
        console.log("Parametros: ", parametros);
        $.ajax({
            type: "POST",
            url: "editar_empleado.php",
            data: parametros,
            beforeSend: function (objeto) {
                $("#resultados_ajax2").html("Mensaje: Cargando...");
            },
            success: function (datos) {
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

    function subir_pdf(id) {
        //alert (id);
        var inf_empleado = $("#inf_empleado" + id).val();
        $("#car_id_display").text('ID: ' + id + ' // Nombre: ' + inf_empleado);
        $("#car_id").val(id);
    }

    function obtener_datos(id) {
        /* inf. basica */
        var estado = $("#estado" + id).val();
        var tipo_entidad = $("#tipo_entidad" + id).val();
        var id_tipo_identificacion = $("#id_tipo_identificacion" + id).val();
        var id_tipo_empleado = $("#id_tipo_empleado" + id).val();
        var documento = $("#identificacion" + id).val();
        var primer_nombre = $("#primer_nombre" + id).val();
        var segundo_nombre = $("#segundo_nombre" + id).val();
        var primer_apellido = $("#primer_apellido" + id).val();
        var segundo_apellido = $("#segundo_apellido" + id).val();

        /*inf. adicional*/
        var sexo = $("#sexo" + id).val();
        var telefono = $("#telefono" + id).val();
        var direccion = $("#direccion" + id).val();
        var correo_electronico = $("#correo_electronico" + id).val();
        var fcha_cumpleano = $("#fcha_cumpleano" + id).val();
        var dato_pension = $("#dato_pension" + id).val();
        var fecha_pension = $("#fecha_pension" + id).val();
        var id_Empleado_sustituto = $("#id_Empleado_sustituto" + id).val();
        var fecha_sustitucion = $("#fecha_sustitucion" + id).val();
        var porcentaje_sustitucion = $("#porcentaje_sustitucion" + id).val();
        var id_cargo = $("#id_cargo" + id).val();
        /*informacion afiliaciones*/
        var id_fondo_pension = $("#id_fondo_pension" + id).val();
        var id_eps = $("#id_eps" + id).val();
        var id_fondo_cesantias = $("#id_fondo_cesantias" + id).val();
        var id_arl = $("#id_arl" + id).val();
        var id_caja_compensacion = $("#id_caja_compensacion" + id).val();
        var id_riesgo_arl = $("#id_riesgo_arl" + id).val();

        //alert(id_cargo);

        $("#mod_id").val(id);
        $("#mod_estado").val(estado);
        $("#mod_id_tipo_identificacion").val(id_tipo_identificacion);
        $("#mod_id_tipo_empleado").val(id_tipo_empleado);
        $("#mod_documento").val(documento);
        $("#mod_primer_nombre").val(primer_nombre);
        $("#mod_segundo_nombre").val(segundo_nombre);
        $("#mod_primer_apellido").val(primer_apellido);
        $("#mod_segundo_apellido").val(segundo_apellido);
        $("#mod_sexo").val(sexo);
        $("#mod_telefono").val(telefono);
        $("#mod_direccion").val(direccion);
        $("#mod_cumpleanos").val(fcha_cumpleano);
        $("#mod_correo").val(correo_electronico);
        $("#mod_id_Empleado_sustituto").val(id_Empleado_sustituto);
        $("#mod_porcentaje_sustitucion").val(porcentaje_sustitucion);
        $("#mod_fecha_sustitucion").val(fecha_sustitucion);
        $("#mod_dato_pension").val(dato_pension);
        $("#mod_fecha_pension").val(fecha_pension);
        $("#mod_id_cargo").val(id_cargo);

        
        $("#mod_id_fondo_pension").val(id_fondo_pension);
        $("#mod_id_eps").val(id_eps);
        $("#mod_id_fondo_cesantias").val(id_fondo_cesantias);
        $("#mod_id_arl").val(id_arl);
        $("#mod_id_caja_compensacion").val(id_caja_compensacion);
        $("#mod_id_riesgo_arl").val(id_riesgo_arl);
    }

    function loadEmpleadosData(empleadoId) {
    //console.log("Cargando datos para el empleado ID:", empleadoId); // Log para verificar el ID recibido

    $.ajax({
        url: 'get_empleado_data.php', // archivo PHP que manejará la consulta
        type: 'POST',
        data: { id: empleadoId },
        dataType: 'json',
        success: function (data) {
            //console.log("Datos recibidos:", data); // Log para verificar los datos recibidos

            // Asigna los valores a los campos del modal
            $('#view_id_tipo_identificacion').val(data.id_tipo_identificacion);
            $('#view_documento').val(data.identificacion);
            $('#view_primer_nombre').val(data.primer_nombre);
            $('#view_segundo_nombre').val(data.segundo_nombre);
            $('#view_primer_apellido').val(data.primer_apellido);
            $('#view_segundo_apellido').val(data.segundo_apellido);

            if (data.estado === '0') {
                $('#view_estado').val('Inactivo');
            } else if (data.estado === '1') {
                $('#view_estado').val('Activo');
            } else {
                $('#view_estado').val('Estado desconocido'); // Opcional
            }
            $('#view_id_tipo_empleado').val(data.id_tipo_empleado);
            if (data.sexo === 'F') {
                $('#view_sexo').val('Femenino');
            } else if (data.sexo === 'M') {
                $('#view_sexo').val('Masculino');
            } else if (data.sexo === 'O') {
                $('#view_sexo').val('Otro');
            } else {
                $('#view_sexo').val('Sexo desconocido'); // Opcional
            }

            $('#view_telefono').val(data.telefono);
            $('#view_direccion').val(data.direccion);
            $('#view_cumpleanos').val(data.fcha_cumpleano);
            $('#view_correo').val(data.correo_electronico);

            $("#view_id_Empleado_sustituto").val(data.id_Empleado_sustituto);
            $("#view_porcentaje_sustitucion").val(data.porcentaje_sustitucion);
            $("#view_fecha_sustitucion").val(data.fecha_sustitucion);
            $("#view_dato_pension").val(data.dato_pension);
            $("#view_fecha_pension").val(data.fecha_pension);

            $("#view_id_fondo_pension").val(data.id_fondo_pension);
            $("#view_id_eps").val(data.id_eps);
            $("#view_id_fondo_cesantias").val(data.id_fondo_cesantias);
            $("#view_id_arl").val(data.id_arl);
            $("#view_id_caja_compensacion").val(data.id_caja_compensacion);
            $("#view_id_riesgo_arl").val(data.id_riesgo_arl);
        },
        error: function (xhr, status, error) {
            console.error("Error en la carga de datos:", error);
        }
    });
}


    function refreshPage() {
        location.reload(); // Refresca la página
    }



</script>