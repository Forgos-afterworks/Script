-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 01 déc. 2021 à 11:30
-- Version du serveur :  10.4.13-MariaDB
-- Version de PHP : 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `afterworks`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `id_article` int(11) NOT NULL,
  `titre` varchar(50) NOT NULL,
  `contenue` varchar(50) NOT NULL,
  `date_creation` date NOT NULL,
  `id_employe` int(11) NOT NULL,
  `id_rubrique` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `catégorie`
--

CREATE TABLE `catégorie` (
  `id_categorie` int(11) NOT NULL,
  `nom_categorie` varchar(50) NOT NULL,
  `description_categorie` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `id_client` int(11) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `CP_adresse` varchar(50) NOT NULL,
  `lib_adresse` varchar(50) NOT NULL,
  `ville_adresse` varchar(50) NOT NULL,
  `tel` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `newsletter` binary(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_commande` int(11) NOT NULL,
  `id_status` int(11) NOT NULL,
  `id_table` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

CREATE TABLE `commentaire` (
  `id_commentaire` int(11) NOT NULL,
  `date_creation_commentaire` date NOT NULL,
  `titre` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `id_client` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `id_declinaison` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `declinaison`
--

CREATE TABLE `declinaison` (
  `id_declinaison` int(11) NOT NULL,
  `nom_declinaison` varchar(50) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `declinaison_table`
--

CREATE TABLE `declinaison_table` (
  `prix_Ht` float NOT NULL,
  `quantite` int(11) DEFAULT NULL,
  `id_produit_declinaison` int(11) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `id_commande` int(11) NOT NULL,
  `id_declinaison` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `employe`
--

CREATE TABLE `employe` (
  `id_employe` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `date_embauche` date NOT NULL,
  `lib_adresse` varchar(50) NOT NULL,
  `CP_adresse` varchar(50) NOT NULL,
  `ville_adresse` varchar(50) NOT NULL,
  `id_role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id_produit` int(11) NOT NULL,
  `nom_produit` varchar(50) NOT NULL,
  `description_produit` text NOT NULL,
  `prix_unitaire_produit` float NOT NULL,
  `image` text DEFAULT NULL,
  `nombre_en_stock` int(11) DEFAULT NULL,
  `id_categorie` int(11) NOT NULL,
  `id_TVA` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `produit_declinaison`
--

CREATE TABLE `produit_declinaison` (
  `id_produit` int(11) NOT NULL,
  `id_declinaison` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `id_role` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `rubrique`
--

CREATE TABLE `rubrique` (
  `id_rubrique` int(11) NOT NULL,
  `description` text NOT NULL,
  `titre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `status`
--

CREATE TABLE `status` (
  `id_status` int(11) NOT NULL,
  `nom_status` varchar(50) NOT NULL,
  `description_status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `tva`
--

CREATE TABLE `tva` (
  `id_TVA` int(11) NOT NULL,
  `pourcentage_TVA` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `tva`
--

INSERT INTO `tva` (`id_TVA`, `pourcentage_TVA`) VALUES
(0, 20);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id_article`),
  ADD KEY `id_employe` (`id_employe`),
  ADD KEY `id_rubrique` (`id_rubrique`);

--
-- Index pour la table `catégorie`
--
ALTER TABLE `catégorie`
  ADD PRIMARY KEY (`id_categorie`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id_client`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `id_status` (`id_status`);

--
-- Index pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`id_commentaire`),
  ADD KEY `id_client` (`id_client`),
  ADD KEY `id_produit` (`id_produit`),
  ADD KEY `id_declinaison` (`id_declinaison`);

--
-- Index pour la table `declinaison`
--
ALTER TABLE `declinaison`
  ADD PRIMARY KEY (`id_declinaison`);

--
-- Index pour la table `declinaison_table`
--
ALTER TABLE `declinaison_table`
  ADD PRIMARY KEY (`id_produit_declinaison`,`id_produit`,`id_declinaison`,`id_commande`),
  ADD KEY `id_commande` (`id_commande`),
  ADD KEY `id_produit` (`id_produit`),
  ADD KEY `id_declinaison` (`id_declinaison`);

--
-- Index pour la table `employe`
--
ALTER TABLE `employe`
  ADD PRIMARY KEY (`id_employe`),
  ADD KEY `id_role` (`id_role`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id_produit`),
  ADD KEY `id_categorie` (`id_categorie`),
  ADD KEY `id_TVA` (`id_TVA`);

--
-- Index pour la table `produit_declinaison`
--
ALTER TABLE `produit_declinaison`
  ADD PRIMARY KEY (`id_produit`,`id_declinaison`),
  ADD KEY `id_declinaison` (`id_declinaison`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`);

--
-- Index pour la table `rubrique`
--
ALTER TABLE `rubrique`
  ADD PRIMARY KEY (`id_rubrique`);

--
-- Index pour la table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id_status`);

--
-- Index pour la table `tva`
--
ALTER TABLE `tva`
  ADD PRIMARY KEY (`id_TVA`);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `article_ibfk_1` FOREIGN KEY (`id_employe`) REFERENCES `employe` (`id_employe`),
  ADD CONSTRAINT `article_ibfk_2` FOREIGN KEY (`id_rubrique`) REFERENCES `rubrique` (`id_rubrique`);

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`id_status`) REFERENCES `status` (`id_status`);

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `commentaire_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `client` (`id_client`),
  ADD CONSTRAINT `commentaire_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produit_declinaison` (`id_produit`),
  ADD CONSTRAINT `commentaire_ibfk_3` FOREIGN KEY (`id_declinaison`) REFERENCES `produit_declinaison` (`id_declinaison`);

--
-- Contraintes pour la table `declinaison_table`
--
ALTER TABLE `declinaison_table`
  ADD CONSTRAINT `declinaison_table_ibfk_1` FOREIGN KEY (`id_commande`) REFERENCES `commande` (`id_commande`),
  ADD CONSTRAINT `declinaison_table_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produit_declinaison` (`id_produit`),
  ADD CONSTRAINT `declinaison_table_ibfk_3` FOREIGN KEY (`id_declinaison`) REFERENCES `produit_declinaison` (`id_declinaison`);

--
-- Contraintes pour la table `employe`
--
ALTER TABLE `employe`
  ADD CONSTRAINT `employe_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`);

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`id_categorie`) REFERENCES `catégorie` (`id_categorie`),
  ADD CONSTRAINT `produit_ibfk_2` FOREIGN KEY (`id_TVA`) REFERENCES `tva` (`id_TVA`);

--
-- Contraintes pour la table `produit_declinaison`
--
ALTER TABLE `produit_declinaison`
  ADD CONSTRAINT `produit_declinaison_ibfk_1` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`),
  ADD CONSTRAINT `produit_declinaison_ibfk_2` FOREIGN KEY (`id_declinaison`) REFERENCES `declinaison` (`id_declinaison`),
  ADD CONSTRAINT `produit_declinaison_ibfk_3` FOREIGN KEY (`id_produit`) REFERENCES `produit` (`id_produit`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
