# Prueba tecnica, respuestas

---

## Explicacion del proyecto

### Modelo de datos

Se realiza el análisis basado en el caso entregado y se define las tablas con las cuales se van a trabajar, son las siguientes:
  - clientes
  - estados
  - logSolicitud
  - rol
  - rolicitudes
  - usuarios

las relaciones implementas son 1:1 entre la tabla **usuarios** y **roles**. Para las relaciones 1:n se asociaron las siguientes tablas: **Solicitudes** con el id de la tabla **Clientes**, id del estado con la tabla **Estados** y id usuario con la tabla usuario **Usuarios**. Esto con el fin de tener presente cual es la solicitud, el estado presentado, a quien esta asociado dicha solicitud y como asesor y auxiliar, quien se encarga del proceso.

### Estructura de carpetas

se entrega la estructura de carpetas con comentarios relacionados a cada archivo.
El enfoque es el siguiente:

el proyecto es un mvc modular en donde dentro de la carpeta Modules se encuentra por carpetas cada modulo requerido para la usabilidad de la api rest, en las cuales se encuentran: clientes, estados, SolicitudesCredito y LogSolicitudes

En donde su uso es el siguiente:

- **Enrutamiento y controlador** (index.php y router.php, controladores, ejemplo GstSolicitudesCredito.php)
  Cuando se envia la peticion esta llega al controlador mediante un enrutador, el enrutador es el archivo **Router.php**, este ejecuta la logica de negocio requerida y se encarga de llamar a la clase de destion **GstNombreArchivo.php**
- **Clases de gestion** (GstNombreArchivo.php, ejemplo GstSolicitudesCredito.php)
  Cada modulo tiene una clase de gestion en caso de ser requerida, esta ejecuta se debe de encargar de ejecutar los procesos transaccionales usando el **ModeloArchivo.php**
- **Modelos** (NombreModeloModel.php, ejemplo SolicitudesCreditoModel.php)
  Los modelos tienen como finalidad mapear la tabla en donde tiene los siguientes campos:

  ```php
  #Ejemplo de modelo

    protected $id = 'llave primaria de la tabla';
  protected $table = 'nombreDeTabla';
  protected $typedCasted;
  protected $campos = []; # Arreglo con todos los campos de la tabla exceptuando la primary key
  protected $typeCampos = [ 'nombre_del_campo' => 'tipo_del_campo']; # Arreglo clave valor que contiene el nombre de de la tabla como clave y el tipo de campo permitido como key, esto no se usoporque se usaba cuando se estaba usando la conexion con mysqli
  protected $typeId = 'i'; # tipo de dato de la primary key
  ```

  El modelo siempre va a heredar de la clase **CRUD.PHP**

- **CRUD**
  La clase crud tiene como finalidad ejecutar el proceso transaccional, tiene varias funciones como create, update, select, prepare y get, se usa desde el modelo, es decir, cuando se crea el modelo, este si o si debe de extender de crud para instanciar el modelo y ejecutar las funciones que se encuentran en crud.

