<?php
session_start();
  if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: ../../../login.php");
    exit;
        }
     
  $active_new="";
  $active_solicitud="";
  $active_sesion="";
  $active_clientes="";
  $active_usuarios="";  
  $title="Inicio";
  

  require_once ("../../../config/db.php");
  require_once ("../../../config/conexion.php");
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include("../../../plantilla/head.php");?>
    <script src="../../../lib/js/jquery.js?v=<?php echo str_replace('.', '', microtime(true)); ?>"></script>
    <script src="../../../lib/jquery-ui.min.js?v=<?php echo str_replace('.', '', microtime(true)); ?>"></script>
    <script src="../../../lib/jquery/jquery-2.2.3.min.js"></script>

</head>

<body>

    <?php
  include("../../../plantilla/navbar.php");
  ?>

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h5>Bienvenido <?php echo $_SESSION['user_name'].', su perfil es de tipo: '.$_SESSION['perfil'] ?></h5>
            </div>
            <div class="panel-body">
                <center>
                    <h2><b>PensionSync</b></h2>
                </center>
                <div class="col-md-12 ">
                    <div class="panel-heading">
                        <h4><b>Señor usuario</b></h4>
                        <p>Bienvenido al Sistema de regisro de Novedades de Nómina de Pensionados
                        Gestione sus novedades de manera eficiente y segura.</p>
                        <p>Simplificamos la administración de su nómina de pensionados, permitiéndole registrar y gestionar actualizaciones en tiempo real. Nuestra plataforma garantiza precisión, seguridad y accesibilidad para que pueda enfocarse en lo que realmente importa.</p>
                        <p>Las opciones del meú pueden variar de acuerdo a su perfil de usuario.</p>
                        <br><br>
                    </div>
                </div>

                <div class="col-md-3">
                    
                    <a class="btn btn-success" href="../empleados/empleados.php"><i
                            class="glyphicon glyphicon-plus"></i> Registros de empleados</a>
                
                </div>

                <div class="col-md-3">
                    
                    <a class="btn btn-success" href="../resoluciones/resoluciones.php"><i
                            class="glyphicon glyphicon-plus"></i> Registros de resoluciones</a>
                
                </div>

                <div class="col-md-3">
                    
                    <a class="btn btn-success" href="../novedades/novedades.php"><i
                            class="glyphicon glyphicon-plus"></i> Registros de novedad</a>
                
                </div>
            </div>

            <?php

  include '../../../plantilla/footer1.php';
  ?>

</body>

</html>