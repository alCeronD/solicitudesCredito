<?php

require_once __DIR__ . '/../Config/Conn.php';

#Clase crud para crear toda la estructura general de consultas.
/**
 * Listado de funciones requeridas
 * select
 * insert
 * order by
 * group by
 * inner join
 * left join
 * natural join
 * right join
 */


abstract class Crud
{
  protected $conn; # En donde se guarda la conexion
  protected $sql; # String que crea la consulta sql
  protected $table; # Nombre de la tabla, hereda su valor desde el modelo
  protected $campos; # Arreglo que contiene campos de la tabla, hereda su valor desde el modelo
  protected $id; # primary key de la tabla, hereda su valor desde el modelo
  protected $typeCampos; # arreglo que tiene los tipos de datos de la tabla, su orden es igual orden de la tabla
  protected $typedCasted; # String que me devuelve los tipos de datos casteados segun su estructura.
  protected $stmt; # en donde se guarda el mysqliprepared
  protected $typeId; # tipo de dato del primary key
  protected $operators = [
    '=',
    '!=',
    '<>',
    '<',
    '>',
    '<=',
    '>=',
    'BETWEEN',
    'IN',
    'IS NULL',
    'LIKE'
  ]; # arreglo para validar e implementar las condicionales

  public function __construct()
  {
    $this->conn = (new Conn)->getConnect();
  }

  # Función para transformar el arreglo de campos en cadenas de string
  public function organizarCampos(array $datos = ["*"])
  {
    $cadena = "";
    foreach ($datos as $dta) {
      // echo $datos;
      $cadena .= $dta . ", ";
    }

    return trim($cadena, ", ");
  }

  # Funcion para organizar los campos de los datos de la tabla en caso de que los vamos a realizar proceso transaccional como insert o update.
  public function organizarDatos($datos)
  {
    $string = "";

    // Concatenamos con signos de interrogacion para preparar la consulta.
    foreach ($datos as $key => $camp) {
      // valido que las keys esten en el modelo de las tablas;
      if (in_array($key, $this->campos)) {
        if ($key === 'created_at' || $key === 'updated_at') {
          $string .= "NOW()" . ", ";
        } else {
          $string .= ":$key" . ", ";
        }
      }
    }



    return trim($string, ", ");
  }

  /**
   * Function para crear los campos a actualizar junto a la cantidad de parametros, es una function auxiliar.
   *
   * @param array $datos - arreglo clave valor con el name del input que viene desde el formulario y el value debe ser el valor a actualizar.
   * @return string
   */
  public function organizarDatosUpdate(array $datos = [])
  {
    $sql = "";
    foreach ($datos as $key => $value) {

      if (in_array($key, $this->campos)) {
        $sql .= "$key = ? ,";
      }
    }
    return trim($sql, ", ");
  }

  /**
   * function para crear sentencia select
   *
   * @param boolean $campos - flag para determinar si hacemos un select con * o en su defecto con los campos de la tabla sin su id.
   * @return $this
   */
  public function select(bool $campos = false)
  {
    $campos = ($campos) ? ["*"] : $this->campos;


    // var_dump($campos);
    $this->sql = "SELECT " . $this->organizarCampos($campos) . " FROM " . $this->table;
    return $this;
  }
  public function insert(array $insertValue)
  {
    $this->sql = "INSERT INTO " . $this->table . " (" . $this->organizarCampos($this->campos) . ") VALUES (" . $this->organizarDatos($insertValue) . ")";
    return $this;
  }
  public function delete()
  {
    $this->sql = "DELETE FROM " . $this->table;
    return $this;
  }

  /**
   * function Crear estructura UPDATE para la actualizacion de datos
   *
   * @param array $datos - arreglo clave valor en donde la clave debe ser igual al valor asignado en name del input de los formularios, y value su valor a actualizar.
   * @return $this
   */
  public function update(array $datos = [])
  {
    $valuesUpdate = $this->organizarDatosUpdate($datos);
    $this->sql .= "UPDATE $this->table SET $valuesUpdate";
    return $this;
  }
  public function where(bool $campo = false, string $nombreCampo = "", array $datos = [])
  {

    if ($campo) {
      $this->sql .= " WHERE $nombreCampo = :$nombreCampo";
      return $this;
    }

    // necesito los datos, para validar que existen y asi validarlos, los operadores de comparacion
    if (array_key_exists($this->id, $datos)) {
      $this->sql .= " WHERE $this->id = :$this->id";
    }
    return $this;
  }

  /**
   * Function para devolver la cantidad de registros de una tabla
   *
   * @return $this
   */
  public function count()
  {
    $this->sql = "SELECT COUNT(*) FROM $this->table";
    return $this;
  }

  public function groupBy() {}

  /**
   * Function para definir el orden del campo, como parámetro se recomienda enviar el index de la tabla, por defecto, asigna el id.
   * @param string|null $campo - String o null para definir el index de la tabla, si no se envia nada, se determina que es el id de la tabla, en caso contrario, se valida la información enviada
   * @param boolean $ASC - flag para determinar si es ascendente o descendente dependiendo del booleano recibido
   * @return $this;
   */
  public function orderBy(string $campo = "", bool $ASC = true)
  {
    #SELECT * FROM `GeneralCrud` ORDER BY gc_id ASC LIMIT 5 OFFSET 5;
    $campoValido = "";
    if (empty($campo)) {
      $campoValido = $this->id;
    }

    // Function para definir el orden del campo, como parámetro se recomienda enviar el index de la tabla, por defecto, asigna el id.

    if (in_array($campo, $this->campos)) {
      $campoValido = $campo;
    }


    $ASC = ($ASC) ? 'ASC' : 'DESC';
    $this->sql .= " ORDER BY $campoValido $ASC";
    return $this;
  }
  public function limit()
  {
    $this->sql .= " LIMIT ?";
    return $this;
  }

