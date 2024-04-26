<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Layout\FileLayout;

class SppagebuilderAddonButton extends SppagebuilderAddons
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

		$class = (isset($settings->class) && $settings->class) ? ' ' . $settings->class : '';
		$class .= (isset($settings->type) && $settings->type) ? ' sppb-btn-' . $settings->type : '';
		$class .= (isset($settings->size) && $settings->size) ? ' sppb-btn-' . $settings->size : '';
		$class .= (isset($settings->block) && $settings->block) ? ' ' . $settings->block : '';
		$class .= (isset($settings->shape) && $settings->shape) ? ' sppb-btn-' . $settings->shape : ' sppb-btn-rounded';
		$class .= (isset($settings->appearance) && $settings->appearance) ? ' sppb-btn-' . $settings->appearance : '';

		// Need to create a function
		$attribs = '';

		list($link, $new_tab) = AddonHelper::parseLink($settings, 'url', ['url' => 'link', 'new_tab' => 'target']);

		$attribs .= ' id="btn-' . $this->addon->id . '"';
		$text = (isset($settings->text) && $settings->text) ? $settings->text : '';
		$icon = (isset($settings->icon) && $settings->icon) ? $settings->icon : '';
		$icon_position = (isset($settings->icon_position) && $settings->icon_position) ? $settings->icon_position : 'left';

		$icon_arr = array_filter(explode(' ', $icon));

		if (count($icon_arr) === 1) {
			$icon = 'fa ' . $icon;
		}

		if ($icon_position === 'left') {
			$text = ($icon) ? '<i class="' . $icon . '" aria-hidden="true"></i> ' . $text : $text;
		} else {
			$text = ($icon) ? $text . ' <i class="' . $icon . '" aria-hidden="true"></i>' : $text;
		}

		$hrefTag = !empty($link) ? 'href="' . $link .'"' : '';

		$output = '<div class="sppb-button-wrapper">';
		$output .= '<a '. $hrefTag  . ' ' . $new_tab . ' ' . $attribs .' class="sppb-btn ' . $class . '">' . $text . '</a>';
		$output .= '</div>';

		return $output;
	}

	/**
	 * Generate the CSS string for the frontend page.
	 *
	 * @return 	string 	The CSS string for the page.
	 * @since 	1.0.0
	 */
	public function css()
	{
		$settings = $this->addon->settings;

		$addon_id = '#sppb-addon-' . $this->addon->id;
		$cssHelper = new CSSHelper($addon_id);
		$layoutPath = JPATH_ROOT . '/components/com_sppagebuilder/layouts';
		$css = '';

		$buttonLayout = new FileLayout('addon.css.button', $layoutPath);


		$options = new stdClass;
		$options->button_type = (isset($settings->type) && $settings->type) ? $settings->type : '';
		$options->button_appearance = (isset($settings->appearance) && $settings->appearance) ? $settings->appearance : '';
		$options->button_color = (isset($settings->color) && $settings->color) ? $settings->color : '';
		$options->button_border_width = (isset($settings->button_border_width) && $settings->button_border_width) ? $settings->button_border_width : '';
		$options->button_color_hover = (isset($settings->color_hover) && $settings->color_hover) ? $settings->color_hover : '';
		$options->button_background_color = (isset($settings->background_color) && $settings->background_color) ? $settings->background_color : '';
		$options->button_background_color_hover = (isset($settings->background_color_hover) && $settings->background_color_hover) ? $settings->background_color_hover : '';
		$options->button_fontstyle = (isset($settings->fontstyle) && $settings->fontstyle) ? $settings->fontstyle : '';
		$options->button_font_style = (isset($settings->font_style) && $settings->font_style) ? $settings->font_style : '';
		$options->button_padding = (isset($settings->button_padding) && $settings->button_padding) ? $settings->button_padding : '';
		$options->button_padding_original = (isset($settings->button_padding_original) && $settings->button_padding_original) ? $settings->button_padding_original : '';
		// $options->fontsize = (isset($settings->fontsize) && $settings->fontsize) ? $settings->fontsize : '';
		$options->fontsize = isset($settings->fontsize_original) ? $settings->fontsize_original : ($settings->fontsize ?? null);
		$options->button_size = isset($settings->size) ? $settings->size : null;
		$options->font_family = isset($settings->font_family) ? $settings->font_family : null;
		$options->button_typography = isset($settings->typography) ? $settings->typography : null;

		// Button Type Link
		$options->link_button_color = (isset($settings->link_button_color) && $settings->link_button_color) ? $settings->link_button_color : '';
		$options->link_border_color = (isset($settings->link_border_color) && $settings->link_border_color) ? $settings->link_border_color : '';
		$options->link_button_border_width = (isset($settings->link_button_border_width) && $settings->link_button_border_width) ? $settings->link_button_border_width : '';
		$options->link_button_padding_bottom = (isset($settings->link_button_padding_bottom) && gettype($settings->link_button_padding_bottom) == 'string') ? $settings->link_button_padding_bottom : '';

		// Link Hover
		$options->link_button_hover_color = (isset($settings->link_button_hover_color) && $settings->link_button_hover_color) ? $settings->link_button_hover_color : '';
		$options->link_button_border_hover_color = (isset($settings->link_button_border_hover_color) && $settings->link_button_border_hover_color) ? $settings->link_button_border_hover_color : '';

		$options->button_letterspace = (isset($settings->letterspace) && $settings->letterspace) ? $settings->letterspace : '';
		$options->button_background_gradient = (isset($settings->background_gradient) && $settings->background_gradient) ? $settings->background_gradient : new stdClass();
		$options->button_background_gradient_hover = (isset($settings->background_gradient_hover) && $settings->background_gradient_hover) ? $settings->background_gradient_hover : new stdClass();

		$settings->alignment = CSSHelper::parseAlignment($settings, 'alignment');
		$alignmentStyle = $cssHelper->generateStyle('.sppb-button-wrapper', $settings, ['alignment' => 'text-align'], false);
		$iconStyle = $cssHelper->generateStyle('.sppb-btn i', $settings, ['icon_margin' => 'margin'], false, ['icon_margin' => 'spacing']);

		$css .= $buttonLayout->render(array('addon_id' => $addon_id, 'options' => $options, 'id' => 'btn-' . $this->addon->id));

		$css .= $iconStyle;
		$css .= $alignmentStyle;

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
		$output = '
		<#
			
			var classList = data.class;
			classList += " sppb-btn-" + data.type;
			if (!_.isEmpty(data.size))
			{
				classList += " sppb-btn-" + data?.size;
			}
			classList += " sppb-btn-" + data.shape;

			if (!_.isEmpty(data.appearance)) {
				classList += " sppb-btn-" + data.appearance;
			}
			
			if (data.block != undefined) {
				classList += " " + data.block;
			}
		#>';

		$output .= '<style type="text/css">';
		$typographyFallbacks = [
			'font'           => 'data.font_family',
			'size'           => 'data.fontsize',
			'letter_spacing' => 'data.letterspace',
			'uppercase'      => 'data.font_style?.uppercase',
			'italic'         => 'data.font_style?.italic',
			'underline'      => 'data.font_style?.underline',
			'weight'         => 'data.font_style?.weight'
		];
		$output .= $lodash->typography('#btn-{{ data.id }}', 'data.typography', $typographyFallbacks);

		// custom
		$output .= '<# if (data.type == "custom") { #>';
		$output .= $lodash->color('color', '#btn-{{ data.id }}', 'data.color');
		$output .= $lodash->color('color', '#btn-{{ data.id }}:hover', 'data.color_hover');
		$output .= $lodash->color('background-color', '#btn-{{ data.id }}:hover', 'data.background_color_hover');
		$output .= $lodash->spacing('padding', '#btn-{{ data.id }}.sppb-btn-custom', 'data.button_padding');
		$output .= '<# if (data.appearance == "outline") { #>';
		$output .= '#btn-{{ data.id }} {background-color: transparent;}';
		$output .= $lodash->unit('border-color', '#btn-{{ data.id }}', 'data.background_color', '', false);
		$output .= $lodash->unit('border-color', '#btn-{{ data.id }}:hover', 'data.background_color_hover', '', false);
		$output .= '<# } else if (data.appearance == "gradient") { #>';
		$output .= '#btn-{{ data.id }} {border: none;}';
		$output .= $lodash->color('background-color', '#btn-{{ data.id }}', 'data.background_gradient');
		$output .= $lodash->color('background-color', '#btn-{{ data.id }}:hover', 'data.background_gradient_hover');
		$output .= '<# } else { #>';
		$output .= $lodash->color('background-color', '#btn-{{ data.id }}', 'data.background_color');
		$output .= $lodash->color('background-color', '#btn-{{ data.id }}:hover', 'data.background_color_hover');
		$output .= '<# } #>';
		$output .= '<# } #>';

		// link
		$output .= '<# if (data.type == "link") { #>';
		$output .= '#btn-{{ data.id }} {padding: 0; border-width: 0; text-decoration: none; border-radius: 0;}';
		$output .= $lodash->color('color', '#btn-{{ data.id }}', 'data.link_button_color');
		$output .= $lodash->unit('border-color', '#btn-{{ data.id }}', 'data.link_border_color', '', false);
		$output .= $lodash->unit('border-bottom-width', '#btn-{{ data.id }}', 'data.link_button_border_width', 'px');
		$output .= $lodash->unit('padding-bottom', '#btn-{{ data.id }}', 'data.link_button_padding_bottom', 'px');
		$output .= $lodash->color('color', '#btn-{{ data.id }}:hover, #btn-{{ data.id }}:focus', 'data.link_button_hover_color');
		$output .= $lodash->unit('border-color', '#btn-{{ data.id }}:hover, #btn-{{ data.id }}:focus', 'data.link_button_border_hover_color', '', false);
		$output .= '<# } #>';

		$output .= $lodash->alignment('text-align', '', 'data.alignment');

		$output .= $lodash->spacing('margin', '#btn-{{ data.id }} i', 'data.icon_margin');
		$output .= '
		</style>

		<#
			let icon_arr = (typeof data.icon !== "undefined" && data.icon) ? data.icon.split(" ") : "";
			let icon_name = icon_arr.length === 1 ? "fa "+data.icon : data.icon;

			/*** link ***/
			const isUrlObject = _.isObject(data?.url) && ( !!data?.url?.url || !!data?.url?.page || !!data?.url?.menu);
			const isUrlString = _.isString(data?.url) && data?.url !== "";

			let href;
			let target;
			let rel;
			let relData="";

			if(isUrlObject || isUrlString ){
				const urlObj = data?.url?.url ? data?.url : window.getSiteUrl(data?.url, data?.target);			
				const {url, new_tab, nofollow, noopener, noreferrer, type} = urlObj;
				
				target = new_tab ? `target="_blank"` : "";

				relData += nofollow ? "nofollow" : "";
				relData += noopener ? " noopener" : "";
				relData += noreferrer ? " noreferrer" : "";

				rel = `rel="${relData}"`;
				
				const buttonUrl = (type === "url" && url) || ( type === "menu" && urlObj.menu) || ( (type === "page" && !!urlObj.page ) && "index.php/component/sppagebuilder/index.php?option=com_sppagebuilder&view=page&id=" + urlObj.page) || "";
				
				href = buttonUrl ? `href=${buttonUrl}` : "";
			}
		#>
		<a  {{href}} {{target}} {{rel}} id="btn-{{ data.id }}" class="sppb-btn {{ classList }}" data-id={{ data.id }}><# if(data.icon_position == "left" && !_.isEmpty(data.icon)) { #><i class="{{ icon_name }}"></i> <# } #><span class="sp-editable-content" data-id={{ data.id }} data-fieldName="text" data-placeholder="Add text...">{{{ data.text }}}</span><# if(data.icon_position == "right" && !_.isEmpty(data.icon)) { #> <i class="{{ icon_name }}"></i><# } #></a>';

		return $output;
	}
}