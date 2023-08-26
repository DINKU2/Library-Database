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
-- Table structure for table `member`
--

DROP TABLE IF EXISTS `member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `member` (
  `MemberID` int(11) NOT NULL AUTO_INCREMENT,
  `MemberTypeID` int(11) NOT NULL,
  `AddressID` int(11) NOT NULL,
  `Lname` varchar(255) NOT NULL,
  `Active` tinyint(1) NOT NULL DEFAULT '0',
  `Fname` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `PhoneNum` varchar(10) NOT NULL,
  `ItemCount` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`MemberID`),
  KEY `FK_MemberTypeID_Member` (`MemberTypeID`),
  KEY `FK_AddressID_Member` (`AddressID`),
  CONSTRAINT `FK_AddressID_Member` FOREIGN KEY (`AddressID`) REFERENCES `address` (`AddressID`),
  CONSTRAINT `FK_MemberTypeID_Member` FOREIGN KEY (`MemberTypeID`) REFERENCES `membertype` (`MemberTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member`
--

LOCK TABLES `member` WRITE;
/*!40000 ALTER TABLE `member` DISABLE KEYS */;
INSERT INTO `member` VALUES (3,2,9,'Lane',1,'Aria','alane@example.com','$2y$10$TC/mypMrAtoGAzMaa3Lmzupk2p8jNfhOJuXQovW6OLqa11B3hrBMi','123456789',1),(4,1,10,'Price',0,'Kaleb','kprice@example.com','$2y$10$BFXTEvxWc7mXWyGXslt26OPaPsmJb3QM9pQyw7HWn/aalYirPrhbi','123456789',1),(5,2,11,'Bauerr',0,'Anaya','Anaya@example.com','$2y$10$WRbih/LwqfLcc9zY2Sg9k.RKxnleouwbQNXC919Hvvwh9MSSjoJvu','123456789',1),(6,1,12,'Mendez',0,'Zaiden','zmendez@example.com','$2y$10$DZaZ/X8K38Ef5ZApm59IluTHw.UOcL0BkBWYROzRWx.xz96IY9Poq','123456789',1),(7,1,13,'Carroll',1,'Adeline','acarroll@example.com','$2y$10$8bDrRgS7EZlGlWf0MCCSE.N.HcuxGjm3eEwpfebnDaOR11EBoqCfa','123456789',1),(8,1,14,'Mueller',1,'Ismael','imueller@example.com','$2y$10$nXp8.pL/hLr3GDDshTX.OebR/SAo9pbUspMnEnGDBJgHvwMFWsP5i','123456789',1),(9,1,15,'Holt',1,'Adalynn','aholt@example.com','$2y$10$TC/mypMrAtoGAzMaa3Lmzupk2p8jNfhOJuXQovW6OLqa11B3hrBMi','123456789',1),(10,1,16,'Rowe',1,'Maximus','mrowe@example.com','$2y$10$Tg/sbMAaHI.TfPsaz2kaJepxI8ZPUWrWV2v.lVZLonrJAJ9DfY/OG','123456789',2),(11,1,17,'Trinh',1,'Huu Loc','trhuloc9@gmail.com','$2y$10$Tg/sbMAaHI.TfPsaz2kaJepxI8ZPUWrWV2v.lVZLonrJAJ9DfY/OG','1234567890',7),(12,1,18,'Watanabe',1,'Sora','s.watanabe@example.com','$2y$10$/VpmNz2D9X//GswFd3.LyefsjfvaAojmoiHnf26WVRntrLKHlKnZS','123456789',1),(13,1,19,'Karunaratne',1,'Dinuk','dinukkarunartne747@gmail.com','$2y$10$F4TEq9/grkG9A8/UtHR7ju6e08a3h4Q36TOvOhXl9EyTNOTy.4zzi','8326094085',1),(14,1,20,'Walsh',1,'Charley','cwalsh@example.com','$2y$10$K/QxwayWJY42NBNHTSjIjOn8uz9UvEXxku7lS4nogRz6c4CyEJH7e','1234567890',1),(15,1,21,'Ahmed',1,'Raheemm','Raheeem@gmail.com','$2y$10$Tg/sbMAaHI.TfPsaz2kaJepxI8ZPUWrWV2v.lVZLonrJAJ9DfY/OG','0123456789',1),(16,1,22,'Grant',1,'Amara','agrant@example.com','$2y$10$Tg/sbMAaHI.TfPsaz2kaJepxI8ZPUWrWV2v.lVZLonrJAJ9DfY/OG','1234560000',1),(18,1,24,'Ingram',1,'Cory','cingram@example.com','$2y$10$qvcnHDFpaBE1TDNGt/Drt.hH.0jGMadAV8VRiMmmu8OwODNM7ypyW','123456789',1),(19,1,25,'Dial',1,'Carson','carsonmdial@gmail.com','$2y$10$oCCriRs2iviDfJ5iY4VG6uX1pjelTjE8am66.NBL3rEa61h5AezPu','1234567895',2),(24,1,30,'Bentley',1,'Olive','obentley@example.com','$2y$10$we41GiBM8elcr5zLjpym1urntIAvAZ5hOa9yRTGjrBcFeyyILrtha','123456789',1),(25,1,31,'Levy',1,'Lyle','llevy@example.com','$2y$10$quH66WZX2Vu0EcdsJi0MK.o14B.lfLet5OhsdacfPdfsyijpMVhbu','1599511590',1),(26,1,32,'Whitney',1,'Brynn','bwhitney@example.com','$2y$10$98KdJS8qM3AZ0tkRdOLmpuKXc0y7kr1B1J6NZPsmwnZ8KdJFL6Xsi','1599511590',1),(27,1,33,'Kramer',1,'Lionel','lkramer@example.com','$2y$10$j.Ki6q.SQi5BJ9WfvMOs3ut8DM8zXqlIe4TpxtAhzgpIOlcz1OGhG','1599511590',1),(28,1,34,'Richmond',1,'Darian','drichmond@example.com','$2y$10$EXRyiGMjWpbblU9vcOnR2ufSyrMs3d5jGj3sblnfK.Pd3av7an5w2','1234567890',1),(29,1,35,'Salas',1,'Beatrice','bsalas@example.com','$2y$10$YcZR5fsabuqWD1sdmUjNzuUKfUHnB9NcvN6MvRhIFbATN2e4FAMLO','1234567890',1),(30,1,36,'Mccoy',1,'Judah','jmccoy@example.com','$2y$10$H8aqG3mgjZfZ/4DrtsUMHOBW4iyvqKR.zzmVJx7eFkNxvuawuqSpy','1234567890',1),(31,1,37,'Huff',1,'Iliana','ihuff@example.com','$2y$10$5xDebG0UmADt4dAMzsSteeuql1bGvUzbY7wkv/r6mXPmfvrkMOuYi','1234567890',1),(32,1,38,'Benson',1,'Brett','bbenson@example.com','$2y$10$.UZ2JbshqBVZEEAU2XFUE.SjNMkRZ6v8Hpjmlf3xmaVfzGz12BkTy','1599511590',1),(33,1,39,'Liu',1,'Wei','wliu@example.com','$2y$10$cJxCVqjzjNfcjMWmHgwuDeaUqPCBgoTDx/e.87OKcz5E1VzPBV3Re','123456789',1),(34,1,41,'Chen',1,'Ling','lchen@example.com','$2y$10$cRbrQv1wM8R.nc5FE4b05eItfydmXI4vVqChnB3uXmi2JEbPWINsK','123456789',1),(35,1,42,'Zhang',1,'Jie','jzhang@example.com','$2y$10$B54/mmczjMc0xiu0kptupuydyYlaLrpKAN9YLCMcDxr/1O0ftwNJS','1234567890',1),(36,1,43,'Wang',1,'Hui','hwang@example.com','$2y$10$5XUqccWu2s9rtIj18DoCsuFh.XicnhbDAq803t3RWG2mRxHnWjSgS','1599511590',1),(37,1,44,'Li',1,'Mei','mli@example.com','$2y$10$K1ByQ.sqPRHERjfeByOWNe3akQv7Tp5qCDNrdLXqmSVOt6B71/O9G','1234567890',1),(38,2,45,'Nakamura',1,'Haruka','h.nakamura@example.com','$2y$10$7JmQD.64yyFLdXS8wuWHTuSePeeMZmd.PeULCLn3e8OmrvRv2qKHW','123456789',1),(39,1,46,'Saito',1,'Yuto','y.saito@example.com','$2y$10$5ZcuZ2wv6AssUY0YFTMmGeFptX7NGd4zj3tH43u3DW6oZAF5vc9Cq','1234567890',1),(40,2,47,'Ito',1,'Akiko','a.ito@example.com','$2y$10$ZPKaRofJE7JVuDzGhlasEeI6TYbwBqS9xMqIyJ5P3UgQJSWMQytX.','9090909090',1),(41,1,48,'Kim',1,'Min-Jae','mj.kim@example.com','$2y$10$6sa63tTzCqNHNW336fI9luMgRKeVQDFtyOEMVhoU4BwUiBh1m/TbG','7133858816',1),(42,1,51,'Perez',1,'Mauricio','mauri_guzman@yahoo.com','$2y$10$sDglpvwbnEgy3e86QYXMI.6dO57mqdiV5ncBJxXviQQ1XskVH42Ay','7133858816',1),(43,1,52,'Perez',1,'Mauricio','mauri1738guzman@gmail.com','$2y$10$PVDe7s6dDznxaZG2xSnFeeVYb5ZzGVlxHWbyZn/q2xFuFLIj7.Y8u','7133858816',1),(44,1,53,'Riddle',1,'Tom','triddles@gmail.com','$2y$10$qT2rvF0/4cRtmzQifFiYmuBjCNbk8h2ethMCJc7uNbqRLG4Nl41ne','7123456789',1),(45,1,58,'Ahmed',1,'Raheeemmm','Raheem@gmail.com','$2y$10$LiKp4RbYh5OGxHW/K4BZD.wV6QqfuCqdkoqUtjNibmkemKDSFcpFO','0123456789',1),(46,1,59,'bbb',1,'aaa','elaheh.bahar1@gmail.com','$2y$10$lw43/1U4ELjCty6X/l/MyO5ROozhXsq550enSPLDVdL.mT0WGvznq','7132569867',1),(47,2,62,'di',1,'car','test@gmail.com','$2y$10$Ywhk3XgP.hyxxGyG0.NRXeOwDt.za3uFIRJ6Bhs7i5yTaH71y6Uiy','1234567890',1),(48,1,63,'Perez',1,'Mauricio','mauri_guzman_@outlook.com','$2y$10$NJfdbt/cbad7iTLlstVUw.GSUvPYwzIofdmNhutz6AVOxSIeQgDmi','7133858816',4),(49,1,66,'Doe',1,'Jake','latefee@gmail.com','$2y$10$tE73TfagdCrv1acoI3XUreWBdAt20BJTBzoVdThnD26AKfr4brKY2','7133858816',4);
/*!40000 ALTER TABLE `member` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-04-23 12:12:16
