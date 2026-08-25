/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.18-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: vendo
-- ------------------------------------------------------
-- Server version	10.11.18-MariaDB-0+deb12u1

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
-- Table structure for table `avis`
--

DROP TABLE IF EXISTS `avis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `avis` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `produit_id` bigint(20) unsigned NOT NULL,
  `commande_id` bigint(20) unsigned DEFAULT NULL,
  `client_nom` varchar(255) NOT NULL,
  `note` tinyint(3) unsigned NOT NULL,
  `commentaire` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `avis_produit_id_foreign` (`produit_id`),
  KEY `avis_commande_id_foreign` (`commande_id`),
  CONSTRAINT `avis_commande_id_foreign` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `avis_produit_id_foreign` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `avis`
--

LOCK TABLES `avis` WRITE;
/*!40000 ALTER TABLE `avis` DISABLE KEYS */;
INSERT INTO `avis` VALUES
(1,1,NULL,'Kévin H.',5,'Très belle qualité, livraison rapide.','2026-08-14 09:25:28','2026-08-14 09:25:28'),
(2,4,NULL,'Marie C.',4,'Jolie couleur, taille parfaite.','2026-08-14 09:25:28','2026-08-14 09:25:28'),
(3,1,3,'youir',3,NULL,'2026-08-20 10:20:21','2026-08-20 10:20:21');
/*!40000 ALTER TABLE `avis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `boutiques`
--

DROP TABLE IF EXISTS `boutiques`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `boutiques` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `nom` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `localisation` varchar(200) DEFAULT NULL,
  `google_maps_url` varchar(500) DEFAULT NULL,
  `seuil_fidele` int(10) unsigned NOT NULL DEFAULT 0,
  `reduction_fidele` int(10) unsigned NOT NULL DEFAULT 0,
  `numero_mobile_money` varchar(255) DEFAULT NULL,
  `operateur_mobile_money` varchar(255) DEFAULT NULL,
  `duree_reservation_defaut_minutes` int(10) unsigned DEFAULT 360,
  `logo_url` varchar(255) DEFAULT NULL,
  `couverture_url` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `boutiques_slug_unique` (`slug`),
  KEY `boutiques_user_id_foreign` (`user_id`),
  CONSTRAINT `boutiques_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `boutiques`
--

LOCK TABLES `boutiques` WRITE;
/*!40000 ALTER TABLE `boutiques` DISABLE KEYS */;
INSERT INTO `boutiques` VALUES
(1,2,'Boutique Aïcha','boutique-aicha','Vêtements et accessoires en pagne, faits avec amour à Cotonou.',NULL,NULL,0,0,NULL,NULL,360,NULL,NULL,'2026-08-14 09:25:28','2026-08-14 09:25:28'),
(3,4,'Boutique Vitesse','boutique-vitesse',NULL,'Cotonou',NULL,0,0,NULL,NULL,360,NULL,NULL,'2026-08-20 16:10:35','2026-08-20 16:10:35');
/*!40000 ALTER TABLE `boutiques` ENABLE KEYS */;
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
INSERT INTO `cache` VALUES
('vendo-cache-0190947303|127.0.0.1','i:1;',1787245761),
('vendo-cache-0190947303|127.0.0.1:timer','i:1787245761;',1787245761),
('vendo-cache-0196141340|127.0.0.1','i:1;',1787319750),
('vendo-cache-0196141340|127.0.0.1:timer','i:1787319750;',1787319750),
('vendo-cache-0197123456|127.0.0.1','i:1;',1787306251),
('vendo-cache-0197123456|127.0.0.1:timer','i:1787306251;',1787306251),
('vendo-cache-97000000|127.0.0.1','i:1;',1787245793),
('vendo-cache-97000000|127.0.0.1:timer','i:1787245793;',1787245793);
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
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `boutique_id` bigint(20) unsigned NOT NULL,
  `nom` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `ordre` smallint(5) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_boutique_id_slug_unique` (`boutique_id`,`slug`),
  CONSTRAINT `categories_boutique_id_foreign` FOREIGN KEY (`boutique_id`) REFERENCES `boutiques` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES
