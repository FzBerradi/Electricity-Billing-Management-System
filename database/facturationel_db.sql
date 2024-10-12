-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : dim. 10 mars 2024 à 14:11
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
-- Base de données : `facturationel`
--

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `CIN_CL` varchar(255) NOT NULL,
  `NOM_CL` varchar(255) DEFAULT NULL,
  `PRENOM_CL` varchar(255) DEFAULT NULL,
  `ADDRESS` varchar(255) DEFAULT NULL,
  `PASSWORD` varchar(255) DEFAULT NULL,
  `EMAIL_CL` varchar(255) DEFAULT NULL,
  `TEL_CL` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`CIN_CL`, `NOM_CL`, `PRENOM_CL`, `ADDRESS`, `PASSWORD`, `EMAIL_CL`, `TEL_CL`) VALUES
('FD1234', 'Fatimazg', 'Hanae', 'Tanger,Maroc', '1234', 'maroua@gmail.com', '+21276354627'),
('GM5463', 'Alae', 'Alami', 'mdiq_Rue1', '1234', 'SA@gmail.com', '+34212345567'),
('HI9822', 'Mohammed', 'chairi', 'Tanger Rue1', '1234', 'chairiali@gmail.com', '0789675544'),
('LF1256', 'ALI', 'AHMED', 'Tetouan rue12', '1234', 'aliahmed@gmail.com', '0767542345'),
('LF43212', 'Berradi', 'FatimaZohra', 'Fnideq,Maroc', '1234', 'fatimazh@gmail.com', '0789654432'),
('LL5463', 'fatima', 'berradi', 'fnideq75', '1234', 'berradi@gmail.com', '07896543521'),
('MN4567', 'Arami', 'Salima', 'Tanger,Maroc', '1234', 'aramisalima@gmail.com', '0789675432');

-- --------------------------------------------------------

--
-- Structure de la table `consommation_annuelle`
--

