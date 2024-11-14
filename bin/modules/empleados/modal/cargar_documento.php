<?php
if (isset($con)) {

  ?>
  <!-- Modal -->
  <div class="modal fade" id="myModalCargaDoc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
              aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Cargar adjuntos de empleado
          <p id="car_id_display" style="margin: 0; opacity: 0.5; background-color: #f7f7f7; color: #6c757d;"></p>
          </h4>
        </div>
        <div class="modal-body">
          <!-- Formulario -->
          <form class="form-horizontal" method="post" id="cargar_adjuntos" name="cargar_adjuntos">
            <div id="resultados_ajax4"></div>
            <br>
            <label for="archivo" class="col-sm-5 control-label">Carga un archivo (.pdf, .doc, .docx, .jpg, .jpeg, .png)</label>
            <div class="col-sm-5">
              <input type="file" name="archivo" id="archivo" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
              <input type="hidden" id="car_id" name="car_id">
            </div>

            <br>  <br>  <br>  
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary" id="subir_archivo">Subir Archivo</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <?php
}
?>

