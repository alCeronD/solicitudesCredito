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

  /**
   * Function para ejecutar la estructura de paginado y re utilizar su logica en otros controllers
   *
   * @param integer $cantidadRegistros - el Count de la tabla
   * @param integer $limit - si el usuario quiere ver 7 o 10, 20, 100 registros
   * @param integer $paginaActual - pagina reciente que el usuario visualiza
   * @return array
   */
  public static function executePaginate(int $cantidadRegistros = 1, int $limit = 3, int $paginaActual = 1)
  {
    # colocamos la division con el max para evitar que no se divida con 0, en caso de que le pasemos 0 en la variable cantidad de registros
    $totalPaginas = (int) ceil($cantidadRegistros / max(1, $limit));
    if ($paginaActual > $totalPaginas) {
      $paginaActual = (int) $totalPaginas;
    }
    if ($paginaActual < 1) {
      $paginaActual = (int) 1;
    }
    $offset = ($paginaActual - 1) * $limit;
    return [
      'offset' => $offset,
      'totalPaginas' => $totalPaginas,
      'limit' => $limit
    ];
  }

  public static function returnGetEncode(array $datos)
  {
    return json_encode($datos);
  }
}
