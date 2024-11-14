<?php
session_start();
  if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: ../../../login.php");
    exit;
        }
        if($_SESSION['perfil'] != 'Administrador')
        {
          header("location: ../../../login.php");
        }

        

  $active_new="active";
  $active_solicitud="";
  $active_clientes="";
  $active_usuarios="";  
  $title="Solicitud";
  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <?php include("../../../plantilla/head.php");?>
    <script src="../../../lib/js/jquery.js?v=<?php echo str_replace('.', '', microtime(true)); ?>"></script>
    <script src="../../../lib/jquery-ui.min.js?v=<?php echo str_replace('.', '', microtime(true)); ?>"></script>  
     <script src="../../../lib/jquery/jquery-2.2.3.min.js"></script>      
    <script src="../../../lib/js/jquery.dataTables.min.js?v=<?php echo str_replace('.', '', microtime(true)); ?>"></script>
    <script src='../../../lib/data_table.js?v=<?php echo str_replace('.', '', microtime(true)); ?>'></script>
    
     <script src='js/gestion.js?v=<?php echo str_replace('.', '', microtime(true)); ?>'></script>
      <script src='js/modal_ver.js?v=<?php echo str_replace('.', '', microtime(true)); ?>'></script>
      <link href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css" />
     <style>
             #pre-load-web {
                width:100%;
                position:absolute;
                background:rgba(0,0,0,0.5);
                left:0px;
                top:0px;
                z-index:100000
            }
            #pre-load-web #imagen-load{
                left:50%;
                margin-left:-30px;
                position:absolute
            }
            #content{
                padding-top: 15%;
                padding-left: 20%;
                padding-right: 20%;
                text-align: center;
            }
         
            .dataTables_filter label{
                display:block !important;
            }
            #myTable_paginate{
                text-align: -webkit-center;
            }
            #myTable_info{
                font-weight: bold;
            }
           /* .panel-body {
            height: 500px;
            }*/
        </style>
  </head>
  <body>
  <?php
  include("../../../plantilla/navbar.php"); //var_dump($_SESSION['user_id']) ;
  ?>  
<div class="container-fluid">
             <div class="col-md-4">
                <div class="panel panel-primary">
                    <div class="panel-heading"><h5>Encuestas</h5></div>
                    <div class="panel-body">
                        <form id="form_encuesta" action="clases/control_crud.php">
                          <div class="col-md-12">
                          <input type="text" name="descripcion_encuesta" id="descripcion_encuesta" placeholder="descripion">
                          </div>
                              
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-floppy-disk"></i> Enviar Datos</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="panel panel-primary">
                    <div class="panel-heading"><h5>Preguntas</h5></div>
                    <div class="panel-body">
                        <form id="form_pregunta" action="clases/control_crud.php">
                          <div class="col-md-12" class="form-control">
                              <label for="id_encuesta">Elija la encuesta:</label><br>
                               <select id="id_encuesta" name="id_encuesta" >
                                
                               </select><br>                        
                            </div> 
                          <div class="col-md-12">
                          <textarea class="col-md-12" type="text" name="descripcion_pregunta" id="descripcion_pregunta" placeholder="descripion" cols="3"> </textarea>
                          <input type="number" name="orden" id="orden" placeholder="orden">
                          </div>
                          <div class="col-md-12" class="form-control">
                              <label for="id_encuesta">Tipo:</label><br>
                               <select id="tipo" name="tipo" >
                                <option value="con respuesta predefinida">Con RTA predefinida</option>
                                <option value="con respuesta libre">Con RTA libre</option>
                               </select><br>                        
                            </div> 
                              
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success"><i class="glyphicon glyphicon-floppy-disk"></i> Enviar Datos</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
              <!--  <div class="col-md-8">
                <div class="panel panel-primary">
                   <div class="panel-heading"><h5>Historial PQR</h5></div>
                    <div class="panel-body">
                        <div class="table-responsive"> 
                         <div id="ver_cargas"></div>
                       </div>
                    </div>
                </div>
            </div> -->
</div>

  <?php
    include '../../../plantilla/footer1.php';
  ?>
  <script src="../../../lib/bootbox.min.js"></script>
</body>
</html>


