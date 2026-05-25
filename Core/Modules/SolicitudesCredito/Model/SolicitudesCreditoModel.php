<?php
include_once __DIR__ . '/../../../Helpers/Crud.php';

class SolicitudesCreditoModel extends Crud
{

  protected $id = 'id_solicitud';
  protected $table = 'solicitudes';
  protected $typedCasted;
  protected $campos = [
    'numero_credito',
    'cliente_id',
    'valor_solicitado',
    'asesor_id',
    'auxiliar_id',
    'estado_id',
    'created_at',
    'updated_at'
  ];
  protected $typeCampos = [
    'numero_credito' => 's',
    'cliente_id' => 'i',
    'valor_solicitado' => 'i',
    'asesor_id' => 'i',
    'auxiliar_id' => 'i',
    'estado_id' => 'i',
    'created_at' => 's',
    'updated_at' => 's'
  ];
  protected $typeId = 'i';
}
