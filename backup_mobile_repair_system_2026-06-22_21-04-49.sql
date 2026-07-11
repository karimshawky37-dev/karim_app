-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: mobile_repair_system
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `mobile_repair_system`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `mobile_repair_system` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `mobile_repair_system`;

--
-- Table structure for table `ai_assistant_logs`
--

DROP TABLE IF EXISTS `ai_assistant_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ai_assistant_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `device_id` int(11) unsigned DEFAULT NULL,
  `query` text NOT NULL,
  `response` text NOT NULL,
  `confidence_score` decimal(3,2) DEFAULT NULL,
  `suggested_actions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`suggested_actions`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ai_assistant_logs`
--

LOCK TABLES `ai_assistant_logs` WRITE;
/*!40000 ALTER TABLE `ai_assistant_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `ai_assistant_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attendance`
--

DROP TABLE IF EXISTS `attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attendance` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `check_in` datetime NOT NULL,
  `check_out` datetime DEFAULT NULL,
  `status` enum('present','absent','late','half_day','holiday','weekend','leave') DEFAULT 'present',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `check_in_ip` varchar(45) DEFAULT NULL,
  `check_out_ip` varchar(45) DEFAULT NULL,
  `check_in_device` text DEFAULT NULL,
  `check_out_device` text DEFAULT NULL,
  `working_hours` decimal(5,2) DEFAULT NULL,
  `is_modified` tinyint(1) DEFAULT 0,
  `modified_by` int(10) unsigned DEFAULT NULL,
  `modified_at` timestamp NULL DEFAULT NULL,
  `modification_reason` text DEFAULT NULL,
  `shift_type` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_attendance_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendance`
--

LOCK TABLES `attendance` WRITE;
/*!40000 ALTER TABLE `attendance` DISABLE KEYS */;
INSERT INTO `attendance` VALUES (1,7,'2026-06-22 20:16:48',NULL,'late',NULL,'2026-06-22 17:16:48','::1',NULL,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL,NULL,0,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `attendance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `shift_id` int(11) unsigned DEFAULT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_role` varchar(20) NOT NULL,
  `action` varchar(50) NOT NULL,
  `table_name` varchar(50) DEFAULT NULL,
  `record_id` int(11) unsigned DEFAULT NULL,
  `old_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_data`)),
  `new_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_data`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `shift_audit_index` (`shift_id`)
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,1,NULL,'مدير النظام','admin','shift_start','shifts',1,NULL,'{\"user\":\"\\u0645\\u062f\\u064a\\u0631 \\u0627\\u0644\\u0646\\u0638\\u0627\\u0645\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 02:13:58'),(5,1,1,'مدير النظام','admin','shift_end','shifts',1,NULL,'{\"status\":\"closed\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 03:05:06'),(6,1,NULL,'مدير النظام','admin','create','devices',2,NULL,'{\"device_code\":\"DEV-2026-1836\",\"brand\":\"\\u0627\\u0648\\u0628\\u0648\",\"model\":\"d\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:24:38'),(7,1,NULL,'مدير النظام','admin','logout','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:26:45'),(8,2,NULL,'محاسب النظام','guest','login','users',2,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:26:48'),(9,2,NULL,'محاسب النظام','accountant','logout','users',2,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:26:53'),(10,3,NULL,'أحمد فني بوردة','guest','login','users',3,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:26:55'),(11,3,NULL,'أحمد فني بوردة','technician','logout','users',3,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:27:11'),(12,3,NULL,'أحمد فني بوردة','guest','login','users',3,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:27:15'),(13,3,NULL,'أحمد فني بوردة','technician','logout','users',3,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:27:18'),(14,4,NULL,'محمد فني سوفت وير','guest','login','users',4,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:27:21'),(15,4,NULL,'محمد فني سوفت وير','technician','logout','users',4,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:27:28'),(16,1,NULL,'مدير النظام','guest','login','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:27:30'),(17,1,NULL,'مدير النظام','admin','logout','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:37:00'),(18,4,NULL,'محمد فني سوفت وير','guest','login','users',4,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:37:05'),(19,4,NULL,'محمد فني سوفت وير','technician','logout','users',4,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:37:22'),(20,2,NULL,'محاسب النظام','guest','login','users',2,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:37:29'),(21,2,NULL,'محاسب النظام','accountant','create','devices',3,NULL,'{\"device_code\":\"DEV-2026-0725\",\"brand\":\"\\u0627\\u0648\\u0628\\u0648\",\"model\":\"1+\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:37:58'),(22,2,NULL,'محاسب النظام','accountant','logout','users',2,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:38:05'),(23,4,NULL,'محمد فني سوفت وير','guest','login','users',4,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:38:08'),(24,4,NULL,'محمد فني سوفت وير','technician','logout','users',4,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:38:18'),(25,3,NULL,'أحمد فني بوردة','guest','login','users',3,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:38:22'),(26,3,NULL,'أحمد فني بوردة','technician','logout','users',3,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:38:27'),(27,5,NULL,'علي فني فك وتقفيل','guest','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:38:33'),(28,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:19:31'),(29,1,NULL,'مدير النظام','guest','login','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:19:34'),(30,1,NULL,'مدير النظام','admin','logout','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:20:12'),(31,5,NULL,'علي فني فك وتقفيل','guest','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:20:18'),(32,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:20:36'),(33,1,NULL,'مدير النظام','guest','login','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:20:40'),(34,1,NULL,'مدير النظام','admin','create','devices',4,NULL,'{\"device_code\":\"DEV-2026-0782\",\"brand\":\"\\u0627\\u0648\\u0628\\u0648\",\"model\":\"\\u064a\\u0633\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:21:55'),(35,1,NULL,'مدير النظام','admin','logout','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:22:14'),(36,5,NULL,'علي فني فك وتقفيل','guest','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:22:17'),(37,5,NULL,'علي فني فك وتقفيل','technician','create','devices',5,NULL,'{\"device_code\":\"DEV-2026-4514\",\"brand\":\"1+\",\"model\":\"\\u064a\\u0633\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:27:49'),(38,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:38:55'),(39,1,NULL,'مدير النظام','guest','login','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:38:58'),(40,1,NULL,'مدير النظام','admin','logout','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:48:38'),(41,5,NULL,'علي فني فك وتقفيل','guest','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:48:41'),(42,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:49:22'),(43,2,NULL,'محاسب النظام','guest','login','users',2,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:49:25'),(44,2,NULL,'محاسب النظام','accountant','login','users',2,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 12:28:59'),(45,2,NULL,'محاسب النظام','accountant','logout','users',2,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 12:30:43'),(46,1,NULL,'مدير النظام','admin','login','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 12:30:46'),(47,1,NULL,'مدير النظام','admin','logout','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 12:31:27'),(48,3,NULL,'أحمد فني بوردة','technician','login','users',3,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 12:31:32'),(49,3,NULL,'أحمد فني بوردة','technician','logout','users',3,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 12:32:02'),(50,2,NULL,'محاسب النظام','accountant','login','users',2,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 12:32:07'),(51,2,NULL,'محاسب النظام','accountant','update','sales',1,'{\"paid_amount\":\"0.00\",\"status\":\"pending\"}','{\"paid_amount\":300,\"status\":\"completed\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 13:00:24'),(52,2,NULL,'محاسب النظام','accountant','update','sales',4,'{\"paid_amount\":\"0.00\",\"status\":\"pending\"}','{\"paid_amount\":300,\"status\":\"completed\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 13:06:19'),(53,2,NULL,'محاسب النظام','accountant','update','installments',1,'{\"remaining_amount\":\"0.00\"}','{\"remaining_amount\":0,\"paid\":17325}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 13:24:06'),(54,2,NULL,'محاسب النظام','accountant','logout','users',2,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 13:31:28'),(55,1,NULL,'مدير النظام','admin','login','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 13:31:31'),(56,1,NULL,'مدير النظام','admin','update','sales',7,'{\"paid_amount\":\"0.00\",\"status\":\"pending\"}','{\"paid_amount\":300,\"status\":\"completed\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 13:32:34'),(57,1,NULL,'مدير النظام','admin','create','inventory_count',0,NULL,'{\"notes\":\"fe\",\"differences\":[{\"id\":1,\"old\":1,\"new\":4,\"diff\":3}]}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 15:40:07'),(58,1,NULL,'مدير النظام','admin','update','sales',8,'{\"paid_amount\":\"0.00\",\"status\":\"pending\"}','{\"paid_amount\":300,\"status\":\"completed\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 15:47:54'),(59,1,NULL,'مدير النظام','admin','update','sales',9,'{\"paid_amount\":\"0.00\",\"status\":\"pending\"}','{\"paid_amount\":300,\"status\":\"completed\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 15:48:33'),(60,1,NULL,'مدير النظام','admin','create','users',7,NULL,'{\"username\":\"الشيخ محمود\",\"full_name\":\"محمود يونس\",\"role\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 16:18:05'),(61,1,NULL,'مدير النظام','admin','logout','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 16:18:10'),(62,7,NULL,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 16:18:15'),(63,7,NULL,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 16:18:23'),(64,7,NULL,'محمود يونس','admin','update','users',3,'{\"is_active\":1}','{\"is_active\":0}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 16:25:01'),(65,7,NULL,'محمود يونس','admin','update','users',3,'{\"is_active\":0}','{\"is_active\":1}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 16:25:08'),(66,7,NULL,'محمود يونس','admin','update','users',4,'{\"is_active\":1}','{\"is_active\":0}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 16:25:11'),(67,7,NULL,'محمود يونس','admin','update','users',4,'{\"is_active\":0}','{\"is_active\":1}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 16:25:13'),(68,7,NULL,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 16:26:49'),(69,3,NULL,'أحمد فني بوردة','technician','login','users',3,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 16:26:52'),(70,3,NULL,'أحمد فني بوردة','technician','logout','users',3,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 16:54:16'),(71,7,NULL,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 16:54:20'),(72,7,NULL,'محمود يونس','admin','create','devices',12,NULL,'{\"device_code\":\"DEV-2026-4270\",\"customer_id\":1,\"brand\":\"اوبو\",\"model\":\"يس\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 17:03:17'),(73,7,NULL,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 04:26:57'),(74,7,NULL,'محمود يونس','admin','shift_start','shifts',2,NULL,'{\"user\":\"محمود يونس\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 05:05:40'),(75,7,2,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 05:05:52'),(76,2,NULL,'محاسب النظام','accountant','login','users',2,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 05:05:55'),(77,2,NULL,'محاسب النظام','accountant','create','devices',13,NULL,'{\"device_code\":\"DEV-2026-0551\",\"customer_id\":1,\"brand\":\"شاومي\",\"model\":\"14\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 05:06:40'),(78,2,NULL,'محاسب النظام','accountant','logout','users',2,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 05:08:15'),(79,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 05:08:18'),(80,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 05:09:00'),(81,7,2,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 05:09:03'),(82,7,2,'محمود يونس','admin','create','devices',14,NULL,'{\"device_code\":\"DEV-2026-6403\",\"customer_id\":1,\"brand\":\"شاومي\",\"model\":\"1+\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 05:15:55'),(83,7,2,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 05:53:22'),(84,7,2,'محمود يونس','admin','create','inventory',12,NULL,'{\"name\":\"شاشة اوبو\",\"category\":\"شاشة\",\"quantity\":10,\"purchase_price\":500,\"selling_price\":700}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 05:54:07'),(85,7,2,'محمود يونس','admin','update','sales',10,'{\"paid_amount\":\"0.00\",\"status\":\"pending\"}','{\"paid_amount\":300,\"status\":\"completed\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 06:10:40'),(86,7,2,'محمود يونس','admin','create','sales',12,NULL,'{\"invoice_number\":\"INV-2026-23592\",\"customer_id\":1,\"total_amount\":700,\"payment_method\":\"cash\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 06:15:35'),(87,7,2,'محمود يونس','admin','create','sales',13,NULL,'{\"invoice_number\":\"INV-2026-39902\",\"customer_id\":1,\"total_amount\":700,\"payment_method\":\"cash\",\"status\":\"pending\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 06:30:18'),(88,7,2,'محمود يونس','admin','create','devices',15,NULL,'{\"device_code\":\"DEV-2026-7612\",\"customer_id\":1,\"brand\":\"شاومي\",\"model\":\"يس\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 08:18:02'),(89,7,2,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 08:20:59'),(90,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 08:21:04'),(91,5,NULL,'علي فني فك وتقفيل','technician','create','device_checklist',1,NULL,'{\"device_id\":15,\"type\":\"after_repair\",\"technician_id\":5}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 08:31:25'),(92,5,NULL,'علي فني فك وتقفيل','technician','create','device_checklist',5,NULL,'{\"device_id\":15,\"type\":\"after_repair\",\"technician_id\":5}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 08:45:56'),(93,5,NULL,'علي فني فك وتقفيل','technician','create','device_checklist',6,NULL,'{\"device_id\":15,\"type\":\"after_repair\",\"technician_id\":5}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 08:46:48'),(94,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 08:58:19'),(95,7,2,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 08:58:23'),(96,7,2,'محمود يونس','admin','create','devices',16,NULL,'{\"device_code\":\"DEV-2026-0913\",\"customer_id\":1,\"brand\":\"اوبو\",\"model\":\"يس\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 09:15:07'),(97,7,2,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 09:15:16'),(98,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 09:15:19'),(99,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 09:15:42'),(100,7,2,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 09:15:45'),(101,7,2,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 13:25:58'),(102,7,2,'محمود يونس','admin','update','users',2,'{\"action\":\"update_permissions\"}','{\"permissions\":[\"create_expenses\",\"create_invoices\",\"delete_invoices\",\"edit_invoices\",\"manage_wallets\",\"view_expenses\",\"view_financial\",\"view_invoices\",\"view_reports\",\"view_wallets\"]}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 13:42:43'),(103,7,2,'محمود يونس','admin','create','devices',17,NULL,'{\"device_code\":\"DEV-2026-1176\",\"customer_id\":1,\"brand\":\"ايفون\",\"model\":\"11برو ماكس\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 13:52:12'),(104,7,2,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 13:52:42'),(105,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 13:52:48'),(106,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 13:57:40'),(107,1,NULL,'مدير النظام','admin','login','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 13:57:44'),(108,1,NULL,'مدير النظام','admin','create','devices',18,NULL,'{\"device_code\":\"DEV-2026-9632\",\"customer_id\":1,\"brand\":\"هواوي\",\"model\":\"14\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 13:59:22'),(109,1,NULL,'مدير النظام','admin','logout','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 13:59:29'),(110,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 13:59:32'),(111,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 14:07:13'),(112,7,2,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 14:07:21'),(113,7,2,'محمود يونس','admin','create','devices',19,NULL,'{\"device_code\":\"DEV-2026-7747\",\"customer_id\":1,\"brand\":\"oppo\",\"model\":\"a52\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 14:08:31'),(114,7,2,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 14:08:35'),(115,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 14:08:41'),(116,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 14:12:20'),(117,7,2,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 14:12:24'),(118,7,2,'محمود يونس','admin','create','devices',20,NULL,'{\"device_code\":\"DEV-2026-7919\",\"customer_id\":1,\"brand\":\"ايفون\",\"model\":\"1+\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 14:13:03'),(119,7,2,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 14:13:09'),(120,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 14:13:12'),(121,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 16:11:07'),(122,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 16:55:19'),(123,1,NULL,'مدير النظام','admin','login','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 16:55:24'),(124,1,NULL,'مدير النظام','admin','logout','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 16:58:30'),(125,1,NULL,'مدير النظام','admin','login','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 16:58:33'),(126,1,NULL,'مدير النظام','admin','logout','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 17:05:32'),(127,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 17:05:36'),(128,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 17:06:35'),(129,7,2,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 17:06:38'),(130,7,2,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 17:07:32'),(131,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 17:07:37'),(132,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 17:12:17'),(133,7,2,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 17:12:22'),(134,7,2,'محمود يونس','admin','shift_start','shifts',3,NULL,'{\"user\":\"محمود يونس\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 17:12:53'),(135,7,3,'محمود يونس','admin','create','attendance',1,NULL,'{\"user_id\":7,\"action\":\"check_in\",\"status\":\"late\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 17:16:48'),(136,7,3,'محمود يونس','admin','update','settings',0,'{\"action\":\"update_work_settings\"}','{\"work_start_time\":\"09:00\",\"work_end_time\":\"18:00\",\"work_hours_per_day\":\"8\",\"late_grace_minutes\":\"15\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 17:51:40');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_logs_archive`
--

