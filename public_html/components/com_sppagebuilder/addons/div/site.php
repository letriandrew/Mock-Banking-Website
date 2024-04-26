<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('Restricted access');

class SppagebuilderAddonDiv extends SppagebuilderAddons
{

	/**
	 * The render method of the DIV addon.
	 * The HTML of the DIV addon is managed from
	 * the `addon-parser.php` file. Nothing is needed to return from here.
	 *
	 * @return 	string
	 * @since 	4.0.0
	 */
	public function render()
	{
		return '';
	}

	/**
	 * The DIV addon's CSS stylings.
	 *
	 * @return 	string 	The CSS string.
	 * @since 	4.0.0
	 */
	public function css()
	{
		$settings = $this->addon->settings;
		$addon_id = '#sppb-addon-' . $this->addon->id;
		$cssHelper = new CSSHelper($addon_id);

		$css = '';

		$props = [
			'display' => 'display',
			'width' => 'width',
			'height' => 'height',
			'overflow' => 'overflow'
		];
		$units = [
			'display' => false,
			'overflow' => false,
		];

		if (isset($settings->reverse_direction) && $settings->reverse_direction) {
			if (is_object($settings->reverse_direction)) {
				if (is_object($settings->flex_direction)) {
					foreach ($settings->reverse_direction as $key => $value) {
						if (!empty($value) && !empty($settings->flex_direction->$key)) {
							$settings->flex_direction->$key = $settings->flex_direction->$key . '-reverse';
						}
					}
				} else {
					foreach ($settings->reverse_direction as $key => $value) {
						if (!empty($value) && !empty($settings->flex_direction)) {
							$settings->flex_direction->$key = $settings->flex_direction . '-reverse';
						}
					}
				}
			} else if (!empty($settings->flex_direction)) {
				$settings->flex_direction = $settings->flex_direction . '-reverse';
			}
		}

		if (isset($settings->display) && \in_array($settings->display, ['flex', 'inline-flex'])) {
			$props = array_merge($props, [
				'flex_direction' => 'flex-direction',
				'justify_content' => 'justify-content',
				'align_items' => 'align-items',
				'flex_gap' => 'gap',
				'flex_wrap' => 'flex-wrap'
			]);

			$units = array_merge($units, [
				'flex_direction' => false,
				'justify_content' => false,
				'align_items' => false,
				'flex_gap' => false,
				'flex_wrap' => false,
			]);
		}

		$divStyle = $cssHelper->generateStyle(':self', $settings, $props, $units);

		$css .= $divStyle;

		return $css;
	}
}
