-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 13 fév. 2019 à 13:47
-- Version du serveur :  5.7.19
-- Version de PHP :  7.0.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `projet_sgbd`
--

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(500) NOT NULL,
  `quantite_stock` int(10) NOT NULL,
  `format` varchar(10) DEFAULT NULL,
  `type` varchar(10) NOT NULL,
  `prix_unitaire` int(100) NOT NULL,
  `poids_unitaire` int(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id`, `designation`, `quantite_stock`, `format`, `type`, `prix_unitaire`, `poids_unitaire`) VALUES
(1, 'coca', 163, '24x25cl', 'caisse', 11, 8),
(2, 'fanta', 61, '24x25cl', 'caisse', 11, 8),
(3, 'Jupiler', 89, '50L', 'FUT', 100, 65),
(4, 'Maes', 18, '24x25cl', 'caisse', 7, 8),
(5, 'Queue de charrue rouge', -4, '24x33cl', 'caisse', 35, 12),
(6, 'Queue de charrue blonde', 35, '24x33cl', 'caisse', 32, 12),
(7, 'Spa reine', 110, '28x25cl', 'caisse', 9, 10),
(8, 'Spa Intense', 118, '28x25cl', 'caisse', 9, 10),
(9, 'Stella', 80, '50L', 'FUT', 100, 65),
(17, 'William Lawson', 21, '1L', 'bouteilles', 15, 1);

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

DROP TABLE IF EXISTS `commandes`;
CREATE TABLE IF NOT EXISTS `commandes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quantiteTotale` bigint(11) NOT NULL,
  `statut` varchar(10) NOT NULL,
  `idClient` int(11) NOT NULL,
  `prix` bigint(20) NOT NULL,
  `poidsTotal` int(11) NOT NULL,
  `Preparateur` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_USER_COMMANDE_idx` (`idClient`)
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id`, `quantiteTotale`, `statut`, `idClient`, `prix`, `poidsTotal`, `Preparateur`) VALUES
(102, 87, 'terminee', 1, 2121, 940, NULL),
(103, 22, 'terminee', 3, 254, 155, NULL),
(104, 6, 'terminee', 3, 66, 48, 'Dudu gui'),
(105, 10, 'terminee', 3, 335, 120, 'Dudu gui'),
(106, 5, 'terminee', 3, 500, 325, NULL),
(107, 5, 'En cours', 3, 500, 325, NULL),
(108, 5, 'terminee', 3, 500, 325, 'Dudu gui'),
(109, 20, 'En cours', 1, 214, 166, NULL),
(110, 100, 'En cours', 1, 3290, 1990, NULL),
(111, 6, 'En cours', 1, 66, 48, NULL),
(112, 5, 'En cours', 1, 75, 5, NULL),
(115, 7, 'terminee', 1, 245, 84, 'Dudu gui'),
(117, 14, 'En cours', 1, 154, 112, NULL),
(118, 14, 'En cours', 1, 154, 112, NULL),
(119, 14, 'En cours', 1, 154, 112, NULL),
(120, 40, 'En cours', 1, 1627, 1032, NULL),
(121, 40, 'En cours', 1, 1627, 1032, NULL),
(122, 21, 'En cours', 1, 690, 371, NULL),
(123, 21, 'En cours', 1, 690, 371, NULL),
(124, 21, 'En cours', 1, 690, 371, NULL),
(125, 21, 'En cours', 1, 690, 371, NULL),
(126, 21, 'En cours', 1, 690, 371, NULL),
(127, 21, 'En cours', 1, 690, 371, NULL),
(128, 21, 'En cours', 1, 690, 371, NULL),
(129, 21, 'En cours', 1, 690, 371, NULL),
(130, 21, 'En cours', 1, 690, 371, NULL),
(131, 30, 'En cours', 1, 508, 354, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `detail_commande`
--

DROP TABLE IF EXISTS `detail_commande`;
CREATE TABLE IF NOT EXISTS `detail_commande` (
  `idCom` int(11) NOT NULL,
  `idArt` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `format` varchar(50) NOT NULL,
  `designation` varchar(500) NOT NULL,
  `type` varchar(50) NOT NULL,
  `poids` bigint(20) NOT NULL,
  `prix` bigint(20) NOT NULL,
  KEY `idCom` (`idCom`),
  KEY `idArt` (`idArt`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `detail_commande`
--

INSERT INTO `detail_commande` (`idCom`, `idArt`, `quantite`, `format`, `designation`, `type`, `poids`, `prix`) VALUES
(102, 1, 11, '24x25cl', 'coca', 'caisse', 88, 121),
(102, 2, 6, '24x25cl', 'fanta', 'caisse', 48, 66),
(102, 3, 8, '50L', 'Jupiler', 'FUT', 520, 800),
(102, 4, 2, '24x25cl', 'Maes', 'caisse', 16, 14),
(102, 5, 2, '24x33cl', 'Queue de charrue rouge', 'caisse', 24, 70),
(102, 6, 2, '24x33cl', 'Queue de charrue blonde', 'caisse', 24, 64),
(102, 7, 2, '28x25cl', 'Spa reine', 'caisse', 20, 18),
(102, 8, 2, '28x25cl', 'Spa Intense', 'caisse', 20, 18),
(102, 9, 2, '50L', 'Stella', 'FUT', 130, 200),
(102, 17, 50, '1L', 'William Lawson', 'bouteilles', 50, 750),
(103, 1, 7, '24x25cl', 'coca', 'caisse', 56, 77),
(103, 2, 12, '24x25cl', 'fanta', 'caisse', 96, 132),
(103, 17, 3, '1L', 'William Lawson', 'bouteilles', 3, 45),
(104, 1, 3, '24x25cl', 'coca', 'caisse', 24, 33),
(104, 2, 3, '24x25cl', 'fanta', 'caisse', 24, 33),
(105, 5, 5, '24x33cl', 'Queue de charrue rouge', 'caisse', 60, 175),
(105, 6, 5, '24x33cl', 'Queue de charrue blonde', 'caisse', 60, 160),
(106, 3, 5, '50L', 'Jupiler', 'FUT', 325, 500),
(107, 3, 5, '50L', 'Jupiler', 'FUT', 325, 500),
(108, 3, 5, '50L', 'Jupiler', 'FUT', 325, 500),
(109, 1, 17, '24x25cl', 'coca', 'caisse', 136, 187),
(109, 7, 3, '28x25cl', 'Spa reine', 'caisse', 30, 27),
(110, 1, 10, '24x25cl', 'coca', 'caisse', 80, 110),
(110, 2, 10, '24x25cl', 'fanta', 'caisse', 80, 110),
(110, 3, 5, '50L', 'Jupiler', 'FUT', 650, 1000),
(110, 4, 10, '24x25cl', 'Maes', 'caisse', 80, 70),
(110, 5, 10, '24x33cl', 'Queue de charrue rouge', 'caisse', 120, 350),
(110, 6, 10, '24x33cl', 'Queue de charrue blonde', 'caisse', 120, 320),
(110, 7, 10, '28x25cl', 'Spa reine', 'caisse', 100, 90),
(110, 8, 10, '28x25cl', 'Spa Intense', 'caisse', 100, 90),
(110, 9, 10, '50L', 'Stella', 'FUT', 650, 1000),
(110, 17, 10, '1L', 'William Lawson', 'bouteilles', 10, 150),
(111, 1, 3, '24x25cl', 'coca', 'caisse', 24, 33),
(111, 2, 3, '24x25cl', 'fanta', 'caisse', 24, 33),
(112, 17, 5, '1L', 'William Lawson', 'bouteilles', 5, 75),
(115, 5, 7, '24x33cl', 'Queue de charrue rouge', 'caisse', 84, 245),
(128, 1, 10, '24x25cl', 'coca', 'caisse', 80, 110),
(128, 3, 3, '50L', 'Jupiler', 'FUT', 195, 410),
(128, 5, 8, '24x33cl', 'Queue de charrue rouge', 'caisse', 96, 690),
(129, 1, 10, '24x25cl', 'coca', 'caisse', 80, 110),
(129, 3, 3, '50L', 'Jupiler', 'FUT', 195, 410),
(129, 5, 8, '24x33cl', 'Queue de charrue rouge', 'caisse', 96, 690),
(130, 1, 10, '24x25cl', 'coca', 'caisse', 80, 110),
(130, 3, 3, '50L', 'Jupiler', 'FUT', 195, 410),
(130, 5, 8, '24x33cl', 'Queue de charrue rouge', 'caisse', 96, 690),
(131, 1, 17, '24x25cl', 'coca', 'caisse', 136, 187),
(131, 2, 11, '24x25cl', 'fanta', 'caisse', 88, 308),
(131, 3, 2, '50L', 'Jupiler', 'FUT', 130, 508);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `dateNaiss` date NOT NULL,
  `adresse` text NOT NULL,
  `codePostal` int(5) NOT NULL,
  `ville` varchar(50) NOT NULL,
  `pays` varchar(50) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `telephone` bigint(10) NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `societe` varchar(150) DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `login` varchar(50) NOT NULL,
  `psw` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `prenom`, `dateNaiss`, `adresse`, `codePostal`, `ville`, `pays`, `mail`, `telephone`, `role`, `societe`, `type`, `login`, `psw`) VALUES
(1, 'DURIBREUX', 'GUILLAUME', '1990-12-31', '10 QUAI VERBOECKHOVEN', 7784, 'warneton', 'belgique', 'GUILLAUME.DURIBREUX@GMAIL.COM', 476380763, NULL, 'DUREX', 'Client', 'raugui', 'c18fda9ef740fa1cfd164544111b6d33'),
(3, 'ALA', 'WAKB', '2001-09-11', 'AAAAAAA', 33333, 'aaaa', 'aaaaaa', 'AA@GMAIL.COM', 333333333, NULL, 'AAAAA', 'Client', 'aza', 'ab4f63f9ac65152575886860dde480a1'),
(4, 'Dudu', 'gui', '1990-02-13', 'azla', 11111, 'aaa', 'aa', 'aaa@gmail.com', 123, 'Preparateur', NULL, 'Employes', 'employes', 'ab4f63f9ac65152575886860dde480a1'),
(5, 'DUDU', 'GUIGUI', '1987-11-11', 'PRES DE LA POSTE', 7700, 'mouscron', 'belgique', 'AAAAAAAA@GMAIL.COM', 4777777777, NULL, NULL, 'Employes', 'ben', '202cb962ac59075b964b07152d234b70');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `FK_USER_COMMANDE` FOREIGN KEY (`idClient`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `detail_commande`
--
ALTER TABLE `detail_commande`
  ADD CONSTRAINT `detail_commande_ibfk_1` FOREIGN KEY (`idArt`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_commande_ibfk_2` FOREIGN KEY (`idCom`) REFERENCES `commandes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
