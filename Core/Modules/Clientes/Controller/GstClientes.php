<?php

include_once __DIR__ . '/../Model/ClientesModel.php';

include_once __DIR__ . '/../../../Helpers/Crud.php';


class GstClientes
{
  protected ClientesModel $mlCliente;

  public function __construct()
  {
    $this->mlCliente = new ClientesModel();
  }

  public function getInfoUser(string $campo, array $datos = [])
  {
    $dataValidateUnique['data'] = $datos;
    $this->mlCliente->select(true);
    $resultUnique = $this->mlCliente->where(true, $campo)->prepareSql($dataValidateUnique)->get();
    return count($resultUnique) === 0 ? [] : $resultUnique;
  }
}
