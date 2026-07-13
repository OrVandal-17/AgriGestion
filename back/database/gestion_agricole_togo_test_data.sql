-- Jeu de données de test pour `gestion_agricole_togo`

-- Mots de passe : tous les comptes ci-dessous utilisent "Test1234!" en
-- clair. Les hachages sont generes avec password_hash(..., PASSWORD_BCRYPT),
-- exactement l'algorithme utilise par UtilisateurController::store()/update()
-- cote backend -- donc verifiables via password_verify() sans retouche.

START TRANSACTION;

--
-- utilisateur (1 admin, 2 responsables, 5 agriculteurs)
--

INSERT INTO `utilisateur` (`IdUtil`, `Nom`, `Prenom`, `Tel`, `Email`, `DateNaissance`, `Sexe`, `PassHash`) VALUES
('USR-FC7VPB', 'Adjo', 'Sena', '92223344', 'admin2@agrigestion.tg', '1990-03-14', 'F', '$2y$10$WKm2eMSwslnXjDWftwbTrOHk6zZwi06C5exW4WxxJR6vVhfScRLm6'),
('USR-RSK8YR', 'Ayao', 'Kodjo', '90334455', 'k.ayao@ex.tg', '1985-07-02', 'M', '$2y$10$DajJjqzR5t1949aKArKHIeuz7xNdSfs5I5sl1DI1DZuDSBxgtqQwO'),
('USR-A5E947', 'Fiadjoe', 'Ama', '91445566', 'a.fiadjoe@ex.tg', '1988-11-23', 'F', '$2y$10$FqzhsfsIMM.gezuMoZDVQO6L6Z8RKZJix5ITZ1ZVpO/TSGhj.C2ey'),
('USR-2TV8ZU', 'Tchamie', 'Kossi', '93556677', 'k.tchamie@ex.tg', '1992-01-09', 'M', '$2y$10$m0AUwHDaERhYazeRmolRFuIlYwYC3EZByUP0M9Y5cHEYEFHEf9ysy'),
('USR-6BFT65', 'Bakoma', 'Essowe', '90667788', 'e.bakoma@ex.tg', '1979-05-30', 'M', '$2y$10$.C3WIeT/o9CyMfqH4FmWZ.qTUKm4LNn92jM5ub7N0f.EFwF.7wcEC'),
('USR-4C3RFM', 'Nassourou', 'Abiba', '91778899', 'a.nassourou@ex.tg', '1995-09-17', 'F', '$2y$10$W58XxwDVHUM/Ygixr90blOfEQovNh3ZTQoj0peS9yIznVVQmNNYb6'),
('USR-7V7RRH', 'Palanga', 'Yendoubouame', '92889900', 'y.palanga@ex.tg', '1983-12-04', 'M', '$2y$10$Ybe2vfd4UiCgAbynwfoL6u1L6N6qrDIzBjyz.A0bQrT.YXnzE8rqW'),
('USR-3TU27F', 'Kolani', 'Djifa', '93990011', 'd.kolani@ex.tg', '1998-02-27', 'F', '$2y$10$KLJjt9P8yXMQ.njzgCVFy./PRsHOJ80DyuN.6a9DO.lcK80gWVQL.');

--
-- cooperative
--

INSERT INTO `cooperative` (`IdCoop`, `NomCoop`, `AdresseCoop`, `EmailCoop`) VALUES
('COO-4W6A23', 'Coop de la Kara', 'Kara', 'coopkara@ex.tg'),
('COO-MRD9DQ', 'Coop des Savanes', 'Dapaong', 'coopsavanes@ex.tg'),
('COO-KBNEHX', 'Coop Maritime', 'Tsevie', 'coopmaritime@ex.tg');

--
-- administrateur / responsable / agriculteur
-- (specialisations : reprennent le code IdUtil correspondant)
--

INSERT INTO `administrateur` (`IdUtil`) VALUES
('USR-FC7VPB');

INSERT INTO `responsable` (`IdUtil`, `IdCoop`) VALUES
('USR-RSK8YR', 'COO-4W6A23'),
('USR-A5E947', 'COO-MRD9DQ');

INSERT INTO `agriculteur` (`IdUtil`, `IdCoop`) VALUES
('USR-2TV8ZU', 'COO-5P5BX6'),
('USR-6BFT65', 'COO-4W6A23'),
('USR-4C3RFM', 'COO-MRD9DQ'),
('USR-7V7RRH', 'COO-KBNEHX'),
('USR-3TU27F', 'COO-KBNEHX');

--
-- culture
--

INSERT INTO `culture` (`IdCulture`, `NomCulture`, `TypeCulture`, `DureeCycle`) VALUES
('CUL-6SXU7B', 'Sorgho', 'Cereale', 120),
('CUL-AHY6HK', 'Riz', 'Cereale', 150),
('CUL-X3RTMT', 'Manioc', 'Tubercule', 300),
('CUL-PEEZWG', 'Coton', 'Fibre', 180);

--
-- zone_agroecologique
--

