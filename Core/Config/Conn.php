<?php

require_once __DIR__ . '/Config.php';

class Conn
{
  private \PDO $conn;

  public function __construct()
  {
    $this->setConnect();
  }

  public function setConnect()
  {
    try {
      $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . CHARSET;

      $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_PERSISTENT => true
      ];

      $this->conn = new PDO($dsn, DB_USER, DB_PASS, options: $options);

      return $this->conn;
    } catch (\PDOException $e) {
      die("Conexión fallida (PDO): " . $e->getMessage());
    }
  }

  public function getConnect(): \PDO
  {
    return $this->conn;
  }
}