(1,1,'vêtement','vetement',1,'2026-08-17 13:55:09','2026-08-17 13:55:09');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commandes`
--

DROP TABLE IF EXISTS `commandes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `commandes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `boutique_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `reference_courte` varchar(255) NOT NULL,
  `client_nom` varchar(255) NOT NULL,
  `client_telephone` varchar(255) NOT NULL,
  `client_localite` varchar(255) DEFAULT NULL,
  `statut` enum('en_attente','confirmee','livree','annulee','retiree') DEFAULT 'en_attente',
  `montant_produit` int(10) unsigned NOT NULL,
  `mode_retrait` varchar(255) DEFAULT NULL,
  `code_retrait` varchar(255) DEFAULT NULL,
  `statut_retrait` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `commandes_reference_courte_unique` (`reference_courte`),
  KEY `commandes_boutique_id_foreign` (`boutique_id`),
  KEY `commandes_user_id_foreign` (`user_id`),
  CONSTRAINT `commandes_boutique_id_foreign` FOREIGN KEY (`boutique_id`) REFERENCES `boutiques` (`id`) ON DELETE CASCADE,
  CONSTRAINT `commandes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commandes`
--

LOCK TABLES `commandes` WRITE;
/*!40000 ALTER TABLE `commandes` DISABLE KEYS */;
INSERT INTO `commandes` VALUES
(1,1,NULL,'VE-7A1Q','Kévin H.','0196000000','Fidjrossè, Cotonou','annulee',15000,NULL,NULL,NULL,'2026-08-14 09:25:28','2026-08-20 10:34:59'),
(2,1,NULL,'VE-2M5T','Marie C.','0195000000','Cadjèhoun, Cotonou','annulee',25000,NULL,NULL,NULL,'2026-08-14 09:25:28','2026-08-20 10:21:40'),
(3,1,NULL,'VE-3P7O','youir','0190947303','Cocotomey','livree',24000,NULL,NULL,NULL,'2026-08-20 10:19:50','2026-08-20 10:21:50'),
(4,1,NULL,'VE-NMTE','Client Test','0197123456',NULL,'annulee',24000,NULL,NULL,NULL,'2026-08-21 08:52:42','2026-08-21 08:55:49');
/*!40000 ALTER TABLE `commandes` ENABLE KEYS */;
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
-- Table structure for table `ligne_commandes`
--

