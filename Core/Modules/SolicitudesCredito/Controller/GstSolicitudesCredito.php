<?php

include_once __DIR__ . '/../Model/SolicitudesCreditoModel.php';
include_once __DIR__ . '/../../../Helpers/Crud.php';

class GstSolicitudesCredito
{

  protected $mlSolicitudCredito;

  public function __construct()
  {
    $this->mlSolicitudCredito = new SolicitudesCreditoModel();
  }

  // aca va la logica del insert para guardar el registro en la base de datos
  public function insert(array $datos = [])
  {
    $dataInsert['data'] = $datos;
    $resultPrepare = $this->mlSolicitudCredito->insert($datos)->prepareSql($dataInsert)->get();
    return $resultPrepare;
  }

  public function validateUnique(string $columnValidate, array $datos = [])
  {
    $this->mlSolicitudCredito->select(true);
    $dataValidateUnique['data'] = $datos;
    $resultUnique = $this->mlSolicitudCredito->where(true, $columnValidate)->prepareSql($dataValidateUnique)->get();
    return $resultUnique;
  }
}
