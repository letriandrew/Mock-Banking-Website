<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

use Joomla\CMS\Uri\Uri;

//no direct access
defined('_JEXEC') or die('Restricted access');

class SppagebuilderAddonDivider extends SppagebuilderAddons
{

	public function render()
	{
		$settings = $this->addon->settings;
		$class = (isset($settings->class) && $settings->class) ? $settings->class : '';
		$divider_type = (isset($settings->divider_type) && $settings->divider_type) ? $settings->divider_type : '';
		//output start
		$output = '';
		$output .= '<div class="sppb-addon-divider-wrap divider-position">';
		$output .= '<div class="sppb-divider sppb-divider-' . $divider_type . ' ' . $class . '"></div>';
		$output .= '</div>';
		return $output;
	}

	public function css()
	{
		$addon_id = '#sppb-addon-' . $this->addon->id;
		$settings = $this->addon->settings;
		$cssHelper = new CSSHelper($addon_id);
		
		$divider_type		= (isset($settings->divider_type) && $settings->divider_type) ? $settings->divider_type : '';
		$divider_image 		= (isset($settings->divider_image) && $settings->divider_image) ? $settings->divider_image : '';
		$divider_image_src = isset($divider_image->src) ? $divider_image->src : $divider_image;
		$background_repeat 	= (isset($settings->background_repeat) && $settings->background_repeat) ? $settings->background_repeat : 'no-repeat';
		$divider_vertical 	= (isset($settings->divider_vertical) && $settings->divider_vertical) ? $settings->divider_vertical : '';

		$css = '';
		$innerStyle = '';

		$dividerProps = [
			'margin_top' => 'margin-top',
			'margin_bottom' => 'margin-bottom',
			'container_div_width' => 'width'
		];

		if ($divider_type === 'border')
		{
			if (!$divider_vertical)
			{
				$dividerProps = array_merge($dividerProps, [
					'border_width' => 'border-bottom-width',
					'border_style' => 'border-bottom-style',
					'border_color' => 'border-bottom-color',
					'border_radius' => 'border-radius',
				]);
			}
			else
			{
				// If we use border_width as key name then it will override the the previous value of border_width which is the border-left-width.
				$settings->divider_width = $settings->border_width;
				
				$dividerProps = array_merge($dividerProps, [
					'border_width' => 'border-left-width',
					'border_style' => 'border-left-style',
					'border_color' => 'border-left-color',
					'border_radius' => 'border-radius',
					'divider_height_vertical' => 'height', 
					'divider_width' => 'width'
				]);			
			}
		}
		else
		{
			$dividerProps['divider_height'] = 'height';

			if (strpos($divider_image_src, 'http://') !== false || strpos($divider_image_src, 'https://') !== false)
			{
				$innerStyle .= ($divider_image_src) ? 'background-image: url(' . $divider_image_src  . ');background-repeat:' . $background_repeat . ';background-position:50% 50%;' : '';
			}
			else
			{
				$innerStyle .= ($divider_image_src) ? 'background-image: url(' . Uri::base() . '/' . $divider_image_src  . ');background-repeat:' . $background_repeat . ';background-position:50% 50%;' : '';
			}
		}

		$units = ['border_style' => false, 'border_color' => false];

		// Alignment Style
		$settings->divider_position = CSSHelper::parseAlignment($settings, 'divider_position');
		$css .= $cssHelper->generateStyle('.divider-position', $settings, ['divider_position' => 'text-align'], false);

		$dividerStyle = $cssHelper->generateStyle('.sppb-divider', $settings, $dividerProps, $units);
		$css .= $dividerStyle;

		if ($innerStyle)
		{
			$css .= $addon_id . ' .sppb-divider {';
			$css .= $innerStyle;
			$css .= '}';
		}

		return $css;
	}

	public static function getTemplate()
	{
		$lodash = new Lodash('#sppb-addon-{{ data.id }}');
		$output = '<style type="text/css">';
		$output .= $lodash->unit('margin-top', '.sppb-divider', 'data.margin_top', 'px');
		$output .= $lodash->unit('margin-bottom', '.sppb-divider', 'data.margin_bottom', 'px');
		$output .= $lodash->unit('width', '.sppb-divider', 'data.container_div_width', 'px');
		$output .= $lodash->alignment('text-align', '.divider-position', 'data.divider_position');
		
		$output .= '<# if(data.divider_type == "border") { #>';
		$output .= $lodash->unit('border-radius', '.sppb-divider', 'data.border_radius', 'px');
		$output .= '<# if(!data.divider_vertical) { #>';
		$output .= $lodash->unit('border-bottom-width', '.sppb-divider', 'data.border_width', 'px');
		$output .= $lodash->unit('border-bottom-style', '.sppb-divider', 'data.border_style', '', false);
		$output .= $lodash->unit('border-bottom-color', '.sppb-divider', 'data.border_color', '', false);
		$output .= '<# } else { #>';
		$output .= $lodash->unit('height', '.sppb-divider', 'data.divider_height_vertical', 'px');
		$output .= $lodash->unit('width', '.sppb-divider', 'data.border_width', 'px');
		$output .= $lodash->unit('border-left-width', '.sppb-divider', 'data.border_width', 'px');
		$output .= $lodash->unit('border-left-style', '.sppb-divider', 'data.border_style', '', false);
		$output .= $lodash->unit('border-left-color', '.sppb-divider', 'data.border_color', '', false);
		$output .= '<# } #>';
		$output .= '<# } else { #>';
		$output .= '<#
				let media = {};
				let mediaSrc = "";

				if (typeof data.divider_image !== "undefined" && typeof data.divider_image.src !== "undefined") {
					media = data.divider_image;
				} else {
					media = {src: data.divider_image};
				}

				if(media?.src?.indexOf("http://") == -1 && media?.src?.indexOf("https://") == -1) {
					mediaSrc = pagebuilder_base + media.src;
				} else {
					mediaSrc = media.src;
				}
			#>';

		$output .= '.sppb-divider {background-image: url({{mediaSrc}});background-position: 50% 50%;}';
		$output .= $lodash->unit('background-repeat', '.sppb-divider', 'data.background_repeat', '', false);
		$output .= $lodash->unit('height', '.sppb-divider', 'data.divider_height', 'px');

		$output .= '<# } #>';
		$output .= '
		</style>
		
		<# let dividerPosition = (!_.isEmpty(data.divider_type) && data.divider_type) ? "divider-position" : "";#>
		<div class="sppb-addon-divider-wrap {{dividerPosition}}">
			<div class="sppb-divider sppb-divider-{{ data.divider_type }} {{ data.class }}"></div>
		</div>';

		return $output;
	}
}