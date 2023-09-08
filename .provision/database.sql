-- --------------------------------------------------------

--
-- Create Database: `sharebox`
--

USE sharebox;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
    `id` varchar(40) NOT NULL,
    `ip_address` varchar(45) NOT NULL,
    `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
    `data` blob NOT NULL,
    KEY `ci_sessions_timestamp` (`timestamp`)
);

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(40) COLLATE utf8_general_ci NOT NULL,
  `login` varchar(50) COLLATE utf8_general_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_autologin`
--

CREATE TABLE IF NOT EXISTS `user_autologin` (
  `key_id` char(32) COLLATE utf8_general_ci NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `user_agent` varchar(150) COLLATE utf8_general_ci NOT NULL,
  `last_ip` varchar(40) COLLATE utf8_general_ci NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`key_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL DEFAULT '1',
  `username` varchar(50) NOT NULL,
  `firstname` varchar(30) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '1',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `ban_reason` varchar(255) DEFAULT NULL,
  `new_password_key` varchar(50) DEFAULT NULL,
  `new_password_requested` datetime DEFAULT NULL,
  `new_email` varchar(100) DEFAULT NULL,
  `new_email_key` varchar(50) DEFAULT NULL,
  `digest` CHAR(32) DEFAULT NULL,
  `last_ip` varchar(40) NOT NULL,
  `last_login` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `search` (`username`,`firstname`,`lastname`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Creator',
  `owner_id` int(11) NOT NULL COMMENT 'Manager',
  `access_level` enum('pregled','izmena','kreiranje') DEFAULT 'pregled',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
INSERT INTO `groups` VALUES (1,'Svi korisnici',0,0,'kreiranje','0000-00-00 00:00:00',0);
UNLOCK TABLES;
-- ------------------------------------------------------

--
-- Table structure for table `users_groups`
--

DROP TABLE IF EXISTS `users_groups`;
CREATE TABLE `users_groups` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`),
  KEY `user_id` (`user_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_groups`
--

LOCK TABLES `users_groups` WRITE;
INSERT INTO `users_groups` VALUES (1,1);
UNLOCK TABLES;

-- ------------------------------------------------------

--
-- Table structure for table `merenja`
--

DROP TABLE IF EXISTS `merenja`;
CREATE TABLE `merenja` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `user_id` int(11) NOT NULL COMMENT 'Creator',
 `group_id` int(11) NOT NULL,
 `name` varchar(50) NOT NULL,
 `description` varchar(255) DEFAULT '',
 `created` datetime NOT NULL,
 `type` enum('staticka','dinamicka','nedovrsena') DEFAULT 'nedovrsena',
 `deleted` tinyint(4) NOT NULL DEFAULT '0',
 PRIMARY KEY (`id`),
 KEY `group` (`group_id`,`user_id`,`type`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Table structure for table `merenja_staticka`
--

DROP TABLE IF EXISTS `merenja_staticka`;
CREATE TABLE `merenja_staticka` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `merenje_id` int(10) DEFAULT NULL,
  `datetime` datetime NOT NULL,
  `ms` smallint(6) NOT NULL,
  `flow` float(8,3) DEFAULT NULL,
  `flow_relative` float(8,3) DEFAULT NULL,
  `pressure` float(8,3) DEFAULT NULL,
  `density` float(8,3) DEFAULT NULL,
  `temperature` float(6,3) DEFAULT NULL,
  `volume` float(8,3) DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `merenje_id` (`merenje_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Table structure for table `merenja_dinamicka`
--

DROP TABLE IF EXISTS `merenja_dinamicka`;
CREATE TABLE `merenja_dinamicka` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `merenje_id` int(10) unsigned NOT NULL,
  `time` float(10,3) DEFAULT NULL,
  `flow` float(8,3) DEFAULT NULL,
  `pressure` float(8,3) DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `merenje_id` (`merenje_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `log`
--
DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `user_id` int(10) unsigned NOT NULL,
 `ts` datetime NOT NULL,
 `action` int(10) unsigned NOT NULL,
 `comment` varchar(45) NOT NULL,
 PRIMARY KEY (`id`),
 KEY `user_id` (`user_id`),
 KEY `action` (`action`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `api_keys`
--
DROP TABLE IF EXISTS `api_keys`;
CREATE TABLE `api_keys` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `user_id` int(11) NOT NULL,
 `key` varchar(40) NOT NULL,
 `level` int(2) NOT NULL,
 `ignore_limits` tinyint(1) NOT NULL DEFAULT '0',
 `is_private_key` tinyint(1) NOT NULL DEFAULT '0',
 `ip_addresses` text,
 `date_created` int(11) NOT NULL,
 `deleted` tinyint(1) NOT NULL DEFAULT '0',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `api_log`;
CREATE TABLE `api_log` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `uri` VARCHAR(255) NOT NULL,
    `method` VARCHAR(6) NOT NULL,
    `params` TEXT DEFAULT NULL,
    `api_key` VARCHAR(40) NOT NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `time` INT(11) NOT NULL,
    `rtime` FLOAT DEFAULT NULL,
    `authorized` VARCHAR(1) NOT NULL,
    `response_code` smallint(3) DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
