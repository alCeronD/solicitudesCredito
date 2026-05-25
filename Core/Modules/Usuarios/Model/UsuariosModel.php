<?php

include_once __DIR__ . '/../../../Helpers/Crud.php';

class UsuariosModel extends Crud
{
  protected $id = 'id_usuario';
  protected $table = 'usuarios';
  protected $typedCasted;
  protected $campos = [
    'nombre_completo',
    'id_rol',
    'created_at',
    'updated_at'
  ];
  protected $typeCampos = [
    'nombre_completo' => 's',
    'id_rol' => 'i',
    'created_at' => 's',
    'updated_at' => 's'
  ];


  protected $typeId = 'i';
}
