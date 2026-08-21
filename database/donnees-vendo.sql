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

-- Dump completed on 2026-08-21 14:59:15