```bash
 /var/www/html/solicitudesCredito/ [main*] tree

 /var/www/html/solicitudesCredito/ [main*] tree
.
├── Core # Núcleo principal del proyecto
│   ├── Config # Carpeta con variables constantes y clases para la conexión en POO
│   │   ├── Config.php # Configuración de credenciales de la base de datos y constantes globales
│   │   ├── Conn.php # Clase manejador de conexión a la base de datos mediante PDO
│   │   └── solicitudesCredito.sql # Script de la base de datos (Estructura e inserciones iniciales)
│   ├── Helpers # Carpeta con clases para reutilizar sus respectivas funciones
│   │   ├── Const.php # Archivo con variables constantes (no se dio uso)
│   │   ├── Crud.php # Clase general para la creación de consultas (Query Builder)
│   │   ├── Response.php # Clase con funciones para estandarizar y reutilizar respuestas JSON/HTTP
│   │   ├── Router.php # Clase que consiste en renderizar el controlador y ejecutar el proceso (Enrutador)
│   │   └── Utils.php # Clase con funciones generales reutilizables (ejecutar la paginación, encode y decode de los datos )
│   └── Modules # Carpeta con todos los módulos de la aplicación
│       ├── Clientes # Módulo de gestión de clientes
│       │   ├── Controller # Carpeta que contiene los controladores del módulo
│       │   │   ├── ClientesController.php # Controlador del cliente que expone los endpoints o métodos públicos
│       │   │   └── GstClientes.php # Clase de gestión que ejecuta los procesos transaccionales y retorna su resultado al controlador
│       │   └── Model # Carpeta que contiene los modelos de datos del módulo
│       │       └── ClientesModel.php # Modelo que mapea la tabla clientes y maneja sus queries específicas
│       ├── Estados # Módulo de gestión de estados de las solicitudes
│       │   ├── Controller # Carpeta que contiene los controladores del módulo
│       │   │   └── GstEstados.php # Clase de gestión para la lógica de negocio y consulta de estados disponibles
│       │   └── Model # Carpeta que contiene los modelos de datos del módulo
│       │       └── EstadoModel.php # Modelo que mapea la tabla estados
│       ├── LogSolicitudes # Módulo de auditoría e histórico de solicitudes
│       │   ├── Controller # Carpeta que contiene los controladores del módulo
│       │   │   └── GstLogSolicitudes.php # Clase de gestión para registrar y consultar el histórico de cambios en las solicitudes
│       │   └── Model # Carpeta que contiene los modelos de datos del módulo
│       │       └── LogSolicitudesModel.php # Modelo que mapea la tabla logSolicitud (Historial de cambios de estado)
│       ├── SolicitudesCredito # Módulo principal para el procesamiento de créditos
│       │   ├── Controller # Carpeta que contiene los controladores del módulo
│       │   │   ├── GstSolicitudesCredito.php # Clase de gestión con la lógica transaccional para crear, actualizar y filtrar créditos
│       │   │   └── SolicitudesCreditoController.php # Controlador de solicitudes que recibe las peticiones HTTP y coordina la respuesta
│       │   ├── Model # Carpeta que contiene los modelos de datos del módulo
│       │   │   └── SolicitudesCreditoModel.php # Modelo que mapea la tabla solicitudes (Cálculo de fechas, Joins complejos)
│       │   └── View # Carpeta que contiene las vistas o componentes de renderizado del módulo
│       │       └── SCView.php # Clase o archivo encargado de estructurar la interfaz visual de las solicitudes de crédito
│       └── Usuarios # Módulo de gestión de usuarios del sistema (Asesores y Auxiliares)
│           └── Model # Carpeta que contiene los modelos de datos del módulo
│               └── UsuariosModel.php # Modelo que mapea la tabla usuarios para validar asesores y auxiliares vinculados
├── index.php # Punto de entrada único de la aplicación (Front Controller) en donde se ejecuta todo el proyecto
├── PRUEBA_TECNICA.MD # Documento con los requerimientos, alcance y directrices de la prueba técnica
└── README.MD # Documentación general del proyecto (Instrucciones de instalación, arquitectura y uso de las APIs)

```

### Instalacion y uso

#### Requerimientos minimos

- PHP 8.0 >=
- Servidor apache para iniciarlizar su localhost.

#### Instalacion

