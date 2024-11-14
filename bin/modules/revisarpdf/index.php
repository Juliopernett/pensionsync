<!DOCTYPE html>
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
        

  $active_new="";
  $active_solicitud="";
  $active_subir="active";
  $active_usuarios="";  
  $title="Subir";
?>
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <?php include("../../../plantilla/head.php");?>
    <script src="../../../lib/js/jquery.js?v=<?php echo str_replace('.', '', microtime(true)); ?>"></script>
    <script src="../../../lib/jquery-ui.min.js?v=<?php echo str_replace('.', '', microtime(true)); ?>"></script>  
     <script src="../../../lib/jquery/jquery-2.2.3.min.js"></script>      
    <script src="../../../lib/js/jquery.dataTables.min.js?v=<?php echo str_replace('.', '', microtime(true)); ?>"></script>
    <script src='../../../lib/data_table.js?v=<?php echo str_replace('.', '', microtime(true)); ?>'></script>
    
     <link href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css" />
     <script type="text/javascript">
     $(document).ready(function() {
     $('#myTable').DataTable({
                sPaginationType: "bootstrap", 
                aLengthMenu: [6],
                language: {sProcessing: "Procesando...",
                    sLengthMenu: "Mostrar _MENU_ registros",
                    sZeroRecords: "No se encontraron resultados",
                    sEmptyTable: "Ningún dato disponible en esta tabla",
                    sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                    sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
                    sInfoPostFix: "",
                    sSearch: "Buscar:",
                    sUrl: "",
                    sInfoThousands: ",",
                    sLoadingRecords: "Cargando...",
                    oPaginate: {
                        sFirst: "Primero",
                        sLast: "Último",
                        sNext: "Siguiente",
                        sPrevious: "Anterior"
                    },
                    oAria: {
                        sSortAscending: ": Activar para ordenar la columna de manera ascendente",
                        sSortDescending: ": Activar para ordenar la columna de manera descendente"
                    }
                }});
            $('.dataTables_filter label').css('display', 'block !important');
            $('.dataTables_filter label input[type="search"]').addClass('form form-control');
            $('input[name="myTable_length"]').addClass('form form-control');
            });

     </script>
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
             <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading"><h5>Ver Archivos</h5></div>
                    <div class="panel-body">
                         <table id="myTable" class="table table-hover table-striped table-bordered table-condensed" cellpadding="0" cellspacing="0" border="1" class="display">
           <thead>
            <tr>
                <th>#</th>
                <th>Titulo</th>
                <th>Descripcion</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Nombre Archivo</th>
                <th>Ver Doc</th>
            </tr>
        </thead>
        <?php
        include 'config.inc.php';
        $db=new Conect_MySql();
        $ses = $_SESSION['user_id'];
                        $sql = "SELECT 
              `solicitud`.`id_solicitud`,
              `solicitud`.`user_id`,
              `tbl_documentos`.`id_documento`,
              `tbl_documentos`.`titulo`,
              `tbl_documentos`.`descripcion`,
              `tbl_documentos`.`nombre_archivo`,
              `users`.`firstname`,
              `users`.`lastname`
            FROM
              `solicitud`
              INNER JOIN `tbl_documentos` ON (`solicitud`.`id_solicitud` = `tbl_documentos`.`id_solicitud`)
              INNER JOIN `users` ON (`solicitud`.`user_id` = `users`.`user_id`)";
            $query = $db->execute($sql);
            while($datos=$db->fetch_row($query)){?>
            <tr>
                <td>PQR #-<?php echo $datos['id_solicitud']; ?></td>
                <td><?php echo $datos['titulo']; ?></td>
                <td><?php echo $datos['descripcion']; ?></td>
                <td><?php echo $datos['firstname']; ?></td>
                <td><?php echo $datos['lastname']; ?></td>
                <td><?php echo $datos['nombre_archivo']; ?></td>
                <td><a href="../subirpdf/archivo.php?id=<?php echo $datos['id_documento']?>" target="iframe_a"><img src="../../../img/pdf.jpg" width="40" height="40" /></a></td>
            </tr>
                
          <?php  } ?>
            
        </table>


                    </div>
                </div>
            </div>


             <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading"><h5>Ver Archivos</h5></div>
                    <div class="panel-body">
                         
                    <iframe height="500px" width="100%" src="" name="iframe_a"></iframe>

                    </div>
                </div>
            </div>
              
          
</div>
<?php
  include '../../../plantilla/footer1.php';
  ?>
    </body>
</html>
