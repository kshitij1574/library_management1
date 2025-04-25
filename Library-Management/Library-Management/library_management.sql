-- MySQL dump 10.13  Distrib 8.0.33, for Linux (x86_64)
--
-- Host: localhost    Database: library_management
-- ------------------------------------------------------
-- Server version	8.0.33-0ubuntu0.22.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `library_management`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `library_management` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `library_management`;

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `books` (
  `book_id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `publication_year` int DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `available_quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`book_id`),
  UNIQUE KEY `isbn` (`isbn`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `books`
--

LOCK TABLES `books` WRITE;
/*!40000 ALTER TABLE `books` DISABLE KEYS */;
INSERT INTO `books` VALUES 
(1,'To Kill a Mockingbird','Harper Lee','9780446310789',1960,'Fiction',5,3,'2023-01-15 09:30:00'),
(2,'1984','George Orwell','9780451524935',1949,'Science Fiction',3,1,'2023-01-16 10:15:00'),
(3,'The Great Gatsby','F. Scott Fitzgerald','9780743273565',1925,'Fiction',4,4,'2023-01-17 11:20:00'),
(4,'Pride and Prejudice','Jane Austen','9781503290564',1813,'Romance',2,2,'2023-01-18 14:45:00'),
(5,'The Hobbit','J.R.R. Tolkien','9780547928227',1937,'Fantasy',6,5,'2023-01-19 16:30:00'),
(6,'The Catcher in the Rye','J.D. Salinger','9780316769488',1951,'Fiction',3,3,'2023-01-20 09:10:00'),
(7,'Brave New World','Aldous Huxley','9780060850524',1932,'Science Fiction',2,1,'2023-01-21 10:25:00'),
(8,'The Lord of the Rings','J.R.R. Tolkien','9780544003415',1954,'Fantasy',4,2,'2023-01-22 13:40:00'),
(9,'Jane Eyre','Charlotte Brontë','9780141441146',1847,'Romance',3,3,'2023-01-23 15:55:00'),
(10,'Animal Farm','George Orwell','9780451526342',1945,'Political Satire',5,4,'2023-01-24 17:20:00');
/*!40000 ALTER TABLE `books` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `members` (
  `member_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text,
  `join_date` date NOT NULL DEFAULT (curdate()),
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`member_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `members`
--

LOCK TABLES `members` WRITE;
/*!40000 ALTER TABLE `members` DISABLE KEYS */;
INSERT INTO `members` VALUES 
(1,'John Smith','john.smith@example.com','555-0101','123 Main St, Anytown','2023-01-10','2023-01-10 09:00:00'),
(2,'Emily Johnson','emily.j@example.com','555-0102','456 Oak Ave, Somewhere','2023-01-12','2023-01-12 10:30:00'),
(3,'Michael Brown','michael.b@example.com','555-0103','789 Pine Rd, Nowhere','2023-01-15','2023-01-15 11:45:00'),
(4,'Sarah Davis','sarah.d@example.com','555-0104','321 Elm St, Anywhere','2023-01-18','2023-01-18 14:20:00'),
(5,'David Wilson','david.w@example.com','555-0105','654 Maple Dr, Everywhere','2023-01-20','2023-01-20 16:10:00');
/*!40000 ALTER TABLE `members` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `loans`
--

DROP TABLE IF EXISTS `loans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `loans` (
  `loan_id` int NOT NULL AUTO_INCREMENT,
  `book_id` int NOT NULL,
  `member_id` int NOT NULL,
  `loan_date` date NOT NULL DEFAULT (curdate()),
  `due_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `status` enum('on loan','returned','overdue') DEFAULT 'on loan',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`loan_id`),
  KEY `book_id` (`book_id`),
  KEY `member_id` (`member_id`),
  CONSTRAINT `loans_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`),
  CONSTRAINT `loans_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `loans`
--

LOCK TABLES `loans` WRITE;
/*!40000 ALTER TABLE `loans` DISABLE KEYS */;
INSERT INTO `loans` VALUES 
(1,1,1,'2023-01-25','2023-02-08',NULL,'on loan','2023-01-25 10:00:00'),
(2,2,2,'2023-01-26','2023-02-09',NULL,'on loan','2023-01-26 11:15:00'),
(3,8,3,'2023-01-27','2023-02-10',NULL,'on loan','2023-01-27 13:30:00'),
(4,7,4,'2023-01-20','2023-02-03','2023-01-28','returned','2023-01-20 14:45:00'),
(5,5,5,'2023-01-22','2023-02-05',NULL,'overdue','2023-01-22 16:20:00');
/*!40000 ALTER TABLE `loans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'library_management'
--

--
-- Procedure to update loan status to overdue
--

DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `update_overdue_loans`()
BEGIN
    UPDATE loans 
    SET status = 'overdue' 
    WHERE status = 'on loan' 
    AND due_date < CURDATE() 
    AND return_date IS NULL;
END ;;
DELIMITER ;

--
-- Event to run the overdue loan update daily
--

DELIMITER ;;
CREATE DEFINER=`root`@`localhost` EVENT `daily_overdue_check`
ON SCHEDULE EVERY 1 DAY STARTS '2023-01-01 00:00:00'
ON COMPLETION PRESERVE ENABLE
DO CALL update_overdue_loans() ;;
DELIMITER ;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-06-15 10:30:45