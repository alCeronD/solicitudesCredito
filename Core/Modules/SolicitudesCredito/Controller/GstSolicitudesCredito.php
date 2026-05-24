<?php

include_once __DIR__ . '/../Model/SolicitudesCreditoModel.php';
include_once __DIR__ . '/../../../Helpers/Crud.php';
include_once __DIR__ . '/../../../Config/Conn.php';

class GstSolicitudesCredito
{

  protected SolicitudesCreditoModel $mlSolicitudCredito;
  protected PDO $conn;

  public function __construct()
  {
    $this->mlSolicitudCredito = new SolicitudesCreditoModel();
    $this->conn = (new Conn())->getConnect();
  }

  // aca va la logica del insert para guardar el registro en la base de datos
  public function insert(array $datos = [])
  {
    $dataInsert['data'] = $datos;
    $resultPrepare = $this->mlSolicitudCredito->insert($datos)->prepareSql($dataInsert)->get();
    return $resultPrepare;
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
        // var_dump($condiciones);
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
