<?php
session_start();
include '../../../../core.php';
include_once Config::$home_bin.Config::$ds.'db'.Config::$ds.'active_table.php'; 
class Tablaencuesta extends ADOdb_Active_Record{}
 
class Encuesta
{
  public $id;
 

public function registrar($descripcion)
    {
       
        $reg              = new Tablaencuesta('encuesta');
        $reg->descripcion = $descripcion;
        $reg->fecha_encuesta = date('Y-m-d');
        $reg->hora_encuesta = date('H:i:s');
        $reg->estado = "Activa";
        $reg->Save();
        $this->id = $reg->id_encuesta;
        return $this->id;
    }

public function listar_todas()
{
    $con = App::$base;
    $sql = "SELECT
    id_encuesta
    , descripcion
FROM
    encuesta
ORDER BY descripcion ASC";

    $rs = $con->dosql($sql,array());

     while (!$rs->EOF) 
                   {

                    $res[] = array( 
                     "id_encuesta" => $rs->fields['id_encuesta'],
                     "descripcion" => $rs->fields['descripcion']                     
                      );

                    $rs->MoveNext();      
                   } 
    
    return $res;
}

public function contestada()
{
  $con = App::$base;
    $sql = "SELECT
   DISTINCT preguntas.id_encuesta
FROM
    respuesta
    INNER JOIN opcion 
        ON (respuesta.id_opcion = opcion.id_opcion)
    INNER JOIN preguntas 
        ON (opcion.id_pregunta = preguntas.id_preguntas)
   WHERE respuesta.id_users=?";

    $rs = $con->dosql($sql,array($_SESSION['user_id']));

     while (!$rs->EOF) 
                   {

                    $res = $rs->fields['id_encuesta'];

                    $rs->MoveNext();      
                   } 
    
    return $res;

}





}