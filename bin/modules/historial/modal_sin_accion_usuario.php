<div class="modal fade" id="myModalsinAccion" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header"> 
 
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Descripcion Estado Solicitud</h4>
            <br>
              <div class="bootstrap-iso">
                <div class="container-fluid">
                  <div class="row">   
                    <div class="form-group ">
                      <label for="descripcion_estado">Descripcion Solicitud</label>
                      <textarea class="form-control" rows="2" id="descripcion_solsin" name="descripcion" disabled></textarea><br>   
                      <label for="descripcion_solicitud">Descripcion del estado</label>
                      <textarea class="form-control" rows="2" id="descripcionsin" name="descripcion" disabled></textarea><br> 
                      <label for="radios">Se soluciono la solicitud?</label><br> 
                      <input type="radio" id="estadosi"  name = "estado" value="3"> Si</input>             
                      <input type="radio" id="estadono" name = "estado" value="2" checked> No</input>     
                      <input type="hidden" id="id_solsin">
                      <input type="hidden" id="idsin">               
                    </div>
                      <div class="modal-footer" >         
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>         
                   
                      </div>        
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
</div>