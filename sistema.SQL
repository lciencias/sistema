-- MySQL dump 10.13  Distrib 5.5.44, for Win64 (x86)
--
-- Host: localhost    Database: sistema
-- ------------------------------------------------------
-- Server version	5.5.44

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `acceso`
--

DROP TABLE IF EXISTS `acceso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acceso` (
  `idacceso` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(150) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `status` char(1) DEFAULT NULL,
  `ip` varchar(30) DEFAULT NULL,
  `explorador` varchar(30) DEFAULT NULL,
  `so` varchar(30) DEFAULT NULL,
  `idusuario` int(11) DEFAULT NULL,
  PRIMARY KEY (`idacceso`),
  KEY `fk_accesousuario` (`idusuario`),
  CONSTRAINT `fk_accesousuario` FOREIGN KEY (`idusuario`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acceso`
--

LOCK TABLES `acceso` WRITE;
/*!40000 ALTER TABLE `acceso` DISABLE KEYS */;
INSERT INTO `acceso` VALUES (1,'admin@sistema.com','2018-10-10 16:25:03','2','::1','CHROME','WIN',NULL),(2,'admin@sistema.com','2018-10-10 16:25:07','1','::1','CHROME','WIN',1),(3,'admin@sistema.com','2018-10-10 16:44:44','1','::1','CHROME','WIN',1),(4,'admin@sistema.com','2018-10-10 16:49:07','1','::1','CHROME','WIN',1);
/*!40000 ALTER TABLE `acceso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bitacora`
--

DROP TABLE IF EXISTS `bitacora`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bitacora` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(10) DEFAULT NULL,
  `idmodulo` int(10) DEFAULT NULL,
  `nombre_modulo` varchar(64) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `idregistro` int(10) DEFAULT NULL,
  `nombre_registro` varchar(150) DEFAULT NULL,
  `estado_anterior` text,
  `estado_despues` text,
  `ip` varchar(16) DEFAULT NULL,
  `tipo_movimiento` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_iduser` (`iduser`),
  KEY `fk_idmodulo` (`idmodulo`),
  CONSTRAINT `fk_idmodulo` FOREIGN KEY (`idmodulo`) REFERENCES `modulo` (`idmodulo`),
  CONSTRAINT `fk_iduser` FOREIGN KEY (`iduser`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitacora`
--

LOCK TABLES `bitacora` WRITE;
/*!40000 ALTER TABLE `bitacora` DISABLE KEYS */;
INSERT INTO `bitacora` VALUES (1,1,4,'PerfilController','2018-10-10 21:44:13',1,'Super Administrador','Sin cambios {Perfil : 1  Modulo 3 : Permiso 1} {Perfil : 1  Modulo 3 : Permiso 3} {Perfil : 1  Modulo 3 : Permiso 2} {Perfil : 1  Modulo 3 : Permiso 5} {Perfil : 1  Modulo 3 : Permiso 4} {Perfil : 1  Modulo 3 : Permiso 13} {Perfil : 1  Modulo 4 : Permiso 4} {Perfil : 1  Modulo 4 : Permiso 13} {Perfil : 1  Modulo 4 : Permiso 1} {Perfil : 1  Modulo 4 : Permiso 3} {Perfil : 1  Modulo 4 : Permiso 2} {Perfil : 1  Modulo 4 : Permiso 5} {Perfil : 1  Modulo 2 : Permiso 5} {Perfil : 1  Modulo 2 : Permiso 4} {Perfil : 1  Modulo 2 : Permiso 13} {Perfil : 1  Modulo 2 : Permiso 1} {Perfil : 1  Modulo 2 : Permiso 3} {Perfil : 1  Modulo 2 : Permiso 2} {Perfil : 1  Modulo 15 : Permiso 5} {Perfil : 1  Modulo 15 : Permiso 1} {Perfil : 1  Modulo 14 : Permiso 1} {Perfil : 1  Modulo 14 : Permiso 5}','Sin cambios {Perfil : 1  Modulo 3 : Permiso 1} {Perfil : 1  Modulo 3 : Permiso 3} {Perfil : 1  Modulo 3 : Permiso 2} {Perfil : 1  Modulo 3 : Permiso 5} {Perfil : 1  Modulo 3 : Permiso 4} {Perfil : 1  Modulo 3 : Permiso 13} {Perfil : 1  Modulo 4 : Permiso 4} {Perfil : 1  Modulo 4 : Permiso 13} {Perfil : 1  Modulo 4 : Permiso 1} {Perfil : 1  Modulo 4 : Permiso 3} {Perfil : 1  Modulo 4 : Permiso 2} {Perfil : 1  Modulo 4 : Permiso 5} {Perfil : 1  Modulo 2 : Permiso 5} {Perfil : 1  Modulo 2 : Permiso 4} {Perfil : 1  Modulo 2 : Permiso 13} {Perfil : 1  Modulo 2 : Permiso 1} {Perfil : 1  Modulo 2 : Permiso 3} {Perfil : 1  Modulo 2 : Permiso 2} {Perfil : 1  Modulo 15 : Permiso 5} {Perfil : 1  Modulo 15 : Permiso 1} {Perfil : 1  Modulo 14 : Permiso 1} {Perfil : 1  Modulo 14 : Permiso 5}','::1',2),(2,1,4,'PerfilController','2018-10-10 21:44:25',2,'Administrador de empresa','Sin cambios {Perfil : 2  Modulo 4 : Permiso 4} {Perfil : 2  Modulo 4 : Permiso 13} {Perfil : 2  Modulo 4 : Permiso 1} {Perfil : 2  Modulo 4 : Permiso 3} {Perfil : 2  Modulo 4 : Permiso 2} {Perfil : 2  Modulo 4 : Permiso 5} {Perfil : 2  Modulo 2 : Permiso 5} {Perfil : 2  Modulo 2 : Permiso 4} {Perfil : 2  Modulo 2 : Permiso 13} {Perfil : 2  Modulo 2 : Permiso 1} {Perfil : 2  Modulo 2 : Permiso 3} {Perfil : 2  Modulo 2 : Permiso 2} {Perfil : 2  Modulo 15 : Permiso 5} {Perfil : 2  Modulo 15 : Permiso 1}','Sin cambios {Perfil : 2  Modulo 4 : Permiso 4} {Perfil : 2  Modulo 4 : Permiso 13} {Perfil : 2  Modulo 4 : Permiso 1} {Perfil : 2  Modulo 4 : Permiso 3} {Perfil : 2  Modulo 4 : Permiso 2} {Perfil : 2  Modulo 4 : Permiso 5} {Perfil : 2  Modulo 2 : Permiso 5} {Perfil : 2  Modulo 2 : Permiso 4} {Perfil : 2  Modulo 2 : Permiso 13} {Perfil : 2  Modulo 2 : Permiso 1} {Perfil : 2  Modulo 2 : Permiso 3} {Perfil : 2  Modulo 2 : Permiso 2} {Perfil : 2  Modulo 15 : Permiso 5} {Perfil : 2  Modulo 15 : Permiso 1}','::1',2);
/*!40000 ALTER TABLE `bitacora` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empresa`
--

DROP TABLE IF EXISTS `empresa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `empresa` (
  `idempresa` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) DEFAULT NULL,
  `direccion` varchar(2000) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  `nombre_representante` varchar(70) DEFAULT NULL,
  `paterno_representante` varchar(70) DEFAULT NULL,
  `materno_representante` varchar(70) DEFAULT NULL,
  `email_representante` varchar(70) DEFAULT NULL,
  `rfc` varchar(20) DEFAULT NULL,
  `razon_social` varchar(100) DEFAULT NULL,
  `logotipo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`idempresa`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empresa`
--

LOCK TABLES `empresa` WRITE;
/*!40000 ALTER TABLE `empresa` DISABLE KEYS */;
/*!40000 ALTER TABLE `empresa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `attempts` tinyint(4) NOT NULL,
  `reserved` tinyint(4) NOT NULL DEFAULT '0',
  `reserved_at` bigint(20) DEFAULT '0',
  `available_at` bigint(20) NOT NULL,
  `created_at` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_reserved_reserved_at_index` (`queue`,`reserved`,`reserved_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES ('2014_10_12_000000_create_users_table',1),('2014_10_12_100000_create_password_resets_table',1),('2017_05_03_164707_create_jobs_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modulo`
--

DROP TABLE IF EXISTS `modulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modulo` (
  `idmodulo` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `identificador` varchar(20) DEFAULT NULL,
  `recurso` varchar(80) DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `admin` char(1) DEFAULT NULL,
  `activo` int(11) DEFAULT '1',
  `clase` varchar(64) DEFAULT NULL,
  `orden` int(11) DEFAULT '0',
  PRIMARY KEY (`idmodulo`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modulo`
--

LOCK TABLES `modulo` WRITE;
/*!40000 ALTER TABLE `modulo` DISABLE KEYS */;
INSERT INTO `modulo` VALUES (1,'Seguridad','m01','/',0,NULL,1,'fa fa-lock fa-lg',1),(2,'Usuarios','Usuario','/seguridad/usuario',1,NULL,1,'fa fa-users',3),(3,'Empresas','Empresa','/seguridad/empresa',1,NULL,1,'fa fa-building',1),(4,'Perfiles','Perfil','/seguridad/perfil',1,NULL,1,'fa fa-address-card',2),(13,'Bitácoras','M2','/',0,NULL,1,'fa fa-book fa-lg',3),(14,'Movimientos','Movimiento','/bitacoras/movimientos',13,NULL,1,'fa fa-gavel',0),(15,'Accesos','Acceso','/bitacoras/acceso',13,NULL,1,'fa fa-history',0);
/*!40000 ALTER TABLE `modulo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modulo_permiso`
--

DROP TABLE IF EXISTS `modulo_permiso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modulo_permiso` (
  `idmodulo_permiso` int(11) NOT NULL AUTO_INCREMENT,
  `idmodulo` int(11) DEFAULT NULL,
  `idpermiso` int(11) DEFAULT NULL,
  PRIMARY KEY (`idmodulo_permiso`),
  KEY `fk1_modper_mod` (`idmodulo`),
  KEY `fk2_modper_per` (`idpermiso`),
  CONSTRAINT `fk1_modper_mod` FOREIGN KEY (`idmodulo`) REFERENCES `modulo` (`idmodulo`),
  CONSTRAINT `fk2_modper_per` FOREIGN KEY (`idpermiso`) REFERENCES `permiso` (`idpermiso`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modulo_permiso`
--

LOCK TABLES `modulo_permiso` WRITE;
/*!40000 ALTER TABLE `modulo_permiso` DISABLE KEYS */;
INSERT INTO `modulo_permiso` VALUES (1,3,1),(2,3,2),(3,3,3),(4,3,4),(5,3,5),(6,3,13),(7,4,1),(8,4,2),(9,4,3),(10,4,4),(11,4,5),(12,4,13),(13,2,1),(14,2,2),(15,2,3),(16,2,4),(17,2,5),(18,2,13),(58,15,1),(59,15,5),(60,14,1),(61,14,5);
/*!40000 ALTER TABLE `modulo_permiso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `password_resets_email_index` (`email`),
  KEY `password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
INSERT INTO `password_resets` VALUES ('molina_mikea@hotmail.com','5ea6aa2f5cf8105a5a74495c38a7d6470bd10b9910923cdc11f4e21ff0852e52','2018-01-25 18:45:08');
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perfil`
--

DROP TABLE IF EXISTS `perfil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perfil` (
  `idperfil` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  `descripcion` varchar(5000) DEFAULT NULL,
  `idempresa` int(11) DEFAULT NULL,
  PRIMARY KEY (`idperfil`),
  KEY `fkperfil_empresa` (`idempresa`),
  CONSTRAINT `fkperfil_empresa` FOREIGN KEY (`idempresa`) REFERENCES `empresa` (`idempresa`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='Tabla que contiene la informacion de los perfiles existentes en el sistema';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfil`
--

LOCK TABLES `perfil` WRITE;
/*!40000 ALTER TABLE `perfil` DISABLE KEYS */;
INSERT INTO `perfil` VALUES (1,'Super Administrador',1,'Super Administrador',NULL),(2,'Administrador de empresa',1,'Administrador de empresa',NULL);
/*!40000 ALTER TABLE `perfil` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perfil_modulo`
--

DROP TABLE IF EXISTS `perfil_modulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perfil_modulo` (
  `idperfil_modulo` int(11) NOT NULL AUTO_INCREMENT,
  `idperfil` int(11) DEFAULT NULL,
  `idmodulo` int(11) DEFAULT NULL,
  PRIMARY KEY (`idperfil_modulo`),
  KEY `fk_perfilModulo_perfil` (`idperfil`),
  KEY `fk_perfilModulo_modulo` (`idmodulo`),
  CONSTRAINT `fk_perfilModulo_modulo` FOREIGN KEY (`idmodulo`) REFERENCES `modulo` (`idmodulo`),
  CONSTRAINT `fk_perfilModulo_perfil` FOREIGN KEY (`idperfil`) REFERENCES `perfil` (`idperfil`)
) ENGINE=InnoDB AUTO_INCREMENT=727 DEFAULT CHARSET=latin1 COMMENT='Tabla que contiene la relacion de los modulos que pertenecen a cada perfil';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfil_modulo`
--

LOCK TABLES `perfil_modulo` WRITE;
/*!40000 ALTER TABLE `perfil_modulo` DISABLE KEYS */;
INSERT INTO `perfil_modulo` VALUES (715,1,1),(716,1,3),(717,1,4),(718,1,2),(719,1,13),(720,1,15),(721,1,14),(722,2,1),(723,2,4),(724,2,2),(725,2,13),(726,2,15);
/*!40000 ALTER TABLE `perfil_modulo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perfil_modulo_permiso`
--

DROP TABLE IF EXISTS `perfil_modulo_permiso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perfil_modulo_permiso` (
  `idperfil_modulo_permiso` int(11) NOT NULL AUTO_INCREMENT,
  `idperfil_modulo` int(11) DEFAULT NULL,
  `idpermiso` int(11) DEFAULT NULL,
  PRIMARY KEY (`idperfil_modulo_permiso`),
  KEY `fk_perfilModuloPermiso_perfilModulo` (`idperfil_modulo`),
  KEY `fkperfilModuloPermiso_permiso` (`idpermiso`),
  CONSTRAINT `fkperfilModuloPermiso_permiso` FOREIGN KEY (`idpermiso`) REFERENCES `permiso` (`idpermiso`),
  CONSTRAINT `fk_perfilModuloPermiso_perfilModulo` FOREIGN KEY (`idperfil_modulo`) REFERENCES `perfil_modulo` (`idperfil_modulo`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1 COMMENT='Tabla que contiene la relacion de los permisos que pertenecen a cada perfil modulo';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perfil_modulo_permiso`
--

LOCK TABLES `perfil_modulo_permiso` WRITE;
/*!40000 ALTER TABLE `perfil_modulo_permiso` DISABLE KEYS */;
INSERT INTO `perfil_modulo_permiso` VALUES (1,716,1),(2,716,3),(3,716,2),(4,716,5),(5,716,4),(6,716,13),(7,717,4),(8,717,13),(9,717,1),(10,717,3),(11,717,2),(12,717,5),(13,718,5),(14,718,4),(15,718,13),(16,718,1),(17,718,3),(18,718,2),(19,720,5),(20,720,1),(21,721,1),(22,721,5),(23,723,4),(24,723,13),(25,723,1),(26,723,3),(27,723,2),(28,723,5),(29,724,5),(30,724,4),(31,724,13),(32,724,1),(33,724,3),(34,724,2),(35,726,5),(36,726,1);
/*!40000 ALTER TABLE `perfil_modulo_permiso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permiso`
--

DROP TABLE IF EXISTS `permiso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permiso` (
  `idpermiso` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) DEFAULT NULL,
  `tipo` char(1) DEFAULT NULL,
  PRIMARY KEY (`idpermiso`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1 COMMENT='Tabla que contiene la informacion de los permisos del sistema';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permiso`
--

LOCK TABLES `permiso` WRITE;
/*!40000 ALTER TABLE `permiso` DISABLE KEYS */;
INSERT INTO `permiso` VALUES (1,'Consulta','1'),(2,'Crear','2'),(3,'Editar','3'),(4,'Eliminar','4'),(5,'Exportar','5'),(13,'Restablecer',NULL);
/*!40000 ALTER TABLE `permiso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `idperfil` int(11) DEFAULT NULL,
  `idempresa` int(11) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT NULL,
  `dummy` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_email_unique` (`email`,`idempresa`),
  KEY `usuario_perfil` (`idperfil`),
  KEY `fk_use_emp` (`idempresa`),
  CONSTRAINT `fk_use_emp` FOREIGN KEY (`idempresa`) REFERENCES `empresa` (`idempresa`),
  CONSTRAINT `usuario_perfil` FOREIGN KEY (`idperfil`) REFERENCES `perfil` (`idperfil`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COMMENT='Tabla que contiene la informacion de los usuarios del sistema';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrador','admin@sistema.com','$2y$10$XdQt1X2gU3mUf22W3FzYF.KczNTrkgFXqGgzx.Oe30i.kZnvsa.9C','QJDaLCEL4HuIu5LQ3smxqv6aOLfxczQLvfhgDOSzBTPQNyg3RCfeBD19jYhO','2017-03-23 21:08:12','2018-10-09 21:22:59',1,NULL,1,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_modulo`
--

DROP TABLE IF EXISTS `users_modulo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_modulo` (
  `iduser_modulo` int(11) NOT NULL AUTO_INCREMENT,
  `iduser` int(11) DEFAULT NULL,
  `idmodulo` int(11) DEFAULT NULL,
  `permitido` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`iduser_modulo`),
  KEY `fk_usersModulo_users` (`iduser`),
  KEY `fk_usersModulo_modulo` (`idmodulo`),
  CONSTRAINT `fk_usersModulo_modulo` FOREIGN KEY (`idmodulo`) REFERENCES `modulo` (`idmodulo`),
  CONSTRAINT `fk_usersModulo_users` FOREIGN KEY (`iduser`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabla que contiene la relacion de los permisos agregados o excluidos a cada usuario';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_modulo`
--

LOCK TABLES `users_modulo` WRITE;
/*!40000 ALTER TABLE `users_modulo` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_modulo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_modulo_permiso`
--

DROP TABLE IF EXISTS `users_modulo_permiso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_modulo_permiso` (
  `iduser_modulo_permiso` int(11) NOT NULL AUTO_INCREMENT,
  `iduser_modulo` int(11) DEFAULT NULL,
  `idpermiso` int(11) DEFAULT NULL,
  `permitido` int(11) DEFAULT NULL,
  PRIMARY KEY (`iduser_modulo_permiso`),
  KEY `fkuserMoPer_usermod` (`iduser_modulo`),
  KEY `fkuserModPer_permiso` (`idpermiso`),
  CONSTRAINT `fkuserModPer_permiso` FOREIGN KEY (`idpermiso`) REFERENCES `permiso` (`idpermiso`),
  CONSTRAINT `fkuserMoPer_usermod` FOREIGN KEY (`iduser_modulo`) REFERENCES `users_modulo` (`iduser_modulo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_modulo_permiso`
--

LOCK TABLES `users_modulo_permiso` WRITE;
/*!40000 ALTER TABLE `users_modulo_permiso` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_modulo_permiso` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-10-10 16:49:28
