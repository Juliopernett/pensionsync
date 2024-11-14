<?php
if (isset($con)) {
  //$empleado_query = mysqli_query($con, "SELECT id, CONCAT(UPPER(LEFT(nombre_completo, 1)), LOWER(SUBSTRING(nombre_completo, 2))) as nombre_completo FROM gt_empleado;");
  $empleado_query = mysqli_query($con, "SELECT id, CONCAT(UPPER(LEFT(COALESCE(nombre_completo, ''), 1)), LOWER(SUBSTRING(COALESCE(nombre_completo, ''), 2))) as nombre_completo FROM gt_empleado;");

  $concepto_query = mysqli_query($con, "SELECT id, CONCAT(Codigo, ' - ', UPPER(LEFT(descripcion, 1)), LOWER(SUBSTRING(descripcion, 2))) as concepto FROM pm_conceptos;");

  $resolucion_query = mysqli_query($con, "SELECT id, CONCAT(numero, ' del  ', fecha_resolucion) as resolucion FROM gt_resoluciones;");

  ?>
  <!-- Modal -->
  <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Editar novedad
          <p id="mod_id_display" style="margin: 0; opacity: 0.5; background-color: #f7f7f7; color: #6c757d;"></p>
          </h4>
        </div>
        <div class="modal-body">
          <!-- Formulario -->
          <form class="form-horizontal" method="post" id="editar_novedad" name="editar_novedad" onsubmit="limpiarValor(document.getElementById('mod_valor'));">
           
            <div id="resultados_ajax2"></div>

            <!-- Contenido de las pestañas -->
            <div class="tab-content">

              <div class="form-group">
                <label for="mod_id_empleado" class="col-sm-3 control-label">Empleado:</label>
                <div class="col-sm-8">
                  <select class="form-control" id="mod_id_empleado" name="mod_id_empleado" required>
                    <?php
                    while ($row1 = mysqli_fetch_assoc($empleado_query)) {
                      echo "<option value='{$row1['id']}'>{$row1['nombre_completo']}</option>";
                    }
                    ?>
                  </select>
                </div>
                <input type="hidden" id="mod_id" name="mod_id">
              </div>

              <div class="form-group">
                <label for="mod_id_concepto" class="col-sm-3 control-label">Concepto:</label>
                <div class="col-sm-8">
                  <select class="form-control" id="mod_id_concepto" name="mod_id_concepto" required>
                    <?php
                    while ($row2 = mysqli_fetch_assoc($concepto_query)) {
                      echo "<option value='{$row2['id']}'>{$row2['concepto']}</option>";
                    }
                    ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="mod_valor" class="col-sm-3 control-label">Valor:</label>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="mod_valor" name="mod_valor"
                        placeholder="Valor de la novedad" required
                        oninput="formatCurrency(this)">
                </div>
              </div>

              <div class="form-group">
                <label for="mod_resolucion_id" class="col-sm-3 control-label">Resolución:</label>
                <div class="col-sm-8">
                  <select class="form-control" id="mod_resolucion_id" name="mod_resolucion_id">
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
                <label for="mod_estado" class="col-sm-3 control-label">Estado:</label>
                <div class="col-sm-8">
                  <select class="form-control" id="mod_estado" name="mod_estado">
                    <option value="0">Inactivo</option>
                    <option value="1">Activo</option>
                  </select>
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
  
$('#myModal2').on('shown.bs.modal', function () {
  var valor = $('#mod_valor').val();
  if (valor) {
    formatCurrency($('#mod_valor')[0]);
  }
});

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
