-- phpMyAdmin SQL Dump
-- Base de données : `gestion_agricole_togo`
--
-- Schéma regénéré à partir du MLD (Modèle Logique de Données) :
--   Utilisateur / Administrateur / Responsable / Agriculteur (spécialisation
--   par héritage : IdUtil est à la fois clé primaire ET clé étrangère vers
--   Utilisateur dans les 3 tables de rôle), Cooperative, Culture,
--   ZoneAgroEcologique, Intrant, Saison, Parcelle, Exploitation
--   (anciennement "mise_en_culture"), Recolte, Utiliser.
--
-- Codification des identifiants :
--   Plus d'auto-increment sur les cles primaires : chaque table utilise un
--   code alphanumerique "PREFIXE-XXXXXX" (6 caracteres apres le prefixe,
--   alphabet sans caracteres ambigus 0/O/1/I/L). Le code est genere cote
--   backend (voir Model::create()) au moment de l'insertion.
--   Prefixes retenus : USR (utilisateur), COO (cooperative), CUL (culture),
--   ZAE (zone_agroecologique), INT (intrant), SAI (saison), PAR (parcelle),
--   EXP (exploitation), REC (recolte).
--   Administrateur / Responsable / Agriculteur n'ont pas de prefixe propre :
--   ils reprennent tel quel le code IdUtil de l'utilisateur (heritage, PK = FK).

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
-- (compte de connexion commun à tous les rôles)
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `IdUtil` varchar(12) COLLATE utf8mb4_general_ci NOT NULL,
  `Nom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Prenom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Tel` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Email` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `DateNaissance` date DEFAULT NULL,
  `Sexe` char(1) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `PassHash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`IdUtil`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`IdUtil`, `Nom`, `Prenom`, `Tel`, `Email`, `DateNaissance`, `Sexe`, `PassHash`) VALUES
('USR-HZCT6D', 'Koffi', 'Systeme', NULL, 'admin@agrigestion.tg', NULL, NULL, '$2b$10$PL0BRX1ENbQNMyKX.mRUROZt1eEIoW9WEP/JPJnoOU6YQROYiCiOK'),
('USR-7WDUH6', 'Amegan', 'Koffi', '91112233', 'ama@ex.tg', NULL, 'M', '$2y$10$6zKkXabOJ5t2qONhqLK/bupPeMGKXsOo56blEDzcK19o49EJTQz..'),
('USR-7PK5X6', 'Mensah', 'Afi', '90112233', 'responsable@ex.tg', NULL, 'F', '$2y$10$6zKkXabOJ5t2qONhqLK/bupPeMGKXsOo56blEDzcK19o49EJTQz..');

-- --------------------------------------------------------

--
-- Structure de la table `culture`
--

