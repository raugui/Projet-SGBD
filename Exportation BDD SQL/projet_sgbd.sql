-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  lun. 04 mars 2019 à 16:32
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
  `poids_unitaire` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id`, `designation`, `quantite_stock`, `format`, `type`, `prix_unitaire`, `poids_unitaire`) VALUES
(1, 'COCA', 2250, '24x25cl', 'CAISSE', 11, 8),
(2, 'FANTA', 664, '24x25cl', 'CAISSE', 11, 8),
(3, 'JUPILER', 588, '50L', 'FUT', 100, 65),
(4, 'MAES', 664, '24x25cl', 'CAISSE', 7, 8),
(5, 'QUEUE DE CHARRUE ROUGE', 1413, '24x33cl', 'CAISSE', 35, 12),
(6, 'QUEUE DE CHARRUE BLONDE', 822, '24x33cl', 'CAISSE', 32, 12),
(7, 'SPA REINE', 1105, '28x25cl', 'CAISSE', 9, 10),
(8, 'SPA INTENSE', 1101, '28x25cl', 'CAISSE', 9, 10),
(9, 'STELLA', 2425, '50L', 'FUT', 100, 65),
(17, 'WILLIAM LAWSON', 1062, '1L', 'BOUTEILLE', 15, 1),
(18, 'ERISTOFF WHITE', 1141, '1L', 'BOUTEILLE', 16, 1),
(19, 'JUPILER', 1362, '24X25CL', 'CAISSE', 8, 8),
(20, 'JUPILER', 496, '24X33CL', 'CAISSE', 11, 10),
(21, 'JUPILER', 795, '20L', 'FUT', 70, 26),
(24, 'ESSAI', 98, '0.70L', 'BOUTEILLE', 500, 0.65);

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

DROP TABLE IF EXISTS `commandes`;
CREATE TABLE IF NOT EXISTS `commandes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quantiteTotale` bigint(11) NOT NULL,
  `statut` varchar(10) NOT NULL,
  `idClient` int(11) DEFAULT NULL,
  `prix` bigint(20) NOT NULL,
  `poidsTotal` float NOT NULL,
  `Preparateur` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_USER_COMMANDE_idx` (`idClient`)
) ENGINE=InnoDB AUTO_INCREMENT=184 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id`, `quantiteTotale`, `statut`, `idClient`, `prix`, `poidsTotal`, `Preparateur`) VALUES
(178, 4, 'terminee', 14, 307, 203, 'WABKAR ALA'),
(179, 4, 'terminee', 14, 128, 48, 'WABKAR ALA'),
(180, 65, 'En cours', 1, 3426, 1376.3, NULL),
(181, 5, 'terminee', 1, 55, 40, 'WABKAR ALA'),
(182, 25, 'terminee', 14, 200, 200, 'WABKAR ALA'),
(183, 8, 'En cours', 14, 800, 520, NULL);

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
  `poids` float NOT NULL,
  `prix` bigint(20) NOT NULL,
  KEY `idCom` (`idCom`),
  KEY `idArt` (`idArt`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `detail_commande`
--

INSERT INTO `detail_commande` (`idCom`, `idArt`, `quantite`, `format`, `designation`, `type`, `poids`, `prix`) VALUES
(178, 3, 3, '50L', 'JUPILER', 'FUT', 195, 300),
(178, 4, 1, '24x25cl', 'MAES', 'CAISSE', 32, 328),
(179, 6, 4, '24x33cl', 'QUEUE DE CHARRUE BLONDE', 'CAISSE', 144, 384),
(180, 1, 2, '24x25cl', 'COCA', 'CAISSE', 16, 22),
(180, 2, 3, '24x25cl', 'FANTA', 'CAISSE', 24, 55),
(180, 3, 1, '50L', 'JUPILER', 'FUT', 65, 155),
(180, 4, 3, '24x25cl', 'MAES', 'CAISSE', 24, 176),
(180, 5, 8, '24x33cl', 'QUEUE DE CHARRUE ROUGE', 'CAISSE', 96, 456),
(180, 6, 5, '24x33cl', 'QUEUE DE CHARRUE BLONDE', 'CAISSE', 60, 616),
(180, 7, 3, '28x25cl', 'SPA REINE', 'CAISSE', 30, 643),
(180, 8, 8, '28x25cl', 'SPA INTENSE', 'CAISSE', 80, 715),
(180, 9, 12, '50L', 'STELLA', 'FUT', 780, 1915),
(180, 17, 3, '1L', 'WILLIAM LAWSON', 'BOUTEILLES', 3, 1960),
(180, 18, 3, '1L', 'ERISTOFF WHITE', 'BOUTEILLES', 3, 2008),
(180, 19, 3, '24X25CL', 'JUPILER', 'CAISSE', 24, 2032),
(180, 20, 4, '24X33CL', 'JUPILER', 'CAISSE', 40, 2076),
(180, 21, 5, '20L', 'JUPILER', 'FUT', 130, 2426),
(180, 24, 2, '0.70L', 'ESSAI', 'BOUTEILLES', 1.3, 3426),
(181, 1, 5, '24x25cl', 'COCA', 'CAISSE', 40, 55),
(182, 19, 25, '24X25CL', 'JUPILER', 'CAISSE', 160, 160),
(183, 3, 8, '50L', 'JUPILER', 'FUT', 520, 800);

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
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `prenom`, `dateNaiss`, `adresse`, `codePostal`, `ville`, `pays`, `mail`, `telephone`, `role`, `societe`, `type`, `login`, `psw`) VALUES
(1, 'DURIBREUX', 'GUILLAUME', '1990-12-31', '10 QUAI VERBOECKHOVEN', 7784, 'warneton', 'belgique', 'GUILLAUME.DURIBREUX@GMAIL.COM', 476380763, NULL, 'DUREX', 'Client', 'raugui', 'ab4f63f9ac65152575886860dde480a1'),
(6, 'ADMINISTRAT', 'EUR', '1111-11-11', 'AAAAAA', 45678, 'aaaa', 'aaaa', 'GUI@GMAIL.COM', 36645, 'Administrateur', '', 'Admin', 'admin', '21232f297a57a5a743894a0e4a801fc3'),
(11, 'WABKAR', 'ALA', '1111-11-11', 'AAAAAA', 45678, 'aaaa', 'aaaa', 'GUI@GMAIL.COM', 36645, 'Preparateur', '', 'Employes', 'employes', 'ab4f63f9ac65152575886860dde480a1'),
(14, 'DEBACKER', 'JEREMY', '1890-01-01', 'RUE DE LA VICTOIRE', 7700, 'mouscron', 'belgique', 'CHERCHEETTROUVE@GMAIL.COM', 5647893145, NULL, 'BARBEROUSSE', 'Client', 'monsieurjeremy', 'ab4f63f9ac65152575886860dde480a1');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `FK_USER_COMMANDE` FOREIGN KEY (`idClient`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

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
