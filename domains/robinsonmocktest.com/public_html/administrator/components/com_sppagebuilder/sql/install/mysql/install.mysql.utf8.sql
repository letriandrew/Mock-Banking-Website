CREATE TABLE IF NOT EXISTS `#__sppagebuilder` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `text` mediumtext NOT NULL,
  `content` mediumtext,
  `extension` varchar(255) NOT NULL DEFAULT 'com_sppagebuilder',
  `extension_view` varchar(255) NOT NULL DEFAULT 'page',
  `view_id` bigint(20) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `published` tinyint(3) NOT NULL DEFAULT '1',
  `catid` int(10) NOT NULL DEFAULT '0',
  `access` int(10) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `created_on` datetime NOT NULL,
  `created_by` bigint(20) NOT NULL DEFAULT '0',
  `modified` datetime NOT NULL,
  `modified_by` bigint(20) NOT NULL DEFAULT '0',
  `checked_out` int(10) NOT NULL DEFAULT '0',
  `checked_out_time` datetime DEFAULT NULL,
  `attribs` varchar(5120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '[]',
  `og_title` varchar(255) NOT NULL DEFAULT '',
  `og_image` varchar(255) NOT NULL DEFAULT '',
  `og_description` varchar(255) NOT NULL DEFAULT '',
  `language` char(7) NOT NULL DEFAULT '',
  `hits` bigint(20) NOT NULL DEFAULT '0',
  `css` longtext NOT NULL,
  `version` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__sppagebuilder_integrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` mediumtext NOT NULL,
  `component` varchar(255) NOT NULL DEFAULT '',
  `plugin` mediumtext NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__spmedia` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `alt` varchar(255) NOT NULL DEFAULT '',
  `caption` varchar(2048) NOT NULL DEFAULT '',
  `description` mediumtext NOT NULL,
  `type` varchar(100) NOT NULL DEFAULT 'image',
  `media_attr` varchar(5120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '{}',
  `extension` varchar(100) NOT NULL DEFAULT '',
  `created_on` datetime NOT NULL,
  `created_by` bigint(20) NOT NULL DEFAULT '0',
  `modified_on` datetime NOT NULL,
  `modified_by` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__sppagebuilder_languages` (
  `id` INT(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` mediumtext NOT NULL,
  `lang_tag` varchar(255) NOT NULL DEFAULT '',
  `lang_key` varchar(100) DEFAULT NULL,
  `version` varchar(255) NOT NULL DEFAULT '',
  `state` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__sppagebuilder_sections` (
  `id` INT(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `section` mediumtext NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `created_by` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__sppagebuilder_addons` (
  `id` INT(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `code` mediumtext NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `created_by` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__sppagebuilder_addonlist` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `ordering` int(5) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `is_favorite` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__sppagebuilder_assets` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `type` varchar(100) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `assets` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `css_path` text,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `access` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__sppagebuilder_colors` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `colors` TEXT,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__sppagebuilder_fonts` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `family_name` varchar(100) NOT NULL DEFAULT '',
  `data` TEXT,
  `type` enum('google', 'local') DEFAULT 'google',
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE(`family_name`, `type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;