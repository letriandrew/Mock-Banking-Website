CREATE TABLE IF NOT EXISTS `#__convertforms` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `params` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__convertforms_campaigns` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `service` varchar(50) NOT NULL,
  `params` mediumtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__convertforms_conversions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `campaign_id` int(10) NOT NULL,
  `form_id` int(11) NOT NULL,
  `visitor_id` varchar(64) DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `params` mediumtext COLLATE utf8mb4_unicode_ci,
  `state` tinyint(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `state` (`state`),
  KEY `created` (`created`),
  KEY `form_id` (`form_id`),
  KEY `campaign_id` (`campaign_id`),
  KEY `user_id` (`user_id`),
  KEY `state_created` (`state`,`created`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `#__convertforms_submission_meta` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `submission_id` int(10) NOT NULL,
  `meta_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `params` mediumtext COLLATE utf8mb4_unicode_ci,
  `date_created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `date_created` (`date_created`),
  KEY `submission_id` (`submission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `#__convertforms_campaigns` 
  (`name`, `state`, `service`) VALUES
  ('Demo Campaign', 1, '0');

CREATE TABLE `#__convertforms_tasks` (
  `id` mediumint NOT NULL AUTO_INCREMENT,
  `form_id` mediumint NOT NULL,
  `title` varchar(100) NOT NULL,
  `state` tinyint NOT NULL DEFAULT '0',
  `action` varchar(100) NOT NULL,
  `app` varchar(100) NOT NULL,
  `trigger` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `connection_id` mediumint DEFAULT NULL,
  `options` text,
  `conditions` text,
  `silentfail` tinyint NOT NULL DEFAULT '0',
  `modified` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `created_by` mediumint NOT NULL,
  `ordering` smallint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `#__convertforms_tasks_history` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created` datetime NOT NULL,
  `created_by` mediumint NOT NULL,
  `task_id` mediumint NOT NULL,
  `payload` text COLLATE utf8mb4_unicode_ci,
  `success` tinyint(1) NOT NULL,
  `errors` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `execution_time` float DEFAULT NULL COMMENT 'The time the action took to finish in seconds',
  `ref_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'submission',
  `ref_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `#__convertforms_connections` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `app` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `params` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;