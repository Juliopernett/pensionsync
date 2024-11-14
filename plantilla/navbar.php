
  <?php
  if(!isset($_SESSION)) { 
      session_start(); 
  }

  include '../../../core.php';
        $db = App::$base;
         $sql = "SELECT count(id_notificacion) as num from gt_notificacion where estado = 1";
         $rs = $db->dosql($sql, array());
         $res = $rs->fields['num'];
         $_SESSION['num'] = $res;
      
		if (isset($title))
		{
	?>

    <div class="container-fluid">
      <div class="row"> 
        <div class="col-md-3">
          <img src="../../../imagenes/PensionSync.jpg" width="110"  >
          <img src="../../../imagenes/LogoDDL2014.png" width="110"  >

        </div>

        <div class="col-md-9">
          <nav class="navbar navbar-default" >
            <div class="container-fluid">
              
              <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                  <li class="nav-item <?php echo $active_sesion;?>"><a href="../inicio/inicio.php"><i class='glyphicon glyphicon-user'></i> <?php echo $_SESSION['user_name'] ?></a></li>
                  <?php
                  if($_SESSION['perfil'] == 'Administrador' or $_SESSION['perfil'] == 'Empleado' or $_SESSION['perfil'] == 'Gerente')
                  /*../solicitud/solicitud.php*/
                  {
                  ?>
                    <li><a href="../empleados/empleados.php"></i> Empleados</a></li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        Procesos <b class="caret"></b>
                      </a>
                      <ul class="dropdown-menu">
                        <li><a href="../resoluciones/resoluciones.php"></i> Resoluciones</a></li>
                        <li class="<?php echo $active_new;?>"><a href="../novedades/novedades.php"></i> Novedades</a></li> 
                      </ul>
                    </li> 

                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        Paramétricas <b class="caret"></b>
                      </a>
                      <ul class="dropdown-menu">
                        <li class="nav-item "><a href="../usuarios/usuarios.php">Usuarios</a></li>
                        <li><a href="../cargos/cargos.php">Cargos</a></li>
                        <li><a href="../entidades/entidades.php">Entidades</a></li>
                        <li><a href="../conceptos/conceptos.php">Conceptos</a></li>
                      </ul>
                    </li>
                  
                  <?php
                  }
                      ?>
                    
                      <li><a href="../../../login.php?logout"><i class='glyphicon glyphicon-off'></i> Cerrar Sesion</a></li>
                    </li>
                  </ul>
                    <ul class="nav navbar-nav navbar-right">
                    </ul>
                  </div>
            </div>
          </nav>
        </div>
      </div>


	<?php
		}
	?>