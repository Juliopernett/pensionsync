<?php
session_start();
include '../../../../core.php';

class Reporte
{
	public function preguntasxencuesta_con_opciones($id_encuesta)
{
    $con = App::$base;
    $sql = "SELECT
    `id_preguntas`,
    orden
    , `des`,
    tipo    
FROM
    `preguntas`
WHERE `id_encuesta`=? and tipo=? order by orden asc";

        $rs = $con->dosql($sql,array($id_encuesta,'con respuesta predefinida'));
        //var_dump($rs);
         while (!$rs->EOF) 
                   {
                    $opciones= $this->opcionesxpregunta($rs->fields['id_preguntas']);
                    $res[]=$arrayName = array('id_preguna' => $rs->fields['id_preguntas'],
                                              'tipo'=>$rs->fields['tipo'],
                                              'descripcion'=>$rs->fields['des'],
                                              'opciones'=>$opciones);
                    
                    
                    
                   $rs->MoveNext();     
                   }  
            
       
        return $res;

}

public function opcionesxpregunta($id_pregunta)
{
    $con = App::$base;
    $sql = "SELECT
    opcion.id_opcion,
    calificacion.descripcion_calificacion
FROM
    opcion
    INNER JOIN calificacion 
        ON (opcion.id_calificacion = calificacion.id_calificacion)
 WHERE id_pregunta=?";

    $rs = $con->dosql($sql,array($id_pregunta));

     while (!$rs->EOF) 
                   {

                    $res[] = array(
                     "porcentaje" => 0,
                     "id_opcion" => $rs->fields['id_opcion'],
                     "descripcion" => $rs->fields['descripcion_calificacion'],
                     "sumatoria"=>0,                     
                     "total_respuestas"=>0
                      );

                    $rs->MoveNext();      
                   } 
    
    return $res;
}

public function respuestas_predefinidas($encuesta)
{
    $con = App::$base;
    $sql = "SELECT
    `id_preguntas`,
    orden
    , `des`,
    tipo    
FROM
    `preguntas`
WHERE `id_encuesta`=? and tipo=? order by orden asc";

        $rs = $con->dosql($sql,array($encuesta,'con respuesta predefinida'));
        //var_dump($rs);
         while (!$rs->EOF) 
                   {
                    $respuestas[]= $this->respuestas($rs->fields['id_preguntas']);
                    // $res[]=$arrayName = array('id_preguna' => $rs->fields['id_preguntas'],
                    //                           'tipo'=>$rs->fields['tipo'],
                    //                           'descripcion'=>$rs->fields['des'],
                    //                           'respuestas'=>$respuestas);
                    
                    
                    
                   $rs->MoveNext();     
                   }  
            
       
        return $respuestas;
}

public function respuestas($id_pregunta)
{
    $con = App::$base;
    $sql = "SELECT
    respuesta.id_respueta
    , respuesta.id_opcion
    , calificacion.descripcion_calificacion
    , opcion.id_pregunta
FROM
    respuesta
    INNER JOIN opcion 
        ON (respuesta.id_opcion = opcion.id_opcion)
    INNER JOIN calificacion 
        ON (opcion.id_calificacion = calificacion.id_calificacion)
   WHERE opcion.id_pregunta=?";

    $rs = $con->dosql($sql,array($id_pregunta));

     while (!$rs->EOF) 
                   {

                    $res[] = array(
                    "id_pregunta"=> $rs->fields['id_pregunta'], 
                    "id_respueta" => $rs->fields['id_respueta'], 
                     "id_opcion" => $rs->fields['id_opcion'],
                     "descripcion" => $rs->fields['descripcion_calificacion']                     
                      );

                    $rs->MoveNext();      
                   } 
    
    return $res;
}

public function get_ids_ultimo_seguimiento()
{
    $con = App::$base;
    $sql = "SELECT
    MAX(seguimiento_solicitud.id_seguimiento) as maximo   
FROM
    seguimiento_solicitud
    INNER JOIN estado 
        ON (seguimiento_solicitud.id_estado = estado.id_estado)
    INNER JOIN solicitud 
        ON (seguimiento_solicitud.id_solicitud = solicitud.id_solicitud)
  GROUP BY seguimiento_solicitud.id_solicitud";

        $rs = $con->dosql($sql,'');
        //var_dump($rs);
         while (!$rs->EOF) 
                   {
                    $res[]= $rs->fields['maximo'];           
                   $rs->MoveNext();     
                   }  
            
       
        return $res;

}

public function get_solicitudes($fecha_ini,$fecha_fin)
{   
    $auxsql.="AND DATE(solicitud.fecha) >= ? "; 
    $auxsql.="AND DATE(solicitud.fecha) <= ? ";
    $array=array($fecha_ini,$fecha_fin);
    // if($fecha_ini!="" && $fecha_fin!=""){}
   //   else{
   //     if($fecha_ini!=""){//$array=array($estado,$fecha_ini);
   // }
   //     else
   //     {
   //      if($fecha_fin!=""){//$array=array($estado,$fecha_fin);
   //  }      

   //     }

   //   }
     
    $con = App::$base;
    $sql = "SELECT
    seguimiento_solicitud.id_seguimiento
    , seguimiento_solicitud.id_solicitud
    , solicitud.sufijo_solicitud
    , solicitud.descripcion_solicitud
    , seguimiento_solicitud.id_estado
    , estado.descripcion AS des_estado
    , seguimiento_solicitud.descripcion_estado 
    , solicitud.estado_solicitud
FROM
    seguimiento_solicitud
    INNER JOIN estado 
        ON (seguimiento_solicitud.id_estado = estado.id_estado)
    INNER JOIN solicitud 
        ON (seguimiento_solicitud.id_solicitud = solicitud.id_solicitud)
    WHERE seguimiento_solicitud.id_estado>0 ".$auxsql;
    //var_dump($sql); //AND DATE(solicitud.fecha) >= ? ".$auxsql

    $rs = $con->dosql($sql,$array);
    //var_dump($rs);
        if($rs->fields!=false){
                 while (!$rs->EOF) 
                   {
                    $res[]= array("id_seguimiento"=>$rs->fields['id_seguimiento'],
                                   "id_solicitud"=>$rs->fields['id_solicitud'],
                                   "sufijo_solicitud"=>$rs->fields['sufijo_solicitud'],
                                   "descripcion_solicitud"=>$rs->fields['descripcion_solicitud'],
                                   "id_estado"=>$rs->fields['id_estado'],
                                   "des_estado"=>$rs->fields['des_estado'],
                                    "descripcion_estado"=>$rs->fields['descripcion_estado'],
                                    "estado_solicitud"=>$rs->fields['estado_solicitud']
                                );

                   $rs->MoveNext();     
                   } 
         return $res;   
        }
        else
        {
      return array();
        }      

}

public function listar_estados()
  {
    $db = App::$base;
        $sql = "SELECT
    id_estado
    , descripcion

FROM
    estado";
                    $rs = $db->dosql($sql, array());

    while (!$rs->EOF) 
                   {

                    $res[] = array( 
                     "id_estado" => $rs->fields['id_estado'],
                     "descripcion" => $rs->fields['descripcion'],
                     "cantidad"=>0                     
                      );

                    $rs->MoveNext();      
                   } 
                   return $res;

  }

}

?>