ALTER TABLE `#__sppagebuilder` MODIFY `asset_id` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `#__sppagebuilder` MODIFY `title` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `#__sppagebuilder` MODIFY `view_id` bigint(20) NOT NULL DEFAULT '0';
ALTER TABLE `#__sppagebuilder` MODIFY `og_title` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `#__sppagebuilder` MODIFY `og_image` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `#__sppagebuilder` MODIFY `og_description` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `#__sppagebuilder` MODIFY `language` char(7) NOT NULL DEFAULT '';

--
ALTER TABLE `#__spmedia` MODIFY `title` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `#__spmedia` MODIFY `path` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `#__spmedia` MODIFY `thumb` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `#__spmedia` MODIFY `alt` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `#__spmedia` MODIFY `caption` varchar(2048) NOT NULL DEFAULT '';
ALTER TABLE `#__spmedia` MODIFY `extension` varchar(100) NOT NULL DEFAULT '';

--
ALTER TABLE `#__sppagebuilder_languages` MODIFY `title` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `#__sppagebuilder_languages` MODIFY `version` varchar(255) NOT NULL DEFAULT '';

--
ALTER TABLE `#__sppagebuilder_sections` MODIFY `title` varchar(255) NOT NULL DEFAULT '';

--
ALTER TABLE `#__sppagebuilder_addons` MODIFY `title` varchar(255) NOT NULL DEFAULT '';

--
ALTER TABLE `#__sppagebuilder_integrations` MODIFY `component` varchar(255) NOT NULL DEFAULT '';