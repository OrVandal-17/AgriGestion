-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 30 juin 2026 à 07:41
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
-- Base de données : `gestion_agricole_togo`
--

-- --------------------------------------------------------

--
-- Structure de la table `agriculteur`
--

CREATE TABLE `agriculteur` (
  `Id_agri` int(11) NOT NULL,
  `Nom_agri` varchar(100) NOT NULL,
  `Prenom_agri` varchar(100) NOT NULL,
  `Sexe` char(1) DEFAULT NULL,
  `DateNaissance` date DEFAULT NULL,
  `Telephone` varchar(20) DEFAULT NULL,
  `Adresse` varchar(255) DEFAULT NULL,
  `Id_coop` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cooperative`
--

CREATE TABLE `cooperative` (
  `Id_coop` int(11) NOT NULL,
  `Nom_coop` varchar(150) NOT NULL,
  `Adresse_coop` varchar(255) DEFAULT NULL,
  `Telephone_coop` varchar(20) DEFAULT NULL,
  `Email_coop` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cultiver`
--

CREATE TABLE `cultiver` (
  `Id_parcelle` int(11) NOT NULL,
  `Id_culture` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `culture`
--

CREATE TABLE `culture` (
  `Id_culture` int(11) NOT NULL,
  `Nom_culture` varchar(100) NOT NULL,
  `Type_culture` varchar(100) DEFAULT NULL,
  `DatePlantation` date DEFAULT NULL,
  `DureeCycle` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `intrant`
--

CREATE TABLE `intrant` (
  `Id_intrant` int(11) NOT NULL,
  `Nom_intrant` varchar(100) NOT NULL,
  `Type_intrant` varchar(100) DEFAULT NULL,
  `Unite` varchar(20) DEFAULT NULL,
  `PrixUnitaire` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `parcelle`
--

CREATE TABLE `parcelle` (
  `Id_parcelle` int(11) NOT NULL,
  `Superficie` decimal(10,2) DEFAULT NULL,
  `Localisation` varchar(255) DEFAULT NULL,
  `EtatParcelle` varchar(50) DEFAULT NULL,
  `Id_agri` int(11) NOT NULL,
  `Id_zone` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `recolte`
--

CREATE TABLE `recolte` (
  `Id_recolte` int(11) NOT NULL,
  `DateRecolte` date NOT NULL,
  `Rendement` decimal(10,2) DEFAULT NULL,
  `Cout` decimal(10,2) DEFAULT NULL,
  `Observation` text DEFAULT NULL,
  `Id_parcelle` int(11) NOT NULL,
  `Id_culture` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `saison`
--

CREATE TABLE `saison` (
  `Id_saison` int(11) NOT NULL,
  `Libelle` varchar(50) DEFAULT NULL,
  `DateDebut` date DEFAULT NULL,
  `DateFin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `Id_util` int(11) NOT NULL,
  `Nom_util` varchar(100) NOT NULL,
  `Prenom_util` varchar(100) NOT NULL,
  `Tel_util` varchar(20) DEFAULT NULL,
  `Email_util` varchar(150) NOT NULL,
  `MotPasse_util` varchar(255) NOT NULL,
  `Role_util` enum('Administrateur','Agriculteur','Responsable') NOT NULL,
  `Id_coop` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utiliser`
--

CREATE TABLE `utiliser` (
  `Id_parcelle` int(11) NOT NULL,
  `Id_intrant` int(11) NOT NULL,
  `DateUtilisation` date NOT NULL,
  `Quantite` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `zone_agroecologique`
--

CREATE TABLE `zone_agroecologique` (
  `Id_zone` int(11) NOT NULL,
  `Nom_zone` varchar(100) NOT NULL,
  `Region` varchar(100) DEFAULT NULL,
  `Description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `agriculteur`
--
ALTER TABLE `agriculteur`
  ADD PRIMARY KEY (`Id_agri`),
  ADD KEY `Id_coop` (`Id_coop`);

--
-- Index pour la table `cooperative`
--
ALTER TABLE `cooperative`
  ADD PRIMARY KEY (`Id_coop`),
  ADD UNIQUE KEY `Email_coop` (`Email_coop`);

--
-- Index pour la table `cultiver`
--
ALTER TABLE `cultiver`
  ADD PRIMARY KEY (`Id_parcelle`,`Id_culture`),
  ADD KEY `Id_culture` (`Id_culture`);

--
-- Index pour la table `culture`
--
ALTER TABLE `culture`
  ADD PRIMARY KEY (`Id_culture`);

--
-- Index pour la table `intrant`
--
ALTER TABLE `intrant`
  ADD PRIMARY KEY (`Id_intrant`);

--
-- Index pour la table `parcelle`
--
ALTER TABLE `parcelle`
  ADD PRIMARY KEY (`Id_parcelle`),
  ADD KEY `Id_agri` (`Id_agri`),
  ADD KEY `Id_zone` (`Id_zone`);

--
-- Index pour la table `recolte`
--
ALTER TABLE `recolte`
  ADD PRIMARY KEY (`Id_recolte`),
  ADD KEY `Id_parcelle` (`Id_parcelle`),
  ADD KEY `Id_culture` (`Id_culture`);

--
-- Index pour la table `saison`
--
ALTER TABLE `saison`
  ADD PRIMARY KEY (`Id_saison`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`Id_util`),
  ADD UNIQUE KEY `Email_util` (`Email_util`),
  ADD KEY `Id_coop` (`Id_coop`);

--
-- Index pour la table `utiliser`
--
ALTER TABLE `utiliser`
  ADD PRIMARY KEY (`Id_parcelle`,`Id_intrant`,`DateUtilisation`),
  ADD KEY `Id_intrant` (`Id_intrant`);

--
-- Index pour la table `zone_agroecologique`
--
ALTER TABLE `zone_agroecologique`
  ADD PRIMARY KEY (`Id_zone`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `agriculteur`
--
ALTER TABLE `agriculteur`
  MODIFY `Id_agri` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `cooperative`
--
ALTER TABLE `cooperative`
  MODIFY `Id_coop` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `culture`
--
ALTER TABLE `culture`
  MODIFY `Id_culture` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `intrant`
--
ALTER TABLE `intrant`
  MODIFY `Id_intrant` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `parcelle`
--
ALTER TABLE `parcelle`
  MODIFY `Id_parcelle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `recolte`
--
ALTER TABLE `recolte`
  MODIFY `Id_recolte` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `saison`
--
ALTER TABLE `saison`
  MODIFY `Id_saison` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `Id_util` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `zone_agroecologique`
--
ALTER TABLE `zone_agroecologique`
  MODIFY `Id_zone` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `agriculteur`
--
ALTER TABLE `agriculteur`
  ADD CONSTRAINT `agriculteur_ibfk_1` FOREIGN KEY (`Id_coop`) REFERENCES `cooperative` (`Id_coop`);

--
-- Contraintes pour la table `cultiver`
--
ALTER TABLE `cultiver`
  ADD CONSTRAINT `cultiver_ibfk_1` FOREIGN KEY (`Id_parcelle`) REFERENCES `parcelle` (`Id_parcelle`),
  ADD CONSTRAINT `cultiver_ibfk_2` FOREIGN KEY (`Id_culture`) REFERENCES `culture` (`Id_culture`);

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
