<?php
session_start();
include '../../../../core.php';
include_once 'enviar_correos.php';
include_once Config::$home_bin.Config::$ds.'db'.Config::$ds.'active_table.php'; 
class Seguimiento extends ADOdb_Active_Record{}
class NotificacionAdmin extends ADOdb_Active_Record{}


class regSolicitud
{


    public function reg_seguimiento($id,$solicitud ,$estado, $descripcion)
    {
      date_default_timezone_set('America/Bogota');
        $fecha = date('Y-m-d');
        $hora =  date ("h:i:s");
        $currentDateTime=date('m/d/Y H:i:s');
        $newDateTime = date('h:i A', strtotime($currentDateTime));
        $reg              = new Seguimiento('seguimiento_solicitud');
       // $reg->load("id_seguimiento = {$id}");
        $reg->id_solicitud      = $solicitud;
        $reg->fecha = $fecha;
        $reg->hora = $newDateTime;
        $reg->id_estado = $estado;
        $reg->descripcion_estado = $descripcion;
        $reg->Save();

        $disc       = new listCorreo();
        $estado_correo = "";
        if( $estado== 2)
        {
          $estado_correo = "EN TRAMITE";
        }
        if( $estado== 3)
        {
          $estado_correo = "RESUELTA";
        }
        if( $estado== 4)
        {
          $estado_correo = "TERMINADA";
        }
        $disc->enviarCorreo($solicitud, $estado_correo);
       /* if ($estado != 1) {
          $disc->enviarCorreo($solicitud, $estado_correo);
        }*/

        if($this->buscarNotificacion($solicitud) != -1)
        {
           $this->editNotificacion($this->buscarNotificacion($solicitud));
        }

        
    }

    public function editNotificacion($id)
    {
      
        $reg              = new NotificacionAdmin('notificacion');
        $reg->load("id_notificacion = {$id}");
        $reg->estado      = 0;     
        $reg->Save();
        
    }

