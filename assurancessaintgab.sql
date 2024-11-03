-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 03, 2024 at 08:54 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `assurancessaintgab`
--

-- --------------------------------------------------------

--
-- Table structure for table `contrats`
--

DROP TABLE IF EXISTS `contrats`;
CREATE TABLE IF NOT EXISTS `contrats` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `simulation_id` int NOT NULL,
  `types_assurance` text NOT NULL,
  `informations` text NOT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `simulation_id` (`simulation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `contrats`
--

INSERT INTO `contrats` (`id`, `user_id`, `simulation_id`, `types_assurance`, `informations`, `date_creation`) VALUES
(14, 3, 19, '[\"Auto\"]', '{\"Auto\":{\"marque\":\"Audi\",\"modele\":\"RS6\",\"annee\":\"2021\",\"immatriculation\":\"AA-0000-AA\"}}', '2024-11-03 20:43:51');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE IF NOT EXISTS `logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `action` varchar(255) NOT NULL,
  `timestamp` datetime DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `action`, `timestamp`, `ip_address`) VALUES
(1, 4, 'Téléchargé une image : 6727d6249ef03.jpg.', '2024-11-03 20:59:32', '::1'),
(2, 4, 'Créé une nouvelle actualité ID 12.', '2024-11-03 20:59:32', '::1'),
(3, 4, 'Déconnexion réussie.', '2024-11-03 21:07:41', '::1'),
(4, 3, 'Connexion réussie.', '2024-11-03 21:07:45', '::1'),
(5, 3, 'Déconnexion réussie.', '2024-11-03 21:07:49', '::1'),
(6, 4, 'Connexion réussie.', '2024-11-03 21:07:53', '::1'),
(7, 4, 'Déconnexion réussie.', '2024-11-03 21:15:22', '::1'),
(8, 3, 'Connexion réussie.', '2024-11-03 21:15:31', '::1'),
(9, 3, 'Déconnexion réussie.', '2024-11-03 21:16:10', '::1'),
(10, 4, 'Connexion réussie.', '2024-11-03 21:16:14', '::1'),
(11, 4, 'Mis à jour les informations de l\'utilisateur ID 3.', '2024-11-03 21:38:13', '::1'),
(12, 4, 'Déconnexion réussie.', '2024-11-03 21:47:49', '::1'),
(13, 3, 'Connexion réussie.', '2024-11-03 21:50:38', '::1'),
(14, 3, 'Déconnexion réussie.', '2024-11-03 21:51:32', '::1'),
(15, 4, 'Connexion réussie.', '2024-11-03 21:51:36', '::1');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `title` varchar(80) NOT NULL,
  `caption` text NOT NULL,
  `keywords` text NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `date`, `title`, `caption`, `keywords`, `image`) VALUES
(11, '2024-11-03', 'incroyable', 'annonce', 'test', '6727d2c94545e.jpg'),
(12, '2222-01-02', 'weq', 'ewq', 'ewq', '6727d6249ef03.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `simulations`
--

DROP TABLE IF EXISTS `simulations`;
CREATE TABLE IF NOT EXISTS `simulations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `types_assurance` text NOT NULL,
  `informations` text NOT NULL,
  `statut` varchar(20) NOT NULL DEFAULT 'En attente',
  `reponse` text,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_reponse` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `simulations`
--

INSERT INTO `simulations` (`id`, `user_id`, `types_assurance`, `informations`, `statut`, `reponse`, `date_creation`, `date_reponse`) VALUES
(19, 3, '[\"Auto\"]', '{\"Auto\":{\"marque\":\"Audi\",\"modele\":\"RS6\",\"annee\":\"2021\",\"immatriculation\":\"AA-0000-AA\"}}', 'Acceptée', 'Je vous propose 3euros', '2024-11-03 20:43:06', '2024-11-03 20:43:18');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(80) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` tinyint NOT NULL DEFAULT '0',
  `cp` varchar(5) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `cp`, `first_name`, `last_name`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(3, 'marc@client.fr', '$2y$10$UK5qb4X0/5F2C/N9awOBluSSBzIU.iQWpo5Xk8xKad8vHUg2Hcoq2', 2, '', 'Marc1', 'Smith', '337123456789', '1 rue de l\'eau', '2024-11-03 19:53:21', '2024-11-03 21:38:13'),
(4, 'David@asg.fr', '$2y$10$PtIxTKTyL6tW6qZ14QhBr.ODmY73jBNTHRzbeKPclksRmKtTgSZFO', 4, '', 'David', 'Zimmer', '337123456789', '1 rue des cocotiers', '2024-11-03 19:55:24', '2024-11-03 19:55:47');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contrats`
--
ALTER TABLE `contrats`
  ADD CONSTRAINT `contrats_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contrats_ibfk_2` FOREIGN KEY (`simulation_id`) REFERENCES `simulations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `simulations`
--
ALTER TABLE `simulations`
  ADD CONSTRAINT `simulations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
