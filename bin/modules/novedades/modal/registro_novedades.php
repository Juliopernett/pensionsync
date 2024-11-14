<?php
if (isset($con)) {
  //$empleado_query = mysqli_query($con, "SELECT id, CONCAT(UPPER(LEFT(nombre_completo, 1)), LOWER(SUBSTRING(nombre_completo, 2))) as nombre_completo FROM gt_empleado;");
  $empleado_query = mysqli_query($con, "SELECT id, CONCAT(UPPER(LEFT(COALESCE(nombre_completo, ''), 1)), LOWER(SUBSTRING(COALESCE(nombre_completo, ''), 2))) as nombre_completo FROM gt_empleado;");

  $concepto_query = mysqli_query($con, "SELECT id, CONCAT(Codigo, ' - ', UPPER(LEFT(descripcion, 1)), LOWER(SUBSTRING(descripcion, 2))) as concepto FROM pm_conceptos;");

  $resolucion_query = mysqli_query($con, "SELECT id, CONCAT(numero, ' del  ', fecha_resolucion) as resolucion FROM gt_resoluciones;");

  ?>
  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-plus'></i> Crear nueva novedad
          <p id="new_id_display" style="margin: 0; opacity: 0.5; background-color: #f7f7f7; color: #6c757d;"></p>
          </h4>
        </div>
        <div class="modal-body">
          <!-- Formulario -->
          <form class="form-horizontal" method="post" id="guardar_novedad" name="guardar_novedad" onsubmit="limpiarValor(document.getElementById('new_valor'));">

            <div id="resultados_ajax"></div>
            <!-- Contenido de las pestañas -->
            <div class="tab-content">

              <div class="form-group">
                <label for="new_id_empleado" class="col-sm-3 control-label">Empleado:</label>
                <input type="hidden" id="new_id_periodo" name="new_id_periodo">
                <div class="col-sm-8">
                  <select class="form-control" id="new_id_empleado" name="new_id_empleado" required>
                    <option value="">Seleccione un empleado</option>
                    <?php
                    while ($row1 = mysqli_fetch_assoc($empleado_query)) {
                      echo "<option value='{$row1['id']}'>{$row1['nombre_completo']}</option>";
                    }
                    ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="new_id_concepto" class="col-sm-3 control-label">Concepto:</label>
                <div class="col-sm-8">
                  <select class="form-control" id="new_id_concepto" name="new_id_concepto" required>
                    <option value="">Seleccione un concepto</option> 
                    <?php
                    while ($row2 = mysqli_fetch_assoc($concepto_query)) {
                      echo "<option value='{$row2['id']}'>{$row2['concepto']}</option>";
                    }
                    ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="new_valor" class="col-sm-3 control-label">Valor:</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="new_valor" name="new_valor"
                        placeholder="Valor de la novedad" required
                        oninput="formatCurrency(this)" />
                </div>
              </div>

              <div class="form-group">
                <label for="new_resolucion_id" class="col-sm-3 control-label">Resolución:</label>
                <div class="col-sm-8">
                  <select class="form-control" id="new_resolucion_id" name="new_resolucion_id">
                    <option value="">Seleccione una resolución (opcional)</option>
                    <?php
                    while ($row3 = mysqli_fetch_assoc($resolucion_query)) {
                      echo "<option value='{$row3['id']}'>{$row3['resolucion']}</option>";
                    }
                    ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
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

function formatCurrency(input) {
  let value = input.value;
  
  // Eliminar cualquier carácter no numérico excepto el punto decimal
  value = value.replace(/[^0-9.]/g, '');
  
  // Si hay más de un punto, eliminar todos menos el primero
  const parts = value.split('.');
  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ','); // Agregar comas en miles
  value = parts.join('.');

  // Establecer el valor formateado de vuelta en el input
  input.value = value;
}

function limpiarValor(input) {
  // Eliminar las comas del valor antes de enviar al servidor
  let value = input.value;
  value = value.replace(/,/g, ''); // Eliminar todas las comas
  input.value = value; // Actualizar el valor del input sin las comas
}


</script>
