<?php

require_once __DIR__ . '/../Model/LogSolicitudesModel.php';
class GstLogSolicitudes
{
  protected LogSolicitudesModel $logSolicitudes;
  public function __construct()
  {
    $this->logSolicitudes = new LogSolicitudesModel();
  }

  public function createLog(array $datosLog = [])
  {
    $finalDatosLog['data'] = $datosLog;
    $resultLog = $this->logSolicitudes->insert($datosLog)->prepareSql($finalDatosLog)->get();
    return $resultLog;
  }
}