Descargue el archivo rar del repositorio: [https://github.com/alCeronD/solicitudesCredito](https://github.com/alCeronD/solicitudesCredito)

- Habilite el servicio de apache y la base de datos, tener en cuenta que deben estar pre instalados los servicios de php y apache, puede instalarlos manera independiente o en su defecto usando un gestor de desarrollo como xampp o wamp, de su preferencia.
- Cree el espacio para importar la base de datos, el nombre de la base de datos es: **solicitudescredito**
- Importe la respectiva base de datos, si al importarla no genera error pero al acceder al proyecto rechaza el usuario ejecute el siguiente script:

  ```sql
  # Omitir el create si ya ha creado la base de datos y pasar a la creacion del user (CREATE USER...)
  CREATE DATABASE IF NOT EXISTS `solicitudescredito`
    DEFAULT CHARACTER SET = 'utf8mb4'
    DEFAULT COLLATE = 'utf8mb4_unicode_ci';
  USE solicitudescredito;
  CREATE USER IF NOT EXISTS 'dev'@'localhost'
    IDENTIFIED BY '1234';

  GRANT ALL PRIVILEGES ON `solicitudescredito`.* TO 'dev'@'localhost';

  FLUSH PRIVILEGES;
  ```

- Luego de cargar los usuarios acceda a la carpeta del proyecto mediante la url, la url es relativa dependiendo de donde alojo, el proyecto, si lo alojo en xampp ingrese a localhost en su navegador y entre a la carpeta htdocs, ahi debera colocar la carpeta DESCOMPRIMIDA.

#### Uso del proyecto

Teniendo en cuenta que la estructura del proyecto es un MVC Modular en la cual el endpoint corresponde al controlador se crean las respectivas urls para su uso.

- **Urls usadas en insomia**:

la estructura de la url es la siguiente:
/index.php = archivo en donde se ejecuta todo.
/Modulo = modulo al cual se dara uso, en este caso, todo se ejecuta en el modulo SolicitudesCredito
/Controlador = nombre del controlador al cual se va llegar la informacion en formato json
/Funtion = function que se va a ejecutar.

- **TENER PRESENTE** - Copiar la ruta desde el string "/index.php" en adelante, ya que lo anterior representa a como accedo al proyecto, siendo xampp o wamp, diferente, en mi caso, estoy usando fedora linux y por ende, el proceso es algo diferente pero no cambia mucho.

En este caso le estamos indicando a la url que busque el modulo solicitudesCredito, acceda al Controlador con ese mismo nombre y ejecute la function CrearSolicitud.

Este mismo enfoque aplica para las demas url

- **Listado de urls**

se adjuntan las urls junto al body en formato json que se usaron en la prueba tecnica.

- Url para crear solicitud: [http://solicitudescredito.local/index.php?modulo=SolicitudesCredito&controlador=SolicitudesCredito&function=crearSolicitud](http://solicitudescredito.local/index.php?modulo=SolicitudesCredito&controlador=SolicitudesCredito&function=crearSolicitud)

- Url para Actualizar estado: [http://solicitudescredito.local/index.php?modulo=SolicitudesCredito&controlador=SolicitudesCredito&function=actualizarSolicitud](http://solicitudescredito.local/index.php?modulo=SolicitudesCredito&controlador=SolicitudesCredito&function=actualizarSolicitud)

- Url para listar las solicitudes: [http://solicitudescredito.local/index.php?modulo=SolicitudesCredito&controlador=SolicitudesCredito&function=getSolicitudes](http://solicitudescredito.local/index.php?modulo=SolicitudesCredito&controlador=SolicitudesCredito&function=getSolicitudes)

- Url para ver el detalle de las solicitudes: [http://solicitudescredito.local/index.php?modulo=SolicitudesCredito&controlador=SolicitudesCredito&function=verDetalle](http://solicitudescredito.local/index.php?modulo=SolicitudesCredito&controlador=SolicitudesCredito&function=verDetalle)

## RESPUESTAS PRUEBA TECNICA

### 3 Backend api rest

- **3.1 CREAR SOLICITUD**:

LAS URLS CON LAS QUE SE USO EL PROYECTO, SE ADJUNTA EL BODY Y LA RESPUESTA
- url: http://solicitudescredito.local/index.php?modulo=SolicitudesCredito&controlador=SolicitudesCredito&function=crearSolicitud

  ```JSON
  //JSON CREAR SOLICITUD --> -->
  //numero_credito: numero aleatorio generado por insomia usando el plugin faker, cambiar a un numero deseado. --> -->
  //BODY --> -->
  {
  "numero_credito": "4207032721201851",
  "cliente_id": 3,
  "valor_solicitado": 434344421,
  "asesor_id": 5344,
  "auxiliar_id": 24552
  }

  //POSIBLES RESPUESTAS BASADOS EN LA LOGICA DESARROLLADA
  {
    "status": true,
    "message": "Solicitud creada exitosamente",
    "data": [
      "15"
    ]
  }

  {
    "status": true,
    "message": "El tipo de dato del numero de credito es incorrecto",
    "data": [
      4207032721201852
    ]
  }
    {
    "status": true,
    "message": "El cliente no existe",
    "data": []
    }

      {
    "status": true,
    "message": "datos de auxiliar o asesor incorrectos",
    "data": []
  }

  ```

- **3.2 LISTA SOLICITUDES**

- url: http://solicitudescredito.local/index.php?modulo=SolicitudesCredito&controlador=SolicitudesCredito&function=getSolicitudes

```JSON
  //BODY
  {
    "page": 1, //Numero de pagina
    "limit": 3, //Limites visuales por pagina
    "filtros":{ //por falta de tiempo, se creo para que se valide 1 solo filtro, si marcan 2 filtros, el sistema arroja error.
      "id_estado": 1,
      "id_estado": 2
    }
  }

  // body cuando se envia mas de un filtro:
    {
    "page": 1, // si cambias el parametro page a 2 este muestra los siguientes registros.
    "limit": 3,
    "filtros":{
      "id_estado": 1,
      "id_cliente": 3
    }
  }

      //POSIBLES RESPUESTAS
    {
    "status": true,
    "message": "maximo 1 filtro permito",
    "data": []
  }

    {
    "status": true,
    "message": "Solicitudes encontradas 20",
    "data": [
      {
        "numero_credito": "66714571738",
        "cliente": "Alex Campos",
        "identificacion_cliente": 29114652,
        "valor_solicitado": 4444444,
        "estado": "Desembolsada",
        "asesor": "Ana",
        "auxiliar": "Isabella",
        "fecha_creacion": "2026-05-24 20:59:55"
      },
      {
        "numero_credito": "66714571732",
        "cliente": "Alex Campos",
        "identificacion_cliente": 29114652,
        "valor_solicitado": 4444444,
        "estado": "Desembolsada",
        "asesor": "Ana",
        "auxiliar": "Isabella",
        "fecha_creacion": "2026-05-24 22:51:28"
      },
      {
        "numero_credito": "11714571732",
        "cliente": "Isabel C",
        "identificacion_cliente": 67930032,
        "valor_solicitado": 4444444,
        "estado": "Creada",
        "asesor": "Ana",
        "auxiliar": "Isabella",
        "fecha_creacion": "2026-05-25 10:24:22"
      }
    ]
  }
```

- **3.3 VER DETALLE**

- url: http://solicitudescredito.local/index.php?modulo=SolicitudesCredito&controlador=SolicitudesCredito&function=verDetalle

```JSON
{
  "id_solicitud": 1
}

// cuando la solicitud es incorrecta
  {
    "status": true,
    "message": "la solicitud no existe",
    "data": []
  }

// respuesta cuando hay una solicitud, muestra todo el detalle
  {
    "status": true,
    "message": "Detalle de solicitud",
    "data": {
      "solicitud": {
        "id_solicitud": 1,
        "numero_credito": "66714571738",
        "valor_solicitado": 4444444,
        "asesor_id": 5344,
        "auxiliar_id": 24552,
        "estado_id": 5,
        "cliente_id": 3,
        "created_at": "2026-05-24 20:59:55",
        "updated_at": "2026-05-24 23:58:23"
      },
      "cliente": {
        "cliente_id": 3,
        "nro_identificacion": 29114652,
        "nombre_completo": "Alex Campos",
        "telefono": "53565444"
      },
      "asesor": {
        "id_usuario": 5344,
        "nombre_completo": "Ana",
        "id_rol": 2,
        "created_at": "2026-05-23 14:27:47",
        "updated_at": "2026-05-23 14:27:47"
      },
      "auxiliar": {
        "id_usuario": 24552,
        "nombre_completo": "Isabella",
        "id_rol": 1,
        "created_at": "2026-05-23 14:27:47",
        "updated_at": "2026-05-23 14:27:47"
      },
      "historico": [
        {
          "id_log": 1,
          "id_solicitud": 1,
          "nombre_proceso": "CREATE",
          "informacion": "{\"numero_credito\":\"66714571738\",\"cliente_id\":3,\"valor_solicitado\":4444444,\"asesor_id\":5344,\"auxiliar_id\":24552,\"estado_id\":1,\"created_at\":\"\",\"updated_at\":\"\"}",
          "created_at": "2026-05-24 20:59:55",
          "updated_at": "2026-05-24 20:59:55"
        },

      ]
    }
  }

```

- **3.4 CAMBIAR ESTADO**

-url: http://solicitudescredito.local/index.php?modulo=SolicitudesCredito&controlador=SolicitudesCredito&function=actualizarSolicitud


  ```JSON
  //BODY
    {
    "estado_id": 5,
    "id_solicitud":4,
    "observacion": "Solicitud aprobada por validación documental"
  }

  //RESPUESTA
    {
    "status": true,
    "message": "Solicitud nro 2 aprobada por validación documental",
    "data": [
      1
    ]
  }
    {
    "status": true,
    "message": "El estado es incorrecto",
    "data": [
      12
    ]
  }
    {
    "status": true,
    "message": "No es posible cambiar una solicitud ya reembolzada",
    "data": []
  }

  ```

### 4 SQL OBLIGATORIO

- **4.1 REPORTE GENERAL**

```SQL

SELECT
      sl.numero_credito AS 'numero_credito',
      cl.nro_identificacion AS 'identificacion_cliente',
      cl.nombre_completo AS 'nombre_cliente',
      sl.valor_solicitado AS 'valor_solicitado',
      es.nombre AS 'estado',
      us_asesor.nombre_completo AS 'nombre asesor',
      us_auxiliar.nombre_completo AS 'nombre auxiliar',
      sl.created_at AS 'fecha_creacion',
      sl.updated_at AS 'ultima_fecha_cambio_estado'
      FROM solicitudes sl
      INNER JOIN clientes cl ON cl.cliente_id = sl.cliente_id
      INNER JOIN estados es ON sl.estado_id = es.id_estado
      LEFT JOIN usuarios us_asesor ON us_asesor.id_usuario = sl.asesor_id
      LEFT JOIN usuarios us_auxiliar ON us_auxiliar.id_usuario = sl.auxiliar_id WHERE sl.created_at BETWEEN '2026-03-25' AND '2026-05-25';

```

- **4.2 clientes con solicitudes duplicadas**

```SQL

SELECT
      cl.nro_identificacion AS 'identificacion_cliente',
      cl.nombre_completo AS 'nombre_cliente',
      sl.valor_solicitado AS 'valor_solicitado',
      es.nombre AS 'estado',
      COUNT(sl.estado_id) AS total_estados
FROM clientes cl
LEFT JOIN solicitudes sl ON sl.cliente_id = cl.cliente_id
INNER JOIN estados es ON es.id_estado = sl.estado_id GROUP BY sl.cliente_id HAVING COUNT(sl.estado_id) > 1;

```

- **4.4 INDICES PROPUESTOS**

```SQL

# INDICES

# FILTRO INDICES POR FECHA DE CREACION:
ALTER TABLE solicitudes ADD INDEX index_created_at (created_at);

# FILTRO INDICE POR ESTADO - Esta ya se encuentra creada cuando se hace la relacion a la tabla estados.

ALTER TABLE solicitudes ADD INDEX fk_id_estado (estado_id);

# FILTRO INDICE IDENTIFICACION DEL CLIENTE -

ALTER TABLE clientes ADD UNIQUE INDEX index_nro_identificacion (nro_identificacion);

# FILTRO ENTRE SOLICITUD Y LOG DE ESTADOS
ALTER TABLE `logSolicitud` ADD INDEX index_log (id_solicitud);

```