    public function buscarNotificacion($id)
  {
    $db = App::$base;
        $sql = "SELECT 
                  id_solicitud, id_notificacion
                FROM
                  notificacion                  
                  WHERE id_solicitud = ?
                  AND id_solicitud != 0";
                    $rs = $db->dosql($sql, array($id));
                    if($rs->fields["id_solicitud"] == "NULL" || $rs->fields["id_solicitud"] == "")
                  return -1;
                else
                  return $rs->fields["id_notificacion"];

  }


public function listSolicitud($id)
{
	$con = App::$base;
    $sql = "SELECT 
            `users`.`user_id`,
            `users`.`firstname`,
            `users`.`lastname`,
            `tipo_solicitud`.`descripcion` AS tipo,
            `solicitud`.`fecha`,
            `solicitud`.`id_solicitud`,
            CONCAT('PQR # ',`solicitud`.`id_solicitud`) AS numero,
            `solicitud`.`estado_solicitud`,
            `seguimiento_solicitud`.`fecha` as fecha1,
            `seguimiento_solicitud`.`hora` as hora,
            `estado`.`descripcion` as estado_descripcion,
             `seguimiento_solicitud`.`id_seguimiento`,        
             
               \"
              <button type=\'button\' class=\'btn btn-primary btn-sm btn_sol\' data-title=\'Edit\' data-toggle=\'modal\' data-target=\'#myModalSol\' >
               <span class=\'glyphicon glyphicon-edit\'></span></button>
               </div>
                \" 
               as editar,
                \"
              <button type=\'button\' class=\'btn btn-info btn-sm btn_sin\' data-title=\'Editsin\' data-toggle=\'modal\' data-target=\'#myModalsinAccion\' >
               <span class=\'glyphicon glyphicon-book\'></span></button>
               </div>
                \" 
               as editar_sin_accion               
            FROM
              `solicitud`
              INNER JOIN `users` ON (`solicitud`.`user_id` = `users`.`user_id`)
              INNER JOIN `tipo_solicitud` ON (`solicitud`.`id_tiposolicitud` = `tipo_solicitud`.`id_tiposolicitud`)
              INNER JOIN `seguimiento_solicitud` ON (`solicitud`.`id_solicitud` = `seguimiento_solicitud`.`id_solicitud`)
              INNER JOIN `estado` ON (`estado`.`id_estado` = `seguimiento_solicitud`.`id_estado`)
              WHERE             
              `seguimiento_solicitud`.`id_solicitud` = ?
              order by `seguimiento_solicitud`.`id_seguimiento` asc
               ";

		$rs = $con->dosql($sql, array($id));
        $tabla = '<table id="myTable" class="table table-hover table-striped table-bordered table-condensed" cellpadding="0" cellspacing="0" border="1" class="display" >
                        <thead>
                        <tr>
                        <th id="yw9_c0">#</th>
                        <th id="yw9_c0">No</th>
                         <!--  <th id="yw9_c1">Nombres</th>
                        <th id="yw9_c2">Apellidos</th>
                     <th id="yw9_c4">Descripcion</th>-->
                        <th id="yw9_c5">Fecha</th>
                        <th id="yw9_c6">Hora</th>
                        <th id="yw9_c7">Estado</th>
                        <th id="yw9_c8">Seg</th>
                       
                   
                        </tr>
                        </thead>
                        <tbody>';
                        $indexActual= 0;
		          while (!$rs->EOF) 
                   {
                    $indexActual++;
                   // $indexActual=$rs->fields['_GRID_HEADER_06_01'];
                  // $indexActual= $rs->recordCount();
                     //var_dump($this->solo_leer_respuesta($rs->fields['id_solicitud']));
                    $clic = 'onclick="editarSin('.$rs->fields['id_seguimiento'].','.$rs->fields['id_solicitud'].')"';
                    $edit = utf8_encode($rs->fields['editar_sin_accion']);
                     if($indexActual == $this->solo_leer_respuesta($rs->fields['id_solicitud']))
                     {
                       $clic = 'onclick="editar('.$rs->fields['id_seguimiento'].','.$rs->fields['id_solicitud'].')"';
                       $edit = utf8_encode($rs->fields['editar']);
                     }
                    if ($rs->fields['estado_descripcion']=='ENVIADA'){
                          $text_estado="Enviada";
                          $label_class='label-primary';}
                      if($rs->fields['estado_descripcion']=='EN TRAMITE'){
                          $text_estado="En Tramite";
                          $label_class='label-warning';}
                        if($rs->fields['estado_descripcion']=='RESUELTA'){
                            $text_estado="Resuelta";
                            $label_class='label-info';}
                            if($rs->fields['estado_descripcion']=='TERMINADA'){
                              $text_estado="Terminada";
                              $label_class='label-success';}

                   	$tabla.='<tr > 

                              <td>                            
                                '.utf8_encode($rs->fields['id_seguimiento']).'
                            </td>
                            <td>                            
                                '.utf8_encode($rs->fields['numero']).'
                            </td>
                          <!--  <td>                            
                                '.utf8_encode($rs->fields['firstname']).'
                            </td>
                            <td>                            
                                '.utf8_encode($rs->fields['lastname']).'
                            </td>
                            <td>                            
                                '.utf8_encode($rs->fields['tipo']).'
                            </td>-->
                            <td>                            
                                '.utf8_encode($rs->fields['fecha1']).'
                            </td> 
                             <td>                            
                                '.utf8_encode($rs->fields['hora']).'
                            </td> 
                            <td align="center">                            
                             <span class="label '.$label_class.'">'.$text_estado.'</span>
                             </td>  
                                                    
                            <td width= "30" '.$clic.'>                            
                                '.$edit.'
                            </td>
                          

                            ' ;                                                                               
                            
            $tabla.= '</tr>';                                     
	
	               $rs->MoveNext();	    
                   }  
            
        $tabla.="</tbody></table>";
        return $tabla;

}

public function solo_leer_respuesta($solicitud)
{
  $db = App::$base;
        $sql = "SELECT count(id_seguimiento) as todos,max(id_seguimiento) as num 
                from seguimiento_solicitud 
                where id_solicitud = ?";
    $rs = $db->dosql($sql, array($solicitud));
       return $rs->fields['todos'] ;
}

public function listSolicitud2($estado)
{
  $con = App::$base;
    $sql = "SELECT 
            `solicitud`.`id_solicitud`,
            `tipo_solicitud`.`descripcion`,
            CONCAT('PQR # ',`solicitud`.`id_solicitud`) AS numero,
            `users`.`firstname`,
            `users`.`lastname`,
            CONCAT(`users`.`firstname`, ' ',
            `users`.`lastname`) AS nombre_completo,
            `solicitud`.`fecha`,
            `solicitud`.`estado_solicitud`, 
            ifnull(`tbl_documentos`.`id_documento`, -1) as id_documento,                   
               \"
              <button type=\'button\' class=\'btn btn-info btn-sm btn_sol\' data-title=\'Edit\'>
               <span class=\'glyphicon glyphicon-hand-right\'></span></button>
               </div>
                \" 
               as ir               
            FROM
          `tbl_documentos`
          RIGHT JOIN `solicitud` ON (`tbl_documentos`.`id_solicitud` = `solicitud`.`id_solicitud`)
          INNER JOIN `tipo_solicitud` ON (`solicitud`.`id_tiposolicitud` = `tipo_solicitud`.`id_tiposolicitud`)
          INNER JOIN `users` ON (`solicitud`.`user_id` = `users`.`user_id`)
            WHERE estado_solicitud = ?
            ";

    $rs = $con->dosql($sql, array($estado));
        $tabla = '<table id="myTable1" class="table table-hover table-striped table-bordered table-condensed" cellpadding="0" cellspacing="0" border="2" class="display" >
                        <thead>
                        <tr>
                        <th id="yw9_c0">#</th>
                        <th id="yw9_c1">Tipo Solicitud</th>
                        <th id="yw9_c2">Usuario</th>
                        <th id="yw9_c3">Fecha</th>
                        <th id="yw9_c4">Estado</th>
                        <th id="yw9_c4">Archivos</th>
                        <th id="yw9_c5">Ver</th>                   
                        </tr>
                        </thead>
                        <tbody>';
              while (!$rs->EOF) 
                   {
                    if ($rs->fields['estado_solicitud']=='Activa'){
                          $text_estado="Activa";
                          $label_class='label-primary';}
                      
                      if($rs->fields['estado_solicitud']=='Inactiva'){
                          $text_estado="Inactiva";
                          $label_class='label-danger';}

                          if($rs->fields['estado_solicitud']=='Espera'){
                            $text_estado="En espera";
                            $label_class='label-info';}

                            if($rs->fields['id_documento'] != -1) 
                            {
                              $case = '<center><a href="../subirpdf/archivo.php?id='.$rs->fields['id_documento'].'" target="iframe_a">
                             <img src="../../../img/pdf.jpg" width="40" height="40" /></a></center>';

                            }
                            else
                            {
                            $case = '<center><span class="label label-info">Sin Archivo</span></center>';
                            }

                    $tabla.='<tr >  
                            <td>                            
                                '.utf8_encode($rs->fields['numero']).'
                            </td>
                            <td>                            
                                '.utf8_encode($rs->fields['descripcion']).'
                            </td>
                            <td>                            
                                '.utf8_encode($rs->fields['nombre_completo']).'
                            </td>
                            <td>                            
                                '.utf8_encode($rs->fields['fecha']).'
                            </td>                            
                           <td align="center">                            
                             <span class="label '.$label_class.'">'.$text_estado.'</span>
                             </td>
                             <td> '.$case.'</td>
                                                    
                            <td width= "30" onclick="listar_seguimiento('.$rs->fields['id_solicitud'].')">                            
                                '.utf8_encode($rs->fields['ir']).'
                            </td>                            

                            ' ;                                                                               
                            
            $tabla.= '</tr>';                                     
  
                 $rs->MoveNext();     
                   }  
            
        $tabla.="</tbody></table>";
        return $tabla;

}

/*public function eliminar($id)
{
    $con = App::$base;
    $sql = "DELETE 
    FROM `colegio`.`grado` 
    WHERE `id_grado`= ?";
    $rs = $con->dosql($sql, array($id));
}

public function eliminar($id)
{
    $reg              = new Grado('grado');
    $reg->load("id_grado = {$id}");
    $reg->Delete();
}*/

/*public function editar($id,$desc, $letra, $numero)
  {

        $db = App::$base;
        $sql = "UPDATE `colegio`.`grado`
                SET descripcion = ?, `letra`= ?, numero = ?
                WHERE `id_grado`= ?";
    $rs = $db->dosql($sql, array($desc,$letra,$numero,$id));
       //var_dump($sql);
  }

  public function editar($id,$desc, $letra, $numero)
    {
        $reg              = new Grado('grado');
        $reg->load("id_grado = {$id}");
        $reg->descripcion      = $desc;
         $reg->letra = $letra;
        $reg->numero = $numero;       
        $reg->Save();
        //return $reg->id_grado;
    }*/

