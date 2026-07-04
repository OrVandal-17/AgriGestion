-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 04 juil. 2026 à 13:14
-- Version du serveur : 8.4.7
-- Version de PHP : 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_agricole_togo`
--

-- --------------------------------------------------------

--
-- Structure de la table `agriculteur`
--

DROP TABLE IF EXISTS `agriculteur`;
CREATE TABLE IF NOT EXISTS `agriculteur` (
  `Id_agri` int NOT NULL AUTO_INCREMENT,
  `Nom_agri` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Prenom_agri` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Sexe` char(1) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `DateNaissance` date DEFAULT NULL,
  `Telephone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Adresse` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Id_coop` int NOT NULL,
  `Id_util` int DEFAULT NULL,
  PRIMARY KEY (`Id_agri`),
  UNIQUE KEY `uniq_agriculteur_util` (`Id_util`),
  KEY `Id_coop` (`Id_coop`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `agriculteur`
--

INSERT INTO `agriculteur` (`Id_agri`, `Nom_agri`, `Prenom_agri`, `Sexe`, `DateNaissance`, `Telephone`, `Adresse`, `Id_coop`, `Id_util`) VALUES
(1, 'Amegan', 'Koffi', 'M', NULL, '91112233', NULL, 1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `cooperative`
--

DROP TABLE IF EXISTS `cooperative`;
CREATE TABLE IF NOT EXISTS `cooperative` (
  `Id_coop` int NOT NULL AUTO_INCREMENT,
  `Nom_coop` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `Adresse_coop` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Telephone_coop` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Email_coop` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`Id_coop`),
  UNIQUE KEY `Email_coop` (`Email_coop`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cooperative`
--

INSERT INTO `cooperative` (`Id_coop`, `Nom_coop`, `Adresse_coop`, `Telephone_coop`, `Email_coop`) VALUES
(1, 'Coop du Plateau', 'Kpalime', '90000000', 'coop@ex.tg');

-- --------------------------------------------------------

--
-- Structure de la table `culture`
--

DROP TABLE IF EXISTS `culture`;
CREATE TABLE IF NOT EXISTS `culture` (
  `Id_culture` int NOT NULL AUTO_INCREMENT,
  `Nom_culture` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Type_culture` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `DatePlantation` date DEFAULT NULL,
  `DureeCycle` int DEFAULT NULL,
  PRIMARY KEY (`Id_culture`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `culture`
--

INSERT INTO `culture` (`Id_culture`, `Nom_culture`, `Type_culture`, `DatePlantation`, `DureeCycle`) VALUES
(1, 'Mais', 'Cereale', NULL, 90);

-- --------------------------------------------------------

--
-- Structure de la table `intrant`
--

DROP TABLE IF EXISTS `intrant`;
CREATE TABLE IF NOT EXISTS `intrant` (
  `Id_intrant` int NOT NULL AUTO_INCREMENT,
  `Nom_intrant` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Type_intrant` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Unite` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `PrixUnitaire` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`Id_intrant`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `intrant`
--

INSERT INTO `intrant` (`Id_intrant`, `Nom_intrant`, `Type_intrant`, `Unite`, `PrixUnitaire`) VALUES
(1, 'Uree', 'Engrais', 'kg', 350.00);

-- --------------------------------------------------------

--
-- Structure de la table `mise_en_culture`
--

DROP TABLE IF EXISTS `mise_en_culture`;
CREATE TABLE IF NOT EXISTS `mise_en_culture` (
  `Id_mise_en_culture` int NOT NULL AUTO_INCREMENT,
  `Id_parcelle` int NOT NULL,
  `Id_culture` int NOT NULL,
  `Id_saison` int DEFAULT NULL,
  `DateMiseEnCulture` date NOT NULL DEFAULT (curdate()),
  PRIMARY KEY (`Id_mise_en_culture`),
  KEY `Id_culture` (`Id_culture`),
  KEY `idx_cultiver_parcelle` (`Id_parcelle`),
  KEY `cultiver_ibfk_3` (`Id_saison`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `parcelle`
--

DROP TABLE IF EXISTS `parcelle`;
CREATE TABLE IF NOT EXISTS `parcelle` (
  `Id_parcelle` int NOT NULL AUTO_INCREMENT,
  `Superficie` decimal(10,2) DEFAULT NULL,
  `Localisation` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `EtatParcelle` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Id_agri` int NOT NULL,
  `Id_zone` int NOT NULL,
  PRIMARY KEY (`Id_parcelle`),
  KEY `Id_agri` (`Id_agri`),
  KEY `Id_zone` (`Id_zone`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `parcelle`
--

INSERT INTO `parcelle` (`Id_parcelle`, `Superficie`, `Localisation`, `EtatParcelle`, `Id_agri`, `Id_zone`) VALUES
(1, 1.50, 'Zone Nord', 'En culture', 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `recolte`
--

DROP TABLE IF EXISTS `recolte`;
CREATE TABLE IF NOT EXISTS `recolte` (
  `Id_recolte` int NOT NULL AUTO_INCREMENT,
  `DateRecolte` date NOT NULL,
  `Rendement` decimal(10,2) DEFAULT NULL,
  `Cout` decimal(10,2) DEFAULT NULL,
  `Observation` text COLLATE utf8mb4_general_ci,
  `Id_parcelle` int NOT NULL,
  `Id_culture` int NOT NULL,
  PRIMARY KEY (`Id_recolte`),
  KEY `Id_parcelle` (`Id_parcelle`),
  KEY `Id_culture` (`Id_culture`),
  KEY `idx_recolte_date` (`DateRecolte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `saison`
--

DROP TABLE IF EXISTS `saison`;
CREATE TABLE IF NOT EXISTS `saison` (
  `Id_saison` int NOT NULL AUTO_INCREMENT,
  `Libelle` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `DateDebut` date DEFAULT NULL,
  `DateFin` date DEFAULT NULL,
  PRIMARY KEY (`Id_saison`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `saison`
--

INSERT INTO `saison` (`Id_saison`, `Libelle`, `DateDebut`, `DateFin`) VALUES
(1, 'Saison des pluies 2026', '2026-04-01', '2026-09-30');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `Id_util` int NOT NULL AUTO_INCREMENT,
  `Nom_util` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Prenom_util` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Tel_util` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Email_util` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `MotPasse_util` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Role_util` enum('Administrateur','Agriculteur','Responsable') COLLATE utf8mb4_general_ci NOT NULL,
  `Id_coop` int DEFAULT NULL,
  PRIMARY KEY (`Id_util`),
  UNIQUE KEY `Email_util` (`Email_util`),
  KEY `Id_coop` (`Id_coop`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`Id_util`, `Nom_util`, `Prenom_util`, `Tel_util`, `Email_util`, `MotPasse_util`, `Role_util`, `Id_coop`) VALUES
(1, 'Koffi', 'Systeme', NULL, 'admin@agrigestion.tg', '$2b$10$PL0BRX1ENbQNMyKX.mRUROZt1eEIoW9WEP/JPJnoOU6YQROYiCiOK', 'Administrateur', NULL),
(2, 'Kodjo', 'Ama', NULL, 'ama@ex.tg', '$2y$10$6zKkXabOJ5t2qONhqLK/bupPeMGKXsOo56blEDzcK19o49EJTQz..', 'Agriculteur', 1);

-- --------------------------------------------------------

--
-- Structure de la table `utiliser`
--

DROP TABLE IF EXISTS `utiliser`;
CREATE TABLE IF NOT EXISTS `utiliser` (
  `Id_utilisation` int NOT NULL AUTO_INCREMENT,
  `Id_parcelle` int NOT NULL,
  `Id_intrant` int NOT NULL,
  `DateUtilisation` date NOT NULL,
  `Quantite` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`Id_utilisation`),
  KEY `Id_intrant` (`Id_intrant`),
  KEY `idx_utiliser_parcelle` (`Id_parcelle`),
  KEY `idx_utiliser_intrant` (`Id_intrant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `zone_agroecologique`
--

DROP TABLE IF EXISTS `zone_agroecologique`;
CREATE TABLE IF NOT EXISTS `zone_agroecologique` (
  `Id_zone` int NOT NULL AUTO_INCREMENT,
  `Nom_zone` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Region` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Description` text COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`Id_zone`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `zone_agroecologique`
--

INSERT INTO `zone_agroecologique` (`Id_zone`, `Nom_zone`, `Region`, `Description`) VALUES
(1, 'Zone soudanienne', 'Savanes', NULL);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `agriculteur`
--
ALTER TABLE `agriculteur`
  ADD CONSTRAINT `agriculteur_ibfk_1` FOREIGN KEY (`Id_coop`) REFERENCES `cooperative` (`Id_coop`),
  ADD CONSTRAINT `agriculteur_ibfk_2` FOREIGN KEY (`Id_util`) REFERENCES `utilisateur` (`Id_util`) ON DELETE SET NULL;

--
-- Contraintes pour la table `mise_en_culture`
--
ALTER TABLE `mise_en_culture`
  ADD CONSTRAINT `mise_en_culture_ibfk_1` FOREIGN KEY (`Id_parcelle`) REFERENCES `parcelle` (`Id_parcelle`),
  ADD CONSTRAINT `mise_en_culture_ibfk_2` FOREIGN KEY (`Id_culture`) REFERENCES `culture` (`Id_culture`),
  ADD CONSTRAINT `mise_en_culture_ibfk_3` FOREIGN KEY (`Id_saison`) REFERENCES `saison` (`Id_saison`) ON DELETE SET NULL;

--
-- Contraintes pour la table `parcelle`
--
ALTER TABLE `parcelle`
  ADD CONSTRAINT `parcelle_ibfk_1` FOREIGN KEY (`Id_agri`) REFERENCES `agriculteur` (`Id_agri`),
  ADD CONSTRAINT `parcelle_ibfk_2` FOREIGN KEY (`Id_zone`) REFERENCES `zone_agroecologique` (`Id_zone`);

--
-- Contraintes pour la table `recolte`
--
ALTER TABLE `recolte`
  ADD CONSTRAINT `recolte_ibfk_1` FOREIGN KEY (`Id_parcelle`) REFERENCES `parcelle` (`Id_parcelle`),
  ADD CONSTRAINT `recolte_ibfk_2` FOREIGN KEY (`Id_culture`) REFERENCES `culture` (`Id_culture`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`Id_coop`) REFERENCES `cooperative` (`Id_coop`) ON DELETE SET NULL;

--
-- Contraintes pour la table `utiliser`
--
ALTER TABLE `utiliser`
  ADD CONSTRAINT `utiliser_ibfk_1` FOREIGN KEY (`Id_parcelle`) REFERENCES `parcelle` (`Id_parcelle`),
  ADD CONSTRAINT `utiliser_ibfk_2` FOREIGN KEY (`Id_intrant`) REFERENCES `intrant` (`Id_intrant`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
