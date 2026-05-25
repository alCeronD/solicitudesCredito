<?php

include_once __DIR__ . '/../Model/UsuariosModel.php';
class GstUsuarios
{
  protected UsuariosModel $mlUsuarios;

  public function __construct()
  {
    $this->mlUsuarios = new UsuariosModel();
  }

  public function getUserId(string $columnValidate, bool $isAsesor = false, array $datos = [])
  {

    $datoValidate['id_usuario'] = ($isAsesor) ? $datos['asesor_id'] : $datos['auxiliar_id'];
    $this->mlUsuarios->select(true);
    $dataValidateUnique['data'] = $datoValidate;
    $resultUnique = $this->mlUsuarios->where(true, $columnValidate)->prepareSql($dataValidateUnique)->get();
    return $resultUnique;
  }
}
