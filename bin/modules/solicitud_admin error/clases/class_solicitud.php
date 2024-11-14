<?php
session_start();
include '../../../../core.php';
include_once Config::$home_bin.Config::$ds.'db'.Config::$ds.'active_table.php'; 
class Seguimiento extends ADOdb_Active_Record{}

class regSolicitud
{


    public function reg_seguimiento($id,$solicitud ,$estado, $descripcion)
    {
      date_default_timezone_set('America/Bogota');
        $fecha = date('Y-m-d');
        $hora =  date ("h:i:s");
        $reg              = new Seguimiento('seguimiento_solicitud');
       // $reg->load("id_seguimiento = {$id}");
        $reg->id_solicitud      = $solicitud;
        $reg->fecha = $fecha;
        $reg->hora = $hora;
        $reg->id_estado = $estado;
        $reg->descripcion_estado = $descripcion;
        $reg->Save();
        
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
              <button type=\'button\' class=\'btn btn-info btn-sm btn_sol\' data-title=\'Edit\' data-toggle=\'modal\' data-target=\'#myModalSol\' >
               <span class=\'glyphicon glyphicon-play\'></span></button>
               </div>
                \" 
               as editar                
            FROM
              `solicitud`
              INNER JOIN `users` ON (`solicitud`.`user_id` = `users`.`user_id`)
              INNER JOIN `tipo_solicitud` ON (`solicitud`.`id_tiposolicitud` = `tipo_solicitud`.`id_tiposolicitud`)
              INNER JOIN `seguimiento_solicitud` ON (`solicitud`.`id_solicitud` = `seguimiento_solicitud`.`id_solicitud`)
              INNER JOIN `estado` ON (`estado`.`id_estado` = `seguimiento_solicitud`.`id_estado`)
              WHERE             
              `seguimiento_solicitud`.`id_solicitud` = ?
              order by `seguimiento_solicitud`.`id_seguimiento` desc
               ";

		$rs = $con->dosql($sql, array($id));
        $tabla = '<table id="myTable" class="table table-hover table-striped table-bordered table-condensed" cellpadding="0" cellspacing="0" border="1" class="display" >
                        <thead>
                        <tr>
                        <th id="yw9_c0">#</th>
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
		          while (!$rs->EOF) 
                   {
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
                                                    
                            <td width= "30" onclick="editar('.$rs->fields['id_seguimiento'].','.$rs->fields['id_solicitud'].')">                            
                                '.utf8_encode($rs->fields['editar']).'
                            </td>
                            

                            ' ;                                                                               
                            
            $tabla.= '</tr>';                                     
	
	               $rs->MoveNext();	    
                   }  
            
        $tabla.="</tbody></table>";
        return $tabla;

}

public function listSolicitud2()
{
  $con = App::$base;
    $sql = "SELECT 
            `solicitud`.`id_solicitud` as sol,
            `tipo_solicitud`.`descripcion`,
            CONCAT('PQR # ',`solicitud`.`id_solicitud`) AS numero,
            `users`.`firstname`,
            `users`.`lastname`,
            CONCAT(`users`.`firstname`, ' ',
            `users`.`lastname`) AS nombre_completo,
            `solicitud`.`fecha`,
            `solicitud`.`estado_solicitud`,                    
               \"
              <button type=\'button\' class=\'btn btn-info btn-sm btn_sol\' data-title=\'Edit\'>
               <span class=\'glyphicon glyphicon-play\'></span></button>
               </div>
                \" 
               as ir               
            FROM
            `tipo_solicitud`
            INNER JOIN `solicitud` ON (`tipo_solicitud`.`id_tiposolicitud` = `solicitud`.`id_tiposolicitud`)
            INNER JOIN `users` ON (`solicitud`.`user_id` = `users`.`user_id`)
            ORDER BY sol DESC ";

    $rs = $con->dosql($sql, array());
        $tabla = '<table id="myTable1" class="table table-hover table-striped table-bordered table-condensed" cellpadding="0" cellspacing="0" border="2" class="display" >
                        <thead>
                        <tr>
                        <th id="yw9_c0">#</th>
                        <th id="yw9_c1">Tipo Solicitud</th>
                        <th id="yw9_c2">Usuario</th>
                        <th id="yw9_c3">Fecha</th>
                        <th id="yw9_c4">Estado</th>
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
                FROM
                  `solicitud`
                  INNER JOIN `seguimiento_solicitud` ON (`solicitud`.`id_solicitud` = `seguimiento_solicitud`.`id_solicitud`)
                  WHERE `seguimiento_solicitud`.`id_seguimiento`= ?";
                    $rs = $db->dosql($sql, array($id));

    while (!$rs->EOF) 
                   {

                    $res = array( 
                     "descripcion_estado" => $rs->fields['descripcion_estado'],
                     "descripcion_solicitud" => $rs->fields['descripcion_solicitud'],
                     "estado_solicitud" => $rs->fields['estado_solicitud']
                      );

                    $rs->MoveNext();      
                   } 
                   return $res;

  }

}

?>