INSERT INTO `zone_agroecologique` (`IdZone`, `NomZone`, `Region`, `Description`) VALUES
('ZAE-NYRABY', 'Zone guineenne', 'Plateaux', NULL),
('ZAE-6FZHVR', 'Zone maritime', 'Maritime', NULL),
('ZAE-4SFBTF', 'Zone des savanes', 'Savanes', NULL);

--
-- intrant
--

INSERT INTO `intrant` (`IdIntrant`, `NomIntrant`, `TypeIntrant`, `Unite`, `PrixUnitaire`) VALUES
('INT-YCAJTY', 'NPK 15-15-15', 'Engrais', 'kg', 400.00),
('INT-R4CAGD', 'Semences ameliorees mais', 'Semence', 'kg', 800.00),
('INT-N4GMAY', 'Glyphosate', 'Herbicide', 'L', 3500.00),
('INT-TFKXX8', 'Insecticide Lambda', 'Insecticide', 'L', 5200.00);

--
-- saison
--

INSERT INTO `saison` (`IdSaison`, `Libelle`, `DateDebut`, `DateFin`) VALUES
('SAI-PQDHVB', 'Saison seche 2026', '2026-11-01', '2027-03-31'),
('SAI-WXANST', 'Saison des pluies 2027', '2027-04-01', '2027-09-30');

--
-- parcelle
--

INSERT INTO `parcelle` (`IdParcelle`, `Superficie`, `Localisation`, `EtatParcelle`, `IdZone`, `IdUtil`) VALUES
('PAR-H3SVGP', 2.00, 'Kara Nord', 'En culture', 'ZAE-NYRABY', 'USR-6BFT65'),
('PAR-E335AF', 0.80, 'Dapaong Est', 'En jachere', 'ZAE-4SFBTF', 'USR-4C3RFM'),
('PAR-UPK47M', 3.20, 'Tsevie Sud', 'En culture', 'ZAE-6FZHVR', 'USR-7V7RRH'),
('PAR-424TDZ', 1.10, 'Tsevie Nord', 'Disponible', 'ZAE-6FZHVR', 'USR-3TU27F'),
('PAR-4TDUFQ', 2.50, 'Kara Sud', 'En culture', 'ZAE-NYRABY', 'USR-2TV8ZU'),
('PAR-FTHJBE', 1.75, 'Dapaong Ouest', 'En culture', 'ZAE-4SFBTF', 'USR-4C3RFM');

--
-- exploitation
--

INSERT INTO `exploitation` (`IdExploitation`, `DateDebut`, `DateFin`, `Etat`, `IdSaison`, `IdCulture`, `IdParcelle`) VALUES
('EXP-TNATN9', '2026-04-05', NULL, 'En cours', 'SAI-EE3FFT', 'CUL-QP7GU5', 'PAR-NAJ3KY'),
('EXP-347H63', '2026-04-10', '2026-08-20', 'Terminee', 'SAI-EE3FFT', 'CUL-6SXU7B', 'PAR-H3SVGP'),
('EXP-WKECG2', '2026-04-15', NULL, 'En cours', 'SAI-EE3FFT', 'CUL-AHY6HK', 'PAR-UPK47M'),
('EXP-JD4DXX', '2026-11-05', NULL, 'En cours', 'SAI-PQDHVB', 'CUL-X3RTMT', 'PAR-4TDUFQ'),
('EXP-AMYQVV', '2026-04-08', '2026-09-01', 'Terminee', 'SAI-EE3FFT', 'CUL-PEEZWG', 'PAR-FTHJBE'),
('EXP-3EN92D', '2026-11-10', NULL, 'En cours', 'SAI-PQDHVB', 'CUL-QP7GU5', 'PAR-E335AF');

--
-- recolte
--

INSERT INTO `recolte` (`IdRecolte`, `DateRecolte`, `Rendement`, `Cout`, `Observation`, `IdExploitation`) VALUES
('REC-P9S2HC', '2026-08-25', 3.20, 45000.00, 'Bonne pluviometrie', 'EXP-347H63'),
('REC-T73YET', '2026-09-05', 2.80, 60000.00, 'Attaque de ravageurs en fin de cycle', 'EXP-AMYQVV'),
('REC-K2DXHK', '2026-08-10', 1.50, 30000.00, 'Recolte partielle', 'EXP-TNATN9'),
('REC-T8QE6R', '2026-08-30', 4.10, 52000.00, NULL, 'EXP-WKECG2'),
('REC-BGC5QJ', '2026-09-10', 0.95, 15000.00, 'Deficit hydrique', 'EXP-3EN92D');

--
-- utiliser (Intrant <-> Exploitation)
--

INSERT INTO `utiliser` (`IdIntrant`, `IdExploitation`, `DateUtil`, `Quantite`) VALUES
('INT-SJM5MS', 'EXP-TNATN9', '2026-04-06', 25.00),
('INT-YCAJTY', 'EXP-347H63', '2026-04-12', 40.00),
('INT-R4CAGD', 'EXP-WKECG2', '2026-04-16', 15.00),
('INT-N4GMAY', 'EXP-JD4DXX', '2026-11-06', 3.00),
('INT-TFKXX8', 'EXP-AMYQVV', '2026-04-20', 2.50),
('INT-SJM5MS', 'EXP-3EN92D', '2026-11-12', 20.00);

COMMIT;
