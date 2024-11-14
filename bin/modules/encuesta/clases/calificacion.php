<?php
session_start();
include '../../../../core.php';
include_once Config::$home_bin.Config::$ds.'db'.Config::$ds.'active_table.php'; 
class Tablacalificacion extends ADOdb_Active_Record{}
 
class Calificacion
{
  public $id;
 

public function registrar($descripcion)
    {
       
        $reg              = new Tablacalificacion('calificacion');
        $reg->descripcion_calificacion = $descripcion;
        $reg->Save();
        $this->id = $reg->id_calificacion;
        return $this->id;
    }

public function listar_todas()
{
    $con = App::$base;
    $sql = "SELECT
    id_calificacion
    , descripcion_calificacion
FROM
    calificacion
ORDER BY descripcion_calificacion ASC";

    $rs = $con->dosql($sql,array());

     while (!$rs->EOF) 
                   {

                    $res[] = array( 
                     "id_calificacion" => $rs->fields['id_calificacion'],
                     "descripcion" => $rs->fields['descripcion_calificacion']                     
                      );

                    $rs->MoveNext();      
                   } 
    
    return $res;
}



}