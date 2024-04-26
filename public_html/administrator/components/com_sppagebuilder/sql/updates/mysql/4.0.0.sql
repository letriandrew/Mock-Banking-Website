-- v4.0.0
CREATE TABLE IF NOT EXISTS `#__sppagebuilder_assets` (
	`id` bigint NOT NULL AUTO_INCREMENT,
	`type` varchar(100) NOT NULL DEFAULT '',
	`name` varchar(100) NOT NULL DEFAULT '',
	`title` varchar(255) NOT NULL DEFAULT '',
	`assets` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
	`css_path` text,
	`created` datetime NOT NULL,
	`created_by` int NOT NULL,
	`published` tinyint(1) NOT NULL DEFAULT '1',
	`access` int(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__sppagebuilder_addonlist` (
	`id` int(5) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(255) NOT NULL,
	`ordering` int(5) NOT NULL DEFAULT '0',
	`status` tinyint(1) NOT NULL DEFAULT '1',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;