<div class="modal fade" id="myModalSol" role="dialog">
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
                              <div class="col-md-3">
                              <label for="Identificacion">Identificación</label>
                              <input class="form-control" rows="2" id="Identificacion_user" name="Identificacion_user" disabled></input><br> 
                              </div>
                              <div class="col-md-4">
                              <label for="Correo">Correo</label>
                              <input class="form-control" rows="2" id="Correo_user" name="Correo_user" disabled></input><br> 
                              </div>
                              <div class="col-md-5">
                              <label for="Nombre">Nombre</label>
                              <input class="form-control" rows="2" id="Nombre_user" name="Nombre_user" disabled></input><br> 
                              </div>
                              <div class="col-md-12">
                              <label for="descripcion_estado">Descripción Solicitud</label>
                              <textarea class="form-control" rows="2" id="descripcion_soli" name="descripcion" disabled></textarea><br>   
                              <label for="descripcion_solicitud">Descripcion del estado</label>
                              <textarea class="form-control" rows="2" id="descripcion" name="descripcion" disabled></textarea><br> 

                              <label for="descripcion_solucion">Nuevo Estado</label>
                              <textarea class="form-control" rows="4" id="nuevo_estado" name="nuevo_estado"></textarea><br> 
                              <label for="radios">¿Se soluciono la solicitud?</label><br> 
                              <input type="radio" id="estadosi"  name = "estado" value="3"> Si</input>             
                              <input type="radio" id="estadono" name = "estado" value="2" checked> No</input>     
                              <input type="hidden" id="id_sol">
                              <input type="hidden" id="id">               
                               </div>
                               <div class="modal-footer" >         
                                  <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>          
                                  <button class="btn btn-primary " id= "btn_save" name="save" type="submit">Guardar Cambios</button>           
                               </div>   
                               </div>      
                      </div>
                  </div>
              </div>
        </div>
      </div>
    </div>
</div>


