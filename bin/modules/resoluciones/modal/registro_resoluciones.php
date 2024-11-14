<?php
if (isset($con)) {

  ?>
  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Crear nueva resolución</h4>
        </div>
        <div class="modal-body">
          <!-- Formulario -->
          <form class="form-horizontal" method="post" id="guardar_concepto" name="guardar_concepto">
            <div id="resultados_ajax"></div>

            <!-- Contenido de las pestañas -->
            <div class="tab-content">
            <div class="form-group">
                <label for="new_numero" class="col-sm-3 control-label">Número:</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="new_numero" name="new_numero"
                      placeholder="número de resolución" maxlength="10" required>
                    <input type="hidden" id="new_id" name="new_id">
                  </div>

                  <label for="new_detalle" class="col-sm-3 control-label">Detalle:</label>
                  <div class="col-sm-8">
                    <textarea id="new_detalle" name="new_detalle" rows="5" maxlength="1000"  style="width: 100%; resize: vertical;" placeholder="  Ingresa el detalle aquí..." required></textarea>
                  </div>

                  <label for="new_fecha_resolucion" class="col-sm-3 control-label">Fecha:</label>
                  <div class="col-sm-8">
                    <input type="date" class="form-control" id="new_fecha_resolucion" name="new_fecha_resolucion"
                      placeholder="Fecha de resolución" required >
                  </div>

                  <label for="new_estado" class="col-sm-3 control-label">Estado:</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="new_estado" name="new_estado">
                      <option value="1">Activo</option>
                      <option value="0">Inactivo</option>
                    </select>
                  </div>
                </div>
            </div>
            
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-primary" id="guardar_datos">Guardar datos</button>
            </div>
          </form>  
        </div>
      </div>
    </div>
  </div>
  <?php
}
?>

<script>
  function validateDigit(input) {
    if (input.value.length > 1) {
      input.value = input.value.slice(0, 1);  // Limitar a 1 dígito
    }
  }

  function validatePorcentaje(input) {
    // Verifica que el valor no sea menor que 0
    if (input.value < 0) {
      input.value = 0;
    }
    // Verifica que el valor no sea mayor que 100
    else if (input.value > 100) {
      input.value = 100;
    }
  }

  function new_checkSustituto() {
    var tipoEmpleado = document.getElementById('new_id_tipo_empleado').value;

    // Ocultar las pestañas de sustituto y pensionado por defecto
    document.getElementById('new_tab_sustituto').style.display = 'none';
    document.getElementById('new_tab_pensionado').style.display = 'none';

    if (tipoEmpleado === '3') { // Sustituto
      document.getElementById('new_tab_sustituto').style.display = 'block';
      $('.nav-tabs a[href="#new_info_sustituto"]').tab('show'); // Mostrar la pestaña de sustituto
      
      new_dato_pension.required = false; // No es requerido
      new_dato_pension.disabled = true;  // Deshabilitado
      new_dato_pension.value = ""; // Limpiar el campo si tiene algún valor
      
      new_fecha_pension.required = false; // No es requerido
      new_fecha_pension.disabled = true;  // Deshabilitado
      new_fecha_pension.value = ""; // Limpiar el campo si tiene algún valor

      new_porcentaje_sustitucion.required = true; // Es requerido
      new_porcentaje_sustitucion.disabled = false;  // habilitado

      new_fecha_sustitucion.required = true; // es requerido
      new_fecha_sustitucion.disabled = false;  // habilitado
   
      new_id_Empleado_sustituto.required = true; //es requerido
      new_id_Empleado_sustituto.disabled = false;  // habilitado


    } else if (tipoEmpleado === '1') { // Pensionado
      document.getElementById('new_tab_pensionado').style.display = 'block';
      $('.nav-tabs a[href="#new_info_pensionado"]').tab('show'); // Mostrar la pestaña de pensionado
      
      new_porcentaje_sustitucion.required = false; // No es requerido
      new_porcentaje_sustitucion.disabled = true;  // Deshabilitado
      new_porcentaje_sustitucion.value = ""; // Limpiar el campo si tiene algún valor
      
      new_fecha_sustitucion.required = false; // No es requerido
      new_fecha_sustitucion.disabled = true;  // Deshabilitado
      new_fecha_sustitucion.value = ""; // Limpiar el campo si tiene algún valor

      new_id_Empleado_sustituto.required = false; // No es requerido
      new_id_Empleado_sustituto.disabled = true;  // Deshabilitado
      new_id_Empleado_sustituto.value = ""; // Limpiar el campo si tiene algún valor

      new_dato_pension.required = true; // es requerido
      new_dato_pension.disabled = false;  // habilitado
  
      new_fecha_pension.required = true; // es requerido
      new_fecha_pension.disabled = false;  // habilitado


    }else{
      new_dato_pension.required = false; // No es requerido
      new_dato_pension.disabled = true;  // Deshabilitado
      new_dato_pension.value = ""; // Limpiar el campo si tiene algún valor
      
      new_fecha_pension.required = false; // No es requerido
      new_fecha_pension.disabled = true;  // Deshabilitado
      new_fecha_pension.value = ""; // Limpiar el campo si tiene algún valor

      new_porcentaje_sustitucion.required = false; // No es requerido
      new_porcentaje_sustitucion.disabled = true;  // Deshabilitado
      new_porcentaje_sustitucion.value = ""; // Limpiar el campo si tiene algún valor
      
      new_fecha_sustitucion.required = false; // No es requerido
      new_fecha_sustitucion.disabled = true;  // Deshabilitado
      new_fecha_sustitucion.value = ""; // Limpiar el campo si tiene algún valor

      new_id_Empleado_sustituto.required = false; // No es requerido
      new_id_Empleado_sustituto.disabled = true;  // Deshabilitado
      new_id_Empleado_sustituto.value = ""; // Limpiar el campo si tiene algún valor

    }
  }

  $('#myModal').on('shown.bs.modal', function () {
    new_checkSustituto(); // Ejecuta la función al mostrar el modal
    $('.nav-tabs a[href="#new_inf_basica"]').tab('show');
  });

</script>