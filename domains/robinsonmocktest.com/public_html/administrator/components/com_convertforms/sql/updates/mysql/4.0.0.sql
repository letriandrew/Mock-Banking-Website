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