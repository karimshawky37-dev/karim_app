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
  `shift_id` int(11) unsigned DEFAULT NULL,
  `check_in` datetime NOT NULL,
  `check_out` datetime DEFAULT NULL,
  `status` enum('present','absent','late','half_day','holiday','weekend','leave') DEFAULT 'present',
  `working_hours` decimal(5,2) DEFAULT NULL,
  `is_modified` tinyint(1) DEFAULT 0,
  `modified_by` int(10) unsigned DEFAULT NULL,
  `modified_at` timestamp NULL DEFAULT NULL,
  `modification_reason` text DEFAULT NULL,
  `shift_type` varchar(20) DEFAULT NULL,
  `check_in_ip` varchar(45) DEFAULT NULL,
  `check_out_ip` varchar(45) DEFAULT NULL,
  `check_in_device` text DEFAULT NULL,
  `check_out_device` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_attendance_index` (`user_id`),
  KEY `shift_attendance_index` (`shift_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendance`
--

LOCK TABLES `attendance` WRITE;
/*!40000 ALTER TABLE `attendance` DISABLE KEYS */;
INSERT INTO `attendance` VALUES (1,1,NULL,'2026-07-10 05:17:42',NULL,'present',NULL,0,NULL,NULL,NULL,NULL,'::1',NULL,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL,NULL,'2026-07-10 02:17:42');
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
  `old_data` longtext DEFAULT NULL,
  `new_data` longtext DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `shift_audit_index` (`shift_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,7,NULL,'محمود يونس','admin','logout','users',7,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-07 16:05:22'),(2,1,NULL,'مدير النظام','admin','login','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-07 16:05:37'),(3,1,NULL,'مدير النظام','admin','login','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 02:16:07'),(4,1,NULL,'مدير النظام','admin','logout','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 02:16:19'),(5,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 02:16:23'),(6,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 02:16:49'),(7,1,NULL,'مدير النظام','admin','login','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 02:16:53'),(8,1,NULL,'مدير النظام','admin','create','attendance',1,NULL,'{\"user_id\":1,\"action\":\"check_in\",\"status\":\"present\",\"shift_type\":null}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 02:17:42'),(9,1,NULL,'مدير النظام','admin','create','devices',1,NULL,'{\"device_code\":\"DEV-2026-6552\",\"customer_id\":\"1\",\"brand\":\"1+\",\"model\":\"يس\",\"status\":\"pending\",\"waiting_for_part\":null}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 02:23:35'),(10,1,NULL,'مدير النظام','admin','logout','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 02:25:49'),(11,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 02:25:54'),(12,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 02:26:43'),(13,1,NULL,'مدير النظام','admin','login','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 02:26:48'),(14,1,NULL,'مدير النظام','admin','logout','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 02:27:57'),(15,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 02:28:01'),(16,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 02:28:54'),(17,1,NULL,'مدير النظام','admin','login','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 02:28:58'),(18,1,NULL,'مدير النظام','admin','logout','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 02:29:58'),(19,5,NULL,'علي فني فك وتقفيل','technician','login','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 02:30:02'),(20,5,NULL,'علي فني فك وتقفيل','technician','logout','users',5,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 02:30:12'),(21,1,NULL,'مدير النظام','admin','login','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 02:30:16'),(22,1,NULL,'مدير النظام','admin','update','sales',13,'{\"paid_amount\":\"200.00\",\"status\":\"partially_paid\"}','{\"paid_amount\":600,\"status\":\"partially_paid\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 02:32:42'),(23,1,NULL,'مدير النظام','admin','create','inventory_count',0,NULL,'{\"notes\":\"\",\"differences\":[]}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36','2026-07-10 02:35:05'),(24,1,NULL,'مدير النظام','admin','login','users',1,NULL,'{\"status\":\"success\"}','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36','2026-07-10 16:45:49');
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
  `old_data` longtext DEFAULT NULL,
  `new_data` longtext DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `archived_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `shift_audit_index` (`shift_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs_archive`
--

LOCK TABLES `audit_logs_archive` WRITE;
/*!40000 ALTER TABLE `audit_logs_archive` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'Karim Ahmed','01029169762',NULL,NULL,NULL,NULL,'2026-07-10 02:23:35','2026-07-10 02:23:35',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_checklist`
--

LOCK TABLES `device_checklist` WRITE;
/*!40000 ALTER TABLE `device_checklist` DISABLE KEYS */;
INSERT INTO `device_checklist` VALUES (1,1,'before','{\"screen_condition\":\"good\",\"has_scratches\":0,\"buttons_working\":0,\"camera_lens\":\"good\",\"charging_port\":0,\"speaker_working\":0,\"mic_working\":0,\"notes\":\"\"}',7,'2026-06-24 12:43:37',NULL,NULL),(2,2,'before','{\"screen_condition\":\"good\",\"has_scratches\":0,\"buttons_working\":0,\"camera_lens\":\"good\",\"charging_port\":0,\"speaker_working\":0,\"mic_working\":0,\"notes\":\"\"}',7,'2026-06-24 13:02:14',NULL,NULL),(3,3,'before','{\"screen_condition\":\"good\",\"has_scratches\":0,\"buttons_working\":0,\"camera_lens\":\"good\",\"charging_port\":0,\"speaker_working\":0,\"mic_working\":0,\"notes\":\"\"}',7,'2026-06-24 15:26:58',NULL,NULL),(4,4,'before','{\"screen_condition\":\"good\",\"has_scratches\":0,\"buttons_working\":0,\"camera_lens\":\"good\",\"charging_port\":0,\"speaker_working\":0,\"mic_working\":0,\"notes\":\"\"}',7,'2026-06-24 15:31:15',NULL,NULL),(5,5,'before','{\"screen_condition\":\"good\",\"has_scratches\":0,\"buttons_working\":0,\"camera_lens\":\"good\",\"charging_port\":0,\"speaker_working\":0,\"mic_working\":0,\"notes\":\"\"}',7,'2026-06-24 15:45:57',NULL,NULL),(6,5,'after','{\"screen_condition\":\"good\",\"has_scratches\":0,\"buttons_working\":0,\"camera_lens\":\"good\",\"charging_port\":0,\"speaker_working\":0,\"mic_working\":0,\"customer_signature\":\"\",\"notes\":\"\"}',7,'2026-06-24 15:46:56','',''),(7,6,'before','{\"screen_condition\":\"good\",\"has_scratches\":0,\"buttons_working\":0,\"camera_lens\":\"good\",\"charging_port\":0,\"speaker_working\":0,\"mic_working\":0,\"notes\":\"\"}',7,'2026-06-24 16:03:29',NULL,NULL),(8,1,'after','{\"screen_condition\":\"good\",\"has_scratches\":0,\"buttons_working\":0,\"camera_lens\":\"good\",\"charging_port\":0,\"speaker_working\":0,\"mic_working\":0,\"customer_signature\":\"\",\"notes\":\"\"}',7,'2026-06-24 16:04:11','',''),(9,4,'after','{\"screen_condition\":\"good\",\"has_scratches\":0,\"buttons_working\":0,\"camera_lens\":\"good\",\"charging_port\":0,\"speaker_working\":0,\"mic_working\":0,\"customer_signature\":\"\",\"notes\":\"\"}',7,'2026-06-24 16:04:16','',''),(10,7,'before','{\"screen_condition\":\"good\",\"has_scratches\":0,\"buttons_working\":0,\"camera_lens\":\"good\",\"charging_port\":0,\"speaker_working\":0,\"mic_working\":0,\"notes\":\"\"}',7,'2026-06-25 03:10:36',NULL,NULL),(11,7,'after','{\"screen_condition\":\"good\",\"has_scratches\":0,\"buttons_working\":0,\"camera_lens\":\"good\",\"charging_port\":0,\"speaker_working\":0,\"mic_working\":0,\"customer_signature\":\"\",\"notes\":\"\"}',7,'2026-06-25 03:21:36','',''),(12,6,'after','{\"screen_condition\":\"good\",\"has_scratches\":0,\"buttons_working\":0,\"camera_lens\":\"good\",\"charging_port\":0,\"speaker_working\":0,\"mic_working\":0,\"customer_signature\":\"\",\"notes\":\"\"}',7,'2026-06-25 12:36:36','',''),(13,8,'before','{\"screen_condition\":\"good\",\"has_scratches\":0,\"buttons_working\":0,\"camera_lens\":\"good\",\"charging_port\":0,\"speaker_working\":0,\"mic_working\":0,\"notes\":\"\"}',7,'2026-07-01 00:02:26',NULL,NULL),(14,8,'after','{\"screen_condition\":\"good\",\"has_scratches\":0,\"buttons_working\":0,\"camera_lens\":\"good\",\"charging_port\":0,\"speaker_working\":0,\"mic_working\":0,\"customer_signature\":\"\",\"notes\":\"\"}',7,'2026-07-01 00:05:09','',''),(15,9,'before','{\"screen_condition\":\"good\",\"has_scratches\":0,\"buttons_working\":0,\"camera_lens\":\"good\",\"charging_port\":0,\"speaker_working\":0,\"mic_working\":0,\"notes\":\"\"}',7,'2026-07-01 00:45:03',NULL,NULL);
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
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_device_id` (`device_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_maintenance_log`
--

LOCK TABLES `device_maintenance_log` WRITE;
/*!40000 ALTER TABLE `device_maintenance_log` DISABLE KEYS */;
INSERT INTO `device_maintenance_log` VALUES (1,1,'received','تم استلام الجهاز من Karim Ahmed',7,'2026-06-24 12:43:37','2026-07-03 17:00:40','2026-07-03 17:00:40'),(2,2,'received','تم استلام الجهاز من المهندس عبدالرحمن',7,'2026-06-24 13:02:14','2026-07-03 17:00:40','2026-07-03 17:00:40'),(3,2,'completed','تم إصلاح الجهاز',5,'2026-06-24 13:51:56','2026-07-03 17:00:40','2026-07-03 17:00:40'),(4,2,'cancelled','تم إلغاء التصليح حسب رغبة العميل - السبب: مزاجو',7,'2026-06-24 15:02:01','2026-07-03 17:00:40','2026-07-03 17:00:40'),(5,1,'completed','تم إصلاح الجهاز',5,'2026-06-24 15:22:00','2026-07-03 17:00:40','2026-07-03 17:00:40'),(6,3,'received','تم استلام الجهاز من كريم',7,'2026-06-24 15:26:58','2026-07-03 17:00:40','2026-07-03 17:00:40'),(7,4,'received','تم استلام الجهاز من محمد',7,'2026-06-24 15:31:15','2026-07-03 17:00:40','2026-07-03 17:00:40'),(8,4,'part_available','قطعة غيار متوفرة: شاشة اوبو',5,'2026-06-24 15:33:05','2026-07-03 17:00:40','2026-07-03 17:00:40'),(9,4,'completed','تم إصلاح الجهاز',5,'2026-06-24 15:34:52','2026-07-03 17:00:40','2026-07-03 17:00:40'),(10,5,'received','تم استلام الجهاز من كريم',7,'2026-06-24 15:45:57','2026-07-03 17:00:40','2026-07-03 17:00:40'),(11,5,'start_inspection','بدء فحص الجهاز',5,'2026-06-24 15:46:20','2026-07-03 17:00:40','2026-07-03 17:00:40'),(12,5,'part_available','قطعة غيار متوفرة: شاشة ايفون',5,'2026-06-24 15:46:32','2026-07-03 17:00:40','2026-07-03 17:00:40'),(13,5,'completed','تم إصلاح الجهاز',5,'2026-06-24 15:46:37','2026-07-03 17:00:40','2026-07-03 17:00:40'),(14,6,'received','تم استلام الجهاز من كريم',7,'2026-06-24 16:03:29','2026-07-03 17:00:40','2026-07-03 17:00:40'),(15,1,'start_inspection','بدء فحص الجهاز',5,'2026-06-24 16:03:40','2026-07-03 17:00:40','2026-07-03 17:00:40'),(16,1,'part_available','قطعة غيار متوفرة: شاشة ايفون',5,'2026-06-24 16:03:47','2026-07-03 17:00:40','2026-07-03 17:00:40'),(17,1,'completed','تم إصلاح الجهاز',5,'2026-06-24 16:03:49','2026-07-03 17:00:40','2026-07-03 17:00:40'),(18,4,'start_inspection','بدء فحص الجهاز',5,'2026-06-24 16:03:50','2026-07-03 17:00:40','2026-07-03 17:00:40'),(19,4,'part_available','قطعة غيار متوفرة: شاشة انفنكس',5,'2026-06-24 16:03:53','2026-07-03 17:00:40','2026-07-03 17:00:40'),(20,4,'completed','تم إصلاح الجهاز',5,'2026-06-24 16:03:54','2026-07-03 17:00:40','2026-07-03 17:00:40'),(21,6,'start_inspection','بدء فحص الجهاز',5,'2026-06-24 16:03:56','2026-07-03 17:00:40','2026-07-03 17:00:40'),(22,7,'received','تم استلام الجهاز من ايمان',7,'2026-06-25 03:10:36','2026-07-03 17:00:40','2026-07-03 17:00:40'),(23,7,'start_inspection','بدء فحص الجهاز',5,'2026-06-25 03:10:55','2026-07-03 17:00:40','2026-07-03 17:00:40'),(24,7,'part_available','قطعة غيار متوفرة: بطارية ايفون',5,'2026-06-25 03:11:13','2026-07-03 17:00:40','2026-07-03 17:00:40'),(25,6,'request_part','طلب قطعة غيار: بطارية هونور',5,'2026-06-25 03:11:31','2026-07-03 17:00:40','2026-07-03 17:00:40'),(26,7,'completed','تم إصلاح الجهاز',5,'2026-06-25 03:12:21','2026-07-03 17:00:40','2026-07-03 17:00:40'),(27,6,'parts_arrived','قطعة غيار متوفرة: بطارية هونور',7,'2026-06-25 03:14:27','2026-07-03 17:00:40','2026-07-03 17:00:40'),(28,6,'completed','تم إصلاح الجهاز',5,'2026-06-25 03:14:55','2026-07-03 17:00:40','2026-07-03 17:00:40'),(29,8,'received','تم استلام الجهاز من كريم',7,'2026-07-01 00:02:26','2026-07-03 17:00:40','2026-07-03 17:00:40'),(30,8,'start_inspection','بدء فحص الجهاز',5,'2026-07-01 00:02:45','2026-07-03 17:00:40','2026-07-03 17:00:40'),(31,8,'request_part','طلب قطعة غيار: فلاتة سوكيت',5,'2026-07-01 00:03:08','2026-07-03 17:00:40','2026-07-03 17:00:40'),(32,8,'parts_arrived','قطعة غيار متوفرة: فلاتة سوكيت',7,'2026-07-01 00:04:02','2026-07-03 17:00:40','2026-07-03 17:00:40'),(33,8,'completed','تم إصلاح الجهاز',5,'2026-07-01 00:04:22','2026-07-03 17:00:40','2026-07-03 17:00:40'),(34,9,'received','تم استلام الجهاز من كريم',7,'2026-07-01 00:45:03','2026-07-03 17:00:40','2026-07-03 17:00:40'),(35,9,'start_inspection','بدء فحص الجهاز',5,'2026-07-01 00:54:01','2026-07-03 17:00:40','2026-07-03 17:00:40'),(36,10,'received','تم استلام الجهاز من كريم',7,'2026-07-06 18:06:06','2026-07-06 15:06:06','2026-07-06 15:06:06'),(37,11,'received','تم استلام الجهاز من كريم',7,'2026-07-06 18:14:57','2026-07-06 15:14:57','2026-07-06 15:14:57'),(38,12,'received','تم استلام الجهاز من محم',1,'2026-07-06 18:43:40','2026-07-06 15:43:40','2026-07-06 15:43:40'),(39,13,'received','تم استلام الجهاز من محمد',7,'2026-07-06 18:45:45','2026-07-06 15:45:45','2026-07-06 15:45:45'),(40,14,'received','تم استلام الجهاز من ابو صلاح الرشاش',1,'2026-07-06 21:50:18','2026-07-06 18:50:18','2026-07-06 18:50:18'),(41,15,'received','تم استلام الجهاز من خالد عبدالحميد',1,'2026-07-07 00:40:23','2026-07-06 21:40:23','2026-07-06 21:40:23'),(42,1,'received','تم استلام الجهاز من Karim Ahmed',1,'2026-07-10 05:23:35','2026-07-10 02:23:35','2026-07-10 02:23:35'),(43,1,'start_inspection','بدء فحص الجهاز',5,'2026-07-10 05:26:03','2026-07-10 02:26:03','2026-07-10 02:26:03'),(44,1,'request_part','طلب قطعة غيار: شاشة ايتيل',5,'2026-07-10 05:26:38','2026-07-10 02:26:38','2026-07-10 02:26:38'),(45,1,'parts_arrived','✅ قطعة غيار متوفرة: شاشة ايتيل',1,'2026-07-10 05:27:47','2026-07-10 02:27:47','2026-07-10 02:27:47'),(46,1,'start_inspection','بدء فحص الجهاز',5,'2026-07-10 05:28:15','2026-07-10 02:28:15','2026-07-10 02:28:15');
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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_qr_codes`
--

LOCK TABLES `device_qr_codes` WRITE;
/*!40000 ALTER TABLE `device_qr_codes` DISABLE KEYS */;
INSERT INTO `device_qr_codes` VALUES (1,1,'DEV-2026-2745','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-2745&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-2745',7,'2026-06-24 12:43:37'),(2,2,'DEV-2026-7787','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-7787&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-7787',7,'2026-06-24 13:02:14'),(3,3,'DEV-2026-0077','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-0077&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-0077',7,'2026-06-24 15:26:58'),(4,4,'DEV-2026-8003','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-8003&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-8003',7,'2026-06-24 15:31:15'),(5,5,'DEV-2026-9345','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-9345&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-9345',7,'2026-06-24 15:45:57'),(6,6,'DEV-2026-0340','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-0340&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-0340',7,'2026-06-24 16:03:29'),(7,7,'DEV-2026-3222','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-3222&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-3222',7,'2026-06-25 03:10:36'),(8,8,'DEV-2026-4319','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-4319&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-4319',7,'2026-07-01 00:02:26'),(9,9,'DEV-2026-4387','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-4387&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-4387',7,'2026-07-01 00:45:03'),(10,10,'DEV-2026-3805','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-3805&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-3805',7,'2026-07-06 18:06:06'),(11,11,'DEV-2026-4328','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-4328&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-4328',7,'2026-07-06 18:14:57'),(12,12,'DEV-2026-7088','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-7088&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-7088',1,'2026-07-06 18:43:40'),(13,13,'DEV-2026-9183','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-9183&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-9183',7,'2026-07-06 18:45:45'),(14,14,'DEV-2026-3576','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-3576&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-3576',1,'2026-07-06 21:50:18'),(15,15,'DEV-2026-7402','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-7402&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-7402',1,'2026-07-07 00:40:23'),(16,1,'DEV-2026-6552','https://api.qrserver.com/v1/create-qr-code/?data=http%3A%2F%2Flocalhost%3A8000%2Ftrack%2FDEV-2026-6552&size=250x250&margin=15','http://localhost:8000/track/DEV-2026-6552',1,'2026-07-10 05:23:35');
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
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_timeline`
--

LOCK TABLES `device_timeline` WRITE;
/*!40000 ALTER TABLE `device_timeline` DISABLE KEYS */;
INSERT INTO `device_timeline` VALUES (1,1,1,'2026-06-24 12:43:37','2026-06-24 12:54:20',643,0,NULL,'2026-06-24 12:43:37'),(2,1,2,'2026-06-24 12:54:20','2026-06-24 13:00:37',377,0,NULL,'2026-06-24 12:54:20'),(3,1,4,'2026-06-24 13:00:37','2026-06-24 13:51:42',3065,1,'شاشة انفنكس','2026-06-24 13:00:37'),(4,2,1,'2026-06-24 13:02:14','2026-06-24 13:45:15',2581,0,NULL,'2026-06-24 13:02:14'),(5,2,2,'2026-06-24 13:45:15','2026-06-24 13:45:57',42,0,NULL,'2026-06-24 13:45:15'),(6,2,4,'2026-06-24 13:45:57','2026-06-24 13:50:49',292,1,'شاشة ريلمي','2026-06-24 13:45:57'),(7,2,3,'2026-06-24 13:50:49','2026-06-24 13:51:56',67,0,NULL,'2026-06-24 13:50:49'),(8,1,3,'2026-06-24 13:51:42','2026-06-24 15:22:00',5418,0,NULL,'2026-06-24 13:51:42'),(9,2,5,'2026-06-24 13:51:56','2026-06-24 14:43:40',3104,0,NULL,'2026-06-24 13:51:56'),(10,2,1,'2026-06-24 14:43:40','2026-06-24 15:02:01',1101,0,NULL,'2026-06-24 14:43:40'),(11,2,7,'2026-06-24 15:02:01',NULL,0,0,NULL,'2026-06-24 15:02:01'),(12,1,5,'2026-06-24 15:22:00','2026-06-24 15:22:22',22,0,NULL,'2026-06-24 15:22:00'),(13,1,1,'2026-06-24 15:22:22','2026-06-24 16:04:11',2509,0,NULL,'2026-06-24 15:22:22'),(14,3,1,'2026-06-24 15:26:58','2026-06-24 15:27:32',34,0,NULL,'2026-06-24 15:26:58'),(15,3,2,'2026-06-24 15:27:32','2026-06-24 15:27:43',11,0,NULL,'2026-06-24 15:27:32'),(16,3,4,'2026-06-24 15:27:43',NULL,0,1,'شاشة اوبو','2026-06-24 15:27:43'),(17,4,1,'2026-06-24 15:31:15','2026-06-24 15:31:24',9,0,NULL,'2026-06-24 15:31:15'),(18,4,2,'2026-06-24 15:31:24','2026-06-24 15:35:20',236,0,NULL,'2026-06-24 15:31:24'),(19,4,1,'2026-06-24 15:35:20','2026-06-24 16:04:16',1736,0,NULL,'2026-06-24 15:35:20'),(20,5,1,'2026-06-24 15:45:57','2026-06-24 15:46:56',59,0,NULL,'2026-06-24 15:45:57'),(21,5,1,'2026-06-24 15:46:56','2026-06-24 15:46:56',0,0,NULL,'2026-06-24 15:46:56'),(22,5,6,'2026-06-24 15:46:56',NULL,0,0,NULL,'2026-06-24 15:46:56'),(23,6,1,'2026-06-24 16:03:29','2026-06-25 12:36:36',73987,0,NULL,'2026-06-24 16:03:29'),(24,1,1,'2026-06-24 16:04:11','2026-06-24 16:04:11',0,0,NULL,'2026-06-24 16:04:11'),(25,1,6,'2026-06-24 16:04:11',NULL,0,0,NULL,'2026-06-24 16:04:11'),(26,4,1,'2026-06-24 16:04:16','2026-06-24 16:04:16',0,0,NULL,'2026-06-24 16:04:16'),(27,4,6,'2026-06-24 16:04:16',NULL,0,0,NULL,'2026-06-24 16:04:16'),(28,7,1,'2026-06-25 03:10:36','2026-06-25 03:21:36',660,0,NULL,'2026-06-25 03:10:36'),(29,7,1,'2026-06-25 03:21:36','2026-06-25 03:21:36',0,0,NULL,'2026-06-25 03:21:36'),(30,7,6,'2026-06-25 03:21:36',NULL,0,0,NULL,'2026-06-25 03:21:36'),(31,6,1,'2026-06-25 12:36:36','2026-06-25 12:36:36',0,0,NULL,'2026-06-25 12:36:36'),(32,6,6,'2026-06-25 12:36:36',NULL,0,0,NULL,'2026-06-25 12:36:36'),(33,8,1,'2026-07-01 00:02:26','2026-07-01 00:05:09',163,0,NULL,'2026-07-01 00:02:26'),(34,8,1,'2026-07-01 00:05:09','2026-07-01 00:05:09',0,0,NULL,'2026-07-01 00:05:09'),(35,8,6,'2026-07-01 00:05:09',NULL,0,0,NULL,'2026-07-01 00:05:09'),(36,9,1,'2026-07-01 00:45:03',NULL,0,0,NULL,'2026-07-01 00:45:03'),(37,10,1,'2026-07-06 18:06:06',NULL,0,0,NULL,'2026-07-06 18:06:06'),(38,11,1,'2026-07-06 18:14:57',NULL,0,0,NULL,'2026-07-06 18:14:57'),(39,12,1,'2026-07-06 18:43:40',NULL,0,0,NULL,'2026-07-06 18:43:40'),(40,13,1,'2026-07-06 18:45:45',NULL,0,0,NULL,'2026-07-06 18:45:45'),(41,14,1,'2026-07-06 21:50:18',NULL,0,0,NULL,'2026-07-06 21:50:18'),(42,15,1,'2026-07-07 00:40:23',NULL,0,0,NULL,'2026-07-07 00:40:23'),(43,1,1,'2026-07-10 05:23:35',NULL,0,0,NULL,'2026-07-10 05:23:35');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `devices`
--

LOCK TABLES `devices` WRITE;
/*!40000 ALTER TABLE `devices` DISABLE KEYS */;
INSERT INTO `devices` VALUES (1,'DEV-2026-6552',1,'1+','يس','ابيض','128ج',NULL,NULL,'ksoei',NULL,2,5,1,'2026-07-10 05:23:35',NULL,NULL,NULL,0,NULL,NULL,'2026-07-10 05:23:35','2026-07-10 05:28:15');
/*!40000 ALTER TABLE `devices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entities`
--

DROP TABLE IF EXISTS `entities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entities` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('customer','supplier','both') NOT NULL DEFAULT 'customer',
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `tax_number` varchar(50) DEFAULT NULL,
  `credit_limit` decimal(15,2) NOT NULL DEFAULT 0.00,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_type` (`type`),
  KEY `idx_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entities`
--

LOCK TABLES `entities` WRITE;
/*!40000 ALTER TABLE `entities` DISABLE KEYS */;
/*!40000 ALTER TABLE `entities` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `internal_messages`
--

LOCK TABLES `internal_messages` WRITE;
/*!40000 ALTER TABLE `internal_messages` DISABLE KEYS */;
INSERT INTO `internal_messages` VALUES (1,7,NULL,'رسالة فورية','السلام عليكم',NULL,NULL,1,'2026-06-22 22:43:27','2026-06-22 19:14:10'),(2,5,NULL,'رسالة فورية','توفرت الشاشة الاوبو؟؟؟',NULL,NULL,1,'2026-06-25 03:00:11','2026-06-23 13:59:11'),(3,5,NULL,'رسالة فورية','نوفرت',NULL,NULL,1,'2026-06-25 03:15:29','2026-06-25 00:15:15');
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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory`
--

LOCK TABLES `inventory` WRITE;
/*!40000 ALTER TABLE `inventory` DISABLE KEYS */;
INSERT INTO `inventory` VALUES (1,'SKU-20260710-6213','25','شاشة ايتيل','شاشة',NULL,NULL,1,'احمد جوهر',200.00,300.00,0.00,2,10,0,'قطعة','رف 2',1,'2026-07-10 02:27:47','2026-07-10 02:27:47',NULL),(2,'SKU-20260710-5523',NULL,'شاشة SAM A51','أخرى',NULL,NULL,1,'احمد جوهر',375.00,425.00,0.00,1,1,0,'قطعة',NULL,1,'2026-07-10 02:39:24','2026-07-10 02:39:24',NULL),(3,'SKU-20260710-2535',NULL,'شاشة SAM A23','أخرى',NULL,NULL,1,'احمد جوهر',375.00,425.00,0.00,5,1,0,'قطعة',NULL,1,'2026-07-10 02:39:24','2026-07-10 02:39:24',NULL),(4,'SKU-20260710-6746',NULL,'شاشة OPPO A1K','أخرى',NULL,NULL,1,'احمد جوهر',350.00,400.00,0.00,5,1,0,'قطعة',NULL,1,'2026-07-10 02:39:24','2026-07-10 02:39:24',NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_movements`
--

LOCK TABLES `inventory_movements` WRITE;
/*!40000 ALTER TABLE `inventory_movements` DISABLE KEYS */;
INSERT INTO `inventory_movements` VALUES (1,1,'purchase',1,'اختبار',1,'2026-06-23 15:34:05'),(3,19,'purchase',10,'فاتورة استلام: 852',7,'2026-06-23 15:44:45'),(4,20,'purchase',10,'فاتورة استلام: 852',7,'2026-06-23 15:44:45'),(6,23,'purchase',1,'فاتورة استلام: 854ه',7,'2026-06-23 16:00:58'),(7,12,'repair_use',1,'استخدمت في صيانة جهاز #26 - ',5,'2026-06-23 16:05:13'),(8,14,'repair_use',1,'استخدمت في صيانة جهاز #23 - ',5,'2026-06-23 16:05:21'),(9,24,'purchase',10,'فاتورة استلام: 852',7,'2026-06-23 16:20:30'),(10,25,'purchase',10,'فاتورة استلام: 852',7,'2026-06-24 13:01:50'),(11,26,'purchase',10,'فاتورة استلام: 4ق',7,'2026-06-25 03:14:27'),(12,27,'purchase',10,'فاتورة استلام: 854ه',7,'2026-07-01 00:04:02'),(13,1,'purchase',10,'فاتورة استلام: 854ه',1,'2026-07-10 05:27:47'),(14,2,'purchase',1,'فاتورة استلام: 1244',1,'2026-07-10 05:39:24'),(15,3,'purchase',1,'فاتورة استلام: 1244',1,'2026-07-10 05:39:24'),(16,4,'purchase',1,'فاتورة استلام: 1244',1,'2026-07-10 05:39:24');
/*!40000 ALTER TABLE `inventory_movements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `investment_data`
--

DROP TABLE IF EXISTS `investment_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `investment_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cash` decimal(10,2) DEFAULT NULL,
  `bank` decimal(10,2) DEFAULT NULL,
  `inventory` decimal(10,2) DEFAULT NULL,
  `customer_debts` decimal(10,2) DEFAULT NULL,
  `mixed_balance` decimal(10,2) DEFAULT NULL,
  `supplier_debts` decimal(10,2) DEFAULT NULL,
  `expenses` decimal(10,2) DEFAULT NULL,
  `initial_capital` decimal(10,2) DEFAULT NULL,
  `capital_added` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `investment_data`
--

LOCK TABLES `investment_data` WRITE;
/*!40000 ALTER TABLE `investment_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `investment_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoice_items`
--

DROP TABLE IF EXISTS `invoice_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice_items` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) unsigned NOT NULL,
  `product_id` int(11) unsigned DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_invoice_id` (`invoice_id`),
  CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_items`
--

LOCK TABLES `invoice_items` WRITE;
/*!40000 ALTER TABLE `invoice_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoice_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoices` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `entity_id` int(11) unsigned NOT NULL,
  `wallet_id` int(11) unsigned DEFAULT NULL,
  `invoice_number` varchar(50) NOT NULL,
  `type` enum('sale','purchase') NOT NULL,
  `payment_type` enum('cash','credit','installment') NOT NULL DEFAULT 'cash',
  `invoice_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `discount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(15,2) NOT NULL,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `remaining_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','posted','paid','partial','overdue','cancelled') NOT NULL DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `created_by` int(11) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_number_unique` (`invoice_number`),
  KEY `idx_entity_id` (`entity_id`),
  KEY `idx_type_status` (`type`,`status`),
  KEY `idx_invoice_date` (`invoice_date`),
  KEY `idx_due_date` (`due_date`),
  KEY `invoices_wallet_id_foreign` (`wallet_id`),
  KEY `invoices_created_by_foreign` (`created_by`),
  CONSTRAINT `invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `invoices_entity_id_foreign` FOREIGN KEY (`entity_id`) REFERENCES `entities` (`id`),
  CONSTRAINT `invoices_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (1,1,'device','📱 جهاز جديد','📱 جهاز 1+ يس من Karim Ahmed','/devices/1',0,'2026-07-10 05:23:35',NULL),(2,5,'device','📱 جهاز جديد لك','📱 جهاز 1+ يس من Karim Ahmed','/devices/1',0,'2026-07-10 05:23:35',NULL),(3,1,'inventory','⏳ طلب قطعة غيار','الفني علي فني فك وتقفيل طلب قطعة \'شاشة ايتيل\' للجهاز رقم 1','/devices/1',0,'2026-07-10 05:26:38',NULL),(4,1,'inventory','📦 قطعة غيار متوفرة','قطعة \'شاشة ايتيل\' أصبحت متوفرة. جهاز DEV-2026-6552 جاهز للإصلاح','/devices/1',0,'2026-07-10 05:27:47',NULL),(5,5,'inventory','🔧 قطعة غيار متوفرة لجهازك','قطعة \'شاشة ايتيل\' أصبحت متوفرة لجهاز DEV-2026-6552.','/devices/1',0,'2026-07-10 05:27:47',NULL);
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partners`
--

DROP TABLE IF EXISTS `partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `contribution` decimal(10,2) DEFAULT NULL,
  `is_manager` tinyint(1) DEFAULT NULL,
  `monthly_salary` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partners`
--

LOCK TABLES `partners` WRITE;
/*!40000 ALTER TABLE `partners` DISABLE KEYS */;
/*!40000 ALTER TABLE `partners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_schedules`
--

DROP TABLE IF EXISTS `payment_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payment_schedules` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) unsigned NOT NULL,
  `due_date` date NOT NULL,
  `amount_due` decimal(15,2) NOT NULL,
  `amount_paid` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','partial','paid','overdue') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_invoice_id` (`invoice_id`),
  KEY `idx_due_date` (`due_date`),
  KEY `idx_status` (`status`),
  CONSTRAINT `payment_schedules_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_schedules`
--

LOCK TABLES `payment_schedules` WRITE;
/*!40000 ALTER TABLE `payment_schedules` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_schedules` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'admin','view_all','2026-06-21 12:42:50'),(2,'admin','create_all','2026-06-21 12:42:50'),(3,'admin','edit_all','2026-06-21 12:42:50'),(4,'admin','delete_all','2026-06-21 12:42:50'),(5,'admin','manage_users','2026-06-21 12:42:50'),(7,'admin','view_financial','2026-06-21 12:42:50'),(8,'admin','create_invoices','2026-06-21 12:42:50'),(10,'admin','manage_wallets','2026-06-21 12:42:50'),(11,'admin','create_expenses','2026-06-21 12:42:50'),(12,'admin','view_audit','2026-06-21 12:42:50'),(20,'technician','view_my_devices','2026-06-21 12:42:50'),(21,'technician','update_repair_status','2026-06-21 12:42:50'),(22,'technician','use_parts','2026-06-21 12:42:50'),(23,'reception','create_devices','2026-06-21 12:42:50'),(24,'reception','view_devices','2026-06-21 12:42:50');
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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_invoices`
--

LOCK TABLES `purchase_invoices` WRITE;
/*!40000 ALTER TABLE `purchase_invoices` DISABLE KEYS */;
INSERT INTO `purchase_invoices` VALUES (1,'854ه','2026-06-20',1,0,0,7,0,1,'2026-06-20 02:46:02'),(7,'864','2026-06-22',1,0,10,10,0,7,'2026-06-22 05:19:09'),(12,'481','2026-06-22',1,0,10,10,0,7,'2026-06-22 05:54:07'),(13,'987','2026-06-22',1,0,10,10,0,1,'2026-06-22 19:43:16'),(14,'854ه','2026-06-23',1,1,10,10,0,7,'2026-06-23 12:40:27'),(15,'852','2026-06-23',1,2,20,20,0,7,'2026-06-23 12:44:45'),(16,'852','2026-06-23',1,1,11,11,0,7,'2026-06-23 12:51:46'),(18,'854ه','2026-06-23',1,1,1,1,0,7,'2026-06-23 13:00:58'),(19,'852','2026-06-23',1,1,10,10,0,7,'2026-06-23 13:20:30'),(20,'852','2026-06-24',1,1,10,10,0,7,'2026-06-24 10:01:50'),(21,'4ق','2026-06-25',1,1,10,10,0,7,'2026-06-25 00:14:27'),(22,'854ه','2026-07-01',1,1,10,10,0,7,'2026-06-30 21:04:02'),(23,'854ه','2026-07-10',1,1,10,10,0,1,'2026-07-10 02:27:47'),(24,'1244','2026-07-10',1,3,3,3,0,1,'2026-07-10 02:39:24');
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
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sale_items`
--

LOCK TABLES `sale_items` WRITE;
/*!40000 ALTER TABLE `sale_items` DISABLE KEYS */;
INSERT INTO `sale_items` VALUES (1,1,'part',1,'شاشة سامسونج',1,300.00,300.00,NULL,NULL),(4,4,'part',1,'شاشة سامسونج',1,300.00,300.00,NULL,NULL),(7,7,'part',1,'شاشة سامسونج',1,300.00,300.00,NULL,NULL),(8,8,'part',1,'شاشة سامسونج',1,300.00,300.00,NULL,NULL),(9,9,'part',1,'شاشة سامسونج',1,300.00,300.00,NULL,NULL),(10,10,'part',1,'شاشة سامسونج',1,300.00,300.00,NULL,NULL),(12,12,'part',12,'شاشة اوبو',1,700.00,700.00,NULL,NULL),(13,13,'part',12,'شاشة اوبو',1,700.00,700.00,NULL,NULL),(33,33,'part',7,'شاشة شاومي',1,700.00,700.00,NULL,NULL),(34,34,'part',7,'شاشة شاومي',1,700.00,700.00,NULL,NULL),(35,35,'part',13,'فلاتة باور',1,300.00,300.00,NULL,NULL),(36,36,'part',13,'فلاتة باور',1,300.00,300.00,NULL,NULL),(37,37,'part',12,'شاشة اوبو',1,700.00,700.00,NULL,NULL),(38,38,'part',14,'شاشة ايفون',1,500.00,500.00,NULL,NULL),(39,39,'service',NULL,'خدمة صيانة جهاز ايفون 13',1,50.00,50.00,NULL,NULL),(40,39,'service',NULL,'خدمة صيانة جهاز شاومي 14',1,50.00,50.00,NULL,NULL),(41,39,'part',26,'قطعة غيار: بطارية هونور',1,300.00,300.00,NULL,NULL),(42,38,'service',NULL,'خدمة صيانة جهاز اوبو يس',1,50.00,50.00,NULL,NULL),(43,38,'part',27,'قطعة غيار: فلاتة سوكيت',1,200.00,200.00,NULL,NULL),(44,40,'part',25,'شاشة انفنكس',1,500.00,500.00,NULL,NULL),(45,41,'part',12,'شاشة اوبو',1,700.00,700.00,NULL,NULL),(46,42,'part',12,'شاشة اوبو',1,700.00,700.00,NULL,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sale_payments`
--

LOCK TABLES `sale_payments` WRITE;
/*!40000 ALTER TABLE `sale_payments` DISABLE KEYS */;
INSERT INTO `sale_payments` VALUES (1,13,400.00,'cash',NULL,'2026-07-10 05:32:42','',1);
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
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES (1,'INV-2026-45633',1,1,'2026-06-20 05:47:22',300.00,0.00,0.00,300.00,300.00,0.00,'cash',NULL,'completed','','2026-06-20 02:47:22','2026-06-21 13:00:24',NULL),(4,'INV-2026-14606',7,5,'2026-06-20 08:19:14',300.00,0.00,0.00,300.00,300.00,0.00,'cash',NULL,'completed','فاتورة صيانة للجهاز DEV-2026-0725 - اوبو 1+','2026-06-20 05:19:14','2026-06-21 13:06:19',NULL),(7,'INV-2026-43886',8,5,'2026-06-20 08:27:14',300.00,0.00,0.00,300.00,300.00,0.00,'cash',NULL,'completed','فاتورة صيانة للجهاز DEV-2026-0782 - اوبو يس','2026-06-20 05:27:14','2026-06-21 13:32:34',NULL),(8,'INV-2026-75516',8,5,'2026-06-20 08:28:13',300.00,0.00,0.00,300.00,300.00,0.00,'cash',NULL,'completed','فاتورة صيانة للجهاز DEV-2026-4514 - 1+ يس','2026-06-20 05:28:13','2026-06-21 15:47:54',NULL),(9,'INV-2026-80149',9,5,'2026-06-20 08:49:10',300.00,0.00,0.00,300.00,300.00,0.00,'cash',NULL,'completed','فاتورة صيانة للجهاز DEV-2026-0311 - اوبو 1+','2026-06-20 05:49:10','2026-06-21 15:48:33',NULL),(10,'INV-2026-51036',7,3,'2026-06-21 15:31:54',300.00,0.00,0.00,300.00,300.00,0.00,'cash',NULL,'completed','فاتورة صيانة للجهاز DEV-2026-0725 - اوبو 1+','2026-06-21 12:31:54','2026-06-22 06:10:40',NULL),(11,'INV-2026-87787',1,5,'2026-06-22 08:08:57',700.00,0.00,0.00,700.00,700.00,0.00,'cash',NULL,'completed','فاتورة صيانة للجهاز DEV-2026-0551 - شاومي 14','2026-06-22 05:08:57','2026-06-22 19:56:37',NULL),(12,'INV-2026-23592',1,7,'2026-06-22 09:15:35',700.00,0.00,0.00,700.00,0.00,0.00,'cash',NULL,'completed','','2026-06-22 06:15:35','2026-06-22 06:15:35',NULL),(13,'INV-2026-39902',1,7,'2026-06-22 09:30:18',700.00,0.00,0.00,700.00,600.00,100.00,'cash',NULL,'partially_paid','','2026-06-22 06:30:18','2026-07-10 02:32:42',NULL),(33,'INV-2026-44743',1,5,'2026-06-22 11:57:53',700.00,0.00,0.00,700.00,700.00,700.00,'cash',NULL,'completed','فاتورة صيانة للجهاز DEV-2026-7612 - شاومي يس','2026-06-22 08:57:53','2026-06-22 09:01:00',NULL),(34,'INV-2026-31454',1,5,'2026-06-22 12:15:39',700.00,0.00,0.00,700.00,0.00,700.00,'cash',NULL,'pending','فاتورة صيانة للجهاز DEV-2026-0913 - اوبو يس','2026-06-22 09:15:39','2026-06-22 09:15:39',NULL),(35,'INV-2026-82276',1,5,'2026-06-22 22:44:17',300.00,0.00,0.00,300.00,0.00,300.00,'cash',NULL,'pending','فاتورة صيانة للجهاز DEV-2026-7038 - SAM A51','2026-06-22 19:44:17','2026-06-22 19:44:17',NULL),(36,'INV-2026-92626',1,5,'2026-06-22 22:44:35',300.00,0.00,0.00,300.00,0.00,300.00,'cash',NULL,'pending','فاتورة صيانة للجهاز DEV-2026-7919 - ايفون 1+','2026-06-22 19:44:35','2026-06-22 19:44:35',NULL),(37,'INV-2026-12328',1,5,'2026-06-23 16:05:13',700.00,0.00,0.00,700.00,0.00,700.00,'cash',NULL,'pending','فاتورة صيانة للجهاز DEV-2026-1833 - اوبو يس','2026-06-23 13:05:13','2026-06-23 13:05:13',NULL),(38,'INV-2026-07146',1,5,'2026-06-23 16:05:21',500.00,0.00,0.00,750.00,500.00,250.00,'cash',NULL,'partially_paid','فاتورة صيانة للجهاز DEV-2026-3058 - ايفون 11برو ماكس','2026-06-23 13:05:21','2026-06-30 21:06:42',NULL),(39,'INV-2026-55015',6,7,'2026-06-25 03:21:37',50.00,0.00,0.00,400.00,400.00,0.00,'cash',NULL,'completed','فاتورة تلقائية للجهاز DEV-2026-3222 - ايفون 13','2026-06-25 00:21:37','2026-06-25 09:40:12',NULL),(40,'INV-2026-63898',1,1,'2026-07-06 18:46:57',500.00,0.00,0.00,0.00,0.00,0.00,'cash',NULL,'completed','','2026-07-06 15:46:57','2026-07-06 15:46:57',NULL),(41,'INV-2026-25036',1,7,'2026-07-06 21:43:39',700.00,0.00,0.00,700.00,0.00,0.00,'cash',NULL,'completed','','2026-07-06 18:43:39','2026-07-06 18:43:39',NULL),(42,'INV-2026-86283',11,1,'2026-07-07 00:41:48',700.00,0.00,0.00,200.09,0.00,200.09,'cash',NULL,'pending','','2026-07-06 21:41:48','2026-07-06 21:41:48',NULL);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shifts`
--

LOCK TABLES `shifts` WRITE;
/*!40000 ALTER TABLE `shifts` DISABLE KEYS */;
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
INSERT INTO `technician_specialties` VALUES (3,5,1,6,0.00,'2026-06-20 02:37:55');
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
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) unsigned DEFAULT NULL,
  `wallet_id` int(11) unsigned DEFAULT NULL,
  `entity_id` int(11) unsigned DEFAULT NULL,
  `transaction_date` datetime NOT NULL DEFAULT current_timestamp(),
  `type` enum('sale','purchase','receipt','payment','expense','opening_balance') NOT NULL,
  `account_type` enum('asset','liability','equity','revenue','expense') NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `balance_after` decimal(15,2) NOT NULL,
  `reference_number` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_invoice_id` (`invoice_id`),
  KEY `idx_wallet_id` (`wallet_id`),
  KEY `idx_entity_id` (`entity_id`),
  KEY `idx_transaction_date` (`transaction_date`),
  KEY `idx_type` (`type`),
  CONSTRAINT `transactions_entity_id_foreign` FOREIGN KEY (`entity_id`) REFERENCES `entities` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transactions_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transactions_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
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
  `role` enum('admin','manager','technician','sales','accountant','reception') NOT NULL DEFAULT 'sales',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `last_login_ip` varchar(45) DEFAULT NULL,
  `last_login_device` text DEFAULT NULL,
  `current_shift_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
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
INSERT INTO `users` VALUES (1,'admin','admin@system.com','$2y$10$gExaRVL6VreKNtLmtb5peetJuoIVe5qkP5COzoaKGoHsqtGc6HTde','مدير النظام','01000000000','admin',1,'2026-07-10 19:45:49','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36',NULL,'2026-06-20 02:02:45','2026-07-10 16:45:49'),(2,'accountant','accountant@system.com','$2y$10$i0Pd96jNUJz8SvlY12Ilt.XyUIXUqmNpMlAM3Xv7j2iLa.k5Yg6eu','محاسب النظام','01055555555','accountant',1,NULL,NULL,NULL,NULL,'2026-06-20 02:02:45','2026-06-20 02:06:03'),(3,'tech1','tech1@system.com','$2y$10$tR7wW76KLGAmhQQO88aLkuKcbzMZ4aDbbmXIVeb4rhG.egQioXhG6','أحمد فني بوردة','01011111111','technician',1,NULL,NULL,NULL,NULL,'2026-06-20 02:02:45','2026-06-20 02:06:03'),(4,'tech2','tech2@system.com','$2y$10$EweIWL8SH3wJYZKtEj0VOe0qK/XHTbqeeKdAn1h212F9wgZvQk4P6','محمد فني سوفت وير','01022222222','technician',1,NULL,NULL,NULL,NULL,'2026-06-20 02:02:45','2026-06-20 02:06:03'),(5,'tech3','tech3@system.com','$2y$10$aFGlhb8AgmQ7DF5Cjinl7.Ib96Fu3IjinF1ufJBOO8Go6MhRqM3NO','علي فني فك وتقفيل','01033333333','technician',1,'2026-07-10 05:30:02','::1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36',NULL,'2026-06-20 02:02:45','2026-07-10 02:30:02');
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
  `current_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wallets`
--

LOCK TABLES `wallets` WRITE;
/*!40000 ALTER TABLE `wallets` DISABLE KEYS */;
INSERT INTO `wallets` VALUES (1,'خزينة المحل','cash',NULL,NULL,0.00,1,NULL,'2026-06-20 02:22:54','2026-06-20 02:22:54',NULL),(2,'فودافون كاش','mobile_wallet',NULL,NULL,0.00,1,NULL,'2026-06-20 02:22:54','2026-06-20 02:22:54',NULL),(3,'اتصالات','mobile_wallet',NULL,NULL,0.00,1,NULL,'2026-06-20 02:22:54','2026-06-20 02:22:54',NULL),(4,'WE Pay','mobile_wallet',NULL,NULL,0.00,1,NULL,'2026-06-20 02:22:54','2026-06-20 02:22:54',NULL),(5,'البنك الأهلي','bank_account',NULL,NULL,0.00,1,NULL,'2026-06-20 02:22:54','2026-06-20 02:22:54',NULL);
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

-- Dump completed on 2026-07-10 20:04:21
