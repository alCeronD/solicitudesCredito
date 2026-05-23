<?php

include_once 'Core/Config/Conn.php';
include_once 'Core/Helpers/Router.php';
include_once 'Core/Helpers/Const.php';
include_once 'Core/Helpers/Utils.php';

$modulo = $_GET['SolicitudesCredito'] ?? 'SolicitudesCredito';
$controlador = $_GET['SolicitudesCredito'] ?? 'SolicitudesCredito';
$function = $_GET['renderMain'] ??  'renderMainView';

if (Utils::ajaxGeneral()) {
  Router::ExecuteFunction($modulo, $controlador, $function);
  exit;
}

$conn = (new Conn)->getConnect();


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo CR_TITLE ?></title>
</head>

<body>
  <h1><?php echo CR_TITLE; ?></h1>

  <div class="container">
    <?php Router::ExecuteFunction($modulo, $controlador, $function); ?>
  </div>

</body>

</html>