  public function offset()
  {
    $this->sql .= " OFFSET ?";

    return $this;
  }


  # Function para preparar la consulta y pasar los valores por referencia
  public function prepareSql(array $datos = [])
  {
    $select = explode(' ', $this->sql);

    $this->stmt = $this->conn->prepare($this->sql);
    $typesData = "";

    #Extraigo los tipos de datos
    $types = $typesData ?? [];

    #Extraigo la informacion
    $data = isset($datos['data']) ? $datos['data'] : [];
    unset($data['created_at']);
    unset($data['updated_at']);
    // Si es un select, solamente preparamos la consulta y retornamos su resultado
    if ((strpos($this->sql, 'SELECT ') === 0) && ($select[0] === "SELECT ")) {

      // validar si tiene un COUNT para solo devolver la consulta
      $hasCount = str_contains($this->sql, "COUNT ");
      if ($hasCount) {
        return $this;
      }

      // validar si el string contiene o WHERE u OFFSET O LIMIT
      $hasOffset = str_contains($this->sql, "OFFSET");
      $hasLimit = str_contains($this->sql, "LIMIT");
      # Validar si requiere paginación
      // if ($hasOffset && $hasLimit) {
      //   $this->stmt->bindValue($types, ...$data);
      // }

      foreach ($data as $key => $value) {
        $marcador = (strpos($key, ':') === 0) ? $key : ":" . $key;

        if (str_contains($this->sql, $marcador)) {
          $this->stmt->bindValue($marcador, $value);
        }
      }



      return $this;
    } else {

      foreach ($data as $key => $value) {
        $marcador = (strpos($key, ':') === 0) ? $key : ":" . $key;

        if (str_contains($this->sql, $marcador)) {
          $this->stmt->bindValue($marcador, $value);
        }
      }
      return $this;
    }

    return $this;
  }
  /**
   * function para castear los tipos de datos de las tablas y devolver un string con el tipo de dato: ejemplo: [s,s,s,i] = devolver un sssi
   *
   * @param array $datos
   * @return string;
   */
  public function castParam(array $datos = [], array $tiposDatos = [])
  {
    # variable en donde vamos a adjuntar poco a poco la cantidad de tipos de datos basados en la consulta
    $finalTypes = "";


    # verificar primero cuantos argumentos hay
    $cantidadParametros = substr_count($this->sql, "?");

    # Retornamos el tipo del id que esta definido en el modelo
    if (str_contains(strtoupper($this->sql), 'DELETE')) {
      return $this->typedCasted .= $this->typeId;
    }

    # Validar si la consulta tiene un OFFSET o LIMIT
    if (str_contains(strtoupper($this->sql), 'OFFSET') && str_contains(strtoupper($this->sql), 'LIMIT')) {
      $this->typedCasted .= "ii";
    }

    // si la estructura tiene UPDATE,DELETE, IMPLEMENTAR EL WHERE
    if (str_contains(strtoupper($this->sql), "UPDATE ")) {
      foreach ($tiposDatos as $key => $value) {
        if (isset($datos['data'][$key])) {
          $this->typedCasted .= $tiposDatos[$key];
        }
      }
    }

    if (str_contains(strtoupper($this->sql), 'INSERT')) {
      foreach ($tiposDatos as $key => $value) {
        if (isset($datos['data'][$key])) {
          $this->typedCasted .= $tiposDatos[$key];
        }
      }
    }

    // validar si hay un where para implementar el tipo de dato integer
    if (str_contains(strtoupper($this->sql), 'WHERE')) {
      $this->typedCasted .= $this->typeId;
    }

    return $this->typedCasted;
  }

  # Obtener el resultado sql y devolverlo


  public function get()
  {
    try {
      # Variable para verificar si es un select
      $checkSelect = explode(' ', $this->sql);

      $this->stmt->execute();

      $sqlUpper = strtoupper($this->sql);
      $resultadoFinal = null;
      $id = null;
      if ((strpos($this->sql, 'SELECT') === 0) && ($checkSelect[0] === "SELECT")) {
        if (str_contains($sqlUpper, "COUNT")) {
          $resultadoFinal = (int) $this->stmt->fetchColumn();
        } else {
          $resultadoFinal = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        }
      } elseif (str_contains($sqlUpper, "UPDATE")) {
        $resultadoFinal = $this->stmt->rowCount();
      } elseif (str_contains($sqlUpper, "INSERT INTO")) {
        $resultadoFinal = (int) $this->conn->lastInsertId();
      }

      if (str_contains($sqlUpper, "INSERT INTO")) {
        $id = $this->conn->lastInsertId();
        $resultadoFinal = $id;
      }


      if (str_contains($sqlUpper, "INSERT INTO")) {
        $this->stmt = null;
        $this->sql = "";
        return $id;
      } else {
        $this->sql = "";
        $this->stmt = null;
        return $resultadoFinal;
      }

      return $resultadoFinal;
    } catch (\Exception $e) {

      $this->sql = "";
      $this->stmt = null;
      return $e->getMessage();
    }
  }

  public function getPrimaryKey()
  {
    return $this->id;
  }

  # Funcion de prueba para verificar como esta armada la sql
  public function showSql()
  {
    return $this->sql;
  }
}
