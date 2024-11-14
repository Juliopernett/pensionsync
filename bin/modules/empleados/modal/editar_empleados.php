<?php
if (isset($con)) {
  // Realizar las consultas a la base de datos
  $tipo_identificacion_query = mysqli_query($con, "SELECT tipo_identificacion, descripcion AS DESCRIPCION_tipo_identificacion FROM pm_tipo_identificacion");
  $cargo_query = mysqli_query($con, "SELECT id, descripcion FROM pm_cargos");
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
  <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Editar información del
            empleado</h4>
        </div>
        <div class="modal-body">
          <!-- Formulario -->
          <form class="form-horizontal" method="post" id="editar_empleado" name="editar_empleado">
            <div id="resultados_ajax2"></div>

            <!-- Pestañas -->
            <ul class="nav nav-tabs" role="tablist">
              <li class="active"><a href="#inf_basica" role="tab" data-toggle="tab">Inf. Básica</a></li>
              <li id="tab_sustituto" style="display:none;"><a href="#info_sustituto" role="tab" data-toggle="tab">Inf. de
                  sustituto</a></li>
              <li id="tab_pensionado" style="display:none;"><a href="#info_pensionado" role="tab" data-toggle="tab">Inf.
                  de pensionado</a></li>
              <li><a href="#info_adicional" role="tab" data-toggle="tab">Inf. adicional</a></li>
              <li><a href="#info_afiliaciones" role="tab" data-toggle="tab">Inf. de afiliaciones</a></li>
            </ul>

            <!-- Contenido de las pestañas -->
            <div class="tab-content">
              <!-- informacion basica-->
              <div class="tab-pane active" id="inf_basica">
                <div class="form-group">
                  <label for="mod_id_tipo_identificacion" class="col-sm-3 control-label">Tipo documento:</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="mod_id_tipo_identificacion" name="mod_id_tipo_identificacion"
                      required>
                      <option value="">Selecciona un tipo de identificación</option>
                      <?php
                      while ($row = mysqli_fetch_assoc($tipo_identificacion_query)) {
                        echo "<option value='{$row['tipo_identificacion']}'>{$row['DESCRIPCION_tipo_identificacion']}</option>";
                      }
                      ?>
                    </select>
                  </div>

                  <label for="mod_documento" class="col-sm-3 control-label">Documento:</label>
                  <div class="col-sm-8">
                    <input type="number" class="form-control" id="mod_documento" name="mod_documento"
                      placeholder="Documento de identidad (sin .)" required>
                    <input type="hidden" id="mod_id" name="mod_id">
                  </div>

                  <label for="mod_primer_nombre" class="col-sm-3 control-label">1er Nombre:</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="mod_primer_nombre" name="mod_primer_nombre"
                      placeholder="Primer nombre" required>
                  </div>

                  <label for="mod_segundo_nombre" class="col-sm-3 control-label">2do Nombre:</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="mod_segundo_nombre" name="mod_segundo_nombre"
                      placeholder="Segundo nombre">
                  </div>

                  <label for="mod_primer_apellido" class="col-sm-3 control-label">1er Apellido:</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="mod_primer_apellido" name="mod_primer_apellido"
                      placeholder="Primer apellido" required>
                  </div>

                  <label for="mod_segundo_apellido" class="col-sm-3 control-label">2do Apellido:</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="mod_segundo_apellido" name="mod_segundo_apellido"
                      placeholder="Segundo apellido">
                  </div>

                  <label for="mod_estado" class="col-sm-3 control-label">Estado:</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="mod_estado" name="mod_estado">
                      <option value="0">Inactivo</option>
                      <option value="1">Activo</option>
                    </select>
                  </div>

                  <label for="mod_id_tipo_empleado" class="col-sm-3 control-label">Tipo empleado:</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="mod_id_tipo_empleado" name="mod_id_tipo_empleado" required
                      onchange="checkSustituto()">
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

              <div class="tab-pane" id="info_adicional">
                <div class="form-group">

                <label for="mod_id_cargo" class="col-sm-3 control-label">Cargo:</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="mod_id_cargo" name="mod_id_cargo" required>
                      <option value="">Selecciona un cargo</option>
                      <?php
                      while ($row24 = mysqli_fetch_assoc($cargo_query)) {
                        echo "<option value='{$row24['id']}'>{$row24['descripcion']}</option>";
                      }
                      ?>
                    </select>
                  </div>

                  <label for="mod_sexo" class="col-sm-3 control-label">Sexo:</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="mod_sexo" name="mod_sexo">
                      <option value="">Selecciona un valor</option>
                      <option value="M">Masculino</option>
                      <option value="F">Femenino</option>
                      <option value="O">Otro</option>
                    </select>
                  </div>

                  <label for="mod_telefono" class="col-sm-3 control-label">Teléfono:</label>
                  <div class="col-sm-8">
                    <input type="number" class="form-control" id="mod_telefono" name="mod_telefono" placeholder="Teléfono"
                      required>
                  </div>

                  <label for="mod_direccion" class="col-sm-3 control-label">Dirección:</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="mod_direccion" name="mod_direccion"
                      placeholder="Dirección" required>
                  </div>

                  <label for="mod_cumpleanos" class="col-sm-3 control-label">Cumple:</label>
                  <div class="col-sm-8">
                    <input type="date" class="form-control" id="mod_cumpleanos" name="mod_cumpleanos" required>
                  </div>

                  <label for="mod_correo" class="col-sm-3 control-label">Correo:</label>
                  <div class="col-sm-8">
                    <input type="email" class="form-control" id="mod_correo" name="mod_correo"
                      placeholder="Correo electrónico" required>
                  </div>
                </div>
              </div>

              <div class="tab-pane" id="info_sustituto">
                <div class="form-group">
                  <label for="mod_id_Empleado_sustituto" class="col-sm-3 control-label">Sustituto de:</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="mod_id_Empleado_sustituto" name="mod_id_Empleado_sustituto" required
                      onchange="checkSustituto()">
                      <option value="">Selecciona un empleado para sustituir</option>
                      <?php
                      while ($row3 = mysqli_fetch_assoc($sustitutode_query)) {
                        echo "<option value='{$row3['id']}'>{$row3['nombre_completo']}</option>";
                      }
                      ?>
                    </select>
                  </div>

                  <label for="mod_fecha_sustitucion" class="col-sm-3 control-label">Fecha:</label>
                  <div class="col-sm-8">
                    <input type="date" class="form-control" id="mod_fecha_sustitucion" name="mod_fecha_sustitucion"
                      required>
                  </div>

                  <label for="mod_porcentaje_sustitucion" class="col-sm-3 control-label">Porcentaje:</label>
                  <div class="col-sm-8">
                    <input type="number" class="form-control" id="mod_porcentaje_sustitucion"
                      name="mod_porcentaje_sustitucion" placeholder="Porcentaje sustitución número entre 0 y 100" required
                      min="0" max="100" step="0.01" oninput="validatePorcentaje(this)" maxlength="100">
                  </div>
                </div>
              </div>

              <div class="tab-pane" id="info_pensionado">
                <div class="form-group">
                  <label for="mod_dato_pension" class="col-sm-3 control-label">Dato pesión:</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="mod_dato_pension" name="mod_dato_pension"
                      placeholder="Descripción pensión ">
                  </div>

                  <label for="mod_fecha_pension" class="col-sm-3 control-label">Fecha pensión:</label>
                  <div class="col-sm-8">
                    <input type="date" class="form-control" id="mod_fecha_pension" name="mod_fecha_pension"
                      placeholder="Fecha pensión">
                  </div>
                </div>
              </div>

              <div class="tab-pane" id="info_afiliaciones">
                <div class="form-group">
                  <label for="mod_id_fondo_pension" class="col-sm-3 control-label">Ent. Pensión:</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="mod_id_fondo_pension" name="mod_id_fondo_pension" required>
                      <option value="">Selecciona una entidad de pensión</option>
                      <?php
                      while ($row4 = mysqli_fetch_assoc($afiliacion_query1)) {
                        echo "<option value='{$row4['ID']}'>{$row4['descripcion']}</option>";
                      }
                      ?>
                    </select>
                  </div>

                  <label for="mod_id_eps" class="col-sm-3 control-label">Ent. Salud:</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="mod_id_eps" name="mod_id_eps" required>
                      <option value="">Selecciona una entidad de salud</option>
                      <?php
                      while ($row5 = mysqli_fetch_assoc($afiliacion_query2)) {
                        echo "<option value='{$row5['ID']}'>{$row5['descripcion']}</option>";
                      }
                      ?>
                    </select>
                  </div>
                  <!--
                  <label for="mod_id_fondo_cesantias" class="col-sm-3 control-label">Ent. Cesantías:</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="mod_id_fondo_cesantias" name="mod_id_fondo_cesantias" required>
                      <option value="">Selecciona una entidad de cesantias</option>
                      <?php
                      while ($row6 = mysqli_fetch_assoc($afiliacion_query3)) {
                        echo "<option value='{$row6['ID']}'>{$row6['descripcion']}</option>";
                      }
                      ?>
                    </select>
                  </div>

                  <label for="mod_id_arl" class="col-sm-3 control-label">Ent. ARL:</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="mod_id_arl" name="mod_id_arl" required>
                      <option value="">Selecciona una entidad de riesgos</option>
                      <?php
                      while ($row7 = mysqli_fetch_assoc($afiliacion_query4)) {
                        echo "<option value='{$row7['ID']}'>{$row7['descripcion']}</option>";
                      }
                      ?>
                    </select>
                  </div>


                  <label for="mod_id_riesgo_arl" class="col-sm-3 control-label">Clase riesgos:</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="mod_id_riesgo_arl" name="mod_id_riesgo_arl" required>
                      <option value="">Selecciona tipo de riesgos</option>
                      <?php
                      while ($row8 = mysqli_fetch_assoc($tiporiesgo_query)) {
                        echo "<option value='{$row8['id']}'>{$row8['descripcion']}</option>";
                      }
                      ?>
                    </select>
                  </div>

                  <label for="mod_id_caja_compensacion" class="col-sm-3 control-label">Caja de compensación:</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="mod_id_caja_compensacion" name="mod_id_caja_compensacion" required>
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
              <button type="submit" class="btn btn-primary" id="actualizar_datos">Actualizar datos</button>
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

  function checkSustituto() {
    var tipoEmpleado = document.getElementById('mod_id_tipo_empleado').value;
    console.log("Tipo empleado seleccionado: ", tipoEmpleado); // <- Añadir esta línea para depurar
    // Ocultar las pestañas de sustituto y pensionado por defecto
    document.getElementById('tab_sustituto').style.display = 'none';
    document.getElementById('tab_pensionado').style.display = 'none';

    if (parseInt(tipoEmpleado) === 3) { // Sustituto
      document.getElementById('tab_sustituto').style.display = 'block';
      $('.nav-tabs a[href="#info_sustituto"]').tab('show'); // Mostrar la pestaña de sustituto
      
      mod_dato_pension.required = false; // No es requerido
      mod_dato_pension.disabled = true;  // Deshabilitado
      mod_dato_pension.value = ""; // Limpiar el campo si tiene algún valor
      
      mod_fecha_pension.required = false; // No es requerido
      mod_fecha_pension.disabled = true;  // Deshabilitado
      mod_fecha_pension.value = ""; // Limpiar el campo si tiene algún valor

      mod_porcentaje_sustitucion.required = true; // Es requerido
      mod_porcentaje_sustitucion.disabled = false;  // habilitado

      mod_fecha_sustitucion.required = true; // es requerido
      mod_fecha_sustitucion.disabled = false;  // habilitado
   
      mod_id_Empleado_sustituto.required = true; //es requerido
      mod_id_Empleado_sustituto.disabled = false;  // habilitado


    } else if (parseInt(tipoEmpleado) === 1) { // Pensionado
      document.getElementById('tab_pensionado').style.display = 'block';
      $('.nav-tabs a[href="#info_pensionado"]').tab('show'); // Mostrar la pestaña de pensionado
      
      mod_porcentaje_sustitucion.required = false; // No es requerido
      mod_porcentaje_sustitucion.disabled = true;  // Deshabilitado
      mod_porcentaje_sustitucion.value = ""; // Limpiar el campo si tiene algún valor
      
      mod_fecha_sustitucion.required = false; // No es requerido
      mod_fecha_sustitucion.disabled = true;  // Deshabilitado
      mod_fecha_sustitucion.value = ""; // Limpiar el campo si tiene algún valor

      mod_id_Empleado_sustituto.required = false; // No es requerido
      mod_id_Empleado_sustituto.disabled = true;  // Deshabilitado
      mod_id_Empleado_sustituto.value = ""; // Limpiar el campo si tiene algún valor

      mod_dato_pension.required = true; // es requerido
      mod_dato_pension.disabled = false;  // habilitado
  
      mod_fecha_pension.required = true; // es requerido
      mod_fecha_pension.disabled = false;  // habilitado


    }else{
      mod_dato_pension.required = false; // No es requerido
      mod_dato_pension.disabled = true;  // Deshabilitado
      mod_dato_pension.value = ""; // Limpiar el campo si tiene algún valor
      
      mod_fecha_pension.required = false; // No es requerido
      mod_fecha_pension.disabled = true;  // Deshabilitado
      mod_fecha_pension.value = ""; // Limpiar el campo si tiene algún valor

      mod_porcentaje_sustitucion.required = false; // No es requerido
      mod_porcentaje_sustitucion.disabled = true;  // Deshabilitado
      mod_porcentaje_sustitucion.value = ""; // Limpiar el campo si tiene algún valor
      
      mod_fecha_sustitucion.required = false; // No es requerido
      mod_fecha_sustitucion.disabled = true;  // Deshabilitado
      mod_fecha_sustitucion.value = ""; // Limpiar el campo si tiene algún valor

      mod_id_Empleado_sustituto.required = false; // No es requerido
      mod_id_Empleado_sustituto.disabled = true;  // Deshabilitado
      mod_id_Empleado_sustituto.value = ""; // Limpiar el campo si tiene algún valor

    }
  }

  $('#myModal2').on('shown.bs.modal', function () {
    checkSustituto(); // Ejecuta la función al mostrar el modal
    $('.nav-tabs a[href="#inf_basica"]').tab('show');
  });
  

</script>