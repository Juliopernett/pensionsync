<?php
if (isset($con)) {

?>
<!-- Modal para Ver Información -->
<div class="modal fade" id="viewEmployeeModal" tabindex="-1" role="dialog" aria-labelledby="viewEmployeeModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="viewEmployeeModalLabel"><i class='glyphicon glyphicon-eye-open'></i> Ver información del empleado</h4>
            </div>
            <div class="modal-body">
                <div id="resultados_ajax_view"></div>
                
                <!-- Pestañas -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="active"><a href="#view_inf_basica" role="tab" data-toggle="tab">Inf. Básica</a></li>
                    <li id="view_tab_sustituto" style="display:none;"><a href="#view_info_sustituto" role="tab" data-toggle="tab">Inf. de sustituto</a></li>
                    <li id="view_tab_pensionado" style="display:none;"><a href="#view_info_pensionado" role="tab" data-toggle="tab">Inf. de pensionado</a></li>
                    <li><a href="#view_info_adicional" role="tab" data-toggle="tab">Inf. adicional</a></li>
                    <li><a href="#view_info_afiliaciones" role="tab" data-toggle="tab">Inf. de afiliaciones</a></li>
                </ul>
                
                <!-- Contenido de las pestañas -->
                <div class="tab-content">
                    <div class="tab-pane active" id="view_inf_basica">
                        <div class="form-group">
                            <label for="view_id_tipo_identificacion" class="col-sm-3 control-label">Tipo id:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="view_id_tipo_identificacion" readonly>
                            </div>

                            <label for="view_documento" class="col-sm-3 control-label">Documento:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="view_documento" readonly>
                            </div>

                            <label for="view_primer_nombre" class="col-sm-3 control-label">1er Nombre:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="view_primer_nombre" readonly>
                            </div>

                            <label for="view_segundo_nombre" class="col-sm-3 control-label">2do Nombre:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="view_segundo_nombre" readonly>
                            </div>

                            <label for="view_primer_apellido" class="col-sm-3 control-label">1er Apellido:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="view_primer_apellido" readonly>
                            </div>

                            <label for="view_segundo_apellido" class="col-sm-3 control-label">2do Apellido:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="view_segundo_apellido" readonly>
                            </div>

                            <label for="view_estado" class="col-sm-3 control-label">Estado:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="view_estado" readonly>
                            </div>

                            <label for="view_id_tipo_empleado" class="col-sm-3 control-label">Tipo empleado:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="view_id_tipo_empleado" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="view_info_adicional">
                        <div class="form-group">
                            <label for="view_sexo" class="col-sm-3 control-label">Sexo:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="view_sexo" readonly>
                            </div>

                            <label for="view_telefono" class="col-sm-3 control-label">Teléfono:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="view_telefono" readonly>
                            </div>

                            <label for="view_direccion" class="col-sm-3 control-label">Dirección:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="view_direccion" readonly>
                            </div>

                            <label for="view_cumpleanos" class="col-sm-3 control-label">Cumple:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="view_cumpleanos" readonly>
                            </div>

                            <label for="view_correo" class="col-sm-3 control-label">Correo:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="view_correo" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="view_info_sustituto">
                        <div class="form-group">
                            <label for="view_id_Empleado_sustituto" class="col-sm-3 control-label">Sustituto de:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="view_id_Empleado_sustituto" readonly>
                            </div>

                            <label for="view_fecha_sustitucion" class="col-sm-3 control-label">Fecha:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="view_fecha_sustitucion" readonly>
                            </div>

                            <label for="view_porcentaje_sustitucion" class="col-sm-3 control-label">Porcentaje:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="view_porcentaje_sustitucion" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="view_info_pensionado">
                        <div class="form-group">
                            <label for="view_dato_pension" class="col-sm-3 control-label">Dato pensión:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="view_dato_pension" readonly>
                            </div>

                            <label for="view_fecha_pension" class="col-sm-3 control-label">Fecha pensión:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="view_fecha_pension" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="view_info_afiliaciones">
                        <div class="form-group">
                            <label for="view_id_fondo_pension" class="col-sm-3 control-label">Ent. Pensión:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="view_id_fondo_pension" readonly>
                            </div>

                            <label for="view_id_eps" class="col-sm-3 control-label">Ent. Salud:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="view_id_eps" readonly>
                            </div>
                            <!---
                            <label for="view_id_fondo_cesantias" class="col-sm-3 control-label">Ent. Cesantías:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="view_id_fondo_cesantias" readonly>
                            </div>

                            <label for="view_id_arl" class="col-sm-3 control-label">Ent. ARL:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="view_id_arl" readonly>
                            </div>

                            <label for="view_id_riesgo_arl" class="col-sm-3 control-label">Clase riesgos:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="view_id_riesgo_arl" readonly>
                            </div>

                            <label for="view_id_caja_compensacion" class="col-sm-3 control-label">Caja de compensación:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="view_id_caja_compensacion" readonly>
                            </div>--->
                        </div>
                    </div>
                </div>  
            </div>      
            <div class="modal-footer">
              
            </div>
        </div>
    </div>
</div>


<?php
}
?>

<script>
function checkSustitutoView() {
    var tipoEmpleado = document.getElementById('view_id_tipo_empleado').value;
    
    // Ocultar las pestañas de sustituto y pensionado por defecto
    document.getElementById('view_tab_sustituto').style.display = 'none';
    document.getElementById('view_tab_pensionado').style.display = 'none';

    if (tipoEmpleado === 'SUSTITUTO') { // Sustituto
        document.getElementById('view_tab_sustituto').style.display = 'block';
        $('.nav-tabs a[href="#view_info_sustituto"]').tab('show'); // Mostrar la pestaña de sustituto
    } else if (tipoEmpleado === 'PENSIONADO') { // Pensionado
        document.getElementById('view_tab_pensionado').style.display = 'block';
        $('.nav-tabs a[href="#view_info_sustituto"]').tab('show'); // Mostrar la pestaña de pensionado
    }
}

$('#viewEmployeeModal').on('shown.bs.modal', function () {
  checkSustitutoView(); // Ejecuta la función al mostrar el modal
	$('.nav-tabs a[href="#view_inf_basica"]').tab('show');
});

</script>

