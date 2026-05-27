/*
 Navicat Premium Data Transfer

 Source Server         : idioma
 Source Server Type    : MySQL
 Source Server Version : 80039 (8.0.39)
 Source Host           : localhost:3306
 Source Schema         : servicio

 Target Server Type    : MySQL
 Target Server Version : 80039 (8.0.39)
 File Encoding         : 65001

 Date: 28/04/2025 13:42:27
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for alumno
-- ----------------------------
DROP TABLE IF EXISTS `alumno`;
CREATE TABLE `alumno`  (
  `alu_id` int NOT NULL AUTO_INCREMENT,
  `alu_matricula` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `alu_nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `alu_paterno` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `alu_materno` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `alu_generacion_id` int NULL DEFAULT NULL,
  `alu_ingreso` year NULL DEFAULT NULL,
  `alu_servicio_id` int NULL DEFAULT NULL,
  `alu_carrera_id` int NULL DEFAULT NULL,
  PRIMARY KEY (`alu_id`) USING BTREE,
  INDEX `fk_alumno_generacion`(`alu_generacion_id` ASC) USING BTREE,
  INDEX `fk_alumno_servicio`(`alu_servicio_id` ASC) USING BTREE,
  INDEX `fk_alumno_carrera`(`alu_carrera_id` ASC) USING BTREE,
  CONSTRAINT `fk_alumno_carrera` FOREIGN KEY (`alu_carrera_id`) REFERENCES `carrera` (`car_id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `fk_alumno_generacion` FOREIGN KEY (`alu_generacion_id`) REFERENCES `generacion` (`gen_id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `fk_alumno_servicio` FOREIGN KEY (`alu_servicio_id`) REFERENCES `servicio` (`ser_id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of alumno
-- ----------------------------
INSERT INTO `alumno` VALUES (1, '21300877', 'Jose Alberto', 'Arechederra', 'Mejia', NULL, 2021, 103, 11);
INSERT INTO `alumno` VALUES (2, '21300877', 'Jose Alberto', 'Arechederra', 'Mejia', NULL, 2021, 18, 7);
INSERT INTO `alumno` VALUES (3, '21300877', 'Jose Alberto', 'Arechederra', 'Mejia', NULL, 2021, 17, 11);
INSERT INTO `alumno` VALUES (4, '21300877', 'Jose Alberto', 'Arechederra', 'Mejia', NULL, 2021, 16, 11);
INSERT INTO `alumno` VALUES (5, '21300877', 'Jose Alberto', 'Arechederra', 'Mejia', NULL, 2021, 18, 12);
INSERT INTO `alumno` VALUES (6, '21300877', 'Jose Alberto', 'Arechederra', 'Mejia', NULL, 2021, 17, 11);
INSERT INTO `alumno` VALUES (7, '21300877', 'Jose Alberto', 'Arechederra', 'Mejia', NULL, 2021, 17, 11);
INSERT INTO `alumno` VALUES (8, '21300877', 'Jose Alberto', 'Arechederra', 'Mejia', 155, 2021, 17, 11);
INSERT INTO `alumno` VALUES (9, '21300877', 'Jose Alberto', 'Arechederra', 'Mejia', NULL, 2021, 17, 9);
INSERT INTO `alumno` VALUES (10, '21300877', 'Jose Alberto', 'Arechederra', 'Mejia', NULL, 2021, 13, 7);
INSERT INTO `alumno` VALUES (11, '21300877', 'Jose Alberto', 'Arechederra', 'Mejia', NULL, 2021, 16, 11);
INSERT INTO `alumno` VALUES (12, '21300878', 'Jose Alberto', 'Arechederra', 'Mejia', 156, 2021, 103, 11);
INSERT INTO `alumno` VALUES (13, '21300878', 'Jose Alberto', 'Arechederra', 'Mejia', 156, 2021, 103, 11);
INSERT INTO `alumno` VALUES (14, '21300878', 'Jose Alberto', 'Arechederra', 'Mejia', 155, 2021, 16, 10);
INSERT INTO `alumno` VALUES (15, '21300878', 'Jose Alberto', 'Arechederra', 'Mejia', 155, 2021, 17, 12);
INSERT INTO `alumno` VALUES (16, '21300877', 'Jose Alberto', 'Arechederra', 'Mejia', 156, 2021, 154, 12);
INSERT INTO `alumno` VALUES (17, '21300877', 'Jose Alberto', 'Arechederra', 'Mejia', 155, 1990, 14, 12);
INSERT INTO `alumno` VALUES (18, '21300877', 'Jose Alberto', 'Arechederra', 'Mejia', 155, 1990, 17, 12);
INSERT INTO `alumno` VALUES (19, '21300877', 'Jose Alberto', 'Arechederra', 'Mejia', 155, 2021, 16, 4);

-- ----------------------------
-- Table structure for anaquel
-- ----------------------------
DROP TABLE IF EXISTS `anaquel`;
CREATE TABLE `anaquel`  (
  `ana_id` int NOT NULL AUTO_INCREMENT,
  `ana_nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`ana_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of anaquel
-- ----------------------------
INSERT INTO `anaquel` VALUES (1, 'AA0001');
INSERT INTO `anaquel` VALUES (3, 'AA0002');
INSERT INTO `anaquel` VALUES (4, 'AA0003');
INSERT INTO `anaquel` VALUES (5, 'AA0004');
INSERT INTO `anaquel` VALUES (6, 'AA0005');
INSERT INTO `anaquel` VALUES (7, 'AA0006');
INSERT INTO `anaquel` VALUES (8, 'AA0007');
INSERT INTO `anaquel` VALUES (9, 'AA0008');
INSERT INTO `anaquel` VALUES (10, 'AA0009');
INSERT INTO `anaquel` VALUES (11, 'AA0010');
INSERT INTO `anaquel` VALUES (12, 'AA0011');
INSERT INTO `anaquel` VALUES (13, 'AA0012');
INSERT INTO `anaquel` VALUES (14, 'AA0013');
INSERT INTO `anaquel` VALUES (15, 'AA0014');
INSERT INTO `anaquel` VALUES (16, 'AA0015');
INSERT INTO `anaquel` VALUES (17, 'AA0016');
INSERT INTO `anaquel` VALUES (18, 'AA0017');
INSERT INTO `anaquel` VALUES (19, 'AA0018');

-- ----------------------------
-- Table structure for archivo
-- ----------------------------
DROP TABLE IF EXISTS `archivo`;
CREATE TABLE `archivo`  (
  `arc_id` int NOT NULL AUTO_INCREMENT,
  `arc_codigo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `arc_nombre_documento` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `arc_caja_id` int NULL DEFAULT NULL,
  `arc_alumno_id` int NULL DEFAULT NULL,
  `arc_contenido` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`arc_id`) USING BTREE,
  INDEX `fk_archivo_caja`(`arc_caja_id` ASC) USING BTREE,
  INDEX `fk_archivo_alumno`(`arc_alumno_id` ASC) USING BTREE,
  CONSTRAINT `fk_archivo_alumno` FOREIGN KEY (`arc_alumno_id`) REFERENCES `alumno` (`alu_id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `fk_archivo_caja` FOREIGN KEY (`arc_caja_id`) REFERENCES `caja` (`caj_id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of archivo
-- ----------------------------
INSERT INTO `archivo` VALUES (6, '86030', 'U4 - T1 Experiencia de usuario.pdf', NULL, 11, 'C:\\xampp\\htdocs\\servicio2\\web/archivos/file_674d293d6aecf8.82950940.pdf');
INSERT INTO `archivo` VALUES (7, '86030', 'file_674d293d6aecf8.82950940 (3).pdf', 1, 8, 'C:\\xampp\\htdocs\\servicio2\\web/archivos/file_674d50eeaede31.09387117.pdf');
INSERT INTO `archivo` VALUES (8, '86030', 'file_674d50eeaede31.09387117.pdf', 1, 9, 'archivos/file_674d6f5f638a42.19827730.pdf');
INSERT INTO `archivo` VALUES (9, '86030', 'file_674d293d6aecf8.82950940 (1).pdf', 1, 13, 'archivos/file_674da990c9aea7.46145968.pdf');
INSERT INTO `archivo` VALUES (10, '860308888888888', 'Estrategias de Procesamiento de Consultas en Bases de Datos Distribuidas.pdf', 2, 13, 'archivos/file_674daa0904a9a6.50455961.pdf');
INSERT INTO `archivo` VALUES (11, '860308888888888hhhhh', 'file_674d50eeaede31.09387117.pdf', 1, 14, 'archivos/file_674dac315d6e92.81433048.pdf');
INSERT INTO `archivo` VALUES (12, '860308888888888hhhhh', 'file_674d50eeaede31.09387117.pdf', 2, 15, 'archivos/file_674ddc7c4616d8.49262324.pdf');
INSERT INTO `archivo` VALUES (13, '86030', 'file_674d293d6aecf8.82950940 (3).pdf', 1, 16, 'archivos/file_67522af2752cd7.47397760.pdf');
INSERT INTO `archivo` VALUES (14, '47', 'マジカル★エクスプローラー エロゲの友人キャラに転生したけど、ゲーム知識使って自由に生きる11【電子特別版】.epub', 1, 17, 'archivos/file_67570394479ed0.44622778.epub');
INSERT INTO `archivo` VALUES (15, '86030', 'Borrador protocolo equipo 9.docx', 1, 18, 'archivos/file_67576c3768c4c9.64098166.docx');
INSERT INTO `archivo` VALUES (16, '86030', '213008770020250115193042kardexg.pdf', 2, 19, 'archivos/file_6797efaad5a481.44513226.pdf');

-- ----------------------------
-- Table structure for auth_assignment
-- ----------------------------
DROP TABLE IF EXISTS `auth_assignment`;
CREATE TABLE `auth_assignment`  (
  `item_name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `user_id` int NOT NULL,
  `created_at` int NULL DEFAULT NULL,
  PRIMARY KEY (`item_name`, `user_id`) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_assignment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_assignment
-- ----------------------------
INSERT INTO `auth_assignment` VALUES ('prueba', 2, 1733758268);

-- ----------------------------
-- Table structure for auth_item
-- ----------------------------
DROP TABLE IF EXISTS `auth_item`;
CREATE TABLE `auth_item`  (
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `type` int NOT NULL,
  `description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `rule_name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `data` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `created_at` int NULL DEFAULT NULL,
  `updated_at` int NULL DEFAULT NULL,
  `group_code` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`name`) USING BTREE,
  INDEX `rule_name`(`rule_name` ASC) USING BTREE,
  INDEX `idx-auth_item-type`(`type` ASC) USING BTREE,
  INDEX `fk_auth_item_group_code`(`group_code` ASC) USING BTREE,
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_auth_item_group_code` FOREIGN KEY (`group_code`) REFERENCES `auth_item_group` (`code`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_item
-- ----------------------------
INSERT INTO `auth_item` VALUES ('/*', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/alumno/*', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/alumno/create', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/alumno/delete', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/alumno/index', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/alumno/update', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/alumno/view', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/anaquel/*', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/anaquel/create', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/anaquel/delete', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/anaquel/index', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/anaquel/update', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/anaquel/view', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/archivo/*', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/archivo/create', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/archivo/delete', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/archivo/download', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/archivo/index', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/archivo/update', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/archivo/ver', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/archivo/view', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/caja/*', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/caja/create', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/caja/delete', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/caja/index', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/caja/view', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/carrera/*', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/carrera/create', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/carrera/delete', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/carrera/index', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/carrera/update', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/carrera/view', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/debug/*', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/debug/default/*', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/debug/default/db-explain', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/debug/default/download-mail', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/debug/default/index', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/debug/default/toolbar', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/debug/default/view', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/debug/user/*', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/debug/user/reset-identity', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/debug/user/set-identity', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/generacion/*', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/generacion/create', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/generacion/delete', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/generacion/index', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/generacion/update', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/generacion/view', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/gii/*', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/gii/default/*', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/gii/default/action', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/gii/default/diff', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/gii/default/index', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/gii/default/preview', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/gii/default/view', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/nivelalmacenamiento/*', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/nivelalmacenamiento/create', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/nivelalmacenamiento/delete', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/nivelalmacenamiento/index', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/nivelalmacenamiento/update', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/nivelalmacenamiento/view', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/pdfjs/*', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/pdfjs/default/*', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/pdfjs/default/index', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/site/*', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/site/about', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/site/captcha', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/site/contact', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/site/error', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/site/handle-scan-result', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/site/index', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/site/index-usuario', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/site/login', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/site/logout', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/site/menubuscar', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/site/menucrear', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/site/scan', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/site/scan-qr', 3, NULL, NULL, NULL, 1733993233, 1733993233, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/*', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/auth-item-group/*', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/auth-item-group/bulk-activate', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/auth-item-group/bulk-deactivate', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/auth-item-group/bulk-delete', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/auth-item-group/create', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/auth-item-group/delete', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/auth-item-group/grid-page-size', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/auth-item-group/grid-sort', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/auth-item-group/index', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/auth-item-group/toggle-attribute', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/auth-item-group/update', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/auth-item-group/view', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/auth/*', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/auth/captcha', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/auth/change-own-password', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/auth/confirm-email', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/auth/confirm-email-receive', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/auth/confirm-registration-email', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/auth/login', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/auth/logout', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/auth/password-recovery', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/auth/password-recovery-receive', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/auth/registration', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/permission/*', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/permission/bulk-activate', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/permission/bulk-deactivate', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/permission/bulk-delete', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/permission/create', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/permission/delete', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/permission/grid-page-size', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/permission/grid-sort', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/permission/index', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/permission/refresh-routes', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/permission/set-child-permissions', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/permission/set-child-routes', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/permission/toggle-attribute', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/permission/update', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/permission/view', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/role/*', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/role/bulk-activate', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/role/bulk-deactivate', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/role/bulk-delete', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/role/create', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/role/delete', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/role/grid-page-size', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/role/grid-sort', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/role/index', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/role/set-child-permissions', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/role/set-child-roles', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/role/toggle-attribute', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/role/update', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/role/view', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/user-permission/*', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/user-permission/set', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/user-permission/set-roles', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/user-visit-log/*', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/user-visit-log/bulk-activate', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/user-visit-log/bulk-deactivate', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/user-visit-log/bulk-delete', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/user-visit-log/create', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/user-visit-log/delete', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/user-visit-log/grid-page-size', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/user-visit-log/grid-sort', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/user-visit-log/index', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/user-visit-log/toggle-attribute', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/user-visit-log/update', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/user-visit-log/view', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/user/*', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/user/bulk-activate', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/user/bulk-deactivate', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/user/bulk-delete', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/user/change-password', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/user/create', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/user/delete', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/user/grid-page-size', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/user/grid-sort', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/user/index', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/user/toggle-attribute', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/user/update', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('/user-management/user/view', 3, NULL, NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('Admin', 1, 'Admin', NULL, NULL, 1426062189, 1426062189, NULL);
INSERT INTO `auth_item` VALUES ('assignRolesToUsers', 2, 'Assign roles to users', NULL, NULL, 1426062189, 1426062189, 'userManagement');
INSERT INTO `auth_item` VALUES ('bindUserToIp', 2, 'Bind user to IP', NULL, NULL, 1426062189, 1426062189, 'userManagement');
INSERT INTO `auth_item` VALUES ('changeOwnPassword', 2, 'Change own password', NULL, NULL, 1426062189, 1426062189, 'userCommonPermissions');
INSERT INTO `auth_item` VALUES ('changeUserPassword', 2, 'Change user password', NULL, NULL, 1426062189, 1426062189, 'userManagement');
INSERT INTO `auth_item` VALUES ('commonPermission', 2, 'Common permission', NULL, NULL, 1426062188, 1426062188, NULL);
INSERT INTO `auth_item` VALUES ('createUsers', 2, 'Create users', NULL, NULL, 1426062189, 1426062189, 'userManagement');
INSERT INTO `auth_item` VALUES ('deleteUsers', 2, 'Delete users', NULL, NULL, 1426062189, 1426062189, 'userManagement');
INSERT INTO `auth_item` VALUES ('editUserEmail', 2, 'Edit user email', NULL, NULL, 1426062189, 1426062189, 'userManagement');
INSERT INTO `auth_item` VALUES ('editUsers', 2, 'Edit users', NULL, NULL, 1426062189, 1426062189, 'userManagement');
INSERT INTO `auth_item` VALUES ('prueba', 1, 'prueba', NULL, NULL, 1733758197, 1733758197, NULL);
INSERT INTO `auth_item` VALUES ('usuario', 1, 'usuario', NULL, NULL, 1733756637, 1733756637, NULL);
INSERT INTO `auth_item` VALUES ('usuarioper', 2, 'usuario', NULL, NULL, 1733756870, 1733756870, 'userCommonPermissions');
INSERT INTO `auth_item` VALUES ('viewRegistrationIp', 2, 'View registration IP', NULL, NULL, 1426062189, 1426062189, 'userManagement');
INSERT INTO `auth_item` VALUES ('viewUserEmail', 2, 'View user email', NULL, NULL, 1426062189, 1426062189, 'userManagement');
INSERT INTO `auth_item` VALUES ('viewUserRoles', 2, 'View user roles', NULL, NULL, 1426062189, 1426062189, 'userManagement');
INSERT INTO `auth_item` VALUES ('viewUsers', 2, 'View users', NULL, NULL, 1426062189, 1426062189, 'userManagement');
INSERT INTO `auth_item` VALUES ('viewVisitLog', 2, 'View visit log', NULL, NULL, 1426062189, 1426062189, 'userManagement');

-- ----------------------------
-- Table structure for auth_item_child
-- ----------------------------
DROP TABLE IF EXISTS `auth_item_child`;
CREATE TABLE `auth_item_child`  (
  `parent` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `child` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  PRIMARY KEY (`parent`, `child`) USING BTREE,
  INDEX `child`(`child` ASC) USING BTREE,
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_item_child
-- ----------------------------
INSERT INTO `auth_item_child` VALUES ('assignRolesToUsers', '/*');
INSERT INTO `auth_item_child` VALUES ('usuarioper', '/*');
INSERT INTO `auth_item_child` VALUES ('assignRolesToUsers', '/gii/*');
INSERT INTO `auth_item_child` VALUES ('usuarioper', '/site/index-usuario');
INSERT INTO `auth_item_child` VALUES ('assignRolesToUsers', '/user-management/*');
INSERT INTO `auth_item_child` VALUES ('usuarioper', '/user-management/*');
INSERT INTO `auth_item_child` VALUES ('changeOwnPassword', '/user-management/auth/change-own-password');
INSERT INTO `auth_item_child` VALUES ('assignRolesToUsers', '/user-management/user-permission/set');
INSERT INTO `auth_item_child` VALUES ('assignRolesToUsers', '/user-management/user-permission/set-roles');
INSERT INTO `auth_item_child` VALUES ('viewVisitLog', '/user-management/user-visit-log/grid-page-size');
INSERT INTO `auth_item_child` VALUES ('viewVisitLog', '/user-management/user-visit-log/index');
INSERT INTO `auth_item_child` VALUES ('viewVisitLog', '/user-management/user-visit-log/view');
INSERT INTO `auth_item_child` VALUES ('editUsers', '/user-management/user/bulk-activate');
INSERT INTO `auth_item_child` VALUES ('editUsers', '/user-management/user/bulk-deactivate');
INSERT INTO `auth_item_child` VALUES ('deleteUsers', '/user-management/user/bulk-delete');
INSERT INTO `auth_item_child` VALUES ('changeUserPassword', '/user-management/user/change-password');
INSERT INTO `auth_item_child` VALUES ('createUsers', '/user-management/user/create');
INSERT INTO `auth_item_child` VALUES ('deleteUsers', '/user-management/user/delete');
INSERT INTO `auth_item_child` VALUES ('viewUsers', '/user-management/user/grid-page-size');
INSERT INTO `auth_item_child` VALUES ('viewUsers', '/user-management/user/index');
INSERT INTO `auth_item_child` VALUES ('editUsers', '/user-management/user/update');
INSERT INTO `auth_item_child` VALUES ('viewUsers', '/user-management/user/view');
INSERT INTO `auth_item_child` VALUES ('Admin', 'assignRolesToUsers');
INSERT INTO `auth_item_child` VALUES ('prueba', 'assignRolesToUsers');
INSERT INTO `auth_item_child` VALUES ('usuario', 'assignRolesToUsers');
INSERT INTO `auth_item_child` VALUES ('prueba', 'bindUserToIp');
INSERT INTO `auth_item_child` VALUES ('usuario', 'bindUserToIp');
INSERT INTO `auth_item_child` VALUES ('Admin', 'changeOwnPassword');
INSERT INTO `auth_item_child` VALUES ('prueba', 'changeOwnPassword');
INSERT INTO `auth_item_child` VALUES ('usuario', 'changeOwnPassword');
INSERT INTO `auth_item_child` VALUES ('Admin', 'changeUserPassword');
INSERT INTO `auth_item_child` VALUES ('prueba', 'changeUserPassword');
INSERT INTO `auth_item_child` VALUES ('usuario', 'changeUserPassword');
INSERT INTO `auth_item_child` VALUES ('Admin', 'createUsers');
INSERT INTO `auth_item_child` VALUES ('prueba', 'createUsers');
INSERT INTO `auth_item_child` VALUES ('usuario', 'createUsers');
INSERT INTO `auth_item_child` VALUES ('Admin', 'deleteUsers');
INSERT INTO `auth_item_child` VALUES ('prueba', 'deleteUsers');
INSERT INTO `auth_item_child` VALUES ('usuario', 'deleteUsers');
INSERT INTO `auth_item_child` VALUES ('prueba', 'editUserEmail');
INSERT INTO `auth_item_child` VALUES ('usuario', 'editUserEmail');
INSERT INTO `auth_item_child` VALUES ('Admin', 'editUsers');
INSERT INTO `auth_item_child` VALUES ('prueba', 'editUsers');
INSERT INTO `auth_item_child` VALUES ('usuario', 'editUsers');
INSERT INTO `auth_item_child` VALUES ('Admin', 'prueba');
INSERT INTO `auth_item_child` VALUES ('Admin', 'usuario');
INSERT INTO `auth_item_child` VALUES ('assignRolesToUsers', 'usuarioper');
INSERT INTO `auth_item_child` VALUES ('prueba', 'usuarioper');
INSERT INTO `auth_item_child` VALUES ('usuario', 'usuarioper');
INSERT INTO `auth_item_child` VALUES ('prueba', 'viewRegistrationIp');
INSERT INTO `auth_item_child` VALUES ('usuario', 'viewRegistrationIp');
INSERT INTO `auth_item_child` VALUES ('editUserEmail', 'viewUserEmail');
INSERT INTO `auth_item_child` VALUES ('prueba', 'viewUserEmail');
INSERT INTO `auth_item_child` VALUES ('usuario', 'viewUserEmail');
INSERT INTO `auth_item_child` VALUES ('assignRolesToUsers', 'viewUserRoles');
INSERT INTO `auth_item_child` VALUES ('prueba', 'viewUserRoles');
INSERT INTO `auth_item_child` VALUES ('usuario', 'viewUserRoles');
INSERT INTO `auth_item_child` VALUES ('Admin', 'viewUsers');
INSERT INTO `auth_item_child` VALUES ('assignRolesToUsers', 'viewUsers');
INSERT INTO `auth_item_child` VALUES ('changeUserPassword', 'viewUsers');
INSERT INTO `auth_item_child` VALUES ('createUsers', 'viewUsers');
INSERT INTO `auth_item_child` VALUES ('deleteUsers', 'viewUsers');
INSERT INTO `auth_item_child` VALUES ('editUsers', 'viewUsers');
INSERT INTO `auth_item_child` VALUES ('prueba', 'viewUsers');
INSERT INTO `auth_item_child` VALUES ('usuario', 'viewUsers');
INSERT INTO `auth_item_child` VALUES ('prueba', 'viewVisitLog');
INSERT INTO `auth_item_child` VALUES ('usuario', 'viewVisitLog');

-- ----------------------------
-- Table structure for auth_item_group
-- ----------------------------
DROP TABLE IF EXISTS `auth_item_group`;
CREATE TABLE `auth_item_group`  (
  `code` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `created_at` int NULL DEFAULT NULL,
  `updated_at` int NULL DEFAULT NULL,
  PRIMARY KEY (`code`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_item_group
-- ----------------------------
INSERT INTO `auth_item_group` VALUES ('userCommonPermissions', 'User common permission', 1426062189, 1426062189);
INSERT INTO `auth_item_group` VALUES ('userManagement', 'User management', 1426062189, 1426062189);

-- ----------------------------
-- Table structure for auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule`  (
  `name` varchar(64) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `data` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL,
  `created_at` int NULL DEFAULT NULL,
  `updated_at` int NULL DEFAULT NULL,
  PRIMARY KEY (`name`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_rule
-- ----------------------------

-- ----------------------------
-- Table structure for caja
-- ----------------------------
DROP TABLE IF EXISTS `caja`;
CREATE TABLE `caja`  (
  `caj_id` int NOT NULL AUTO_INCREMENT,
  `caj_codigo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `caj_anaquel_id` int NULL DEFAULT NULL,
  `caj_nivel_id` int NULL DEFAULT NULL,
  PRIMARY KEY (`caj_id`) USING BTREE,
  INDEX `caj_anaquel_id`(`caj_anaquel_id` ASC) USING BTREE,
  INDEX `caj_nivel_id`(`caj_nivel_id` ASC) USING BTREE,
  CONSTRAINT `caja_ibfk_1` FOREIGN KEY (`caj_anaquel_id`) REFERENCES `anaquel` (`ana_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `caja_ibfk_2` FOREIGN KEY (`caj_nivel_id`) REFERENCES `nivelalmacenamiento` (`niv_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of caja
-- ----------------------------
INSERT INTO `caja` VALUES (1, 'AC01T0001', 1, 3);
INSERT INTO `caja` VALUES (2, 'AC01T0002', 1, 3);
INSERT INTO `caja` VALUES (3, 'AC01T0003', 1, 3);
INSERT INTO `caja` VALUES (4, 'AC14S0001', 14, 2);
INSERT INTO `caja` VALUES (5, 'AC15T0001', 15, 3);
INSERT INTO `caja` VALUES (6, 'AC13S0001', 13, 2);
INSERT INTO `caja` VALUES (7, 'AC14S0002', 14, 2);
INSERT INTO `caja` VALUES (8, 'AC15P0001', 15, 1);

-- ----------------------------
-- Table structure for carrera
-- ----------------------------
DROP TABLE IF EXISTS `carrera`;
CREATE TABLE `carrera`  (
  `car_id` int NOT NULL AUTO_INCREMENT,
  `car_nombre` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`car_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of carrera
-- ----------------------------
INSERT INTO `carrera` VALUES (1, 'Ingeniería Civil');
INSERT INTO `carrera` VALUES (2, 'Ingeniería Química');
INSERT INTO `carrera` VALUES (3, 'Ingeniería Petrolera');
INSERT INTO `carrera` VALUES (4, 'Ingeniería Ambiental');
INSERT INTO `carrera` VALUES (5, 'Ingeniería Industrial');
INSERT INTO `carrera` VALUES (6, 'Ingeniería Bioquímica');
INSERT INTO `carrera` VALUES (7, 'Ingeniería en Informática');
INSERT INTO `carrera` VALUES (8, 'Ingeniería en Sistemas Computacionales');
INSERT INTO `carrera` VALUES (9, 'Ingeniería en Gestión Empresarial');
INSERT INTO `carrera` VALUES (10, 'Ingeniería en Ciencias de Datos');
INSERT INTO `carrera` VALUES (11, 'Ingeniería en Tecnologías de la Información y Comunicaciones');
INSERT INTO `carrera` VALUES (12, 'Licenciatura en Administración');

-- ----------------------------
-- Table structure for generacion
-- ----------------------------
DROP TABLE IF EXISTS `generacion`;
CREATE TABLE `generacion`  (
  `gen_id` int NOT NULL AUTO_INCREMENT,
  `gen_nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`gen_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 157 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of generacion
-- ----------------------------
INSERT INTO `generacion` VALUES (155, 'enero-junio');
INSERT INTO `generacion` VALUES (156, 'agosto-diciembre');

-- ----------------------------
-- Table structure for ingreso
-- ----------------------------
DROP TABLE IF EXISTS `ingreso`;
CREATE TABLE `ingreso`  (
  `ing_id` int NOT NULL AUTO_INCREMENT,
  `ing_anio` year NOT NULL,
  PRIMARY KEY (`ing_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ingreso
-- ----------------------------

-- ----------------------------
-- Table structure for migration
-- ----------------------------
DROP TABLE IF EXISTS `migration`;
CREATE TABLE `migration`  (
  `version` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `apply_time` int NULL DEFAULT NULL,
  PRIMARY KEY (`version`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migration
-- ----------------------------
INSERT INTO `migration` VALUES ('m000000_000000_base', 1733130891);
INSERT INTO `migration` VALUES ('m210828_183945_create_setting_table', 1733130896);

-- ----------------------------
-- Table structure for nivelalmacenamiento
-- ----------------------------
DROP TABLE IF EXISTS `nivelalmacenamiento`;
CREATE TABLE `nivelalmacenamiento`  (
  `niv_id` int NOT NULL AUTO_INCREMENT,
  `niv_nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`niv_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of nivelalmacenamiento
-- ----------------------------
INSERT INTO `nivelalmacenamiento` VALUES (1, 'primero');
INSERT INTO `nivelalmacenamiento` VALUES (2, 'segundo');
INSERT INTO `nivelalmacenamiento` VALUES (3, 'tercero');

-- ----------------------------
-- Table structure for periodo
-- ----------------------------
DROP TABLE IF EXISTS `periodo`;
CREATE TABLE `periodo`  (
  `per_id` int NOT NULL AUTO_INCREMENT,
  `per_nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`per_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of periodo
-- ----------------------------
INSERT INTO `periodo` VALUES (1, 'enero-julio');
INSERT INTO `periodo` VALUES (2, 'julio-diciembre');

-- ----------------------------
-- Table structure for servicio
-- ----------------------------
DROP TABLE IF EXISTS `servicio`;
CREATE TABLE `servicio`  (
  `ser_id` int NOT NULL AUTO_INCREMENT,
  `ser_anio` year NOT NULL,
  `ser_periodo_id` int NULL DEFAULT NULL,
  PRIMARY KEY (`ser_id`) USING BTREE,
  INDEX `ser_periodo_id`(`ser_periodo_id` ASC) USING BTREE,
  CONSTRAINT `servicio_ibfk_1` FOREIGN KEY (`ser_periodo_id`) REFERENCES `periodo` (`per_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 155 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of servicio
-- ----------------------------
INSERT INTO `servicio` VALUES (1, 1974, 1);
INSERT INTO `servicio` VALUES (2, 1974, 2);
INSERT INTO `servicio` VALUES (3, 1975, 1);
INSERT INTO `servicio` VALUES (4, 1975, 2);
INSERT INTO `servicio` VALUES (5, 1976, 1);
INSERT INTO `servicio` VALUES (6, 1976, 2);
INSERT INTO `servicio` VALUES (7, 1977, 1);
INSERT INTO `servicio` VALUES (8, 1977, 2);
INSERT INTO `servicio` VALUES (9, 1978, 1);
INSERT INTO `servicio` VALUES (10, 1978, 2);
INSERT INTO `servicio` VALUES (11, 1979, 1);
INSERT INTO `servicio` VALUES (12, 1979, 2);
INSERT INTO `servicio` VALUES (13, 1980, 1);
INSERT INTO `servicio` VALUES (14, 1980, 2);
INSERT INTO `servicio` VALUES (15, 1981, 1);
INSERT INTO `servicio` VALUES (16, 1981, 2);
INSERT INTO `servicio` VALUES (17, 1982, 1);
INSERT INTO `servicio` VALUES (18, 1982, 2);
INSERT INTO `servicio` VALUES (19, 1983, 1);
INSERT INTO `servicio` VALUES (20, 1983, 2);
INSERT INTO `servicio` VALUES (21, 1984, 1);
INSERT INTO `servicio` VALUES (22, 1984, 2);
INSERT INTO `servicio` VALUES (23, 1985, 1);
INSERT INTO `servicio` VALUES (24, 1985, 2);
INSERT INTO `servicio` VALUES (25, 1986, 1);
INSERT INTO `servicio` VALUES (26, 1986, 2);
INSERT INTO `servicio` VALUES (27, 1987, 1);
INSERT INTO `servicio` VALUES (28, 1987, 2);
INSERT INTO `servicio` VALUES (29, 1988, 1);
INSERT INTO `servicio` VALUES (30, 1988, 2);
INSERT INTO `servicio` VALUES (31, 1989, 1);
INSERT INTO `servicio` VALUES (32, 1989, 2);
INSERT INTO `servicio` VALUES (33, 1990, 1);
INSERT INTO `servicio` VALUES (34, 1990, 2);
INSERT INTO `servicio` VALUES (35, 1991, 1);
INSERT INTO `servicio` VALUES (36, 1991, 2);
INSERT INTO `servicio` VALUES (37, 1992, 1);
INSERT INTO `servicio` VALUES (38, 1992, 2);
INSERT INTO `servicio` VALUES (39, 1993, 1);
INSERT INTO `servicio` VALUES (40, 1993, 2);
INSERT INTO `servicio` VALUES (41, 1994, 1);
INSERT INTO `servicio` VALUES (42, 1994, 2);
INSERT INTO `servicio` VALUES (43, 1995, 1);
INSERT INTO `servicio` VALUES (44, 1995, 2);
INSERT INTO `servicio` VALUES (45, 1996, 1);
INSERT INTO `servicio` VALUES (46, 1996, 2);
INSERT INTO `servicio` VALUES (47, 1997, 1);
INSERT INTO `servicio` VALUES (48, 1997, 2);
INSERT INTO `servicio` VALUES (49, 1998, 1);
INSERT INTO `servicio` VALUES (50, 1998, 2);
INSERT INTO `servicio` VALUES (51, 1999, 1);
INSERT INTO `servicio` VALUES (52, 1999, 2);
INSERT INTO `servicio` VALUES (53, 2000, 1);
INSERT INTO `servicio` VALUES (54, 2000, 2);
INSERT INTO `servicio` VALUES (55, 2001, 1);
INSERT INTO `servicio` VALUES (56, 2001, 2);
INSERT INTO `servicio` VALUES (57, 2002, 1);
INSERT INTO `servicio` VALUES (58, 2002, 2);
INSERT INTO `servicio` VALUES (59, 2003, 1);
INSERT INTO `servicio` VALUES (60, 2003, 2);
INSERT INTO `servicio` VALUES (61, 2004, 1);
INSERT INTO `servicio` VALUES (62, 2004, 2);
INSERT INTO `servicio` VALUES (63, 2005, 1);
INSERT INTO `servicio` VALUES (64, 2005, 2);
INSERT INTO `servicio` VALUES (65, 2006, 1);
INSERT INTO `servicio` VALUES (66, 2006, 2);
INSERT INTO `servicio` VALUES (67, 2007, 1);
INSERT INTO `servicio` VALUES (68, 2007, 2);
INSERT INTO `servicio` VALUES (69, 2008, 1);
INSERT INTO `servicio` VALUES (70, 2008, 2);
INSERT INTO `servicio` VALUES (71, 2009, 1);
INSERT INTO `servicio` VALUES (72, 2009, 2);
INSERT INTO `servicio` VALUES (73, 2010, 1);
INSERT INTO `servicio` VALUES (74, 2010, 2);
INSERT INTO `servicio` VALUES (75, 2011, 1);
INSERT INTO `servicio` VALUES (76, 2011, 2);
INSERT INTO `servicio` VALUES (77, 2012, 1);
INSERT INTO `servicio` VALUES (78, 2012, 2);
INSERT INTO `servicio` VALUES (79, 2013, 1);
INSERT INTO `servicio` VALUES (80, 2013, 2);
INSERT INTO `servicio` VALUES (81, 2014, 1);
INSERT INTO `servicio` VALUES (82, 2014, 2);
INSERT INTO `servicio` VALUES (83, 2015, 1);
INSERT INTO `servicio` VALUES (84, 2015, 2);
INSERT INTO `servicio` VALUES (85, 2016, 1);
INSERT INTO `servicio` VALUES (86, 2016, 2);
INSERT INTO `servicio` VALUES (87, 2017, 1);
INSERT INTO `servicio` VALUES (88, 2017, 2);
INSERT INTO `servicio` VALUES (89, 2018, 1);
INSERT INTO `servicio` VALUES (90, 2018, 2);
INSERT INTO `servicio` VALUES (91, 2019, 1);
INSERT INTO `servicio` VALUES (92, 2019, 2);
INSERT INTO `servicio` VALUES (93, 2020, 1);
INSERT INTO `servicio` VALUES (94, 2020, 2);
INSERT INTO `servicio` VALUES (95, 2021, 1);
INSERT INTO `servicio` VALUES (96, 2021, 2);
INSERT INTO `servicio` VALUES (97, 2022, 1);
INSERT INTO `servicio` VALUES (98, 2022, 2);
INSERT INTO `servicio` VALUES (99, 2023, 1);
INSERT INTO `servicio` VALUES (100, 2023, 2);
INSERT INTO `servicio` VALUES (101, 2024, 1);
INSERT INTO `servicio` VALUES (102, 2024, 2);
INSERT INTO `servicio` VALUES (103, 2025, 1);
INSERT INTO `servicio` VALUES (104, 2025, 2);
INSERT INTO `servicio` VALUES (105, 2026, 1);
INSERT INTO `servicio` VALUES (106, 2026, 2);
INSERT INTO `servicio` VALUES (107, 2027, 1);
INSERT INTO `servicio` VALUES (108, 2027, 2);
INSERT INTO `servicio` VALUES (109, 2028, 1);
INSERT INTO `servicio` VALUES (110, 2028, 2);
INSERT INTO `servicio` VALUES (111, 2029, 1);
INSERT INTO `servicio` VALUES (112, 2029, 2);
INSERT INTO `servicio` VALUES (113, 2030, 1);
INSERT INTO `servicio` VALUES (114, 2030, 2);
INSERT INTO `servicio` VALUES (115, 2031, 1);
INSERT INTO `servicio` VALUES (116, 2031, 2);
INSERT INTO `servicio` VALUES (117, 2032, 1);
INSERT INTO `servicio` VALUES (118, 2032, 2);
INSERT INTO `servicio` VALUES (119, 2033, 1);
INSERT INTO `servicio` VALUES (120, 2033, 2);
INSERT INTO `servicio` VALUES (121, 2034, 1);
INSERT INTO `servicio` VALUES (122, 2034, 2);
INSERT INTO `servicio` VALUES (123, 2035, 1);
INSERT INTO `servicio` VALUES (124, 2035, 2);
INSERT INTO `servicio` VALUES (125, 2036, 1);
INSERT INTO `servicio` VALUES (126, 2036, 2);
INSERT INTO `servicio` VALUES (127, 2037, 1);
INSERT INTO `servicio` VALUES (128, 2037, 2);
INSERT INTO `servicio` VALUES (129, 2038, 1);
INSERT INTO `servicio` VALUES (130, 2038, 2);
INSERT INTO `servicio` VALUES (131, 2039, 1);
INSERT INTO `servicio` VALUES (132, 2039, 2);
INSERT INTO `servicio` VALUES (133, 2040, 1);
INSERT INTO `servicio` VALUES (134, 2040, 2);
INSERT INTO `servicio` VALUES (135, 2041, 1);
INSERT INTO `servicio` VALUES (136, 2041, 2);
INSERT INTO `servicio` VALUES (137, 2042, 1);
INSERT INTO `servicio` VALUES (138, 2042, 2);
INSERT INTO `servicio` VALUES (139, 2043, 1);
INSERT INTO `servicio` VALUES (140, 2043, 2);
INSERT INTO `servicio` VALUES (141, 2044, 1);
INSERT INTO `servicio` VALUES (142, 2044, 2);
INSERT INTO `servicio` VALUES (143, 2045, 1);
INSERT INTO `servicio` VALUES (144, 2045, 2);
INSERT INTO `servicio` VALUES (145, 2046, 1);
INSERT INTO `servicio` VALUES (146, 2046, 2);
INSERT INTO `servicio` VALUES (147, 2047, 1);
INSERT INTO `servicio` VALUES (148, 2047, 2);
INSERT INTO `servicio` VALUES (149, 2048, 1);
INSERT INTO `servicio` VALUES (150, 2048, 2);
INSERT INTO `servicio` VALUES (151, 2049, 1);
INSERT INTO `servicio` VALUES (152, 2049, 2);
INSERT INTO `servicio` VALUES (153, 2050, 1);
INSERT INTO `servicio` VALUES (154, 2050, 2);

-- ----------------------------
-- Table structure for setting
-- ----------------------------
DROP TABLE IF EXISTS `setting`;
CREATE TABLE `setting`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `created_at` datetime NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of setting
-- ----------------------------

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `auth_key` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `confirmation_token` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `status` int NOT NULL DEFAULT 1,
  `superadmin` smallint NULL DEFAULT 0,
  `created_at` int NOT NULL,
  `updated_at` int NOT NULL,
  `registration_ip` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `bind_to_ip` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `email` varchar(128) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `email_confirmed` smallint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (1, 'superadmin', 'kz2px152FAWlkHbkZoCiXgBAd-S8SSjF', '$2y$13$MhlYe12xkGFnSeK0sO2up.Y9kAD9Ct6JS1i9VLP7YAqd1dFsSylz2', NULL, 1, 1, 1426062188, 1426062188, NULL, NULL, NULL, 0);
INSERT INTO `user` VALUES (2, 'superusuario', 'Vg8UBcY3JgHWGAzZBdALnC-nyrTrRJEh', '$2y$13$Y3vIfJHpDqIc2FPFj0KZRe8wW5qOuqFN6tGzWHjaZfxge.O64Y0dS', NULL, 1, 0, 1733757518, 1733757518, '::1', '', '', 0);

-- ----------------------------
-- Table structure for user_visit_log
-- ----------------------------
DROP TABLE IF EXISTS `user_visit_log`;
CREATE TABLE `user_visit_log`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `token` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `ip` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `language` char(2) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `user_agent` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `user_id` int NULL DEFAULT NULL,
  `visit_time` int NOT NULL,
  `browser` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `os` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  CONSTRAINT `user_visit_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_visit_log
-- ----------------------------
INSERT INTO `user_visit_log` VALUES (1, '674d5e5d0c756', '::1', 'es', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', 1, 1733123677, 'Chrome', 'Windows');
INSERT INTO `user_visit_log` VALUES (2, '674d62b8c3512', '::1', 'es', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', 1, 1733124792, 'Chrome', 'Windows');
INSERT INTO `user_visit_log` VALUES (3, '674d990b7cbb5', '::1', 'es', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', 1, 1733138699, 'Chrome', 'Windows');
INSERT INTO `user_visit_log` VALUES (4, '674d9a1ea608d', '::1', 'es', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', 1, 1733138974, 'Chrome', 'Windows');
INSERT INTO `user_visit_log` VALUES (5, '674d9a21c2576', '::1', 'es', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', 1, 1733138977, 'Chrome', 'Windows');
INSERT INTO `user_visit_log` VALUES (6, '674d9a24c92a8', '::1', 'es', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', 1, 1733138980, 'Chrome', 'Windows');
INSERT INTO `user_visit_log` VALUES (7, '674ddc3980b99', '::1', 'es', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', 1, 1733155897, 'Chrome', 'Windows');
INSERT INTO `user_visit_log` VALUES (8, '674de055c250d', '::1', 'es', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', 1, 1733156949, 'Chrome', 'Windows');
INSERT INTO `user_visit_log` VALUES (9, '674f65e408675', '::1', 'es', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', 1, 1733256676, 'Chrome', 'Windows');
INSERT INTO `user_visit_log` VALUES (10, '675228ccda000', '::1', 'es', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', 1, 1733437644, 'Chrome', 'Windows');
INSERT INTO `user_visit_log` VALUES (11, '6752294f3f47e', '::1', 'es', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36', 1, 1733437775, 'Chrome', 'Windows');
INSERT INTO `user_visit_log` VALUES (12, '6756b94c5f438', '::1', 'es', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36 Edg/131.0.0.0', 1, 1733736780, 'Chrome', 'Windows');

-- ----------------------------
-- Table structure for usuario
-- ----------------------------
DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario`  (
  `usu_id` int NOT NULL AUTO_INCREMENT,
  `usu_nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `usu_paterno` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `usu_materno` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `usu_usuario` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `usu_contrasena` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`usu_id`) USING BTREE,
  UNIQUE INDEX `usu_usuario`(`usu_usuario` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of usuario
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
