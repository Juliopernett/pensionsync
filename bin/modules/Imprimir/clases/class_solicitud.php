<?php
session_start();
include '../../../../core.php';
/*include_once 'enviar_correos.php';*/
include_once Config::$home_bin.Config::$ds.'db'.Config::$ds.'active_table.php'; 
class Seguimiento extends ADOdb_Active_Record{}
class NotificacionAdmin extends ADOdb_Active_Record{}


class regSolicitud
{

public function listSolicitud2()
{
  $con = App::$base;
    $sql = "SELECT 
            `solicitud`.`id_solicitud`,
            `tipo_solicitud`.`descripcion`,
            CONCAT('PQR # ',`solicitud`.`id_solicitud`) AS numero,
            `users`.`firstname`,
            `users`.`lastname`,
            IF(`tipo_solicitud`.`id_tiposolicitud` = 5, 'ANONIMO', 
              CONCAT(`users`.`firstname`, ' ',
            `users`.`lastname`)) AS nombre_completo,
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
            /*WHERE estado_solicitud = ?*/
            order by `solicitud`.`id_solicitud` desc
            ";

    $rs = $con->dosql($sql, array($estado));
        $tabla = '<table id="myTable1" class="table table-hover table-striped table-bordered table-condensed" cellpadding="0" cellspacing="0" border="2" class="display" >
                        <thead>
                        <tr>
                        <th id="yw9_c0">#</th>
                        <th id="yw9_c1">Tipo</th>
                        <th id="yw9_c2">Usuario</th>
                        <th id="yw9_c3">Fecha</th>
                        <th id="yw9_c5">Pdf</th>                   
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


}

?>