CREATE DATABASE IF NOT EXISTS `solicitudescredito`
    DEFAULT CHARACTER SET = 'utf8mb4'
    DEFAULT COLLATE = 'utf8mb4_unicode_ci';
USE solicitudescredito;
CREATE USER IF NOT EXISTS 'dev'@'localhost'
    IDENTIFIED BY '1234';

GRANT ALL PRIVILEGES ON `solicitudescredito`.* TO 'dev'@'localhost';

FLUSH PRIVILEGES;

-- LISTADO DE TABLAS: TBLCLIENTES, TBLUSUARIOS, TBLROL, TBLSOLICITUDES, TBLESTADO_SOLICITUDES, TBLLOG_SOLICITUD

CREATE TABLE IF NOT EXISTS clientes(
    id_cliente INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'llave primaria del cliente',
    nombre_completo VARCHAR(250) NOT NULL COMMENT 'Nombres y apellidos completos de quien solicita el credito',
    telefono VARCHAR(30) NOT NULL COMMENT 'Número telefónico de contacto',
    PRIMARY KEY (id_cliente)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='tabla en donde se alojan el listado de clientes';

ALTER TABLE clientes
    ADD COLUMN nro_identificacion INT(11) NOT NULL UNIQUE AFTER id_cliente;

ALTER TABLE clientes MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE clientes RENAME COLUMN id_cliente TO cliente_id;

DESC clientes;

SHOW CREATE TABLE clientes;

INSERT INTO clientes(nro_identificacion,nombre_completo, telefono) VALUES('203026718','Jhon doe', '3226534487');



CREATE TABLE IF NOT EXISTS usuarios(
    id_usuario INT(11) NOT NULL PRIMARY KEY COMMENT 'id primario representativo del usuario' ,
    nombre_completo VARCHAR(250) NOT NULL COMMENT 'Nombre completo del usuario',
    id_rol INT (11) NOT NULL COMMENT 'Id que representa que tipo de rol tiene el usuario, si es auxiliar o asesor',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '',
    CONSTRAINT fk_id_rol Foreign Key (id_rol) REFERENCES rol(id_rol)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='tabla que almacena los usuarios que interactuan con el sistema';

INSERT INTO usuarios (id_usuario,nombre_completo, id_rol) VALUES(5344,'Ana',2),(42213,'Mateo', 2),(66433,'Fernando', 2), (35555,'William', 2), (24932,'Paola', 1), (24552,'Isabella',1), (63444,'Alonso', 1) ;

SELECT * FROM solicitudes;

CREATE TABLE IF NOT EXISTS roles(
    id_rol INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'id primario identificativo del rol',
    nombre_rol VARCHAR(100) NOT NULL COMMENT 'nombre del rol',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='listado de roles';

INSERT INTO rol (nombre_rol) VALUES ('Auxiliar');
INSERT INTO rol (nombre_rol) VALUES ('Asesor');


CREATE TABLE IF NOT EXISTS solicitudes(
    id_solicitud INT(11) PRIMARY KEY AUTO_INCREMENT COMMENT 'id autoincrementable que representa la solicitud creada',
    valor_solicitado INT(50) NOT NULL COMMENT 'valor del crédito solicitado',
    asesor_id INT(11) NOT NULL COMMENT 'id identificativo del asesor',
    auxiliar_id INT(11) NOT NULL COMMENT 'id identificativo del auxiliar',
    id_estado INT(11) NOT NULL COMMENT 'estado en el cual se encuentra el proceso',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    constraint fk_id_estado Foreign Key (id_estado) REFERENCES estados(id_estado)
    )ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='tabla en donde almacenamos las solicitudes creadas';

ALTER TABLE solicitudes ADD COLUMN id_cliente INT(11) NOT NULL AFTER id_estado;
ALTER TABLE solicitudes ADD CONSTRAINT fk_id_cliente FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente);
ALTER TABLE solicitudes ADD CONSTRAINT fk_id_auxiliar FOREIGN KEY (auxiliar_id) REFERENCES usuarios(id_usuario);
ALTER TABLE solicitudes ADD CONSTRAINT fk_id_asesor FOREIGN KEY (asesor_id) REFERENCES usuarios(id_usuario);

ALTER TABLE solicitudes RENAME COLUMN valor_solicitado TO nro_credito;
ALTER TABLE solicitudes RENAME COLUMN nro_credito TO numero_credito;

ALTER TABLE solicitudes ADD COLUMN valor_solicitado INT(11) NOT NULL UNIQUE AFTER id_estado;

ALTER TABLE solicitudes MODIFY COLUMN valor_solicitado INT(11) AFTER nro_credito;
ALTER TABLE solicitudes MODIFY COLUMN id_cliente INT(40);

ALTER TABLE solicitudes MODIFY COLUMN numero_credito VARCHAR(250) NOT NULL UNIQUE;

ALTER TABLE solicitudes RENAME COLUMN id_cliente TO cliente_id;

ALTER TABLE solicitudes RENAME COLUMN id_estado TO estado_id;



CREATE TABLE IF NOT EXISTS estados(
    id_estado INT(11) PRIMARY KEY AUTO_INCREMENT COMMENT '',
    nombre VARCHAR(50) NOT NULL COMMENT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='tabla de estado para referenciar con la tabla solicitudes';

INSERT INTO estados(nombre, created_at, updated_at) VALUES ('Creada', NOW(), NOW()),('En revision', NOW(), NOW()),( 'Aprobada', NOW(), NOW()), ('Rechazada', NOW(), NOW()), ('Desembolsada', NOW(), NOW());


TRUNCATE TABLE estados;

ALTER TABLE solicitudes DROP FOREIGN KEY fk_id_estado;
ALTER TABLE solicitudes DROP KEY fk_id_estado;

--- nombre_proceso = que fue lo que se actualizo y el estado
-- informacion = CREATE, UPDATED,
CREATE TABLE IF NOT EXISTS logSolicitud(
    id_solicitud INT(11) PRIMARY KEY AUTO_INCREMENT COMMENT 'id identificativo del historico de las solicitudes',
    nombre_proceso VARCHAR(30) NOT NULL COMMENT 'nombre del proceso creado, si es un CREATE (primera vez) o UPDATE dependiendo del proceso',
    informacion TEXT NOT NULL COMMENT 'informacion guardada que fue modificada',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='historico de logs en donde se almacena cuando se hacen el cambio de estado de las solicitudes';

ALTER TABLE `logSolicitud` RENAME COLUMN id_solicitud TO id_log;
ALTER TABLE `logSolicitud` MODIFY COLUMN id_solicitud INT(11) NOT NULL AFTER id_log;

SELECT
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
      LEFT JOIN usuarios us_auxiliar ON us_auxiliar.id_usuario = sl.auxiliar_id WHERE sl.id_solicitud = 18;


      DELETE FROM solicitudes;

      SELECT * FROM clientes;


INSERT INTO clientes(nro_identificacion,nombre_completo, telefono) VALUES('67930032','Isabel C', '3222334487');

SELECT * FROM solicitudes;

ALTER TABLE solicitudes AUTO_INCREMENT = 500;

DELETE FROM solicitudes;

TRUNCATE TABLE solicitudes;

TRUNCATE TABLE `logSolicitud`;

SELECT * FROM logSolicitud;
SELECT * FROM solicitudes;
SELECT * FROM clientes;

SELECT * FROM usuarios;

DELETE FROM solicitudes;


UPDATE solicitudes SET estado_id = 1 WHERE id_solicitud = 1;
SELECT * FROM `logSolicitud`;

SELECT * FROM solicitudes;