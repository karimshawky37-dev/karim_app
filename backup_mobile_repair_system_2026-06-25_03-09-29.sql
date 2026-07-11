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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendance`
--

LOCK TABLES `attendance` WRITE;
/*!40000 ALTER TABLE `attendance` DISABLE KEYS */;
INSERT INTO `attendance` VALUES (1,7,'2026-06-22 20:16:48','2026-06-22 22:53:36','late',NULL,'2026-06-22 17:16:48','::1','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',2.60,0,NULL,NULL,NULL,NULL),(2,7,'2026-06-23 12:23:40',NULL,'late',NULL,'2026-06-23 09:23:40','::1',NULL,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL,NULL,0,NULL,NULL,NULL,NULL),(3,5,'2026-06-23 13:37:44',NULL,'late',NULL,'2026-06-23 10:37:44','::1',NULL,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL,NULL,0,NULL,NULL,NULL,NULL),(4,7,'2026-06-24 11:46:30',NULL,'late',NULL,'2026-06-24 08:46:30','::1',NULL,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL,NULL,0,NULL,NULL,NULL,NULL),(5,5,'2026-06-24 12:48:06',NULL,'late',NULL,'2026-06-24 09:48:06','::1',NULL,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL,NULL,0,NULL,NULL,NULL,NULL),(6,7,'2026-06-25 02:59:27',NULL,'present',NULL,'2026-06-24 23:59:27','::1',NULL,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL,NULL,0,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `attendance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attendance_archive`
--

DROP TABLE IF EXISTS `attendance_archive`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `attendance_archive` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `shift_id` int(11) DEFAULT NULL,
  `check_in` datetime DEFAULT NULL,
  `check_out` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `working_hours` decimal(5,2) DEFAULT NULL,
  `is_modified` tinyint(1) DEFAULT 0,
  `modification_reason` text DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `modified_at` datetime DEFAULT NULL,
  `check_in_ip` varchar(45) DEFAULT NULL,
  `check_in_device` varchar(255) DEFAULT NULL,
  `check_out_ip` varchar(45) DEFAULT NULL,
  `check_out_device` varchar(255) DEFAULT NULL,
  `shift_type` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `archived_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_shift_id` (`shift_id`),
  KEY `idx_archived_at` (`archived_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendance_archive`
--

LOCK TABLES `attendance_archive` WRITE;
/*!40000 ALTER TABLE `attendance_archive` DISABLE KEYS */;
/*!40000 ALTER TABLE `attendance_archive` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=260 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,1,NULL,'مدير النظام','admin','shift_start','shifts',1,NULL,'{\"user\":\"\\u0645\\u062f\\u064a\\u0631 \\u0627\\u0644\\u0646\\u0638\\u0627\\u0645\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 02:13:58'),(5,1,1,'مدير النظام','admin','shift_end','shifts',1,NULL,'{\"status\":\"closed\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 03:05:06'),(6,1,NULL,'مدير النظام','admin','create','devices',2,NULL,'{\"device_code\":\"DEV-2026-1836\",\"brand\":\"\\u0627\\u0648\\u0628\\u0648\",\"model\":\"d\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:24:38'),(7,1,NULL,'مدير النظام','admin','logout','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:26:45'),(8,2,NULL,'محاسب النظام','guest','login','users',2,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:26:48'),(9,2,NULL,'محاسب النظام','accountant','logout','users',2,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:26:53'),(10,3,NULL,'أحمد فني بوردة','guest','login','users',3,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:26:55'),(11,3,NULL,'أحمد فني بوردة','technician','logout','users',3,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:27:11'),(12,3,NULL,'أحمد فني بوردة','guest','login','users',3,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:27:15'),(13,3,NULL,'أحمد فني بوردة','technician','logout','users',3,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:27:18'),(14,4,NULL,'محمد فني سوفت وير','guest','login','users',4,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:27:21'),(15,4,NULL,'محمد فني سوفت وير','technician','logout','users',4,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:27:28'),(16,1,NULL,'مدير النظام','guest','login','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:27:30'),(17,1,NULL,'مدير النظام','admin','logout','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:37:00'),(18,4,NULL,'محمد فني سوفت وير','guest','login','users',4,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:37:05'),(19,4,NULL,'محمد فني سوفت وير','technician','logout','users',4,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:37:22'),(20,2,NULL,'محاسب النظام','guest','login','users',2,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:37:29'),(21,2,NULL,'محاسب النظام','accountant','create','devices',3,NULL,'{\"device_code\":\"DEV-2026-0725\",\"brand\":\"\\u0627\\u0648\\u0628\\u0648\",\"model\":\"1+\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:37:58'),(22,2,NULL,'محاسب النظام','accountant','logout','users',2,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:38:05'),(23,4,NULL,'محمد فني سوفت وير','guest','login','users',4,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:38:08'),(24,4,NULL,'محمد فني سوفت وير','technician','logout','users',4,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:38:18'),(25,3,NULL,'أحمد فني بوردة','guest','login','users',3,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:38:22'),(26,3,NULL,'أحمد فني بوردة','technician','logout','users',3,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:38:27'),(27,5,NULL,'علي فني فك وتقفيل','guest','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 04:38:33'),(28,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:19:31'),(29,1,NULL,'مدير النظام','guest','login','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:19:34'),(30,1,NULL,'مدير النظام','admin','logout','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:20:12'),(31,5,NULL,'علي فني فك وتقفيل','guest','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:20:18'),(32,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:20:36'),(33,1,NULL,'مدير النظام','guest','login','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:20:40'),(34,1,NULL,'مدير النظام','admin','create','devices',4,NULL,'{\"device_code\":\"DEV-2026-0782\",\"brand\":\"\\u0627\\u0648\\u0628\\u0648\",\"model\":\"\\u064a\\u0633\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:21:55'),(35,1,NULL,'مدير النظام','admin','logout','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:22:14'),(36,5,NULL,'علي فني فك وتقفيل','guest','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:22:17'),(37,5,NULL,'علي فني فك وتقفيل','technician','create','devices',5,NULL,'{\"device_code\":\"DEV-2026-4514\",\"brand\":\"1+\",\"model\":\"\\u064a\\u0633\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:27:49'),(38,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:38:55'),(39,1,NULL,'مدير النظام','guest','login','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:38:58'),(40,1,NULL,'مدير النظام','admin','logout','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:48:38'),(41,5,NULL,'علي فني فك وتقفيل','guest','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:48:41'),(42,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:49:22'),(43,2,NULL,'محاسب النظام','guest','login','users',2,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-20 05:49:25'),(44,2,NULL,'محاسب النظام','accountant','login','users',2,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 12:28:59'),(45,2,NULL,'محاسب النظام','accountant','logout','users',2,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 12:30:43'),(46,1,NULL,'مدير النظام','admin','login','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 12:30:46'),(47,1,NULL,'مدير النظام','admin','logout','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 12:31:27'),(48,3,NULL,'أحمد فني بوردة','technician','login','users',3,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 12:31:32'),(49,3,NULL,'أحمد فني بوردة','technician','logout','users',3,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 12:32:02'),(50,2,NULL,'محاسب النظام','accountant','login','users',2,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 12:32:07'),(51,2,NULL,'محاسب النظام','accountant','update','sales',1,'{\"paid_amount\":\"0.00\",\"status\":\"pending\"}','{\"paid_amount\":300,\"status\":\"completed\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 13:00:24'),(52,2,NULL,'محاسب النظام','accountant','update','sales',4,'{\"paid_amount\":\"0.00\",\"status\":\"pending\"}','{\"paid_amount\":300,\"status\":\"completed\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 13:06:19'),(53,2,NULL,'محاسب النظام','accountant','update','installments',1,'{\"remaining_amount\":\"0.00\"}','{\"remaining_amount\":0,\"paid\":17325}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 13:24:06'),(54,2,NULL,'محاسب النظام','accountant','logout','users',2,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 13:31:28'),(55,1,NULL,'مدير النظام','admin','login','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 13:31:31'),(56,1,NULL,'مدير النظام','admin','update','sales',7,'{\"paid_amount\":\"0.00\",\"status\":\"pending\"}','{\"paid_amount\":300,\"status\":\"completed\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 13:32:34'),(57,1,NULL,'مدير النظام','admin','create','inventory_count',0,NULL,'{\"notes\":\"fe\",\"differences\":[{\"id\":1,\"old\":1,\"new\":4,\"diff\":3}]}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 15:40:07'),(58,1,NULL,'مدير النظام','admin','update','sales',8,'{\"paid_amount\":\"0.00\",\"status\":\"pending\"}','{\"paid_amount\":300,\"status\":\"completed\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 15:47:54'),(59,1,NULL,'مدير النظام','admin','update','sales',9,'{\"paid_amount\":\"0.00\",\"status\":\"pending\"}','{\"paid_amount\":300,\"status\":\"completed\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 15:48:33'),(60,1,NULL,'مدير النظام','admin','create','users',7,NULL,'{\"username\":\"الشيخ محمود\",\"full_name\":\"محمود يونس\",\"role\":\"admin\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 16:18:05'),(61,1,NULL,'مدير النظام','admin','logout','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 16:18:10'),(62,7,NULL,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 16:18:15'),(63,7,NULL,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 16:18:23'),(64,7,NULL,'محمود يونس','admin','update','users',3,'{\"is_active\":1}','{\"is_active\":0}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 16:25:01'),(65,7,NULL,'محمود يونس','admin','update','users',3,'{\"is_active\":0}','{\"is_active\":1}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 16:25:08'),(66,7,NULL,'محمود يونس','admin','update','users',4,'{\"is_active\":1}','{\"is_active\":0}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 16:25:11'),(67,7,NULL,'محمود يونس','admin','update','users',4,'{\"is_active\":0}','{\"is_active\":1}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 16:25:13'),(68,7,NULL,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 16:26:49'),(69,3,NULL,'أحمد فني بوردة','technician','login','users',3,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 16:26:52'),(70,3,NULL,'أحمد فني بوردة','technician','logout','users',3,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 16:54:16'),(71,7,NULL,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 16:54:20'),(72,7,NULL,'محمود يونس','admin','create','devices',12,NULL,'{\"device_code\":\"DEV-2026-4270\",\"customer_id\":1,\"brand\":\"اوبو\",\"model\":\"يس\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-21 17:03:17'),(73,7,NULL,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 04:26:57'),(74,7,NULL,'محمود يونس','admin','shift_start','shifts',2,NULL,'{\"user\":\"محمود يونس\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 05:05:40'),(75,7,2,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 05:05:52'),(76,2,NULL,'محاسب النظام','accountant','login','users',2,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 05:05:55'),(77,2,NULL,'محاسب النظام','accountant','create','devices',13,NULL,'{\"device_code\":\"DEV-2026-0551\",\"customer_id\":1,\"brand\":\"شاومي\",\"model\":\"14\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 05:06:40'),(78,2,NULL,'محاسب النظام','accountant','logout','users',2,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 05:08:15'),(79,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 05:08:18'),(80,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 05:09:00'),(81,7,2,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 05:09:03'),(82,7,2,'محمود يونس','admin','create','devices',14,NULL,'{\"device_code\":\"DEV-2026-6403\",\"customer_id\":1,\"brand\":\"شاومي\",\"model\":\"1+\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 05:15:55'),(83,7,2,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 05:53:22'),(84,7,2,'محمود يونس','admin','create','inventory',12,NULL,'{\"name\":\"شاشة اوبو\",\"category\":\"شاشة\",\"quantity\":10,\"purchase_price\":500,\"selling_price\":700}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 05:54:07'),(85,7,2,'محمود يونس','admin','update','sales',10,'{\"paid_amount\":\"0.00\",\"status\":\"pending\"}','{\"paid_amount\":300,\"status\":\"completed\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 06:10:40'),(86,7,2,'محمود يونس','admin','create','sales',12,NULL,'{\"invoice_number\":\"INV-2026-23592\",\"customer_id\":1,\"total_amount\":700,\"payment_method\":\"cash\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 06:15:35'),(87,7,2,'محمود يونس','admin','create','sales',13,NULL,'{\"invoice_number\":\"INV-2026-39902\",\"customer_id\":1,\"total_amount\":700,\"payment_method\":\"cash\",\"status\":\"pending\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 06:30:18'),(88,7,2,'محمود يونس','admin','create','devices',15,NULL,'{\"device_code\":\"DEV-2026-7612\",\"customer_id\":1,\"brand\":\"شاومي\",\"model\":\"يس\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 08:18:02'),(89,7,2,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 08:20:59'),(90,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 08:21:04'),(91,5,NULL,'علي فني فك وتقفيل','technician','create','device_checklist',1,NULL,'{\"device_id\":15,\"type\":\"after_repair\",\"technician_id\":5}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 08:31:25'),(92,5,NULL,'علي فني فك وتقفيل','technician','create','device_checklist',5,NULL,'{\"device_id\":15,\"type\":\"after_repair\",\"technician_id\":5}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 08:45:56'),(93,5,NULL,'علي فني فك وتقفيل','technician','create','device_checklist',6,NULL,'{\"device_id\":15,\"type\":\"after_repair\",\"technician_id\":5}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 08:46:48'),(94,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 08:58:19'),(95,7,2,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 08:58:23'),(96,7,2,'محمود يونس','admin','create','devices',16,NULL,'{\"device_code\":\"DEV-2026-0913\",\"customer_id\":1,\"brand\":\"اوبو\",\"model\":\"يس\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 09:15:07'),(97,7,2,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 09:15:16'),(98,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 09:15:19'),(99,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 09:15:42'),(100,7,2,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 09:15:45'),(101,7,2,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 13:25:58'),(102,7,2,'محمود يونس','admin','update','users',2,'{\"action\":\"update_permissions\"}','{\"permissions\":[\"create_expenses\",\"create_invoices\",\"delete_invoices\",\"edit_invoices\",\"manage_wallets\",\"view_expenses\",\"view_financial\",\"view_invoices\",\"view_reports\",\"view_wallets\"]}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 13:42:43'),(103,7,2,'محمود يونس','admin','create','devices',17,NULL,'{\"device_code\":\"DEV-2026-1176\",\"customer_id\":1,\"brand\":\"ايفون\",\"model\":\"11برو ماكس\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 13:52:12'),(104,7,2,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 13:52:42'),(105,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 13:52:48'),(106,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 13:57:40'),(107,1,NULL,'مدير النظام','admin','login','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 13:57:44'),(108,1,NULL,'مدير النظام','admin','create','devices',18,NULL,'{\"device_code\":\"DEV-2026-9632\",\"customer_id\":1,\"brand\":\"هواوي\",\"model\":\"14\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 13:59:22'),(109,1,NULL,'مدير النظام','admin','logout','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 13:59:29'),(110,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 13:59:32'),(111,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 14:07:13'),(112,7,2,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 14:07:21'),(113,7,2,'محمود يونس','admin','create','devices',19,NULL,'{\"device_code\":\"DEV-2026-7747\",\"customer_id\":1,\"brand\":\"oppo\",\"model\":\"a52\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 14:08:31'),(114,7,2,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 14:08:35'),(115,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 14:08:41'),(116,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 14:12:20'),(117,7,2,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 14:12:24'),(118,7,2,'محمود يونس','admin','create','devices',20,NULL,'{\"device_code\":\"DEV-2026-7919\",\"customer_id\":1,\"brand\":\"ايفون\",\"model\":\"1+\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 14:13:03'),(119,7,2,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 14:13:09'),(120,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 14:13:12'),(121,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 16:11:07'),(122,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 16:55:19'),(123,1,NULL,'مدير النظام','admin','login','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 16:55:24'),(124,1,NULL,'مدير النظام','admin','logout','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 16:58:30'),(125,1,NULL,'مدير النظام','admin','login','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 16:58:33'),(126,1,NULL,'مدير النظام','admin','logout','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 17:05:32'),(127,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 17:05:36'),(128,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 17:06:35'),(129,7,2,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 17:06:38'),(130,7,2,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 17:07:32'),(131,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 17:07:37'),(132,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 17:12:17'),(133,7,2,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 17:12:22'),(134,7,2,'محمود يونس','admin','shift_start','shifts',3,NULL,'{\"user\":\"محمود يونس\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 17:12:53'),(135,7,3,'محمود يونس','admin','create','attendance',1,NULL,'{\"user_id\":7,\"action\":\"check_in\",\"status\":\"late\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 17:16:48'),(136,7,3,'محمود يونس','admin','update','settings',0,'{\"action\":\"update_work_settings\"}','{\"work_start_time\":\"09:00\",\"work_end_time\":\"18:00\",\"work_hours_per_day\":\"8\",\"late_grace_minutes\":\"15\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 17:51:40'),(137,7,3,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 19:11:36'),(138,7,3,'محمود يونس','admin','shift_start','shifts',4,NULL,'{\"user\":\"محمود يونس\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 19:13:35'),(139,7,4,'محمود يونس','admin','create','devices',22,NULL,'{\"device_code\":\"DEV-2026-7038\",\"customer_id\":1,\"brand\":\"SAM\",\"model\":\"A51\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 19:30:23'),(140,7,4,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 19:33:41'),(141,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 19:33:47'),(142,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 19:37:17'),(143,1,NULL,'مدير النظام','admin','login','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 19:37:23'),(144,1,NULL,'مدير النظام','admin','create','inventory',13,NULL,'{\"name\":\"فلاتة باور\",\"category\":\"IC\",\"quantity\":10,\"purchase_price\":200,\"selling_price\":300}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 19:43:16'),(145,1,NULL,'مدير النظام','admin','logout','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 19:43:41'),(146,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 19:43:46'),(147,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 19:44:38'),(148,7,4,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 19:44:42'),(149,7,4,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 19:46:46'),(150,2,NULL,'محاسب النظام','accountant','login','users',2,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 19:46:53'),(151,2,NULL,'محاسب النظام','accountant','logout','users',2,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 19:47:32'),(152,2,NULL,'محاسب النظام','accountant','login','users',2,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 19:47:35'),(153,2,NULL,'محاسب النظام','accountant','logout','users',2,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 19:47:48'),(154,1,NULL,'مدير النظام','admin','login','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 19:47:53'),(155,1,NULL,'مدير النظام','admin','logout','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 19:48:59'),(156,3,NULL,'أحمد فني بوردة','technician','login','users',3,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 19:49:02'),(157,3,NULL,'أحمد فني بوردة','technician','logout','users',3,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 19:49:11'),(158,7,4,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 19:49:16'),(159,7,4,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 19:51:24'),(160,7,4,'محمود يونس','admin','update','attendance',1,'{\"action\":\"check_out\",\"working_hours\":2.6}',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 19:53:36'),(161,7,4,'محمود يونس','admin','update','sales',11,'{\"paid_amount\":\"0.00\",\"status\":\"pending\"}','{\"paid_amount\":700,\"status\":\"completed\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-22 19:56:37'),(162,7,4,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 09:22:29'),(163,7,4,'محمود يونس','admin','create','attendance',2,NULL,'{\"user_id\":7,\"action\":\"check_in\",\"status\":\"late\",\"shift_type\":null}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 09:23:40'),(164,7,4,'محمود يونس','admin','shift_start','shifts',5,NULL,'{\"user\":\"محمود يونس\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 09:23:50'),(165,7,5,'محمود يونس','admin','create','devices',23,NULL,'{\"device_code\":\"DEV-2026-3058\",\"customer_id\":1,\"brand\":\"ايفون\",\"model\":\"11برو ماكس\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 09:25:21'),(166,7,5,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 09:27:05'),(167,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 09:27:09'),(168,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 09:43:16'),(169,7,5,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 09:43:19'),(170,7,5,'محمود يونس','admin','create','devices',26,NULL,'{\"device_code\":\"DEV-2026-1833\",\"customer_id\":1,\"brand\":\"اوبو\",\"model\":\"يس\",\"technician_id\":5}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 10:01:30'),(171,7,5,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 10:06:26'),(172,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 10:06:29'),(173,5,NULL,'علي فني فك وتقفيل','technician','create','attendance',3,NULL,'{\"user_id\":5,\"action\":\"check_in\",\"status\":\"late\",\"shift_type\":null}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 10:37:44'),(174,5,NULL,'علي فني فك وتقفيل','technician','create','device_maintenance_log',3,NULL,'{\"device_id\":26,\"action\":\"منتظر\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 11:23:33'),(175,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 11:23:45'),(176,7,5,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 11:23:51'),(177,7,5,'محمود يونس','admin','create','device_maintenance_log',4,NULL,'{\"device_id\":26,\"action\":\"منتظر\",\"is_important\":0}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 11:29:21'),(178,7,5,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 11:29:45'),(179,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 11:29:49'),(180,5,NULL,'علي فني فك وتقفيل','technician','update','devices',26,'{\"action\":\"diagnose\"}','{\"diagnosed_issue\":\"شاشة مكسورة\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 11:32:49'),(181,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 11:33:27'),(182,7,5,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 11:33:31'),(183,7,5,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 13:04:41'),(184,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 13:04:44'),(185,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 13:06:30'),(186,1,NULL,'مدير النظام','admin','login','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 13:06:33'),(187,1,NULL,'مدير النظام','admin','update','devices',26,'{\"status\":\"pending\"}','{\"status\":\"received\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 13:06:42'),(188,1,NULL,'مدير النظام','admin','delete','devices',1,'{\"reason\":\"حذف يدوي\"}',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 13:11:38'),(189,1,NULL,'مدير النظام','admin','create','devices',27,NULL,'{\"device_code\":\"DEV-2026-6338\",\"customer_id\":1,\"brand\":\"اوبو\",\"model\":\"1+\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 13:12:37'),(190,1,NULL,'مدير النظام','admin','logout','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 13:18:31'),(191,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 13:18:34'),(192,5,NULL,'علي فني فك وتقفيل','technician','update','devices',27,'{\"action\":\"diagnose\"}','{\"diagnosed_issue\":\"بطارية منفوخة\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 13:19:31'),(193,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 13:19:47'),(194,7,5,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 13:19:51'),(195,7,5,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 13:20:40'),(196,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 13:20:43'),(197,5,NULL,'علي فني فك وتقفيل','technician','update','devices',27,'{\"status_old\":\"unknown\"}','{\"status_new\":\"waiting_parts\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 13:41:50'),(198,5,NULL,'علي فني فك وتقفيل','technician','create','device_checklist',14,NULL,'{\"device_id\":27,\"type\":\"after_repair\",\"technician_id\":5}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 14:02:10'),(199,5,NULL,'علي فني فك وتقفيل','technician','create','device_checklist',15,NULL,'{\"device_id\":27,\"type\":\"after_repair\",\"technician_id\":5}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 14:04:07'),(200,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 14:04:42'),(201,7,5,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-23 14:04:45'),(202,7,5,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 08:41:33'),(203,7,5,'محمود يونس','admin','create','attendance',4,NULL,'{\"user_id\":7,\"action\":\"check_in\",\"status\":\"late\",\"shift_type\":null}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 08:46:30'),(204,7,5,'محمود يونس','admin','create','devices',1,NULL,'{\"device_code\":\"DEV-2026-2745\",\"customer_id\":1,\"brand\":\"انفنكس\",\"model\":\"14\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 09:43:37'),(205,7,5,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 09:43:49'),(206,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 09:43:52'),(207,7,5,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 09:44:03'),(208,7,5,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 09:46:48'),(209,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 09:46:50'),(210,5,NULL,'علي فني فك وتقفيل','technician','create','attendance',5,NULL,'{\"user_id\":5,\"action\":\"check_in\",\"status\":\"late\",\"shift_type\":null}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 09:48:06'),(211,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 10:01:02'),(212,7,5,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 10:01:06'),(213,7,5,'محمود يونس','admin','create','devices',2,NULL,'{\"device_code\":\"DEV-2026-7787\",\"customer_id\":1,\"brand\":\"اوبو\",\"model\":\"يس\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 10:02:14'),(214,7,5,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 10:02:16'),(215,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 10:02:20'),(216,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 10:46:11'),(217,7,5,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 10:46:58'),(218,7,5,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 10:47:48'),(219,7,5,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 10:47:49'),(220,7,5,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 10:47:51'),(221,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 10:47:53'),(222,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 11:09:56'),(223,7,5,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 11:09:59'),(224,7,5,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 12:21:50'),(225,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 12:21:54'),(226,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 12:22:03'),(227,7,5,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 12:22:06'),(228,7,5,'محمود يونس','admin','create','devices',3,NULL,'{\"device_code\":\"DEV-2026-0077\",\"customer_id\":1,\"brand\":\"هواوي\",\"model\":\"14\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 12:26:58'),(229,7,5,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 12:27:12'),(230,7,5,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 12:27:14'),(231,7,5,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 12:27:17'),(232,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 12:27:20'),(233,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 12:30:47'),(234,7,5,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 12:30:49'),(235,7,5,'محمود يونس','admin','create','devices',4,NULL,'{\"device_code\":\"DEV-2026-8003\",\"customer_id\":1,\"brand\":\"oppo\",\"model\":\"a52\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 12:31:15'),(236,7,5,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 12:31:17'),(237,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 12:31:20'),(238,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 12:33:12'),(239,7,5,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 12:33:15'),(240,7,5,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 12:33:22'),(241,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 12:33:24'),(242,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 12:35:06'),(243,7,5,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 12:35:09'),(244,7,5,'محمود يونس','admin','create','devices',5,NULL,'{\"device_code\":\"DEV-2026-9345\",\"customer_id\":6,\"brand\":\"1+\",\"model\":\"1+\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 12:45:57'),(245,7,5,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 12:46:08'),(246,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 12:46:10'),(247,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 12:46:39'),(248,7,5,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 12:46:42'),(249,7,5,'محمود يونس','admin','delete','devices',3,'{\"reason\":\"Soft delete\"}',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 12:48:22'),(250,7,5,'محمود يونس','admin','delete','devices',2,'{\"reason\":\"Soft delete\"}',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 13:02:49'),(251,7,5,'محمود يونس','admin','delete','devices',5,'{\"reason\":\"Soft delete\"}',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 13:03:09'),(252,7,5,'محمود يونس','admin','create','devices',6,NULL,'{\"device_code\":\"DEV-2026-0340\",\"customer_id\":6,\"brand\":\"شاومي\",\"model\":\"14\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 13:03:29'),(253,7,5,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 13:03:30'),(254,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 13:03:33'),(255,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 13:04:00'),(256,7,5,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 13:04:03'),(257,7,5,'محمود يونس','admin','delete','devices',1,'{\"reason\":\"Soft delete\"}',NULL,'::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 13:04:18'),(258,7,5,'محمود يونس','admin','login','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 23:57:48'),(259,7,5,'محمود يونس','admin','create','attendance',6,NULL,'{\"user_id\":7,\"action\":\"check_in\",\"status\":\"present\",\"shift_type\":null}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-06-24 23:59:27');
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
INSERT INTO `customers` VALUES (1,'محمد','01029169762',NULL,NULL,NULL,NULL,'2026-06-20 02:39:55','2026-06-24 12:31:15',NULL),(6,'كريم','010020',NULL,NULL,NULL,NULL,'2026-06-20 04:24:38','2026-06-20 04:24:38',NULL),(7,'المهندس عبدالرحمن','01029169768',NULL,NULL,NULL,NULL,'2026-06-20 04:37:58','2026-06-20 04:37:58',NULL),(8,'A To Z Educational Center (Zahraa El Maadi)','hg',NULL,NULL,NULL,NULL,'2026-06-20 05:21:55','2026-06-20 05:27:49',NULL),(9,'المهندس عبدالرحمن باشا','سماعة',NULL,NULL,NULL,NULL,'2026-06-20 05:48:12','2026-06-20 05:48:12',NULL);
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device_checklist`
--

