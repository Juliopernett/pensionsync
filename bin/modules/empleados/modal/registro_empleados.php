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
	");/*----SALUD
  $afiliacion_query3 = mysqli_query($con, "
    SELECT ID, 
    CONCAT(NIT, '-', DV, '  ', CONCAT(UPPER(LEFT(NOMBRE, 1)), LOWER(SUBSTRING(NOMBRE, 2)))) AS descripcion 
    FROM pm_entidades A 
    WHERE A.tipo_entidad IN (
        SELECT TP.ID 
        FROM pm_tipo_entidad TP 
        WHERE TP.CODIGO='CE'
		);
	");/*----CESANTIAS
  $afiliacion_query4 = mysqli_query($con, "
    SELECT ID, 
    CONCAT(NIT, '-', DV, '  ', CONCAT(UPPER(LEFT(NOMBRE, 1)), LOWER(SUBSTRING(NOMBRE, 2)))) AS descripcion 
    FROM pm_entidades A 
    WHERE A.tipo_entidad IN (
        SELECT TP.ID 
        FROM pm_tipo_entidad TP 
        WHERE TP.CODIGO='AR'
		);
	");/*----ARL
  $afiliacion_query5 = mysqli_query($con, "
    SELECT ID, 
    CONCAT(NIT, '-', DV, '  ', CONCAT(UPPER(LEFT(NOMBRE, 1)), LOWER(SUBSTRING(NOMBRE, 2)))) AS descripcion 
    FROM pm_entidades A 
    WHERE A.tipo_entidad IN (
        SELECT TP.ID 
        FROM pm_tipo_entidad TP 
        WHERE TP.CODIGO='CA'
		);
	");/*----CAJA DE COMPENSACION
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
          <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Crear nuevo empleado</h4>
        </div>
        <div class="modal-body">
          <!-- Formulario -->
          <form class="form-horizontal" method="post" id="guardar_empleado" name="guardar_empleado">
            <div id="resultados_ajax"></div>

            <!-- Pestañas -->
            <ul class="nav nav-tabs" role="tablist">
              <li class="active"><a href="#new_inf_basica" role="tab" data-toggle="tab">Inf. Básica</a></li>
              <li id="new_tab_sustituto" style="display:none;"><a href="#new_info_sustituto" role="tab" data-toggle="tab">Inf. de
                  sustituto</a></li>
              <li id="new_tab_pensionado" style="display:none;"><a href="#new_info_pensionado" role="tab" data-toggle="tab">Inf.
                  de pensionado</a></li>
              <li><a href="#new_info_adicional" role="tab" data-toggle="tab">Inf. adicional</a></li>
              <li><a href="#new_info_afiliaciones" role="tab" data-toggle="tab">Inf. de afiliaciones</a></li>
            </ul>

            <!-- Contenido de las pestañas -->
            <div class="tab-content">
              <!-- informacion basica-->
              <div class="tab-pane active" id="new_inf_basica">
                <div class="form-group">
                  <label for="new_id_tipo_identificacion" class="col-sm-3 control-label">Tipo documento:</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="new_id_tipo_identificacion" name="new_id_tipo_identificacion"
                      required>
                      <option value="">Selecciona un tipo de identificación</option>
                      <?php
                      while ($row = mysqli_fetch_assoc($tipo_identificacion_query)) {
                        echo "<option value='{$row['tipo_identificacion']}'>{$row['DESCRIPCION_tipo_identificacion']}</option>";
                      }
                      ?>
                    </select>
                  </div>

                  <label for="new_documento" class="col-sm-3 control-label">Documento:</label>
                  <div class="col-sm-8">
                    <input type="number" class="form-control" id="new_documento" name="new_documento"
                      placeholder="Documento de identidad (sin .)" required>
                    <input type="hidden" id="new_id" name="new_id">
                  </div>

                  <label for="new_primer_nombre" class="col-sm-3 control-label">1er Nombre:</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="new_primer_nombre" name="new_primer_nombre"
                      placeholder="Primer nombre" required>
                  </div>

                  <label for="new_segundo_nombre" class="col-sm-3 control-label">2do Nombre:</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="new_segundo_nombre" name="new_segundo_nombre"
                      placeholder="Segundo nombre">
                  </div>

                  <label for="new_primer_apellido" class="col-sm-3 control-label">1er Apellido:</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="new_primer_apellido" name="new_primer_apellido"
                      placeholder="Primer apellido" required>
                  </div>

                  <label for="new_segundo_apellido" class="col-sm-3 control-label">2do Apellido:</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="new_segundo_apellido" name="new_segundo_apellido"
                      placeholder="Segundo apellido">
                  </div>

                  <label for="new_estado" class="col-sm-3 control-label">Estado:</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="new_estado" name="new_estado">
                      <option value="1">Activo</option>
                      <option value="0">Inactivo</option>
                    </select>
                  </div>

                  <label for="new_id_tipo_empleado" class="col-sm-3 control-label">Tipo empleado:</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="new_id_tipo_empleado" name="new_id_tipo_empleado" required
                      onchange="new_checkSustituto()">
                      <option value="">Selecciona un tipo de empleado</option>
                      <?php
                      while ($row2 = mysqli_fetch_assoc($tipo_empleado_query)) {
                        echo "<option value='{$row2['id']}'>{$row2['descripcion']}</option>";
                      }
                      ?>
                    </select>
                  </div>

                </div>
              </div>

              <div class="tab-pane" id="new_info_adicional">
                <div class="form-group">

                <label for="new_id_cargo" class="col-sm-3 control-label">Cargo:</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="new_id_cargo" name="new_id_cargo" required>
                      <option value="">Selecciona un cargo</option>
                      <?php
                      while ($row24 = mysqli_fetch_assoc($cargo_query)) {
                        echo "<option value='{$row24['id']}'>{$row24['descripcion']}</option>";
                      }
                      ?>
                    </select>
                  </div>

                  <label for="new_sexo" class="col-sm-3 control-label">Sexo:</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="new_sexo" name="new_sexo">
                      <option value="">Selecciona un valor</option>
                      <option value="M">Masculino</option>
                      <option value="F">Femenino</option>
                      <option value="O">Otro</option>
                    </select>
                  </div>

                  <label for="new_telefono" class="col-sm-3 control-label">Teléfono:</label>
                  <div class="col-sm-8">
                    <input type="number" class="form-control" id="new_telefono" name="new_telefono" placeholder="Teléfono"
                      required>
                  </div>

                  <label for="new_direccion" class="col-sm-3 control-label">Dirección:</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="new_direccion" name="new_direccion"
                      placeholder="Dirección" required>
                  </div>

                  <label for="new_cumpleanos" class="col-sm-3 control-label">Cumple:</label>
                  <div class="col-sm-8">
                    <input type="date" class="form-control" id="new_cumpleanos" name="new_cumpleanos" required>
                  </div>

                  <label for="new_correo" class="col-sm-3 control-label">Correo:</label>
                  <div class="col-sm-8">
                    <input type="email" class="form-control" id="new_correo" name="new_correo"
                      placeholder="Correo electrónico" required>
                  </div>
                </div>
              </div>

              <div class="tab-pane" id="new_info_sustituto">
                <div class="form-group">
                  <label for="new_id_Empleado_sustituto" class="col-sm-3 control-label">Sustituto de:</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="new_id_Empleado_sustituto" name="new_id_Empleado_sustituto" required
                      onchange="new_checkSustituto()">
                      <option value="">Selecciona un empleado para sustituir</option>
                      <?php
                      while ($row3 = mysqli_fetch_assoc($sustitutode_query)) {
                        echo "<option value='{$row3['id']}'>{$row3['nombre_completo']}</option>";
                      }
                      ?>
                    </select>
                  </div>

                  <label for="new_fecha_sustitucion" class="col-sm-3 control-label">Fecha:</label>
                  <div class="col-sm-8">
                    <input type="date" class="form-control" id="new_fecha_sustitucion" name="new_fecha_sustitucion"
                      required>
                  </div>

                  <label for="new_porcentaje_sustitucion" class="col-sm-3 control-label">Porcentaje:</label>
                  <div class="col-sm-8">
                    <input type="number" class="form-control" id="new_porcentaje_sustitucion"
                      name="new_porcentaje_sustitucion" placeholder="Porcentaje sustitución número entre 0 y 100" required
                      min="0" max="100" step="0.01" oninput="validatePorcentaje(this)" maxlength="100">
                  </div>
                </div>
              </div>

              <div class="tab-pane" id="new_info_pensionado">
                <div class="form-group">
                  <label for="new_dato_pension" class="col-sm-3 control-label">Dato pesión:</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="new_dato_pension" name="new_dato_pension"
                      placeholder="Descripción pensión ">
                  </div>

                  <label for="new_fecha_pension" class="col-sm-3 control-label">Fecha pensión:</label>
                  <div class="col-sm-8">
                    <input type="date" class="form-control" id="new_fecha_pension" name="new_fecha_pension"
                      placeholder="Fecha pensión">
                  </div>
                </div>
              </div>

              <div class="tab-pane" id="new_info_afiliaciones">
                <div class="form-group">
                  <label for="new_id_fondo_pension" class="col-sm-3 control-label">Ent. Pensión:</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="new_id_fondo_pension" name="new_id_fondo_pension" required>
                      <option value="">Selecciona una entidad de pensión</option>
                      <?php
                      while ($row4 = mysqli_fetch_assoc($afiliacion_query1)) {
                        echo "<option value='{$row4['ID']}'>{$row4['descripcion']}</option>";
                      }
                      ?>
                    </select>
                  </div>

                  <label for="new_id_eps" class="col-sm-3 control-label">Ent. Salud:</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="new_id_eps" name="new_id_eps" required>
                      <option value="">Selecciona una entidad de salud</option>
                      <?php
                      while ($row5 = mysqli_fetch_assoc($afiliacion_query2)) {
                        echo "<option value='{$row5['ID']}'>{$row5['descripcion']}</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <!--    
                  <label for="new_id_fondo_cesantias" class="col-sm-3 control-label">Ent. Cesantías:</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="new_id_fondo_cesantias" name="new_id_fondo_cesantias" required>
                      <option value="">Selecciona una entidad de cesantias</option>
                      <?php
                      while ($row6 = mysqli_fetch_assoc($afiliacion_query3)) {
                        echo "<option value='{$row6['ID']}'>{$row6['descripcion']}</option>";
                      }
                      ?>
                    </select>
                  </div>

                  <label for="new_id_arl" class="col-sm-3 control-label">Ent. ARL:</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="new_id_arl" name="new_id_arl" required>
                      <option value="">Selecciona una entidad de riesgos</option>
                      <?php
                      while ($row7 = mysqli_fetch_assoc($afiliacion_query4)) {
                        echo "<option value='{$row7['ID']}'>{$row7['descripcion']}</option>";
                      }
                      ?>
                    </select>
                  </div>


                  <label for="new_id_riesgo_arl" class="col-sm-3 control-label">Clase riesgos:</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="new_id_riesgo_arl" name="new_id_riesgo_arl" required>
                      <option value="">Selecciona tipo de riesgos</option>
                      <?php
                      while ($row8 = mysqli_fetch_assoc($tiporiesgo_query)) {
                        echo "<option value='{$row8['id']}'>{$row8['descripcion']}</option>";
                      }
                      ?>
                    </select>
                  </div>

                  <label for="new_id_caja_compensacion" class="col-sm-3 control-label">Caja de compensación:</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="new_id_caja_compensacion" name="new_id_caja_compensacion" required>
                      <option value="">Selecciona una caja de compensación</option>
                      <?php
                      while ($row8 = mysqli_fetch_assoc($afiliacion_query5)) {
                        echo "<option value='{$row8['ID']}'>{$row8['descripcion']}</option>";
                      }
                      ?>
                    </select>
                  </div>
                  -->
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