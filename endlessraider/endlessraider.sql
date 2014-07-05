-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Ven 04 Juillet 2014 à 17:58
-- Version du serveur :  5.6.15-log
-- Version de PHP :  5.4.24

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `endlessraider`
--

-- --------------------------------------------------------

--
-- Structure de la table `er_classe`
--

DROP TABLE IF EXISTS `er_classe`;
CREATE TABLE IF NOT EXISTS `er_classe` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(100) CHARACTER SET ascii NOT NULL,
  `idJeu` int(10) NOT NULL,
  `iconPath` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IND_IDJEU` (`idJeu`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Vider la table avant d'insérer `er_classe`
--

TRUNCATE TABLE `er_classe`;
--
-- Contenu de la table `er_classe`
--

INSERT INTO `er_classe` (`id`, `libelle`, `idJeu`, `iconPath`) VALUES
(3, 'Envouteur', 3, 'front/images/upload/9153c370d64128cb41e62fa67ada0720.png'),
(4, 'Barbare', 4, 'front/images/upload/f69332cf1aa76a61fe34f607e47457d6.png');

-- --------------------------------------------------------

--
-- Structure de la table `er_evenement`
--

DROP TABLE IF EXISTS `er_evenement`;
CREATE TABLE IF NOT EXISTS `er_evenement` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) CHARACTER SET ascii NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `imagePath` varchar(255) CHARACTER SET ascii DEFAULT NULL,
  `date` datetime NOT NULL,
  `idJeu` int(10) NOT NULL,
  `nbrParticipantsMax` int(10) DEFAULT NULL,
  `levelMin` int(10) DEFAULT NULL,
  `levelMax` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IND_IDJEU` (`idJeu`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Vider la table avant d'insérer `er_evenement`
--

TRUNCATE TABLE `er_evenement`;
--
-- Contenu de la table `er_evenement`
--

INSERT INTO `er_evenement` (`id`, `nom`, `description`, `imagePath`, `date`, `idJeu`, `nbrParticipantsMax`, `levelMin`, `levelMax`) VALUES
(5, 'GW2Event', '', 'front/images/upload/def6e4648be9fa63c2807dda6f107167.png', '2014-06-18 21:00:00', 3, NULL, 1, 80),
(7, 'TestEvent_GW2', '', NULL, '2014-06-19 00:00:00', 3, 2, NULL, NULL),
(8, 'Gw2 sans role', '', NULL, '2014-06-26 00:00:00', 3, NULL, NULL, NULL),
(9, 'Minecraft EVT', '', NULL, '2014-06-26 00:00:00', 2, NULL, NULL, NULL),
(10, 'TestEvent_Minecraft', '', NULL, '2014-06-28 00:00:00', 2, NULL, NULL, NULL),
(11, 'TestEvent_GW2_today', '', NULL, '2014-06-26 09:00:00', 3, NULL, NULL, NULL),
(12, 'TestEvent_GW2_ceSoir', '', NULL, '2014-06-26 20:00:00', 3, NULL, NULL, NULL),
(13, 'event Diablo 3', '', NULL, '2014-07-17 00:00:00', 4, NULL, NULL, NULL),
(14, 'event Diablo 3', '', NULL, '2014-07-23 00:00:00', 4, NULL, NULL, NULL),
(15, 'Event L4D', '', NULL, '2014-06-29 00:00:00', 5, NULL, NULL, NULL),
(16, 'Event L4D', '', NULL, '2014-07-16 16:00:00', 5, NULL, NULL, NULL),
(17, 'testPerso', '', NULL, '2014-07-16 00:00:00', 2, 0, NULL, NULL),
(18, 'TestEvent_GW2KO', '', NULL, '2014-07-29 00:00:00', 2, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `er_jeu`
--

DROP TABLE IF EXISTS `er_jeu`;
CREATE TABLE IF NOT EXISTS `er_jeu` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) CHARACTER SET ascii NOT NULL,
  `iconPath` varchar(255) CHARACTER SET ascii DEFAULT NULL,
  `idTypeJeu` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`),
  KEY `idTypeJeu` (`idTypeJeu`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Vider la table avant d'insérer `er_jeu`
--

TRUNCATE TABLE `er_jeu`;
--
-- Contenu de la table `er_jeu`
--

INSERT INTO `er_jeu` (`id`, `nom`, `iconPath`, `idTypeJeu`) VALUES
(2, 'Minecraft', 'front/images/upload/8ec365ea838519c4e31da53e8b54ef56.png', 3),
(3, 'GW2', 'front/images/upload/5258a079817a50e58e928ef0a80bf9d6.png', 1),
(4, 'Diablo 3', 'front/images/upload/28f7e844b5e2e505c977298fa9daf017.jpg', 4),
(5, 'Left 4 dead', 'front/images/upload/ccb140501f1c5b73109e4e1d515b7d22.png', 2);

-- --------------------------------------------------------

--
-- Structure de la table `er_participant`
--

DROP TABLE IF EXISTS `er_participant`;
CREATE TABLE IF NOT EXISTS `er_participant` (
  `idPersonnage` int(10) NOT NULL,
  `idEvenement` int(10) NOT NULL,
  `idUser` int(10) NOT NULL,
  `idRole` int(10) DEFAULT NULL,
  `statut` varchar(1) CHARACTER SET ascii NOT NULL,
  `dateInscription` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `commentaire` text CHARACTER SET utf8,
  PRIMARY KEY (`idEvenement`,`idUser`),
  KEY `FK_Participant_idUser` (`idUser`),
  KEY `IND_IdRole` (`idRole`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Vider la table avant d'insérer `er_participant`
--

TRUNCATE TABLE `er_participant`;
--
-- Contenu de la table `er_participant`
--

INSERT INTO `er_participant` (`idPersonnage`, `idEvenement`, `idUser`, `idRole`, `statut`, `dateInscription`, `commentaire`) VALUES
(4, 5, 2, 1, 'D', '2014-06-17 17:23:25', NULL),
(4, 7, 2, 5, 'D', '2014-06-17 12:41:10', NULL),
(5, 7, 3, 4, 'D', '2014-06-17 12:33:05', NULL),
(6, 7, 4, 4, 'I', '2014-06-17 12:32:54', NULL),
(7, 9, 1, NULL, 'D', '2014-06-25 17:44:57', NULL),
(7, 10, 1, NULL, 'I', '2014-06-27 14:34:12', 'piscine'),
(3, 12, 1, NULL, 'D', '2014-06-26 10:44:49', NULL),
(11, 13, 1, NULL, 'D', '2014-07-01 16:28:44', 'test'),
(11, 14, 1, NULL, 'D', '2014-07-01 13:08:58', 'tezetMODIF2''345'),
(12, 15, 1, NULL, 'D', '2014-06-27 14:33:46', 'dede'),
(12, 16, 1, NULL, 'D', '2014-07-01 15:45:43', 'tets2'),
(13, 18, 4, NULL, 'I', '2014-07-03 13:42:22', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `er_personnage`
--

DROP TABLE IF EXISTS `er_personnage`;
CREATE TABLE IF NOT EXISTS `er_personnage` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) CHARACTER SET ascii NOT NULL,
  `level` int(10) DEFAULT NULL,
  `genre` varchar(1) CHARACTER SET ascii DEFAULT NULL,
  `idRace` int(10) DEFAULT NULL,
  `idClasse` int(10) DEFAULT NULL,
  `idJeu` int(10) NOT NULL,
  `idUser` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNI_nom_jeu_user` (`nom`,`idJeu`,`idUser`),
  KEY `IND_IdUser` (`idUser`),
  KEY `IND_IdJeu` (`idJeu`),
  KEY `IND_idClasse` (`idClasse`),
  KEY `IND_idRace` (`idRace`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Vider la table avant d'insérer `er_personnage`
--

TRUNCATE TABLE `er_personnage`;
--
-- Contenu de la table `er_personnage`
--

INSERT INTO `er_personnage` (`id`, `nom`, `level`, `genre`, `idRace`, `idClasse`, `idJeu`, `idUser`) VALUES
(3, 'Thorkh2', 23, 'M', 3, 3, 3, 1),
(4, 'testPerso', 3, 'M', NULL, NULL, 3, 2),
(5, 'Liath', NULL, 'M', NULL, NULL, 3, 3),
(6, 'Bryan', NULL, 'M', NULL, NULL, 3, 4),
(7, 'Thorkh', NULL, 'M', NULL, NULL, 2, 1),
(8, 'testPerso23', NULL, 'M', NULL, NULL, 3, 2),
(9, 'Sieg', NULL, 'M', NULL, NULL, 2, 2),
(10, 'TEst', NULL, 'M', NULL, NULL, 2, 1),
(11, 'testPerso', NULL, 'M', NULL, 4, 4, 1),
(12, 'testPerso', NULL, 'M', NULL, NULL, 5, 1),
(13, 'testPerso', NULL, 'M', NULL, NULL, 2, 4);

-- --------------------------------------------------------

--
-- Structure de la table `er_race`
--

DROP TABLE IF EXISTS `er_race`;
CREATE TABLE IF NOT EXISTS `er_race` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(100) CHARACTER SET utf8 NOT NULL,
  `idJeu` int(10) NOT NULL,
  `iconPath` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IND_IDJEU` (`idJeu`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Vider la table avant d'insérer `er_race`
--

TRUNCATE TABLE `er_race`;
--
-- Contenu de la table `er_race`
--

INSERT INTO `er_race` (`id`, `libelle`, `idJeu`, `iconPath`) VALUES
(2, 'Humain', 3, NULL),
(3, 'Norn', 3, 'front/images/upload/4be1a4629a23d43f8cf4410eb6ba4433.png');

-- --------------------------------------------------------

--
-- Structure de la table `er_role`
--

DROP TABLE IF EXISTS `er_role`;
CREATE TABLE IF NOT EXISTS `er_role` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) CHARACTER SET ascii NOT NULL,
  `idEvenement` int(10) NOT NULL,
  `nbrVoulu` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IND_IdEvent` (`idEvenement`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Vider la table avant d'insérer `er_role`
--

TRUNCATE TABLE `er_role`;
--
-- Contenu de la table `er_role`
--

INSERT INTO `er_role` (`id`, `libelle`, `idEvenement`, `nbrVoulu`) VALUES
(1, 'Tank', 5, NULL),
(4, 'Support', 7, 1),
(5, 'Tank', 7, 3);

-- --------------------------------------------------------

--
-- Structure de la table `er_templateevenement`
--

DROP TABLE IF EXISTS `er_templateevenement`;
CREATE TABLE IF NOT EXISTS `er_templateevenement` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) CHARACTER SET ascii NOT NULL,
  `description` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `imagePath` varchar(255) CHARACTER SET ascii DEFAULT NULL,
  `idJeu` int(10) NOT NULL,
  `nbrParticipantsMax` int(10) DEFAULT NULL,
  `levelMin` int(10) DEFAULT NULL,
  `levelMax` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IND_IDJEU` (`idJeu`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Vider la table avant d'insérer `er_templateevenement`
--

TRUNCATE TABLE `er_templateevenement`;
--
-- Contenu de la table `er_templateevenement`
--

INSERT INTO `er_templateevenement` (`id`, `nom`, `description`, `imagePath`, `idJeu`, `nbrParticipantsMax`, `levelMin`, `levelMax`) VALUES
(1, 'testTemplate', '', NULL, 3, NULL, NULL, NULL),
(2, 'TestEvent_GW2 Tmp', '', NULL, 3, NULL, 5, 80),
(9, 'Minecraft EVT', '', NULL, 2, NULL, NULL, NULL),
(10, 'd3', '<p>Template de test<br/></p>', NULL, 4, NULL, NULL, NULL),
(13, 'event Diablo 3', '', NULL, 4, NULL, NULL, NULL),
(15, 'Event L4D', '', NULL, 5, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `er_templaterole`
--

DROP TABLE IF EXISTS `er_templaterole`;
CREATE TABLE IF NOT EXISTS `er_templaterole` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) CHARACTER SET ascii NOT NULL,
  `idTemplate` int(10) NOT NULL,
  `nbrVoulu` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IND_IdTemplate` (`idTemplate`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Vider la table avant d'insérer `er_templaterole`
--

TRUNCATE TABLE `er_templaterole`;
--
-- Contenu de la table `er_templaterole`
--

INSERT INTO `er_templaterole` (`id`, `libelle`, `idTemplate`, `nbrVoulu`) VALUES
(1, 'dedde', 2, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `er_typejeu`
--

DROP TABLE IF EXISTS `er_typejeu`;
CREATE TABLE IF NOT EXISTS `er_typejeu` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL,
  `libelle` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Vider la table avant d'insérer `er_typejeu`
--

TRUNCATE TABLE `er_typejeu`;
--
-- Contenu de la table `er_typejeu`
--

INSERT INTO `er_typejeu` (`id`, `code`, `libelle`) VALUES
(1, 'MMORPG', 'MMORPG (Massively Multiplayer Online Role Playing Game)'),
(2, 'FPS', 'FPS (First Person Shooter)'),
(3, 'OTHER', 'Autre'),
(4, 'HNS', 'Hack''n Slash');

-- --------------------------------------------------------

--
-- Structure de la table `er_user`
--

DROP TABLE IF EXISTS `er_user`;
CREATE TABLE IF NOT EXISTS `er_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) CHARACTER SET ascii NOT NULL,
  `password` varchar(50) CHARACTER SET ascii NOT NULL,
  `droit` varchar(50) CHARACTER SET ascii NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Vider la table avant d'insérer `er_user`
--

TRUNCATE TABLE `er_user`;
--
-- Contenu de la table `er_user`
--

INSERT INTO `er_user` (`id`, `login`, `password`, `droit`) VALUES
(1, 'Thorkh', 'test', 'admin'),
(2, 'Sieg', 'test', 'officier'),
(3, 'Liath', '', 'officier'),
(4, 'Bryan', '', 'membre');

-- --------------------------------------------------------

--
-- Structure de la table `er_usergestionjeu`
--

DROP TABLE IF EXISTS `er_usergestionjeu`;
CREATE TABLE IF NOT EXISTS `er_usergestionjeu` (
  `idUser` int(10) NOT NULL,
  `idJeu` int(10) NOT NULL,
  PRIMARY KEY (`idUser`,`idJeu`),
  KEY `FK_idJeu_GestionJeu` (`idJeu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Vider la table avant d'insérer `er_usergestionjeu`
--

TRUNCATE TABLE `er_usergestionjeu`;
--
-- Contenu de la table `er_usergestionjeu`
--

INSERT INTO `er_usergestionjeu` (`idUser`, `idJeu`) VALUES
(1, 2),
(3, 2),
(1, 3),
(2, 3),
(1, 4),
(1, 5);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `er_classe`
--
ALTER TABLE `er_classe`
  ADD CONSTRAINT `FK_Classe_idJeu` FOREIGN KEY (`idJeu`) REFERENCES `er_jeu` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `er_evenement`
--
ALTER TABLE `er_evenement`
  ADD CONSTRAINT `FK_Jeu_id` FOREIGN KEY (`idJeu`) REFERENCES `er_jeu` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `er_jeu`
--
ALTER TABLE `er_jeu`
  ADD CONSTRAINT `FK_jeu_idTypeJeu` FOREIGN KEY (`idTypeJeu`) REFERENCES `er_typejeu` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `er_participant`
--
ALTER TABLE `er_participant`
  ADD CONSTRAINT `FK_Participant_idEvent` FOREIGN KEY (`idEvenement`) REFERENCES `er_evenement` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_Participant_idRole` FOREIGN KEY (`idRole`) REFERENCES `er_role` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_Participant_idUser` FOREIGN KEY (`idUser`) REFERENCES `er_user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `er_personnage`
--
ALTER TABLE `er_personnage`
  ADD CONSTRAINT `FK_Perso_idClasse` FOREIGN KEY (`idClasse`) REFERENCES `er_classe` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_Perso_idJeu` FOREIGN KEY (`idJeu`) REFERENCES `er_jeu` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_Perso_idRace` FOREIGN KEY (`idRace`) REFERENCES `er_race` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_Perso_idUser` FOREIGN KEY (`idUser`) REFERENCES `er_user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `er_race`
--
ALTER TABLE `er_race`
  ADD CONSTRAINT `FK_Race_idJeu` FOREIGN KEY (`idJeu`) REFERENCES `er_jeu` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `er_role`
--
ALTER TABLE `er_role`
  ADD CONSTRAINT `FK_Role_IdEvent` FOREIGN KEY (`idEvenement`) REFERENCES `er_evenement` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `er_templateevenement`
--
ALTER TABLE `er_templateevenement`
  ADD CONSTRAINT `FK_template_Jeu_id` FOREIGN KEY (`idJeu`) REFERENCES `er_jeu` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `er_templaterole`
--
ALTER TABLE `er_templaterole`
  ADD CONSTRAINT `FK_TemplateRole_IdTemplate` FOREIGN KEY (`idTemplate`) REFERENCES `er_templateevenement` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `er_usergestionjeu`
--
ALTER TABLE `er_usergestionjeu`
  ADD CONSTRAINT `FK_idJeu_GestionJeu` FOREIGN KEY (`idJeu`) REFERENCES `er_jeu` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_idUser_GestionJeu` FOREIGN KEY (`idUser`) REFERENCES `er_user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
