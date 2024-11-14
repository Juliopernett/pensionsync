<?php
if (isset($con)) {
  // Realizar las consultas a la base de datos
  $tipo_identificacion_query = mysqli_query($con, "SELECT tipo_identificacion, descripcion AS DESCRIPCION_tipo_identificacion FROM pm_tipo_identificacion");
  $cargo_query = mysqli_query($con, "SELECT id, descripcion FROM pm_cargos where estado=1");
  $tipo_empleado_query = mysqli_query($con, "SELECT id, CONCAT(UPPER(LEFT(descripcion, 1)), LOWER(SUBSTRING(descripcion, 2))) AS descripcion FROM pm_tipo_empleado;");
  $sustitutode_query = mysqli_query($con, "SELECT id, CONCAT(UPPER(LEFT(nombre_completo, 1)), LOWER(SUBSTRING(nombre_completo, 2))) as nombre_completo FROM gt_empleado where id_tipo_empleado=1;
												"); /*solo se listan los pensionados*/

  /* QUERYS DE AFILIACIONES*/

  $afiliacion_query1 = mysqli_query($con, "
    SELECT ID, 
    CONCAT(NIT, '-', DV, '  ', CONCAT(UPPER(LEFT(NOMBRE, 1)), LOWER(SUBSTRING(NOMBRE, 2)))) AS descripcion 
    FROM pm_entidades A 
    WHERE A.tipo_entidad IN (
        SELECT TP.ID 
        FROM pm_tipo_entidad TP 
        WHERE TP.CODIGO='PE'
		);
	");/*--PENSION*/

  $afiliacion_query2 = mysqli_query($con, "
    SELECT ID, 
    CONCAT(NIT, '-', DV, '  ', CONCAT(UPPER(LEFT(NOMBRE, 1)), LOWER(SUBSTRING(NOMBRE, 2)))) AS descripcion 
    FROM pm_entidades A 
    WHERE A.tipo_entidad IN (
        SELECT TP.ID 
        FROM pm_tipo_entidad TP 
        WHERE TP.CODIGO='SA'
		);
	");/*----SALUD*/
  $afiliacion_query3 = mysqli_query($con, "
    SELECT ID, 
    CONCAT(NIT, '-', DV, '  ', CONCAT(UPPER(LEFT(NOMBRE, 1)), LOWER(SUBSTRING(NOMBRE, 2)))) AS descripcion 
    FROM pm_entidades A 
    WHERE A.tipo_entidad IN (
        SELECT TP.ID 
        FROM pm_tipo_entidad TP 
        WHERE TP.CODIGO='CE'
		);
	");/*----CESANTIAS*/
  $afiliacion_query4 = mysqli_query($con, "
    SELECT ID, 
    CONCAT(NIT, '-', DV, '  ', CONCAT(UPPER(LEFT(NOMBRE, 1)), LOWER(SUBSTRING(NOMBRE, 2)))) AS descripcion 
    FROM pm_entidades A 
    WHERE A.tipo_entidad IN (
        SELECT TP.ID 
        FROM pm_tipo_entidad TP 
        WHERE TP.CODIGO='AR'
		);
	");/*----ARL*/
  $afiliacion_query5 = mysqli_query($con, "
    SELECT ID, 
    CONCAT(NIT, '-', DV, '  ', CONCAT(UPPER(LEFT(NOMBRE, 1)), LOWER(SUBSTRING(NOMBRE, 2)))) AS descripcion 
    FROM pm_entidades A 
    WHERE A.tipo_entidad IN (
        SELECT TP.ID 
        FROM pm_tipo_entidad TP 
        WHERE TP.CODIGO='CA'
		);
	");/*----CAJA DE COMPENSACION*/
  $tiporiesgo_query = mysqli_query($con, "
											SELECT id, descripcion, porcentaje FROM pm_tipo_riesgo;");/*----tipo riesgos*/


  ?>
  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Crear nuevo concepto</h4>
        </div>
        <div class="modal-body">
          <!-- Formulario -->
          <form class="form-horizontal" method="post" id="guardar_concepto" name="guardar_concepto">
            <div id="resultados_ajax"></div>

            <!-- Contenido de las pestañas -->
            <div class="tab-content">
                <div class="form-group">
                <label for="new_codigo" class="col-sm-3 control-label">Código:</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="new_codigo" name="new_codigo"
                      placeholder="Código del concepto" maxlength="10" required>
                    <input type="hidden" id="new_id" name="new_id">
                  </div>

                  <label for="new_descripcion" class="col-sm-3 control-label">Descripción:</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="new_descripcion" name="new_descripcion"
                      placeholder="Descripción del concepto" maxlength="255" required >
                  </div>

                  <label for="new_tipo_movimiento" class="col-sm-3 control-label">Tipo Movimiento:</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="new_tipo_movimiento" name="new_tipo_movimiento">
                    <option value="">Selecciona un tipo movimiento</option>
                      <option value="1">Devengos</option>
                      <option value="2">Descuentos</option>
                      <option value="6">Informativo</option>
                      <option value="7">Aporte Patrono</option>
                      <option value="8">Parafiscales</option>
                    </select>
                  </div>

                  <label for="new_tipo_concepto" class="col-sm-3 control-label">Tipo concepto:</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="new_tipo_concepto" name="new_tipo_concepto">
                    <option value="">Selecciona un tipo de concepto</option>
                      <option value="1">Pensionado</option>
                      <option value="2">Activo</option>
                      <option value="3">Sustituto</option>
                    </select>
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