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
$active_conceptos = "active";
$title = "Administración de novedades";

// Obtener el año y mes actuales
$current_year = date("Y");
$current_month = date("m");
$fecha_actual = date('Ym'); // Formato Año-Mes (Ej: 2024-11)

// Calcular el año y mes límites hacia arriba y hacia abajo
$start_date = date("Ym", strtotime("-1 year")); // Un año hacia abajo
$end_date = date("Ym", strtotime("+1 year"));   // Un año hacia arriba

// Obtener el periodo correspondiente en la base de datos
$query_periodo = "SELECT id, codigo FROM pm_periodo WHERE codigo like '%$fecha_actual%' LIMIT 1";
$result_periodo = mysqli_query($con, $query_periodo);
$periodo_actual = mysqli_fetch_assoc($result_periodo);
$act_periodo = $periodo_actual['id']; // Suponiendo que usas el id del periodo

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("../../../plantilla/head.php"); ?>
    <script src="../../../lib/js/jquery.js?v=<?php echo str_replace('.', '', microtime(true)); ?>"></script>
    <script src="../../../lib/jquery/jquery-2.2.3.min.js"></script>
    <script src="../../../lib/jquery-ui.min.js"></script>
    <script type="text/javascript" src="js/novedades.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.5.0/dist/autoNumeric.min.js"></script>

</head>

<body>
    <?php include("../../../plantilla/navbar.php"); ?>
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4> Administración de novedades</h4>
            </div>
            <div class="panel-body">
                <?php
                include("modal/registro_novedades.php");
                include("modal/editar_novedades.php");
                ?>
                <form class="form-horizontal" role="form" id="datos_cotizacion">

                    <!-- Filtro por periodo (obligatorio) -->
                    <div class="form-group row">
                        <label for="sel_periodo" class="col-md-2 control-label">Período:</label>
                        <div class="col-md-8">
                            <select class="form-control" id="sel_periodo" required onchange="load(1);">
                                <?php
                                // Obtener los periodos disponibles
                                $query_periodos = mysqli_query($con, "SELECT * FROM pm_periodo WHERE codigo BETWEEN '$start_date' AND '$end_date' ORDER BY id ASC");

                                while ($row_periodo = mysqli_fetch_array($query_periodos)) {
                                    // Si el periodo es el actual, marcarlo como seleccionado
                                    $selected = ($row_periodo['id'] == $act_periodo) ? 'selected' : '';
                                    echo '<option value="' . $row_periodo['id'] . '" ' . $selected . '>' . $row_periodo['codigo'] . ' - ' . $row_periodo['mes'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="q" class="col-md-2 control-label">Buscar novedades:</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" id="q" placeholder="empleado, concepto, valor, estado, usuario, fecha de registro, resolución" onkeyup='load(1);'>
                        </div>
                        <button type='button' class="btn btn-success" data-toggle="modal" data-target="#myModal">Crear novedad</button>
                    </div>

                    <div class="col-md-4">
                        <span id="loader"></span>
                    </div>
                </form>

            </div>

            <!-- Tabla de usuarios -->
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

    // Capturar el cambio de periodo y pasar el valor a la modal
    $("#sel_periodo").change(function () {
    // Obtener el valor visual (texto mostrado en el select)
    var periodoSeleccionadoTexto = $("#sel_periodo option:selected").text();

    // Obtener el valor real (value del option seleccionado)
    var periodoSeleccionadoValor = $("#sel_periodo").val();

    // Mostrar el texto visible en la modal
    $("#new_id_display").text("Periodo: " + periodoSeleccionadoTexto); 
    $("#new_id_periodo").val(periodoSeleccionadoValor);
    });

    // Establecer los valores iniciales cuando la página se carga
    $(document).ready(function() {
        // Obtener el valor visual (texto mostrado en el select)
        var periodoSeleccionadoTexto = $("#sel_periodo option:selected").text();

        // Obtener el valor real (value del option seleccionado)
        var periodoSeleccionadoValor = $("#sel_periodo").val();

        // Mostrar el texto visible en la modal
        $("#new_id_display").text("Periodo: " + periodoSeleccionadoTexto);
        $("#new_id_periodo").val(periodoSeleccionadoValor);
        // Log para depuración
        //console.log("Valor seleccionado al cargar la página:", periodoSeleccionadoValor);
    });



    $("#guardar_novedad").submit(function (event) {
        $('#guardar_datos').attr("disabled", true);

        var parametros = $(this).serialize();
        //console.log("Parametros: ", parametros);
        $.ajax({
            type: "POST",
            url: "nueva_novedad.php",
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

    $("#editar_novedad").submit(function (event) {
        $('#actualizar_datos').attr("disabled", true);
        var parametros = $(this).serialize();
        //console.log("Parametros: ", parametros);
        $.ajax({
            type: "POST",
            url: "editar_novedad.php",
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

    function obtener_datos(id) {
    var id_empleado = $("#id_empleado" + id).val();
    var id_concepto = $("#id_concepto" + id).val();
    var id_periodo = $("#id_periodo" + id).val(); // mostrar
    var Valor = $("#Valor" + id).val(); // Aquí obtienes el valor
    var estado = $("#estado" + id).val();
    var resolucion_id = $("#resolucion_id" + id).val();
    var codigo_periodo = $("#codigo_periodo" + id).val();

    // Validamos si Valor está vacío o no es un número válido
    if (isNaN(Valor) || Valor.trim() === '') {
        alert('El valor debe ser un número válido');
        return; // Salir si el valor no es válido
    }

    // Establecemos los valores de los campos en el modal
    $("#mod_id").val(id);
    $("#mod_id_display").text('ID: ' + id + ' // Periodo: ' + codigo_periodo);
    $("#mod_id_empleado").val(id_empleado);
    $("#mod_id_concepto").val(id_concepto);
    $("#mod_id_periodo").val(id_periodo);
    $("#mod_valor").val(Valor); // Asignamos el valor aquí (al campo mod_valor)
    $("#mod_estado").val(estado);
    $("#mod_resolucion_id").val(resolucion_id);

    // Ahora, aplicamos AutoNumeric SOLO a #mod_valor
   /*     setTimeout(function () {
            new AutoNumeric('#mod_valor', {
                currencySymbol: '$',          // Cambia el símbolo de la moneda según sea necesario
                decimalPlaces: 2,             // Dos decimales
                digitGroupSeparator: ',',     // Separador de miles
                decimalCharacter: '.',       // Separador decimal
                minimumValue: '0.00',         // Valor mínimo
                maximumValue: '9999999999.99' // Valor máximo
            });
        }, 100); // Pequeño retraso para asegurar que el valor se asigne antes de aplicar AutoNumeric*/
    }


    function refreshPage() {
        location.reload(); // Refresca la página
    }
</script>
