<?php

/**
 * Clase para definir la ruta y la ejecución de funciones
 */
class Router
{
  public static function createRoute(String $modulo = "SolicitudesCredito", String $controller = "SolicitudesCredito", String $function = "renderMainView", Bool $parameters = false, String $pagina = 'index')
  {

    // Valida tipos de datos
    if (!is_string($modulo)) return;
    if (!is_string($controller)) return;
    if (!is_string($function)) return;

    if ($pagina != 'dashboard') {
      $url = "$pagina.php?modulo=$modulo&controlador=$controller&function=$function";
    } else {
      $url = $pagina . ".php?modulo=$modulo&controlador=$controller&function=$function";
    }

    return $url;
  }

  public static function ExecuteFunction(String $modulo = 'SolicitudesCredito', String $controlador = 'SolicitudesCredito', String $function = 'renderMainView')
  {

    $modulo = $_GET['modulo'] ?? $_POST['modulo'] ?? $modulo;
    $controlador = $_GET['controlador'] ?? $_POST['controlador'] ?? $controlador;
    $function = $_GET['function'] ?? $_POST['function'] ?? $function;

    if (!$modulo || !$controlador || !$function) {



      header('Content-Type: application/json');
      echo json_encode(['success' => false, 'message' => "Faltan parámetros de ejecución"]);
      exit;
    }

    $controladorFile = ucfirst($controlador) . "Controller.php";

    $rutaFile = __DIR__ . "/../Modules/$modulo/Controller/$controladorFile";

    if (!is_file($rutaFile)) {
      echo json_encode(['success' => false, 'message' => "No existe el controlador en $rutaFile"]);
      exit;
    }

    include_once $rutaFile;

    // Crear el nombre de la clase
    $nameController = $controlador . "Controller";

    $objController = new $nameController();

    if (method_exists($objController, $function)) {
      $objController->$function();
    } else {
      echo json_encode(['success' => false, 'message' => "La función $function no existe en el controlador"]);
    }
  }
}
