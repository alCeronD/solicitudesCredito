<?php

include_once 'GstSolicitudesCredito.php';
include_once __DIR__ . '/../../../Helpers/Utils.php';
include_once __DIR__ . '/../../../Helpers/Response.php';
include_once __DIR__ . '/../../Clientes/Controller/GstClientes.php';
include_once __DIR__ . '/../../LogSolicitudes/Controller/GstLogSolicitudes.php';


class SolicitudesCreditoController
{
  protected GstSolicitudesCredito $gstSC;
  protected GstClientes $gstClientes;
  protected GstLogSolicitudes $gstLog;
  public function __construct()
  {
    $this->gstSC = new GstSolicitudesCredito();
    $this->gstClientes = new GstClientes();
    $this->gstLog = new GstLogSolicitudes();
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
      // creamos el log luego de validar que todo el proceso fue exitoso.
      $dataEncode = Utils::returnGetEncode($data);
      $datosLog['nombre_proceso'] = "CREATE";
      $datosLog['informacion'] = $dataEncode;
      $datosLog['created_at'] = "";
      $datosLog['updated_at'] = "";
      $resultLog = $this->gstLog->createLog($datosLog);
      if ($resultLog) {
        Response::success('Solicitud creada exitosamente', [$resultPost]);
      }
    }
  }
}
