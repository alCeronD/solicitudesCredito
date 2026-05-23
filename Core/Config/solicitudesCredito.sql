CREATE DATABASE IF NOT EXISTS `solicitudescredito`
    DEFAULT CHARACTER SET = 'utf8mb4'
    DEFAULT COLLATE = 'utf8mb4_unicode_ci';

CREATE USER IF NOT EXISTS 'dev'@'localhost'
    IDENTIFIED BY '1234';

GRANT ALL PRIVILEGES ON `solicitudescredito`.* TO 'dev'@'localhost';

FLUSH PRIVILEGES;