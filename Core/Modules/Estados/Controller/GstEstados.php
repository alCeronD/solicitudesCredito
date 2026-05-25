<?php

include_once __DIR__ . '/../Model/EstadoModel.php';
class GstEstado
{
  protected EstadosModel $mlEstados;
  public function __construct()
  {
    $this->mlEstados = new EstadosModel();
  }

  public function select(array $datos = [])
  {
    // cambio el key del estado de estado_id a id_estado, esto porque asi esta en la tabla estado.
    $estado = ['id_estado' => $datos['estado_id']];
    $preparedEstados['data'] = $estado;
    $result = $this->mlEstados->select(true)->where(true, 'id_estado', $datos)->prepareSql($preparedEstados)->get();
    return $result;
  }
}
