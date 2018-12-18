-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 18, 2018 at 02:35 PM
-- Server version: 5.5.60-0+deb8u1
-- PHP Version: 5.6.38-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `opendata`
--

-- --------------------------------------------------------

--
-- Table structure for table `apps`
--

CREATE TABLE IF NOT EXISTS `apps` (
`app_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `kategorie_id` int(11) NOT NULL,
  `beschreibung` text NOT NULL,
  `url_1` varchar(255) NOT NULL,
  `url_2` varchar(255) NOT NULL,
  `url_3` varchar(255) NOT NULL,
  `url_1_type` varchar(50) NOT NULL,
  `url_2_type` varchar(50) NOT NULL,
  `url_3_type` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `apps_kategorien`
--

CREATE TABLE IF NOT EXISTS `apps_kategorien` (
`apps_kategorien_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `beschreibung` text NOT NULL,
  `url` varchar(255) NOT NULL,
  `beschreibung_lang` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

CREATE TABLE IF NOT EXISTS `author` (
`author_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `datenquelle_mysql_schema`
--

CREATE TABLE IF NOT EXISTS `datenquelle_mysql_schema` (
`row_id` int(11) NOT NULL,
  `ressource_id` int(11) NOT NULL,
  `type` varchar(55) NOT NULL,
  `name` varchar(55) NOT NULL,
  `beschreibung` varchar(255) NOT NULL,
  `hide_on_preview` tinyint(1) NOT NULL,
  `position` int(11) NOT NULL,
  `import_name` varchar(255) NOT NULL,
  `groupable` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=268 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `datensaetze`
--

CREATE TABLE IF NOT EXISTS `datensaetze` (
`id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `name` varchar(200) NOT NULL,
  `beschreibung_kurz` varchar(255) NOT NULL,
  `beschreibung_lang` text NOT NULL,
  `released` int(11) DEFAULT '0',
  `author_id` int(11) NOT NULL,
  `time_released` int(11) NOT NULL,
  `time_changed` int(11) NOT NULL,
  `license_id` int(11) NOT NULL,
  `userinfo` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=161 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `datensaetze_gruppen`
--

CREATE TABLE IF NOT EXISTS `datensaetze_gruppen` (
`dg_id` int(11) NOT NULL,
  `datensatz_id` int(11) NOT NULL,
  `gruppen_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `datensaetze_tags`
--

CREATE TABLE IF NOT EXISTS `datensaetze_tags` (
`dg_id` int(11) NOT NULL,
  `datensatz_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `diagrams_grafiken`
--

CREATE TABLE IF NOT EXISTS `diagrams_grafiken` (
`grafik_id` int(11) NOT NULL,
  `diagram_id` int(11) NOT NULL,
  `text_oberhalb` text NOT NULL,
  `titel` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type_diagram` varchar(15) NOT NULL,
  `hoehe` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `betriebsart` varchar(10) NOT NULL,
  `opmode` varchar(50) NOT NULL,
  `gruppenspalte` varchar(255) NOT NULL,
  `sortmode` varchar(55) NOT NULL,
  `showInLegend` int(11) NOT NULL,
  `sum_spalte` varchar(55) NOT NULL,
  `einheit` varchar(10) NOT NULL,
  `text_y` varchar(255) NOT NULL,
  `text_unterhalb` text NOT NULL,
  `sql_where` varchar(255) NOT NULL,
  `dropdown_class` varchar(55) NOT NULL,
  `dropdown_enable` int(11) NOT NULL,
  `filter` varchar(255) NOT NULL,
  `filter_wert` varchar(255) NOT NULL,
  `y_spalte` varchar(255) NOT NULL,
  `reihenfolge` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gruppen`
--

CREATE TABLE IF NOT EXISTS `gruppen` (
`gruppe_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `license`
--

CREATE TABLE IF NOT EXISTS `license` (
`license_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `opendata_sde10`
--

CREATE TABLE IF NOT EXISTS `opendata_sde10` (
`fid` int(32) NOT NULL,
  `SNR` int(32) DEFAULT NULL,
  `IDENT` varchar(255) DEFAULT NULL,
  `SCHULE` varchar(255) DEFAULT NULL,
  `STICHTAG` varchar(255) DEFAULT NULL,
  `SJAHR` int(32) DEFAULT NULL,
  `SCHUELER` int(32) DEFAULT NULL,
  `ART` varchar(255) DEFAULT NULL,
  `SCHULFORM` varchar(255) DEFAULT NULL,
  `SCHULTRAEGER` varchar(255) DEFAULT NULL,
  `ORT` varchar(255) DEFAULT NULL,
  `AGS` varchar(255) DEFAULT NULL,
  `RS_ORT` varchar(255) DEFAULT NULL,
  `RS_KOMMUNE` varchar(255) DEFAULT NULL,
  `STANDORT` varchar(255) DEFAULT NULL,
  `KOMMUNE` varchar(255) DEFAULT NULL,
  `LANDKREIS` varchar(50) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=553 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ressource`
--

CREATE TABLE IF NOT EXISTS `ressource` (
`ressource_id` int(11) NOT NULL,
  `datensatz_id` int(11) NOT NULL,
  `pos_nr` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(3) NOT NULL,
  `beschreibung` text NOT NULL,
  `time_added` int(11) NOT NULL,
  `time_changed` int(11) NOT NULL,
  `dkan_res_id` varchar(55) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `datenquelle` varchar(5) NOT NULL,
  `mysql_table_name` varchar(255) NOT NULL,
  `preview_map` int(11) NOT NULL,
  `preview_map_shape` tinyint(1) NOT NULL,
  `preview_marker_default_group` varchar(255) NOT NULL,
  `preview_map_cluster` int(11) NOT NULL DEFAULT '0',
  `preview_map_cluster_only_cluster` tinyint(1) NOT NULL,
  `disable_preview_table` tinyint(1) NOT NULL,
  `url_to_ext` varchar(500) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `sde_tabelle` varchar(255) NOT NULL,
  `sde_fid_index_row` varchar(55) NOT NULL,
  `beschreibung_lang` text NOT NULL,
  `use_geocoder` int(11) NOT NULL,
  `query_where` text NOT NULL,
  `released` int(11) NOT NULL,
  `wfs_url` varchar(255) NOT NULL,
  `check_lk` tinyint(1) NOT NULL,
  `disable_coord_check` tinyint(1) NOT NULL,
  `disable_add_gemeinde` int(11) NOT NULL,
  `disable_telefon_check` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ressource_diagrams`
--

CREATE TABLE IF NOT EXISTS `ressource_diagrams` (
`diagram_id` int(11) NOT NULL,
  `ressource_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
`tag_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `visible` int(11) NOT NULL,
  `linked` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `apps`
--
ALTER TABLE `apps`
 ADD PRIMARY KEY (`app_id`);

--
-- Indexes for table `apps_kategorien`
--
ALTER TABLE `apps_kategorien`
 ADD PRIMARY KEY (`apps_kategorien_id`);

--
-- Indexes for table `author`
--
ALTER TABLE `author`
 ADD PRIMARY KEY (`author_id`);

--
-- Indexes for table `datenquelle_mysql_schema`
--
ALTER TABLE `datenquelle_mysql_schema`
 ADD PRIMARY KEY (`row_id`);

--
-- Indexes for table `datensaetze`
--
ALTER TABLE `datensaetze`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `datensaetze_gruppen`
--
ALTER TABLE `datensaetze_gruppen`
 ADD PRIMARY KEY (`dg_id`), ADD UNIQUE KEY `datensatz_id_2` (`datensatz_id`,`gruppen_id`), ADD UNIQUE KEY `datensatz_id_3` (`datensatz_id`,`gruppen_id`), ADD KEY `gruppen_id` (`gruppen_id`), ADD KEY `datensatz_id` (`datensatz_id`);

--
-- Indexes for table `datensaetze_tags`
--
ALTER TABLE `datensaetze_tags`
 ADD PRIMARY KEY (`dg_id`), ADD UNIQUE KEY `datensatz_id` (`datensatz_id`,`tag_id`);

--
-- Indexes for table `diagrams_grafiken`
--
ALTER TABLE `diagrams_grafiken`
 ADD PRIMARY KEY (`grafik_id`);

--
-- Indexes for table `gruppen`
--
ALTER TABLE `gruppen`
 ADD UNIQUE KEY `gruppe_id` (`gruppe_id`);

--
-- Indexes for table `license`
--
ALTER TABLE `license`
 ADD PRIMARY KEY (`license_id`);

--
-- Indexes for table `opendata_sde10`
--
ALTER TABLE `opendata_sde10`
 ADD PRIMARY KEY (`fid`);

--
-- Indexes for table `ressource`
--
ALTER TABLE `ressource`
 ADD PRIMARY KEY (`ressource_id`), ADD KEY `dkan_res_id` (`dkan_res_id`);

--
-- Indexes for table `ressource_diagrams`
--
ALTER TABLE `ressource_diagrams`
 ADD PRIMARY KEY (`diagram_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
 ADD UNIQUE KEY `tag_id` (`tag_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `apps`
--
ALTER TABLE `apps`
MODIFY `app_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `apps_kategorien`
--
ALTER TABLE `apps_kategorien`
MODIFY `apps_kategorien_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `author`
--
ALTER TABLE `author`
MODIFY `author_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `datenquelle_mysql_schema`
--
ALTER TABLE `datenquelle_mysql_schema`
MODIFY `row_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=268;
--
-- AUTO_INCREMENT for table `datensaetze`
--
ALTER TABLE `datensaetze`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=161;
--
-- AUTO_INCREMENT for table `datensaetze_gruppen`
--
ALTER TABLE `datensaetze_gruppen`
MODIFY `dg_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=78;
--
-- AUTO_INCREMENT for table `datensaetze_tags`
--
ALTER TABLE `datensaetze_tags`
MODIFY `dg_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `diagrams_grafiken`
--
ALTER TABLE `diagrams_grafiken`
MODIFY `grafik_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=44;
--
-- AUTO_INCREMENT for table `gruppen`
--
ALTER TABLE `gruppen`
MODIFY `gruppe_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `license`
--
ALTER TABLE `license`
MODIFY `license_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `opendata_sde10`
--
ALTER TABLE `opendata_sde10`
MODIFY `fid` int(32) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=553;
--
-- AUTO_INCREMENT for table `ressource`
--
ALTER TABLE `ressource`
MODIFY `ressource_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=125;
--
-- AUTO_INCREMENT for table `ressource_diagrams`
--
ALTER TABLE `ressource_diagrams`
MODIFY `diagram_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
