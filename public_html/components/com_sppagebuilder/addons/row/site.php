<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct access
defined ('_JEXEC') or die ('Restricted access');

class SppagebuilderAddonRow extends SppagebuilderAddons {

	public function render() {
		$settings = $this->addon->settings;
		$output = '';

		return $output;

	}

	public function css() {
		$settings = $this->addon->settings;
		$css = '';

		return $css;
	}

	public static function getTemplate()
	{
		$output = '';
		return $output;
	}
}