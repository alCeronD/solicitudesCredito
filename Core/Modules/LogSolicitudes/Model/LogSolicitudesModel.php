<?php
require_once __DIR__ . '/../../../Helpers/Crud.php';
class LogSolicitudesModel extends Crud
{
  protected $id = 'id_log';
  protected $table = 'logSolicitud';
  protected $typedCasted;
  protected $campos = [
    'id_solicitud',
    'nombre_proceso',
    'informacion',
    'created_at',
    'updated_at'
  ];
  protected $typeCampos = [
    'nombre_proceso' => 's',
    'id_solicitud' => 'i',
    'informacion' => 's',
    'created_at' => 's',
    'updated_at' => 's'
  ];


  protected $typeId = 'i';
}
