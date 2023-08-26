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
-- Table structure for table `employee`
--

DROP TABLE IF EXISTS `employee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employee` (
  `EmployeeID` int(11) NOT NULL AUTO_INCREMENT,
  `LibraryID` int(11) NOT NULL,
  `AddressID` int(11) NOT NULL,
  `Fname` varchar(255) NOT NULL,
  `Lname` varchar(255) NOT NULL,
  `Active` tinyint(1) NOT NULL DEFAULT '1',
  `Email` varchar(255) NOT NULL,
  `PhoneNum` varchar(10) NOT NULL,
  `Password` varchar(255) NOT NULL,
  PRIMARY KEY (`EmployeeID`),
  KEY `FK_LibraryID_Employee` (`LibraryID`),
  KEY `FK_AddressID_Employee` (`AddressID`),
  CONSTRAINT `FK_AddressID_Employee` FOREIGN KEY (`AddressID`) REFERENCES `address` (`AddressID`),
  CONSTRAINT `FK_LibraryID_Employee` FOREIGN KEY (`LibraryID`) REFERENCES `library` (`LibraryID`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee`
--

LOCK TABLES `employee` WRITE;
/*!40000 ALTER TABLE `employee` DISABLE KEYS */;
INSERT INTO `employee` VALUES (1,1,19,'Loc','Trinh',1,'admin@gmail.com','123456789','$2y$10$Tg/sbMAaHI.TfPsaz2kaJepxI8ZPUWrWV2v.lVZLonrJAJ9DfY/OG'),(2,2,56,'Michael','Davis',1,'michaeldavis@library.org','1597533215','$2y$10$7g32O41ZYfp5wjty1H08OuNP2Z0/bs4wMQZwYdpxbzdXOzi1KVbvK'),(3,4,57,'Mei Ling','Chen',1,'meilingchen@library.org','8521474566','$2y$10$Y8Qy1Nofx0izluXBvHuMf.7gBQ0znk/xcLvJUlCTiXAuF9neXD0Fi'),(4,1,1,'John','Doe',1,'johndoe1@library.org','4451234567','$2y$10$Tg/sbMAaHI.TfPsaz2kaJepxI8ZPUWrWV2v.lVZLonrJAJ9DfY/OG'),(5,2,2,'Jane','Doe',1,'janedoe@library.org','5552345678','$2y$10$Tg/sbMAaHI.TfPsaz2kaJepxI8ZPUWrWV2v.lVZLonrJAJ9DfY/OG'),(6,3,3,'Bob','Smith',1,'bobsmith@library.org','5553456789','$2y$10$Tg/sbMAaHI.TfPsaz2kaJepxI8ZPUWrWV2v.lVZLonrJAJ9DfY/OG'),(7,4,4,'Alice','Johnson',1,'alicejohnson@library.org','5554567890','$2y$10$Tg/sbMAaHI.TfPsaz2kaJepxI8ZPUWrWV2v.lVZLonrJAJ9DfY/OG'),(8,1,5,'David','Lee',1,'davidlee@library.org','5555678901','$2y$10$Tg/sbMAaHI.TfPsaz2kaJepxI8ZPUWrWV2v.lVZLonrJAJ9DfY/OG'),(9,2,6,'Catherine','Jones',1,'catherinejones@library.org','5556789012','$2y$10$Tg/sbMAaHI.TfPsaz2kaJepxI8ZPUWrWV2v.lVZLonrJAJ9DfY/OG'),(10,3,7,'Michael','Davis',1,'michaeldavis@library.org','5557890123','$2y$10$Tg/sbMAaHI.TfPsaz2kaJepxI8ZPUWrWV2v.lVZLonrJAJ9DfY/OG'),(11,4,8,'Emily','Garcia',1,'emilygarcia@library.org','5558901234','$2y$10$Tg/sbMAaHI.TfPsaz2kaJepxI8ZPUWrWV2v.lVZLonrJAJ9DfY/OG'),(12,1,9,'Daniel','Wilson',1,'danielwilson@library.org','5559012345','$2y$10$Tg/sbMAaHI.TfPsaz2kaJepxI8ZPUWrWV2v.lVZLonrJAJ9DfY/OG'),(13,2,10,'Stephanie','Brown',1,'stephaniebrown@library.org','5550123456','$2y$10$Tg/sbMAaHI.TfPsaz2kaJepxI8ZPUWrWV2v.lVZLonrJAJ9DfY/OG'),(14,4,64,'Do','Chen',1,'domixii@gmail.com','123456789','$2y$10$zDvL1N8k94gm13BPtcV/l.OwL8rQo4j5/31ARfzu.l8LOpR4pZLq6'),(15,4,65,'bjhbjh','bbh',1,'hmm@gmail.com','1234567876','$2y$10$QqvK2Ym5dSi0fRnAIdRFLObjqxuWGyxB2jdIEoFZ9O26wnzcURtS2');
/*!40000 ALTER TABLE `employee` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-04-23 12:12:12
