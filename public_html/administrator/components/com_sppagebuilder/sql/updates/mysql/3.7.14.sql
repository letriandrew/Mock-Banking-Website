-- 3.7.14
SET SQL_MODE = "";

UPDATE `#__spmedia` SET `created_on` = '1970-01-01 00:00:00' WHERE `created_on` = '0000-00-00 00:00:00' OR `created_on` IS NULL;
UPDATE `#__spmedia` SET `modified_on` = '1970-01-01 00:00:00' WHERE `modified_on` = '0000-00-00 00:00:00' OR `modified_on` IS NULL;
UPDATE `#__sppagebuilder` SET `created_on` = '1970-01-01 00:00:00' WHERE `created_on` = '0000-00-00 00:00:00' OR `created_on` IS NULL;
UPDATE `#__sppagebuilder` SET `modified` = '1970-01-01 00:00:00' WHERE `modified` = '0000-00-00 00:00:00' OR `modified` IS NULL;
UPDATE `#__sppagebuilder` SET `checked_out_time` = NULL WHERE DATE_FORMAT(`checked_out_time`, '%Y-%m-%d') = '0000-00-00 00:00:00' OR `checked_out_time` IS NULL;
UPDATE `#__sppagebuilder_sections` SET `created` = '1970-01-01 00:00:00' WHERE `created` = '0000-00-00 00:00:00' OR `created` IS NULL;
UPDATE `#__sppagebuilder_addons` SET `created` = '1970-01-01 00:00:00' WHERE `created` = '0000-00-00 00:00:00' OR `created` IS NULL;

ALTER TABLE `#__spmedia` MODIFY `created_on` DATETIME NOT NULL;
ALTER TABLE `#__spmedia` MODIFY `modified_on` DATETIME NOT NULL;
ALTER TABLE `#__sppagebuilder` MODIFY `created_on` DATETIME NOT NULL;
ALTER TABLE `#__sppagebuilder` MODIFY `modified` DATETIME NOT NULL;
ALTER TABLE `#__sppagebuilder` MODIFY `checked_out_time` DATETIME DEFAULT NULL;
ALTER TABLE `#__sppagebuilder_sections` MODIFY `created` DATETIME NOT NULL;
ALTER TABLE `#__sppagebuilder_addons` MODIFY `created` DATETIME NOT NULL;