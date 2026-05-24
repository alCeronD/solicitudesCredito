<?php

include_once 'GstSolicitudesCredito.php';
include_once __DIR__ . '/../../../Helpers/Utils.php';
include_once __DIR__ . '/../../../Helpers/Response.php';
include_once __DIR__ . '/../../Clientes/Controller/GstClientes.php';


class SolicitudesCreditoController
{
  protected GstSolicitudesCredito $gstSC;
  protected GstClientes $gstClientes;
  public function __construct()
  {
    $this->gstSC = new GstSolicitudesCredito();
    $this->gstClientes = new GstClientes();
  }

  public function renderMainView()
  {
    return include_once __DIR__ . '/../View/SCView.php';
  }

  // aca llamo a la CLASE DE GESTION
  public function crearSolicitud()
  {

    header('Content-Type: application/json; charset=utf-8');
    $data = Utils::returnGetDecode();

    // estado 1, indicando que su creacion inicial es creado.
    $data['estado_id'] = 1;
    $data['created_at'] = "";
    $data['updated_at'] = "";

    if (empty($data['numero_credito'])) {
      Response::success('El numero de credito es obligatorio', [$data['numero_credito']]);
    }

    if (empty($data['valor_solicitado']) || $data['valor_solicitado'] <= 0) {
      Response::success('Valor solicitado incorrecto, el campo es obligatorio y su valor como minimo es 1', [0]);
    }

    // validar existencia antes de ejecutar la function:
    $resultDuplicate = $this->gstSC->validateUnique('numero_credito', $data);
    $resultExistClienteId = $this->gstClientes->getInfoUser('cliente_id', $data);
    if (empty($resultExistClienteId)) {
      Response::success('El cliente no existe', []);
    }

    $numero_credito = isset($resultDuplicate[0]['numero_credito']) ? $resultDuplicate[0]['numero_credito'] : null;
    if ($numero_credito === $data['numero_credito']) {
      Response::success("El numero de credito $numero_credito ya existe", [$numero_credito]);
    }
    $resultPost = $this->gstSC->insert($data);
    if ($resultPost) {
      Response::success('Solicitud creada exitosamente', [$resultPost]);
    }
  }
}
