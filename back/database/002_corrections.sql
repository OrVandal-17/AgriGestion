-- ============================================================================
-- 002_corrections.sql
-- Corrections apportees au schema `gestion_agricole_togo` suite a l'analyse
-- du diagramme de cas d'utilisation. A executer APRES gestion_agricole_togo.sql
-- ============================================================================
-- Probleme 1 : aucun lien entre un compte `utilisateur` (role Agriculteur)
--              et sa fiche `agriculteur`. Impossible de savoir quelles
--              parcelles appartiennent a l'utilisateur connecte.
-- Probleme 2 : la table `saison` n'etait reliee a aucune autre table alors
--              que le cas d'utilisation "Selectionner une saison" existe.
-- Probleme 3 : `cultiver` (Id_parcelle, Id_culture) en cle primaire composite
--              empeche d'enregistrer plusieurs mises en culture successives
--              (rotation) sur la meme parcelle avec la meme culture au fil
--              des saisons/annees. Necessaire pour l'alerte de rotation et
--              pour l'historique de l'agriculteur.
-- Probleme 4 : `utiliser` a le meme souci de cle composite (pas de date de
--              creation d'enregistrement fiable pour l'historique).
-- ============================================================================

-- 1) Lier un agriculteur a son compte utilisateur (login)
ALTER TABLE `agriculteur`
  ADD COLUMN `Id_util` INT(11) DEFAULT NULL AFTER `Id_coop`;

ALTER TABLE `agriculteur`
  ADD UNIQUE KEY `uniq_agriculteur_util` (`Id_util`);

ALTER TABLE `agriculteur`
  ADD CONSTRAINT `agriculteur_ibfk_2`
  FOREIGN KEY (`Id_util`) REFERENCES `utilisateur` (`Id_util`) ON DELETE SET NULL;

-- 2) Transformer `cultiver` en table d'historique "mise en culture"
--    (ajout d'une cle de substitution + date + lien vers saison)
ALTER TABLE `cultiver` ADD KEY `idx_cultiver_parcelle` (`Id_parcelle`);

ALTER TABLE `cultiver`
  DROP PRIMARY KEY,
  ADD COLUMN `Id_mise_en_culture` INT(11) NOT NULL AUTO_INCREMENT FIRST,
  ADD PRIMARY KEY (`Id_mise_en_culture`);

ALTER TABLE `cultiver`
  ADD COLUMN `Id_saison` INT(11) DEFAULT NULL AFTER `Id_culture`,
  ADD COLUMN `DateMiseEnCulture` DATE NOT NULL DEFAULT (CURRENT_DATE) AFTER `Id_saison`;

ALTER TABLE `cultiver`
  ADD CONSTRAINT `cultiver_ibfk_3`
  FOREIGN KEY (`Id_saison`) REFERENCES `saison` (`Id_saison`) ON DELETE SET NULL;

RENAME TABLE `cultiver` TO `mise_en_culture`;

-- 3) Meme correction pour `utiliser` (intrants utilises) : cle de
--    substitution pour permettre plusieurs saisies le meme jour et fiabiliser
--    l'historique.
ALTER TABLE `utiliser`
  ADD KEY `idx_utiliser_parcelle` (`Id_parcelle`),
  ADD KEY `idx_utiliser_intrant` (`Id_intrant`);

ALTER TABLE `utiliser`
  DROP PRIMARY KEY,
  ADD COLUMN `Id_utilisation` INT(11) NOT NULL AUTO_INCREMENT FIRST,
  ADD PRIMARY KEY (`Id_utilisation`);

-- 4) Index utiles pour les tableaux de bord "Responsable de cooperative"
ALTER TABLE `recolte` ADD KEY `idx_recolte_date` (`DateRecolte`);

-- ============================================================================
-- Fin des corrections
-- ============================================================================
