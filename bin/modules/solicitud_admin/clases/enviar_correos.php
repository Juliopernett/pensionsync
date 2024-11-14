<?php

include_once Config::$home_bin.Config::$ds.'db'.Config::$ds.'active_table.php'; 
class Notificacion extends ADOdb_Active_Record{}
class listCorreo
{

public function enviarCorreo($solicitud, $estado){
$correo = $this->buscarCorreo($solicitud);
//$correo = '';
//var_dump($correo);
//$curso = $this->buscar($id,1);
//$estudiante = $this->buscar($id,2);
//var_dump($cor);
$asunto = "Notificacion PQR# $solicitud";
$cuerpo = "Su solicitud ha cambiado de estado a $estado, Puedes revisar tu solicitud ingresando a : http://pqr";
$inicio = nl2br("$cuerpo\n\nEste mensaje es automatico. Favor no responder");
$mensaje = str_replace("<br />", "", $inicio);

if($correo == "" || $correo =="NULL" ){
return -1;}
else{

//error_reporting(-1);
//ini_set('display_errors', 'On');
//set_error_handler("var_dump");

$subject = $asunto;// El valor entre corchetes son los atributos name del formulario html
$msg = $cuerpo;
$from = $correo;
// El from DEBE corresponder a una cuenta de correo real creada en el servidor
$headers = "From: prueba@\r\n"; 
$headers .= "Reply-To: $from\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=utf-8\r\n"; 
	
if(mail($from, $subject, $msg,$headers)){
	//echo "mail enviado";
	}else{
	//$errorMessage = error_get_last()['msg'];
//	echo $errorMessage;
}

return 1;

}


}

public function buscarCorreo($id)
  {
    $db = App::$base;
        $sql = "SELECT 
				  `solicitud`.`id_solicitud`,
				  `solicitud`.`user_id`,
				  `users`.`user_email`
				FROM
				  `solicitud`
				  INNER JOIN `seguimiento_solicitud` ON (`solicitud`.`id_solicitud` = `seguimiento_solicitud`.`id_solicitud`)
				  INNER JOIN `users` ON (`solicitud`.`user_id` = `users`.`user_id`)
				   WHERE solicitud.id_solicitud = ?
  					GROUP BY user_id";

			    $rs = $db->dosql($sql, array($id));
			    return $rs->fields['user_email'];

  }


}

//$prueba = new listCorreo();
//$prueba->enviarCorreo('1','pendiente');
?>