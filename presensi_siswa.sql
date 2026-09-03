-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: presensi_siswa
-- ------------------------------------------------------
-- Server version	8.4.3

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
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
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
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
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
-- Table structure for table `guru`
--

DROP TABLE IF EXISTS `guru`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `guru` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `guru_nip_unique` (`nip`),
  KEY `guru_user_id_foreign` (`user_id`),
  CONSTRAINT `guru_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `guru`
--

LOCK TABLES `guru` WRITE;
/*!40000 ALTER TABLE `guru` DISABLE KEYS */;
INSERT INTO `guru` VALUES (1,3,'Saidah, S.Pd.I','P',NULL,'2026-08-11 16:50:27','2026-08-23 23:33:44'),(2,5,'Ahmad Fauzan','L',NULL,'2026-08-13 00:59:02','2026-08-23 23:33:32'),(3,6,'Emil Fauzi','L',NULL,'2026-08-13 00:59:45','2026-08-23 23:33:11');
/*!40000 ALTER TABLE `guru` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
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
-- Table structure for table `kelas`
--

DROP TABLE IF EXISTS `kelas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `kelas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama_kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wali_kelas_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kelas_wali_kelas_id_foreign` (`wali_kelas_id`),
  CONSTRAINT `kelas_wali_kelas_id_foreign` FOREIGN KEY (`wali_kelas_id`) REFERENCES `guru` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kelas`
--

LOCK TABLES `kelas` WRITE;
/*!40000 ALTER TABLE `kelas` DISABLE KEYS */;
INSERT INTO `kelas` VALUES (4,'Kelas 9',NULL,'2026-08-24 01:13:07','2026-08-24 01:14:24'),(5,'Kelas 8',2,'2026-08-24 01:13:22','2026-08-31 16:54:26'),(6,'Kelas 7',NULL,'2026-08-24 01:13:55','2026-08-31 16:54:18');
/*!40000 ALTER TABLE `kelas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_presensi_manual`
--

DROP TABLE IF EXISTS `log_presensi_manual`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_presensi_manual` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `siswa_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `status_lama` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_baru` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `diubah_oleh` bigint unsigned NOT NULL,
  `waktu` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `log_presensi_manual_siswa_id_foreign` (`siswa_id`),
  KEY `log_presensi_manual_diubah_oleh_foreign` (`diubah_oleh`),
  CONSTRAINT `log_presensi_manual_diubah_oleh_foreign` FOREIGN KEY (`diubah_oleh`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `log_presensi_manual_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_presensi_manual`
--

LOCK TABLES `log_presensi_manual` WRITE;
/*!40000 ALTER TABLE `log_presensi_manual` DISABLE KEYS */;
INSERT INTO `log_presensi_manual` VALUES (18,30,'2026-08-29',NULL,'izin',NULL,7,'2026-08-29 01:47:58'),(19,30,'2026-08-29','izin','dibatalkan',NULL,7,'2026-08-29 01:48:11'),(20,30,'2026-08-29',NULL,'izin',NULL,7,'2026-08-29 01:48:55'),(21,31,'2026-08-29',NULL,'sakit',NULL,7,'2026-08-29 01:48:58');
/*!40000 ALTER TABLE `log_presensi_manual` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_qr`
--

DROP TABLE IF EXISTS `log_qr`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `log_qr` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `siswa_id` bigint unsigned NOT NULL,
  `qr_token_lama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qr_token_baru` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `diubah_oleh` bigint unsigned NOT NULL,
  `waktu` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `log_qr_siswa_id_foreign` (`siswa_id`),
  KEY `log_qr_diubah_oleh_foreign` (`diubah_oleh`),
  CONSTRAINT `log_qr_diubah_oleh_foreign` FOREIGN KEY (`diubah_oleh`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `log_qr_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_qr`
--

LOCK TABLES `log_qr` WRITE;
/*!40000 ALTER TABLE `log_qr` DISABLE KEYS */;
INSERT INTO `log_qr` VALUES (7,30,'4ca1bfb2-45f6-441c-a778-28504ca01237','7ae0dcec-51da-4e5c-b536-76b6c719be1a',7,'2026-08-24 08:57:18'),(8,30,'7ae0dcec-51da-4e5c-b536-76b6c719be1a','3efbac38-4176-4224-8054-7cb0b18a60b8',7,'2026-08-24 08:57:59');
/*!40000 ALTER TABLE `log_qr` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_08_11_041845_create_permission_tables',1),(5,'2026_08_11_044514_create_guru_table',1),(6,'2026_08_11_044531_create_kelas_table',1),(7,'2026_08_11_044549_create_siswa_table',1),(8,'2026_08_11_113618_add_is_active_to_users_table',1),(9,'2026_08_11_124225_create_log_qr_table',1),(10,'2026_08_11_231752_create_pengaturan_presensi_table',1),(11,'2026_08_11_231810_create_pengaturan_hari_libur_table',1),(12,'2026_08_11_231918_create_presensi_table',1),(13,'2026_08_12_074714_create_log_presensi_manual_table',2),(14,'2026_08_24_060542_add_jenis_kelamin_to_siswa_table',3),(15,'2026_08_24_060639_add_jenis_kelamin_to_guru_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',2),(2,'App\\Models\\User',3),(2,'App\\Models\\User',5),(2,'App\\Models\\User',6),(1,'App\\Models\\User',7);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
INSERT INTO `password_reset_tokens` VALUES ('ginajunianm@gmail.com','$2y$12$Hz/HCBY.lciZs5YEP9kuAO1.E7JG0UVAX.yUKYxhToX0o7JdbLnt.','2026-08-16 15:02:56');
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengaturan_hari_libur`
--

DROP TABLE IF EXISTS `pengaturan_hari_libur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengaturan_hari_libur` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `hari` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pengaturan_hari_libur_hari_unique` (`hari`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengaturan_hari_libur`
--

LOCK TABLES `pengaturan_hari_libur` WRITE;
/*!40000 ALTER TABLE `pengaturan_hari_libur` DISABLE KEYS */;
INSERT INTO `pengaturan_hari_libur` VALUES (8,'Minggu',NULL,'2026-08-16 01:13:08','2026-08-16 01:13:08');
/*!40000 ALTER TABLE `pengaturan_hari_libur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengaturan_presensi`
--

DROP TABLE IF EXISTS `pengaturan_presensi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengaturan_presensi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `jam_masuk_standar` time NOT NULL DEFAULT '07:00:00',
  `toleransi_terlambat_menit` int unsigned NOT NULL DEFAULT '15',
  `jam_pulang_standar` time NOT NULL DEFAULT '15:00:00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengaturan_presensi`
--

LOCK TABLES `pengaturan_presensi` WRITE;
/*!40000 ALTER TABLE `pengaturan_presensi` DISABLE KEYS */;
INSERT INTO `pengaturan_presensi` VALUES (1,'19:00:00',5,'02:00:00','2026-08-11 16:27:39','2026-08-16 01:13:08');
/*!40000 ALTER TABLE `pengaturan_presensi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `presensi`
--

DROP TABLE IF EXISTS `presensi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `presensi` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `siswa_id` bigint unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `status` enum('hadir','terlambat','izin','sakit','alpa') COLLATE utf8mb4_unicode_ci NOT NULL,
  `waktu_masuk` time DEFAULT NULL,
  `dicatat_oleh_masuk` bigint unsigned DEFAULT NULL,
  `waktu_pulang` time DEFAULT NULL,
  `dicatat_oleh_pulang` bigint unsigned DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `presensi_siswa_id_tanggal_unique` (`siswa_id`,`tanggal`),
  KEY `presensi_dicatat_oleh_masuk_foreign` (`dicatat_oleh_masuk`),
  KEY `presensi_dicatat_oleh_pulang_foreign` (`dicatat_oleh_pulang`),
  CONSTRAINT `presensi_dicatat_oleh_masuk_foreign` FOREIGN KEY (`dicatat_oleh_masuk`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `presensi_dicatat_oleh_pulang_foreign` FOREIGN KEY (`dicatat_oleh_pulang`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `presensi_siswa_id_foreign` FOREIGN KEY (`siswa_id`) REFERENCES `siswa` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `presensi`
--

LOCK TABLES `presensi` WRITE;
/*!40000 ALTER TABLE `presensi` DISABLE KEYS */;
INSERT INTO `presensi` VALUES (13,45,'2026-08-24','terlambat','22:07:51',7,NULL,NULL,NULL,'2026-08-24 15:07:51','2026-08-24 15:07:51'),(14,46,'2026-08-24','terlambat','22:09:39',7,NULL,NULL,NULL,'2026-08-24 15:09:39','2026-08-24 15:09:39'),(16,30,'2026-08-29','izin',NULL,NULL,NULL,NULL,NULL,'2026-08-29 01:48:55','2026-08-29 01:48:55'),(17,31,'2026-08-29','sakit',NULL,NULL,NULL,NULL,NULL,'2026-08-29 01:48:58','2026-08-29 01:48:58'),(18,46,'2026-08-31','terlambat','23:45:23',5,NULL,NULL,NULL,'2026-08-31 16:45:23','2026-08-31 16:45:23'),(19,45,'2026-08-31','terlambat','23:46:01',5,NULL,NULL,NULL,'2026-08-31 16:46:01','2026-08-31 16:46:01');
/*!40000 ALTER TABLE `presensi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','web','2026-08-11 16:27:39','2026-08-11 16:27:39'),(2,'guru','web','2026-08-11 16:27:39','2026-08-11 16:27:39');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('25coNye3MEMHWhghpnv3z0h1D25eBX31y5PtRbQf',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJmVkNDUEExaUhwemlyQzlKM2hub2tkSVU3NzZySVZmMHZGSlFhd3hLIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDAiLCJyb3V0ZSI6IndlbGNvbWUifX0=',1788196499),('vVg67mTCzdQDgjbwIMNvbnheN0jbLh9LVOKK5vDU',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJFVUZQdzc0TGNSMEZYelloTUU5amNJd3FJMDJiQ2IybzV2RW9DZm5GIiwidXJsIjpbXSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9kYXNoYm9hcmQiLCJyb3V0ZSI6ImRhc2hib2FyZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoyfQ==',1788422969),('VwU75QU8352JNtqygCNm5seI3y8KM6AYcNO5Rw2L',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','eyJfdG9rZW4iOiI2cFNiSkV2bERKaDhmdkJDNUtlallDOUJ0a253RkNnYUFZaXZxSFhaIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9kYXNoYm9hcmQiLCJyb3V0ZSI6ImRhc2hib2FyZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoyfQ==',1788399884),('vysxQRdANuPKgLMOHh9aeyihnamxZycMveauuvs9',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36','eyJfdG9rZW4iOiJ4elpYZjQ4cTFab2RFV0VMT253anZTTXFCRzVBeGV4WWM0UWd3bnFkIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9kYXNoYm9hcmQiLCJyb3V0ZSI6ImRhc2hib2FyZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoyfQ==',1788355262);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `siswa`
--

DROP TABLE IF EXISTS `siswa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `siswa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kelas_id` bigint unsigned NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `siswa_nis_unique` (`nis`),
  UNIQUE KEY `siswa_qr_token_unique` (`qr_token`),
  KEY `siswa_kelas_id_foreign` (`kelas_id`),
  CONSTRAINT `siswa_kelas_id_foreign` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `siswa`
--

LOCK TABLES `siswa` WRITE;
/*!40000 ALTER TABLE `siswa` DISABLE KEYS */;
INSERT INTO `siswa` VALUES (30,'3133654561','A Restu Anshor','L',6,NULL,'3efbac38-4176-4224-8054-7cb0b18a60b8',1,'2026-08-24 01:27:50','2026-08-24 08:57:59'),(31,'3136132350','Abdul Latif Nurohman','L',6,NULL,'cf963574-4fa9-49cf-8c74-2a2ab915112d',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(32,'0133767180','Abdul Rahman Al Ghifari','L',6,NULL,'09b05690-22ff-498f-8c45-f705078961de',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(33,'3139339181','Aini Nailil Hamidah','P',6,NULL,'bf0bd850-14d7-4b58-87a6-4a041af64774',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(34,'3134124668','Alif Alpiana','L',6,NULL,'ab0d12f0-1765-4f3e-8ae2-21ecb87f0d26',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(35,'3137157529','Aulia Izzatunisa Thalita','P',6,NULL,'30ac6406-1bcb-4da5-9a15-c80c9a1aaa37',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(36,'3146924459','Calista Alya Aziza','P',6,NULL,'564352aa-f144-4e2d-9a5f-50cbcc69c1c0',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(37,'0138775889','Cheryl Fathan Amzad','L',6,NULL,'2a9bc3fb-bc1d-4298-9ae1-71b3baa74457',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(38,'3126440793','Delvina Novita','P',6,NULL,'b3338b44-aa58-45ce-879b-deae5e0ae412',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(39,'0134621551','Deri Parhan','L',6,NULL,'c880b646-439c-4097-b52a-1d0cf8e6ddc2',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(40,'0136641394','Diman Nugraha','L',6,NULL,'01e5e4fb-7582-4ee7-8b83-514aa698500b',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(41,'0132081794','Dini Feby Heryani','P',6,NULL,'e86209ff-da3f-411c-97bd-73475f3b9c6d',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(42,'3131139882','El Rizka Fahira Putri','P',6,NULL,'1d9f5f39-f7b4-49f6-8a3b-5fe1d3d25660',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(43,'3136866230','Haikal Akbar Nur Rohman','L',6,NULL,'ce8cc0bd-4dbc-4a0e-9feb-cfb381f3175a',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(44,'0146685663','Hasna Kamilah','P',6,NULL,'bd258ae3-2a1e-4fd2-9183-e40e3dd421a3',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(45,'3125559395','Abdul Halim Nurul Hamid','L',5,NULL,'63f047a1-5917-4026-bd0d-7b09265289a7',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(46,'3124134256','Acep Luthfi Al Faruq','L',5,NULL,'df281ddd-e153-46ad-827d-2ac41df41844',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(47,'0128785810','Adilia Nurlaila','P',5,NULL,'75db9ffc-72ba-49b7-a6d9-af6a2e3208d2',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(48,'3129348305','Agung Firmansyah','L',5,NULL,'7869b213-ad9d-4b67-ad1b-409dd3d8c238',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(49,'3120617522','Ahmad Agung Pratama','L',5,NULL,'45770889-bf8b-462b-bc23-7aaa7ccdac7b',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(50,'3134304540','Akbar Ar-Rafi','L',5,NULL,'c8e7e2a1-c91a-47a3-b807-d7b93b53f364',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(51,'3128235219','Aldi Wijaya','P',5,NULL,'3eced7c2-21f6-4ba4-b105-833306fbb346',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(52,'0121912667','Anggi Muhammad Hilmi','L',5,NULL,'11186a28-d1b3-496b-9c8d-aadb00d818df',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(53,'3129916667','Anis Khoerunisa','P',5,NULL,'18c1b601-fd6b-449c-a52f-9917c1eaf154',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(54,'3135228552','Aprillio Elden','L',5,NULL,'db335975-599c-4a43-bdaf-bf1d7eaa84d5',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(55,'0128692405','Athifa Zahwa Al Hakim','P',5,NULL,'d054ae96-e71f-40a1-84f5-cf4009265816',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(56,'0129107061','Citra Puspitasari','P',5,NULL,'0781354a-5fa5-42df-8831-aa28f06f038c',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(57,'3122218731','Diki Maulana','L',5,NULL,'6832cd7e-c7c9-4daa-acad-2905fb6c3a11',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(58,'3112821235','Dikri Abdul Aziz','L',5,NULL,'9a670054-fc6a-427b-8517-f33d82da388c',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(59,'0134900599','Dzaki Abhar Mubarok','P',5,NULL,'7330553a-57ec-41f6-aeba-465fa8afe789',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(60,'0116982000','Aini Nur Jannah','P',4,NULL,'56586a36-7e97-4328-a4ab-d20b219dad40',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(61,'0112397306','Amelia Alva Hakim','P',4,NULL,'9232e6aa-bfbd-445b-bc30-28251498cd18',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(62,'3104006899','Arman Maulana','L',4,'siswa-foto/19f1fb50-0c4f-483d-b16b-c96716b2b332.jpg','94713424-d3c9-4bc8-817b-15e238ddb8f2',1,'2026-08-24 01:27:50','2026-09-02 13:10:03'),(63,'0124527974','Aulia Nurul Adnin','P',4,NULL,'7f6bed68-f23e-4820-90a2-4ac5a05457e9',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(64,'3113893418','Daffa Naufal Hasan','L',4,NULL,'8a020027-31ec-4436-b55d-8f1d59dd3501',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(65,'0114058160','Danal Lutfirrohman','L',4,NULL,'ee8e6b20-0205-4cc9-8ed5-443d6636ae6b',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(66,'0112077865','Danil Lutfirrohim','L',4,NULL,'4748f3f5-5099-443f-b7d3-0df968a90dc4',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(67,'0116111823','Dede Rusli','L',4,NULL,'5819ff55-665c-413c-868e-3c8eee79cfdc',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(68,'0114546034','Devi Nurazizah','P',4,NULL,'e67c6fea-9b8a-4d1b-8284-7f8de1173abf',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(69,'3122208522','Dhea Nursyamsiyah','P',4,NULL,'0e320fc2-b871-4e8e-be65-8bd3c9bad8ae',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(70,'0118959909','Dianis Safitri','P',4,NULL,'217be041-a819-4890-9d1a-9650befbee64',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(71,'0113532752','Egi Nurholis Aliansyah','L',4,NULL,'ff011699-10e4-4e74-a015-c39f613f419e',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(72,'0114907347','Farhan Nulhakim','L',4,NULL,'580c9264-e7a4-4b69-b46b-814ebf01346e',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(73,'0115878529','Fathan Abdul Fatah Mutamim Anshori','L',4,NULL,'b9da9386-1aeb-4b9c-9998-aad9c3ceaa52',1,'2026-08-24 01:27:50','2026-08-24 01:27:50'),(74,'3124171428','Hasna Alya Nabila','P',4,NULL,'0b74bd9a-9916-4658-88e1-68e93a5a794b',1,'2026-08-24 01:27:50','2026-08-24 01:27:50');
/*!40000 ALTER TABLE `siswa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Test User','test@example.com','2026-08-11 16:27:38','$2y$12$K3WiFu.q.rXPR.3XjPRVJObVa4lwjfjpQAoxEJlLWsng9omilU1ki',1,'WzHbIIGigS','2026-08-11 16:27:38','2026-08-11 16:27:38'),(2,'Administrator','admin1@sekolah.test',NULL,'$2y$12$ec1sZjxB1tLmN19XJvpzcOzMqek1AY4xJfpYKnNkG6lEgvlZaapSa',1,'CmSmfZMTYs5OSTLkeBfLa9h0xwnHwjpnWSDw3TjJ12P95STSnijYDDu08pdy','2026-08-11 16:27:39','2026-08-13 23:24:11'),(3,'Saidah, S.Pd.I','saidahski@gmail.com',NULL,'$2y$12$Zh6h0dDjbN0OzIE0EYtYBO/K3r7f7XnFxT3jEjL6yA0dcI6peg0L2',1,NULL,'2026-08-11 16:50:27','2026-08-13 01:02:02'),(4,'ginian.m','ginian.m@gmail.com',NULL,'$2y$12$dJRkIZLpA3s4axr3.oGct.mjGnfGLSqtEqR9I89QCy4kdSWXcAsA.',1,NULL,'2026-08-12 04:41:18','2026-08-12 04:41:18'),(5,'Ahmad Fauzan','ahmadfzn@gmail.com',NULL,'$2y$12$pc3B6u2gqWHIz9kNya53MOUkx4dlHYfyeyDdouc6d2qfYscmGPpxq',1,NULL,'2026-08-13 00:59:02','2026-08-13 00:59:02'),(6,'Emil Fauzi','emilfauzi@gmail.com',NULL,'$2y$12$xPtfg156Kqkx9GsEhi4pd.FCHyIQ4muQdBQCpxznlMSNMsak7WXxe',1,'cYLRzGAw4e43p4UumrZPYwFTIPJdBxtSaDimmfYsQ7Tnepolu2GQlSGbV4UQ','2026-08-13 00:59:44','2026-08-13 00:59:44'),(7,'ginian.m','ginajunianm@gmail.com',NULL,'$2y$12$kGt/j0Isqww/4eBrQxTWCOFTsPyo27h8ANAxsdHpqPkCjsaOF7pVi',1,NULL,'2026-08-13 23:27:34','2026-08-16 14:59:19');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'presensi_siswa'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-03 21:49:50
