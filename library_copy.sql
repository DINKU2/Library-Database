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
-- Table structure for table `copy`
--

DROP TABLE IF EXISTS `copy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `copy` (
  `CopyID` int(11) NOT NULL AUTO_INCREMENT,
  `ItemID` int(11) NOT NULL,
  `LibraryID` int(11) NOT NULL,
  `Status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`CopyID`),
  KEY `FK_ItemID_Copy` (`ItemID`),
  KEY `FK_LibraryID_Copy` (`LibraryID`),
  CONSTRAINT `FK_ItemID_Copy` FOREIGN KEY (`ItemID`) REFERENCES `item` (`ItemID`),
  CONSTRAINT `FK_LibraryID_Copy` FOREIGN KEY (`LibraryID`) REFERENCES `library` (`LibraryID`)
) ENGINE=InnoDB AUTO_INCREMENT=149 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `copy`
--

LOCK TABLES `copy` WRITE;
/*!40000 ALTER TABLE `copy` DISABLE KEYS */;
INSERT INTO `copy` VALUES (1,1,1,0),(2,1,1,0),(3,2,1,0),(4,4,1,0),(5,1,1,0),(6,23,1,0),(7,23,1,0),(8,17,1,1),(9,17,1,0),(10,25,1,1),(11,25,1,0),(12,12,1,0),(13,5,2,0),(14,5,2,0),(15,5,2,0),(16,7,2,0),(17,7,2,0),(18,7,2,0),(19,7,2,0),(20,10,2,0),(21,10,2,0),(22,13,2,0),(23,13,2,0),(24,13,2,0),(25,13,2,0),(26,13,2,0),(27,9,1,0),(28,9,1,0),(29,9,1,0),(30,8,2,0),(31,8,2,0),(32,14,1,0),(33,14,1,0),(34,14,1,0),(35,11,2,0),(36,11,2,0),(37,6,1,0),(38,6,1,0),(39,6,1,0),(40,6,1,0),(41,24,1,1),(42,15,3,0),(43,15,3,0),(44,15,4,0),(45,15,4,0),(46,15,1,0),(47,15,1,0),(48,15,5,0),(49,15,5,0),(50,1,3,0),(51,20,3,0),(52,20,3,0),(53,20,1,0),(54,20,1,0),(55,20,4,0),(56,20,4,0),(57,23,4,0),(58,23,4,0),(59,13,5,0),(60,17,2,0),(61,20,5,0),(62,26,1,0),(63,26,1,0),(64,26,1,0),(65,26,1,0),(66,26,1,0),(67,26,1,0),(68,26,1,0),(69,23,1,0),(70,23,1,0),(71,23,1,0),(72,23,1,0),(73,23,1,0),(74,23,1,0),(75,23,1,0),(76,23,1,0),(77,23,1,0),(78,23,1,0),(79,36,1,0),(80,36,1,0),(81,35,3,0),(82,35,3,0),(83,35,3,0),(84,35,3,0),(85,27,2,0),(86,34,2,0),(87,34,2,0),(88,34,2,0),(89,37,1,0),(90,33,3,0),(91,33,3,0),(92,38,1,1),(93,38,1,1),(94,38,1,1),(95,48,2,0),(96,48,2,0),(97,48,2,0),(98,48,1,0),(99,48,1,0),(100,48,1,0),(101,49,1,0),(102,49,1,0),(103,49,3,0),(104,49,3,0),(105,49,3,0),(106,51,1,0),(107,51,1,0),(108,51,4,0),(109,51,4,0),(110,52,1,0),(111,52,1,0),(112,52,2,0),(113,53,1,0),(114,53,1,0),(115,53,5,0),(116,53,5,0),(117,53,5,0),(118,53,5,0),(119,53,5,0),(120,53,5,0),(121,54,1,0),(122,54,1,0),(123,54,2,0),(124,54,2,0),(125,55,1,0),(126,55,3,0),(127,55,3,0),(128,56,1,0),(129,56,4,0),(130,56,4,0),(131,57,1,0),(132,57,1,0),(133,57,5,0),(134,57,5,0),(135,58,1,0),(136,58,1,0),(137,58,4,0),(138,58,4,0),(139,59,5,0),(140,59,5,0),(141,59,1,0),(142,59,1,0),(143,60,1,0),(144,60,1,0),(145,60,5,0),(146,61,1,0),(147,61,1,0),(148,61,5,0);
/*!40000 ALTER TABLE `copy` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`loctrinh`@`%`*/ /*!50003 TRIGGER update_item_quantity AFTER INSERT ON `copy`
FOR EACH ROW
BEGIN
    UPDATE Item SET Quantity = Quantity + 1 WHERE ItemID = NEW.ItemID;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`loctrinh`@`%`*/ /*!50003 TRIGGER update_available
AFTER INSERT ON copy
FOR EACH ROW
BEGIN
    UPDATE Item
    SET Available = CASE
        WHEN (SELECT SUM(Quantity) FROM copy WHERE ItemID = NEW.ItemID) > 0 THEN 1
        ELSE 0
        END
    WHERE ItemID = NEW.ItemID;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-04-23 12:12:23