CREATE TABLE `consommation_annuelle` (
  `CIN_CL` varchar(255) DEFAULT NULL,
  `CONSOMMATION` float DEFAULT NULL,
  `ANNEE` int(11) DEFAULT NULL,
  `DATE_SAISIE` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `consommation_annuelle`
--

INSERT INTO `consommation_annuelle` (`CIN_CL`, `CONSOMMATION`, `ANNEE`, `DATE_SAISIE`) VALUES
('LF43212', 8900, 2024, '2024-12-31'),
('LF43212', 8900, 2024, '2024-12-31'),
('LF43212', 8900, 2024, '2024-12-31'),
('LF43212', 8900, 2024, '2024-12-31'),
('LF1256', 8900, 2024, '2024-12-31');

-- --------------------------------------------------------

--
-- Structure de la table `facture`
--

CREATE TABLE `facture` (
  `ID_FACTURE` int(11) NOT NULL,
  `CIN_CL` varchar(255) DEFAULT NULL,
  `PHOTO_COMPTEUR` varchar(255) DEFAULT NULL,
  `CONSOMMATION` float DEFAULT NULL,
  `ANOMALIE` int(11) DEFAULT NULL,
  `CONS_ACT` float DEFAULT NULL,
  `CONS_ANC` float NOT NULL,
  `PRIX_HT` float DEFAULT NULL,
  `PRIX_TTC` float DEFAULT NULL,
  `STATUS` varchar(255) DEFAULT NULL,
  `ANNEE` int(11) DEFAULT year(curdate()),
  `MOIS` varchar(15) DEFAULT NULL,
  `ADDRESS` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `facture`
--

INSERT INTO `facture` (`ID_FACTURE`, `CIN_CL`, `PHOTO_COMPTEUR`, `CONSOMMATION`, `ANOMALIE`, `CONS_ACT`, `CONS_ANC`, `PRIX_HT`, `PRIX_TTC`, `STATUS`, `ANNEE`, `MOIS`, `ADDRESS`) VALUES
(74, 'LF1256', 'uploads/compp.jpg', 100, 0, 100, 0, 80, 91.2, 'unpaid', 2024, '1', 'Tetouan rue12'),
(75, 'LF1256', 'uploads/compp.jpg', 100, 0, 200, 100, 180, 205.2, 'paid', 2024, '2', 'Tetouan rue12'),
(91, 'LF1256', 'uploads/compp.jpg', 600, 0, 800, 200, 606, 690.84, 'paid', 2024, '3', 'Tetouan rue12'),
(126, 'LF1256', 'uploads/compp.jpg', 50, 0, 850, 800, 858.5, 978.69, 'unpaid', 2024, '4', 'Tetouan rue12'),
(127, 'LF1256', 'uploads/compp.jpg', 50, 0, 900, 850, 40, 45.6, 'unpaid', 2024, '5', 'Tetouan rue12'),
(135, 'LF1256', 'uploads/compp.jpg', -500, 1, 400, 900, 404, 460.56, NULL, 2024, '6', 'Tetouan rue12');

-- --------------------------------------------------------

--
-- Structure de la table `fournisseur`
--

CREATE TABLE `fournisseur` (
  `ID_FOURNI` int(11) NOT NULL,
  `EMAIL_F` varchar(255) NOT NULL,
  `PASSWORD_F` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `fournisseur`
--

INSERT INTO `fournisseur` (`ID_FOURNI`, `EMAIL_F`, `PASSWORD_F`) VALUES
(1, 'admin11@gmail.com', 'admin1'),
(2, 'admin2@gmail.com', 'admin2'),
(3, 'admin3@gmail.com', 'admin3');

-- --------------------------------------------------------

--
-- Structure de la table `reclamation`
--

CREATE TABLE `reclamation` (
  `ID_RECLAMATION` int(11) NOT NULL,
  `CIN_CL` varchar(255) DEFAULT NULL,
  `TYPE` varchar(255) DEFAULT NULL,
  `DESCRIPTION` varchar(255) DEFAULT NULL,
  `REPONSE` varchar(255) NOT NULL,
  `STATUS` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reclamation`
--

INSERT INTO `reclamation` (`ID_RECLAMATION`, `CIN_CL`, `TYPE`, `DESCRIPTION`, `REPONSE`, `STATUS`) VALUES
(1, 'GM5463', 'Fuite interne', 'test', 'en attente', 'En attente'),
(2, 'LL5463', 'Facture', 'facture indisponible', 'Done', 'Résolu'),
(3, 'LF43212', 'Facture', 'j\'ai pas recu la facture de ce mois!', 'Done', 'Résolu'),
(7, 'LF43212', 'Autres', 'call me or send me help', 'fixed', 'Résolu'),
(8, 'LF1256', 'Fuite interne', 'fuite externe ,j\'ai besoin de votre assistant', 'Ok c\'est bon', 'En attente'),
(12, 'LF1256', 'Fuite interne', 'Autres..test', 'en attente juste moment', 'En attente'),
(13, 'LF43212', 'Facture', 'J\'ai pas recus la facture de mars', 'vous pouvez verifier maintenant', 'En attente'),
(15, 'LF1256', 'Fuite interne', 'I need an assistant pls', 'In progress..wait a moment', 'In progress'),
(17, 'LF1256', 'Autres', 'qqqqqqqqqqqqqqqqqqqqqqqqqqqqq', '', '');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`CIN_CL`);

--
-- Index pour la table `consommation_annuelle`
--
ALTER TABLE `consommation_annuelle`
  ADD KEY `CIN_CL` (`CIN_CL`);

--
-- Index pour la table `facture`
--
ALTER TABLE `facture`
  ADD PRIMARY KEY (`ID_FACTURE`),
  ADD KEY `CIN_CL` (`CIN_CL`);

--
-- Index pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  ADD PRIMARY KEY (`ID_FOURNI`),
  ADD UNIQUE KEY `EMAIL_F` (`EMAIL_F`);

--
-- Index pour la table `reclamation`
--
ALTER TABLE `reclamation`
  ADD PRIMARY KEY (`ID_RECLAMATION`),
  ADD KEY `CIN_CL` (`CIN_CL`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `facture`
--
ALTER TABLE `facture`
  MODIFY `ID_FACTURE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=136;

--
-- AUTO_INCREMENT pour la table `reclamation`
--
ALTER TABLE `reclamation`
  MODIFY `ID_RECLAMATION` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `consommation_annuelle`
--
ALTER TABLE `consommation_annuelle`
  ADD CONSTRAINT `consommation_annuelle_ibfk_1` FOREIGN KEY (`CIN_CL`) REFERENCES `client` (`CIN_CL`);

--
-- Contraintes pour la table `facture`
--
ALTER TABLE `facture`
  ADD CONSTRAINT `facture_ibfk_1` FOREIGN KEY (`CIN_CL`) REFERENCES `client` (`CIN_CL`);

--
-- Contraintes pour la table `reclamation`
--
ALTER TABLE `reclamation`
  ADD CONSTRAINT `reclamation_ibfk_1` FOREIGN KEY (`CIN_CL`) REFERENCES `client` (`CIN_CL`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
