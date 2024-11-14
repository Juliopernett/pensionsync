<?php
include '../../../../core.php';
require '../PHPMailerAutoload.php';
include_once Config::$home_bin.Config::$ds.'db'.Config::$ds.'active_table.php'; 
 class Notificacion extends ADOdb_Active_Record{}
class listCorreo
{


public function reg_notificacion($id, $descripcion)  
     
    {
        date_default_timezone_set('America/Bogota');
        $fecha = date('Y-m-d');
        $hora =  date ("h:i:s");
            
        $reg              = new Notificacion('notificacion');
        $reg->id_solicitud              =$id;       
        $reg->fecha_notificacion        = $fecha;
        $reg->descripcion_notificacion  = $descripcion;
        $reg->Save();
        
    }

public function buscar_fechas(){
        $db = App::$base;

        $sql = "SELECT DATEDIFF(NOW(),fecha) as dias,
                        id_estado, 
                        id_solicitud, 
                        para_notificacion 
                FROM seguimiento_solicitud 
                WHERE para_notificacion = -1";
                $rs = $db->dosql($sql, array());                 
                
                while (!$rs->EOF) 
                   {                      
                                            
                          if($rs->fields['dias'] >= 1) {
                            $this->reg_notificacion($rs->fields['id_solicitud'], 'Alerta la solicitud no ha sido resuelta');
                            $this->enviarCorreo($rs->fields['id_solicitud']);
                            }
                                                  
  
                 $rs->MoveNext();     
                   }  

}


public function listarDocentesCorreo($id)
{
  //var_dump($id);
	$con = App::$base;
    $sql = "SELECT 
              `docente`.`nombre_completo`,
              `docente`.`user_email` as mail,
              CONCAT(`grado`.`descripcion`,' ',
              `grado`.`letra`) as grado,
              `materia`.`descripcion` as materia,
               `grado`.`descripcion`,
               `grado`.`letra`,
               `materia_organizada`.`cod_docente`,
              
               \"
              <button type=\'button\' class=\'btn btn-info btn-sm btn_edit\' data-title=\'Edit\' data-toggle=\'modal\' data-target=\'#myModal\' >
               <span class=\'glyphicon glyphicon-envelope\'></span></button>
               </div>
                \" 
               as editar,
               \"
              <button type=\'button\' class=\'btn btn-danger btn-sm btn_delete\' data-title=\'Edit\'>
               <span class=\'glyphicon glyphicon-trash\'></span></button>
               </div>
                \"
                 as borrar                    
                      FROM
            `docente`
            INNER JOIN `materia_organizada` ON (`docente`.`id_docente` = `materia_organizada`.`cod_docente`)
            INNER JOIN `materiaxgrado` ON (`materia_organizada`.`cod_mxg` = `materiaxgrado`.`id_mxg`)
            INNER JOIN `materia` ON (`materiaxgrado`.`cod_materia` = `materia`.`id_materia`)
            INNER JOIN `grado` ON (`materiaxgrado`.`cod_grado` = `grado`.`id_grado`)
          WHERE
            `grado`.`id_grado` = ? ";

		$rs = $con->dosql($sql, array($this->buscarGrado($id)));
    //var_dump($this->buscarGrado($id));
        $tabla = '<table id="myTable" class="table table-hover table-striped table-bordered table-condensed" cellpadding="0" cellspacing="0" border="1" class="display" >
                        <thead>
                        <tr>
                        <th id="yw9_c0">Nombre Docente</th>
                        <th id="yw9_c1">Grado</th>
                        <th id="yw9_c2">Materia</th>
                        <th id="yw9_c2">Mail</th>
                        <th id="yw9_c7">Redactar</th>
                        
                        </tr>
                        </thead>
                        <tbody>';
		          while (!$rs->EOF) 
                   {
                   	$tabla.='<tr >  
                            <td>                            
                                '.utf8_encode($rs->fields['nombre_completo']).'
                            </td>
                            <td>                            
                                '.utf8_encode($rs->fields['grado']).'
                            </td>
                            <td>                            
                                '.utf8_encode($rs->fields['materia']).'
                            </td>
                            <td>                            
                                '.utf8_encode($rs->fields['mail']).'
                            </td>
                                                      
                                                    
                            <td align="center" width= "30" onclick="editar('.$rs->fields['cod_docente'].')">                            
                                '.utf8_encode($rs->fields['editar']).'
                            </td>';                                                                               
                            
            $tabla.= '</tr>';                                     
	
	               $rs->MoveNext();	    
                   }  
            
        $tabla.="</tbody></table>";
        //var_dump($tabla);
        return $tabla;

}

 public function buscarCorreoDocente($id)
  {
    $db = App::$base;
        $sql = "SELECT
                 user_email
                 FROM
                 docente
                 WHERE id_docente = ?";
    $rs = $db->dosql($sql, array($id));

    return $rs->fields['user_email'];

  }

public function enviarCorreo($cor){
//$correo = $this->buscarCorreoDocente($id);
//var_dump($correo);
//$curso = $this->buscar($id,1);
//$estudiante = $this->buscar($id,2);
var_dump($cor);
$asunto = "Alerta PQR#  $cor";
$cuerpo = "No ha solucionado la Solicitud PQR #- $cor , favor revisar, esta es una prueba de recibir correo en el sistema de PQR, German me comentas si lo recibis";
$inicio = nl2br("Administrador \nFavor responder este correo a \n\n$cuerpo");
$mensaje = str_replace("<br />", "", $inicio);
$correo = "";
//$correo = "germanjativa@gmail.com";
if($correo == "" || $correo =="NULL" ){
return -1;}
else{
$mail = new PHPMailer;
/*$mail->IsSMTP();                           // telling the class to use SMTP
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->Host       = "smtp.gmail.com"; // set the SMTP server
$mail->Port       = 587;   */
$mail->IsSMTP();   
$mail ->SMTPSecure  =  'ssl' ;                         // telling the class to use SMTP
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->Host       = "smtp.gmail.com"; // set the SMTP server
$mail->Port       = 465;                     // set the SMTP port
$mail->Username   = ""; // SMTP account username
$mail->Password   = "";        // SMTP account password
//$mail->setFrom($correo, 'Padre de Familia');
$mail->setFrom($correo, "Alerta PQR no Resuelta");
$mail->addAddress($correo, 'Recibe');
$mail->Subject  = $asunto;
//$mail->Body     = "$cuerpo <br>".$padre;
$mail->Body     = $mensaje;

if(!$mail->send()) {
 // echo 'Message was not sent.';
  //echo 'Mailer error: ' . $mail->ErrorInfo;
    return -2;
} else {
  //echo 'Message has been sent.';
    return 1;
}
}
}



  public function buscar($id,$r)
  {
    $db = App::$base;    
        $sql = "SELECT 
                   CONCAT(`grado`.`descripcion`,' ',
                  `grado`.`letra`) as curso
                FROM
                  `grado`
                  INNER JOIN `estudiantexgrado` ON (`grado`.`id_grado` = `estudiantexgrado`.`id_grado`)
                  INNER JOIN `estudiante` ON (`estudiantexgrado`.`id_estudiante` = `estudiante`.`id_estudiante`)
                WHERE
                  `estudiantexgrado`.`id_estudiante` = ? AND 
                  `estudiantexgrado`.`estado_grado` = 1
                LIMIT 1";
    $rs = $db->dosql($sql, array($id));
  /*  if($r == 1){
    return $rs->fields['nombres'];
        }
        else{
     return $rs->fields['curso'];
        }*/
return $rs->fields['curso'];
  }

}

?>