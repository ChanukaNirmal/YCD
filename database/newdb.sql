-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: ycd
-- ------------------------------------------------------
-- Server version	8.0.40

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
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart` (
  `Cart_ID` int NOT NULL AUTO_INCREMENT,
  `Viewer_ID` int DEFAULT NULL,
  `Recipe_ID` int DEFAULT NULL,
  `Quantity` int DEFAULT '1',
  PRIMARY KEY (`Cart_ID`),
  KEY `Viewer_ID` (`Viewer_ID`),
  KEY `Recipe_ID` (`Recipe_ID`),
  CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`Viewer_ID`) REFERENCES `viewerregister` (`Viewer_ID`),
  CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`Recipe_ID`) REFERENCES `recipe` (`Recipe_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart`
--

LOCK TABLES `cart` WRITE;
/*!40000 ALTER TABLE `cart` DISABLE KEYS */;
INSERT INTO `cart` VALUES (4,14,32,2);
/*!40000 ALTER TABLE `cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `creatorregister`
--

DROP TABLE IF EXISTS `creatorregister`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `creatorregister` (
  `Creator_ID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Phone_number` varchar(20) DEFAULT NULL,
  `Password` varchar(100) DEFAULT NULL,
  `Channel_name` varchar(100) DEFAULT NULL,
  `Channel_link` varchar(255) DEFAULT NULL,
  `Number_of_subscribers` int DEFAULT NULL,
  `Number_of_recipes` int DEFAULT NULL,
  `Location` varchar(100) DEFAULT NULL,
  `logo` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`Creator_ID`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `creatorregister`
--

LOCK TABLES `creatorregister` WRITE;
/*!40000 ALTER TABLE `creatorregister` DISABLE KEYS */;
INSERT INTO `creatorregister` VALUES (6,'Kasimir Jarvis','ryfacazuj@mailinator.com','+1 (439) 781-3675','$2y$10$cxZALkj6Rp1Iaj8NP1E34u8NXGWFESrLemfu.yIngEUNCSqDVrwPS','Leonard Roberts','Incididunt praesenti',92,9,'Magni labore nostrum',NULL),(9,'Nira S','chanukaati@gmail.com','0718920133','$2y$10$eyM0n8rQWq660WN2tEd60u/GLKtEZJBTA3TPStOqjkqBHhyZmk05K','Nira S','https://www.youtube.com/results?search_query=veo+3+sinhala',123,1233,'colombo','uploads/logo_683987aa02e8d_channels4_profile (3).jpg'),(10,'sa as','chanukasati@gmail.com','0718920133','$2y$10$s1rYyHr/vHu1UejXws3R6.8wEamXvCtf.iqZ7SvMA8Z8YwoW42RSO','poorna','Ea eligendi necessit',73,91,'sd','uploads/logo_68398d6c6fe93_channels4_profile (1).jpg'),(11,'sa as','123@gmail.com','0718920133','$2y$10$2uuNutvU31zLS1spPBXXX.6SZnEJfFD0Br5zOPWkt9Kr4v8wo26aK','waruni\'s kitchen','https://www.youtube.com/results?search_query=veo+3+sinhala',432,1212,'colombo','uploads/logo_6839eb421c889_channels4_profile (2).jpg');
/*!40000 ALTER TABLE `creatorregister` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order`
--

DROP TABLE IF EXISTS `order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order` (
  `Order_ID` int NOT NULL AUTO_INCREMENT,
  `Address` varchar(255) DEFAULT NULL,
  `Quantity` int DEFAULT NULL,
  `Recipe_ID` int DEFAULT NULL,
  `Viewer_ID` int DEFAULT NULL,
  `state` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`Order_ID`),
  KEY `Recipe_ID` (`Recipe_ID`),
  KEY `Viewer_ID` (`Viewer_ID`),
  CONSTRAINT `order_ibfk_1` FOREIGN KEY (`Recipe_ID`) REFERENCES `recipe` (`Recipe_ID`),
  CONSTRAINT `order_ibfk_2` FOREIGN KEY (`Viewer_ID`) REFERENCES `viewerregister` (`Viewer_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order`
--

LOCK TABLES `order` WRITE;
/*!40000 ALTER TABLE `order` DISABLE KEYS */;
INSERT INTO `order` VALUES (27,'dasdadsdsd',1,31,14,'dispatched'),(28,'dasdadsdsd',1,31,14,'dispatched'),(29,'dasdadsdsd',1,32,14,'dispatched'),(30,'dasdadsdsd',3,31,14,'dispatched'),(31,'dasdadsdsd',1,32,14,'accepted'),(32,'dasdadsdsd',1,25,14,NULL),(33,'dasdadsdsd',1,30,16,NULL),(34,'Siyambalawala,Imbulana,Ruwanwella.',2,32,18,'dispatched');
/*!40000 ALTER TABLE `order` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recipe`
--

DROP TABLE IF EXISTS `recipe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recipe` (
  `Recipe_ID` int NOT NULL AUTO_INCREMENT,
  `Recipe_title` varchar(100) DEFAULT NULL,
  `Recipe_description` text,
  `ytLink` varchar(255) DEFAULT NULL,
  `Preparation_time` int DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL,
  `Delivery_areas` varchar(255) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `Dish_image_1` varchar(255) DEFAULT NULL,
  `Dish_image_2` varchar(255) DEFAULT NULL,
  `Dish_image_3` varchar(255) DEFAULT NULL,
  `Creator_ID` int DEFAULT NULL,
  PRIMARY KEY (`Recipe_ID`),
  KEY `Creator_ID` (`Creator_ID`),
  CONSTRAINT `Creator_ID` FOREIGN KEY (`Creator_ID`) REFERENCES `creatorregister` (`Creator_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recipe`
--

LOCK TABLES `recipe` WRITE;
/*!40000 ALTER TABLE `recipe` DISABLE KEYS */;
INSERT INTO `recipe` VALUES (22,'Commodi ut dolor non','Excepteur veritatis ','https://www.youtube.com/watch?v=pOXGWEKNDww',92,127.00,'Error adipisci a num','uploads/download.jpg','uploads/images (3).jpg','uploads/images (1).jpg','uploads/images (2).jpg',NULL),(23,'Qui aut itaque ratio','Consequuntur repudia','https://www.pukad.com.au',24,671.00,'Qui amet eaque aut ','uploads/images (3).jpg','uploads/images.jpg','uploads/download.jpg','uploads/images (3).jpg',NULL),(24,'Aut magnam eu quis m','Est hic ut commodi r','https://www.jabidorewa.in',84,531.00,'Nostrum inventore si','uploads/images (4).jpg','uploads/images (2).jpg','uploads/download.jpg','uploads/images (1).jpg',6),(25,'Et quidem temporibus','Duis enim voluptates','https://www.youtube.com/watch?v=pOXGWEKNDww',38,826.00,'Blanditiis odit mole','uploads/download.jpg','uploads/mqdefault_6s.webp','uploads/mqdefault_6s (4).webp','uploads/images.jpg',6),(26,'Aut magnam eu quis m','Est hic ut commodi r','https://www.jabidorewa.in',84,531.00,'Nostrum inventore si','uploads/images (4).jpg','uploads/images (2).jpg','uploads/download.jpg','uploads/images (1).jpg',6),(27,'Aut magnam eu quis m','Est hic ut commodi r','https://www.jabidorewa.in',84,531.00,'Nostrum inventore si','uploads/images (4).jpg','uploads/images (2).jpg','uploads/download.jpg','uploads/images (1).jpg',6),(28,'Aut magnam eu quis m','Est hic ut commodi r','https://www.jabidorewa.in',84,531.00,'Nostrum inventore si','uploads/images (4).jpg','uploads/images (2).jpg','uploads/download.jpg','uploads/images (1).jpg',6),(29,'Fugiat qui placeat ','Qui pariatur Fugiat','https://www.youtube.com/watch?v=pOXGWEKNDww',77,334.00,'colombo','uploads/download.jpg','uploads/images (3).jpg','uploads/images (1).jpg','uploads/mqdefault_6s (2).webp',9),(30,'Officia sed velit fa','Nisi voluptas tempor','https://www.youtube.com/watch?v=pOXGWEKNDww',36,832.00,'Provident tempore ','uploads/images.jpg','uploads/mqdefault_6s (4).webp','uploads/mqdefault_6s.webp','uploads/images (3).jpg',10),(31,'Chicken Biriyani','Indulge in the rich and aromatic flavours of Chicken Biryani, a timeless Indian delicacy that brings together succulent pieces of marinated chicken, fragrant basmati rice, and a medley of warm spices. Layered and slow-cooked to perfection, this dish is infused with saffron, caramelized onions, and fresh herbs, creating a mouthwatering experience in every bite. Whether served at a festive gathering or enjoyed as a comforting meal, this Chicken Biryani is sure to satisfy both the soul and the stomach. Perfect when paired with raita, boiled eggs, or a spicy salad.','https://www.youtube.com/watch?v=pOXGWEKNDww',60,900.00,'colombo','uploads/chicken-biryani-design-template-41c9db2a7a1fd2eeb61e9712f80e0509_screen.jpg','uploads/image.jpeg','uploads/1000_F_252459861_GsIZfY0D57QVbcclbCQlawabYnj7bWPh.jpg','uploads/images.jpeg',11),(32,'මේක නම් මරු.. එලවලු නැතිව සුපිරි නූඩ්ල්ස් එකක් - Simple Noodles Recipe Without Vegetables','මේක නම් මරු එලවලු නැතිව සුපිරි නූඩ්ල්ස් එකක් - Simple Noodles Recipe Without Vegetables In this video, you can learn how to make noodles without vegetables. This is a simple recipe but a very delicious dish. Try out this noodles recipe.\r\n\r\nඅවශ්‍ය ද්‍රව්‍ය:\r\nනූඩ්ල්ස් 100g\r\nතක්කාලි සෝස් මේසහැඳි 02\r\nසොයා සෝස් මේසහැඳි 01\r\nගම්මිරිස් තේහැඳි 1/4\r\nලුණු\r\nමිරිස් කෑලි මේසහැඳි 01\r\nමිරිස් කුඩු තේහැඳි 1/2\r\nසීනි තේහැඳි 1/2\r\nපොල්තෙල් මේසහැඳි 02\r\nසුදු ලූණු බික් 04','https://www.youtube.com/watch?v=kJ6prgf7Qfk',20,600.00,'colombo','uploads/hq720.jpg','uploads/20221130023757-untitled-design-12-3.webp','uploads/20221203144456-chicken-lo-mein.webp','uploads/20230516091139-my-20home-20pantry-20recipes-20-1.webp',11);
/*!40000 ALTER TABLE `recipe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `review`
--

DROP TABLE IF EXISTS `review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `review` (
  `Review_ID` int NOT NULL AUTO_INCREMENT,
  `Viewer_ID` int DEFAULT NULL,
  `Recipe_ID` int DEFAULT NULL,
  `ReviewerName` varchar(100) DEFAULT NULL,
  `Rating` int DEFAULT NULL,
  `Comment` text,
  `Created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Review_ID`),
  KEY `Viewer_ID` (`Viewer_ID`),
  KEY `Recipe_ID` (`Recipe_ID`),
  CONSTRAINT `review_ibfk_1` FOREIGN KEY (`Viewer_ID`) REFERENCES `viewerregister` (`Viewer_ID`),
  CONSTRAINT `review_ibfk_2` FOREIGN KEY (`Recipe_ID`) REFERENCES `recipe` (`Recipe_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `review`
--

LOCK TABLES `review` WRITE;
/*!40000 ALTER TABLE `review` DISABLE KEYS */;
INSERT INTO `review` VALUES (13,14,31,'Nimal jayathilaka',3,'Good, but a bit too spicy for my taste.','2025-06-02 14:01:03');
/*!40000 ALTER TABLE `review` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ride`
--

DROP TABLE IF EXISTS `ride`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ride` (
  `Ride_ID` int NOT NULL AUTO_INCREMENT,
  `Order_ID` int DEFAULT NULL,
  `Rider_ID` int DEFAULT NULL,
  `Pickup_Location` varchar(255) DEFAULT NULL,
  `Dropoff_Location` varchar(255) DEFAULT NULL,
  `Assigned_Time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Ride_ID`),
  KEY `Order_ID` (`Order_ID`),
  KEY `Rider_ID` (`Rider_ID`),
  CONSTRAINT `ride_ibfk_1` FOREIGN KEY (`Order_ID`) REFERENCES `order` (`Order_ID`),
  CONSTRAINT `ride_ibfk_2` FOREIGN KEY (`Rider_ID`) REFERENCES `riderregister` (`Rider_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ride`
--

LOCK TABLES `ride` WRITE;
/*!40000 ALTER TABLE `ride` DISABLE KEYS */;
INSERT INTO `ride` VALUES (5,31,1,'colombo','dasdadsdsd','2025-06-02 19:10:54'),(6,31,3,'colombo','dasdadsdsd','2025-06-02 19:11:02'),(7,31,3,'colombo','dasdadsdsd','2025-06-02 19:11:04'),(8,31,3,'colombo','dasdadsdsd','2025-06-02 19:22:46'),(9,30,3,'colombo','dasdadsdsd','2025-06-02 19:32:56'),(10,30,2,'colombo','dasdadsdsd','2025-06-02 19:49:05'),(11,34,3,'colombo','Siyambalawala,Imbulana,Ruwanwella.','2025-06-02 19:54:56'),(13,34,6,'colombo','Siyambalawala,Imbulana,Ruwanwella.','2025-06-04 17:49:03');
/*!40000 ALTER TABLE `ride` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `riderregister`
--

DROP TABLE IF EXISTS `riderregister`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `riderregister` (
  `Rider_ID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) DEFAULT NULL,
  `Service_area` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Phone_number` varchar(20) DEFAULT NULL,
  `Password` varchar(100) DEFAULT NULL,
  `Google_account` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`Rider_ID`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `riderregister`
--

LOCK TABLES `riderregister` WRITE;
/*!40000 ALTER TABLE `riderregister` DISABLE KEYS */;
INSERT INTO `riderregister` VALUES (1,'Rogan Chen','Harum possimus ipsa','raxyhe@mailinator.com','+1 (791) 446-5774','$2y$10$KwLmI7wBUxsL1kmdgWqcaOiDTzVb1OS320nyGTczB5hvZ53mNWQci',NULL),(2,'Norman Velez','Minus maiores odio q','zujalepek@mailinator.com','+1 (928) 787-3586','$2y$10$gvD4DnzDryM1ue/dqPuJ/Omqar5kKcRWb1WhOgfSI6gKMJgC0OCAm',NULL),(3,'koobiyo','colombo','chanukaastwi@gmail.com','0718920133','$2y$10$On279iuhnIhzE8FfUl.77uKviSzjFmzzf7ktOO9tEfb318sM/IzNG',NULL),(4,'Fardar Express','colombo','fardar@gmail.com','0718920188','$2y$10$940l3v7fmLGSwVAhsXmlW.rykbbL4AMtE7wIpngu54ELNeudUoZx.',NULL),(5,'111','colombo','chanukaati@gmail.com','0718920133','$2y$10$ZE3LijuBZ08V9ovhAWuzquGhmfTqF5YT6vTcBKiWVkVC40UDBdeTq',NULL),(6,'111','Dolor ducimus alias','ch@gmail.com','0718920133','$2y$10$EI056.J9VGNpaTuQATfTGeSHLX2KiN6ZrP2wXechcpIOeyqMSVcHS',NULL);
/*!40000 ALTER TABLE `riderregister` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `viewerregister`
--

DROP TABLE IF EXISTS `viewerregister`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `viewerregister` (
  `Viewer_ID` int NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Phone_number` varchar(20) DEFAULT NULL,
  `Password` varchar(100) DEFAULT NULL,
  `Google_account` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`Viewer_ID`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `viewerregister`
--

LOCK TABLES `viewerregister` WRITE;
/*!40000 ALTER TABLE `viewerregister` DISABLE KEYS */;
INSERT INTO `viewerregister` VALUES (14,'Dulanka nimsara','dasdadsdsd','chi@gmail.com','0718920133','$2y$10$FdlYfz3NKiLl6oz120/xIOHN4uA3z07r4aIxZIHRWxHGvq0YW3A92',NULL),(15,'Renee Short','Omnis sed praesentiu','varavyf@mailinator.com','+1 (859) 515-1413','$2y$10$JOzBI61K.BpTcepNFpPwPeL/hP/.z0.FKvzH9mnjH4bjLmgiEIhz6',NULL),(16,'sa as','dasdadsdsd','ati@gmail.com','0718920133','$2y$10$5mwWyjsKkLcFhucGczrG.uW/NYOPqOq1//YFvZeYo.OP7//DWjW0e',NULL),(18,'chanuka nirmal','Siyambalawala,Imbulana,Ruwanwella.','chanuke2aati@gmail.com','0718920133','$2y$10$5F1BDszD5x/5DD4bzyZmreN0.PMTvNFBjpRVX2M4yzxWNnAWJygMa',NULL),(19,'CHANUKA','dasdadsdsd','chanuka@gmail.com','0718920133','$2y$10$z7Bg.mmRaDhyxp5izxPOfuhPcS3j17y.BZ1Lltc34sOhMi3g6aO26',NULL),(20,'chanuka nirmal','dasdadsdsd','11@gmail.com','0718920133','$2y$10$3yJQF94IWU1w256SVxMdx.uk.bsNcy39yTEo4C3lHp904xP1ZukR.',NULL),(21,'Dulanka nimsara','dasdadsdsd','chanukaastwi@gmail.com','0718920133','$2y$10$TI8mkpI9qTuvQD58DD4DS.u3P9avNAdmdY2F8G1jDcZGORUEWOWQu',NULL);
/*!40000 ALTER TABLE `viewerregister` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-05  0:06:29
