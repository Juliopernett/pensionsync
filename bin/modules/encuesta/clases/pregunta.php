<?php
session_start();
include '../../../../core.php';
include_once Config::$home_bin.Config::$ds.'db'.Config::$ds.'active_table.php'; 
class Tablapregunta extends ADOdb_Active_Record{}
class Tablaopcion extends ADOdb_Active_Record{}
 
class Pregunta
{
  public $id;
 

public function registrar($id_encuesta,$descripcion, $orden, $tipo)
    {
       
        $reg              = new Tablapregunta('preguntas');
        $reg->id_encuesta = $id_encuesta;
        $reg->des = $descripcion;
        $reg->orden= $orden;
        $reg->tipo= $tipo;
        $reg->Save();
        $this->id = $reg->id_preguntas;//var_dump($reg->id_preguntas);
        return $this->id;
    }

  public function tabla_preguntasxencuesta($id_encuesta)
{
	$con = App::$base;
    $sql = "SELECT
    `id_preguntas`,
    orden
    , `des`    
FROM
    `preguntas`
WHERE `id_encuesta`=? order by orden asc";

		$rs = $con->dosql($sql,array($id_encuesta));
		//var_dump($rs);
        $tabla = '<table id="myTable" class="table table-hover table-striped table-bordered table-condensed" cellpadding="0" cellspacing="0" border="1" class="display" >
                        <thead>
                        <tr>
                        <th id="yw9_c0">Orden</th>
                        <th id="yw9_c1">Pregunta</th>
                        <th id="yw9_c2">Asignar cal.</th>
                        <th id="yw9_c2">Ver cal.</th>
                        </tr>
                        </thead>
                        <tbody>';
		          while (!$rs->EOF) 
                   {
                    
                   	$tabla.='<tr >  
                            <td>                            
                                '.utf8_encode($rs->fields['orden']).'
                            </td>
                            <td>                            
                                '.utf8_encode($rs->fields['des']).'
                            </td>
                            <td width= "50" onclick="asignar_calificacion('.$rs->fields['id_preguntas'].')">                            
                                <button type="button" class="btn btn-success btn-sm btn_edit" >
               <span class="glyphicon glyphicon-arrow-right"></span></button>
                            </td>
                            <td width= "50" onclick="ver_calificaciones('.$rs->fields['id_preguntas'].')">                            
                                <button type="button" class="btn btn-primary btn-sm btn_edit" data-title="Edit" data-toggle="modal" data-target="#myModal" >
               <span class="glyphicon glyphicon-eye-open"></span></button>
                            </td>' ;                                                                               
                            
            $tabla.= '</tr>';                                     
	
	               $rs->MoveNext();	    
                   }  
            
        $tabla.="</tbody></table>";
        return $tabla;

}

public function asignar_posible_calificacion($id_calificacion,$id_pregunta)
    {
       
        $reg              = new Tablaopcion('opcion');
        $reg->id_calificacion = $id_calificacion;
        $reg->id_pregunta = $id_pregunta;
        $reg->Save();
        $this->id = $reg->id_opcion;//var_dump($reg->id_preguntas);
        return $this->id;
    }

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
WHERE `id_encuesta`=? order by orden asc";

        $rs = $con->dosql($sql,array($id_encuesta));
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
                     "id_opcion" => $rs->fields['id_opcion'],
                     "descripcion" => $rs->fields['descripcion_calificacion']                     
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
WHERE `id_encuesta`=? order by orden asc";

        $rs = $con->dosql($sql,array($id_encuesta));
        //var_dump($rs);
         while (!$rs->EOF) 
                   {
                    $respuestas= $this->respuestas($rs->fields['id_preguntas']);
                    $res[]=$arrayName = array('id_preguna' => $rs->fields['id_preguntas'],
                                              'tipo'=>$rs->fields['tipo'],
                                              'descripcion'=>$rs->fields['des'],
                                              'opciones'=>$respuestas);
                    
                    
                    
                   $rs->MoveNext();     
                   }  
            
       
        return $res;
}

public function respuestas($id_pregunta)
{
    $con = App::$base;
    $sql = "SELECT
    respuesta.id_respueta
    , respuesta.id_opcion
    , calificacion.descripcion_calificacion
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