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
          <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Editar concepto
          </h4>
        </div>
        <div class="modal-body">
          <!-- Formulario -->
          <form class="form-horizontal" method="post" id="editar_concepto" name="editar_concepto">
            <div id="resultados_ajax2"></div>


            <!-- Contenido de las pestañas -->
            <div class="tab-content">
                <div class="form-group">
                <label for="mod_codigo" class="col-sm-3 control-label">Código:</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="mod_codigo" name="mod_codigo"
                      placeholder="Código del concepto" maxlength="10" required>
                    <input type="hidden" id="mod_id" name="mod_id">
                  </div>

                  <label for="mod_descripcion" class="col-sm-3 control-label">Descripción:</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" id="mod_descripcion" name="mod_descripcion"
                      placeholder="Descripción del concepto" maxlength="255" required >
                  </div>

                  <label for="mod_tipo_movimiento" class="col-sm-3 control-label">Tipo Movimiento:</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="mod_tipo_movimiento" name="mod_tipo_movimiento">
                      <option value="1">Devengos</option>
                      <option value="2">Descuentos</option>
                      <option value="6">Informativo</option>
                      <option value="7">Aporte Patrono</option>
                      <option value="8">Parafiscales</option>
                    </select>
                  </div>

                  <label for="mod_tipo_concepto" class="col-sm-3 control-label">Tipo concepto:</label>
                  <div class="col-sm-8">
                    <select class="form-control" id="mod_tipo_concepto" name="mod_tipo_concepto">
                      <option value="1">Pensionado</option>
                      <option value="2">Activo</option>
                      <option value="3">Sustituto</option>
                    </select>
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
