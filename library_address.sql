-- MySQL dump 10.13  Distrib 8.0.32, for Win64 (x86_64)
--
-- Host: cosc3380mysql.mysql.database.azure.com    Database: library
-- ------------------------------------------------------
-- Server version	5.7.40-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `address` (
  `AddressID` int(11) NOT NULL AUTO_INCREMENT,
  `StreetAddress` varchar(255) NOT NULL,
  `City` varchar(100) NOT NULL,
  `State` varchar(100) NOT NULL,
  `ZipCode` varchar(5) NOT NULL,
  PRIMARY KEY (`AddressID`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `address`
--

LOCK TABLES `address` WRITE;
/*!40000 ALTER TABLE `address` DISABLE KEYS */;
INSERT INTO `address` VALUES (1,'4333 University Drive','Houston','Texas','77204'),(2,'4200 Elgin Street','Houston','Texas','78956'),(3,'3333 Cullen Boulevard','Houston','Texas','77204'),(4,'4349 Martin Luther King Blvd','Houston','Texas','77204'),(5,'5055 Medical Circle','Houston','Texas','77204'),(6,'Spring','Houston','Texas','99999'),(7,'Spring','Houston','Texas','99999'),(8,'Spring','Katy','Texas','99999'),(9,'Spring','Spring','Texas','99999'),(10,'Spring','Woodlands','Texas','99999'),(11,'Spring','Katy','Texas','99999'),(12,'Spring','Old Town','Texas','99999'),(13,'Spring','Huffman','Texas','99999'),(14,'Spring','humble','Texas','99999'),(15,'3722','Spring','Texas','77388'),(16,'3722','Spring','Texas','77388'),(17,'3722','Spring','Texas','12345'),(18,'123 Random','Houston','Texas','77300'),(19,'2416 Ridgebrook Ln','Pearland','Texas','77584'),(20,'123 road','houston','Texas','12345'),(21,'123 something rd','Houston','Texas','64068'),(22,'3722 Nowhere','Summer','Texas','77000'),(23,'Elk Blvd','Spring','Texas','77344'),(24,'SomeWhere','Summer','Texas','77000'),(25,'123 sample street','Richmond','Texas','77406'),(26,'9855','Winter','Texas','77800'),(27,'9855','Winter','Texas','77800'),(28,'3722','Summer','Texas','78956'),(29,'3722 Cypress','Spring','Texas','78956'),(30,'3722 Cypress','Spring','Texas','78956'),(31,'3022 NoWhere','Summer','Texas','12345'),(32,'3022 NoWhere','Summer','Texas','12345'),(33,'3022 NoWhere','Summer','Texas','12345'),(34,'23432','Winter','Texas','44380'),(35,'23432','Winter','Texas','44380'),(36,'23432','Summer','Texas','99999'),(37,'23432','Winter','Texas','44380'),(38,'Spring','Summer','Texas','12345'),(39,'3722 Cypress','Spring','Texas','77388'),(40,'Spring','Tx','Texas','77388'),(41,'Spring','Tx','Texas','77388'),(42,'Spring','Spring','Texas','77388'),(43,'3722 Cypress','Spring','Texas','77388'),(44,'Spring','Summer','Texas','77388'),(45,'Spring','Summer','Texas','77388'),(46,'Spring','Spring','Texas','77388'),(47,'3722 NoWhere','Spring','Texas','99999'),(48,'42 elm street','Houston','Texas','77087'),(49,'404 elm street','Houston','Texas','77087'),(50,'404 elm street','Houston','Texas','77087'),(51,'404 elm street','Houston','Texas','77087'),(52,'404 elm street','Houston','Texas','77087'),(53,'123 bob hope lane','Houston','Texas','77484'),(56,'123 Main St','Austin','Texas','78701'),(57,'456 Oak St','Houston','Texas','77002'),(58,'Bayou Oaks','Hosuton','Texas','77004'),(59,'4800 Calhoun Road','Houston','Texas','77204'),(60,'Bayou Oaks','Houston','Texas','77004'),(61,'123 Something rd','Houston','Texas','77004'),(62,'efsgrdfnhb ','weg','knv','73946'),(63,'304 Russel Brooke','Houston','Texas','77087'),(64,'3022 NoWhere','Spring','Texas','77300'),(65,'12 vgh','dfbv','fbd','76543'),(66,'441 grove Ln','Houston','TX','77087');
/*!40000 ALTER TABLE `address` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-04-23 12:12:29