DROP TABLE IF EXISTS `ligne_commandes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ligne_commandes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `commande_id` bigint(20) unsigned NOT NULL,
  `produit_id` bigint(20) unsigned NOT NULL,
  `nom_produit` varchar(255) NOT NULL,
  `prix_unitaire` int(10) unsigned NOT NULL,
  `quantite` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ligne_commandes_commande_id_foreign` (`commande_id`),
  KEY `ligne_commandes_produit_id_foreign` (`produit_id`),
  CONSTRAINT `ligne_commandes_commande_id_foreign` FOREIGN KEY (`commande_id`) REFERENCES `commandes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ligne_commandes_produit_id_foreign` FOREIGN KEY (`produit_id`) REFERENCES `produits` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ligne_commandes`
--

LOCK TABLES `ligne_commandes` WRITE;
/*!40000 ALTER TABLE `ligne_commandes` DISABLE KEYS */;
INSERT INTO `ligne_commandes` VALUES
(1,1,1,'Robe en pagne',12000,1,'2026-08-14 09:25:28','2026-08-14 09:25:28'),
(2,2,2,'Boubou brodé',25000,1,'2026-08-14 09:25:28','2026-08-14 09:25:28'),
(3,3,1,'Robe en pagne',12000,2,'2026-08-20 10:19:50','2026-08-20 10:19:50'),
(4,4,1,'Robe en pagne',12000,2,'2026-08-21 08:52:42','2026-08-21 08:52:42');
/*!40000 ALTER TABLE `ligne_commandes` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2026_08_14_000001_create_boutiques_table',1),
(5,'2026_08_14_000002_create_produits_table',1),
(6,'2026_08_14_000003_create_commandes_table',1),
(7,'2026_08_14_000004_create_ligne_commandes_table',1),
(8,'2026_08_14_000005_create_avis_table',1),
(9,'2026_08_17_000001_add_localisation_to_boutiques_table',2),
(10,'2026_08_17_000002_add_commande_id_to_avis_table',2),
(11,'2026_08_17_000003_create_categories_table',3),
(12,'2026_08_17_000004_add_categorie_id_to_produits_table',3),
(13,'2026_08_17_000005_add_user_id_to_commandes_table',3),
(14,'2026_08_17_152432_add_google_maps_url_to_boutiques_table',4),
(15,'2026_08_17_160409_add_est_en_solde_to_produits_table',5),
(16,'2026_08_17_160420_add_couverture_url_to_boutiques_table',5),
(17,'2026_08_17_160420_add_fidelite_to_boutiques_table',5),
(18,'2026_08_18_000001_add_mobile_money_and_reservation_to_boutiques_table',6),
(19,'2026_08_18_000002_add_retrait_to_commandes_table',6),
(20,'2026_08_18_000003_add_retiree_to_statut_enum_commandes_table',7),
(21,'2026_08_20_000001_convertir_numeros_telephone_v10_chiffres',8);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `produits`
--

DROP TABLE IF EXISTS `produits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `produits` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `boutique_id` bigint(20) unsigned NOT NULL,
  `categorie_id` bigint(20) unsigned DEFAULT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `prix` int(10) unsigned NOT NULL,
  `prix_promo` int(10) unsigned DEFAULT NULL,
  `stock_quantite` int(10) unsigned NOT NULL DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL,
  `est_disponible` tinyint(1) NOT NULL DEFAULT 1,
  `est_en_solde` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `produits_boutique_id_foreign` (`boutique_id`),
  KEY `produits_categorie_id_foreign` (`categorie_id`),
  CONSTRAINT `produits_boutique_id_foreign` FOREIGN KEY (`boutique_id`) REFERENCES `boutiques` (`id`) ON DELETE CASCADE,
  CONSTRAINT `produits_categorie_id_foreign` FOREIGN KEY (`categorie_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `produits`
--

LOCK TABLES `produits` WRITE;
/*!40000 ALTER TABLE `produits` DISABLE KEYS */;
INSERT INTO `produits` VALUES
(1,1,NULL,'Robe en pagne','Description courte du produit Robe en pagne.',15000,12000,7,'robe-en-pagne.png',1,1,'2026-08-14 09:25:28','2026-08-21 08:55:49'),
(2,1,NULL,'Boubou brodé','Description courte du produit Boubou brodé.',25000,NULL,4,'boubou-brode.png',1,0,'2026-08-14 09:25:28','2026-08-20 10:21:40'),
(3,1,NULL,'Sac à main en tissu','Description courte du produit Sac à main en tissu.',8000,NULL,0,'sac-a-main-chanvre-rose.png',1,0,'2026-08-14 09:25:28','2026-08-17 15:22:58'),
(4,1,NULL,'Turban assorti','Description courte du produit Turban assorti.',5000,NULL,4,'turban.png',1,0,'2026-08-14 09:25:28','2026-08-17 15:22:58'),
(5,1,NULL,'Robes Test Fresh',NULL,3000,NULL,5,'robe.png',1,0,'2026-08-14 14:05:28','2026-08-17 15:22:58'),
(6,1,NULL,'pagne tradritionnel',NULL,4000,NULL,3,'pagne.png',1,0,'2026-08-14 14:08:29','2026-08-17 15:22:58');
/*!40000 ALTER TABLE `produits` ENABLE KEYS */;
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
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES
('32rk0qs4KiCgAV0YijZsomVCCYDsy3jqChJz7qoO',NULL,'127.0.0.1','curl/7.88.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVVl4QUVzeE5EdWJrTTBQSVB5SDh3RG9jZzFWam00WlJQdUx5MHY1USI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWdpc3RlciI7czo1OiJyb3V0ZSI7czo4OiJyZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1787318201),
('7n3NhcgDOCPPrAZ4hqNgOZ28SjQeA9RpJ6h57PcP',NULL,'127.0.0.1','curl/7.88.1','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiTW5QUmJETmZPY0hkaFk1RVNUNTg0dXVXUXUwc0x0QkxKUG9pVVIwMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWdpc3RlciI7czo1OiJyb3V0ZSI7czo4OiJyZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjI6e2k6MDtzOjEwOiJfb2xkX2lucHV0IjtpOjE7czo2OiJlcnJvcnMiO31zOjM6Im5ldyI7YTowOnt9fXM6MTA6Il9vbGRfaW5wdXQiO2E6NDp7czo2OiJfdG9rZW4iO3M6NDA6Ik1uUFJiRE5mT2NIZGhZNUVTVDU4NHV1V1F1MHNMdEJMSlBvaVVSMDMiO3M6NDoibmFtZSI7czoxOiJYIjtzOjk6InRlbGVwaG9uZSI7czozOiIxMjMiO3M6MTI6ImJvdXRpcXVlX25vbSI7czoxOiJCIjt9czo2OiJlcnJvcnMiO086MzE6IklsbHVtaW5hdGVcU3VwcG9ydFxWaWV3RXJyb3JCYWciOjE6e3M6NzoiACoAYmFncyI7YToxOntzOjc6ImRlZmF1bHQiO086Mjk6IklsbHVtaW5hdGVcU3VwcG9ydFxNZXNzYWdlQmFnIjoyOntzOjExOiIAKgBtZXNzYWdlcyI7YToxOntzOjk6InRlbGVwaG9uZSI7YToxOntpOjA7czoxMDA6Ik51bcOpcm8gZGUgdMOpbMOpcGhvbmUgaW52YWxpZGUuIFV0aWxpc2V6IGxlIGZvcm1hdCBiw6luaW5vaXMgw6AgMTAgY2hpZmZyZXMgKGV4LiA6IDAxIDk3IDEyIDM0IDU2KS4iO319czo5OiIAKgBmb3JtYXQiO3M6ODoiOm1lc3NhZ2UiO319fX0=',1787319088),
('7Reb4co5Sb7voDrGAUGRlb9oP7w6q15Gb8so2QcJ',NULL,'127.0.0.1','curl/7.88.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoid1Jod09SdjIzYUxJNDlTQ0U2V3ZGQkFIZDBmTEVtSUlwaEs4SWRXYSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWdpc3RlciI7czo1OiJyb3V0ZSI7czo4OiJyZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1787318529),
('8fyNufw8i8EFTjG3yOWn30gaQXHnABq6DlIPeTqq',NULL,'127.0.0.1','curl/7.88.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOUw2OEdTN1hyY0tZMnFIc3p6cnBEQkdOWFl4SVZoS2s1R3YwZ243YyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ib3V0aXF1ZS9ib3V0aXF1ZS1haWNoYS9wYW5pZXIiO3M6NToicm91dGUiO3M6NjoicGFuaWVyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1787318529),
('iVHvohqjmwCwypT46bj5zZ1CNFXoVlZapJlcEf0d',NULL,'127.0.0.1','Mozilla/5.0 (iPhone; CPU iPhone OS 17_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoic1dwSENDN3ZDbGk3ZHFhRU55dDRvRlFKT2JOakdRYjRhUTFrbXRyUyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2Rhc2hib2FyZCI7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAiO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1787322837),
('jPxY9HnoSfuOqqoVMNjETCL9Wd1C77FuRi7oRmw2',NULL,'127.0.0.1','curl/7.88.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMlJiVGt4dWRxNFp4ZmdyNXRCZlNwSVFCRlNRNXJmYzlTOGtLQmhTRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWdpc3RlciI7czo1OiJyb3V0ZSI7czo4OiJyZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1787319087),
('LLjt2td8AsciVxbwJQvZ9jLbvbwPrb5bL5h09BIU',6,'127.0.0.1','curl/7.88.1','YTo0OntzOjY6Il90b2tlbiI7czo0MDoib1dteXpTRzlsdUlvZDV2TDZPYjRoVk1tSXFPeWJmWVR4aTNLaWlMWSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6OToiZGFzaGJvYXJkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Njt9',1787320508),
('mm8m6tkTIXxpS90IrmchP2sJ2nxpuY6PQXeSIhMt',NULL,'127.0.0.1','curl/7.88.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiajFuMEM1SGY1R1pnRFByTkNCclhpeFltUkk0bURiaXhMTHNoTFE3NyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1787318420),
('MtzuK14ZZNjjq7sEPlVCUd1l6cKsTxnuzbaMKYU2',NULL,'127.0.0.1','curl/7.88.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiN2dpZ0VTQjRGY1pwa1V0ektqamNmOGxxTDdIM20waWMySERwa09LMiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWdpc3RlciI7czo1OiJyb3V0ZSI7czo4OiJyZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1787318402),
('nLtN1MOP1rUTYHyuZ4zme61wjZkMUSYo9h6ad3RY',NULL,'127.0.0.1','curl/7.88.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYlBXWDFZdE9YeWh3RWlsMThVOWdRSXQxcXMxNEhHV2tta0VwT2wydSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWdpc3RlciI7czo1OiJyb3V0ZSI7czo4OiJyZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1787319087),
('nx9ZS466lbFqQEjuJGamtUgsGqGN96H5Erm8N4Q6',NULL,'127.0.0.1','curl/7.88.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYjJ2UUxhSFBGSnFoa29rVXRjbmpuRHY0RE9COVFhZ2h3OTU3WmVOaSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWdpc3RlciI7czo1OiJyb3V0ZSI7czo4OiJyZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1787318109),
('Og4qUVv77NIM2ro0ZEjXUgMnTzSzq9G6iz8MNdCk',NULL,'127.0.0.1','curl/7.88.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNmlHN1RSVVAxbVdrUXllODRoVFlqU1E3dXN3NzJCYTZ1cU1zOWlpRCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWdpc3RlciI7czo1OiJyb3V0ZSI7czo4OiJyZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1787319088),
('pPFtja1NiIg5o9TXemKZkfZfweXSyLagP0TB8Sjx',NULL,'127.0.0.1','curl/7.88.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUGFieVRYc0RsN0RhdW9ENERBY2RUa1FUN01vY3M2QW9aOUoyN3oyRCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWdpc3RlciI7czo1OiJyb3V0ZSI7czo4OiJyZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1787318486),
('QwgVnOExDP3M2NRRAvM4azykqOzFSW8tZiSYoF9X',NULL,'127.0.0.1','curl/7.88.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTlZva1lVVVRrTExVM3k5NzRKOFdoM2VnVnVHVkdOdUE5YVdTRWluSiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1787318510),
('uugrR1R3sD3urLdZXdpvdO7LBxkkebP6u8hOLR1G',NULL,'127.0.0.1','curl/7.88.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoicFFibFJaNzBqNmVLMjY2MVZtZFBra3pJUmFPYnJQdDNQdXBDUzNkcSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWdpc3RlciI7czo1OiJyb3V0ZSI7czo4OiJyZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1787318420),
('Y7s5sh3skNBWX6FGHKuDe1KyF9iuUIHZ3aYFE1ZC',NULL,'127.0.0.1','curl/7.88.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRjV3cGhhdUFWMmV0WUtrRjBocHVIMmlaUkZCdTJHcUh6SndSRFpxbCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ib3V0aXF1ZS9ib3V0aXF1ZS1haWNoYSI7czo1OiJyb3V0ZSI7czoyNToiYm91dGlxdWUtcHVibGlxdWUuYWNjdWVpbCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1787318529),
('YVe4q3PvnkKHvXkzg6eSlE6B7BeCiO0AwbUTED5D',NULL,'127.0.0.1','curl/7.88.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZmhGWkZEWW1jWnlGd01NQXA1ZERWSEdwbHIxMExmdkJkYkVCczFBbSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWdpc3RlciI7czo1OiJyb3V0ZSI7czo4OiJyZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1787318197),
('YwzS2I7e4mbQMGNRo6sM3FuUTu5Bsut2Av0yTlsF',NULL,'127.0.0.1','curl/7.88.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVE80Y1V4b0xXTXdVUmhudWV0bkw1QkNCTGVEVndmSmNyVEQwTzlqeSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ib3V0aXF1ZS9ib3V0aXF1ZS1haWNoYS9wYW5pZXIiO3M6NToicm91dGUiO3M6NjoicGFuaWVyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1787318551),
('ZtKPkdHOymaBVTz8EjHs1eyr94XmBOTFDw9bCwJA',NULL,'127.0.0.1','curl/7.88.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiV2pHY0htU2Rjc2xEM1IyWjZQSktycDRJV2tnRHB6YjBlRFhBMVg3biI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9ib3V0aXF1ZS9ib3V0aXF1ZS1haWNoYSI7czo1OiJyb3V0ZSI7czoyNToiYm91dGlxdWUtcHVibGlxdWUuYWNjdWVpbCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1787318529);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `telephone` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `role` enum('vendeur','client','admin') NOT NULL DEFAULT 'vendeur',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_telephone_unique` (`telephone`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'Admin Vendo','0190000001','admin@vendo.bj','admin',NULL,'$2y$12$oFHAi9Y0CFMvKVSUgnXpvO5IkoyZTw8xZtiitW0ngGiD/fv6Ajida',NULL,'2026-08-14 09:25:27','2026-08-14 09:25:27'),
(2,'Aïcha Dossou','0197000000','aicha@vendo.bj','vendeur',NULL,'$2y$12$BegsJjW4l.13UifVqAc/Uu.0DiLNYag9mB6n1aVTn3sCyMXbwirCm','gUmmauHTmTwIB9ZkwvsrNHrQEaBYS3d2T3E0INIJZlj63wQpE7eJNz7Zh7re','2026-08-14 09:25:28','2026-08-14 09:25:28'),
(4,'Test Vitesse','0195444444',NULL,'vendeur',NULL,'$2y$12$3tAozIopg0joW5wL8QE0D.sAdwYD.QDGBTgdHnBtcELvHAdpU5JOG',NULL,'2026-08-20 16:10:35','2026-08-20 16:10:35');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-26  0:29:35
