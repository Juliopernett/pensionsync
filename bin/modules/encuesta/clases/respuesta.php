<?php
session_start();
include '../../../../core.php';
include_once Config::$home_bin.Config::$ds.'db'.Config::$ds.'active_table.php'; 
class Tablarespuestapre extends ADOdb_Active_Record{}
class Tablarespuestalib extends ADOdb_Active_Record{}
 
class Respuesta
{
  public $id; 

public function registrar_predefinidas($rtasPre)
    {
     
      foreach ($rtasPre as $r) {
        $reg              = new Tablarespuestapre('respuesta');
        $reg->id_opcion = $r;
        $reg->id_users = $_SESSION['user_id'];
        $reg->Save();
      }       
   }

public function registrar_libres($rtasLib)
    {
     
      foreach ($rtasLib as $r) {
        $reg              = new Tablarespuestapre('respuestalibre');
        $reg->texto = $r;
        $reg->id_users = $_SESSION['user_id'];
        $reg->Save();
      }       
   }
}