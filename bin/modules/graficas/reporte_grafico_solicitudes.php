<?php
session_start();
  if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: ../../../login.php");
    exit;
        }
        if(($_SESSION['perfil'] != 'Administrador') AND  ($_SESSION['perfil'] != 'Empleado')
        AND  ($_SESSION['perfil'] != 'Gerente') )
        {
          header("location: ../../../login.php");
        }

        

  $active_new="active";
  $active_solicitud="";
  $active_clientes="";
  $active_usuarios="";  
  $title="Filtros Reporte";
  
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
    
      <script src='js/reporte_grafico_solicitudes.js?v=<?php echo str_replace('.', '', microtime(true)); ?>'></script>
      <script src='js/modal_ver.js?v=<?php echo str_replace('.', '', microtime(true)); ?>'></script>
      <link href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css" />
      <!-- <script src="../../../lib/code/js/highcharts.js"></script>
      
      <script src="../../../lib/code/modules/data.js"></script>
<script src="../../../lib/code/modules/drilldown.js"></script> -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<script src="../../../lib/code/modules/exporting.js"></script>
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
  <div class="row">
            <div class="col-md-4">
              <label for="tipo">Entre:</label>
                               <input class="col-md-12" type="date" name="fecha_inicial" id="fecha_inicial"> 
            </div>
             <div class="col-md-4">
              <label for="tipo">y:</label>
                               <input class="col-md-12" type="date" name="fecha_final" id="fecha_final">
            </div>
            <div class="col-md-2">
              <button type="button" class="btn btn-success" id="datos_solicitudes"><i class="glyphicon glyphicon-floppy-disk"></i> Consultar Datos</button>
            </div>
  </div>

  <div class="row"><br><br></div>

 <div class="row">
            <div class="col-md-2">
            </div>
            <div class="col-md-8 graficas_solicitudes">
              <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto">
                
              </div>
            </div>
            <div class="col-md-2">
            </div>
  </div>
               
</div>


  <?php
    include '../../../plantilla/footer1.php';
  ?>
  <script src="../../../lib/bootbox.min.js"></script>
</body>
</html>


