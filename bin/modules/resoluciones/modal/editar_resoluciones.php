<?php
if (isset($con)) {
 

  ?>
  <!-- Modal -->
  <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Editar resolución
          </h4>
        </div>
        <div class="modal-body">
          <!-- Formulario -->
          <form class="form-horizontal" method="post" id="editar_resolucion" name="editar_resolucion">
            <div id="resultados_ajax2"></div>

            <!-- Contenido de las pestañas -->
            <div class="tab-content">
                <div class="form-group">
                <label for="mod_numero" class="col-sm-3 control-label">Número:</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="mod_numero" name="mod_numero"
                      placeholder="número de resolución" maxlength="10" required>
                    <input type="hidden" id="mod_id" name="mod_id">
                  </div>

                  <label for="mod_detalle" class="col-sm-3 control-label">Detalle:</label>
                  <div class="col-sm-8">
                    <textarea id="mod_detalle" name="mod_detalle" rows="5" maxlength="1000"  style="width: 100%; resize: vertical;" placeholder="Ingresa el detalle aquí..." required></textarea>
                  </div>

                  <label for="mod_fecha_resolucion" class="col-sm-3 control-label">Fecha:</label>
                  <div class="col-sm-8">
                    <input type="date" class="form-control" id="mod_fecha_resolucion" name="mod_fecha_resolucion"
                      placeholder="Fecha de resolución" required >
                  </div>

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
