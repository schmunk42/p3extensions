-- phpMyAdmin SQL Dump
-- version 3.4.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 16. Sep 2011 um 18:37
-- Server Version: 5.1.57
-- PHP-Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `ab_content_meta`
--

CREATE TABLE IF NOT EXISTS `tablename_meta` (
  `id` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `type` varchar(64) DEFAULT NULL,
  `language` varchar(8) DEFAULT NULL,
  `treeParent_id` int(11) DEFAULT NULL COMMENT 'NULL = rootNode',
  `treePosition` int(11) DEFAULT NULL,
  `begin` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `keywords` text,
  `customData` text,
  `label` int(11) DEFAULT NULL,
  `owner` varchar(64) DEFAULT NULL,
  `checkAccessCreate` varchar(256) DEFAULT NULL,
  `checkAccessRead` varchar(256) DEFAULT NULL,
  `checkAccessUpdate` varchar(256) DEFAULT NULL,
  `checkAccessDelete` varchar(256) DEFAULT NULL,
  `createdAt` timestamp NULL DEFAULT NULL,
  `createdBy` varchar(64) DEFAULT NULL,
  `modifiedAt` timestamp NULL DEFAULT NULL,
  `modifiedBy` varchar(64) DEFAULT NULL,
  `guid` varchar(64) DEFAULT NULL,
  `ancestor_guid` varchar(64) DEFAULT NULL COMMENT 'item this record was copied from',
  `model` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_treeParent_id` (`treeParent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints der Tabelle `ab_content_meta`
--
ALTER TABLE `tablename_meta`
  ADD CONSTRAINT `fk_ab_content_meta_ab_content1` FOREIGN KEY (`id`) REFERENCES `tablename` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_treeParent_id` FOREIGN KEY (`treeParent_id`) REFERENCES `tablename_meta` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
