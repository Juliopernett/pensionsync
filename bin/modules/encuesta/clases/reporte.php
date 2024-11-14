<?php

//include_once Config::$home_bin.Config::$ds.'db'.Config::$ds.'active_table.php'; 
class reporte
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
//var_dump($sql);
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

}

?>