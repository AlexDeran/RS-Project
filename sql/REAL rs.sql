-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Lun 26 Novembre 2018 à 10:42
-- Version du serveur :  5.7.24-0ubuntu0.18.04.1
-- Version de PHP :  7.2.10-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `rs`
--

-- --------------------------------------------------------

--
-- Structure de la table `appartenance`
--

CREATE TABLE `appartenance` (
  `utilisateurs_nom` varchar(255) CHARACTER SET utf8 NOT NULL,
  `catRS_nom` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Contenu de la table `appartenance`
--

INSERT INTO `appartenance` (`utilisateurs_nom`, `catRS_nom`) VALUES
('André José', 'Entretien Ménager'),
('René Champard', 'Entretien Ménager'),
('Frédéric Depuiset', 'Polyvalence');

-- --------------------------------------------------------

--
-- Structure de la table `catAutre`
--

CREATE TABLE `catAutre` (
  `id` int(10) NOT NULL,
  `nom` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Contenu de la table `catAutre`
--

INSERT INTO `catAutre` (`id`, `nom`) VALUES
(1, 'Information collective'),
(2, 'Formations'),
(3, 'Forum emploi'),
(4, 'Création d’entreprise'),
(5, 'Tests / Concours');

-- --------------------------------------------------------

--
-- Structure de la table `catPE`
--

CREATE TABLE `catPE` (
  `id` int(10) UNSIGNED NOT NULL,
  `nom` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Contenu de la table `catPE`
--

INSERT INTO `catPE` (`id`, `nom`) VALUES
(1, 'Agriculture'),
(2, 'Animation'),
(3, 'Bâtiment'),
(4, 'Commerce / Vente'),
(5, 'Espaces verts et naturels\n'),
(6, 'Hôtellerie - Restauration'),
(7, 'Industrie'),
(8, 'Informatique / Numérique'),
(9, 'Logistique'),
(10, 'Maintenance'),
(11, 'Mécanique'),
(12, 'Médico-social'),
(13, 'Secrétariat'),
(14, 'Sécurité'),
(15, 'Services à la personne'),
(16, 'Services à la collectivité'),
(17, 'Tourisme'),
(18, 'Transport'),
(19, 'Travaux Publics');

-- --------------------------------------------------------

--
-- Structure de la table `catRS`
--

CREATE TABLE `catRS` (
  `id` int(10) UNSIGNED NOT NULL,
  `nom` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Contenu de la table `catRS`
--

INSERT INTO `catRS` (`id`, `nom`) VALUES
(2, 'Entretien Ménager'),
(3, 'Espaces Verts'),
(4, 'Polyvalence'),
(1, 'Tous');

-- --------------------------------------------------------

--
-- Structure de la table `contenu`
--

CREATE TABLE `contenu` (
  `id` int(11) UNSIGNED NOT NULL,
  `nom_contenu` varchar(255) CHARACTER SET utf8 NOT NULL,
  `cat_Doc` varchar(255) CHARACTER SET utf8 NOT NULL,
  `cat_DocsUtiles` varchar(255) CHARACTER SET utf8 NOT NULL,
  `nom_catRS` varchar(255) CHARACTER SET utf8 NOT NULL,
  `nom_catPE` varchar(255) CHARACTER SET utf8 NOT NULL,
  `date_publication` date NOT NULL,
  `texte` longtext CHARACTER SET utf8 NOT NULL,
  `img` varchar(255) CHARACTER SET utf8 NOT NULL,
  `pieces_jointes` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(10) UNSIGNED NOT NULL,
  `nom` varchar(255) CHARACTER SET utf8 NOT NULL,
  `identifiant` varchar(255) CHARACTER SET utf8 NOT NULL,
  `mdphash` varchar(255) CHARACTER SET utf8 NOT NULL,
  `statut` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Contenu de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `identifiant`, `mdphash`, `statut`) VALUES
(62, 'Alexandre Labreveux', 'Alexandre', '$2y$10$9qxszwJX/yxqv0Rf6nz4TedadgEsf0N.Kz2PtbGvIV4XZd4YzxMsu', 'admin'),
(65, 'Frédéric Depuiset', 'Frédéric', '$2y$10$9v.F3xMSja9lgm4Pmj.qsuItGNKZsDh1Q9Vrr4jQUXge1H6MgNPu2', 'salaries'),
(66, 'René Champard', 'René', '$2y$10$3NVBMs1gdfJufl.fWgWgQ.yusE5Zw/GKz4s7pCahk5WTZfdc9QtoO', 'salaries'),
(72, 'André José', 'André', '$2y$10$wBCdp7ajYbGnMlvztjIKlunN37RTBQenbcPOyvQ2W2njX9jRNKy36', 'salaries');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `appartenance`
--
ALTER TABLE `appartenance`
  ADD PRIMARY KEY (`utilisateurs_nom`),
  ADD KEY `catRS` (`catRS_nom`);

--
-- Index pour la table `catAutre`
--
ALTER TABLE `catAutre`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `catPE`
--
ALTER TABLE `catPE`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `catRS`
--
ALTER TABLE `catRS`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nom` (`nom`);

--
-- Index pour la table `contenu`
--
ALTER TABLE `contenu`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nom` (`nom`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `catAutre`
--
ALTER TABLE `catAutre`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT pour la table `catPE`
--
ALTER TABLE `catPE`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT pour la table `catRS`
--
ALTER TABLE `catRS`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `contenu`
--
ALTER TABLE `contenu`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
