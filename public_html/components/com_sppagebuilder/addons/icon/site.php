<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class SppagebuilderAddonIcon extends SppagebuilderAddons
{
	/**
	 * The addon frontend render method.
	 * The returned HTML string will render to the frontend page.
	 *
	 * @return  string  The HTML string.
	 * @since   1.0.0
	 */
	public function render()
	{

		$settings = $this->addon->settings;
		$class = (isset($settings->class) && $settings->class) ? $settings->class : '';
		$class .= (isset($settings->hover_effect) && $settings->hover_effect) ? ' sppb-icon-hover-effect-' . $settings->hover_effect : '';
		$name = (isset($settings->name) && $settings->name) ? $settings->name : '';
		// $link = (isset($settings->link) && $settings->link) ? $settings->link : '';
		// $target = (isset($settings->target) && $settings->target) ? 'rel="noopener noreferrer" target="' . $settings->target . '"' : '';

		list($link, $target) = AddonHelper::parseLink($settings, 'title_link', ['url' => 'link', 'new_tab' => 'target']);

		if ($name) {
			$output   = '<div class="sppb-icon ' . $class . '">';
			if (!empty($link)) {
				$output .= '<a ' . $target . ' href="' . $link . '">';
			}
			$output  .= '<span class="sppb-icon-inner">';

			$icon_arr = array_filter(explode(' ', $name));
			if (count($icon_arr) === 1) {
				$name = 'fa ' . $name;
			}

			$output  .= '<i class="' . $name . '" aria-hidden="true"></i>';
			$output  .= '</span>';
			if (!empty($link)) {
				$output .= '</a>';
			}
			$output  .= '</div>';
			return $output;
		}
	}

	/**
	 * Generate the CSS string for the frontend page.
	 *
	 * @return 	string 	The CSS string for the page.
	 * @since 	1.0.0
	 */
	public function css()
	{
		$addon_id = '#sppb-addon-' . $this->addon->id;
		$settings = $this->addon->settings;

		$cssHelper = new CSSHelper($addon_id);
		$css = '';
		$settings->alignment = $cssHelper->parseAlignment($settings, 'alignment');

		$iconStyle = $cssHelper->generateStyle(
			'.sppb-icon-inner',
			$settings,
			[
				'margin' => 'margin',
				'height' => 'height',
				'width'	=> 'width',
				'border_radius' => 'border-radius',
				'border_width' => 'border-width',
				'color' => 'color',
				'background' => 'background-color',
				'border_color' => 'border-style: solid;border-color:%s'
			],
			['margin' => false, 'color' => false, 'background' => false, 'border_color' => false],
			['margin_original' => 'spacing']
		);
		$fontStyle = $cssHelper->generateStyle('.sppb-icon-inner i', $settings, ['height' => 'line-height', 'size' => 'font-size', 'border_width' => 'margin-top:-%s']);
		$iconHoverStyle = $cssHelper->generateStyle(
			'.sppb-icon-inner:hover',
			$settings,
			[
				'hover_color' => 'color',
				'hover_background' => 'background-color',
				'hover_border_color' => 'border-color',
				'hover_border_width' => 'border-width',
				'hover_border_radius' => 'border-radius'
			],
			['hover_color' => false, 'hover_background' => false, 'hover_border_color' => false]
		);

		$css .= $iconStyle;
		$css .= $iconHoverStyle;
		$css .= $fontStyle;

		$css .= $cssHelper->generateStyle(':self', $settings, ['alignment' => 'text-align'], false);

		return $css;
	}

	/**
	 * Generate the lodash template string for the frontend editor.
	 *
	 * @return 	string 	The lodash template string.
	 * @since 	1.0.0
	 */
	public static function getTemplate()
	{
		$lodash = new Lodash('#sppb-addon-{{ data.id }}');
		$output = '<style type="text/css">';

		$output .= $lodash->alignment('text-align', '.sppb-icon', 'data.alignment');
		$output .= $lodash->color('background-color', '.sppb-icon-inner', 'data.background');
		$output .= $lodash->color('color', '.sppb-icon-inner', 'data.color');
		$output .= $lodash->spacing('margin', '.sppb-icon-inner', 'data.margin');
		$output .= $lodash->unit('height', '.sppb-icon-inner', 'data.height', 'px');
		$output .= $lodash->unit('width', '.sppb-icon-inner', 'data.width', 'px');

		$output .= '<# if (data.border_width) { #>';
		$output .= '#sppb-addon-{{ data.id }} .sppb-icon-inner {border-style: solid;}';
		$output .= $lodash->unit('border-color', '.sppb-icon-inner', 'data.border_color', '', false);
		$output .= $lodash->unit('border-width', '.sppb-icon-inner', 'data.border_width', 'px');
		$output .= '<# } #>';
		$output .= $lodash->unit('border-radius', '.sppb-icon-inner', 'data.border_radius', 'px');


		$output .= $lodash->unit('font-size', '.sppb-icon-inner i', 'data.size', 'px');
		$output .= $lodash->unit('line-height', '.sppb-icon-inner i', 'data.height', 'px');
		$output .= $lodash->unit('margin-top', '.sppb-icon-inner i', 'data.border_width', 'px', true, '-');

		// Hover
		$output .= '<# if (data.use_hover) { #>';
		$output .= $lodash->color('color', '.sppb-icon-inner:hover', 'data.hover_color');
		$output .= $lodash->color('background-color', '.sppb-icon-inner:hover', 'data.hover_background');
		$output .= $lodash->unit('border-color', '.sppb-icon-inner:hover', 'data.hover_border_color', '', false);
		$output .= $lodash->unit('border-width', '.sppb-icon-inner:hover', 'data.hover_border_width', 'px');
		$output .= $lodash->unit('border-radius', '.sppb-icon-inner:hover', 'data.hover_border_radius', 'px');
		$output .= '<# } #>';

		$output .= '
		</style>
		<# if (data.name) { #>
			<div class="sppb-icon {{ data.class }}">
				<# 
				const isMenu = _.isObject(data.title_link) && data.title_link.type === "menu" && data.title_link?.menu;
				const isPage = _.isObject(data.title_link) && data.title_link.type === "page" && data.title_link?.page;
				const isUrl = _.isObject(data.title_link) && data.title_link.type === "url" && data.title_link?.url;
				const isOldUrl = _.isString(data.link) && !_.isEmpty(data.link);

				if (isMenu || isPage || isUrl || isOldUrl) {
					const urlObj = data?.title_link || window.getSiteUrl(data.link, data.target || false);
					const {url, page, menu, type, new_tab, nofollow, noopener, noreferrer} = urlObj;
					const target = new_tab ? "_blank": "";
					
					let rel = "";
					rel += nofollow ? "nofollow": "";
					rel += noopener ? " noopener": "";
					rel += noreferrer ? " noreferrer": "";
					
					let newUrl = "";
					if(type === "url") newUrl = url;
					if(type === "menu") newUrl = menu;
					if(type === "page") newUrl = page ? `index.php?option=com_sppagebuilder&view=page&id=${page}` : "";
					#>
					<a href=\'{{ newUrl }}\' target=\'{{ target }}\' rel=\'{{ rel }}\'>
				<# }
				let icon_arr = (typeof data.name !== "undefined" && data.name) ? data.name.split(" ") : "";
                let icon_name = icon_arr.length === 1 ? "fa " + data.name : data.name;
				#>
				<span class="sppb-icon-inner">
					<i class="{{ icon_name }}"></i>
				</span>
				<# if (isMenu || isPage || isUrl || isOldUrl) { #>
					</a>
				<# } #>
			</div>
		<# } #>';

		return $output;
	}
}