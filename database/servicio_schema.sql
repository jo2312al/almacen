-- Base de datos reconstruida para el proyecto servicio2.
-- Generada a partir de los modelos Yii2 ubicados en /models.
--
-- Uso recomendado desde MySQL/MariaDB:
--   mysql -u root -p < database/servicio_schema.sql
--
-- Despues de importar, revisa config/db.php para que coincidan usuario,
-- password, host, puerto y nombre de base de datos.

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `servicio`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `servicio`;

DROP TABLE IF EXISTS `archivo`;
DROP TABLE IF EXISTS `alumno`;
DROP TABLE IF EXISTS `caja`;
DROP TABLE IF EXISTS `servicio`;
DROP TABLE IF EXISTS `periodo`;
DROP TABLE IF EXISTS `generacion`;
DROP TABLE IF EXISTS `carrera`;
DROP TABLE IF EXISTS `anaquel`;
DROP TABLE IF EXISTS `nivelalmacenamiento`;
DROP TABLE IF EXISTS `fondo`;
DROP TABLE IF EXISTS `clave_programatica`;
DROP TABLE IF EXISTS `area_generadora`;
DROP TABLE IF EXISTS `seccion_serie`;
DROP TABLE IF EXISTS `ingreso`;
DROP TABLE IF EXISTS `usuario`;
DROP TABLE IF EXISTS `user_visit_log`;
DROP TABLE IF EXISTS `auth_assignment`;
DROP TABLE IF EXISTS `auth_item_child`;
DROP TABLE IF EXISTS `auth_item`;
DROP TABLE IF EXISTS `auth_rule`;
DROP TABLE IF EXISTS `auth_item_group`;
DROP TABLE IF EXISTS `user`;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `carrera` (
  `car_id` INT NOT NULL AUTO_INCREMENT,
  `car_nombre` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`car_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `generacion` (
  `gen_id` INT NOT NULL AUTO_INCREMENT,
  `gen_nombre` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`gen_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `periodo` (
  `per_id` INT NOT NULL AUTO_INCREMENT,
  `per_nombre` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`per_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `servicio` (
  `ser_id` INT NOT NULL AUTO_INCREMENT,
  `ser_anio` DATE NOT NULL,
  `ser_periodo_id` INT NULL,
  PRIMARY KEY (`ser_id`),
  KEY `idx_servicio_periodo` (`ser_periodo_id`),
  CONSTRAINT `fk_servicio_periodo`
    FOREIGN KEY (`ser_periodo_id`) REFERENCES `periodo` (`per_id`)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `alumno` (
  `alu_id` INT NOT NULL AUTO_INCREMENT,
  `alu_matricula` VARCHAR(8) NOT NULL,
  `alu_nombre` VARCHAR(50) NOT NULL,
  `alu_paterno` VARCHAR(50) NOT NULL,
  `alu_materno` VARCHAR(50) NOT NULL,
  `alu_generacion_id` INT NULL,
  `alu_ingreso` VARCHAR(10) NULL,
  `alu_servicio_id` INT NULL,
  `alu_carrera_id` INT NULL,
  PRIMARY KEY (`alu_id`),
  UNIQUE KEY `uq_alumno_matricula` (`alu_matricula`),
  KEY `idx_alumno_generacion` (`alu_generacion_id`),
  KEY `idx_alumno_servicio` (`alu_servicio_id`),
  KEY `idx_alumno_carrera` (`alu_carrera_id`),
  CONSTRAINT `fk_alumno_generacion`
    FOREIGN KEY (`alu_generacion_id`) REFERENCES `generacion` (`gen_id`)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `fk_alumno_servicio`
    FOREIGN KEY (`alu_servicio_id`) REFERENCES `servicio` (`ser_id`)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `fk_alumno_carrera`
    FOREIGN KEY (`alu_carrera_id`) REFERENCES `carrera` (`car_id`)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `anaquel` (
  `ana_id` INT NOT NULL AUTO_INCREMENT,
  `ana_nombre` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`ana_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `nivelalmacenamiento` (
  `niv_id` INT NOT NULL AUTO_INCREMENT,
  `niv_nombre` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`niv_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `caja` (
  `caj_id` INT NOT NULL AUTO_INCREMENT,
  `caj_codigo` VARCHAR(50) NOT NULL,
  `caj_anaquel_id` INT NULL,
  `caj_nivel_id` INT NULL,
  PRIMARY KEY (`caj_id`),
  UNIQUE KEY `uq_caja_codigo` (`caj_codigo`),
  KEY `idx_caja_anaquel` (`caj_anaquel_id`),
  KEY `idx_caja_nivel` (`caj_nivel_id`),
  CONSTRAINT `fk_caja_anaquel`
    FOREIGN KEY (`caj_anaquel_id`) REFERENCES `anaquel` (`ana_id`)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `fk_caja_nivel`
    FOREIGN KEY (`caj_nivel_id`) REFERENCES `nivelalmacenamiento` (`niv_id`)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `fondo` (
  `fon_id` INT NOT NULL AUTO_INCREMENT,
  `fon_codigo` VARCHAR(50) NOT NULL,
  `fon_descripcion` VARCHAR(255) NULL,
  PRIMARY KEY (`fon_id`),
  UNIQUE KEY `uq_fondo_codigo` (`fon_codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `clave_programatica` (
  `cla_id` INT NOT NULL AUTO_INCREMENT,
  `cla_codigo` VARCHAR(50) NOT NULL,
  `cla_descripcion` VARCHAR(255) NULL,
  PRIMARY KEY (`cla_id`),
  UNIQUE KEY `uq_clave_programatica_codigo` (`cla_codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `area_generadora` (
  `are_id` INT NOT NULL AUTO_INCREMENT,
  `are_codigo` VARCHAR(50) NOT NULL,
  `are_descripcion` VARCHAR(255) NULL,
  PRIMARY KEY (`are_id`),
  UNIQUE KEY `uq_area_generadora_codigo` (`are_codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `seccion_serie` (
  `sec_id` INT NOT NULL AUTO_INCREMENT,
  `sec_codigo` VARCHAR(10) NOT NULL,
  `sec_descripcion` VARCHAR(100) NULL,
  PRIMARY KEY (`sec_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `archivo` (
  `arc_id` INT NOT NULL AUTO_INCREMENT,
  `arc_codigo` VARCHAR(100) NOT NULL,
  `arc_nombre_documento` VARCHAR(100) NOT NULL,
  `arc_caja_id` INT NOT NULL,
  `arc_alumno_id` INT NOT NULL,
  `arc_ruta` VARCHAR(255) NOT NULL,
  `arc_fondo_id` INT NULL,
  `arc_clave_programatica_id` INT NULL,
  `arc_area_generadora_id` INT NULL,
  `arc_seccion_serie_id` INT NULL,
  PRIMARY KEY (`arc_id`),
  KEY `idx_archivo_caja` (`arc_caja_id`),
  KEY `idx_archivo_alumno` (`arc_alumno_id`),
  KEY `idx_archivo_fondo` (`arc_fondo_id`),
  KEY `idx_archivo_clave_programatica` (`arc_clave_programatica_id`),
  KEY `idx_archivo_area_generadora` (`arc_area_generadora_id`),
  KEY `idx_archivo_seccion_serie` (`arc_seccion_serie_id`),
  CONSTRAINT `fk_archivo_caja`
    FOREIGN KEY (`arc_caja_id`) REFERENCES `caja` (`caj_id`)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_archivo_alumno`
    FOREIGN KEY (`arc_alumno_id`) REFERENCES `alumno` (`alu_id`)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_archivo_fondo`
    FOREIGN KEY (`arc_fondo_id`) REFERENCES `fondo` (`fon_id`)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `fk_archivo_clave_programatica`
    FOREIGN KEY (`arc_clave_programatica_id`) REFERENCES `clave_programatica` (`cla_id`)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `fk_archivo_area_generadora`
    FOREIGN KEY (`arc_area_generadora_id`) REFERENCES `area_generadora` (`are_id`)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `fk_archivo_seccion_serie`
    FOREIGN KEY (`arc_seccion_serie_id`) REFERENCES `seccion_serie` (`sec_id`)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `ingreso` (
  `ing_id` INT NOT NULL AUTO_INCREMENT,
  `ing_anio` DATE NOT NULL,
  PRIMARY KEY (`ing_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `usuario` (
  `usu_id` INT NOT NULL AUTO_INCREMENT,
  `usu_nombre` VARCHAR(50) NOT NULL,
  `usu_paterno` VARCHAR(50) NOT NULL,
  `usu_materno` VARCHAR(50) NOT NULL,
  `usu_usuario` VARCHAR(20) NOT NULL,
  `usu_contrasena` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`usu_id`),
  UNIQUE KEY `uq_usuario_usuario` (`usu_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tablas usadas por webvimark/module-user-management.
CREATE TABLE `auth_rule` (
  `name` VARCHAR(64) NOT NULL,
  `data` TEXT NULL,
  `created_at` INT NULL,
  `updated_at` INT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `auth_item_group` (
  `code` VARCHAR(64) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `created_at` INT NULL,
  `updated_at` INT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `auth_item` (
  `name` VARCHAR(64) NOT NULL,
  `type` INT NOT NULL,
  `description` TEXT NULL,
  `rule_name` VARCHAR(64) NULL,
  `data` TEXT NULL,
  `created_at` INT NULL,
  `updated_at` INT NULL,
  `group_code` VARCHAR(64) NULL,
  PRIMARY KEY (`name`),
  KEY `idx_auth_item_type` (`type`),
  KEY `idx_auth_item_rule_name` (`rule_name`),
  KEY `idx_auth_item_group_code` (`group_code`),
  CONSTRAINT `fk_auth_item_rule`
    FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT `fk_auth_item_group`
    FOREIGN KEY (`group_code`) REFERENCES `auth_item_group` (`code`)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `auth_item_child` (
  `parent` VARCHAR(64) NOT NULL,
  `child` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`parent`, `child`),
  KEY `idx_auth_item_child_child` (`child`),
  CONSTRAINT `fk_auth_item_child_parent`
    FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_auth_item_child_child`
    FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL,
  `auth_key` VARCHAR(32) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `confirmation_token` VARCHAR(255) NULL,
  `status` INT NOT NULL DEFAULT 1,
  `superadmin` SMALLINT(1) NULL DEFAULT 0,
  `created_at` INT NOT NULL,
  `updated_at` INT NOT NULL,
  `registration_ip` VARCHAR(15) NULL,
  `bind_to_ip` VARCHAR(255) NULL,
  `email` VARCHAR(128) NULL,
  `email_confirmed` SMALLINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `auth_assignment` (
  `item_name` VARCHAR(64) NOT NULL,
  `user_id` INT NOT NULL,
  `created_at` INT NULL,
  PRIMARY KEY (`item_name`, `user_id`),
  KEY `idx_auth_assignment_user_id` (`user_id`),
  CONSTRAINT `fk_auth_assignment_item`
    FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_auth_assignment_user`
    FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `user_visit_log` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `token` VARCHAR(255) NOT NULL,
  `ip` VARCHAR(15) NOT NULL,
  `language` CHAR(2) NOT NULL,
  `user_agent` VARCHAR(255) NOT NULL,
  `user_id` INT NULL,
  `visit_time` INT NOT NULL,
  `browser` VARCHAR(30) NULL,
  `os` VARCHAR(20) NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_visit_log_user_id` (`user_id`),
  CONSTRAINT `fk_user_visit_log_user`
    FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos iniciales minimos.
INSERT INTO `periodo` (`per_id`, `per_nombre`) VALUES
  (1, 'Enero-Julio'),
  (2, 'Agosto-Diciembre');

INSERT INTO `nivelalmacenamiento` (`niv_id`, `niv_nombre`) VALUES
  (1, 'A'),
  (2, 'B'),
  (3, 'C'),
  (4, 'D');

INSERT INTO `fondo` (`fon_codigo`, `fon_descripcion`) VALUES
  ('DEFAULT', 'Fondo inicial');

INSERT INTO `area_generadora` (`are_codigo`, `are_descripcion`) VALUES
  ('DEFAULT', 'Area generadora inicial');

INSERT INTO `clave_programatica` (`cla_codigo`, `cla_descripcion`) VALUES
  ('DEFAULT', 'Clave programatica inicial');

INSERT INTO `seccion_serie` (`sec_codigo`, `sec_descripcion`) VALUES
  ('DEFAULT', 'Seccion/serie inicial');

INSERT INTO `auth_item_group` (`code`, `name`, `created_at`, `updated_at`) VALUES
  ('userManagement', 'User management', UNIX_TIMESTAMP(), UNIX_TIMESTAMP()),
  ('appRoles', 'Roles de la aplicacion', UNIX_TIMESTAMP(), UNIX_TIMESTAMP());

INSERT INTO `auth_item` (`name`, `type`, `description`, `created_at`, `updated_at`, `group_code`) VALUES
  ('admin', 1, 'Administrador del sistema', UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'appRoles'),
  ('prueba', 1, 'Usuario operativo', UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'appRoles'),
  ('viewer', 1, 'Usuario de solo lectura', UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'appRoles'),
  ('/*', 2, 'Acceso completo a rutas', UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 'userManagement');

INSERT INTO `auth_item_child` (`parent`, `child`) VALUES
  ('admin', '/*');

-- Usuario inicial de recuperacion.
-- Usuario: superadmin
-- Password por defecto del modulo Webvimark: superadmin
-- Cambiar inmediatamente despues de entrar.
INSERT INTO `user`
  (`id`, `username`, `auth_key`, `password_hash`, `status`, `superadmin`, `created_at`, `updated_at`, `email_confirmed`)
VALUES
  (1, 'superadmin', 'kz2px152FAWlkHbkZoCiXgBAd-S8SSjF',
   '$2y$13$MhlYe12xkGFnSeK0sO2up.Y9kAD9Ct6JS1i9VLP7YAqd1dFsSylz2',
   1, 1, UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), 0);

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`) VALUES
  ('admin', 1, UNIX_TIMESTAMP());

