-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 21, 2023 at 02:21 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_restoweb`
--
CREATE DATABASE IF NOT EXISTS `db_restoweb` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_restoweb`;

-- --------------------------------------------------------

--
-- Table structure for table `commande`
--

CREATE TABLE `commande` (
  `id_commande` int NOT NULL,
  `id_user` int NOT NULL,
  `id_etat` int NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `total_commande` decimal(10,2) DEFAULT '0.00',
  `type_conso` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `commande`
--

INSERT INTO `commande` (`id_commande`, `id_user`, `id_etat`, `date`, `total_commande`, `type_conso`) VALUES
(192, 4, 3, '2023-11-30 00:00:00', NULL, 0),
(193, 4, 1, '2023-11-30 00:00:00', '26.00', 0),
(194, 4, 1, '2023-11-30 00:00:00', '26.00', 0),
(195, 4, 1, '2023-11-30 00:00:00', '27.00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ligne`
--

CREATE TABLE `ligne` (
  `id_ligne` int NOT NULL,
  `id_commande` int NOT NULL,
  `id_produit` int NOT NULL,
  `qte` int NOT NULL DEFAULT '0',
  `total_ligne_ht` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `ligne`
--

INSERT INTO `ligne` (`id_ligne`, `id_commande`, `id_produit`, `qte`, `total_ligne_ht`) VALUES
(305, 192, 3, 1, '13.00'),
(306, 192, 2, 1, '14.00');

--
-- Triggers `ligne`
--
DELIMITER $$
CREATE TRIGGER `after_ligne_insert` AFTER INSERT ON `ligne` FOR EACH ROW BEGIN
DECLARE f_total_commande INT;
DECLARE f_type_conso INT;
DECLARE TVA INT;

-- Affectation type consommation
SELECT type_conso INTO f_type_conso
FROM commande
WHERE commande.id_commande = NEW.id_commande;

-- Calcul taux TVA
IF f_type_conso = 1
THEN
	SET TVA = 1.055;
END IF;

IF f_type_conso = 2
THEN
	SET TVA = 1.1;
END IF;

-- Calcule total HT des lignes de la commande
SELECT SUM(total_ligne_ht) INTO f_total_commande
FROM ligne
WHERE ligne.id_commande = NEW.id_commande;

 	-- Calcul total TTC
SET f_total_commande = f_total_commande * TVA;

-- Mise à jour total commande
UPDATE commande SET total_commande=f_total_commande
WHERE commande.id_commande = NEW.id_commande;
 
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_ligne_update` AFTER UPDATE ON `ligne` FOR EACH ROW BEGIN
DECLARE f_total_commande INT;
DECLARE f_type_conso INT;
DECLARE TVA INT;

-- Affectation type consommation
SELECT type_conso INTO f_type_conso
FROM commande
WHERE commande.id_commande = NEW.id_commande;

-- Calcul taux TVA
IF f_type_conso = 1
THEN
	SET TVA = 1.055;
END IF;

IF f_type_conso = 2
THEN
	SET TVA = 1.1;
END IF;

-- Calcule total HT des lignes de la commande
SELECT SUM(total_ligne_ht) INTO f_total_commande
FROM ligne
WHERE ligne.id_commande = NEW.id_commande;

 	-- Calcul total TTC
SET f_total_commande = f_total_commande * TVA;

-- Mise à jour total commande
UPDATE commande SET total_commande=f_total_commande
WHERE commande.id_commande = NEW.id_commande;
 
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_ligne_insert` BEFORE INSERT ON `ligne` FOR EACH ROW BEGIN
DECLARE f_prixht FLOAT;

	-- Affectation du prix hors taxe
SELECT prix_ht INTO f_prixht 
FROM produit 
WHERE produit.id_produit = NEW.id_produit; 
	
	-- Calcul total ligne
SET NEW.total_ligne_ht = f_prixht * NEW.qte;
 
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_ligne_update` BEFORE UPDATE ON `ligne` FOR EACH ROW BEGIN
DECLARE f_prixht FLOAT;

	-- Affectation du prix hors taxe
SELECT prix_ht INTO f_prixht 
FROM produit 
WHERE produit.id_produit = NEW.id_produit; 
	
	-- Calcul total ligne
SET NEW.total_ligne_ht = f_prixht * NEW.qte;
 
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `produit`
--

CREATE TABLE `produit` (
  `id_produit` int NOT NULL,
  `libelle` varchar(255) NOT NULL,
  `prix_ht` decimal(10,2) NOT NULL,
  `descProduit` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `produit`
--

INSERT INTO `produit` (`id_produit`, `libelle`, `prix_ht`, `descProduit`) VALUES
(1, 'pizza Margherita', '12.50', NULL),
(2, 'pizza Chorizo', '14.00', NULL),
(3, 'assiette de charcuterie', '12.50', NULL),
(4, 'assiette de fromages', '10.50', NULL),
(5, 'hamburger viande', '7.50', NULL),
(6, 'hamburger vegan', '9.00', NULL),
(7, 'hot dog', '5.00', NULL),
(8, 'empanadas poulet', '11.00', NULL),
(9, 'empanadas thon', '9.00', NULL),
(10, 'portion de frites', '5.00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `login`, `password`, `email`) VALUES
(4, 'a', '$2y$10$PzJDRtXjIeDHwSuoN24gAO8mA8xO5ENC5ci352fzWvpl4Y7RGQC4q', 'a@a.fr');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_etat` (`id_etat`);

--
-- Indexes for table `ligne`
--
ALTER TABLE `ligne`
  ADD PRIMARY KEY (`id_ligne`),
  ADD KEY `id_commande` (`id_commande`),
  ADD KEY `id_produit` (`id_produit`);

--
-- Indexes for table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id_produit`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=196;

--
-- AUTO_INCREMENT for table `ligne`
--
ALTER TABLE `ligne`
  MODIFY `id_ligne` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=307;

--
-- AUTO_INCREMENT for table `produit`
--
ALTER TABLE `produit`
  MODIFY `id_produit` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Constraints for table `ligne`
--
ALTER TABLE `ligne`
  ADD CONSTRAINT `ligne_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`),
  ADD CONSTRAINT `ligne_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
