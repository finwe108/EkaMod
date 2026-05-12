/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.6.25-MariaDB, for Linux (x86_64)
--
-- Host: sdb-83.hosting.stackcp.net    Database: laravel-35303934667f
-- ------------------------------------------------------
-- Server version	10.11.11-MariaDB-log

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
-- Table structure for table `admission_applications`
--

DROP TABLE IF EXISTS `admission_applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admission_applications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `application_number` varchar(255) NOT NULL,
  `school_year_id` bigint(20) unsigned DEFAULT NULL,
  `grade_level_id` bigint(20) unsigned DEFAULT NULL,
  `section_id` bigint(20) unsigned DEFAULT NULL,
  `student_type` enum('new','transferee','returning') NOT NULL DEFAULT 'new',
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `suffix` varchar(255) DEFAULT NULL,
  `sex` varchar(255) NOT NULL,
  `birth_date` date NOT NULL,
  `birth_place` varchar(255) DEFAULT NULL,
  `mother_tongue` varchar(255) DEFAULT NULL,
  `is_ip` tinyint(1) NOT NULL DEFAULT 0,
  `ethnic_group` varchar(255) DEFAULT NULL,
  `religion` varchar(255) DEFAULT NULL,
  `lrn` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact_number` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `house_street` varchar(255) DEFAULT NULL,
  `barangay` varchar(255) DEFAULT NULL,
  `municipality_city` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `father_name` varchar(255) DEFAULT NULL,
  `father_contact` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `mother_contact` varchar(255) DEFAULT NULL,
  `guardian_name` varchar(255) DEFAULT NULL,
  `guardian_relationship` varchar(255) DEFAULT NULL,
  `guardian_contact` varchar(255) DEFAULT NULL,
  `parent_guardian_contact` varchar(255) DEFAULT NULL,
  `last_school_attended` varchar(255) DEFAULT NULL,
  `last_grade_level_completed` varchar(255) DEFAULT NULL,
  `strand_or_track` varchar(255) DEFAULT NULL,
  `application_status` enum('submitted','under_review','accepted','rejected','cancelled') NOT NULL DEFAULT 'submitted',
  `remarks` text DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `reviewed_by` bigint(20) unsigned DEFAULT NULL,
  `accepted_student_id` bigint(20) unsigned DEFAULT NULL,
  `created_user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admission_applications_application_number_unique` (`application_number`),
  KEY `admission_applications_grade_level_id_foreign` (`grade_level_id`),
  KEY `admission_applications_section_id_foreign` (`section_id`),
  KEY `admission_applications_reviewed_by_foreign` (`reviewed_by`),
  KEY `admission_applications_accepted_student_id_foreign` (`accepted_student_id`),
  KEY `admission_applications_created_user_id_foreign` (`created_user_id`),
  KEY `admission_status_created_idx` (`application_status`,`created_at`),
  KEY `admission_sy_grade_idx` (`school_year_id`,`grade_level_id`),
  CONSTRAINT `admission_applications_accepted_student_id_foreign` FOREIGN KEY (`accepted_student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL,
  CONSTRAINT `admission_applications_created_user_id_foreign` FOREIGN KEY (`created_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `admission_applications_grade_level_id_foreign` FOREIGN KEY (`grade_level_id`) REFERENCES `grade_levels` (`id`) ON DELETE SET NULL,
  CONSTRAINT `admission_applications_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `admission_applications_school_year_id_foreign` FOREIGN KEY (`school_year_id`) REFERENCES `school_years` (`id`) ON DELETE SET NULL,
  CONSTRAINT `admission_applications_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admission_applications`
--

LOCK TABLES `admission_applications` WRITE;
/*!40000 ALTER TABLE `admission_applications` DISABLE KEYS */;
INSERT INTO `admission_applications` VALUES (1,'ADM-20260427-KUKODI',2,1,NULL,'new','KUNTIDEVI DASI','MENDEZ','AQUINO',NULL,'Female','2022-11-28','TANGUB, MIS. OCC.','ENGLISH',0,NULL,'VAISHNAVISM',NULL,'nice.atma@gmail.com','09273390859',NULL,'PUROK 9','Romagook','Kibawe','Bukidnon','NICETO AQUINO','09273390859','CHERYL MENDEZ','09059391446','CHERYL MENDEZ','Parent','09059391446','09059391446',NULL,NULL,'Preschool','rejected',NULL,'Duplicate Application','2026-04-27 06:52:54','2026-04-27 14:37:35',1,NULL,NULL,'2026-04-27 06:52:54','2026-04-27 14:37:35'),(3,'ADM-20260502-FZSGC4',2,1,NULL,'new','test1',NULL,'student',NULL,'Female','2022-11-28','Tangub, Mis. Occ.','English',0,NULL,'Vaishnava',NULL,'nice.atma@gmail.com','09273390859',NULL,NULL,NULL,NULL,NULL,'Niceto Aquino','09273390859','Cheryl Aquino','09059391446','Niceto Aquino','Father','09273390859',NULL,NULL,NULL,'Preschool','accepted',NULL,NULL,'2026-05-02 00:56:28','2026-05-02 17:01:07',1,28,14,'2026-05-02 00:56:28','2026-05-02 17:01:07');
/*!40000 ALTER TABLE `admission_applications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `announcements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `posted_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_announcements_posted_by` (`posted_by`),
  CONSTRAINT `fk_announcements_teacher` FOREIGN KEY (`posted_by`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcements`
--

LOCK TABLES `announcements` WRITE;
/*!40000 ALTER TABLE `announcements` DISABLE KEYS */;
/*!40000 ALTER TABLE `announcements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attendance`
--

DROP TABLE IF EXISTS `attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `attendance` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL,
  `date` date NOT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_attendance_student_date` (`student_id`,`date`),
  KEY `idx_attendance_student_id` (`student_id`),
  KEY `idx_attendance_date` (`date`),
  CONSTRAINT `fk_attendance_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendance`
--

LOCK TABLES `attendance` WRITE;
/*!40000 ALTER TABLE `attendance` DISABLE KEYS */;
/*!40000 ALTER TABLE `attendance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` varchar(20) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
INSERT INTO `departments` VALUES (1,'Academic Affairs','ACA','Academic Affairs','2026-04-12 04:25:36','2026-04-12 04:25:36'),(2,'Registrar','REG','Registrar Office','2026-04-12 04:25:36','2026-04-12 04:25:36'),(3,'Human Resource','HR','Human Resource','2026-04-12 04:25:36','2026-04-12 04:25:36'),(4,'Accounting','ACC','Accounting Office','2026-04-12 04:25:36','2026-04-12 04:25:36'),(5,'Information Technology','IT','IT Department','2026-04-12 04:25:36','2026-04-12 04:25:36'),(6,'Administration','ADM','Administration','2026-04-12 04:25:36','2026-04-12 04:25:36'),(7,'Guidance','GUI','Guidance Office','2026-04-12 04:25:36','2026-04-12 04:25:36'),(8,'General Services','GEN','General Services','2026-04-12 04:25:36','2026-04-12 04:25:36');
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `document_requirement_rules`
--

DROP TABLE IF EXISTS `document_requirement_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `document_requirement_rules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `document_type_id` bigint(20) unsigned NOT NULL,
  `grade_level_id` bigint(20) unsigned DEFAULT NULL,
  `student_type` varchar(50) DEFAULT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT 1,
  `require_if_no_existing_copy` tinyint(1) NOT NULL DEFAULT 1,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `document_requirement_rules_document_type_id_index` (`document_type_id`),
  KEY `document_requirement_rules_grade_level_id_index` (`grade_level_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `document_requirement_rules`
--

LOCK TABLES `document_requirement_rules` WRITE;
/*!40000 ALTER TABLE `document_requirement_rules` DISABLE KEYS */;
INSERT INTO `document_requirement_rules` VALUES (1,1,NULL,NULL,1,1,NULL,'2026-05-10 00:22:58','2026-05-10 00:22:58'),(2,2,NULL,'transferee',1,1,NULL,'2026-05-10 00:23:44','2026-05-10 00:23:44'),(3,3,NULL,'transferee',1,1,NULL,'2026-05-10 00:24:04','2026-05-10 00:24:04'),(4,4,NULL,'transferee',1,1,NULL,'2026-05-10 00:24:43','2026-05-10 00:24:43'),(5,4,4,'new',1,1,NULL,'2026-05-10 09:15:51','2026-05-10 09:15:51'),(6,4,14,'new',1,1,NULL,'2026-05-10 09:16:15','2026-05-10 09:16:15'),(7,6,4,'new',1,1,NULL,'2026-05-10 09:17:08','2026-05-10 09:17:08'),(8,7,4,'new',1,1,NULL,'2026-05-10 09:17:59','2026-05-10 09:17:59'),(9,7,14,'new',1,1,NULL,'2026-05-10 09:21:02','2026-05-10 09:21:02');
/*!40000 ALTER TABLE `document_requirement_rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `document_types`
--

DROP TABLE IF EXISTS `document_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `document_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `document_types`
--

LOCK TABLES `document_types` WRITE;
/*!40000 ALTER TABLE `document_types` DISABLE KEYS */;
INSERT INTO `document_types` VALUES (1,'PSA Birth Certificate','Clear copy of PSA/NSO birth certificate.',1,1,'2026-05-09 16:57:04','2026-05-09 16:57:04'),(2,'SF9 / Report Card','Latest report card from previous school year.',1,2,'2026-05-09 16:57:04','2026-05-09 16:57:04'),(3,'Good Moral Certificate','Certificate of good moral character.',1,3,'2026-05-09 16:57:04','2026-05-09 16:57:04'),(4,'SF10 / Form 137','Permanent academic record from previous school.',1,4,'2026-05-09 16:57:04','2026-05-09 16:57:04'),(5,'2x2 ID Photo','Recent student ID photo.',1,5,'2026-05-09 16:57:04','2026-05-09 16:57:04'),(6,'ECCD Checklist','Early Childhood Care and Development Checklist from Kinder',1,NULL,'2026-05-10 08:58:08','2026-05-10 08:58:08'),(7,'Certificate of Completion','For Grade 1 and 11 requirement. \r\nCertificate of Completion from Kinder or Grade 10',1,NULL,'2026-05-10 08:59:17','2026-05-10 09:06:50'),(8,'Proof of Income','For local ITR/Certificate of Indigency\r\nFor abroad: Certificate of employment, Payslip',1,8,'2026-05-10 09:02:33','2026-05-10 09:02:33'),(9,'ESC Certificate','Certificate for ESC and SHSVP applicants/recipients',1,10,'2026-05-10 09:11:23','2026-05-10 09:11:23');
/*!40000 ALTER TABLE `document_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employee_id_counters`
--

DROP TABLE IF EXISTS `employee_id_counters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `employee_id_counters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `year` year(4) NOT NULL,
  `department_code` varchar(20) NOT NULL,
  `last_number` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_year_department` (`year`,`department_code`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employee_id_counters`
--

LOCK TABLES `employee_id_counters` WRITE;
/*!40000 ALTER TABLE `employee_id_counters` DISABLE KEYS */;
INSERT INTO `employee_id_counters` VALUES (3,2010,'ADM',1,'2026-04-12 10:07:11','2026-04-12 10:07:11'),(4,2025,'ACA',4,'2026-04-13 02:41:21','2026-04-19 10:18:19'),(5,2023,'ACA',3,'2026-04-14 01:07:03','2026-04-16 13:53:00'),(6,2022,'ACA',2,'2026-04-16 14:11:08','2026-04-19 10:16:35');
/*!40000 ALTER TABLE `employee_id_counters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `employees` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id` varchar(50) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `suffix` varchar(20) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `civil_status` varchar(30) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `employment_date` date NOT NULL,
  `first_department_id` bigint(20) unsigned NOT NULL,
  `first_department_code` varchar(20) NOT NULL,
  `first_department_name` varchar(100) NOT NULL,
  `current_department_id` bigint(20) unsigned DEFAULT NULL,
  `employee_type` enum('teaching','non_teaching') NOT NULL DEFAULT 'non_teaching',
  `employment_status` enum('active','inactive','resigned','retired') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_id` (`employee_id`),
  KEY `fk_employees_first_department` (`first_department_id`),
  KEY `fk_employees_current_department` (`current_department_id`),
  KEY `idx_employees_last_name` (`last_name`),
  KEY `idx_employees_employee_type` (`employee_type`),
  KEY `idx_employees_employment_status` (`employment_status`),
  KEY `idx_employees_employment_date` (`employment_date`),
  CONSTRAINT `fk_employees_current_department` FOREIGN KEY (`current_department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_employees_first_department` FOREIGN KEY (`first_department_id`) REFERENCES `departments` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (3,'EMP-2010-ADM-0001','Cheryl','Mendez','Aquino',NULL,'Female','1988-04-18','Married','gagamaquino@gmail.com','09059391446','Purok 9, Romagook, Kibawe, Bukidnon','2010-06-01',6,'ADM','Administration',1,'teaching','active','2026-04-12 10:07:11','2026-04-12 10:07:11'),(4,'EMP-2025-ACA-0001','Jenny',NULL,'Cowas',NULL,'Female','2000-01-01','Single',NULL,NULL,NULL,'2025-06-01',1,'ACA','Academic Affairs',1,'teaching','active','2026-04-13 02:41:21','2026-04-13 02:41:21'),(5,'EMP-2023-ACA-0001','Mary Clesa','Labesores','Galanque',NULL,'Female','2000-01-01','Married',NULL,NULL,NULL,'2023-06-01',1,'ACA','Academic Affairs',1,'teaching','active','2026-04-14 01:07:03','2026-04-14 01:07:03'),(6,'EMP-2023-ACA-0002','Saira Mae',NULL,'Alolino',NULL,'Female','2000-01-01','Single',NULL,NULL,NULL,'2023-06-01',1,'ACA','Academic Affairs',1,'teaching','active','2026-04-16 13:47:18','2026-04-16 13:47:18'),(7,'EMP-2023-ACA-0003','Desiree','Gabutin','Soriano',NULL,'Female','2000-01-01','Single',NULL,NULL,NULL,'2023-06-01',1,'ACA','Academic Affairs',1,'teaching','active','2026-04-16 13:53:00','2026-04-16 13:53:00'),(8,'EMP-2025-ACA-0002','Cristy','Cebu','Asistido',NULL,'Female','2000-01-01','Married',NULL,NULL,NULL,'2025-06-01',1,'ACA','Academic Affairs',1,'teaching','active','2026-04-16 13:59:49','2026-04-16 13:59:49'),(9,'EMP-2025-ACA-0003','Schichem','Mandacaya','Poblete',NULL,'Female','2000-01-01','Single',NULL,NULL,NULL,'2025-06-01',1,'ACA','Academic Affairs',1,'teaching','active','2026-04-16 14:04:45','2026-04-16 14:04:45'),(10,'EMP-2022-ACA-0001','Michelle','Euraoba','Ladaga',NULL,'Female','2000-01-01','Married',NULL,NULL,NULL,'2022-06-01',1,'ACA','Academic Affairs',1,'teaching','active','2026-04-16 14:11:08','2026-04-16 14:11:08'),(11,'EMP-2022-ACA-0002','Lezcil Joy','Eder','Corpuz',NULL,'Female','1988-04-19','Married',NULL,NULL,NULL,'2022-06-01',1,'ACA','Academic Affairs',1,'teaching','active','2026-04-19 10:16:35','2026-04-19 10:16:35'),(12,'EMP-2025-ACA-0004','Manelyn','E','Tangub',NULL,'Female','2026-04-19','Single',NULL,NULL,NULL,'2025-06-01',1,'ACA','Academic Affairs',1,'teaching','active','2026-04-19 10:18:19','2026-04-19 10:18:19');
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enrollments`
--

DROP TABLE IF EXISTS `enrollments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `enrollments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL,
  `school_year_id` bigint(20) unsigned NOT NULL,
  `grade_level_id` bigint(20) unsigned NOT NULL,
  `section_id` bigint(20) unsigned DEFAULT NULL,
  `student_type` varchar(20) NOT NULL DEFAULT 'new',
  `status` varchar(20) NOT NULL DEFAULT 'enrolled',
  `date_enrolled` date DEFAULT NULL,
  `date_dropped` date DEFAULT NULL,
  `date_transferred_out` date DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_enrollments_student_school_year` (`student_id`,`school_year_id`),
  KEY `fk_enrollments_school_year` (`school_year_id`),
  KEY `fk_enrollments_grade_level` (`grade_level_id`),
  KEY `fk_enrollments_section` (`section_id`),
  CONSTRAINT `fk_enrollments_grade_level` FOREIGN KEY (`grade_level_id`) REFERENCES `grade_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_enrollments_school_year` FOREIGN KEY (`school_year_id`) REFERENCES `school_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_enrollments_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_enrollments_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enrollments`
--

LOCK TABLES `enrollments` WRITE;
/*!40000 ALTER TABLE `enrollments` DISABLE KEYS */;
INSERT INTO `enrollments` VALUES (1,1,1,4,1,'new','promoted',NULL,NULL,NULL,NULL,'2026-04-11 03:51:42','2026-05-11 17:55:50'),(2,2,1,4,1,'new','enrolled',NULL,NULL,NULL,NULL,'2026-04-11 04:25:02','2026-04-11 04:25:02'),(3,3,1,4,1,'new','enrolled',NULL,NULL,NULL,NULL,'2026-04-11 04:41:57','2026-04-11 04:41:57'),(4,4,1,4,1,'new','enrolled',NULL,NULL,NULL,NULL,'2026-04-11 04:48:07','2026-04-11 04:48:07'),(5,5,1,4,1,'new','enrolled',NULL,NULL,NULL,NULL,'2026-04-11 04:50:24','2026-04-11 04:50:24'),(8,6,1,4,1,'new','enrolled',NULL,NULL,NULL,NULL,'2026-04-11 10:09:03','2026-04-11 10:09:03'),(9,7,1,4,1,'new','enrolled',NULL,NULL,NULL,NULL,'2026-04-11 10:11:22','2026-04-11 10:11:22'),(10,8,1,4,14,'new','enrolled',NULL,NULL,NULL,NULL,'2026-04-17 06:27:27','2026-04-17 06:27:27'),(11,9,1,4,1,'new','enrolled',NULL,NULL,NULL,NULL,'2026-04-17 06:29:22','2026-04-17 06:29:22'),(12,10,1,4,1,'new','enrolled',NULL,NULL,NULL,NULL,'2026-04-17 06:31:53','2026-04-17 06:31:53'),(13,11,1,4,1,'new','enrolled',NULL,NULL,NULL,NULL,'2026-04-17 06:33:39','2026-04-17 06:33:39'),(14,12,1,4,1,'new','enrolled',NULL,NULL,NULL,NULL,'2026-04-17 06:35:46','2026-04-17 06:35:46'),(15,13,1,4,1,'new','enrolled',NULL,NULL,NULL,NULL,'2026-04-17 06:39:55','2026-04-17 06:39:55'),(16,14,1,4,1,'new','enrolled',NULL,NULL,NULL,NULL,'2026-04-17 06:41:43','2026-04-17 06:41:43'),(17,15,1,5,2,'transferee','enrolled','2025-05-26',NULL,NULL,NULL,'2026-04-17 07:33:11','2026-04-20 03:03:58'),(18,16,1,5,2,'new','enrolled',NULL,NULL,NULL,NULL,'2026-04-17 07:37:00','2026-04-17 07:37:00'),(19,17,1,5,2,'new','enrolled',NULL,NULL,NULL,NULL,'2026-04-17 07:39:20','2026-04-17 07:39:20'),(20,18,1,5,2,'old','enrolled','2025-06-09',NULL,NULL,NULL,'2026-04-20 03:37:20','2026-04-20 03:37:20'),(21,18,3,4,1,'old','enrolled','2024-07-09',NULL,NULL,NULL,'2026-04-20 04:22:16','2026-04-20 04:22:16'),(22,18,4,3,25,'new','enrolled','2023-07-05',NULL,NULL,NULL,'2026-04-20 05:08:02','2026-04-20 05:08:02'),(23,19,4,3,25,'new','enrolled',NULL,NULL,NULL,NULL,'2026-04-20 05:13:41','2026-04-20 05:13:41'),(24,21,8,2,27,'old','enrolled','2014-06-01',NULL,NULL,NULL,'2026-04-20 05:40:40','2026-04-20 05:40:40'),(25,22,8,2,27,'new','enrolled','2014-06-01',NULL,NULL,NULL,'2026-04-20 05:44:46','2026-04-20 05:44:46'),(26,23,7,1,28,'new','enrolled','2015-06-01',NULL,NULL,NULL,'2026-04-20 06:13:48','2026-04-20 06:13:48'),(27,21,7,3,26,'old','enrolled','2015-06-01',NULL,NULL,NULL,'2026-04-20 06:18:46','2026-04-20 06:18:46'),(28,24,7,3,26,'new','enrolled','2015-06-01',NULL,NULL,NULL,'2026-04-20 06:29:07','2026-04-20 06:29:07'),(30,28,2,1,30,'new','enrolled',NULL,NULL,NULL,NULL,'2026-05-02 17:01:07','2026-05-03 10:46:06'),(31,19,1,5,2,'old','enrolled',NULL,NULL,NULL,NULL,'2026-05-04 18:56:13','2026-05-04 18:56:13'),(32,29,1,5,2,'old','enrolled','2025-06-08',NULL,NULL,NULL,'2026-05-04 20:47:18','2026-05-04 20:47:18'),(33,30,1,5,2,'old','enrolled','2025-06-09',NULL,NULL,NULL,'2026-05-04 21:10:48','2026-05-04 21:10:48'),(34,20,7,2,29,'old','enrolled',NULL,NULL,NULL,NULL,'2026-05-04 21:16:22','2026-05-04 21:16:22'),(35,22,7,3,26,'old','enrolled',NULL,NULL,NULL,NULL,'2026-05-04 21:48:27','2026-05-04 21:48:27'),(36,31,1,3,31,'new','promoted',NULL,NULL,NULL,NULL,'2026-05-11 18:53:21','2026-05-11 19:02:01'),(37,32,1,3,31,'new','promoted',NULL,NULL,NULL,NULL,'2026-05-11 19:06:12','2026-05-11 20:50:01'),(38,33,1,3,31,'new','promoted',NULL,NULL,NULL,NULL,'2026-05-11 19:10:22','2026-05-11 19:20:39'),(39,34,1,3,31,'old','promoted',NULL,NULL,NULL,NULL,'2026-05-11 20:48:37','2026-05-11 21:06:36'),(40,35,1,3,31,'old','promoted',NULL,NULL,NULL,NULL,'2026-05-11 21:04:35','2026-05-11 21:04:52'),(41,36,1,3,31,'old','promoted',NULL,NULL,NULL,NULL,'2026-05-11 21:10:00','2026-05-11 21:10:17'),(42,37,1,3,31,'old','promoted',NULL,NULL,NULL,NULL,'2026-05-11 21:23:02','2026-05-11 21:23:15'),(43,38,1,3,31,'old','promoted',NULL,NULL,NULL,NULL,'2026-05-11 21:25:44','2026-05-11 21:26:00'),(44,39,1,3,31,'old','promoted',NULL,NULL,NULL,NULL,'2026-05-11 21:27:55','2026-05-11 21:28:04'),(45,40,1,3,31,'old','promoted',NULL,NULL,NULL,NULL,'2026-05-11 21:30:30','2026-05-11 21:30:40'),(46,41,1,3,31,'old','promoted',NULL,NULL,NULL,NULL,'2026-05-11 21:32:43','2026-05-11 21:33:24');
/*!40000 ALTER TABLE `enrollments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grade_levels`
--

DROP TABLE IF EXISTS `grade_levels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `grade_levels` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(3) NOT NULL,
  `name` varchar(100) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_grade_levels_name` (`name`),
  UNIQUE KEY `grade_levels_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grade_levels`
--

LOCK TABLES `grade_levels` WRITE;
/*!40000 ALTER TABLE `grade_levels` DISABLE KEYS */;
INSERT INTO `grade_levels` VALUES (1,'P01','Preschool 1',1,NULL,NULL),(2,'P02','Preschool 2',2,NULL,NULL),(3,'K00','Kinder',3,NULL,NULL),(4,'G01','Grade 1',4,NULL,NULL),(5,'G02','Grade 2',5,NULL,NULL),(6,'G03','Grade 3',6,NULL,NULL),(7,'G04','Grade 4',7,NULL,NULL),(8,'G05','Grade 5',8,NULL,NULL),(9,'G06','Grade 6',9,NULL,NULL),(10,'G07','Grade 7',10,NULL,NULL),(11,'G08','Grade 8',11,NULL,NULL),(12,'G09','Grade 9',12,NULL,NULL),(13,'G10','Grade 10',13,NULL,NULL),(14,'G11','Grade 11',14,NULL,NULL),(15,'G12','Grade 12',15,NULL,NULL);
/*!40000 ALTER TABLE `grade_levels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grades`
--

DROP TABLE IF EXISTS `grades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `grades` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `teacher_load_id` bigint(20) unsigned DEFAULT NULL,
  `student_id` bigint(20) unsigned NOT NULL,
  `subject_id` bigint(20) unsigned DEFAULT NULL,
  `quarter` tinyint(3) unsigned NOT NULL,
  `term_no` tinyint(3) unsigned DEFAULT NULL,
  `grade` decimal(5,2) NOT NULL,
  `written_work` decimal(8,2) DEFAULT NULL,
  `performance_task` decimal(8,2) DEFAULT NULL,
  `behavior` decimal(8,2) DEFAULT NULL,
  `quarterly_exam` decimal(8,2) DEFAULT NULL,
  `long_test` decimal(8,2) DEFAULT NULL,
  `initial_grade` decimal(8,2) DEFAULT NULL,
  `final_grade` decimal(8,2) DEFAULT NULL,
  `remarks` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_grades_student_subject_quarter` (`student_id`,`subject_id`,`quarter`),
  UNIQUE KEY `uq_grades_teacher_load_student_term` (`teacher_load_id`,`student_id`,`term_no`),
  KEY `idx_grades_student_id` (`student_id`),
  KEY `idx_grades_subject_id` (`subject_id`),
  KEY `idx_grades_quarter` (`quarter`),
  KEY `idx_grades_teacher_load_id` (`teacher_load_id`),
  CONSTRAINT `fk_grades_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_grades_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_grades_teacher_load` FOREIGN KEY (`teacher_load_id`) REFERENCES `teacher_loads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grades`
--

LOCK TABLES `grades` WRITE;
/*!40000 ALTER TABLE `grades` DISABLE KEYS */;
/*!40000 ALTER TABLE `grades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grading_profile_components`
--

DROP TABLE IF EXISTS `grading_profile_components`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `grading_profile_components` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `grading_profile_id` bigint(20) unsigned NOT NULL,
  `component` varchar(50) NOT NULL,
  `weight` decimal(5,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_gpc_profile` (`grading_profile_id`),
  CONSTRAINT `fk_gpc_profile` FOREIGN KEY (`grading_profile_id`) REFERENCES `grading_profiles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grading_profile_components`
--

LOCK TABLES `grading_profile_components` WRITE;
/*!40000 ALTER TABLE `grading_profile_components` DISABLE KEYS */;
INSERT INTO `grading_profile_components` VALUES (1,1,'written_work',30.00,NULL,NULL),(2,1,'performance_task',45.00,NULL,NULL),(3,1,'behavior',5.00,NULL,NULL),(4,2,'long_test',10.00,NULL,NULL),(5,2,'quarterly_exam',10.00,NULL,NULL),(6,2,'written_work',40.00,NULL,NULL),(7,2,'performance_task',35.00,NULL,NULL),(8,2,'behavior',5.00,NULL,NULL),(9,2,'long_test',10.00,NULL,NULL),(10,2,'quarterly_exam',10.00,NULL,NULL),(11,3,'written_work',20.00,NULL,NULL),(12,3,'performance_task',55.00,NULL,NULL),(13,3,'behavior',5.00,NULL,NULL),(14,3,'long_test',10.00,NULL,NULL),(15,3,'quarterly_exam',10.00,NULL,NULL),(16,4,'written_work',25.00,NULL,NULL),(17,4,'performance_task',45.00,NULL,NULL),(18,4,'behavior',5.00,NULL,NULL),(19,4,'long_test',10.00,NULL,NULL),(20,4,'quarterly_exam',15.00,NULL,NULL),(21,5,'written_work',25.00,NULL,NULL),(22,5,'performance_task',40.00,NULL,NULL),(23,5,'behavior',5.00,NULL,NULL),(24,5,'long_test',10.00,NULL,NULL),(25,5,'quarterly_exam',20.00,NULL,NULL),(26,6,'written_work',35.00,NULL,NULL),(27,6,'performance_task',35.00,NULL,NULL),(28,6,'behavior',5.00,NULL,NULL),(29,6,'long_test',10.00,NULL,NULL),(30,6,'quarterly_exam',15.00,NULL,NULL);
/*!40000 ALTER TABLE `grading_profile_components` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grading_profiles`
--

DROP TABLE IF EXISTS `grading_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `grading_profiles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `education_level` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grading_profiles`
--

LOCK TABLES `grading_profiles` WRITE;
/*!40000 ALTER TABLE `grading_profiles` DISABLE KEYS */;
INSERT INTO `grading_profiles` VALUES (1,'JHS - Languages/AP/EsP/GMRC','JHS',NULL,NULL,NULL),(2,'JHS - Science/Math','JHS',NULL,NULL,NULL),(3,'JHS - MAPEH/EPP/TLE','JHS',NULL,NULL,NULL),(4,'SHS - Core Subjects','SHS',NULL,NULL,NULL),(5,'SHS - Other Subjects','SHS',NULL,NULL,NULL),(6,'SHS - Work Immersion','SHS',NULL,NULL,NULL);
/*!40000 ALTER TABLE `grading_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',2),(3,'0001_01_01_000002_create_jobs_table',2),(4,'0001_01_01_000000_create_users_table',1),(5,'2026_04_26_105201_create_admission_applications_table',2),(6,'2026_04_26_105632_add_student_id_to_users_table',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_date` date DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_payments_student_id` (`student_id`),
  KEY `idx_payments_payment_date` (`payment_date`),
  KEY `idx_payments_status` (`status`),
  CONSTRAINT `fk_payments_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `required_student_documents`
--

DROP TABLE IF EXISTS `required_student_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `required_student_documents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `required_student_documents`
--

LOCK TABLES `required_student_documents` WRITE;
/*!40000 ALTER TABLE `required_student_documents` DISABLE KEYS */;
INSERT INTO `required_student_documents` VALUES (1,'Birth Certificate','Upload a clear copy of PSA/NSO birth certificate.',1,1,1,'2026-05-09 16:10:56','2026-05-09 16:10:56'),(2,'Report Card','Upload latest report card or SF9.',1,1,2,'2026-05-09 16:10:56','2026-05-09 16:10:56'),(3,'Good Moral Certificate','Required for transferees when applicable.',1,1,3,'2026-05-09 16:10:56','2026-05-09 16:10:56'),(4,'2x2 ID Photo','Upload recent 2x2 student photo.',1,1,4,'2026-05-09 16:10:56','2026-05-09 16:10:56');
/*!40000 ALTER TABLE `required_student_documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'super_admin','Super Admin','Full system access','2026-04-12 04:46:26','2026-04-12 04:46:26'),(2,'admin','Administrator','Administrative access','2026-04-12 04:46:26','2026-04-12 04:46:26'),(3,'registrar','Registrar','Registrar office access','2026-04-12 04:46:26','2026-04-12 04:46:26'),(4,'teacher','Teacher','Faculty access','2026-04-12 04:46:26','2026-04-12 04:46:26'),(5,'principal','Principal','Principal access','2026-04-12 04:46:26','2026-04-12 04:46:26'),(6,'accounting','Accounting','Accounting office access','2026-04-12 04:46:26','2026-04-12 04:46:26'),(7,'hr','Human Resource','HR access','2026-04-12 04:46:26','2026-04-12 04:46:26'),(8,'guidance','Guidance','Guidance office access','2026-04-12 04:46:26','2026-04-12 04:46:26'),(9,'student','Student','Student portal access','2026-04-12 04:46:26','2026-04-12 04:46:26'),(10,'parent','Parent','Parent portal access','2026-04-12 04:46:26','2026-04-12 04:46:26');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `school_settings`
--

DROP TABLE IF EXISTS `school_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `school_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `school_id` varchar(50) DEFAULT NULL,
  `region` varchar(150) DEFAULT NULL,
  `division` varchar(150) DEFAULT NULL,
  `district` varchar(150) DEFAULT NULL,
  `school_name` varchar(255) DEFAULT NULL,
  `school_head_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `school_settings`
--

LOCK TABLES `school_settings` WRITE;
/*!40000 ALTER TABLE `school_settings` DISABLE KEYS */;
INSERT INTO `school_settings` VALUES (1,'404986','X','Bukidnon','West Kibawe','Madana Mohana Colleges, Inc.','Cheryl M. Aquino','2026-04-19 00:56:18','2026-04-19 00:56:52');
/*!40000 ALTER TABLE `school_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `school_years`
--

DROP TABLE IF EXISTS `school_years`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `school_years` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `starts_on` date DEFAULT NULL,
  `ends_on` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_school_years_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `school_years`
--

LOCK TABLES `school_years` WRITE;
/*!40000 ALTER TABLE `school_years` DISABLE KEYS */;
INSERT INTO `school_years` VALUES (1,'2025-2026','2025-06-15','2026-03-31',1,'2026-04-11 01:28:11','2026-05-10 15:12:05'),(2,'2026-2027','2026-06-08','2027-04-08',0,'2026-04-11 02:33:37','2026-05-10 15:12:05'),(3,'2024-2025','2024-07-08','2025-05-30',0,'2026-04-20 04:21:20','2026-05-10 15:12:05'),(4,'2023-2024','2023-07-08','2024-05-30',0,'2026-04-20 04:58:17','2026-05-10 15:12:05'),(5,'2011-2012','2011-06-01','2012-03-30',0,'2026-04-20 05:22:41','2026-05-10 15:12:05'),(6,'2010-2011','2010-06-01','2011-03-30',0,'2026-04-20 05:25:19','2026-05-10 15:12:05'),(7,'2015-2016','2015-06-01','2016-03-30',0,'2026-04-20 05:29:10','2026-05-10 15:12:05'),(8,'2014-2015','2014-06-01','2015-03-30',0,'2026-04-20 05:35:20','2026-05-10 15:12:05');
/*!40000 ALTER TABLE `school_years` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sections`
--

DROP TABLE IF EXISTS `sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `school_year_id` bigint(20) unsigned NOT NULL,
  `grade_level_id` bigint(20) unsigned NOT NULL,
  `teacher_id` bigint(20) unsigned DEFAULT NULL,
  `capacity` int(3) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_sections_school_year_name` (`school_year_id`,`name`),
  KEY `idx_sections_school_year_id` (`school_year_id`),
  KEY `idx_sections_grade_level_id` (`grade_level_id`),
  KEY `idx_sections_teacher_id` (`teacher_id`),
  CONSTRAINT `fk_sections_grade_level` FOREIGN KEY (`grade_level_id`) REFERENCES `grade_levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_sections_school_year` FOREIGN KEY (`school_year_id`) REFERENCES `school_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_sections_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sections`
--

LOCK TABLES `sections` WRITE;
/*!40000 ALTER TABLE `sections` DISABLE KEYS */;
INSERT INTO `sections` VALUES (1,'1-Gokula',1,4,3,15,'2026-04-11 02:52:19','2026-04-16 23:21:50'),(2,'2-Vrindavan',1,5,4,15,'2026-04-11 07:29:28','2026-04-16 23:22:36'),(3,'3-Kalindi',1,6,NULL,NULL,'2026-04-14 06:41:34','2026-04-14 06:41:34'),(4,'4-Sarasvati',1,7,NULL,NULL,'2026-04-14 06:41:59','2026-04-14 06:41:59'),(5,'5-Ganges',1,8,NULL,NULL,'2026-04-14 06:42:25','2026-04-14 06:42:25'),(6,'6-Jamuna',1,9,NULL,NULL,'2026-04-14 06:43:00','2026-04-14 06:43:00'),(7,'7-Mathura',1,10,NULL,NULL,'2026-04-14 06:43:40','2026-04-14 06:43:40'),(8,'8-Navadwip',1,11,NULL,NULL,'2026-04-14 06:44:16','2026-04-14 06:44:16'),(9,'9-Mayapur',1,12,NULL,NULL,'2026-04-14 06:44:43','2026-04-14 06:44:43'),(10,'10-Vaikuntha',1,13,NULL,NULL,'2026-04-14 07:40:47','2026-04-14 07:40:47'),(11,'11-Dwarka',1,14,2,NULL,'2026-04-14 07:43:30','2026-04-16 23:22:19'),(12,'12-Naimisharanya',1,15,NULL,NULL,'2026-04-14 07:44:05','2026-04-14 07:44:05'),(13,'K-Govardhan',2,3,NULL,NULL,'2026-04-14 14:47:02','2026-04-14 14:47:02'),(14,'1-Gokula',2,4,NULL,NULL,'2026-04-14 14:47:42','2026-04-14 14:47:42'),(15,'2-Vrindavan',2,5,NULL,NULL,'2026-04-14 14:49:01','2026-04-14 14:49:01'),(16,'3-Kalindi',2,6,NULL,NULL,'2026-04-14 14:49:41','2026-04-14 14:49:41'),(17,'4-Sarasvati',2,7,NULL,NULL,'2026-04-14 14:50:31','2026-04-14 14:50:31'),(18,'5-Ganges',2,8,NULL,NULL,'2026-04-14 14:51:03','2026-04-14 14:51:03'),(19,'6-Jamuna',2,9,NULL,NULL,'2026-04-14 14:51:34','2026-04-14 14:51:34'),(20,'7-Mathura',2,10,NULL,NULL,'2026-04-14 14:52:06','2026-04-14 14:52:06'),(21,'8-Navadwip',2,11,NULL,NULL,'2026-04-14 14:52:37','2026-04-14 14:52:37'),(22,'9-Mayapur',2,12,NULL,NULL,'2026-04-14 14:53:12','2026-04-14 14:53:12'),(23,'10-Vaikuntha',2,13,NULL,NULL,'2026-04-14 14:53:51','2026-04-14 14:53:51'),(24,'11-Dwarka',2,13,NULL,NULL,'2026-04-14 14:55:19','2026-04-14 14:55:19'),(25,'K-Govardhan',4,3,NULL,15,'2026-04-20 04:59:37','2026-04-20 04:59:37'),(26,'K-Govardhan',7,3,NULL,10,'2026-04-20 05:30:00','2026-04-20 06:17:23'),(27,'P2-Govardhan',8,2,NULL,10,'2026-04-20 05:39:47','2026-04-20 05:39:47'),(28,'P1-Govardhan',7,1,NULL,10,'2026-04-20 05:47:15','2026-04-20 05:47:15'),(29,'P2-Govardhan',7,2,NULL,10,'2026-04-20 06:17:54','2026-04-20 06:17:54'),(30,'P1-Govardhan',2,1,NULL,NULL,'2026-05-03 09:43:17','2026-05-03 09:43:17'),(31,'K-Govardhan',1,3,NULL,NULL,'2026-05-11 17:58:36','2026-05-11 17:58:36');
/*!40000 ALTER TABLE `sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_sessions_user_id` (`user_id`),
  KEY `idx_sessions_last_activity` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('0LDl7IFA2Bs01WqOx9JnOCCbGSTucCAsnpbm5REg',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNTg5YVRnWkhnaGRYR3hzVmptRktMTWdtR0Y1UHpoR3VHYzdzaU5HTSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777892096),('1f45YNlm4keidPohixlvVAAKx7rzLCTNsGqKvyLO',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoibXM0UUhUanpkZ3RienRkdUJIYWhreDRuc1lKQ1VaN0xnRW9XR2tMbSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777888710),('1zBceAomutiEbyYqjGSlZ2QMP77EfQXhN8jrAstu',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNEdKVFQ3ZlZjSTRSa05GUXhJVmlGaHdad0Y0S1ZFMlFSWGZmdDdNMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777888463),('2CmYnZ3P9id1IiPniNLdSBMMBIjExbOL0c95r9j0',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoibFlTbVNiZ1k1NHlFbzJuSmVzWEJPUng2ZkJXZk1ud1ZrRW5oTnlPOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777888279),('2T0t8sCn6XxnRCnVmoVJc1bW5ATNs9A9oAxyrVbI',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZjlUaEs2OEpDcEp4ZWlpdUNVR2V6TmFxMHNIeFNpQkhvbmRzYzJTViI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777888955),('40uuQQTxXkCod56WivIFWaWvTL73RwsekCY0SZDo',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYkVOUWZTbVFUcDZaMENmam5zMEhZdmNLejU2TVlLTEl4WldHU3pBcSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777890126),('4jOaEQTfN442RBoYg1j5mYx6ZqqWbcJg4N75xYqK',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTFRwa2ZlMWtBOXVyQXY0RFRVNFAzMmY3NmF4SDRDeWxZNU40MUpXRCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777891477),('4Rj2Cl0zr8foKqvOfqCW9e8mb0VULwe4udfmzSlJ',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoieXozQ1VrMVdPSWw2WTRVUE5DY2l0NnZ3YjN5VTBPNnJzbFREemZ3ciI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777886127),('6bLrzljlzuVDMcZzdCbAWXMOhFBiAscVPy0gWTZc',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSDVvWWdzMjZLaTNZU0Y2MGdhV255MkE3NjB3a09EeTNWTHJGOENvRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777890064),('7CcPHe1rqGbOAs2uOWmFW34Drd4rV0sTAF7Fb5L7',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTERIVVp6NmlYZkNwMlFqaE5yRVpqSnRkMzFuWklYcHdnUmlLOTh3eSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777892771),('7dUNhssKOQnkj7Zh6rYh3PIlpcUWqxne5PWh3A4V',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQlZOejhSZGhjaHFDV01YQmRlNHhQNHJWNmhsck5jNFhzT1pNQnpYeCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777894045),('7RArqaAmKKs185FD69U6y9fnMoiDhH3TeA5ArLHw',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZm1tSzFjOW11OG1VSTN6VXo3R3NtU1hkcXNQdDBNZ2pDcXhCZEJRZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777887172),('83wb52qicUuDSh72F3kQwbxkLMwpjwx8i70DdvIz',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNU5IemUya1pxQlE0akNRdjB4TVcyQzRaY2dyUjVKRUpVSmRxNDZRYyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777894107),('88tU6XRr1kS0obflifgR9wjH9tjzJPUYsOTWpfOA',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaDB3OWhLYUlIU0dVcEZ3U3YxRnBEQkI2c2RTbUJIWlREVnA1RE00MyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777893324),('8AQDq7WcPjZ3wWx4MdSNFpLG4nJ5ibbyUW3E13uk',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSldhbTE0N0c1cWxYcXlaNmlhY0pyT25zOFZINklqcWFwSlRUb3kyVSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777888095),('9AYH1ilJb69w8lVjgZiIGucaN98ulAYneSrQysP5',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYUp1aWJmamwzYmMxdnJmdFdRUnBmVGQzNGNCQkZTSjNLQ3IzbnZ1cSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777894418),('9tazcfe4gCcUWGjBKXgMzY13VdcOuVBj0tJeESGI',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoibjVnWVl3RmtlRVhpWE9yaEE0Y2NoTGxLc1BwWVQ3aXgwUUdoNXlpUSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777886804),('A8Cp9K9Qmy97rvaeVyYsVG8qwIKiaQc7XCD4hNO2',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMWJ1R2toSkZVbWdZZWdoMU53N1VkSHp6SzZiVTlFTGJ6VDA5VmYyZyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777889079),('ABnSygcXNwvBGmziiGrJIObuQE2tpkIpBynTC1N2',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMWxTb2p4UVlUR0diUGxxaTl1S2M2OWpvV2R2UUtlWGIzaGRMamJyMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777886619),('AdCMgQyakwpHbvC7DfU3SMYSW5MkE4chLEZw6EPF',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYkJLdjdLV0hxV2x6elh6VkNEUmp2R0tiMUtRWTZaVWo2ZFRBWkhyTSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777891170),('akJ3lkq7bjCL2bU9at3QhMADvTxzrFGsAgY48ry1',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoibmc1R2c4eHVsTWxBRWVRM3JSRW05cGRSTUxFdFQ2NmVmVXVWdHNZNiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777893201),('aNL7KQ8xqlBhAVqCySV6fx3F0c1qQoSFdycHL8vv',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiczVNSEViZ255Y1ZOWjg0OHNrb243WXpFUlF5ajZmakVMZVVvS3MyMCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777886251),('asmt1Bci4sp9L06uEemyrOGIqztCvz1i17gZvNSV',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSTFjbWRnODh1dW9QS09Qb1ZDVGN2UkZXNm1BbnlvbTFJQVVwNktYYSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777891416),('axgrEa92GUhAr8druXbO6xUAtVpyeIfKbpHxCodW',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoickF4SXhEMElOelBzYXpjMUo1SzRpSWhUQUd2V3lKZUlwSzg3QmVJeiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777890310),('AYZ5YkmpCE4fJjUobL41WNedBwSHLVNPfZUaeVev',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRldJM2lCWXRtOTRyNHNETHp4amk4OFB5ZG45M3lUTkIzdE9GNlE2NiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777894295),('b31FwePiSkTyj22SGF6Ys2nwskghUBB8wD83eq06',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiT3JMUkhkbkVkUlo2WklqQWlIcExEVkRQcjlJR3FPODNsc0pmYTdGNiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777891354),('b9O7uqRXGGDvbWCQHVBCGhh9rdvIr5xRPBSAMnVq',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaGJkd1pwM1lxd2pOWEE3T3p1RzhaNm1vVnNaa09HeXJTVkVQVHJaZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777889141),('bHZu4xKykdP2n6zETlvzZC4HXLkEp7nQjzhPWnAp',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTzVvQWdCNzdHVUgxWXFwQW1JOFF6ZVg3ZjVBckNCSENxa1dVeUlrZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777886988),('Bju0eVUDNIsSjS38wv73FXhcnJIKSxTvn6tCe0tK',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoidmV0QXNyT3BGTjhYbWQ0VEN5emJwb1NRbmJaSVFvc0xXRW04QVRYVSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777888771),('bWu7OuiJ5sqzCx3fd1iOuueaOpPshBZUKYxHiNYF',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiR0o0empMZ1g2M1FQeEtwdzZuUVhxSktjdWJ5TUVkMERwVEo2eFZ1cSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777886927),('bzefAExpfeWqJzXIXrSlbjTfnWQTOYODrTKOjFwO',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoicnVpTGFVMjV3aUczdldLWjZ2Yzl6bkpueW1MQTFIcHZJazlhNzlURiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777887602),('C1jqdZLl1mOA3XLO0QwuTgDGlj6a5B8aMzRZnpTn',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNnZldlJsNTl4aGswQkhjQ01yek0zZkh0RlEwYVV4dW5icllkcmlodyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777892649),('CnOZvLTQK2xV3z9dwoHDh8LjsrCePFJoboa10H7F',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYm80NVQ5OVRMcHdrSGFHSjNZS202WDduQk4wV2VlYUo1eGFOM2NwcyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777892526),('d1ehHoWDtn6gsy2MnGT11Ag4Q2I1eFUAbQC3jg8p',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSDdoWldkZmV1OU1uMzQ5UVJDaWl1Z0F4ZU9udldneTJXb1lwZERHWCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777888833),('D2eZODscEO363i2FgQsKcfRBeiWs8YuJ4Gtl7dWE',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiS3RuN29YWlczM1kwc3hhU1B4akk2S1FkOVlFa0RUdzJVQ1NTMHdWNiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777893017),('DcK1tnO5Avw5f9zVTt8T4f55JLjnpmOeg2GKGTm3',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUnFpQkZsTmgzNDNVZWZQcGtCQlpyYk9qTmtuZ0h4U291UDZNZFhmRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777890986),('dG4KtEVo0kGa3w6ajOaK51NNihWTuW9YwsFodvuF',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYXhoTWFSMVRBOTJwV3ZLcTQxQ1VJNU5JbGlrUWp2V2RDdUNMMTRKOSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777890003),('dHuNjv6M5jDMzKV54r1qVqkrIDVCJ1gWancva5an',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVEZ6cjEzTzZhNEhJSERvRWlQNGVYODBHV3NMMTR2ek9vclo2UnpSUiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777887971),('dirkN8IcsfI7FgjCmqwYcivUqu4weB2VfvqNEkYF',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUVFHZEFWbjlqdE9uelJqRVk2SVBYS0VuV3N4Z0hKckJiT1NxT0QxSyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777891293),('Dr0hex5JTVZ8MT0bjQ52eqdBB1XLzJG36BAuxqDG',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRlI4THhWSkhlamU0ZjRoNkRSdEliYmpYcGJ2aW1BUGRoUkIxNmV4MCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777887910),('EAPzp7nikUWSEfIPlPYCGjHMEjezx5mpxbUHRm6o',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNlNrOEUxOFJuN2Zob0hNSXNuMFNqbzRYYW9jRFFud0w5enViWFgwYyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777894357),('ECQXDxORLdomJfiqigTGw1EK7RL9jWszuiNM5L7E',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiM05HSUJoRHdZQkRaZGVFWVU0SlpHWlpJbmo1cW1TckpWNnVFaHJOeSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777892342),('ed8oqwOt6Hp0auBC6hHVujPA9lcKOzNIwEywU3st',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOWFKS0oybjE2d2lkNm00MWFRRkZiSlgzbndOcWFkbHRtaGNWY3J1aCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777889202),('EEXtqDY4QRKjOc7PLisLLLGVF8DdmhZ0tRA32IiU',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoieUtqSG94MmJFTDlVTGxOZXQzalBsR1hhUVBBWGd4cGlaeTNFUnlnZyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777893263),('elXmiVgFwZlBIXoWZHay9T6rl90x69xTjbSDPc4X',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiM3p0akhTWEh5YlVLMElVM2Yxc0ZhZHh0Q1AzaTRvcmh1UWFTTVpSYSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777892587),('eTWDDHUoYCFezcZLT432Y7enBH60uOedIp0s6BfP',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZm1TVUU3eXhXa0lCdll2a3VERVNuY2NVZlpDa295QTB3RVA3elFiRyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777891109),('FjtzExGBZPlTHh1siQLgouHcFhasCIzx0DMP8gRd',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoibzhXOEQ0dEZKMjhIcDI5OXY3MVplNnF4VnRON1A5dFdNQUxrT2JVZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777886066),('FpsFHclBxcAVmCerpEqQnacoNvfTRkg4awCTvf8z',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRXNNMGFETVdlcmFGYjM4bDNlWjFudmE5SFdpZFROU0RicGx2NUpENyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777893460),('g2JablmWRq9VHWD9fzvYKRctzovqIekfeEniavOO',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoibUVNYkxTSmJ0b1Y5Q3hrdE5NYUZ3Y0lhd2dPNjZ2c1JNeEtEVDVCSyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777886312),('g5oN0Gk6JOCzWqbO3b9NYfpyyO7diAU45v8DXODa',1,'180.191.234.63','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:150.0) Gecko/20100101 Firefox/150.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNHhBTjFwT1JqU2hOY3FoaHZUd2dtRjQwbFFLek9hclFRTW80OXV1OCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1777894152),('g5TWFqgs8H1aEUjkVh3Ttqss1Z9fdAhcbLzsWrga',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ2VuclVCd1NNUkMxZnc3d05uV1BaTTFQdmNvaGJrb1RsbkV6UVhqWCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777888218),('gDihXPQHK9JRGHVqtTZL7QlpCRoivjjThAmbdT40',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVExYMFhpUzRxeVpxRmJDbWhRUDVvdTJRUnhZUzFSellUYnR2V29WdCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777887356),('H0V3GUYHgTbTZB0fxo9rspMjNfLUkbZP9azhDkTZ',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiME10Mmd3aTJSQm9VQjF5RWU5RzhxUzkwVTNPU2VZYk43Y3c4d1RobSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777889387),('HGrWuSiRwM5V1g3Tk1IxLozESGJ4G88yYQjWON7j',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNEh5NjhIYVFQN2oxSnZWQkdCNHJ3YnN3UnZQOWE2TEhraml0dmZOeiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777892894),('HoAR7rIZjtyVJl3w7H3ZCuqmpCtk0WJCuBIVxEUF',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYmxRMEk0aFZQNXBpMlo2MHA1bzNDZlVOblpQbHdGbHdJcENINEQyWSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777892219),('hqzoho54KjbW1MFdHUGavlrwwXxway2Yc4fe6Vnr',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTTVDSG1reVBUd3pHeXRkUFlzZkRKWlFMUW05RmhyUk9UVWc0TG1WZyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777889572),('htOA6VFdw1hgPB9bUQKk8qXvrWpb7bg2XN229EmL',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYUUwVUcyVk5rZ3dqN3BGSnNuMnNmcmUzeEVzUnBPcnNmSE5pMnlNdiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777891850),('ITYu8EXb4WbgnC66pfvf94z3fuYF7vE5kKeRK4Q9',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoidk1SMTlTZmxzbEJNWm5QdWhtM0RkVk1XTTZYU0RPNXJrT1U1NnYwYSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777892280),('jehrPc5tNEYBGiJT8pmFnGtWC8JPmyUY1RHMpwDu',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoicUw4ZTV4SDRSd2NWdE1uVWhkMGNDWWl6blVaTU05bDBoTXF4aW1xMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777888587),('jk2OLmrsr23mLvJixIo2d2khWVkwc5lNdxwPcv3e',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYVFwMlRMVkJzSVh1YXBubEtBVGFVSEJWM1pOeGxORzNmT1JZQmlWeiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777889818),('JKIO6izXXjEENpEnE0VvePJe6GzcwBT0PXdQLGal',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoieEk4T1k1YXN0WEZ3Y3lHNjVtVFBGdU5aZjlRaU9pY1VyT0hZQ2p5MyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777889633),('jU5aBUoqEdtoKxdnPtzUWkjTqOwurHditLVAY0Lt',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoibTFZQ1hmOWozSkFwYlk2TFhxc2d4ZGVyNzM1ZkhUSGFWY1A3SU45ZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777888033),('KDSROs4GS9EQrYw5HQCv8nIiQcrX4WZA6aOIGvx8',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRHE3QkNaM2liUWtjTGxGczRuQzdpWFd1NnNFNUJaODdPRzU2UjFXSyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777892956),('l98LDk9bC5IYi68pkeDqol1vz4KS5FaS2FE2n5zD',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiR1VPbTdVRUdUV0tRNHJVN3d2aXJGN3NmemxaRlVSRHZVa0l1Z09ROCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777890371),('LBWZHXLCcUCZgSPuBir105ZxkAf0lXwU2Ixc9E5M',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSTNlM3BRMDRIaVZDdlhoOU44QlBKQnpRa1VOVHV3bDl2cldURHI4ViI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777889695),('LcEBmL62wfUW78ckYhoV3GAG1mIrvg5cIiCfVBhn',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiazI5bmNsdUE3Y3lzZ0hpYW1aY0dkMHZkWG12NUpmdWxlclpYSlZydiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777891788),('lCJHUupoV1r71fE7qrhCWkyL4LFje3Cn82gLKuJO',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRXNuZFltYks4aHNyVFNFdEQyUlRLRjJzNVVRZEhVcXRiVTZGZGVPTiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777889510),('lCMAU4nXkHmJRuMKPO5jnloi9K9o7BrHyfriXiq0',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNGNIQUFYZW1JS0VBRWtRQVNxM01UMGR1REVqWnBCcUdMZDVrMDhDMiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777892710),('LOXloORopnYEZpFZ8kK04zKSQjjBCNuk7Pkx1wy1',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYTVtSnpsRGJ6alZBcWNLQ05KZ2dxa1JlZnJwRTV5cTR3NEdHV05tUCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777887787),('M9q7FamPwVhpeRgcLOFyXei77a8qb1EW1RZ4vNsq',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYUJMM1ZxS1dxSXFYaGJDRGhXd0hIdTlkbEdjNW5RaEpWY1dYUER1VSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777887234),('Mg0JtcnoNJFlmbw9mVIcxCVIzz5LDBSoljiVeZ3x',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoib0ExYUVsRE0zTFlXSkdGR1dOMDZVcEF1YVpWRk80RlZzcllESzN6UyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777888648),('mKyKoG1V5pjZDkD3VgUHpL8cxaPa55ah7ghIyWp7',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoia1NLYVhFZUhJOUtPUTNWRld1VVRwSHkzWlFONVRWTGNBOXJMMDltYSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777887541),('Mofggdslr3DKpQLDfvRNVL3MfFbUaO0LON1vDgPd',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTjFxMXJ0alA4THQxY0ZEekZ6WmoyWVNycnhoUW82Tk44dHdNSEdYaSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777886496),('MRsc3okZPyHtvfYH1FDQwhXxLCkuEXtGFxj6w0J1',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiN0lYWHlCNExIR3pieVpqQmI4Qld0Y09Kc3NscnNCY2tNWTRobVpWMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777886681),('N6A2emUcHkf7cIIQHGGiZjHCR76M9n76gvzgaoah',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoid2xhU3J6clpMMEtzbTlCdkF1bXYzbGhac3liOFJBZzJkWkRtdjJEdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777890187),('ngynRWVHJKckcC3ixbtqobVHOK4vw8tT5zdIhsxi',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoia0J4QWZjWWx4R09SSlBkUkdBVmhLN2RRanZ1WFRnS0sxNnQxT2N6ZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777891973),('nMbLXx4aPfr3jwNqff8VNdxK9QoOc3lolQTJUJYV',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiY3BCOEZTQjJzQWxXZlE4bnhCNTk2Z2NlUUtIV0FsZDNuVGVjWGpPSyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777888894),('Nsb9Q0jIwSMIMDFjWOtUOyDb30rpbsUmg9OLzxvO',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaTJhRW9MSGRSdzV6Z1l0eE9IM0g2YVhRWVdDRDJVY3NQNTNNOVYxYiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777886558),('NVyZ3NxnuxyLbQu6jMC0eDynmqqb3ZJdOElX7ooe',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNVBpc3R5QTVPMlowOXJxU3UzVXBOc01uU1pHUmRwWElnYjRQdW1JOCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777887295),('nZG6yLlY5JZfZ6gelKsAtddcAmTRIDZnCCILPD3A',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOUFsMHFOZnBDWDExZTFLQ1AxVDFlYWtzZExUYjYyd2k2WFlJM0JiZyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777892403),('nzXedPSXPtuWX3mcgPZI7Q6SlobSHeuiba1zSpBD',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoid3FlaE51NnFOSEdOT1RjV0lGOTlNV2NvYXpXM1RIelk2M0pLeUtzTSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777890617),('oGecJ0BJtQpOjJCssgPMdf07q8IQyUDZawMVacIU',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMXVVRlZzU2VwVVJFR1VwMTladXhIcTZOOHB4bFNVZ1hSZlVhZmFGVSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777891911),('opRgjzZKPG42Nt2OalKmqDElB8xDdxpItqFhh8mV',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ1U5ZVhLTGpRY3dHYmtjQWF0UG40eE9jQXNoN1FRMUtJQ3RvNld0ZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777891231),('otiTvdPmXGZ5jozjtJC5zesDWWja4y7QSqOJ3EGT',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTUhYYm9RUWM5dW1UejNIRW9FM3BqMG5ueXU3dHQ4RmJrYTJoTEhrVyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777887418),('P3l80lTizlBmuTZx7x5dHYgNgRPF7K8lwAyesSWX',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWlY5ek5yS0tyUzg4NFV3bjkxaHZ5SU9sTXc1eFBXbzhDeExsb1BIVSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777886865),('P7kgDusuRfUSvbkBfxnw36xeTr2xtO84TJ7wz1EW',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYVNxamNrcktpRUc0QXRlT0lFOXhTQUhxMjFUNjRTRTFSZmxSd1dDUiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777889264),('PM3X0D3PFZfHgIFONGnyEwhltljep0u5NPatHEcG',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTFpkOU1VbVpQNkxlUXhBZ0pKMXRQbkpOMEl2dnpPNjlHSTdjT0lMRCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777892034),('pqI01ob5RlnXIvv95pc7J3ZzzjL4ABp91K5lrI2U',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoieTZubkppWGxSVDZ5S2daY2gwbGxFb0NkN2NHc0ZiSGkxQXdpTkZJcSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777892157),('PunFOwxizdenx1E65UhSB1EqAoXUCXvADFMhqmj9',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ291TlhGQ1puVG1JYVZkSnYwektOaERGMXRKVERpblE0ZUg1YzVYWiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777893079),('pvHJWFzAJ5c6W5iXqV9xg8QzUpu07nWIRUBDx2Gm',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZFUxNWtXQ1dibVE0S0w0ZlczczRvZE9yWG42WXBvREYxVHhNSVJtRyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777887664),('PxnrWnwdpMEp9cYYpQN4ZMMDou0kmsowWPRQ3NXD',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWUpUWEU3dUoyOHFONXdRTnBRa3YyQlp2U1JZOEJDOUFnM1RsQTRHbyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777889448),('Q5jChOwdygIACKXnEhD0DFC5dV5jiiMbngGhNMKR',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUzFSVlRXRVg2WlhabUdzbmJHNnZHYW5mdU0yaFQ1bFN2c1hZblhZUSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777890740),('QntHtfAd1pb4vg864ZYEslvkm0DSu4JYwr775CaJ',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSzl2SmNNZUpra2ZES0RTSG5JUmpxNTBIRkFNUXd0MFhlQWlJdnA1WiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777890863),('rKaXXutpyNt4WIcX0iN5TZpuh8WonS2MaVCvI8Tv',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ2x4QjhsdURub1dwNGZxQld6bGlSWUhET3FqMmw3bGw3ZHNzdnljNCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777890801),('RmxEMath7DKAnTNfhmxuS21svgOuTHBkBjiIfURX',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQnJad2w3YXY3MWw4eEgwWU05cjJFQ1VQSURQOXBuVUNEMHZGM0R6SyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777889325),('rN3DxMYOrE4aMC9e1xz1QRcb5JFYZb9xGcjO42ie',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMHJEVEJIcTZ3UG1GMklSdWRjTG12S1pNb09EZTdHczFnSlJVUGtNSCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777887049),('S3GTZYd3wJWRzpV422LwF6jNV4wnVnDBKtCTTfjw',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSDV5aXVpTXlFbDg2SEFab000ZHdKcThFMjk2ZUtCTm1xNlRwNWVYVSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777889880),('s3JWx3dHzTvX957w8psElajWPEvNMwR1s8E9jxhr',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoicjAxNklOQzc3bU1UaTU1RlloaUlEQjdFNnp6cmYxR0o0TkRQYUJCZCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777893140),('S91oA5DfVy5qLduzluX3CTq4tL6JcqrRO5Vslbbe',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoia3U0YmZieHV0MFJ5VG9ZNVFUV3hNZk5QTjhYOFlUQXhMNjI0aWRXQiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777886189),('secdeJCvasz3g9rxvTlcxFdZ6RMUHZqfh2oKDi7f',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRmFmS05EU0Y3YXNXeDMxY1JnMkh2d2p2V0kwbWY3RGR6TzJJOWttaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777890924),('SIIF2siQF6k4LsKD1x3J4PMBU1SKsldGSfa9CIv6',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNnFhRkdIYXVYdTFQVzVtdm05YmlQV1k1RThVblZJRWR3ZlRoU3dwQiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777890679),('T7o5GfTzq3IDoAFwxDRZWEqRo8JHTkk2DIx9ILx8',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQjJOdUtDM3Q4Qm9rYjI4RjRpZ2hmTUFCTFJlTTJqRU01dGZTU3NWNCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777890556),('t9TqOkRR1EGftk1lgGaOgqlvBlHoDBRDTzhIK73o',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWWpuRmVyYk1QbGZDNjRwMHZ4Mk40cDM0YkpDUE9tUTR0OUhlT2lRRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777888341),('tIM430Ww1ERexGMKaVIX3KYQbCJJoJ2cBnV21Dtu',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoicERpaGdWQlhMN2F5S28wdXUxWVZGZ1B3ZzlMc0FUZ045Qm11QWNXQSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777886373),('TMpClCDSLg5KoQPGciyKHp2alxyvEBlKkobBmWY8',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaE1QRXFDajc5cjdQcVFKb3ptWGhoWDk1aEpqeUV3bHlSRmg5QnhnRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777892465),('ucXPmRa5jqwhfmvSvlSJ4dMYmQNotOksbF956qGn',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiR1ZuU2tsWTkwZ1FXNmttYmZtZ1IxTFFvMjUzdlFzSXloWjdDaEFSNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777888525),('UVUrZD9thawizoAahqRxewZrTGqnPxSJGT6SjtwC',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiejc5N2l3Y2tVSmZwdmFIMU8wWHE1OU04SkJORHdHeU9iWHFhbThiZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777891047),('v2eQ7Biaqrf5m3AXLqiO6r8I9tWDq6yAZXl6TjUR',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoicFRSa3RrQmRWTm1GS1ZFNkFOUFFFMjJHeFdaTVVaVHAyZHlOWnlhdSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777889018),('v3MSTx361Axna02Uw4jvnlX4iZytUrWurRP2BdGw',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYjJPbjMwczVsUTlZUVJUVm9pZVhqUHJRVXVUT1ZOeUNyeU1QdzNoSiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777893385),('VDwisKCIEvOJ6Ug0HXomvAaawSj9ZYNsJMRRn4NF',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZTFJU2VGMnVFdlRhR1pGRHBXREZjNTFHSFhlekplU040TEJnOGZLQSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777890249),('VpDaGBAZtivpSOkeaSZO3TUnZlWKSNBNMVehFKih',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiU0Ftd3dRSGJINXZOR3o4SGtCNWJVbU56TlZNb3ZlMjZzU0VBelZReCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777891600),('VPh8ZsxADQzZBc9W0I5vKXWOHr44AplKEjNQQWjJ',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWDdMaDlVd2ViRGRlM0sxeVZ5dVRKdG0xTHV6N3ZrS2JNbXhCOU9aSSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777889942),('vuBzyHb0hUcm8ddfISpMKbRWCPMSP68orsvLL4Ig',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTmFNT1loZkRsZU1xUHpZVTV3cUdVVnZJcVg4MTdGWndxWklDMjdERiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777892833),('W6WCPLuwYMVY2UInpDycXQWHwlVPsmjl4tjiJBh2',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoieHNqNE1DWWlsZm40VkVpekNLMGUwU1daRjRDMzhaR1hyWUJldlpmMCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777887480),('x6scw69EP6Ygu0f9A4U9N0X0P1O7wOPnZZoGbhig',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVjFyOXBraDNUWFZyWFJxWmVMbzNHN0h2WVA2c1BmVUU5RmowVkhMbCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777888402),('XFE79zUpPXRVf9ZhBZXUbzVqdifBghZsM6vmy4G3',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoic3NaYTZQcmI1TkVibnBzanBoVlBOc2VhY3k4aGp2bnBQZDM0RkFyWCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777890433),('XJQxKLwIGKdHbPYBQDYLCawuPPgiAWgOR16ST9az',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSm95UnB1RTROY3gxMHM2Mjl3OWJxQ2RIREpOYWFneExtMTF3dTB3OCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777890494),('Xnf5dpR0cFdWaX1UgB6waVxSNvubotEAl972ca26',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWUgzSDg1aGJUU0xHdFMyOWdybXdGOU1XQmJwYlVaZkxURVNNd3o4NSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777886435),('y7eHY013FmXegP11fhLCshreDoMftbXvF5c52Hj8',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiN0hzQWEyQ1B4cWtFV3JuQ3ROS0d1dWxWNGlBcXBiRHZzTTdYOVJZZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777888156),('YEOvDFAW6xzawig9HUFoyU6DTH7XyBtGdJ8zQIRW',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoieTZ4RmpOQXBPRlF3Um9EME1tUmNnbWdFTEJ3c1RQUjBQNzdjaWtDbSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777891539),('Z2dcin07HvtFvsWRVXmlMgQ2c1OsndTDZF31G9uL',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUENMUjlwaFdRZEp6RW5LWldYb0FoYTJxTUdUdm9qU1RSQ3Q0YThBeCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777889757),('Z5EbLNKyieaBMNTC95hfFJpVRSuA7YKzvdsCBo31',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoicWRSRzNCSGdldU9zVDY0SmtuMW01WXFLQ004UmdESmxRREZXblU2TiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777887725),('zdWMzeTLs2fqqR4O9XL39GjcWZ96gCxOwLfgN2eQ',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZFBlVFVZdW95T0dFMHNIakVJUUZTcE1Ld21GdjJPbVR4OEhrd1ZBNiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777887848),('zhGFOgOPIIsFKPio2Yc0wm90qHFYVcE0QEJDCyLK',NULL,'120.28.195.55','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoicHpYbGJ3YmdVengzbDF0b2kzVXRWVGkycEdhOVBtR3pwR3p6eDA5RyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777886005),('zjr211Rf25mgxadILyUl9mAIP3u9s9x5vDWKEuSH',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaEtvUk1yNGgzNUVWZ1dzQ0NLYnRPVjhQNGNpVkp0blJJbTdTQjBYWiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777887111),('zTbxuwZG5eLemPFgYbgccbtKasCGWFNbzB9LIP5m',NULL,'180.191.234.63','Uptime-Kuma/1.23.17','YTozOntzOjY6Il90b2tlbiI7czo0MDoieHNJc285Q0REUGtaSVJqOW1qREZhQldmS0I3ODlmQXJneEptbUJ3TCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vbWFkYW5hbW9oYW5hY29sbGVnZXMuY29tIjtzOjU6InJvdXRlIjtzOjExOiJwdWJsaWMuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1777886742);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sf1_generated_reports`
--

DROP TABLE IF EXISTS `sf1_generated_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sf1_generated_reports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `school_year_id` bigint(20) unsigned NOT NULL,
  `grade_level_id` bigint(20) unsigned NOT NULL,
  `section_id` bigint(20) unsigned NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `progress` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `filename` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `error_message` text DEFAULT NULL,
  `generated_by` bigint(20) unsigned DEFAULT NULL,
  `generated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `source_updated_at` timestamp NULL DEFAULT NULL,
  `needs_regeneration` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sf1_unique_section_report` (`school_year_id`,`grade_level_id`,`section_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sf1_generated_reports`
--

LOCK TABLES `sf1_generated_reports` WRITE;
/*!40000 ALTER TABLE `sf1_generated_reports` DISABLE KEYS */;
INSERT INTO `sf1_generated_reports` VALUES (1,1,4,1,'outdated',100,'SF1_2025-2026_Grade_1_1-Gokula.xlsx','reports/sf1/1/SF1_2025-2026_Grade_1_1-Gokula.xlsx',NULL,1,'2026-05-11 06:42:25','2026-05-10 17:42:58','2026-05-11 17:55:50','2026-05-11 06:42:25',1);
/*!40000 ALTER TABLE `sf1_generated_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_document_uploads`
--

DROP TABLE IF EXISTS `student_document_uploads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_document_uploads` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL,
  `required_student_document_id` bigint(20) unsigned NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `original_filename` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'submitted',
  `remarks` text DEFAULT NULL,
  `uploaded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_doc_unique` (`student_id`,`required_student_document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_document_uploads`
--

LOCK TABLES `student_document_uploads` WRITE;
/*!40000 ALTER TABLE `student_document_uploads` DISABLE KEYS */;
/*!40000 ALTER TABLE `student_document_uploads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student_documents`
--

DROP TABLE IF EXISTS `student_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `student_documents` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned NOT NULL,
  `document_type_id` bigint(20) unsigned NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `original_filename` varchar(255) DEFAULT NULL,
  `source` varchar(50) NOT NULL DEFAULT 'uploaded',
  `status` varchar(50) NOT NULL DEFAULT 'submitted',
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `verified_by` bigint(20) unsigned DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_documents_student_document_unique` (`student_id`,`document_type_id`),
  KEY `student_documents_student_id_index` (`student_id`),
  KEY `student_documents_document_type_id_index` (`document_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student_documents`
--

LOCK TABLES `student_documents` WRITE;
/*!40000 ALTER TABLE `student_documents` DISABLE KEYS */;
INSERT INTO `student_documents` VALUES (1,28,1,'uploads/student-documents/28/document_1_1778348570.png','Picture7.png','uploaded','rejected',0,1,NULL,'Copy not clear','2026-05-10 00:38:50','2026-05-10 00:43:37');
/*!40000 ALTER TABLE `student_documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `students` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` varchar(50) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `suffix` varchar(20) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `birth_place` varchar(150) DEFAULT NULL,
  `mother_tongue` varchar(100) DEFAULT NULL,
  `is_ip` tinyint(1) NOT NULL DEFAULT 0,
  `ethnic_group` varchar(100) DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `lrn` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `contact_number` varchar(30) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `house_street` varchar(150) DEFAULT NULL,
  `barangay` varchar(100) DEFAULT NULL,
  `municipality_city` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `father_name` varchar(150) DEFAULT NULL,
  `father_contact` varchar(30) DEFAULT NULL,
  `mother_name` varchar(150) DEFAULT NULL,
  `mother_contact` varchar(30) DEFAULT NULL,
  `guardian_name` varchar(255) DEFAULT NULL,
  `guardian_relationship` varchar(100) DEFAULT NULL,
  `parent_guardian_contact` varchar(30) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `guardian_contact` varchar(30) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Active',
  `photo_path` varchar(255) DEFAULT NULL,
  `sex` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_students_student_id` (`student_id`),
  UNIQUE KEY `uq_students_lrn` (`lrn`),
  KEY `idx_students_last_name` (`last_name`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (1,'G0120250030','Jeelahhe Kris','Baclayon','Butaya',NULL,'2019-06-21','Dangcagan, Bukidnon','Binisaya',0,NULL,'Roman Catholic','459565240005',NULL,NULL,'Purok 3-A Poblacion, Dangcagan, Bukidnon','Purok 3-A','Poblacion','Dangcagan','Bukidnon',NULL,NULL,NULL,NULL,'Esther Lazarito Butaya','Grandparent',NULL,NULL,'09566192451','active',NULL,'female','2026-04-11 03:51:42','2026-05-11 06:41:16'),(2,'G0120250023','Renz Khian','Abriol','Camay',NULL,'2019-09-06',NULL,NULL,0,NULL,NULL,'404987240013',NULL,NULL,'P-6 East Kibawe, Kibawe, Bukidnon',NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,'Kce Shyne Asis Abriol',NULL,NULL,NULL,'09058040662','active',NULL,'male','2026-04-11 04:25:02','2026-04-11 04:25:02'),(3,'G0120250025','Prince Ethan','Alolino','Daño',NULL,'2019-06-16',NULL,NULL,0,NULL,NULL,'126309240020',NULL,NULL,'P-6 Sampagar, Damulog, Bukidnon',NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,'Saira Mae Quirino Alolino',NULL,NULL,NULL,'09501870887','active',NULL,'male','2026-04-11 04:41:57','2026-04-11 04:41:57'),(4,'K0020240028','Chloe','Guinita','Dela Victoria',NULL,'2018-11-13',NULL,NULL,0,NULL,NULL,'404986240003',NULL,NULL,'P4-A East Kibawe, Kibawe, Bukidnon',NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,'Cecille Oriendo Guinita',NULL,NULL,NULL,'09216535422','active',NULL,'female','2026-04-11 04:48:07','2026-04-11 04:48:07'),(5,'P0220230037','Albert Thomas','Gales','Jorge',NULL,'2018-12-18',NULL,NULL,0,NULL,NULL,'404986240008',NULL,NULL,'P-6 Palma, Kibawe, Bukidnon',NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,'Bede Gales',NULL,NULL,NULL,'09984685613','active',NULL,'male','2026-04-11 04:50:24','2026-04-11 04:50:24'),(6,'G0120250036','Brix Lee',NULL,'Lamag',NULL,'2019-05-01',NULL,NULL,0,NULL,NULL,'119068240049',NULL,NULL,'East Kibawe, Kibawe, Bukidnon',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Evelyn Mata Lamag',NULL,NULL,NULL,'0993-939-6156','active',NULL,'male','2026-04-11 10:09:03','2026-05-10 01:41:43'),(7,'P0120220006','Zabrina Blaire','Pagayon','Matic-an',NULL,'2019-02-18',NULL,NULL,0,NULL,NULL,'404986240005',NULL,NULL,'P-5 Migcuya, Dangcagan, Bukidnon',NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,'Teresa G. Pagayon',NULL,NULL,NULL,'09058034973','active',NULL,'female','2026-04-11 10:11:22','2026-04-11 10:11:22'),(8,'P0120220010','Zhia Scarlette','Baynosa','Mendez',NULL,'2019-03-19',NULL,NULL,0,NULL,NULL,'404986240006',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,'Checille Baynosa Mendez',NULL,NULL,NULL,'09057036254','active',NULL,'female','2026-04-17 06:27:27','2026-04-17 06:27:27'),(9,'G0120250037','Ezekiah',NULL,'Orong',NULL,'2019-09-27',NULL,NULL,0,NULL,NULL,'126443240021',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,'Cysep Pepito Orong',NULL,NULL,NULL,'09161938398','active',NULL,'female','2026-04-17 06:29:22','2026-04-17 06:29:22'),(10,'P0120220011','Donna Christ','Bañoc','Papasin',NULL,'2019-03-31',NULL,NULL,0,NULL,NULL,'404986240009',NULL,NULL,'P-3 Labuagon, Kibawe, Bukidnon',NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,'Cristy Bañoc',NULL,NULL,NULL,'09362463762','active',NULL,'female','2026-04-17 06:31:53','2026-04-17 06:31:53'),(11,'K0020240040','Athena Jay','Rule','Polistico',NULL,'2019-06-29',NULL,NULL,0,NULL,NULL,'404986240007',NULL,NULL,'P-4A East Kibawe, Kibawe, Bukidnon',NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,'Rengelyn Bragat Lalaguna',NULL,NULL,NULL,'09676534889','active',NULL,'female','2026-04-17 06:33:39','2026-04-17 06:33:39'),(12,'P0220230010','Pearlyzza Gold',NULL,'Ponce',NULL,'2019-01-18',NULL,NULL,0,NULL,NULL,'404986240010',NULL,NULL,'P-2 Old Damulog, Damulog, Bukidnon',NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,'Dexter Ponce',NULL,NULL,NULL,'09700863807|09638471257','active',NULL,'female','2026-04-17 06:35:46','2026-04-17 06:35:46'),(13,'G0120250046','Vella Cyra',NULL,'Solitana',NULL,'2019-08-17',NULL,NULL,0,NULL,NULL,'126438240087',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,'Gilda M. Solitana',NULL,NULL,NULL,NULL,'active',NULL,'female','2026-04-17 06:39:55','2026-04-17 06:39:55'),(14,'K0020240008','Aqueela','Saligumba','Tilos',NULL,'2019-06-09',NULL,NULL,0,NULL,NULL,'404986240013',NULL,NULL,'P-1B Talahiron, Kibawe, Bukidnon',NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,'Willah Mae S. Tilos',NULL,NULL,NULL,'09675267366','active',NULL,'female','2026-04-17 06:41:43','2026-04-17 06:41:43'),(15,'G0220250038','Josie Mae','Torculas','Acapulco',NULL,'2018-06-01',NULL,'Binisaya',0,NULL,NULL,'126443230017',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,'Maricel Torculas',NULL,NULL,NULL,NULL,'active',NULL,'female','2026-04-17 07:33:11','2026-04-19 22:26:30'),(16,'G0220250039','Dwince Alezzio','Butaya','Acob',NULL,'2018-07-23',NULL,NULL,0,NULL,NULL,'103624230002',NULL,NULL,'Purok 3A, Poblacion, Dangcagan, Bukidnon',NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,'Dwisther Love L. Butaya',NULL,NULL,NULL,'09911816486','active',NULL,'male','2026-04-17 07:37:00','2026-04-17 07:37:00'),(17,'G0220250042','Erich Emerald','Cebu','Asistido',NULL,'2018-05-16',NULL,NULL,0,NULL,NULL,'126327230025',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,NULL,'Cristy Joy T. Cebu',NULL,NULL,NULL,NULL,'active',NULL,'female','2026-04-17 07:39:20','2026-04-17 07:39:20'),(18,'K0020230001','Rhea Kassandra',NULL,'Balbutin',NULL,'2017-11-24','Kibawe, Bukidnon','Binisaya',0,NULL,'Roman Catholic','404986230001',NULL,NULL,NULL,'Malipayon','Natulongan','Kibawe','Bukidnon',NULL,'','Edcel Laries Balbutin',NULL,'Venus Larios Roiles','Aunt','09161306045',NULL,NULL,'active',NULL,'female','2026-04-19 14:26:08','2026-04-19 15:02:34'),(19,'K0020230002','Lhia Shayne','Roiles','Cagas',NULL,'2018-03-26','Kibawe, Bukidnon','Binisaya',0,NULL,'Roman Catholic','404986230002',NULL,NULL,NULL,'Malipayon','Natulongan','Kibawe','Bukidnon','Angelito Daguplo Cagas','','Venus Larios Roiles',NULL,'Venus Larios Roiles','parent','09161306045',NULL,NULL,'active',NULL,'female','2026-04-20 05:12:24','2026-04-20 05:12:24'),(20,'P0220150012','Trixie Marie','Mataquil','Romero',NULL,'2011-07-07','Kibawe, Bukidnon','Binisaya',0,NULL,'Baptist','404986150015',NULL,NULL,NULL,'Purok 3','Palma','Kibawe','Bukidnon','Arnie Salvo Romero',NULL,'Riza Agra-an Mataquil',NULL,'Riza Agra-an Mataquil','parent','09261625815',NULL,NULL,'active',NULL,'female','2026-04-20 05:33:22','2026-05-04 21:20:39'),(21,'P0220140003','George Carl','Pagas','Castillo',NULL,'2010-09-13','Kibawe, Bukidnon','Binisaya',0,NULL,'Roman Catholic','404986150022',NULL,NULL,NULL,'Purok 3','East Kibawe','Kibawe','Bukidnon','Albert Castillo','','Dienisel Beam',NULL,'Esmeralda Pagas','Grandparent','09979240366',NULL,NULL,'active',NULL,'male','2026-04-20 05:39:01','2026-04-20 05:39:01'),(22,'P0220140009','Raphael Adrian','Gelbolingo','Rivera',NULL,'2010-08-10','Don Carlos, Bukidnon','Binisaya',0,NULL,'Roman Catholic','404986150030',NULL,NULL,NULL,'Purok 7','Talahiron','Kibawe','Bukidnon',NULL,NULL,'Rebecca Bendanillo Gelbolingo',NULL,'Rebecca Bendanillo Gelbolingo','parent','09169604515',NULL,NULL,'active',NULL,'male','2026-04-20 05:44:10','2026-05-04 21:48:53'),(23,'P0120150005','Queen Quay','Baoy','Rejas',NULL,'2012-02-24','Kibawe, Bukidnon','Binisaya',0,NULL,'Roman Catholic','404986150005',NULL,NULL,NULL,'Puok 5','Palma','Kibawe','Bukidnon','Jovelito Sundo Rejas','09171388362','Joen Taganas Baoy','09178338307','Joen Taganas Baoy','Parent','09178338307',NULL,NULL,'active',NULL,'female','2026-04-20 05:59:15','2026-04-20 06:12:53'),(24,'K0020150023','Danna Mea','Sujetado','Ragmac',NULL,'2009-11-06','Kibawe, Bukidnon','Binisaya',0,NULL,'Grace Gospel Church of Christ','404986150028',NULL,NULL,NULL,'Purok 2','East Kibawe','Kibawe','Bukidnon','Dante Cepada Ragmac','09752202078','Mildred Fajardo Sujetado','09751059473','Dante Cepada Ragmac','Parent','09752202078',NULL,NULL,'active',NULL,'female','2026-04-20 06:28:14','2026-04-20 06:28:14'),(28,'P0120260001','Kuntidevi dasi','Mendez','Aquino',NULL,'2022-11-28','Tangub, Mis. Occ.','English',0,NULL,'Vaishnava',NULL,'nice.atma@gmail.com','09273390859',NULL,'611 Laurel St.','East Kibawe','Kibawe','Bukidnon','Niceto Aquino','09273390859','Cheryl Aquino','09059391446','Niceto Aquino','Parent','09273390859',NULL,'09273390859','active','uploads/students/photos/student_28_1777773368.jpg','female','2026-05-02 17:01:07','2026-05-03 09:41:03'),(29,'K0020230008','Alson Venz',NULL,'Guipetacio',NULL,'2018-01-04',NULL,NULL,0,NULL,NULL,'404986230004',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Active',NULL,'male','2026-05-04 20:40:17','2026-05-04 20:40:17'),(30,'K0020230032','Eva Tatyana',NULL,'Mandalunes',NULL,'2018-01-16',NULL,NULL,0,NULL,NULL,'404986230005',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Active',NULL,'female','2026-05-04 20:50:55','2026-05-04 20:50:55'),(31,'P0220240033','Angelou','Dausin','Adame',NULL,'2020-03-18','Don Carlos, Bukidnon','Binisaya',0,NULL,'Roman Catholic','404986250001',NULL,NULL,NULL,'Purok 1','East Kibawe','Kibawe','Bukidnon','Harve Barros Adame','09675264224','Charina Sarmento Dausin','09177134461','Harve Barros Adame','Parent','09675264224',NULL,NULL,'active',NULL,'male','2026-05-11 18:53:21','2026-05-11 19:01:44'),(32,'P0220240037','Scarlet Heart','Timosan','Caderao',NULL,'2020-02-15','Kibawe, Bukidnon','English',0,NULL,'Roman Catholic','404986250003',NULL,NULL,NULL,NULL,'Migcuya','Dangcagan','Bukidnon','Bege James Villar Caderao','09975134974','Mary Grace Anay Timosan',NULL,'Bege James Villar Caderao','Parent','09975134974',NULL,NULL,'active',NULL,'female','2026-05-11 19:06:12','2026-05-11 19:07:01'),(33,'P0220240027','Charles','Guinita','Dela Victoria',NULL,'2020-02-17','Kibawe, Bukidnon','Binisaya',0,NULL,'Roman Catholic','404986250010',NULL,NULL,NULL,'Purok 4A','East Kibawe','Kibawe','Bukidnon','Charlie Valledor Dela Victoria','09092799516','Cecille Oriendo Guinita','09216535422','Cecille Oriendo Guinita','Parent','09216535422',NULL,NULL,'active',NULL,'male','2026-05-11 19:10:22','2026-05-11 19:10:53'),(34,'P0220240003','Jernando Folice','Obaob','Paredes',NULL,'2019-09-21','Kibawe, Bukidnon','English',0,NULL,'Roman Catholic','404986250004',NULL,NULL,NULL,'252 P-6 Bliss Compound','West Kibawe','Kibawe','Bukidnon','Joy Marc Gomez Paredes','09753338501','Jennilyn Calma Obaob','09622482615','Joy Marc Gomez Paredes','Parent','09753338501',NULL,NULL,'active',NULL,'male','2026-05-11 20:48:37','2026-05-11 20:48:37'),(35,'K0020250022','Aris Michael','Suplido','Ayag',NULL,'2019-11-16','Maramag, Bukidnon','Binisaya',0,NULL,'Christian','404986250002',NULL,NULL,NULL,'Purok 4','Palma','Kibawe','Bukidnon','John Michael Rulida Ayag','09996733535','Monaliza Luzon Suplido','09074284480','Monaliza Luzon Suplido','Parent','09074284480',NULL,NULL,'active',NULL,'male','2026-05-11 21:04:35','2026-05-11 21:04:35'),(36,'K0020250005','Xiammarah Kelly',NULL,'Bendanio',NULL,'2019-12-02','Kibawe, Bukidnon',NULL,0,NULL,NULL,'404986250005',NULL,NULL,NULL,'Purok 7','Old Damulog','Damulog','Bukidnon',NULL,NULL,'Clesergyn Devino Bendanio','09700818735','Clesergyn Devino Bendanio','Parent','09700818735',NULL,NULL,'active',NULL,'female','2026-05-11 21:10:00','2026-05-11 21:10:00'),(37,'K0020250009','Prince Gabriel','Poblete','Cafe',NULL,NULL,NULL,NULL,0,NULL,NULL,'404986250009',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'active',NULL,'male','2026-05-11 21:23:02','2026-05-11 21:23:02'),(38,'K0020250006','John Sebastian',NULL,'Timtim',NULL,NULL,NULL,NULL,0,NULL,NULL,'404986250006',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'active',NULL,'male','2026-05-11 21:25:44','2026-05-11 21:25:44'),(39,'K0020250019','John Dawin','Clarido','Ygot',NULL,NULL,NULL,NULL,0,NULL,NULL,'404986250007',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'active',NULL,'male','2026-05-11 21:27:55','2026-05-11 21:27:55'),(40,'K0020250029','Zhoey Kharmelle','Acain','Goc-ong',NULL,NULL,NULL,NULL,0,NULL,NULL,'404986250008',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'active',NULL,'female','2026-05-11 21:30:30','2026-05-11 21:30:30'),(41,'K0020250040','Chrispaul Ivan Kieth','Suarez','Capuyan',NULL,NULL,NULL,NULL,0,NULL,NULL,'404986250011',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'active',NULL,'male','2026-05-11 21:32:43','2026-05-11 21:32:43');
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `subjects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `grade_level_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `grading_profile_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_subjects_code` (`code`),
  KEY `idx_subjects_grade_level_id` (`grade_level_id`),
  KEY `fk_subjects_grading_profile` (`grading_profile_id`),
  CONSTRAINT `fk_subjects_grade_level` FOREIGN KEY (`grade_level_id`) REFERENCES `grade_levels` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_subjects_grading_profile` FOREIGN KEY (`grading_profile_id`) REFERENCES `grading_profiles` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subjects`
--

LOCK TABLES `subjects` WRITE;
/*!40000 ALTER TABLE `subjects` DISABLE KEYS */;
INSERT INTO `subjects` VALUES (1,'LANG1','Language 1','Language for Grade 1',4,'2026-04-13 09:31:43','2026-04-13 09:31:43',NULL),(2,'R&L1','Reading and Literacy 1','Reading and Literacy for Grade 1 of the MATATAG Curriculum',4,'2026-04-13 09:32:36','2026-04-13 09:38:43',NULL),(3,'MATH1','Mathematics 1','Mathematics for Grade 1 of the MATATAG Curriculum',4,'2026-04-13 09:33:18','2026-04-13 09:38:12',NULL),(4,'MAKA1','Makabansa 1','Makabansa (nationalism/social studies) for Grade 1 of the MATATAG Curriculum',4,'2026-04-13 09:34:12','2026-04-13 09:37:54',NULL),(5,'GMRC1','Good Manners and Right Conduct 1','Good Manners and Right Conduct for Grade 1 of the MATATAG Curriculum',4,'2026-04-13 09:35:58','2026-04-13 09:37:38',NULL),(6,'MTB-MLE1','Mother Tongue 1','Mother Tongue Based - Multi-Language Education for Grade 1 of the K-12 Curriculum',4,'2026-04-13 09:41:12','2026-04-13 09:41:12',NULL),(7,'FIL1','Filipino 1','Filipino for Grade 1 of the K-12 Curriculum',4,'2026-04-13 09:42:04','2026-04-13 09:42:04',NULL),(8,'ENG1','English 1','English for Grade 1 of the K-12 Curriculum',4,'2026-04-13 09:43:30','2026-04-13 09:43:30',NULL),(9,'SCI1','Science 1','Science for Grade 1 of the K-12 Curriculum',4,'2026-04-13 09:44:10','2026-04-13 09:44:10',NULL),(10,'AP1','Araling Panlipunan 1','Araling Panlipunan for Grade 1 of the K-12 Curriculum',4,'2026-04-13 09:45:03','2026-04-13 09:45:03',NULL),(11,'EsP1','Edukasyon sa Pagpapakatao 1','Edukasyon sa Pagpapakatao for Grade 1 of the K-12 Curriculum',4,'2026-04-13 09:46:19','2026-04-13 09:46:19',NULL),(12,'MUS1','Music 1','Music for Grade 1 of the K-12 Curriculum',4,'2026-04-13 09:47:21','2026-04-13 09:47:21',NULL),(13,'ARTS1','Arts 1','Arts for Grade 1 of the K-12 Curriculum',4,'2026-04-13 09:47:48','2026-04-13 09:47:48',NULL),(14,'PE1','Physical Education 1','Physical Education for Grade 1 of the K-12 Curriculum',4,'2026-04-13 09:48:21','2026-04-13 09:48:21',NULL),(15,'HLTH1','Health 1','Health for Grade 1 of the K-12 Curriculum',4,'2026-04-13 09:49:09','2026-04-13 09:49:09',NULL),(16,'ENG2','English 2','English for Grade 2 of the K-12 Curriculum',5,'2026-04-14 06:22:16','2026-04-14 06:36:26',NULL),(17,'ENG3','English 3','English for Grade 3 of the K-12 Curriculum',6,'2026-04-14 06:22:52','2026-04-14 06:36:14',NULL),(18,'ENG4','English 4','English for Grade 4 of the K-12 Curriculum',7,'2026-04-14 06:23:27','2026-04-14 06:36:02',NULL),(19,'ENG5','English 5','English for Grade 5 of the K-12 Curriculum',8,'2026-04-14 06:24:03','2026-04-14 06:35:50',NULL),(20,'AP5','Araling Panlipunan 5','Araling Panlipunan for Grade 5 of the K-12 Curriculum',8,'2026-04-14 06:24:42','2026-04-14 06:24:42',NULL),(21,'GMRC2','Good Manners and Right Conduct 2','Good Manners and Right Conduct for Grade 2 of the MATATAG Curriculum',5,'2026-04-14 07:56:05','2026-04-14 07:56:05',NULL),(22,'SCI2','Science 2','Science for Grade 2 of the K-12 Curriculum',5,'2026-04-14 07:56:51','2026-04-14 07:56:51',NULL),(23,'SCI3','Science 3','Science for Grade 3 of the K-12 Curriculum',6,'2026-04-14 07:57:22','2026-04-14 07:57:22',NULL),(24,'SCI4','Science 4','Science for Grade 4 of the K-12 Curriculum',7,'2026-04-14 07:57:55','2026-04-14 07:57:55',NULL),(25,'R&W2','Reading and Writing 2','Reading and Writing for Grade 2. A supplemental subject of MMCI for grade schoolers',5,'2026-04-14 07:58:59','2026-04-14 07:58:59',NULL),(26,'TLE10','Technology and Livelihood Education 10','Technology and Livelihood for Grade 10 of the K-12 Curriculum',13,'2026-04-14 07:59:52','2026-04-14 07:59:52',NULL),(27,'MUS2','Music 2','Music for Grade 2 of the K-12 Curriculum',5,'2026-04-14 08:00:47','2026-04-14 08:00:47',NULL),(28,'ARTS2','Arts 2','Arts for Grade 2 of the K-12 Curriculum',5,'2026-04-14 08:01:27','2026-04-14 08:01:27',NULL),(29,'PE2','Physical Education 2','Physical Education for Grade 2 of the K-12 Curriculum',5,'2026-04-14 10:50:41','2026-04-14 10:50:41',NULL),(30,'HLTH2','Health 2','Health for Grade 2 of the K-12 Curriculum',5,'2026-04-14 10:51:13','2026-04-14 10:51:13',NULL),(31,'MUS3','Music 3','Music for Grade 3 of the K-12 Curriculum',6,'2026-04-14 10:51:59','2026-04-14 10:51:59',NULL),(32,'ARTS3','Arts 3','Arts for Grade 3 of the K-12 Curriculum',6,'2026-04-14 10:52:31','2026-04-14 10:52:31',NULL),(33,'PE3','Physical Education 3','Physical Education for Grade 3 of the K-12 Curriculum',6,'2026-04-14 10:53:07','2026-04-14 10:53:07',NULL),(34,'HLTH3','Health 3','Health for Grade 3 of the K-12 Curriculum',6,'2026-04-14 10:56:16','2026-04-14 10:56:16',NULL);
/*!40000 ALTER TABLE `subjects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teacher_load_schedules`
--

DROP TABLE IF EXISTS `teacher_load_schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `teacher_load_schedules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `teacher_load_id` bigint(20) unsigned NOT NULL,
  `day_of_week` tinyint(3) unsigned NOT NULL,
  `time_start` time NOT NULL,
  `time_end` time NOT NULL,
  `room` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_tls_teacher_load` (`teacher_load_id`),
  KEY `idx_tls_day_time` (`day_of_week`,`time_start`,`time_end`),
  KEY `idx_tls_room` (`room`),
  CONSTRAINT `fk_teacher_load_schedules_teacher_load` FOREIGN KEY (`teacher_load_id`) REFERENCES `teacher_loads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacher_load_schedules`
--

LOCK TABLES `teacher_load_schedules` WRITE;
/*!40000 ALTER TABLE `teacher_load_schedules` DISABLE KEYS */;
INSERT INTO `teacher_load_schedules` VALUES (16,5,2,'14:35:00','16:00:00','Kalindi','2026-04-14 07:47:13','2026-04-14 07:47:13'),(17,5,4,'14:35:00','16:00:00','Kalindi','2026-04-14 07:47:13','2026-04-14 07:47:13'),(18,6,2,'12:30:00','14:15:00','Sarasvati','2026-04-14 07:50:33','2026-04-14 07:50:33'),(19,6,4,'12:30:00','14:15:00','Sarasvati','2026-04-14 07:50:33','2026-04-14 07:50:33'),(20,7,1,'14:35:00','16:00:00','Ganges','2026-04-14 07:52:11','2026-04-14 07:52:11'),(21,7,3,'14:35:00','16:00:00','Ganges','2026-04-14 07:52:11','2026-04-14 07:52:11'),(22,7,5,'14:35:00','16:00:00','Ganges','2026-04-14 07:52:11','2026-04-14 07:52:11'),(23,8,2,'09:15:00','11:15:00','Ganges','2026-04-14 07:53:16','2026-04-14 07:53:16'),(24,8,4,'09:15:00','11:15:00','Ganges','2026-04-14 07:53:16','2026-04-14 07:53:16'),(25,4,1,'13:00:00','14:20:00','Vrindavan','2026-04-14 07:53:40','2026-04-14 07:53:40'),(26,4,3,'13:00:00','14:20:00','Vrindavan','2026-04-14 07:53:40','2026-04-14 07:53:40'),(27,4,5,'13:00:00','14:20:00','Vrindavan','2026-04-14 07:53:40','2026-04-14 07:53:40'),(28,3,1,'09:50:00','11:10:00','Gokula','2026-04-14 07:53:59','2026-04-14 07:53:59'),(29,3,3,'09:50:00','11:10:00','Gokula','2026-04-14 07:53:59','2026-04-14 07:53:59'),(30,3,5,'09:50:00','11:10:00','Gokula','2026-04-14 07:53:59','2026-04-14 07:53:59'),(31,1,1,'08:15:00','09:35:00','Gokula','2026-04-14 07:54:12','2026-04-14 07:54:12'),(32,1,3,'08:15:00','09:35:00','Gokula','2026-04-14 07:54:12','2026-04-14 07:54:12'),(33,1,5,'08:15:00','09:35:00','Gokula','2026-04-14 07:54:12','2026-04-14 07:54:12'),(34,9,1,'08:15:00','09:35:00','Vrindavan','2026-04-14 11:01:08','2026-04-14 11:01:08'),(35,9,3,'08:15:00','09:35:00','Vrindavan','2026-04-14 11:01:08','2026-04-14 11:01:08'),(36,9,5,'08:15:00','09:35:00','Vrindavan','2026-04-14 11:01:08','2026-04-14 11:01:08'),(37,10,1,'09:50:00','11:10:00','Vrindavan','2026-04-14 11:02:25','2026-04-14 11:02:25'),(38,10,3,'09:50:00','11:10:00','Vrindavan','2026-04-14 11:02:25','2026-04-14 11:02:25'),(39,10,5,'09:50:00','11:10:00','Vrindavan','2026-04-14 11:02:25','2026-04-14 11:02:25'),(40,11,1,'13:00:00','14:20:00','Kalindi','2026-04-14 11:04:19','2026-04-14 11:04:19'),(41,11,3,'13:00:00','14:20:00','Kalindi','2026-04-14 11:04:19','2026-04-14 11:04:19'),(42,11,5,'13:00:00','14:20:00','Kalindi','2026-04-14 11:04:19','2026-04-14 11:04:19'),(45,12,1,'14:35:00','16:00:00','Sarasvati','2026-04-15 04:32:59','2026-04-15 04:32:59'),(46,12,3,'14:35:00','16:00:00','Sarasvati','2026-04-15 04:32:59','2026-04-15 04:32:59'),(47,12,5,'14:35:00','16:00:00','Sarasvati','2026-04-15 04:32:59','2026-04-15 04:32:59'),(52,13,2,'07:00:00','09:00:00','Vrindavan','2026-04-15 04:43:31','2026-04-15 04:43:31'),(53,13,4,'07:00:00','09:00:00','Vrindavan','2026-04-15 04:43:31','2026-04-15 04:43:31'),(54,14,2,'09:15:00','11:15:00','Vaikuntha','2026-04-15 04:46:22','2026-04-15 04:46:22'),(55,14,4,'09:15:00','11:15:00','Vaikuntha','2026-04-15 04:46:22','2026-04-15 04:46:22'),(56,15,2,'14:30:00','16:00:00','Gokula','2026-04-15 04:53:51','2026-04-15 04:53:51'),(57,15,4,'14:30:00','16:00:00','Gokula','2026-04-15 04:53:51','2026-04-15 04:53:51'),(60,16,2,'12:30:00','14:15:00','Kalindi','2026-04-17 03:21:45','2026-04-17 03:21:45'),(61,16,4,'12:30:00','14:15:00','Kalindi','2026-04-17 03:21:45','2026-04-17 03:21:45');
/*!40000 ALTER TABLE `teacher_load_schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teacher_load_sections`
--

DROP TABLE IF EXISTS `teacher_load_sections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `teacher_load_sections` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `teacher_load_id` bigint(20) unsigned NOT NULL,
  `section_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_teacher_load_section` (`teacher_load_id`,`section_id`),
  KEY `idx_teacher_load_sections_load` (`teacher_load_id`),
  KEY `idx_teacher_load_sections_section` (`section_id`),
  CONSTRAINT `fk_teacher_load_sections_load` FOREIGN KEY (`teacher_load_id`) REFERENCES `teacher_loads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_teacher_load_sections_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacher_load_sections`
--

LOCK TABLES `teacher_load_sections` WRITE;
/*!40000 ALTER TABLE `teacher_load_sections` DISABLE KEYS */;
INSERT INTO `teacher_load_sections` VALUES (7,5,3,'2026-04-14 07:47:13','2026-04-14 07:47:13'),(8,6,4,'2026-04-14 07:50:33','2026-04-14 07:50:33'),(9,7,5,'2026-04-14 07:52:11','2026-04-14 07:52:11'),(10,8,5,'2026-04-14 07:53:16','2026-04-14 07:53:16'),(11,4,2,'2026-04-14 07:53:40','2026-04-14 07:53:40'),(12,3,1,'2026-04-14 07:53:59','2026-04-14 07:53:59'),(13,1,1,'2026-04-14 07:54:12','2026-04-14 07:54:12'),(14,9,2,'2026-04-14 11:01:08','2026-04-14 11:01:08'),(15,10,2,'2026-04-14 11:02:25','2026-04-14 11:02:25'),(16,11,3,'2026-04-14 11:04:19','2026-04-14 11:04:19'),(18,12,4,'2026-04-15 04:32:59','2026-04-15 04:32:59'),(21,13,2,'2026-04-15 04:43:31','2026-04-15 04:43:31'),(22,14,10,'2026-04-15 04:46:22','2026-04-15 04:46:22'),(23,15,1,'2026-04-15 04:53:51','2026-04-15 04:53:51'),(24,16,3,'2026-04-15 04:55:46','2026-04-15 04:55:46');
/*!40000 ALTER TABLE `teacher_load_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teacher_load_subjects`
--

DROP TABLE IF EXISTS `teacher_load_subjects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `teacher_load_subjects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `teacher_load_id` bigint(20) unsigned NOT NULL,
  `subject_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_teacher_load_subject` (`teacher_load_id`,`subject_id`),
  KEY `idx_teacher_load_subjects_load` (`teacher_load_id`),
  KEY `idx_teacher_load_subjects_subject` (`subject_id`),
  CONSTRAINT `fk_teacher_load_subjects_load` FOREIGN KEY (`teacher_load_id`) REFERENCES `teacher_loads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_teacher_load_subjects_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacher_load_subjects`
--

LOCK TABLES `teacher_load_subjects` WRITE;
/*!40000 ALTER TABLE `teacher_load_subjects` DISABLE KEYS */;
INSERT INTO `teacher_load_subjects` VALUES (7,5,17,'2026-04-14 07:47:13','2026-04-14 07:47:13'),(8,6,18,'2026-04-14 07:50:33','2026-04-14 07:50:33'),(9,7,19,'2026-04-14 07:52:11','2026-04-14 07:52:11'),(10,8,20,'2026-04-14 07:53:16','2026-04-14 07:53:16'),(11,4,16,'2026-04-14 07:53:40','2026-04-14 07:53:40'),(12,3,8,'2026-04-14 07:53:59','2026-04-14 07:53:59'),(13,1,5,'2026-04-14 07:54:12','2026-04-14 07:54:12'),(14,9,21,'2026-04-14 11:01:08','2026-04-14 11:01:08'),(15,10,22,'2026-04-14 11:02:25','2026-04-14 11:02:25'),(16,11,23,'2026-04-14 11:04:19','2026-04-14 11:04:19'),(18,12,24,'2026-04-15 04:32:59','2026-04-15 04:32:59'),(21,13,25,'2026-04-15 04:43:31','2026-04-15 04:43:31'),(22,14,26,'2026-04-15 04:46:22','2026-04-15 04:46:22'),(23,15,9,'2026-04-15 04:53:51','2026-04-15 04:53:51'),(25,16,32,'2026-04-17 03:21:45','2026-04-17 03:21:45'),(26,16,34,'2026-04-17 03:21:45','2026-04-17 03:21:45'),(27,16,31,'2026-04-17 03:21:45','2026-04-17 03:21:45'),(28,16,33,'2026-04-17 03:21:45','2026-04-17 03:21:45');
/*!40000 ALTER TABLE `teacher_load_subjects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teacher_load_terms`
--

DROP TABLE IF EXISTS `teacher_load_terms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `teacher_load_terms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `teacher_load_id` bigint(20) unsigned NOT NULL,
  `term_no` tinyint(3) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teacher_load_terms_teacher_load_id_term_no_unique` (`teacher_load_id`,`term_no`),
  KEY `teacher_load_terms_term_no_index` (`term_no`),
  CONSTRAINT `teacher_load_terms_teacher_load_id_foreign` FOREIGN KEY (`teacher_load_id`) REFERENCES `teacher_loads` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacher_load_terms`
--

LOCK TABLES `teacher_load_terms` WRITE;
/*!40000 ALTER TABLE `teacher_load_terms` DISABLE KEYS */;
INSERT INTO `teacher_load_terms` VALUES (1,1,1,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(2,3,1,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(3,15,1,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(4,4,1,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(5,5,1,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(6,6,1,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(7,7,1,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(8,8,1,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(9,9,1,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(10,10,1,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(11,11,1,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(12,12,1,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(13,13,1,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(14,14,1,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(15,16,1,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(16,1,2,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(17,3,2,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(18,15,2,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(19,4,2,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(20,5,2,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(21,6,2,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(22,7,2,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(23,8,2,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(24,9,2,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(25,10,2,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(26,11,2,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(27,12,2,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(28,13,2,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(29,14,2,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(30,16,2,'2026-04-26 09:11:12','2026-04-26 09:11:12'),(31,1,3,'2026-04-26 09:11:13','2026-04-26 09:11:13'),(32,3,3,'2026-04-26 09:11:13','2026-04-26 09:11:13'),(33,15,3,'2026-04-26 09:11:13','2026-04-26 09:11:13'),(34,4,3,'2026-04-26 09:11:13','2026-04-26 09:11:13'),(35,5,3,'2026-04-26 09:11:13','2026-04-26 09:11:13'),(36,6,3,'2026-04-26 09:11:13','2026-04-26 09:11:13'),(37,7,3,'2026-04-26 09:11:13','2026-04-26 09:11:13'),(38,8,3,'2026-04-26 09:11:13','2026-04-26 09:11:13'),(39,9,3,'2026-04-26 09:11:13','2026-04-26 09:11:13'),(40,10,3,'2026-04-26 09:11:13','2026-04-26 09:11:13'),(41,11,3,'2026-04-26 09:11:13','2026-04-26 09:11:13'),(42,12,3,'2026-04-26 09:11:13','2026-04-26 09:11:13'),(43,13,3,'2026-04-26 09:11:13','2026-04-26 09:11:13'),(44,14,3,'2026-04-26 09:11:13','2026-04-26 09:11:13'),(45,16,3,'2026-04-26 09:11:13','2026-04-26 09:11:13');
/*!40000 ALTER TABLE `teacher_load_terms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teacher_loads`
--

DROP TABLE IF EXISTS `teacher_loads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `teacher_loads` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `teacher_id` bigint(20) unsigned NOT NULL,
  `subject_id` bigint(20) unsigned NOT NULL,
  `section_id` bigint(20) unsigned NOT NULL,
  `school_year_id` bigint(20) unsigned NOT NULL,
  `schedule_days` varchar(100) DEFAULT NULL,
  `schedule_time` varchar(100) DEFAULT NULL,
  `room` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_multi_grade` tinyint(1) NOT NULL DEFAULT 0,
  `is_combined` tinyint(1) NOT NULL DEFAULT 0,
  `load_type` enum('regular','combined','multi_grade') NOT NULL DEFAULT 'regular',
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_teacher_load_unique` (`teacher_id`,`subject_id`,`section_id`,`school_year_id`),
  KEY `fk_teacher_loads_subject` (`subject_id`),
  KEY `idx_teacher_loads_teacher` (`teacher_id`),
  KEY `idx_teacher_loads_section` (`section_id`),
  KEY `idx_teacher_loads_school_year` (`school_year_id`),
  CONSTRAINT `fk_teacher_loads_school_year` FOREIGN KEY (`school_year_id`) REFERENCES `school_years` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_teacher_loads_section` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_teacher_loads_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_teacher_loads_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teacher_loads`
--

LOCK TABLES `teacher_loads` WRITE;
/*!40000 ALTER TABLE `teacher_loads` DISABLE KEYS */;
INSERT INTO `teacher_loads` VALUES (1,3,5,1,1,'Mon/Wed/Fri','08:15 - 09:35','Gokula',1,0,0,'regular',NULL,'2026-04-13 09:58:17','2026-04-14 07:54:12'),(3,3,8,1,1,'Mon/Wed/Fri','09:50 - 11:10','Gokula',1,0,0,'regular',NULL,'2026-04-14 06:20:40','2026-04-14 07:53:59'),(4,3,16,2,1,'Mon/Wed/Fri','13:00 - 14:20','Vrindavan',1,0,0,'regular',NULL,'2026-04-14 06:26:22','2026-04-14 07:53:40'),(5,3,17,3,1,'Tue/Thu','14:35 - 16:00','Kalindi',1,0,0,'regular',NULL,'2026-04-14 07:47:13','2026-04-14 07:47:13'),(6,3,18,4,1,'Tue/Thu','12:30 - 14:15','Sarasvati',1,0,0,'regular',NULL,'2026-04-14 07:50:33','2026-04-14 07:50:33'),(7,3,19,5,1,'Mon/Wed/Fri','14:35 - 16:00','Ganges',1,0,0,'regular',NULL,'2026-04-14 07:52:11','2026-04-14 07:52:11'),(8,3,20,5,1,'Tue/Thu','09:15 - 11:15','Ganges',1,0,0,'regular',NULL,'2026-04-14 07:53:16','2026-04-14 07:53:16'),(9,4,21,2,1,'Mon/Wed/Fri','08:15 - 09:35','Vrindavan',1,0,0,'regular',NULL,'2026-04-14 11:01:08','2026-04-14 11:01:08'),(10,4,22,2,1,'Mon/Wed/Fri','09:50 - 11:10','Vrindavan',1,0,0,'regular',NULL,'2026-04-14 11:02:25','2026-04-14 11:02:25'),(11,4,23,3,1,'Mon/Wed/Fri','13:00 - 14:20','Kalindi',1,0,0,'regular',NULL,'2026-04-14 11:04:19','2026-04-14 11:04:19'),(12,4,24,4,1,'Mon/Wed/Fri','14:35 - 16:00','Sarasvati',1,0,0,'regular',NULL,'2026-04-15 04:32:06','2026-04-15 04:32:59'),(13,4,25,2,1,'Tue/Thu','07:00 - 09:00','Vrindavan',1,0,0,'regular',NULL,'2026-04-15 04:35:17','2026-04-15 04:43:31'),(14,4,26,10,1,'Tue/Thu','09:15 - 11:15','Vaikuntha',1,0,0,'regular',NULL,'2026-04-15 04:37:18','2026-04-15 04:46:22'),(15,4,9,1,1,'Tue/Thu','14:30 - 16:00','Gokula',1,0,0,'regular',NULL,'2026-04-15 04:53:51','2026-04-15 04:53:51'),(16,4,32,3,1,NULL,NULL,'Kalindi',1,0,1,'combined',NULL,'2026-04-15 04:55:46','2026-04-17 03:21:45');
/*!40000 ALTER TABLE `teacher_loads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teachers`
--

DROP TABLE IF EXISTS `teachers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `teachers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `employee_id_ref` bigint(20) unsigned DEFAULT NULL,
  `teacher_no` varchar(50) DEFAULT NULL,
  `specialization` varchar(150) DEFAULT NULL,
  `subject_specialty` varchar(150) DEFAULT NULL,
  `license_no` varchar(100) DEFAULT NULL,
  `major` varchar(100) DEFAULT NULL,
  `rank_title` varchar(100) DEFAULT NULL,
  `is_adviser` tinyint(1) NOT NULL DEFAULT 0,
  `date_hired_as_teacher` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_teachers_employee_id_ref` (`employee_id_ref`),
  UNIQUE KEY `uq_teachers_teacher_no` (`teacher_no`),
  KEY `idx_teachers_specialization` (`specialization`),
  KEY `idx_teachers_license_no` (`license_no`),
  KEY `idx_teachers_subject_specialty` (`subject_specialty`),
  CONSTRAINT `fk_teachers_employee` FOREIGN KEY (`employee_id_ref`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teachers`
--

LOCK TABLES `teachers` WRITE;
/*!40000 ALTER TABLE `teachers` DISABLE KEYS */;
INSERT INTO `teachers` VALUES (2,3,NULL,NULL,NULL,NULL,NULL,NULL,0,'2010-06-01','2026-04-12 10:07:11','2026-04-12 10:07:11'),(3,4,NULL,NULL,NULL,NULL,NULL,NULL,0,'2025-06-01','2026-04-13 02:41:22','2026-04-13 02:41:22'),(4,5,NULL,NULL,NULL,NULL,NULL,NULL,0,'2023-06-01','2026-04-14 01:07:03','2026-04-14 01:07:03'),(5,6,NULL,NULL,NULL,NULL,NULL,NULL,0,'2023-06-01','2026-04-16 13:47:18','2026-04-16 13:47:18'),(6,7,NULL,NULL,NULL,NULL,NULL,NULL,0,'2023-06-01','2026-04-16 13:53:00','2026-04-16 13:53:00'),(7,8,NULL,NULL,NULL,NULL,NULL,NULL,0,'2025-06-01','2026-04-16 13:59:49','2026-04-16 13:59:49'),(8,9,NULL,NULL,NULL,NULL,NULL,NULL,0,'2025-06-01','2026-04-16 14:04:45','2026-04-16 14:04:45'),(9,10,NULL,NULL,NULL,NULL,NULL,NULL,0,'2022-06-01','2026-04-16 14:11:08','2026-04-16 14:11:08'),(10,11,NULL,NULL,NULL,NULL,NULL,NULL,0,'2022-06-01','2026-04-19 10:16:35','2026-04-19 10:16:35'),(11,12,NULL,NULL,NULL,NULL,NULL,NULL,0,'2025-06-01','2026-04-19 10:18:19','2026-04-19 10:18:19');
/*!40000 ALTER TABLE `teachers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_roles`
--

DROP TABLE IF EXISTS `user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_roles_user_role` (`user_id`,`role_id`),
  KEY `idx_user_roles_user` (`user_id`),
  KEY `idx_user_roles_role` (`role_id`),
  CONSTRAINT `fk_user_roles_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_user_roles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_roles`
--

LOCK TABLES `user_roles` WRITE;
/*!40000 ALTER TABLE `user_roles` DISABLE KEYS */;
INSERT INTO `user_roles` VALUES (1,1,1,'2026-04-12 08:26:50','2026-04-12 08:26:50'),(2,2,5,NULL,NULL),(3,2,4,NULL,NULL),(4,3,4,NULL,NULL),(5,4,4,NULL,NULL),(6,5,4,NULL,NULL),(7,6,4,NULL,NULL),(8,7,4,NULL,NULL),(9,8,4,NULL,NULL),(10,9,4,NULL,NULL),(11,10,4,NULL,NULL),(12,11,4,NULL,NULL),(14,14,9,'2026-05-02 17:01:07','2026-05-02 17:01:07'),(15,15,9,'2026-05-10 01:36:12','2026-05-10 01:36:12');
/*!40000 ALTER TABLE `user_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint(20) unsigned DEFAULT NULL,
  `employee_id_ref` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `must_change_password` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `uq_users_username` (`username`),
  KEY `idx_users_employee_id_ref` (`employee_id_ref`),
  KEY `idx_users_is_active` (`is_active`),
  KEY `users_student_id_foreign` (`student_id`),
  CONSTRAINT `fk_users_employee` FOREIGN KEY (`employee_id_ref`) REFERENCES `employees` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `users_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,NULL,NULL,'Super Admin','admin','ses.admin@madanamohanacolleges.com',NULL,'$2y$12$J6FM66V9FOSG9x.0GEN3suRUOCITbmuhsv5oqaUI7iOHk1OkbzUkO',0,1,NULL,'lSQj1DPASry7h9aMwNuXTOTiHZvR3Xu8sKsZHlMAeEXYP8923QRZO3weKW3w','2026-04-12 08:25:15','2026-04-12 08:25:15'),(2,NULL,3,'Cheryl Aquino','cherylma','gagamaquino@gmail.com',NULL,'$2y$12$3kNCn866cA/W35s1t6Vjl.ozpNygkoOIAzD5jOmae2R/HKC.tB.su',0,1,NULL,NULL,'2026-04-12 10:07:11','2026-04-12 10:07:11'),(3,NULL,4,'Jenny Cowas','jennyc','mmci.g1.gokula@gmail.com',NULL,'$2y$12$rDCBjRnhnUW7bIAdkDO0zuZXXBGK8KC9vdBZ0pOK4suJrQHxww7LC',0,1,NULL,NULL,'2026-04-13 02:41:22','2026-04-13 02:41:22'),(4,NULL,5,'Mary Clesa Galanque','maryclesalg','mmci.g2.vrindavan@gmail.com',NULL,'$2y$12$X6kEkSPDOpxmvkEbaIBVoOjLbHY8nWR60V51TzzLusjgoPDg5hgWm',0,1,NULL,NULL,'2026-04-14 01:07:04','2026-04-14 01:07:04'),(5,NULL,6,'Saira Mae Alolino','sairamaea','mmci.g4.sarasvati@gmail.com',NULL,'$2y$12$Vo8vEfuPnpFAloJnxaULh.bsnoWQx/M2.jeqEZF2loSM7K32ITDG6',0,1,NULL,NULL,'2026-04-16 13:47:18','2026-04-16 13:47:18'),(6,NULL,7,'Desiree Soriano','desireegs','mmci.g3.kalindi@gmail.com',NULL,'$2y$12$aEi9QXBxbsW2OflH4JgPZu5NVNkHSuZgR.xeCtvmIFlLHdin2MnlG',0,1,NULL,NULL,'2026-04-16 13:53:01','2026-04-16 13:53:01'),(7,NULL,8,'Cristy Asistido','cristyca','mmci.g5.ganges@gmail.com',NULL,'$2y$12$psD0Gdt0MGD/3MetiH4jqO0uF0GqTpr0JLcrHG1bzKNfNeZ5Av/qa',0,1,NULL,NULL,'2026-04-16 13:59:50','2026-04-16 13:59:50'),(8,NULL,9,'Schichem Poblete','schichemmp','mmci.g6.jamuna@gmail.com',NULL,'$2y$12$hcPVHaZmpUeibwJPxT1Bn.Zw51uCxGWjznSyxp8TCDzj7NesjGWBC',0,1,NULL,NULL,'2026-04-16 14:04:46','2026-04-16 14:04:46'),(9,NULL,10,'Michelle Ladaga','michelle.el','mmci.g7.mathura@gmail.com',NULL,'$2y$12$DCNwcNPPFVBPr2ys1k8lf.MQSDU5b8stjV9tVccWsCLtVE9GHh3HK',0,1,NULL,NULL,'2026-04-16 14:11:08','2026-04-16 14:11:08'),(10,NULL,11,'Lezcil Joy Corpuz','lezcilec','mmci.g8.navadwip@gmail.com',NULL,'$2y$12$lpAfoKk5VDqN7kRVtNfbJ.sMaPTkuKlhjFfX.Kdiz59MIQhsD6qhe',0,1,NULL,NULL,'2026-04-19 10:16:35','2026-04-19 10:16:35'),(11,NULL,12,'Manelyn Tangub','manelynet','mmci.g10.vaikuntha@gmail.com',NULL,'$2y$12$kX9txH0xCsGV8dZzb8kq4u1gWhqBZdXr17ir7Tsm8Gm3TdDrZVix2',0,1,NULL,NULL,'2026-04-19 10:18:19','2026-04-19 10:18:19'),(14,28,NULL,'Kuntidevi dasi Aquino','P0120260001','nice.atma@gmail.com',NULL,'$2y$12$ChBz9EyMKYHHI4d0PtEFZeID3WgWKd1ySAvgmauSl5XIvnWmxnuc6',0,1,NULL,'Z8QjxlDYsdzwgDU3CKS3lpFsbunjHj8QKMmTg6qv8cfl5I42jZCCyo9Tg52X','2026-05-02 17:01:07','2026-05-10 01:06:53'),(15,6,NULL,'Brix Lee Lamag','G0120250036',NULL,NULL,'$2y$12$HFHteRjQB60Y7AxUeBvIpuTbWVc9VTtlRMbn/TCpRaDHJMLyARHgO',0,1,NULL,NULL,'2026-05-10 01:36:12','2026-05-10 01:39:23');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'laravel-35303934667f'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-11 20:30:37
