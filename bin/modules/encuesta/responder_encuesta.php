<?php
session_start();
  if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: ../../../login.php");
    exit;
        }
        if($_SESSION['perfil'] != 'Empleado')
        {
          header("location: ../../../login.php");
        }

        

  $active_new="";
  $active_solicitud="";
  $active_clientes="";
  $active_usuarios="";
  $active_responder="active";   
  $title="Encuesta";
  
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
    
     <script src='js/responder.js?v=<?php echo str_replace('.', '', microtime(true)); ?>'></script> 
     
      <script>
      
      </script>
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
      <div class="col-md-12">
          <div class="panel panel-primary">
                <div class="panel-heading"><h5>Encuesta de Satisfaccion</h5></div>
                    <div class="panel-body">
                        <form id="form_responder">
                            <div id="datos"></div>
                        </form>
                          <div class="row">
                            <div class="col-md-2">
                                <button class="btn btn-success" id="responder"><i class="glyphicon glyphicon-floppy-disk"></i> Enviar Datos</button>
                            </div>
                          </div>  
                    </div>
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


<?php
include 'modal_asignaropciones.php';
?>
  <?php
    include '../../../plantilla/footer1.php';
  ?>
  <script src="../../../lib/bootbox.min.js"></script>
</body>
</html>


