<?php
require_once __DIR__ . '/../../../Helpers/Crud.php';
class LogSolicitudesModel extends Crud
{
  protected $id = 'id_solicitud';
  protected $table = 'logSolicitud';
  protected $typedCasted;
  protected $campos = [
    'nombre_proceso',
    'informacion',
    'created_at',
    'updated_at'
  ];
  protected $typeCampos = [
    'nombre_proceso' => 's',
    'informacion' => 's',
    'created_at' => 's',
    'updated_at' => 's'
  ];


  protected $typeId = 'i';
}