  public function buscar($id)
  {
    $db = App::$base;
        $sql = "SELECT 
                  `solicitud`.`descripcion_solicitud`,
                  `seguimiento_solicitud`.`descripcion_estado`,
                  `seguimiento_solicitud`.`id_seguimiento`,
                  `solicitud`.`estado_solicitud`
                  , CONCAT(`users`.`firstname`,' ', `users`.`lastname` ) as `Nombre_user`
                  , `users`.`identificacion` as `Identificacion_user` 
                  , `users`.`user_email` as `Correo_user`
                FROM
                  `solicitud`
                  INNER JOIN `seguimiento_solicitud` ON (`solicitud`.`id_solicitud` = `seguimiento_solicitud`.`id_solicitud`)
                  INNER JOIN `users` ON (`users`.`user_id`= `solicitud`.`user_id`)
                  WHERE `seguimiento_solicitud`.`id_seguimiento`= ?";
                    $rs = $db->dosql($sql, array($id));

    while (!$rs->EOF) 
                   {

                    $res = array( 
                     "descripcion_estado" => $rs->fields['descripcion_estado'],
                     "descripcion_solicitud" => $rs->fields['descripcion_solicitud'],
                     "estado_solicitud" => $rs->fields['estado_solicitud'],
                     "Nombre_user" => $rs->fields['Nombre_user'],
                     "Identificacion_user" => $rs->fields['Identificacion_user'],
                     "Correo_user" => $rs->fields['Correo_user']
                      );

                    $rs->MoveNext();      
                   } 
                   return $res;

  }

}

?>