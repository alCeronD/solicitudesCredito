<?php

include_once 'GstSolicitudesCredito.php';
include_once __DIR__ . '/../../../Helpers/Utils.php';
include_once __DIR__ . '/../../../Helpers/Response.php';
include_once __DIR__ . '/../../Clientes/Controller/GstClientes.php';
include_once __DIR__ . '/../../LogSolicitudes/Controller/GstLogSolicitudes.php';
include_once __DIR__ . '/../../Estados/Controller/GstEstados.php';
include_once __DIR__ . '/../../Usuarios/Controller/GstUsuarios.php';


class SolicitudesCreditoController
{
  protected GstSolicitudesCredito $gstSC;
  protected GstClientes $gstClientes;
  protected GstLogSolicitudes $gstLog;
  protected GstEstado $gstEstados;
  protected GstUsuarios $gstUsuarios;
  public function __construct()
  {
    $this->gstSC = new GstSolicitudesCredito();
    $this->gstClientes = new GstClientes();
    $this->gstLog = new GstLogSolicitudes();
    $this->gstEstados = new GstEstado();
    $this->gstUsuarios = new GstUsuarios();
  }

  public function renderMainView()
  {
    return include_once __DIR__ . '/../View/SCView.php';
  }

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

    // valido si el numero de credito me llega como string.
    if (is_int($data['numero_credito'])) {
      Response::success('El tipo de dato del numero de credito es incorrecto', [$data['numero_credito']]);
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

    // validar existencia del asesor o auxiliar
    $existAsesor = $this->gstUsuarios->getUserId('id_usuario', true, $data);
    $existAsesorAuxiliar = $this->gstUsuarios->getUserId('id_usuario', false, $data);

    if (count($existAsesor) === 0 || count($existAsesorAuxiliar) === 0) {
      Response::success('datos de auxiliar o asesor incorrectos', []);
    }




    $resultPost = $this->gstSC->insertSolicitud($data);
    $idSolicitud = (int)$resultPost;
    if ($resultPost) {
      // creamos el log luego de validar que todo el proceso fue exitoso.
      $dataEncode = Utils::returnGetEncode($data);
      $datosLog['id_solicitud'] = $idSolicitud;
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

  public function actualizarSolicitud()
  {
    header('Content-Type: application/json; charset=utf-8');
    $data = Utils::returnGetDecode();

    // validar que la solicitud exista
    $resultDuplicateSolicitud = $this->gstSC->validateUnique('id_solicitud', $data);
    $resultExistsEstado = $this->gstEstados->select($data);

    // validar que el estado a cambiar exista.
    if (count($resultExistsEstado) === 0) {
      Response::success("El estado es incorrecto", [$data['estado_id']]);
    }
    // validar que la solicitud exista, si no existe, no ejecutar el cambio de estado.
    if (count($resultDuplicateSolicitud) === 0) {
      Response::success("Solicitud Nro {$data['id_solicitud']} no existe", [$data['id_solicitud']]);
    }

    // validar no cambiar solicitud en estado deseembolzada
    if ($resultDuplicateSolicitud[0]['estado_id'] === 5) {
      Response::success('No es posible cambiar una solicitud ya reembolzada', []);
    }

    // var_dump($resultDuplicateSolicitud);
    // 4 rechazada, 3 aprobada
    if ($resultDuplicateSolicitud[0]['estado_id'] === 4 && $data['estado_id'] === 3) {
      Response::success('Solicitud actual es rechazada, no se puede cambiar al estado aprobado', []);
    }

    $resultActualizarSolicitud = $this->gstSC->update($data);
    if ($resultActualizarSolicitud) {
      $idSolicitud = $data['id_solicitud'];
      if ($resultActualizarSolicitud) {
        // creamos el log luego de validar que todo el proceso fue exitoso.
        $dataEncode = Utils::returnGetEncode($data);
        $datosLog['id_solicitud'] = $idSolicitud;
        $datosLog['nombre_proceso'] = "UPDATE";
        $datosLog['informacion'] = $dataEncode;
        $datosLog['created_at'] = "";
        $datosLog['updated_at'] = "";
        $resultLog = $this->gstLog->createLog($datosLog);
        if ($resultLog) {
          Response::success("Solicitud nro $idSolicitud aprobada por validación documental", [$resultActualizarSolicitud]);
        }
      }
    }
  }

  public function getSolicitudes()
  {

    header('Content-Type: application/json; charset=utf-8');
    $data = Utils::returnGetDecode();

    $filtrosDefinidos = ['id_estado', 'fecha_inicio', 'fecha_fin', 'identificacion_cliente', 'asesor_id'];
    $mapasDeFiltros = [
      'id_estado' => 'es.id_estado',
      'identificacion_cliente' => 'cl.nro_identificacion',
      'asesor_id' => 'us_asesor.id_rol' # Se usa el id del rol siendo 1 auxiliar y 2 asesor
    ];

    if (!empty($data['filtros'])) {
      if (count($data['filtros']) > 1) {
        Response::success('maximo 1 filtro permito', []);
      }

      $filtroFinal = [];
      if (count($data['filtros']) === 1) {
        foreach ($data['filtros'] as $key => $value) {
          if (!in_array($key, $filtrosDefinidos)) {
            Response::success("Filtro $key no permitido", []);
          }
          $columnaAlias = $mapasDeFiltros[$key];
          $filtroFinal[$columnaAlias] = $value;
        }
        unset($data['filtros']);
        $data['filtros'] = $filtroFinal;
      }
    }

    // count paginas
    $countSolicitudes = $this->gstSC->count();
    $limit = $data['limit'] ?? 3;
    $page = $data['page'] ?? 1;
    // Logica del paginado
    $resultPaginate = Utils::executePaginate($countSolicitudes, $limit, $page);
    if (empty($data['filtros'])) {
      $resultSelect = $this->gstSC->select($resultPaginate, []);
    } else {
      $resultSelect = $this->gstSC->select($resultPaginate, $data['filtros']);
    }


    if (count($resultSelect) === 0) {
      Response::success('Sin registros', $resultSelect);
    }

    Response::success("Solicitudes encontradas $countSolicitudes", $resultSelect);
  }

  public function verDetalle()
  {
    header('Content-Type: application/json; charset=utf-8');

    $data = Utils::returnGetDecode();
    $result = $this->gstSC->getDetail($data);
    if (empty($result)) {
      Response::success('la solicitud no existe', $result);
    }

    Response::success('Detalle de solicitud', $result);
  }
}
