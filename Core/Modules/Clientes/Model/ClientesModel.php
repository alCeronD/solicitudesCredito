<?php

include_once __DIR__ . '/../../../Helpers/Crud.php';
class ClientesModel extends Crud
{
  protected $id = 'id_cliente';
  protected $table = 'clientes';
  protected $typedCasted;
  protected $campos = [
    'id_cliente',
    'nro_identificacion',
    'nombre_completo',
    'telefono',
    'created_at',
    'updated_at'
  ];
  protected $typeCampos = [
    'nro_identificacion' => 'i',
    'nombre_completo' => 's',
    'telefono' => 's',
    'created_at' => 's',
    'updated_at' => 's'
  ];


  protected $typeId = 'i';
}
