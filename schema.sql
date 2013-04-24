SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `games`;
CREATE TABLE IF NOT EXISTS `games` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) unsigned NOT NULL COMMENT '0:not started, 1:going, 2:finished',
  `variant` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `handsize` tinyint(1) unsigned NOT NULL,
  `playersnum` tinyint(1) unsigned NOT NULL,
  `currentplayer` tinyint(1) unsigned NOT NULL,
  `lives` tinyint(2) unsigned NOT NULL,
  `hints` tinyint(2) unsigned NOT NULL,
  `deck` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `builtpiles` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `discard` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `game_player`;
CREATE TABLE IF NOT EXISTS `game_player` (
  `gameid` int(11) unsigned NOT NULL,
  `playerid` int(11) unsigned NOT NULL,
  `order` tinyint(1) unsigned NOT NULL,
  `hand` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `info` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  UNIQUE KEY `game_player` (`gameid`,`playerid`),
  KEY `gameid` (`gameid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `alias` (`name`),
  KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `game_log`;
CREATE TABLE IF NOT EXISTS `game_log` (
  `id` int(12) unsigned NOT NULL AUTO_INCREMENT,
  `gameid` int(11) unsigned NOT NULL,
  `playerid` int(11) DEFAULT NULL,
  `event` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `data` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `gameid` (`gameid`,`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;