-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Sam 25 Avril 2020 à 20:56
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `OT_BDD`
--
CREATE DATABASE IF NOT EXISTS `OT_BDD` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `OT_BDD`;

-- --------------------------------------------------------

--
-- Structure de la table `OT`
--

CREATE TABLE IF NOT EXISTS `OT` (
  `ID` int(255) NOT NULL,
  `DESIGNATION` varchar(255) NOT NULL,
  `AUTORISATIONS_PARTICULIERES` boolean NOT NULL,
  `PLAN_PREV` varchar(255),
  `RISQUES_BIOLOGIQUES` boolean NOT NULL,
  `RISQUES_BIOLOGIQUES_DESIGNATION` varchar(255),
  `RISQUES_BIOLOGIQUES_ACCES` boolean,  
  `SIGNATURE_PROPRIO_DEBUT` varchar(255),
  `SIGNATURE_PROPRIO_DEBUT_DATE` Datetime,
  `SIGNATURE_INTERVENANT` varchar(255),
  `SIGNATURE_INTERVENANT_DATE` Datetime,
  `SIGNATURE_SQO` varchar(255),
  `SIGNATURE_SQO_DATE` Datetime,
  `SIGNATURE_PROPRIO_FIN` varchar(255),
  `SIGNATURE_PROPRIO_FIN_DATE` Datetime,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Risques_Precautions_EPI`
--


CREATE TABLE IF NOT EXISTS `Risques_Precautions_EPI` (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `NOM` varchar(255) NOT NULL,
  `Type` varchar(255) NOT NULL,
  `Cible` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


-- --------------------------------------------------------

--
-- Structure de la table `OT_RP_EPI`
--


CREATE TABLE IF NOT EXISTS `OT_RP_EPI` (
  `ID_OT` int(255),
  `ID_RP_EPI` int(11)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

