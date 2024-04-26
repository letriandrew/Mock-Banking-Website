SET SQL_MODE = "";

ALTER TABLE `#__sppagebuilder_addonlist` ADD `is_favorite` tinyint(1) NOT NULL DEFAULT '0' AFTER `status`;
