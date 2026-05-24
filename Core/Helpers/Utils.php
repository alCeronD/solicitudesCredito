<?php

class Utils
{
  public static function ajaxGeneral()
  {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
  }
  /**
   * returnGetDecode - function para devolver la información en arreglo asociativo que se recibe por petición
   *
   * @return array
   */
  public static function returnGetDecode()
  {
    if (ob_get_length()) ob_clean();

    $json = file_get_contents("php://input");

    $data = json_decode($json, true);

    return $data;
  }

  public static function returnGetEncode(array $datos)
  {
    return json_encode($datos);
  }
}