DROP TABLE IF EXISTS `device_checklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device_checklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) NOT NULL,
  `check_type` enum('before','after') NOT NULL,
  `checklist_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`checklist_data`)),
  `checked_by` int(11) NOT NULL,
  `checked_at` datetime NOT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_device_id` (`device_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_checklist`
--

LOCK TABLES `device_checklist` WRITE;
/*!40000 ALTER TABLE `device_checklist` DISABLE KEYS */;
INSERT INTO `device_checklist` VALUES (1,1,'before','{\"screen_condition\":\"good\",\"has_scratches\":0,\"buttons_working\":0,\"camera_lens\":\"good\",\"charging_port\":0,\"speaker_working\":0,\"mic_working\":0,\"notes\":\"\"}',7,'2026-06-24 12:43:37',NULL,NULL),(2,2,'before','{\"screen_condition\":\"good\",\"has_scratches\":0,\"buttons_working\":0,\"camera_lens\":\"good\",\"charging_port\":0,\"speaker_working\":0,\"mic_working\":0,\"notes\":\"\"}',7,'2026-06-24 13:02:14',NULL,NULL),(3,3,'before','{\"screen_condition\":\"good\",\"has_scratches\":0,\"buttons_working\":0,\"camera_lens\":\"good\",\"charging_port\":0,\"speaker_working\":0,\"mic_working\":0,\"notes\":\"\"}',7,'2026-06-24 15:26:58',NULL,NULL),(4,4,'before','{\"screen_condition\":\"good\",\"has_scratches\":0,\"buttons_working\":0,\"camera_lens\":\"good\",\"charging_port\":0,\"speaker_working\":0,\"mic_working\":0,\"notes\":\"\"}',7,'2026-06-24 15:31:15',NULL,NULL),(5,5,'before','{\"screen_condition\":\"good\",\"has_scratches\":0,\"buttons_working\":0,\"camera_lens\":\"good\",\"charging_port\":0,\"speaker_working\":0,\"mic_working\":0,\"notes\":\"\"}',7,'2026-06-24 15:45:57',NULL,NULL),(6,5,'after','{\"screen_condition\":\"good\",\"has_scratches\":0,\"buttons_working\":0,\"camera_lens\":\"good\",\"charging_port\":0,\"speaker_working\":0,\"mic_working\":0,\"customer_signature\":\"\",\"notes\":\"\"}',7,'2026-06-24 15:46:56','',''),(7,6,'before','{\"screen_condition\":\"good\",\"has_scratches\":0,\"buttons_working\":0,\"camera_lens\":\"good\",\"charging_port\":0,\"speaker_working\":0,\"mic_working\":0,\"notes\":\"\"}',7,'2026-06-24 16:03:29',NULL,NULL),(8,1,'after','{\"screen_condition\":\"good\",\"has_scratches\":0,\"buttons_working\":0,\"camera_lens\":\"good\",\"charging_port\":0,\"speaker_working\":0,\"mic_working\":0,\"customer_signature\":\"\",\"notes\":\"\"}',7,'2026-06-24 16:04:11','',''),(9,4,'after','{\"screen_condition\":\"good\",\"has_scratches\":0,\"buttons_working\":0,\"camera_lens\":\"good\",\"charging_port\":0,\"speaker_working\":0,\"mic_working\":0,\"customer_signature\":\"\",\"notes\":\"\"}',7,'2026-06-24 16:04:16','','');
/*!40000 ALTER TABLE `device_checklist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device_escalations`
--

DROP TABLE IF EXISTS `device_escalations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device_escalations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `escalated_at` datetime NOT NULL,
  `resolved_at` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `resolved_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_device_id` (`device_id`),
  KEY `idx_status_id` (`status_id`),
  KEY `idx_escalated_at` (`escalated_at`),
  KEY `idx_resolved` (`resolved_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_escalations`
--

LOCK TABLES `device_escalations` WRITE;
/*!40000 ALTER TABLE `device_escalations` DISABLE KEYS */;
/*!40000 ALTER TABLE `device_escalations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device_maintenance_log`
--

DROP TABLE IF EXISTS `device_maintenance_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device_maintenance_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `performed_by` int(11) DEFAULT NULL,
  `performed_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_device_id` (`device_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_maintenance_log`
--

LOCK TABLES `device_maintenance_log` WRITE;
/*!40000 ALTER TABLE `device_maintenance_log` DISABLE KEYS */;
INSERT INTO `device_maintenance_log` VALUES (1,1,'received','تم استلام الجهاز من Karim Ahmed',7,'2026-06-24 12:43:37'),(2,2,'received','تم استلام الجهاز من المهندس عبدالرحمن',7,'2026-06-24 13:02:14'),(3,2,'completed','تم إصلاح الجهاز',5,'2026-06-24 13:51:56'),(4,2,'cancelled','تم إلغاء التصليح حسب رغبة العميل - السبب: مزاجو',7,'2026-06-24 15:02:01'),(5,1,'completed','تم إصلاح الجهاز',5,'2026-06-24 15:22:00'),(6,3,'received','تم استلام الجهاز من كريم',7,'2026-06-24 15:26:58'),(7,4,'received','تم استلام الجهاز من محمد',7,'2026-06-24 15:31:15'),(8,4,'part_available','قطعة غيار متوفرة: شاشة اوبو',5,'2026-06-24 15:33:05'),(9,4,'completed','تم إصلاح الجهاز',5,'2026-06-24 15:34:52'),(10,5,'received','تم استلام الجهاز من كريم',7,'2026-06-24 15:45:57'),(11,5,'start_inspection','بدء فحص الجهاز',5,'2026-06-24 15:46:20'),(12,5,'part_available','قطعة غيار متوفرة: شاشة ايفون',5,'2026-06-24 15:46:32'),(13,5,'completed','تم إصلاح الجهاز',5,'2026-06-24 15:46:37'),(14,6,'received','تم استلام الجهاز من كريم',7,'2026-06-24 16:03:29'),(15,1,'start_inspection','بدء فحص الجهاز',5,'2026-06-24 16:03:40'),(16,1,'part_available','قطعة غيار متوفرة: شاشة ايفون',5,'2026-06-24 16:03:47'),(17,1,'completed','تم إصلاح الجهاز',5,'2026-06-24 16:03:49'),(18,4,'start_inspection','بدء فحص الجهاز',5,'2026-06-24 16:03:50'),(19,4,'part_available','قطعة غيار متوفرة: شاشة انفنكس',5,'2026-06-24 16:03:53'),(20,4,'completed','تم إصلاح الجهاز',5,'2026-06-24 16:03:54'),(21,6,'start_inspection','بدء فحص الجهاز',5,'2026-06-24 16:03:56');
/*!40000 ALTER TABLE `device_maintenance_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device_qr_codes`
--

DROP TABLE IF EXISTS `device_qr_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device_qr_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) NOT NULL,
  `qr_code` varchar(100) NOT NULL,
  `qr_image_path` varchar(255) DEFAULT NULL,
  `tracking_url` varchar(255) DEFAULT NULL,
  `generated_by` int(11) DEFAULT NULL,
  `generated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_device_id` (`device_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_qr_codes`
--

LOCK TABLES `device_qr_codes` WRITE;
/*!40000 ALTER TABLE `device_qr_codes` DISABLE KEYS */;
INSERT INTO `device_qr_codes` VALUES (1,1,'DEV-2026-2745','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-2745&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-2745',7,'2026-06-24 12:43:37'),(2,2,'DEV-2026-7787','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-7787&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-7787',7,'2026-06-24 13:02:14'),(3,3,'DEV-2026-0077','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-0077&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-0077',7,'2026-06-24 15:26:58'),(4,4,'DEV-2026-8003','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-8003&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-8003',7,'2026-06-24 15:31:15'),(5,5,'DEV-2026-9345','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-9345&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-9345',7,'2026-06-24 15:45:57'),(6,6,'DEV-2026-0340','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-0340&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-0340',7,'2026-06-24 16:03:29');
/*!40000 ALTER TABLE `device_qr_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device_statuses`
--

DROP TABLE IF EXISTS `device_statuses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device_statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `is_final` tinyint(1) DEFAULT 0,
  `color` varchar(20) DEFAULT '#3b82f6',
  `icon` varchar(50) DEFAULT 'fa-circle',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_statuses`
--

LOCK TABLES `device_statuses` WRITE;
/*!40000 ALTER TABLE `device_statuses` DISABLE KEYS */;
INSERT INTO `device_statuses` VALUES (1,'معلق (Pending)','pending',0,'#f59e0b','fa-clock','2026-06-24 12:38:08'),(2,'تحت الفحص (Under Inspection)','inspection',0,'#3b82f6','fa-search','2026-06-24 12:38:08'),(3,'تحت الإصلاح (Under Repair)','repairing',0,'#8b5cf6','fa-tools','2026-06-24 12:38:08'),(4,'معلق بانتظار قطعة (Suspended)','suspended',0,'#ef4444','fa-pause','2026-06-24 12:38:08'),(5,'جاهز للتسليم (Ready)','ready',0,'#22c55e','fa-check-circle','2026-06-24 12:38:08'),(6,'تم التسليم (Delivered)','delivered',1,'#94a3b8','fa-check-double','2026-06-24 12:38:08'),(7,'ملغي (Cancelled)','cancelled',1,'#ef4444','fa-times-circle','2026-06-24 14:17:35');
/*!40000 ALTER TABLE `device_statuses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device_timeline`
--

DROP TABLE IF EXISTS `device_timeline`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device_timeline` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `started_at` datetime NOT NULL,
  `ended_at` datetime DEFAULT NULL,
  `duration_seconds` int(11) DEFAULT 0,
  `is_paused` tinyint(1) DEFAULT 0,
  `paused_reason` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_device_id` (`device_id`),
  KEY `idx_status_id` (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_timeline`
--

LOCK TABLES `device_timeline` WRITE;
/*!40000 ALTER TABLE `device_timeline` DISABLE KEYS */;
INSERT INTO `device_timeline` VALUES (1,1,1,'2026-06-24 12:43:37','2026-06-24 12:54:20',643,0,NULL,'2026-06-24 12:43:37'),(2,1,2,'2026-06-24 12:54:20','2026-06-24 13:00:37',377,0,NULL,'2026-06-24 12:54:20'),(3,1,4,'2026-06-24 13:00:37','2026-06-24 13:51:42',3065,1,'شاشة انفنكس','2026-06-24 13:00:37'),(4,2,1,'2026-06-24 13:02:14','2026-06-24 13:45:15',2581,0,NULL,'2026-06-24 13:02:14'),(5,2,2,'2026-06-24 13:45:15','2026-06-24 13:45:57',42,0,NULL,'2026-06-24 13:45:15'),(6,2,4,'2026-06-24 13:45:57','2026-06-24 13:50:49',292,1,'شاشة ريلمي','2026-06-24 13:45:57'),(7,2,3,'2026-06-24 13:50:49','2026-06-24 13:51:56',67,0,NULL,'2026-06-24 13:50:49'),(8,1,3,'2026-06-24 13:51:42','2026-06-24 15:22:00',5418,0,NULL,'2026-06-24 13:51:42'),(9,2,5,'2026-06-24 13:51:56','2026-06-24 14:43:40',3104,0,NULL,'2026-06-24 13:51:56'),(10,2,1,'2026-06-24 14:43:40','2026-06-24 15:02:01',1101,0,NULL,'2026-06-24 14:43:40'),(11,2,7,'2026-06-24 15:02:01',NULL,0,0,NULL,'2026-06-24 15:02:01'),(12,1,5,'2026-06-24 15:22:00','2026-06-24 15:22:22',22,0,NULL,'2026-06-24 15:22:00'),(13,1,1,'2026-06-24 15:22:22','2026-06-24 16:04:11',2509,0,NULL,'2026-06-24 15:22:22'),(14,3,1,'2026-06-24 15:26:58','2026-06-24 15:27:32',34,0,NULL,'2026-06-24 15:26:58'),(15,3,2,'2026-06-24 15:27:32','2026-06-24 15:27:43',11,0,NULL,'2026-06-24 15:27:32'),(16,3,4,'2026-06-24 15:27:43',NULL,0,1,'شاشة اوبو','2026-06-24 15:27:43'),(17,4,1,'2026-06-24 15:31:15','2026-06-24 15:31:24',9,0,NULL,'2026-06-24 15:31:15'),(18,4,2,'2026-06-24 15:31:24','2026-06-24 15:35:20',236,0,NULL,'2026-06-24 15:31:24'),(19,4,1,'2026-06-24 15:35:20','2026-06-24 16:04:16',1736,0,NULL,'2026-06-24 15:35:20'),(20,5,1,'2026-06-24 15:45:57','2026-06-24 15:46:56',59,0,NULL,'2026-06-24 15:45:57'),(21,5,1,'2026-06-24 15:46:56','2026-06-24 15:46:56',0,0,NULL,'2026-06-24 15:46:56'),(22,5,6,'2026-06-24 15:46:56',NULL,0,0,NULL,'2026-06-24 15:46:56'),(23,6,1,'2026-06-24 16:03:29',NULL,0,0,NULL,'2026-06-24 16:03:29'),(24,1,1,'2026-06-24 16:04:11','2026-06-24 16:04:11',0,0,NULL,'2026-06-24 16:04:11'),(25,1,6,'2026-06-24 16:04:11',NULL,0,0,NULL,'2026-06-24 16:04:11'),(26,4,1,'2026-06-24 16:04:16','2026-06-24 16:04:16',0,0,NULL,'2026-06-24 16:04:16'),(27,4,6,'2026-06-24 16:04:16',NULL,0,0,NULL,'2026-06-24 16:04:16');
/*!40000 ALTER TABLE `device_timeline` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `devices`
--

DROP TABLE IF EXISTS `devices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_code` varchar(50) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `color` varchar(30) DEFAULT NULL,
  `storage_capacity` varchar(20) DEFAULT NULL,
  `imei_1` varchar(20) DEFAULT NULL,
  `imei_2` varchar(20) DEFAULT NULL,
  `reported_issue` text NOT NULL,
  `diagnosed_issue` text DEFAULT NULL,
  `current_status_id` int(11) NOT NULL,
  `assigned_technician_id` int(11) DEFAULT NULL,
  `received_by` int(11) NOT NULL,
  `received_at` datetime NOT NULL,
  `waiting_for_part` varchar(100) DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `sale_id` int(11) DEFAULT NULL,
  `is_paid` tinyint(1) DEFAULT 0,
  `pickup_date` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `device_code` (`device_code`),
  KEY `idx_customer_id` (`customer_id`),
  KEY `idx_current_status` (`current_status_id`),
  KEY `idx_technician` (`assigned_technician_id`),
  KEY `idx_deleted_at` (`deleted_at`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `devices`
--

LOCK TABLES `devices` WRITE;
/*!40000 ALTER TABLE `devices` DISABLE KEYS */;
INSERT INTO `devices` VALUES (1,'DEV-2026-2745',1,'انفنكس','14','اسود','128ج',NULL,NULL,'يي',NULL,6,5,7,'2026-06-24 12:43:37',NULL,NULL,NULL,0,'2026-06-24 16:04:11','2026-06-24 16:04:18','2026-06-24 12:43:37','2026-06-24 16:04:11'),(2,'DEV-2026-7787',1,'اوبو','يس','ابيض','128ج',NULL,NULL,'8ع',NULL,7,5,7,'2026-06-24 13:02:14',NULL,'مزاجو',NULL,0,NULL,'2026-06-24 16:02:49','2026-06-24 13:02:14','2026-06-24 15:02:01'),(3,'DEV-2026-0077',1,'هواوي','14','ابيض','128GB',NULL,NULL,'gc',NULL,4,5,7,'2026-06-24 15:26:58','شاشة اوبو',NULL,NULL,0,NULL,'2026-06-24 15:48:22','2026-06-24 15:26:58','2026-06-24 15:27:43'),(4,'DEV-2026-8003',1,'oppo','a52','ابيض','128ج',NULL,NULL,'ثسب',NULL,6,5,7,'2026-06-24 15:31:15',NULL,NULL,NULL,0,'2026-06-24 16:04:16',NULL,'2026-06-24 15:31:15','2026-06-24 16:04:16'),(5,'DEV-2026-9345',6,'1+','1+','baby blue','256ج',NULL,NULL,'j',NULL,6,5,7,'2026-06-24 15:45:57',NULL,NULL,NULL,0,'2026-06-24 15:46:56','2026-06-24 16:03:09','2026-06-24 15:45:57','2026-06-24 15:46:56'),(6,'DEV-2026-0340',6,'شاومي','14','baby blue','128GB',NULL,NULL,'ق',NULL,2,5,7,'2026-06-24 16:03:29',NULL,NULL,NULL,0,NULL,NULL,'2026-06-24 16:03:29','2026-06-24 16:03:56');
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `internal_messages`
--

LOCK TABLES `internal_messages` WRITE;
/*!40000 ALTER TABLE `internal_messages` DISABLE KEYS */;
INSERT INTO `internal_messages` VALUES (1,7,NULL,'رسالة فورية','السلام عليكم',NULL,NULL,1,'2026-06-22 22:43:27','2026-06-22 19:14:10'),(2,5,NULL,'رسالة فورية','توفرت الشاشة الاوبو؟؟؟',NULL,NULL,1,'2026-06-25 03:00:11','2026-06-23 13:59:11');
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
  `barcode` varchar(50) DEFAULT NULL,
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
  KEY `idx_supplier_id` (`supplier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory`
--

LOCK TABLES `inventory` WRITE;
/*!40000 ALTER TABLE `inventory` DISABLE KEYS */;
INSERT INTO `inventory` VALUES (1,'SKU-2026-7005',NULL,'شاشة سامسونج','شاشة',NULL,NULL,1,'احمد جوهر',200.00,300.00,0.00,3,4,0,'قطعة','رف2',1,'2026-06-20 02:46:02','2026-06-21 15:40:07',NULL),(7,'SKU-2026-3162','85','شاشة شاومي','شاشة',NULL,NULL,1,'احمد جوهر',500.00,700.00,0.00,2,13,0,'قطعة','رف2',1,'2026-06-22 05:19:09','2026-06-22 09:15:39',NULL),(12,'SKU-2026-1235','511','شاشة اوبو','شاشة',NULL,NULL,1,'احمد جوهر',500.00,700.00,0.00,2,7,0,'قطعة','1',1,'2026-06-22 05:54:07','2026-06-23 13:05:13',NULL),(13,'SKU-2026-1078','54','فلاتة باور','IC',NULL,NULL,1,'احمد جوهر',200.00,300.00,0.00,2,8,0,'قطعة','رف2',1,'2026-06-22 19:43:16','2026-06-22 19:44:35',NULL),(14,'SKU-20260623-0884',NULL,'شاشة ايفون','شاشة',NULL,NULL,NULL,NULL,400.00,500.00,0.00,2,9,0,'قطعة',NULL,1,'2026-06-23 11:46:46','2026-06-23 13:05:21',NULL),(15,'SKU-20260623-0148',NULL,'فلاتة باور','IC',NULL,NULL,NULL,NULL,200.00,300.00,0.00,2,10,0,'قطعة',NULL,1,'2026-06-23 11:48:54','2026-06-23 11:48:54',NULL),(16,'SKU-20260623-7485',NULL,'شاشة هواوي','شاشة',NULL,NULL,NULL,NULL,200.00,300.00,0.00,2,10,0,'قطعة',NULL,1,'2026-06-23 11:50:08','2026-06-23 11:50:08',NULL),(17,'SKU-20260623-4806',NULL,'شاشة ريلمي','شاشة',NULL,NULL,NULL,NULL,200.00,300.00,0.00,2,10,0,'قطعة',NULL,1,'2026-06-23 11:50:08','2026-06-23 11:50:08',NULL),(19,'SKU-20260623-3891',NULL,'فلاتة صوت','IC',NULL,NULL,1,'احمد جوهر',50.00,70.00,0.00,2,10,0,'قطعة',NULL,1,'2026-06-23 12:44:45','2026-06-23 12:44:45',NULL),(20,'SKU-20260623-4523',NULL,'فلاتة بطارية','IC',NULL,NULL,1,'احمد جوهر',50.00,70.00,0.00,2,10,0,'قطعة',NULL,1,'2026-06-23 12:44:45','2026-06-23 12:44:45',NULL),(23,'SKU-20260623-8711','25','بطارية ايفون','بطارية',NULL,NULL,1,'احمد جوهر',12.00,21.00,0.00,5,1,0,'قطعة','2',1,'2026-06-23 13:00:58','2026-06-23 13:01:25',NULL),(24,'SKU-20260623-3922','5','بطارية انفنكس','بطارية',NULL,NULL,1,'احمد جوهر',100.00,200.00,0.00,2,10,0,'قطعة','2',1,'2026-06-23 13:20:30','2026-06-23 13:20:30',NULL),(25,'SKU-20260624-1169','7','شاشة انفنكس','شاشة',NULL,NULL,1,'احمد جوهر',400.00,500.00,0.00,2,10,0,'قطعة','2',1,'2026-06-24 10:01:50','2026-06-24 10:01:50',NULL);
/*!40000 ALTER TABLE `inventory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_count_items`
--

DROP TABLE IF EXISTS `inventory_count_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_count_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `count_id` int(11) NOT NULL,
  `inventory_id` int(11) NOT NULL,
  `system_quantity` int(11) NOT NULL DEFAULT 0,
  `actual_quantity` int(11) NOT NULL DEFAULT 0,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_count_item` (`count_id`,`inventory_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_count_items`
--

LOCK TABLES `inventory_count_items` WRITE;
/*!40000 ALTER TABLE `inventory_count_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory_count_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_counts`
--

DROP TABLE IF EXISTS `inventory_counts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_counts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `started_by` int(11) DEFAULT NULL,
  `completed_by` int(11) DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `status` enum('in_progress','completed','cancelled') DEFAULT 'in_progress',
  `total_differences` int(11) DEFAULT 0,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_counts`
--

LOCK TABLES `inventory_counts` WRITE;
/*!40000 ALTER TABLE `inventory_counts` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory_counts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_movements`
--

DROP TABLE IF EXISTS `inventory_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory_movements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inventory_id` int(11) NOT NULL,
  `movement_type` enum('purchase','sale','repair_use','adjustment','return') NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_inventory_id` (`inventory_id`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_movements`
--

LOCK TABLES `inventory_movements` WRITE;
/*!40000 ALTER TABLE `inventory_movements` DISABLE KEYS */;
INSERT INTO `inventory_movements` VALUES (1,1,'purchase',1,'اختبار',1,'2026-06-23 15:34:05'),(3,19,'purchase',10,'فاتورة استلام: 852',7,'2026-06-23 15:44:45'),(4,20,'purchase',10,'فاتورة استلام: 852',7,'2026-06-23 15:44:45'),(6,23,'purchase',1,'فاتورة استلام: 854ه',7,'2026-06-23 16:00:58'),(7,12,'repair_use',1,'استخدمت في صيانة جهاز #26 - ',5,'2026-06-23 16:05:13'),(8,14,'repair_use',1,'استخدمت في صيانة جهاز #23 - ',5,'2026-06-23 16:05:21'),(9,24,'purchase',10,'فاتورة استلام: 852',7,'2026-06-23 16:20:30'),(10,25,'purchase',10,'فاتورة استلام: 852',7,'2026-06-24 13:01:50');
/*!40000 ALTER TABLE `inventory_movements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime NOT NULL,
  `read_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_is_read` (`is_read`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (1,1,'device','❌ إلغاء تصليح','تم إلغاء تصليح الجهاز رقم 2 - السبب: مزاجو','/devices/2',0,'2026-06-24 15:02:01',NULL),(2,1,'device','📱 جهاز جديد','📱 جهاز هواوي 14 من كريم','/devices/3',0,'2026-06-24 15:26:58',NULL),(3,5,'device','📱 جهاز جديد لك','تم توزيع جهاز هواوي 14 عليك','/devices/3',1,'2026-06-24 15:26:58',NULL),(4,1,'inventory','⏳ طلب قطعة غيار','تم طلب قطعة \'شاشة اوبو\' للجهاز رقم 3','/devices/3',0,'2026-06-24 15:27:43',NULL),(5,1,'device','📱 جهاز جديد','📱 جهاز oppo a52 من محمد','/devices/4',0,'2026-06-24 15:31:15',NULL),(6,5,'device','📱 جهاز جديد لك','تم توزيع جهاز oppo a52 عليك','/devices/4',1,'2026-06-24 15:31:15',NULL),(7,5,'inventory','🔧 قطعة غيار متوفرة','القطعة \'شاشة اوبو\' موجودة في المخزون (الكمية: 7). تم تغيير حالة الجهاز إلى جاري الإصلاح.','/devices/4',0,'2026-06-24 15:33:05',NULL),(8,1,'repair','✅ جهاز تم إصلاحه','تم إصلاح الجهاز رقم 4 بواسطة الفني علي فني فك وتقفيل','/devices/4',0,'2026-06-24 15:34:52',NULL),(9,1,'device','📱 جهاز جديد','📱 جهاز 1+ 1+ من كريم','/devices/5',0,'2026-06-24 15:45:57',NULL),(10,5,'device','📱 جهاز جديد لك','تم توزيع جهاز 1+ 1+ عليك','/devices/5',0,'2026-06-24 15:45:57',NULL),(11,5,'inventory','🔧 قطعة غيار متوفرة','القطعة \'شاشة ايفون\' موجودة في المخزون (الكمية: 9). تم تغيير حالة الجهاز إلى جاري الإصلاح.','/devices/5',0,'2026-06-24 15:46:32',NULL),(12,1,'repair','✅ جهاز تم إصلاحه','تم إصلاح الجهاز رقم 5 بواسطة الفني علي فني فك وتقفيل','/devices/5',0,'2026-06-24 15:46:37',NULL),(13,1,'security','🚨 تنبيه أمني','🚨 تحذير أمني!\nالإجراء: delete\nالجدول: devices\nالرقم: 3\nالمستخدم: محمود يونس (admin)\nIP: ::1\nالوقت: 2026-06-24 15:48:22',NULL,0,'2026-06-24 15:48:22',NULL),(14,1,'security','🚨 تنبيه أمني','🚨 تحذير أمني!\nالإجراء: delete\nالجدول: devices\nالرقم: 2\nالمستخدم: محمود يونس (admin)\nIP: ::1\nالوقت: 2026-06-24 16:02:49',NULL,0,'2026-06-24 16:02:49',NULL),(15,1,'security','🚨 تنبيه أمني','🚨 تحذير أمني!\nالإجراء: delete\nالجدول: devices\nالرقم: 5\nالمستخدم: محمود يونس (admin)\nIP: ::1\nالوقت: 2026-06-24 16:03:09',NULL,0,'2026-06-24 16:03:09',NULL),(16,1,'device','📱 جهاز جديد','📱 جهاز شاومي 14 من كريم','/devices/6',0,'2026-06-24 16:03:29',NULL),(17,5,'device','📱 جهاز جديد لك','تم توزيع جهاز شاومي 14 عليك','/devices/6',0,'2026-06-24 16:03:29',NULL),(18,5,'inventory','🔧 قطعة غيار متوفرة','القطعة \'شاشة ايفون\' موجودة في المخزون (الكمية: 9). تم تغيير حالة الجهاز إلى جاري الإصلاح.','/devices/1',0,'2026-06-24 16:03:47',NULL),(19,1,'repair','✅ جهاز تم إصلاحه','تم إصلاح الجهاز رقم 1 بواسطة الفني علي فني فك وتقفيل','/devices/1',0,'2026-06-24 16:03:49',NULL),(20,5,'inventory','🔧 قطعة غيار متوفرة','القطعة \'شاشة انفنكس\' موجودة في المخزون (الكمية: 10). تم تغيير حالة الجهاز إلى جاري الإصلاح.','/devices/4',0,'2026-06-24 16:03:53',NULL),(21,1,'repair','✅ جهاز تم إصلاحه','تم إصلاح الجهاز رقم 4 بواسطة الفني علي فني فك وتقفيل','/devices/4',0,'2026-06-24 16:03:54',NULL),(22,1,'security','🚨 تنبيه أمني','🚨 تحذير أمني!\nالإجراء: delete\nالجدول: devices\nالرقم: 1\nالمستخدم: محمود يونس (admin)\nIP: ::1\nالوقت: 2026-06-24 16:04:18',NULL,0,'2026-06-24 16:04:18',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'admin','view_all','2026-06-21 12:42:50'),(2,'admin','create_all','2026-06-21 12:42:50'),(3,'admin','edit_all','2026-06-21 12:42:50'),(4,'admin','delete_all','2026-06-21 12:42:50'),(5,'admin','manage_users','2026-06-21 12:42:50'),(6,'admin','manage_settings','2026-06-21 12:42:50'),(7,'admin','view_financial','2026-06-21 12:42:50'),(8,'admin','create_invoices','2026-06-21 12:42:50'),(9,'admin','edit_invoices','2026-06-21 12:42:50'),(10,'admin','manage_wallets','2026-06-21 12:42:50'),(11,'admin','create_expenses','2026-06-21 12:42:50'),(12,'admin','view_audit','2026-06-21 12:42:50'),(13,'admin','manage_backup','2026-06-21 12:42:50'),(20,'technician','view_my_devices','2026-06-21 12:42:50'),(21,'technician','update_repair_status','2026-06-21 12:42:50'),(22,'technician','use_parts','2026-06-21 12:42:50'),(23,'reception','create_devices','2026-06-21 12:42:50'),(24,'reception','view_devices','2026-06-21 12:42:50'),(25,'manager','view_all','2026-06-21 12:42:50'),(26,'manager','create_all','2026-06-21 12:42:50'),(27,'manager','edit_all','2026-06-21 12:42:50'),(28,'manager','view_financial','2026-06-21 12:42:50'),(29,'manager','view_audit','2026-06-21 12:42:50'),(30,'admin','view_reports','2026-06-21 13:29:25'),(31,'admin','export_reports','2026-06-21 13:29:25'),(33,'manager','view_reports','2026-06-21 13:29:25'),(40,'admin','manage_inventory','2026-06-21 15:29:09'),(41,'admin','manage_inventory_count','2026-06-21 15:29:09'),(42,'manager','manage_inventory','2026-06-21 15:29:09'),(43,'manager','manage_inventory_count','2026-06-21 15:29:09'),(44,'admin','view_users','2026-06-21 16:15:51'),(45,'admin','create_users','2026-06-21 16:15:51'),(46,'admin','edit_users','2026-06-21 16:15:51'),(47,'admin','delete_users','2026-06-21 16:15:51'),(48,'admin','view_attendance','2026-06-22 04:50:50'),(49,'admin','edit_attendance','2026-06-22 04:50:50'),(50,'admin','export_attendance','2026-06-22 04:50:50'),(51,'manager','view_attendance','2026-06-22 04:50:50'),(52,'admin','manage_roles','2026-06-22 13:35:13'),(56,'technician','view_parts','2026-06-22 13:35:13'),(57,'reception','view_customers','2026-06-22 13:35:13'),(58,'reception','create_customers','2026-06-22 13:35:13'),(59,'reception','view_my_devices','2026-06-22 13:35:13'),(60,'manager','view_invoices','2026-06-22 13:35:13'),(61,'manager','edit_invoices','2026-06-22 13:35:13'),(62,'manager','edit_attendance','2026-06-22 13:35:13'),(83,'accountant','create_expenses','2026-06-22 13:42:43'),(84,'accountant','create_invoices','2026-06-22 13:42:43'),(85,'accountant','delete_invoices','2026-06-22 13:42:43'),(86,'accountant','edit_invoices','2026-06-22 13:42:43'),(87,'accountant','manage_wallets','2026-06-22 13:42:43'),(88,'accountant','view_expenses','2026-06-22 13:42:43'),(89,'accountant','view_financial','2026-06-22 13:42:43'),(90,'accountant','view_invoices','2026-06-22 13:42:43'),(91,'accountant','view_reports','2026-06-22 13:42:43'),(92,'accountant','view_wallets','2026-06-22 13:42:43'),(94,'technician','view_technician_dashboard','2026-06-22 17:56:40'),(95,'sales','view_invoices','2026-06-22 17:56:40'),(96,'sales','create_invoices','2026-06-22 17:56:40'),(97,'sales','view_customers','2026-06-22 17:56:40'),(98,'sales','create_customers','2026-06-22 17:56:40'),(99,'technician','view_devices','2026-06-23 10:35:22'),(100,'technician','edit_devices','2026-06-23 10:35:22'),(101,'technician','view_attendance','2026-06-23 10:37:41'),(102,'technician','edit_attendance','2026-06-23 10:37:41');
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
  `total_items` int(11) DEFAULT 0,
  `quantity` int(11) NOT NULL,
  `received_quantity` int(11) NOT NULL,
  `discrepancy` int(11) NOT NULL DEFAULT 0,
  `created_by` int(11) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_invoices`
--

LOCK TABLES `purchase_invoices` WRITE;
/*!40000 ALTER TABLE `purchase_invoices` DISABLE KEYS */;
INSERT INTO `purchase_invoices` VALUES (1,'854ه','2026-06-20',1,0,0,7,0,1,'2026-06-20 02:46:02'),(7,'864','2026-06-22',1,0,10,10,0,7,'2026-06-22 05:19:09'),(12,'481','2026-06-22',1,0,10,10,0,7,'2026-06-22 05:54:07'),(13,'987','2026-06-22',1,0,10,10,0,1,'2026-06-22 19:43:16'),(14,'854ه','2026-06-23',1,1,10,10,0,7,'2026-06-23 12:40:27'),(15,'852','2026-06-23',1,2,20,20,0,7,'2026-06-23 12:44:45'),(16,'852','2026-06-23',1,1,11,11,0,7,'2026-06-23 12:51:46'),(18,'854ه','2026-06-23',1,1,1,1,0,7,'2026-06-23 13:00:58'),(19,'852','2026-06-23',1,1,10,10,0,7,'2026-06-23 13:20:30'),(20,'852','2026-06-24',1,1,10,10,0,7,'2026-06-24 10:01:50');
/*!40000 ALTER TABLE `purchase_invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `repair_jobs`
--

DROP TABLE IF EXISTS `repair_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `repair_jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) NOT NULL,
  `technician_id` int(11) NOT NULL,
  `technician_type_id` int(11) DEFAULT NULL,
  `inventory_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `job_description` text DEFAULT NULL,
  `started_at` datetime NOT NULL,
  `completed_at` datetime DEFAULT NULL,
  `is_completed` tinyint(1) DEFAULT 0,
  `parts_used` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_device_id` (`device_id`),
  KEY `idx_technician_id` (`technician_id`),
  KEY `idx_completed` (`is_completed`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `repair_jobs`
--

LOCK TABLES `repair_jobs` WRITE;
/*!40000 ALTER TABLE `repair_jobs` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sale_items`
--

LOCK TABLES `sale_items` WRITE;
/*!40000 ALTER TABLE `sale_items` DISABLE KEYS */;
INSERT INTO `sale_items` VALUES (1,1,'part',1,'شاشة سامسونج',1,300.00,300.00,NULL,NULL),(4,4,'part',1,'شاشة سامسونج',1,300.00,300.00,NULL,NULL),(7,7,'part',1,'شاشة سامسونج',1,300.00,300.00,NULL,NULL),(8,8,'part',1,'شاشة سامسونج',1,300.00,300.00,NULL,NULL),(9,9,'part',1,'شاشة سامسونج',1,300.00,300.00,NULL,NULL),(10,10,'part',1,'شاشة سامسونج',1,300.00,300.00,NULL,NULL),(12,12,'part',12,'شاشة اوبو',1,700.00,700.00,NULL,NULL),(13,13,'part',12,'شاشة اوبو',1,700.00,700.00,NULL,NULL),(33,33,'part',7,'شاشة شاومي',1,700.00,700.00,NULL,NULL),(34,34,'part',7,'شاشة شاومي',1,700.00,700.00,NULL,NULL),(35,35,'part',13,'فلاتة باور',1,300.00,300.00,NULL,NULL),(36,36,'part',13,'فلاتة باور',1,300.00,300.00,NULL,NULL),(37,37,'part',12,'شاشة اوبو',1,700.00,700.00,NULL,NULL),(38,38,'part',14,'شاشة ايفون',1,500.00,500.00,NULL,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES (1,'INV-2026-45633',1,1,'2026-06-20 05:47:22',300.00,0.00,0.00,300.00,300.00,0.00,'cash',NULL,'completed','','2026-06-20 02:47:22','2026-06-21 13:00:24',NULL),(4,'INV-2026-14606',7,5,'2026-06-20 08:19:14',300.00,0.00,0.00,300.00,300.00,0.00,'cash',NULL,'completed','فاتورة صيانة للجهاز DEV-2026-0725 - اوبو 1+','2026-06-20 05:19:14','2026-06-21 13:06:19',NULL),(7,'INV-2026-43886',8,5,'2026-06-20 08:27:14',300.00,0.00,0.00,300.00,300.00,0.00,'cash',NULL,'completed','فاتورة صيانة للجهاز DEV-2026-0782 - اوبو يس','2026-06-20 05:27:14','2026-06-21 13:32:34',NULL),(8,'INV-2026-75516',8,5,'2026-06-20 08:28:13',300.00,0.00,0.00,300.00,300.00,0.00,'cash',NULL,'completed','فاتورة صيانة للجهاز DEV-2026-4514 - 1+ يس','2026-06-20 05:28:13','2026-06-21 15:47:54',NULL),(9,'INV-2026-80149',9,5,'2026-06-20 08:49:10',300.00,0.00,0.00,300.00,300.00,0.00,'cash',NULL,'completed','فاتورة صيانة للجهاز DEV-2026-0311 - اوبو 1+','2026-06-20 05:49:10','2026-06-21 15:48:33',NULL),(10,'INV-2026-51036',7,3,'2026-06-21 15:31:54',300.00,0.00,0.00,300.00,300.00,0.00,'cash',NULL,'completed','فاتورة صيانة للجهاز DEV-2026-0725 - اوبو 1+','2026-06-21 12:31:54','2026-06-22 06:10:40',NULL),(11,'INV-2026-87787',1,5,'2026-06-22 08:08:57',700.00,0.00,0.00,700.00,700.00,0.00,'cash',NULL,'completed','فاتورة صيانة للجهاز DEV-2026-0551 - شاومي 14','2026-06-22 05:08:57','2026-06-22 19:56:37',NULL),(12,'INV-2026-23592',1,7,'2026-06-22 09:15:35',700.00,0.00,0.00,700.00,0.00,0.00,'cash',NULL,'completed','','2026-06-22 06:15:35','2026-06-22 06:15:35',NULL),(13,'INV-2026-39902',1,7,'2026-06-22 09:30:18',700.00,0.00,0.00,700.00,0.00,200.00,'cash',NULL,'pending','','2026-06-22 06:30:18','2026-06-22 06:30:18',NULL),(33,'INV-2026-44743',1,5,'2026-06-22 11:57:53',700.00,0.00,0.00,700.00,700.00,700.00,'cash',NULL,'completed','فاتورة صيانة للجهاز DEV-2026-7612 - شاومي يس','2026-06-22 08:57:53','2026-06-22 09:01:00',NULL),(34,'INV-2026-31454',1,5,'2026-06-22 12:15:39',700.00,0.00,0.00,700.00,0.00,700.00,'cash',NULL,'pending','فاتورة صيانة للجهاز DEV-2026-0913 - اوبو يس','2026-06-22 09:15:39','2026-06-22 09:15:39',NULL),(35,'INV-2026-82276',1,5,'2026-06-22 22:44:17',300.00,0.00,0.00,300.00,0.00,300.00,'cash',NULL,'pending','فاتورة صيانة للجهاز DEV-2026-7038 - SAM A51','2026-06-22 19:44:17','2026-06-22 19:44:17',NULL),(36,'INV-2026-92626',1,5,'2026-06-22 22:44:35',300.00,0.00,0.00,300.00,0.00,300.00,'cash',NULL,'pending','فاتورة صيانة للجهاز DEV-2026-7919 - ايفون 1+','2026-06-22 19:44:35','2026-06-22 19:44:35',NULL),(37,'INV-2026-12328',1,5,'2026-06-23 16:05:13',700.00,0.00,0.00,700.00,0.00,700.00,'cash',NULL,'pending','فاتورة صيانة للجهاز DEV-2026-1833 - اوبو يس','2026-06-23 13:05:13','2026-06-23 13:05:13',NULL),(38,'INV-2026-07146',1,5,'2026-06-23 16:05:21',500.00,0.00,0.00,500.00,0.00,500.00,'cash',NULL,'pending','فاتورة صيانة للجهاز DEV-2026-3058 - ايفون 11برو ماكس','2026-06-23 13:05:21','2026-06-23 13:05:21',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shifts`
--

LOCK TABLES `shifts` WRITE;
/*!40000 ALTER TABLE `shifts` DISABLE KEYS */;
INSERT INTO `shifts` VALUES (1,1,'مدير النظام','2026-06-20 05:13:58','2026-06-20 06:05:06','archived',0,'2026-06-20 02:13:58',NULL),(2,7,'محمود يونس','2026-06-22 08:05:40','2026-06-22 20:12:53','closed',0,'2026-06-22 05:05:40',NULL),(3,7,'محمود يونس','2026-06-22 20:12:53','2026-06-22 22:13:35','closed',0,'2026-06-22 17:12:53',NULL),(4,7,'محمود يونس','2026-06-22 22:13:35','2026-06-23 12:23:50','closed',0,'2026-06-22 19:13:35',NULL),(5,7,'محمود يونس','2026-06-23 12:23:50',NULL,'active',0,'2026-06-23 09:23:50',NULL);
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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `device_id` int(11) NOT NULL,
  `from_technician_id` int(11) DEFAULT NULL,
  `to_technician_id` int(11) NOT NULL,
  `from_technician_type_id` int(11) DEFAULT NULL,
  `to_technician_type_id` int(11) DEFAULT NULL,
  `transfer_reason` varchar(255) DEFAULT NULL,
  `transfer_notes` text DEFAULT NULL,
  `transferred_by` int(11) DEFAULT NULL,
  `is_auto_transfer` tinyint(1) DEFAULT 0,
  `transferred_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_device_id` (`device_id`),
  KEY `idx_from_technician` (`from_technician_id`),
  KEY `idx_to_technician` (`to_technician_id`),
  KEY `idx_transferred_at` (`transferred_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `technician_transfers`
--

LOCK TABLES `technician_transfers` WRITE;
/*!40000 ALTER TABLE `technician_transfers` DISABLE KEYS */;
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
INSERT INTO `users` VALUES (1,'admin','admin@system.com','$2y$10$gExaRVL6VreKNtLmtb5peetJuoIVe5qkP5COzoaKGoHsqtGc6HTde','مدير النظام','01000000000','admin',1,'2026-06-23 16:06:33','2026-06-20 02:02:45','2026-06-23 13:06:33','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL),(2,'accountant','accountant@system.com','$2y$10$i0Pd96jNUJz8SvlY12Ilt.XyUIXUqmNpMlAM3Xv7j2iLa.k5Yg6eu','محاسب النظام','01055555555','accountant',1,'2026-06-22 22:47:35','2026-06-20 02:02:45','2026-06-22 19:47:35','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL),(3,'tech1','tech1@system.com','$2y$10$tR7wW76KLGAmhQQO88aLkuKcbzMZ4aDbbmXIVeb4rhG.egQioXhG6','أحمد فني بوردة','01011111111','technician',1,'2026-06-22 22:49:02','2026-06-20 02:02:45','2026-06-22 19:49:02','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL),(4,'tech2','tech2@system.com','$2y$10$EweIWL8SH3wJYZKtEj0VOe0qK/XHTbqeeKdAn1h212F9wgZvQk4P6','محمد فني سوفت وير','01022222222','technician',1,NULL,'2026-06-20 02:02:45','2026-06-21 16:25:13',NULL,NULL,NULL),(5,'tech3','tech3@system.com','$2y$10$aFGlhb8AgmQ7DF5Cjinl7.Ib96Fu3IjinF1ufJBOO8Go6MhRqM3NO','علي فني فك وتقفيل','01033333333','technician',1,'2026-06-24 16:03:33','2026-06-20 02:02:45','2026-06-24 13:03:33','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL),(7,'الشيخ محمود','karimelsha3er222@gmail.com','$2y$10$WIKUBmZ37ajodufc/JVNHeuG4YRyt48ijsXYKtKpLvCNffrK1poxC','محمود يونس','+201016413643','admin',1,'2026-06-25 02:57:48','2026-06-21 16:18:05','2026-06-24 23:57:48','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL);
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

-- Dump completed on 2026-06-25  3:09:29
