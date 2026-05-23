<?php

include_once 'GstSolicitudesCredito.php';

class SolicitudesCreditoController
{
  protected GstSolicitudesCredito $gstSC;
  public function __construct()
  {
    $this->gstSC = new GstSolicitudesCredito();
  }

  public function renderMainView()
  {
    return include_once __DIR__ . '/../View/SCView.php';
  }
}
