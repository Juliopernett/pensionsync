<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php
include_once 'config.inc.php';
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
  $active_subir="active";
  $active_usuarios="";  
  $title="Subir";

if (isset($_POST['subir'])) {
    $nombre = $_FILES['archivo']['name'];
    $tipo = $_FILES['archivo']['type'];
    $tamanio = $_FILES['archivo']['size'];
    $ruta = $_FILES['archivo']['tmp_name'];
    $destino = "archivos/" . $nombre;
    if ($nombre != "") {
        if (copy($ruta, $destino)) {
            $titulo= $_POST['titulo'];
            $descri= $_POST['descripcion'];
            $sol = $_POST['id_solicitud'];
            $db=new Conect_MySql();
            $user = $_SESSION['user_id'];
            $sql = "INSERT INTO tbl_documentos(titulo,descripcion,tamanio,tipo,nombre_archivo, id_usuario, id_solicitud) VALUES('$titulo','$descri','$tamanio','$tipo','$nombre','$user','$sol' )";
            $query = $db->execute($sql);
            if($query){
                echo "<script>alert('El archivo se subio correctamente')</script>";
            }
        } else {
            echo "<script>alert('Error')</script>";
        }
    }
}
?>


<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <?php include("../../../plantilla/head.php");?>
    <script src="../../../lib/js/jquery.js?v=<?php echo str_replace('.', '', microtime(true)); ?>"></script>
    <script src="../../../lib/jquery-ui.min.js?v=<?php echo str_replace('.', '', microtime(true)); ?>"></script>  
     <script src="../../../lib/jquery/jquery-2.2.3.min.js"></script>      
    <script src="../../../lib/js/jquery.dataTables.min.js?v=<?php echo str_replace('.', '', microtime(true)); ?>"></script>
    <script src='../../../lib/data_table.js?v=<?php echo str_replace('.', '', microtime(true)); ?>'></script>
    
    
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
                    <div class="panel-heading"><h5>Subir Archivos</h5></div>
                    <div class="panel-body">
                        <form method="post" action="" enctype="multipart/form-data">

                          <div class="col-md-4">
                                 <label for="titulo">Elija la Solicitud:</label>
                                   <select class="form-control" name="id_solicitud">
                                        <?php
                                          include '../../../core.php';
                                           $con = App::$base;
                                           $sql = "SELECT 
                                                   id_solicitud 
                                                   from solicitud 
                                                   where user_id = ?
                                                   order by id_solicitud desc";
                                          $rs = $con->dosql($sql, array($_SESSION['user_id']));
       
                                        while (!$rs->EOF) 
                                             {
                                            ?>                                              
                                            <option value="<?php echo $rs->fields['id_solicitud']; ?>">PQR # <?php echo $rs->fields['id_solicitud']; ?></option>                                     
                                         <?php 
                                           $rs->MoveNext();                                               
                                          } 
                                           ?>
                                      
                                   </select>                  
                            </div> 

                            <div class="col-md-4">
                                 <label for="titulo">Titulo:</label>
                               <input type="text" name="titulo" class="form-control" required>                    
                            </div>  
                            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                            <div class="col-md-4">
                              <label for="descripcion">Descripcion:</label>
                              <textarea class="form-control" rows="1" id="descripcion" name="descripcion" required="true"></textarea><br>
                                </div>                                                              
                                      
                            <div class="col-md-3">
                                <input type="file" class="form-control-file" name="archivo"><br>
                               
                            </div>
                            <div class="col-md-3">
                                 <button type="submit" name="subir" class="btn btn-success"><i class="glyphicon glyphicon-floppy-disk"></i> Subir</button>
                                
                            </div>
                            <div class="col-md-3">
                               Ver Listado  <a href="lista.php"><img src="../../../img/lib.png" width="50" height="50"></a>
                               

                            </div>
                        </form>
                    </div>
                </div>
            </div>
              
          
</div>


<?php
  include '../../../plantilla/footer1.php';
  ?>


    </body>
</html>