DROP TABLE IF EXISTS `culture`;
CREATE TABLE IF NOT EXISTS `culture` (
  `IdCulture` varchar(12) COLLATE utf8mb4_general_ci NOT NULL,
  `NomCulture` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `TypeCulture` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `DureeCycle` int DEFAULT NULL,
  PRIMARY KEY (`IdCulture`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `culture`
--

INSERT INTO `culture` (`IdCulture`, `NomCulture`, `TypeCulture`, `DureeCycle`) VALUES
('CUL-QP7GU5', 'Mais', 'Cereale', 90);

-- --------------------------------------------------------

--
-- Structure de la table `zone_agroecologique`
--

DROP TABLE IF EXISTS `zone_agroecologique`;
CREATE TABLE IF NOT EXISTS `zone_agroecologique` (
  `IdZone` varchar(12) COLLATE utf8mb4_general_ci NOT NULL,
  `NomZone` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Region` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Description` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`IdZone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `zone_agroecologique`
--

INSERT INTO `zone_agroecologique` (`IdZone`, `NomZone`, `Region`, `Description`) VALUES
('ZAE-FMKRMY', 'Zone soudanienne', 'Savanes', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `intrant`
--

DROP TABLE IF EXISTS `intrant`;
CREATE TABLE IF NOT EXISTS `intrant` (
  `IdIntrant` varchar(12) COLLATE utf8mb4_general_ci NOT NULL,
  `NomIntrant` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `TypeIntrant` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Unite` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `PrixUnitaire` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`IdIntrant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `intrant`
--

INSERT INTO `intrant` (`IdIntrant`, `NomIntrant`, `TypeIntrant`, `Unite`, `PrixUnitaire`) VALUES
('INT-SJM5MS', 'Uree', 'Engrais', 'kg', 350.00);

-- --------------------------------------------------------

--
-- Structure de la table `saison`
--

DROP TABLE IF EXISTS `saison`;
CREATE TABLE IF NOT EXISTS `saison` (
  `IdSaison` varchar(12) COLLATE utf8mb4_general_ci NOT NULL,
  `Libelle` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `DateDebut` date DEFAULT NULL,
  `DateFin` date DEFAULT NULL,
  PRIMARY KEY (`IdSaison`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `saison`
--

INSERT INTO `saison` (`IdSaison`, `Libelle`, `DateDebut`, `DateFin`) VALUES
('SAI-EE3FFT', 'Saison des pluies 2026', '2026-04-01', '2026-09-30');

-- --------------------------------------------------------

--
-- Structure de la table `cooperative`
--

DROP TABLE IF EXISTS `cooperative`;
CREATE TABLE IF NOT EXISTS `cooperative` (
  `IdCoop` varchar(12) COLLATE utf8mb4_general_ci NOT NULL,
  `NomCoop` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `AdresseCoop` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `EmailCoop` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`IdCoop`),
  UNIQUE KEY `EmailCoop` (`EmailCoop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cooperative`
--

INSERT INTO `cooperative` (`IdCoop`, `NomCoop`, `AdresseCoop`, `EmailCoop`) VALUES
('COO-5P5BX6', 'Coop du Plateau', 'Kpalime', 'coop@ex.tg');

-- --------------------------------------------------------

--
-- Structure de la table `administrateur`
-- (spécialisation de `utilisateur` : IdUtil est à la fois PK et FK,
-- meme code alphanumerique que dans `utilisateur`)
--

DROP TABLE IF EXISTS `administrateur`;
CREATE TABLE IF NOT EXISTS `administrateur` (
  `IdUtil` varchar(12) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`IdUtil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `administrateur` (`IdUtil`) VALUES
('USR-HZCT6D');

-- --------------------------------------------------------

--
-- Structure de la table `responsable`
-- (spécialisation de `utilisateur`, rattachée à une `cooperative`)
--

DROP TABLE IF EXISTS `responsable`;
CREATE TABLE IF NOT EXISTS `responsable` (
  `IdUtil` varchar(12) COLLATE utf8mb4_general_ci NOT NULL,
  `IdCoop` varchar(12) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`IdUtil`),
  KEY `IdCoop` (`IdCoop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `responsable` (`IdUtil`, `IdCoop`) VALUES
('USR-7PK5X6', 'COO-5P5BX6');

-- --------------------------------------------------------

--
-- Structure de la table `agriculteur`
-- (spécialisation de `utilisateur`, rattachée à une `cooperative`)
--

DROP TABLE IF EXISTS `agriculteur`;
CREATE TABLE IF NOT EXISTS `agriculteur` (
  `IdUtil` varchar(12) COLLATE utf8mb4_general_ci NOT NULL,
  `IdCoop` varchar(12) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`IdUtil`),
  KEY `IdCoop` (`IdCoop`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `agriculteur` (`IdUtil`, `IdCoop`) VALUES
('USR-7WDUH6', 'COO-5P5BX6');

-- --------------------------------------------------------

--
-- Structure de la table `parcelle`
--

DROP TABLE IF EXISTS `parcelle`;
CREATE TABLE IF NOT EXISTS `parcelle` (
  `IdParcelle` varchar(12) COLLATE utf8mb4_general_ci NOT NULL,
  `Superficie` decimal(8,2) DEFAULT NULL,
  `Localisation` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `EtatParcelle` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `IdZone` varchar(12) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `IdUtil` varchar(12) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`IdParcelle`),
  KEY `IdZone` (`IdZone`),
  KEY `IdUtil` (`IdUtil`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `parcelle`
--

INSERT INTO `parcelle` (`IdParcelle`, `Superficie`, `Localisation`, `EtatParcelle`, `IdZone`, `IdUtil`) VALUES
('PAR-NAJ3KY', 1.50, 'Zone Nord', 'En culture', 'ZAE-FMKRMY', 'USR-7WDUH6');

-- --------------------------------------------------------

--
-- Structure de la table `exploitation`
-- (anciennement `mise_en_culture` : une parcelle mise en exploitation
-- pour une culture donnée, sur une saison donnée)
--

DROP TABLE IF EXISTS `exploitation`;
CREATE TABLE IF NOT EXISTS `exploitation` (
  `IdExploitation` varchar(12) COLLATE utf8mb4_general_ci NOT NULL,
  `DateDebut` date DEFAULT NULL,
  `DateFin` date DEFAULT NULL,
  `Etat` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `IdSaison` varchar(12) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `IdCulture` varchar(12) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `IdParcelle` varchar(12) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`IdExploitation`),
  KEY `IdSaison` (`IdSaison`),
  KEY `IdCulture` (`IdCulture`),
  KEY `IdParcelle` (`IdParcelle`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `recolte`
--

DROP TABLE IF EXISTS `recolte`;
CREATE TABLE IF NOT EXISTS `recolte` (
  `IdRecolte` varchar(12) COLLATE utf8mb4_general_ci NOT NULL,
  `DateRecolte` date NOT NULL,
  `Rendement` decimal(10,2) DEFAULT NULL,
  `Cout` decimal(12,2) DEFAULT NULL,
  `Observation` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `IdExploitation` varchar(12) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`IdRecolte`),
  KEY `IdExploitation` (`IdExploitation`),
  KEY `idx_recolte_date` (`DateRecolte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utiliser`
-- (association Intrant <-> Exploitation, clé composite)
--

DROP TABLE IF EXISTS `utiliser`;
CREATE TABLE IF NOT EXISTS `utiliser` (
  `IdIntrant` varchar(12) COLLATE utf8mb4_general_ci NOT NULL,
  `IdExploitation` varchar(12) COLLATE utf8mb4_general_ci NOT NULL,
  `DateUtil` date NOT NULL,
  `Quantite` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`IdIntrant`, `IdExploitation`, `DateUtil`),
  KEY `IdExploitation` (`IdExploitation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `administrateur`
--
ALTER TABLE `administrateur`
  ADD CONSTRAINT `administrateur_ibfk_1` FOREIGN KEY (`IdUtil`) REFERENCES `utilisateur` (`IdUtil`) ON DELETE CASCADE;

--
-- Contraintes pour la table `responsable`
--
ALTER TABLE `responsable`
  ADD CONSTRAINT `responsable_ibfk_1` FOREIGN KEY (`IdUtil`) REFERENCES `utilisateur` (`IdUtil`) ON DELETE CASCADE,
  ADD CONSTRAINT `responsable_ibfk_2` FOREIGN KEY (`IdCoop`) REFERENCES `cooperative` (`IdCoop`) ON DELETE SET NULL;

--
-- Contraintes pour la table `agriculteur`
--
ALTER TABLE `agriculteur`
  ADD CONSTRAINT `agriculteur_ibfk_1` FOREIGN KEY (`IdUtil`) REFERENCES `utilisateur` (`IdUtil`) ON DELETE CASCADE,
  ADD CONSTRAINT `agriculteur_ibfk_2` FOREIGN KEY (`IdCoop`) REFERENCES `cooperative` (`IdCoop`) ON DELETE SET NULL;

--
-- Contraintes pour la table `parcelle`
--
ALTER TABLE `parcelle`
  ADD CONSTRAINT `parcelle_ibfk_1` FOREIGN KEY (`IdZone`) REFERENCES `zone_agroecologique` (`IdZone`),
  ADD CONSTRAINT `parcelle_ibfk_2` FOREIGN KEY (`IdUtil`) REFERENCES `agriculteur` (`IdUtil`);

--
-- Contraintes pour la table `exploitation`
--
ALTER TABLE `exploitation`
  ADD CONSTRAINT `exploitation_ibfk_1` FOREIGN KEY (`IdSaison`) REFERENCES `saison` (`IdSaison`) ON DELETE SET NULL,
  ADD CONSTRAINT `exploitation_ibfk_2` FOREIGN KEY (`IdCulture`) REFERENCES `culture` (`IdCulture`),
  ADD CONSTRAINT `exploitation_ibfk_3` FOREIGN KEY (`IdParcelle`) REFERENCES `parcelle` (`IdParcelle`);

--
-- Contraintes pour la table `recolte`
--
ALTER TABLE `recolte`
  ADD CONSTRAINT `recolte_ibfk_1` FOREIGN KEY (`IdExploitation`) REFERENCES `exploitation` (`IdExploitation`);

--
-- Contraintes pour la table `utiliser`
--
ALTER TABLE `utiliser`
  ADD CONSTRAINT `utiliser_ibfk_1` FOREIGN KEY (`IdIntrant`) REFERENCES `intrant` (`IdIntrant`),
  ADD CONSTRAINT `utiliser_ibfk_2` FOREIGN KEY (`IdExploitation`) REFERENCES `exploitation` (`IdExploitation`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
