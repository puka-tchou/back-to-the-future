-- MySQL dump 10.13  Distrib 5.7.24, for Win64 (x86_64)
--
-- Host: localhost    Database: crouzet_pricing
-- ------------------------------------------------------
-- Server version	5.7.24

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
-- Table structure for table `prices_history`
--

DROP TABLE IF EXISTS `prices_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prices_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `part_number` varchar(45) NOT NULL,
  `date_checked` date NOT NULL,
  `price` float NOT NULL,
  `supplier` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_part_number_idx` (`part_number`),
  CONSTRAINT `fk_part_number` FOREIGN KEY (`part_number`) REFERENCES `products` (`part_number`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `product_match`
--

DROP TABLE IF EXISTS `product_match`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_match` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `part_number` varchar(45) NOT NULL,
  `replacement` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_part_number_idx` (`part_number`),
  KEY `fk_replacement_idx` (`replacement`),
  CONSTRAINT `fk_match_part_number` FOREIGN KEY (`part_number`) REFERENCES `products` (`part_number`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `fk_match_replacement` FOREIGN KEY (`replacement`) REFERENCES `products` (`part_number`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `part_number` varchar(45) NOT NULL,
  `state` float NOT NULL DEFAULT '-1',
  `tracked_since` date NOT NULL,
  `tracked_end` date DEFAULT NULL,
  `last_check` date DEFAULT NULL,
  `update_interval` int(11) NOT NULL,
  `manufacturer` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `part_number_UNIQUE` (`part_number`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `stock_history`
--

DROP TABLE IF EXISTS `stock_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stock_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `part_number` varchar(100) NOT NULL,
  `date_checked` date NOT NULL,
  `state` float NOT NULL DEFAULT '-1',
  `parts_in_stock` float NOT NULL DEFAULT '-1',
  `parts_on_order` float NOT NULL DEFAULT '-1',
  `min_order` float NOT NULL DEFAULT '-1',
  `supplier` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stock_history_un` (`id`),
  KEY `stock_history_fk` (`part_number`),
  CONSTRAINT `stock_history_fk` FOREIGN KEY (`part_number`) REFERENCES `products` (`part_number`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping routines for database 'crouzet_pricing'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-06-09 15:44:19
