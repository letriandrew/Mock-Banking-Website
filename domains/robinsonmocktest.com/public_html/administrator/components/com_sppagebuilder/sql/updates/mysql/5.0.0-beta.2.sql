SET SQL_MODE = "";

CREATE TABLE IF NOT EXISTS `#__sppagebuilder_colors` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `colors` TEXT,
  `created` DATETIME NOT NULL,
  `created_by` int NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__sppagebuilder_fonts` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `family_name` varchar(100) NOT NULL DEFAULT '',
  `data` TEXT,
  `type` enum('google', 'local') DEFAULT 'google',
  `created` DATETIME NOT NULL,
  `created_by` int NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE(`family_name`, `type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `#__sppagebuilder_sections` ADD `ordering` int(11) NOT NULL DEFAULT '0' AFTER `section`;
ALTER TABLE `#__sppagebuilder_addons` ADD `ordering` int(11) NOT NULL DEFAULT '0' AFTER `code`;