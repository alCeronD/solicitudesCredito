<?php

require_once __DIR__ . '/../../../Helpers/Crud.php';

class EstadosModel extends Crud
{
  protected $id = 'id_estado';
  protected $table = 'estados';
  protected $typedCasted;
  protected $campos = [
    'nombre',
    'descripcion',
    'created_at',
    'updated_at'
  ];
  protected $typeCampos = [
    'nombre' => 's',
    'descripcion' => 's',
    'created_at' => 's',
    'updated_at' => 's'
  ];
  protected $typeId = 'i';
}
