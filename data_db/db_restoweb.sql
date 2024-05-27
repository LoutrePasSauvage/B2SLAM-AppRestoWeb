-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 27 mai 2024 à 18:47
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `db_restoweb`
--

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_commande` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_etat` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp(),
  `total_commande` decimal(10,2) DEFAULT 0.00,
  `type_conso` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id_commande`, `id_user`, `id_etat`, `date`, `total_commande`, `type_conso`) VALUES
(198, 5, 1, '2024-05-27 13:10:22', 38.00, 0);

-- --------------------------------------------------------

--
-- Structure de la table `ligne`
--

CREATE TABLE `ligne` (
  `id_ligne` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `qte` int(11) NOT NULL DEFAULT 0,
  `total_ligne_ht` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déclencheurs `ligne`
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
IF f_type_conso = 0
THEN
	SET TVA = 1.055;
END IF;

IF f_type_conso = 1
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
IF f_type_conso = 0
THEN
	SET TVA = 1.055;
END IF;

IF f_type_conso = 1
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
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id_produit` int(11) NOT NULL,
  `libelle` varchar(255) NOT NULL,
  `prix_ht` decimal(10,2) NOT NULL,
  `descProduit` varchar(250) DEFAULT NULL,
  `Rupture` int(11) NOT NULL DEFAULT 0,
  `MotifRupture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id_produit`, `libelle`, `prix_ht`, `descProduit`, `Rupture`, `MotifRupture`) VALUES
(1, 'pizza Margherita', 12.50, NULL, 0, ''),
(2, 'pizza Chorizo', 14.00, NULL, 0, ''),
(3, 'assiette de charcuterie', 12.50, NULL, 0, ''),
(4, 'assiette de fromages', 10.50, NULL, 0, ''),
(5, 'hamburger viande', 7.50, NULL, 0, ''),
(6, 'hamburger vegan', 9.00, NULL, 0, ''),
(7, 'hot dog', 5.00, NULL, 0, ''),
(8, 'empanadas poulet', 11.00, NULL, 0, ''),
(9, 'empanadas thon', 9.00, NULL, 0, ''),
(10, 'portion de frites', 5.00, NULL, 0, '');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `admin` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id_user`, `login`, `password`, `email`, `admin`) VALUES
(5, 'a', '$2y$10$g1/XXfIAwn7noqJljw6ehum8q5Ujj92rly7FBHNHGbqgoKrAoWwN.', 'a@restoweb.fr', 1),
(6, 'b', '$2y$10$wOtRjRLbxXhUaMYbv/dw/eiKlU.NaLxFLJXZCoDi0UM8/jho9wC6i', 'b@restoweb.fr', 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_etat` (`id_etat`);

--
-- Index pour la table `ligne`
--
ALTER TABLE `ligne`
  ADD PRIMARY KEY (`id_ligne`),
  ADD KEY `id_commande` (`id_commande`),
  ADD KEY `id_produit` (`id_produit`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id_produit`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=199;

--
-- AUTO_INCREMENT pour la table `ligne`
--
ALTER TABLE `ligne`
  MODIFY `id_ligne` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=334;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id_produit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Contraintes pour la table `ligne`
--
ALTER TABLE `ligne`
  ADD CONSTRAINT `ligne_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`),
  ADD CONSTRAINT `ligne_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