DROP TABLE IF EXISTS `audit_logs_archive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audit_logs_archive` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `shift_id` int(11) unsigned DEFAULT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_role` varchar(20) NOT NULL,
  `action` varchar(50) NOT NULL,
  `table_name` varchar(50) DEFAULT NULL,
  `record_id` int(11) unsigned DEFAULT NULL,
  `old_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_data`)),
  `new_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_data`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `archived_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `shift_audit_index` (`shift_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs_archive`
--

LOCK TABLES `audit_logs_archive` WRITE;
/*!40000 ALTER TABLE `audit_logs_archive` DISABLE KEYS */;
INSERT INTO `audit_logs_archive` VALUES (1,1,1,'مدير النظام','admin','create','devices',1,NULL,'{\"device_code\":\"DEV-2026-2101\",\"brand\":\"\\u0627\\u0648\\u0628\\u0648\",\"model\":\"\\u064a\\u0633\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 02:39:55','2026-06-20 03:05:06'),(2,1,1,'مدير النظام','admin','create','inventory',1,NULL,'{\"name\":\"\\u0634\\u0627\\u0634\\u0629 \\u0633\\u0627\\u0645\\u0633\\u0648\\u0646\\u062c\",\"category\":\"\\u0634\\u0627\\u0634\\u0629\",\"quantity\":7,\"purchase_price\":200,\"selling_price\":300}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 02:46:02','2026-06-20 03:05:06'),(3,1,1,'مدير النظام','admin','create','sales',1,NULL,'{\"invoice_number\":\"INV-2026-45633\",\"customer_id\":1,\"total_amount\":300,\"payment_method\":\"cash\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 02:47:22','2026-06-20 03:05:06');
/*!40000 ALTER TABLE `audit_logs_archive` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `national_id` varchar(20) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `phone_index` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'المهندس عبدالرحمن','01029169762',NULL,NULL,NULL,NULL,'2026-06-20 02:39:55','2026-06-22 14:13:03',NULL),(6,'كريم','010020',NULL,NULL,NULL,NULL,'2026-06-20 04:24:38','2026-06-20 04:24:38',NULL),(7,'المهندس عبدالرحمن','01029169768',NULL,NULL,NULL,NULL,'2026-06-20 04:37:58','2026-06-20 04:37:58',NULL),(8,'A To Z Educational Center (Zahraa El Maadi)','hg',NULL,NULL,NULL,NULL,'2026-06-20 05:21:55','2026-06-20 05:27:49',NULL),(9,'المهندس عبدالرحمن باشا','سماعة',NULL,NULL,NULL,NULL,'2026-06-20 05:48:12','2026-06-20 05:48:12',NULL);
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device_checklist`
--

DROP TABLE IF EXISTS `device_checklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device_checklist` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `device_id` int(11) unsigned NOT NULL,
  `wifi_working` tinyint(1) DEFAULT NULL,
  `bluetooth_working` tinyint(1) DEFAULT NULL,
  `network_working` tinyint(1) DEFAULT NULL,
  `camera_working` tinyint(1) DEFAULT NULL,
  `fingerprint_working` tinyint(1) DEFAULT NULL,
  `audio_working` tinyint(1) DEFAULT NULL,
  `charging_working` tinyint(1) DEFAULT NULL,
  `screen_condition` enum('good','scratched','cracked','broken','not_checked') DEFAULT 'not_checked',
  `check_notes` text DEFAULT NULL,
  `checked_by` int(11) unsigned NOT NULL,
  `checked_at` datetime NOT NULL DEFAULT current_timestamp(),
  `is_after_repair` tinyint(1) DEFAULT 0,
  `repair_notes` text DEFAULT NULL,
  `check_type` enum('before_repair','after_repair','final_delivery') DEFAULT 'before_repair',
  PRIMARY KEY (`id`),
  KEY `checked_by` (`checked_by`),
  KEY `device_checklist_ibfk_1` (`device_id`),
  CONSTRAINT `device_checklist_ibfk_1` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `device_checklist_ibfk_2` FOREIGN KEY (`checked_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_checklist`
--

LOCK TABLES `device_checklist` WRITE;
/*!40000 ALTER TABLE `device_checklist` DISABLE KEYS */;
INSERT INTO `device_checklist` VALUES (1,15,1,1,1,1,1,1,1,'good','',5,'2026-06-22 11:31:25',1,'شاشة','before_repair'),(5,15,1,1,1,1,1,1,1,'good','',5,'2026-06-22 11:45:56',1,'ف','before_repair'),(6,15,1,1,1,1,1,1,1,'good','',5,'2026-06-22 11:46:48',1,'ث','before_repair'),(7,15,1,1,1,1,1,1,1,'good','رب',7,'2026-06-22 12:01:00',0,'محمد','final_delivery');
/*!40000 ALTER TABLE `device_checklist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device_photos`
--

DROP TABLE IF EXISTS `device_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device_photos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `device_id` int(11) unsigned NOT NULL,
  `photo_path` varchar(255) NOT NULL,
  `photo_type` enum('front','back','side','screen','damage','other') NOT NULL DEFAULT 'other',
  `uploaded_by` int(11) unsigned NOT NULL,
  `uploaded_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `device_id` (`device_id`),
  KEY `uploaded_by` (`uploaded_by`),
  CONSTRAINT `device_photos_ibfk_1` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `device_photos_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_photos`
--

LOCK TABLES `device_photos` WRITE;
/*!40000 ALTER TABLE `device_photos` DISABLE KEYS */;
/*!40000 ALTER TABLE `device_photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device_qr_codes`
--

DROP TABLE IF EXISTS `device_qr_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device_qr_codes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `device_id` int(11) unsigned NOT NULL,
  `qr_code` varchar(255) NOT NULL,
  `qr_image_path` varchar(255) NOT NULL,
  `tracking_url` varchar(255) NOT NULL,
  `generated_by` int(11) unsigned NOT NULL,
  `generated_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `device_qr_unique` (`device_id`),
  KEY `generated_by` (`generated_by`),
  CONSTRAINT `device_qr_codes_ibfk_1` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `device_qr_codes_ibfk_2` FOREIGN KEY (`generated_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_qr_codes`
--

LOCK TABLES `device_qr_codes` WRITE;
/*!40000 ALTER TABLE `device_qr_codes` DISABLE KEYS */;
INSERT INTO `device_qr_codes` VALUES (1,1,'DEV-2026-2101','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-2101&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-2101',1,'2026-06-20 05:39:55'),(2,2,'DEV-2026-1836','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-1836&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-1836',1,'2026-06-20 07:24:38'),(3,3,'DEV-2026-0725','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-0725&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-0725',1,'2026-06-20 07:37:58'),(4,4,'DEV-2026-0782','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-0782&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-0782',1,'2026-06-20 08:21:55'),(7,7,'DEV-2026-1034','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-1034&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-1034',1,'2026-06-21 19:24:17'),(8,12,'DEV-2026-4270','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-4270&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-4270',7,'2026-06-21 20:03:17'),(9,13,'DEV-2026-0551','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-0551&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-0551',2,'2026-06-22 08:06:40'),(10,14,'DEV-2026-6403','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-6403&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-6403',7,'2026-06-22 08:15:55'),(11,15,'DEV-2026-7612','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-7612&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-7612',7,'2026-06-22 11:18:02'),(12,16,'DEV-2026-0913','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-0913&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-0913',7,'2026-06-22 12:15:07'),(13,17,'DEV-2026-1176','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-1176&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-1176',7,'2026-06-22 16:52:12'),(14,18,'DEV-2026-9632','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-9632&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-9632',1,'2026-06-22 16:59:22'),(15,19,'DEV-2026-7747','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-7747&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-7747',7,'2026-06-22 17:08:31'),(16,20,'DEV-2026-7919','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-7919&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-7919',7,'2026-06-22 17:13:03');
/*!40000 ALTER TABLE `device_qr_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device_statuses`
--

DROP TABLE IF EXISTS `device_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device_statuses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `is_final` tinyint(1) NOT NULL DEFAULT 0,
  `order_index` tinyint(3) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_statuses`
--

LOCK TABLES `device_statuses` WRITE;
/*!40000 ALTER TABLE `device_statuses` DISABLE KEYS */;
INSERT INTO `device_statuses` VALUES (1,'تم الاستلام','received',0,1,'2026-06-20 01:22:09'),(2,'جاري الفحص','inspection',0,2,'2026-06-20 01:22:09'),(3,'في انتظار قطع غيار','waiting_parts',0,3,'2026-06-20 01:22:09'),(4,'جاري الإصلاح','repairing',0,4,'2026-06-20 01:22:09'),(5,'تم الإصلاح','repaired',0,5,'2026-06-20 01:22:09'),(6,'جاهز للاستلام','ready_for_pickup',1,6,'2026-06-20 01:22:09'),(7,'تم الاستلام من قبل العميل','picked_up',1,7,'2026-06-20 01:22:09'),(8,'مرفوض / غير قابل للإصلاح','rejected',1,8,'2026-06-20 01:22:09'),(17,'تم التسليم','delivered',1,9,'2026-06-20 05:34:45');
/*!40000 ALTER TABLE `device_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `devices`
--

DROP TABLE IF EXISTS `devices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `devices` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `device_code` varchar(50) NOT NULL,
  `customer_id` int(11) unsigned NOT NULL,
  `received_by` int(11) unsigned NOT NULL,
  `brand` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `color` varchar(30) DEFAULT NULL,
  `storage_capacity` varchar(20) DEFAULT NULL,
  `imei_1` varchar(20) DEFAULT NULL,
  `imei_2` varchar(20) DEFAULT NULL,
  `serial_number` varchar(50) DEFAULT NULL,
  `condition_type` enum('new','used') NOT NULL DEFAULT 'used',
  `is_under_warranty` tinyint(1) NOT NULL DEFAULT 0,
  `reported_issue` text NOT NULL,
  `diagnosed_issue` text DEFAULT NULL,
  `estimated_cost` decimal(10,2) DEFAULT NULL,
  `final_cost` decimal(10,2) DEFAULT NULL,
  `advance_payment` decimal(10,2) DEFAULT 0.00,
  `current_status_id` int(11) unsigned NOT NULL,
  `assigned_technician_id` int(11) unsigned DEFAULT NULL,
  `is_paid` tinyint(1) NOT NULL DEFAULT 0,
  `pickup_date` datetime DEFAULT NULL,
  `received_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `sale_id` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `waiting_for_part` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `device_code_unique` (`device_code`),
  UNIQUE KEY `imei_1_unique` (`imei_1`),
  UNIQUE KEY `imei_2_unique` (`imei_2`),
  UNIQUE KEY `serial_number_unique` (`serial_number`),
  KEY `customer_id` (`customer_id`),
  KEY `received_by` (`received_by`),
  KEY `current_status_id` (`current_status_id`),
  KEY `assigned_technician_id` (`assigned_technician_id`),
  CONSTRAINT `devices_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  CONSTRAINT `devices_ibfk_2` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`),
  CONSTRAINT `devices_ibfk_3` FOREIGN KEY (`current_status_id`) REFERENCES `device_statuses` (`id`),
  CONSTRAINT `devices_ibfk_4` FOREIGN KEY (`assigned_technician_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `devices`
--

LOCK TABLES `devices` WRITE;
/*!40000 ALTER TABLE `devices` DISABLE KEYS */;
INSERT INTO `devices` VALUES (1,'DEV-2026-2101',1,1,'اوبو','يس','ابيض','128GB',NULL,NULL,NULL,'used',0,'ثب','',NULL,NULL,0.00,4,3,0,NULL,'2026-06-20 05:39:55','2026-06-22 05:25:02','2026-06-20 02:39:55',NULL,NULL,NULL),(2,'DEV-2026-1836',6,1,'اوبو','d','baby blue','128GB',NULL,NULL,NULL,'used',0,'hcg',NULL,NULL,NULL,0.00,1,4,0,NULL,'2026-06-20 07:24:38','2026-06-20 05:19:52','2026-06-20 04:24:38',NULL,NULL,NULL),(3,'DEV-2026-0725',7,1,'اوبو','1+','baby blue','128ج',NULL,NULL,NULL,'used',0,'ؤاا','dgbd',NULL,NULL,0.00,1,3,0,NULL,'2026-06-20 07:37:58','2026-06-22 04:37:46','2026-06-20 04:37:58',10,NULL,NULL),(4,'DEV-2026-0782',8,1,'اوبو','يس','ابيض','128GB',NULL,NULL,NULL,'used',0,'ي','',NULL,NULL,0.00,4,3,0,NULL,'2026-06-20 08:21:55','2026-06-22 05:08:31','2026-06-20 05:21:55',7,NULL,NULL),(7,'DEV-2026-1034',1,1,'اوبو','1+','ابيض','128ج',NULL,NULL,NULL,'used',0,'سش',NULL,NULL,NULL,0.00,1,3,0,NULL,'2026-06-21 19:24:17','2026-06-21 16:24:29','2026-06-21 16:24:17',NULL,NULL,NULL),(8,'DEV-2026-3762',1,7,'اوبو','يس','baby blue','128GB',NULL,NULL,NULL,'used',0,'ى',NULL,NULL,NULL,0.00,4,NULL,0,NULL,'2026-06-21 19:54:57','2026-06-22 05:54:07','2026-06-21 16:54:57',NULL,NULL,NULL),(9,'DEV-2026-4339',1,7,'اوبو','يس','baby blue','128GB',NULL,NULL,NULL,'used',0,'ى',NULL,NULL,NULL,0.00,4,NULL,0,NULL,'2026-06-21 19:57:26','2026-06-22 05:54:07','2026-06-21 16:57:26',NULL,NULL,NULL),(10,'DEV-2026-3462',1,7,'اوبو','يس','baby blue','128GB',NULL,NULL,NULL,'used',0,'ى',NULL,NULL,NULL,0.00,4,NULL,0,NULL,'2026-06-21 19:58:24','2026-06-22 05:54:07','2026-06-21 16:58:24',NULL,NULL,NULL),(11,'DEV-2026-9371',1,7,'اوبو','يس','baby blue','128GB',NULL,NULL,NULL,'used',0,'ى',NULL,NULL,NULL,0.00,4,NULL,0,NULL,'2026-06-21 19:59:08','2026-06-22 05:54:07','2026-06-21 16:59:08',NULL,NULL,NULL),(12,'DEV-2026-4270',1,7,'اوبو','يس','baby blue','128GB',NULL,NULL,NULL,'used',0,'ى',NULL,NULL,NULL,0.00,4,3,0,NULL,'2026-06-21 20:03:17','2026-06-22 06:30:18','2026-06-21 17:03:17',13,NULL,NULL),(13,'DEV-2026-0551',1,2,'شاومي','14','ابيض','128ج',NULL,NULL,NULL,'used',0,'ق','كسر',NULL,NULL,0.00,1,5,0,NULL,'2026-06-22 08:06:40','2026-06-22 05:09:52','2026-06-22 05:06:40',11,NULL,''),(14,'DEV-2026-6403',1,7,'شاومي','1+','ابيض','128ج',NULL,NULL,NULL,'used',0,'قي',NULL,NULL,NULL,0.00,2,5,0,NULL,'2026-06-22 08:15:55','2026-06-22 05:15:55','2026-06-22 05:15:55',NULL,NULL,'شاشة شاومي'),(15,'DEV-2026-7612',1,7,'شاومي','يس','ابيض','128ج',NULL,NULL,NULL,'used',0,'سبي','',NULL,NULL,0.00,17,5,1,'2026-06-22 12:01:00','2026-06-22 11:18:02','2026-06-22 09:01:00','2026-06-22 08:18:02',33,NULL,''),(16,'DEV-2026-0913',1,7,'اوبو','يس','ابيض','128ج',NULL,NULL,NULL,'used',0,'فاي','',NULL,NULL,0.00,5,5,0,NULL,'2026-06-22 12:15:07','2026-06-22 09:15:39','2026-06-22 09:15:07',34,NULL,''),(17,'DEV-2026-1176',1,7,'ايفون','11برو ماكس','اسود','256ج',NULL,NULL,NULL,'used',0,'ث',NULL,NULL,NULL,0.00,2,5,0,NULL,'2026-06-22 16:52:12','2026-06-22 13:52:12','2026-06-22 13:52:12',NULL,NULL,''),(18,'DEV-2026-9632',1,1,'هواوي','14','اسود','256ج',NULL,NULL,NULL,'used',0,'ث',NULL,NULL,NULL,0.00,2,5,0,NULL,'2026-06-22 16:59:22','2026-06-22 13:59:22','2026-06-22 13:59:22',NULL,NULL,''),(19,'DEV-2026-7747',1,7,'oppo','a52','black','256gb',NULL,NULL,NULL,'used',0,'de',NULL,NULL,NULL,0.00,2,5,0,NULL,'2026-06-22 17:08:31','2026-06-22 14:08:31','2026-06-22 14:08:31',NULL,NULL,''),(20,'DEV-2026-7919',1,7,'ايفون','1+','ابيض','128ج',NULL,NULL,NULL,'used',0,'غل',NULL,NULL,NULL,0.00,2,5,0,NULL,'2026-06-22 17:13:03','2026-06-22 14:13:03','2026-06-22 14:13:03',NULL,NULL,'');
/*!40000 ALTER TABLE `devices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expenses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `expense_category` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `expense_date` date NOT NULL,
  `payment_method` enum('cash','wallet','bank') NOT NULL DEFAULT 'cash',
  `wallet_id` int(11) unsigned DEFAULT NULL,
  `receipt_image` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(11) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `wallet_expense_index` (`wallet_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
INSERT INTO `expenses` VALUES (1,'إيجار','f',5.00,'2026-06-20','cash',2,NULL,NULL,1,'2026-06-20 02:23:20','2026-06-20 02:23:20'),(2,'إيجار','f',58.00,'2026-06-20','cash',8,NULL,NULL,1,'2026-06-20 02:47:59','2026-06-20 02:47:59');
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `installment_payments`
--

DROP TABLE IF EXISTS `installment_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `installment_payments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `installment_id` int(11) unsigned NOT NULL,
  `payment_number` tinyint(3) NOT NULL,
  `due_date` date NOT NULL,
  `payment_date` date DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `paid_amount` decimal(10,2) DEFAULT 0.00,
  `is_paid` tinyint(1) NOT NULL DEFAULT 0,
  `penalty` decimal(10,2) DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `installment_payment_index` (`installment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `installment_payments`
--

LOCK TABLES `installment_payments` WRITE;
/*!40000 ALTER TABLE `installment_payments` DISABLE KEYS */;
INSERT INTO `installment_payments` VALUES (1,1,1,'2026-06-20','2026-06-20',2475.00,2475.00,1,0.00,'','2026-06-20 02:46:33','2026-06-20 04:15:54'),(2,1,2,'2026-07-20','2026-06-21',2475.00,2475.00,1,0.00,'','2026-06-20 02:46:33','2026-06-21 13:07:34'),(3,1,3,'2026-08-20','2026-06-21',2475.00,2475.00,1,0.00,'','2026-06-20 02:46:33','2026-06-21 13:07:47'),(4,1,4,'2026-09-20','2026-06-21',2475.00,2475.00,1,0.00,'','2026-06-20 02:46:33','2026-06-21 13:12:35'),(5,1,5,'2026-10-20','2026-06-21',2475.00,2475.00,1,0.00,'','2026-06-20 02:46:33','2026-06-21 13:13:08'),(6,1,6,'2026-11-20','2026-06-21',2475.00,2475.00,1,0.00,'','2026-06-20 02:46:33','2026-06-21 13:24:06'),(7,1,7,'2026-12-20','2026-06-21',2475.00,2475.00,1,0.00,'','2026-06-20 02:46:33','2026-06-21 13:24:06'),(8,1,8,'2027-01-20','2026-06-21',2475.00,2475.00,1,0.00,'','2026-06-20 02:46:33','2026-06-21 13:24:06'),(9,1,9,'2027-02-20','2026-06-21',2475.00,2475.00,1,0.00,'','2026-06-20 02:46:33','2026-06-21 13:24:06'),(10,1,10,'2027-03-20','2026-06-21',2475.00,2475.00,1,0.00,'','2026-06-20 02:46:33','2026-06-21 13:24:06'),(11,1,11,'2027-04-20','2026-06-21',2475.00,2475.00,1,0.00,'','2026-06-20 02:46:33','2026-06-21 13:24:06'),(12,1,12,'2027-05-20','2026-06-21',2475.00,2475.00,1,0.00,'','2026-06-20 02:46:33','2026-06-21 13:24:06');
/*!40000 ALTER TABLE `installment_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `installments`
--

DROP TABLE IF EXISTS `installments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `installments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` int(11) unsigned DEFAULT NULL,
  `customer_id` int(11) unsigned NOT NULL,
  `device_name` varchar(200) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `down_payment` decimal(10,2) NOT NULL DEFAULT 0.00,
  `remaining_amount` decimal(10,2) NOT NULL,
  `number_of_installments` tinyint(3) NOT NULL,
  `installment_value` decimal(10,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('active','completed','defaulted','cancelled') NOT NULL DEFAULT 'active',
  `notes` text DEFAULT NULL,
  `created_by` int(11) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_installment_index` (`customer_id`),
  KEY `sale_installment_index` (`sale_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `installments`
--

LOCK TABLES `installments` WRITE;
/*!40000 ALTER TABLE `installments` DISABLE KEYS */;
INSERT INTO `installments` VALUES (1,NULL,1,'2',30000.00,300.00,0.00,12,2475.00,'2026-06-20','2027-06-20','completed','',1,'2026-06-20 02:46:33','2026-06-21 13:24:06',NULL);
/*!40000 ALTER TABLE `installments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `internal_messages`
--

DROP TABLE IF EXISTS `internal_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `internal_messages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) unsigned NOT NULL,
  `receiver_id` int(11) unsigned DEFAULT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `sender_message_index` (`sender_id`),
  KEY `receiver_message_index` (`receiver_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `internal_messages`
--

LOCK TABLES `internal_messages` WRITE;
/*!40000 ALTER TABLE `internal_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `internal_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sku` varchar(50) NOT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `category` varchar(50) NOT NULL,
  `brand` varchar(50) DEFAULT NULL,
  `model` varchar(50) DEFAULT NULL,
  `supplier_id` int(11) unsigned DEFAULT NULL,
  `supplier_name` varchar(100) DEFAULT NULL,
  `purchase_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `selling_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `min_selling_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `alert_quantity` int(11) NOT NULL DEFAULT 5,
  `current_quantity` int(11) NOT NULL DEFAULT 0,
  `reserved_quantity` int(11) NOT NULL DEFAULT 0,
  `unit` varchar(20) DEFAULT 'قطعة',
  `location` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sku_unique` (`sku`),
  UNIQUE KEY `barcode_unique` (`barcode`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory`
--

LOCK TABLES `inventory` WRITE;
/*!40000 ALTER TABLE `inventory` DISABLE KEYS */;
INSERT INTO `inventory` VALUES (1,'SKU-2026-7005',NULL,'شاشة سامسونج','شاشة',NULL,NULL,1,'احمد جوهر',200.00,300.00,0.00,3,4,0,'قطعة','رف2',1,'2026-06-20 02:46:02','2026-06-21 15:40:07',NULL),(7,'SKU-2026-3162','85','شاشة شاومي','شاشة',NULL,NULL,1,'احمد جوهر',500.00,700.00,0.00,2,13,0,'قطعة','رف2',1,'2026-06-22 05:19:09','2026-06-22 09:15:39',NULL),(12,'SKU-2026-1235','511','شاشة اوبو','شاشة',NULL,NULL,1,'احمد جوهر',500.00,700.00,0.00,2,8,0,'قطعة','1',1,'2026-06-22 05:54:07','2026-06-22 06:30:18',NULL);
/*!40000 ALTER TABLE `inventory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_movements`
--

DROP TABLE IF EXISTS `inventory_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_movements` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `inventory_id` int(11) unsigned NOT NULL,
  `movement_type` enum('purchase','sale','return','adjustment','repair_use','transfer') NOT NULL,
  `quantity` int(11) NOT NULL,
  `reference_id` int(11) unsigned DEFAULT NULL,
  `reference_type` varchar(50) DEFAULT NULL,
  `price_at_movement` decimal(10,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_by` int(11) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `inventory_movements_ibfk_1` (`inventory_id`),
  CONSTRAINT `inventory_movements_ibfk_1` FOREIGN KEY (`inventory_id`) REFERENCES `inventory` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_movements_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_movements`
--

LOCK TABLES `inventory_movements` WRITE;
/*!40000 ALTER TABLE `inventory_movements` DISABLE KEYS */;
INSERT INTO `inventory_movements` VALUES (4,1,'repair_use',1,NULL,NULL,0.00,'استخدمت في صيانة جهاز #3 - شاشة مكسورة',5,'2026-06-20 05:19:14'),(7,1,'repair_use',1,NULL,NULL,0.00,'استخدمت في صيانة جهاز #4 - بلا',5,'2026-06-20 05:27:14'),(8,1,'repair_use',1,NULL,NULL,0.00,'استخدمت في صيانة جهاز #5 - تا',5,'2026-06-20 05:28:13'),(9,1,'repair_use',1,NULL,NULL,0.00,'استخدمت في صيانة جهاز #6 - gh',5,'2026-06-20 05:49:10'),(10,1,'repair_use',1,NULL,NULL,0.00,'استخدمت في صيانة جهاز #3 - dgbd',3,'2026-06-21 12:31:54'),(11,1,'adjustment',3,NULL,NULL,0.00,'جرد: fe',1,'2026-06-21 15:40:07'),(32,7,'repair_use',1,NULL,NULL,0.00,'استخدمت في صيانة جهاز #15 - ',5,'2026-06-22 08:57:53'),(33,7,'repair_use',1,NULL,NULL,0.00,'استخدمت في صيانة جهاز #16 - ',5,'2026-06-22 09:15:39');
/*!40000 ALTER TABLE `inventory_movements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT NULL,
  `type` varchar(50) NOT NULL DEFAULT 'system',
  `title` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_notification_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (1,1,'inventory','✅ قطعة غيار متوفرة الآن!','قطعة \"شاشة اوبو\" أصبحت متوفرة للجهاز DEV-2026-3762',1,'/devices/8','2026-06-22 05:07:50'),(2,1,'inventory','✅ قطعة غيار متوفرة الآن!','قطعة \"شاشة اوبو\" أصبحت متوفرة للجهاز DEV-2026-4339',1,'/devices/9','2026-06-22 05:07:50'),(3,1,'inventory','✅ قطعة غيار متوفرة الآن!','قطعة \"شاشة اوبو\" أصبحت متوفرة للجهاز DEV-2026-3462',1,'/devices/10','2026-06-22 05:07:50'),(4,1,'inventory','✅ قطعة غيار متوفرة الآن!','قطعة \"شاشة اوبو\" أصبحت متوفرة للجهاز DEV-2026-9371',1,'/devices/11','2026-06-22 05:07:50'),(5,1,'inventory','✅ قطعة غيار متوفرة الآن!','قطعة \"شاشة اوبو\" أصبحت متوفرة للجهاز DEV-2026-4270',1,'/devices/12','2026-06-22 05:07:50'),(6,1,'inventory','قطعة غيار متوفرة - تم تحديث الجهاز','قطعة \"شاشة اوبو\" أصبحت متوفرة. تم تغيير حالة الجهاز DEV-2026-3762 إلى جاري الإصلاح',1,'/devices/8','2026-06-22 05:54:07'),(7,1,'inventory','قطعة غيار متوفرة - تم تحديث الجهاز','قطعة \"شاشة اوبو\" أصبحت متوفرة. تم تغيير حالة الجهاز DEV-2026-4339 إلى جاري الإصلاح',1,'/devices/9','2026-06-22 05:54:07'),(8,1,'inventory','قطعة غيار متوفرة - تم تحديث الجهاز','قطعة \"شاشة اوبو\" أصبحت متوفرة. تم تغيير حالة الجهاز DEV-2026-3462 إلى جاري الإصلاح',1,'/devices/10','2026-06-22 05:54:07'),(9,1,'inventory','قطعة غيار متوفرة - تم تحديث الجهاز','قطعة \"شاشة اوبو\" أصبحت متوفرة. تم تغيير حالة الجهاز DEV-2026-9371 إلى جاري الإصلاح',1,'/devices/11','2026-06-22 05:54:07'),(10,1,'inventory','قطعة غيار متوفرة - تم تحديث الجهاز','قطعة \"شاشة اوبو\" أصبحت متوفرة. تم تغيير حالة الجهاز DEV-2026-4270 إلى جاري الإصلاح',1,'/devices/12','2026-06-22 05:54:07'),(11,3,'repair','قطعة غيار متوفرة لجهازك','قطعة \"شاشة اوبو\" أصبحت متوفرة. تم تغيير حالة الجهاز DEV-2026-4270 إلى جاري الإصلاح',0,'/devices/12','2026-06-22 05:54:07'),(12,1,'test','🔧 اختبار','هذه رسالة اختبار من SQL',0,NULL,'2026-06-22 14:19:33'),(13,3,'system','🔧 اختبار للفني','هذه رسالة اختبار للفني',0,'/devices/1','2026-06-22 14:23:03'),(14,3,'system','🧪 اختبار يدوي','هذه رسالة اختبار للفني',0,'/devices/1','2026-06-22 14:27:44'),(15,3,'system','🧪 اختبار من SQL','هذه رسالة اختبار للفني',0,'/devices/1','2026-06-22 16:17:21'),(16,5,'system','🧪 اختبار للفني 5','هذه رسالة اختبار للمستخدم رقم 5',0,'/devices/1','2026-06-22 16:32:46');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role` varchar(20) NOT NULL,
  `permission` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_permission` (`role`,`permission`)
) ENGINE=InnoDB AUTO_INCREMENT=128 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'admin','view_all','2026-06-21 12:42:50'),(2,'admin','create_all','2026-06-21 12:42:50'),(3,'admin','edit_all','2026-06-21 12:42:50'),(4,'admin','delete_all','2026-06-21 12:42:50'),(5,'admin','manage_users','2026-06-21 12:42:50'),(6,'admin','manage_settings','2026-06-21 12:42:50'),(7,'admin','view_financial','2026-06-21 12:42:50'),(8,'admin','create_invoices','2026-06-21 12:42:50'),(9,'admin','edit_invoices','2026-06-21 12:42:50'),(10,'admin','manage_wallets','2026-06-21 12:42:50'),(11,'admin','create_expenses','2026-06-21 12:42:50'),(12,'admin','view_audit','2026-06-21 12:42:50'),(13,'admin','manage_backup','2026-06-21 12:42:50'),(20,'technician','view_my_devices','2026-06-21 12:42:50'),(21,'technician','update_repair_status','2026-06-21 12:42:50'),(22,'technician','use_parts','2026-06-21 12:42:50'),(23,'reception','create_devices','2026-06-21 12:42:50'),(24,'reception','view_devices','2026-06-21 12:42:50'),(25,'manager','view_all','2026-06-21 12:42:50'),(26,'manager','create_all','2026-06-21 12:42:50'),(27,'manager','edit_all','2026-06-21 12:42:50'),(28,'manager','view_financial','2026-06-21 12:42:50'),(29,'manager','view_audit','2026-06-21 12:42:50'),(30,'admin','view_reports','2026-06-21 13:29:25'),(31,'admin','export_reports','2026-06-21 13:29:25'),(33,'manager','view_reports','2026-06-21 13:29:25'),(40,'admin','manage_inventory','2026-06-21 15:29:09'),(41,'admin','manage_inventory_count','2026-06-21 15:29:09'),(42,'manager','manage_inventory','2026-06-21 15:29:09'),(43,'manager','manage_inventory_count','2026-06-21 15:29:09'),(44,'admin','view_users','2026-06-21 16:15:51'),(45,'admin','create_users','2026-06-21 16:15:51'),(46,'admin','edit_users','2026-06-21 16:15:51'),(47,'admin','delete_users','2026-06-21 16:15:51'),(48,'admin','view_attendance','2026-06-22 04:50:50'),(49,'admin','edit_attendance','2026-06-22 04:50:50'),(50,'admin','export_attendance','2026-06-22 04:50:50'),(51,'manager','view_attendance','2026-06-22 04:50:50'),(52,'admin','manage_roles','2026-06-22 13:35:13'),(56,'technician','view_parts','2026-06-22 13:35:13'),(57,'reception','view_customers','2026-06-22 13:35:13'),(58,'reception','create_customers','2026-06-22 13:35:13'),(59,'reception','view_my_devices','2026-06-22 13:35:13'),(60,'manager','view_invoices','2026-06-22 13:35:13'),(61,'manager','edit_invoices','2026-06-22 13:35:13'),(62,'manager','edit_attendance','2026-06-22 13:35:13'),(83,'accountant','create_expenses','2026-06-22 13:42:43'),(84,'accountant','create_invoices','2026-06-22 13:42:43'),(85,'accountant','delete_invoices','2026-06-22 13:42:43'),(86,'accountant','edit_invoices','2026-06-22 13:42:43'),(87,'accountant','manage_wallets','2026-06-22 13:42:43'),(88,'accountant','view_expenses','2026-06-22 13:42:43'),(89,'accountant','view_financial','2026-06-22 13:42:43'),(90,'accountant','view_invoices','2026-06-22 13:42:43'),(91,'accountant','view_reports','2026-06-22 13:42:43'),(92,'accountant','view_wallets','2026-06-22 13:42:43'),(94,'technician','view_technician_dashboard','2026-06-22 17:56:40'),(95,'sales','view_invoices','2026-06-22 17:56:40'),(96,'sales','create_invoices','2026-06-22 17:56:40'),(97,'sales','view_customers','2026-06-22 17:56:40'),(98,'sales','create_customers','2026-06-22 17:56:40');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_invoices`
--

DROP TABLE IF EXISTS `purchase_invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `purchase_invoices` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(50) NOT NULL,
  `invoice_date` date NOT NULL,
  `supplier_id` int(11) unsigned DEFAULT NULL,
  `item_id` int(11) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `received_quantity` int(11) NOT NULL,
  `discrepancy` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_invoices`
--

LOCK TABLES `purchase_invoices` WRITE;
/*!40000 ALTER TABLE `purchase_invoices` DISABLE KEYS */;
INSERT INTO `purchase_invoices` VALUES (1,'854ه','2026-06-20',1,1,0,7,0,1,'2026-06-20 02:46:02'),(7,'864','2026-06-22',1,7,10,10,0,7,'2026-06-22 05:19:09'),(12,'481','2026-06-22',1,12,10,10,0,7,'2026-06-22 05:54:07');
/*!40000 ALTER TABLE `purchase_invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `repair_jobs`
--

DROP TABLE IF EXISTS `repair_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `repair_jobs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `device_id` int(11) unsigned NOT NULL,
  `technician_id` int(11) unsigned NOT NULL,
  `technician_type_id` int(11) unsigned NOT NULL,
  `job_description` text NOT NULL,
  `work_performed` text DEFAULT NULL,
  `parts_used` text DEFAULT NULL,
  `started_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0,
  `quality_check_passed` tinyint(1) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `device_id` (`device_id`),
  KEY `technician_id` (`technician_id`),
  KEY `technician_type_id` (`technician_type_id`),
  CONSTRAINT `repair_jobs_ibfk_1` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `repair_jobs_ibfk_2` FOREIGN KEY (`technician_id`) REFERENCES `users` (`id`),
  CONSTRAINT `repair_jobs_ibfk_3` FOREIGN KEY (`technician_type_id`) REFERENCES `technician_types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `repair_jobs`
--

LOCK TABLES `repair_jobs` WRITE;
/*!40000 ALTER TABLE `repair_jobs` DISABLE KEYS */;
INSERT INTO `repair_jobs` VALUES (1,1,5,1,'تم توزيع الجهاز يدوياً',NULL,'[]','2026-06-20 05:39:55','2026-06-20 08:19:20',1,NULL,NULL,'2026-06-20 02:39:55','2026-06-20 05:19:20'),(2,2,4,3,'تم توزيع الجهاز يدوياً',NULL,NULL,'2026-06-20 07:24:38','2026-06-20 07:27:25',1,NULL,NULL,'2026-06-20 04:24:38','2026-06-20 04:27:25'),(3,3,5,1,'تم توزيع الجهاز يدوياً',NULL,'[{\"id\":1,\"name\":\"\\u0634\\u0627\\u0634\\u0629 \\u0633\\u0627\\u0645\\u0633\\u0648\\u0646\\u062c\",\"price\":300}]','2026-06-20 07:37:58','2026-06-20 08:19:14',1,NULL,NULL,'2026-06-20 04:37:58','2026-06-20 05:19:14'),(4,4,5,1,'تم توزيع الجهاز يدوياً',NULL,'[{\"id\":1,\"name\":\"شاشة سامسونج\",\"price\":300}]','2026-06-20 08:21:55','2026-06-20 08:27:14',1,NULL,NULL,'2026-06-20 05:21:55','2026-06-20 05:27:14'),(5,3,3,2,'مهمة: general_repair - جهاز DEV-2026-0725',NULL,'[{\"id\":1,\"name\":\"شاشة سامسونج\",\"price\":300}]','2026-06-20 08:27:17','2026-06-21 15:31:54',1,NULL,NULL,'2026-06-20 05:27:17','2026-06-21 12:31:54'),(6,1,3,2,'مهمة: general_repair - جهاز DEV-2026-2101',NULL,NULL,'2026-06-20 08:27:20',NULL,0,NULL,NULL,'2026-06-20 05:27:20','2026-06-20 05:27:20'),(9,7,3,2,'تم توزيع الجهاز يدوياً',NULL,NULL,'2026-06-21 19:24:17',NULL,0,NULL,NULL,'2026-06-21 16:24:17','2026-06-21 16:24:17'),(10,12,3,2,'تم توزيع الجهاز يدوياً',NULL,NULL,'2026-06-21 20:03:17',NULL,0,NULL,NULL,'2026-06-21 17:03:17','2026-06-21 17:03:17'),(11,13,5,1,'تم توزيع الجهاز يدوياً',NULL,'[{\"id\":6,\"name\":\"شاشة اوبو\",\"price\":700}]','2026-06-22 08:06:40','2026-06-22 08:08:57',1,NULL,NULL,'2026-06-22 05:06:40','2026-06-22 05:08:57'),(12,4,3,2,'مهمة: general_repair - جهاز DEV-2026-0782',NULL,NULL,'2026-06-22 08:08:31',NULL,0,NULL,NULL,'2026-06-22 05:08:31','2026-06-22 05:08:31'),(13,14,5,1,'تم توزيع الجهاز يدوياً',NULL,NULL,'2026-06-22 08:15:55',NULL,0,NULL,NULL,'2026-06-22 05:15:55','2026-06-22 05:15:55'),(14,12,3,2,'إصلاح بعد توفير قطعة: شاشة اوبو',NULL,NULL,'2026-06-22 08:54:07',NULL,0,NULL,NULL,'2026-06-22 05:54:07','2026-06-22 05:54:07'),(15,15,5,1,'تم توزيع الجهاز يدوياً',NULL,'[{\"id\":7,\"name\":\"شاشة شاومي\",\"price\":700}]','2026-06-22 11:18:02','2026-06-22 11:57:53',1,NULL,NULL,'2026-06-22 08:18:02','2026-06-22 08:57:53'),(16,16,5,1,'تم توزيع الجهاز يدوياً',NULL,'[{\"id\":7,\"name\":\"شاشة شاومي\",\"price\":700}]','2026-06-22 12:15:07','2026-06-22 12:15:39',1,NULL,NULL,'2026-06-22 09:15:07','2026-06-22 09:15:39'),(17,17,5,1,'تم توزيع الجهاز يدوياً',NULL,NULL,'2026-06-22 16:52:12',NULL,0,NULL,NULL,'2026-06-22 13:52:12','2026-06-22 13:52:12'),(18,18,5,1,'تم توزيع الجهاز يدوياً',NULL,NULL,'2026-06-22 16:59:22',NULL,0,NULL,NULL,'2026-06-22 13:59:22','2026-06-22 13:59:22'),(19,19,5,1,'تم توزيع الجهاز يدوياً',NULL,NULL,'2026-06-22 17:08:31',NULL,0,NULL,NULL,'2026-06-22 14:08:31','2026-06-22 14:08:31'),(20,20,5,1,'تم توزيع الجهاز يدوياً',NULL,NULL,'2026-06-22 17:13:03',NULL,0,NULL,NULL,'2026-06-22 14:13:03','2026-06-22 14:13:03');
/*!40000 ALTER TABLE `repair_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sale_items`
--

DROP TABLE IF EXISTS `sale_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sale_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` int(11) unsigned NOT NULL,
  `item_type` enum('service','part','device') NOT NULL DEFAULT 'part',
  `inventory_id` int(11) unsigned DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `device_imei` varchar(20) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_items_index` (`sale_id`),
  KEY `inventory_sale_index` (`inventory_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sale_items`
--

LOCK TABLES `sale_items` WRITE;
/*!40000 ALTER TABLE `sale_items` DISABLE KEYS */;
INSERT INTO `sale_items` VALUES (1,1,'part',1,'شاشة سامسونج',1,300.00,300.00,NULL,NULL),(4,4,'part',1,'شاشة سامسونج',1,300.00,300.00,NULL,NULL),(7,7,'part',1,'شاشة سامسونج',1,300.00,300.00,NULL,NULL),(8,8,'part',1,'شاشة سامسونج',1,300.00,300.00,NULL,NULL),(9,9,'part',1,'شاشة سامسونج',1,300.00,300.00,NULL,NULL),(10,10,'part',1,'شاشة سامسونج',1,300.00,300.00,NULL,NULL),(12,12,'part',12,'شاشة اوبو',1,700.00,700.00,NULL,NULL),(13,13,'part',12,'شاشة اوبو',1,700.00,700.00,NULL,NULL),(33,33,'part',7,'شاشة شاومي',1,700.00,700.00,NULL,NULL),(34,34,'part',7,'شاشة شاومي',1,700.00,700.00,NULL,NULL);
/*!40000 ALTER TABLE `sale_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sale_payments`
--

DROP TABLE IF EXISTS `sale_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sale_payments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` int(11) unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','card','wallet','bank_transfer') NOT NULL DEFAULT 'cash',
  `wallet_id` int(11) unsigned DEFAULT NULL,
  `payment_date` datetime NOT NULL DEFAULT current_timestamp(),
  `notes` text DEFAULT NULL,
  `created_by` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sale_payments`
--

LOCK TABLES `sale_payments` WRITE;
/*!40000 ALTER TABLE `sale_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `sale_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sales` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(50) NOT NULL,
  `customer_id` int(11) unsigned DEFAULT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `sale_date` datetime NOT NULL DEFAULT current_timestamp(),
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(10,2) DEFAULT 0.00,
  `tax` decimal(10,2) DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(10,2) DEFAULT 0.00,
  `remaining_amount` decimal(10,2) DEFAULT 0.00,
  `payment_method` varchar(50) DEFAULT 'cash',
  `wallet_id` int(11) unsigned DEFAULT NULL,
  `status` varchar(50) DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_number_unique` (`invoice_number`),
  KEY `customer_sale_index` (`customer_id`),
  KEY `user_sale_index` (`user_id`),
  KEY `wallet_sale_index` (`wallet_id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES (1,'INV-2026-45633',1,1,'2026-06-20 05:47:22',300.00,0.00,0.00,300.00,300.00,0.00,'cash',NULL,'completed','','2026-06-20 02:47:22','2026-06-21 13:00:24',NULL),(4,'INV-2026-14606',7,5,'2026-06-20 08:19:14',300.00,0.00,0.00,300.00,300.00,0.00,'cash',NULL,'completed','فاتورة صيانة للجهاز DEV-2026-0725 - اوبو 1+','2026-06-20 05:19:14','2026-06-21 13:06:19',NULL),(7,'INV-2026-43886',8,5,'2026-06-20 08:27:14',300.00,0.00,0.00,300.00,300.00,0.00,'cash',NULL,'completed','فاتورة صيانة للجهاز DEV-2026-0782 - اوبو يس','2026-06-20 05:27:14','2026-06-21 13:32:34',NULL),(8,'INV-2026-75516',8,5,'2026-06-20 08:28:13',300.00,0.00,0.00,300.00,300.00,0.00,'cash',NULL,'completed','فاتورة صيانة للجهاز DEV-2026-4514 - 1+ يس','2026-06-20 05:28:13','2026-06-21 15:47:54',NULL),(9,'INV-2026-80149',9,5,'2026-06-20 08:49:10',300.00,0.00,0.00,300.00,300.00,0.00,'cash',NULL,'completed','فاتورة صيانة للجهاز DEV-2026-0311 - اوبو 1+','2026-06-20 05:49:10','2026-06-21 15:48:33',NULL),(10,'INV-2026-51036',7,3,'2026-06-21 15:31:54',300.00,0.00,0.00,300.00,300.00,0.00,'cash',NULL,'completed','فاتورة صيانة للجهاز DEV-2026-0725 - اوبو 1+','2026-06-21 12:31:54','2026-06-22 06:10:40',NULL),(11,'INV-2026-87787',1,5,'2026-06-22 08:08:57',700.00,0.00,0.00,700.00,0.00,700.00,'cash',NULL,'pending','فاتورة صيانة للجهاز DEV-2026-0551 - شاومي 14','2026-06-22 05:08:57','2026-06-22 05:08:57',NULL),(12,'INV-2026-23592',1,7,'2026-06-22 09:15:35',700.00,0.00,0.00,700.00,0.00,0.00,'cash',NULL,'completed','','2026-06-22 06:15:35','2026-06-22 06:15:35',NULL),(13,'INV-2026-39902',1,7,'2026-06-22 09:30:18',700.00,0.00,0.00,700.00,0.00,200.00,'cash',NULL,'pending','','2026-06-22 06:30:18','2026-06-22 06:30:18',NULL),(33,'INV-2026-44743',1,5,'2026-06-22 11:57:53',700.00,0.00,0.00,700.00,700.00,700.00,'cash',NULL,'completed','فاتورة صيانة للجهاز DEV-2026-7612 - شاومي يس','2026-06-22 08:57:53','2026-06-22 09:01:00',NULL),(34,'INV-2026-31454',1,5,'2026-06-22 12:15:39',700.00,0.00,0.00,700.00,0.00,700.00,'cash',NULL,'pending','فاتورة صيانة للجهاز DEV-2026-0913 - اوبو يس','2026-06-22 09:15:39','2026-06-22 09:15:39',NULL);
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_group` varchar(50) DEFAULT 'general',
  `is_encrypted` tinyint(1) NOT NULL DEFAULT 0,
  `description` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key_unique` (`setting_key`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'work_start_time','09:00','attendance',0,'وقت بدء الدوام الرسمي (تنسيق 24 ساعة)','2026-06-22 17:18:29'),(11,'work_end_time','18:00','attendance',0,'وقت نهاية العمل','2026-06-22 17:31:11'),(12,'work_hours_per_day','8','attendance',0,'عدد ساعات العمل اليومية','2026-06-22 17:31:11'),(13,'late_grace_minutes','15','attendance',0,'دقائق السماح بعد بداية العمل','2026-06-22 17:31:11'),(14,'shift_enabled','0','attendance',0,'تفعيل نظام الورديات','2026-06-22 17:31:11'),(15,'shift_morning_start','09:00','attendance',0,'بداية الوردية الصباحية','2026-06-22 17:31:11'),(16,'shift_morning_end','14:00','attendance',0,'نهاية الوردية الصباحية','2026-06-22 17:31:11'),(17,'shift_evening_start','14:00','attendance',0,'بداية الوردية المسائية','2026-06-22 17:31:11'),(18,'shift_evening_end','20:00','attendance',0,'نهاية الوردية المسائية','2026-06-22 17:31:11');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shifts`
--

DROP TABLE IF EXISTS `shifts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shifts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `start_time` datetime NOT NULL DEFAULT current_timestamp(),
  `end_time` datetime DEFAULT NULL,
  `status` enum('active','closed','archived') NOT NULL DEFAULT 'active',
  `total_actions` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `archived_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shifts`
--

LOCK TABLES `shifts` WRITE;
/*!40000 ALTER TABLE `shifts` DISABLE KEYS */;
INSERT INTO `shifts` VALUES (1,1,'مدير النظام','2026-06-20 05:13:58','2026-06-20 06:05:06','archived',0,'2026-06-20 02:13:58',NULL),(2,7,'محمود يونس','2026-06-22 08:05:40','2026-06-22 20:12:53','closed',0,'2026-06-22 05:05:40',NULL),(3,7,'محمود يونس','2026-06-22 20:12:53',NULL,'active',0,'2026-06-22 17:12:53',NULL);
/*!40000 ALTER TABLE `shifts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shifts_archive`
--

DROP TABLE IF EXISTS `shifts_archive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shifts_archive` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shift_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `total_actions` int(11) DEFAULT 0,
  `archived_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_archived_at` (`archived_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shifts_archive`
--

LOCK TABLES `shifts_archive` WRITE;
/*!40000 ALTER TABLE `shifts_archive` DISABLE KEYS */;
/*!40000 ALTER TABLE `shifts_archive` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `suppliers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `tax_number` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` VALUES (1,'احمد جوهر',NULL,'',NULL,NULL,NULL,NULL,'2026-06-20 02:46:02','2026-06-20 02:46:02');
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `technician_specialties`
--

DROP TABLE IF EXISTS `technician_specialties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technician_specialties` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `technician_type_id` int(11) unsigned NOT NULL,
  `experience_years` tinyint(2) DEFAULT 0,
  `rating` decimal(3,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_tech_unique` (`user_id`,`technician_type_id`),
  KEY `technician_type_id` (`technician_type_id`),
  CONSTRAINT `technician_specialties_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `technician_specialties_ibfk_2` FOREIGN KEY (`technician_type_id`) REFERENCES `technician_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technician_specialties`
--

LOCK TABLES `technician_specialties` WRITE;
/*!40000 ALTER TABLE `technician_specialties` DISABLE KEYS */;
INSERT INTO `technician_specialties` VALUES (1,3,2,5,0.00,'2026-06-20 02:37:55'),(2,4,3,4,0.00,'2026-06-20 02:37:55'),(3,5,1,6,0.00,'2026-06-20 02:37:55');
/*!40000 ALTER TABLE `technician_specialties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `technician_transfers`
--

DROP TABLE IF EXISTS `technician_transfers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technician_transfers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `device_id` int(11) unsigned NOT NULL,
  `from_technician_id` int(11) unsigned DEFAULT NULL,
  `to_technician_id` int(11) unsigned NOT NULL,
  `from_technician_type_id` int(11) unsigned DEFAULT NULL,
  `to_technician_type_id` int(11) unsigned NOT NULL,
  `transfer_reason` text NOT NULL,
  `transfer_notes` text DEFAULT NULL,
  `transferred_by` int(11) unsigned NOT NULL,
  `is_auto_transfer` tinyint(1) NOT NULL DEFAULT 0,
  `transferred_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `device_id` (`device_id`),
  KEY `from_technician_id` (`from_technician_id`),
  KEY `to_technician_id` (`to_technician_id`),
  KEY `from_technician_type_id` (`from_technician_type_id`),
  KEY `to_technician_type_id` (`to_technician_type_id`),
  KEY `transferred_by` (`transferred_by`),
  CONSTRAINT `technician_transfers_ibfk_1` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `technician_transfers_ibfk_2` FOREIGN KEY (`from_technician_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `technician_transfers_ibfk_3` FOREIGN KEY (`to_technician_id`) REFERENCES `users` (`id`),
  CONSTRAINT `technician_transfers_ibfk_4` FOREIGN KEY (`from_technician_type_id`) REFERENCES `technician_types` (`id`) ON DELETE SET NULL,
  CONSTRAINT `technician_transfers_ibfk_5` FOREIGN KEY (`to_technician_type_id`) REFERENCES `technician_types` (`id`),
  CONSTRAINT `technician_transfers_ibfk_6` FOREIGN KEY (`transferred_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technician_transfers`
--

LOCK TABLES `technician_transfers` WRITE;
/*!40000 ALTER TABLE `technician_transfers` DISABLE KEYS */;
INSERT INTO `technician_transfers` VALUES (1,3,5,3,1,2,'تحويل آلي بناءً على سير العمل','المرحلة: general_repair',5,1,'2026-06-20 08:27:17'),(2,1,5,3,1,2,'تحويل آلي بناءً على سير العمل','المرحلة: general_repair',5,1,'2026-06-20 08:27:20'),(3,4,5,3,1,2,'تحويل آلي بناءً على سير العمل','المرحلة: general_repair',5,1,'2026-06-22 08:08:31');
/*!40000 ALTER TABLE `technician_transfers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `technician_types`
--

DROP TABLE IF EXISTS `technician_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `technician_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technician_types`
--

LOCK TABLES `technician_types` WRITE;
/*!40000 ALTER TABLE `technician_types` DISABLE KEYS */;
INSERT INTO `technician_types` VALUES (1,'فني فك وتقفيل','disassembly','متخصص في فك الأجهزة وتقفيلها وفحص الجودة','2026-06-20 01:22:09'),(2,'فني بوردة ومعالجات','motherboard','متخصص في إصلاح البوردة والـ IC والمعالجات','2026-06-20 01:22:09'),(3,'فني سوفت وير','software','متخصص في مشاكل البرمجيات والسوفت وير','2026-06-20 01:22:09');
/*!40000 ALTER TABLE `technician_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `role` enum('admin','manager','technician','sales','accountant') NOT NULL DEFAULT 'sales',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login_ip` varchar(45) DEFAULT NULL,
  `last_login_device` text DEFAULT NULL,
  `current_shift_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_unique` (`username`),
  UNIQUE KEY `email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','admin@system.com','$2y$10$gExaRVL6VreKNtLmtb5peetJuoIVe5qkP5COzoaKGoHsqtGc6HTde','مدير النظام','01000000000','admin',1,'2026-06-22 19:58:33','2026-06-20 02:02:45','2026-06-22 16:58:33','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL),(2,'accountant','accountant@system.com','$2y$10$i0Pd96jNUJz8SvlY12Ilt.XyUIXUqmNpMlAM3Xv7j2iLa.k5Yg6eu','محاسب النظام','01055555555','accountant',1,'2026-06-22 08:05:55','2026-06-20 02:02:45','2026-06-22 05:05:55','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL),(3,'tech1','tech1@system.com','$2y$10$tR7wW76KLGAmhQQO88aLkuKcbzMZ4aDbbmXIVeb4rhG.egQioXhG6','أحمد فني بوردة','01011111111','technician',1,'2026-06-21 19:26:52','2026-06-20 02:02:45','2026-06-21 16:26:52','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL),(4,'tech2','tech2@system.com','$2y$10$EweIWL8SH3wJYZKtEj0VOe0qK/XHTbqeeKdAn1h212F9wgZvQk4P6','محمد فني سوفت وير','01022222222','technician',1,NULL,'2026-06-20 02:02:45','2026-06-21 16:25:13',NULL,NULL,NULL),(5,'tech3','tech3@system.com','$2y$10$aFGlhb8AgmQ7DF5Cjinl7.Ib96Fu3IjinF1ufJBOO8Go6MhRqM3NO','علي فني فك وتقفيل','01033333333','technician',1,'2026-06-22 20:07:37','2026-06-20 02:02:45','2026-06-22 17:07:37','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL),(7,'الشيخ محمود','karimelsha3er222@gmail.com','$2y$10$WIKUBmZ37ajodufc/JVNHeuG4YRyt48ijsXYKtKpLvCNffrK1poxC','محمود يونس','+201016413643','admin',1,'2026-06-22 20:12:22','2026-06-21 16:18:05','2026-06-22 17:12:22','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wallet_transactions`
--

DROP TABLE IF EXISTS `wallet_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wallet_transactions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `wallet_id` int(11) unsigned NOT NULL,
  `transaction_type` enum('deposit','withdraw','transfer','payment','fawry') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `fee` decimal(10,2) DEFAULT 0.00,
  `balance_after` decimal(10,2) NOT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `transaction_date` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `wallet_transaction_index` (`wallet_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wallet_transactions`
--

LOCK TABLES `wallet_transactions` WRITE;
/*!40000 ALTER TABLE `wallet_transactions` DISABLE KEYS */;
INSERT INTO `wallet_transactions` VALUES (1,2,'withdraw',5.00,0.00,-5.00,NULL,'مصروف: f','2026-06-20 05:23:20',1,'2026-06-20 02:23:20'),(2,8,'withdraw',58.00,0.00,-58.00,NULL,'مصروف: f','2026-06-20 05:47:59',1,'2026-06-20 02:47:59');
/*!40000 ALTER TABLE `wallet_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wallets`
--

DROP TABLE IF EXISTS `wallets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wallets` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `wallet_name` varchar(50) NOT NULL,
  `wallet_type` enum('mobile_wallet','bank_account','cash') NOT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `owner_name` varchar(100) DEFAULT NULL,
  `current_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wallets`
--

LOCK TABLES `wallets` WRITE;
/*!40000 ALTER TABLE `wallets` DISABLE KEYS */;
INSERT INTO `wallets` VALUES (1,'خزينة المحل','cash',NULL,NULL,0.00,1,NULL,'2026-06-20 02:22:54','2026-06-20 02:22:54'),(2,'فودافون كاش','mobile_wallet',NULL,NULL,-5.00,1,NULL,'2026-06-20 02:22:54','2026-06-20 02:23:20'),(3,'اتصالات','mobile_wallet',NULL,NULL,0.00,1,NULL,'2026-06-20 02:22:54','2026-06-20 02:22:54'),(4,'WE Pay','mobile_wallet',NULL,NULL,0.00,1,NULL,'2026-06-20 02:22:54','2026-06-20 02:22:54'),(5,'البنك الأهلي','bank_account',NULL,NULL,0.00,1,NULL,'2026-06-20 02:22:54','2026-06-20 02:22:54'),(6,'خزينة المحل','cash',NULL,NULL,0.00,1,NULL,'2026-06-20 02:37:55','2026-06-20 02:37:55'),(7,'فودافون كاش','mobile_wallet',NULL,NULL,0.00,1,NULL,'2026-06-20 02:37:55','2026-06-20 02:37:55'),(8,'اتصالات','mobile_wallet',NULL,NULL,-58.00,1,NULL,'2026-06-20 02:37:55','2026-06-20 02:47:59'),(9,'WE Pay','mobile_wallet',NULL,NULL,0.00,1,NULL,'2026-06-20 02:37:55','2026-06-20 02:37:55'),(10,'البنك الأهلي','bank_account',NULL,NULL,0.00,1,NULL,'2026-06-20 02:37:55','2026-06-20 02:37:55');
/*!40000 ALTER TABLE `wallets` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-22 21:04:49
