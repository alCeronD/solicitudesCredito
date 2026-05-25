<?php

include_once __DIR__ . '/../Model/SolicitudesCreditoModel.php';
include_once __DIR__ . '/../../Clientes/Model/ClientesModel.php';
include_once __DIR__ . '/../../Usuarios/Model/UsuariosModel.php';
include_once __DIR__ . '/../../LogSolicitudes/Model/LogSolicitudesModel.php';
include_once __DIR__ . '/../../../Helpers/Crud.php';
include_once __DIR__ . '/../../../Config/Conn.php';

class GstSolicitudesCredito
{

  protected SolicitudesCreditoModel $mlSolicitudCredito;
  protected ClientesModel $mlClientes;
  protected UsuariosModel $mlUser;
  protected LogSolicitudesModel $mlLog;
  protected PDO $conn;

  public function __construct()
  {
    $this->mlSolicitudCredito = new SolicitudesCreditoModel();
    $this->mlClientes = new ClientesModel();
    $this->mlUser = new UsuariosModel();
    $this->mlLog = new LogSolicitudesModel();
    $this->conn = (new Conn())->getConnect();
  }

  // aca va la logica del insert para guardar el registro en la base de datos
  public function insertSolicitud(array $datos = [])
  {
    $dataInsert['data'] = $datos;
    $idSolicitud = $this->mlSolicitudCredito->insert($datos)->prepareSql($dataInsert)->get();
    return $idSolicitud;
  }

  public function select(array $resultPaginate = [], $filtros = [])
  {

    $sql = "SELECT
      sl.numero_credito AS 'numero_credito',
      cl.nombre_completo AS 'cliente',
      cl.nro_identificacion AS 'identificacion_cliente',
      sl.valor_solicitado AS 'valor_solicitado',
      es.nombre AS 'estado',
      us_asesor.nombre_completo AS 'asesor',
      us_auxiliar.nombre_completo AS 'auxiliar',
      sl.created_at AS 'fecha_creacion'
      FROM solicitudes sl
      INNER JOIN clientes cl ON cl.cliente_id = sl.cliente_id
      INNER JOIN estados es ON sl.estado_id = es.id_estado
      LEFT JOIN usuarios us_asesor ON us_asesor.id_usuario = sl.asesor_id
      LEFT JOIN usuarios us_auxiliar ON us_auxiliar.id_usuario = sl.auxiliar_id ";

    $condiciones = [];
    $parametrosFiltros = [];
    if (count($filtros) > 0) {
      foreach ($filtros as $key => $value) {
        $nombreMarcador = str_contains($key, '.') ? explode('.', $key)[1] : $key;
        $condiciones[] = "{$key} = :{$nombreMarcador}";
        $parametrosFiltros[$nombreMarcador] = $value;
      }
      if (count($condiciones) > 1) {
        $sql .= " WHERE " . implode(' AND ', $condiciones);
      } else {
        $sql .= " WHERE $condiciones[0]";
      }
    }

    $sql .= " ORDER BY sl.id_solicitud ASC LIMIT :limit OFFSET :offset";

    $stmt = $this->conn->prepare($sql);

    // aplicamos valores al filtro
    foreach ($parametrosFiltros as $key => $value) {
      $marcador = ":" . $key;
      $stmt->bindValue($marcador, $value, PDO::PARAM_INT);
    }

    // aplicamos valores a la paginacion
    foreach ($resultPaginate as $key => $value) {
      if ($key === 'totalPaginas') {
        continue;
      }
      if (str_contains($sql, ':limit') && str_contains($sql, 'offset')) {
        $marcador = ":" . $key;
        $stmt->bindValue($marcador, $value, PDO::PARAM_INT);
      }
    }

    if (!$stmt->execute()) {
      return false;
    }

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
  }

  public function getDetail(array $datos = [])
  {
    $dataId['data'] = $datos;

    $this->mlSolicitudCredito->select(true);
    // Extraemos la solicitud
    $resultSolicitudId = $this->mlSolicitudCredito->where(true, 'id_solicitud')->prepareSql($dataId)->get();
    // Extraemos las keys relevantes para formar el detail.
    $asesorId = $resultSolicitudId[0]['asesor_id'];
    $auxiliarId = $resultSolicitudId[0]['auxiliar_id'];
    $clienteId = $resultSolicitudId[0]['cliente_id'];
    // falta historico
    $historicoLog = $resultSolicitudId[0]['id_solicitud'];
    // var_dump($resultSolicitudId);

    // estructura para extraer la informacion de manera independiente.
    $dataCliente['data'] = ['cliente_id' => $clienteId];
    $dataAsesor['data'] = ['id_usuario' => $asesorId];
    $dataAuxiliar['data'] = ['id_usuario' => $auxiliarId];
    $dataHistorico['data'] = ['id_solicitud' => $historicoLog];
    $resultClienteInfo = $this->mlClientes->select(true)->where(true, 'cliente_id')->prepareSql($dataCliente)->get();
    $resultAsesorInfo = $this->mlUser->select(true)->where(true, 'id_usuario')->prepareSql($dataAsesor)->get();
    $resultAuxiliarInfo = $this->mlUser->select(true)->where(true, 'id_usuario')->prepareSql($dataAuxiliar)->get();
    $resultHistoricoInfo = $this->mlLog->select(true)->where(true, 'id_solicitud')->prepareSql($dataHistorico)->get();

    var_dump($resultHistoricoInfo);
    die();






    die();
    $idSolicitud = $resultSolicitudId[0]['id_solicitud'];
    // $sql = "SELECT
    //   sl.numero_credito AS 'numero_credito',
    //   cl.nombre_completo AS 'cliente',
    //   cl.nro_identificacion AS 'identificacion_cliente',
    //   sl.valor_solicitado AS 'valor_solicitado',
    //   es.nombre AS 'estado',
    //   us_asesor.nombre_completo AS 'asesor',
    //   us_auxiliar.nombre_completo AS 'auxiliar',
    //   sl.created_at AS 'fecha_creacion'
    //   FROM solicitudes sl
    //   INNER JOIN clientes cl ON cl.cliente_id = sl.cliente_id
    //   INNER JOIN estados es ON sl.estado_id = es.id_estado
    //   LEFT JOIN usuarios us_asesor ON us_asesor.id_usuario = sl.asesor_id
    //   LEFT JOIN usuarios us_auxiliar ON us_auxiliar.id_usuario = sl.auxiliar_id WHERE sl.id_solicitud = :id_solicitud";

    // $stmtDetail = $this->conn->prepare($sql);

    // $stmtDetail->bindValue(":id_solicitud", $idSolicitud, PDO::PARAM_INT);

    // $stmtDetail->execute();

    // $detail = $stmtDetail->fetchAll(PDO::FETCH_ASSOC);

    // var_dump($detail);


    // var_dump($idSolicitud);
    var_dump($idSolicitud);
    die();

    return true;
  }
  public function count()
  {
    return $this->mlSolicitudCredito->select()->count()->prepareSql()->get();
  }

  public function validateUnique(string $columnValidate, array $datos = [])
  {
    $this->mlSolicitudCredito->select(true);
    $dataValidateUnique['data'] = $datos;
    $resultUnique = $this->mlSolicitudCredito->where(true, $columnValidate)->prepareSql($dataValidateUnique)->get();
    return $resultUnique;
  }
}
