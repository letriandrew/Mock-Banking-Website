<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('Restricted access');

class SppagebuilderAddonText_block extends SppagebuilderAddons
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
		$title = (isset($settings->title) && $settings->title) ? $settings->title : '';
		$heading_selector = (isset($settings->heading_selector) && $settings->heading_selector) ? $settings->heading_selector : 'h3';

		// Options
		$text = (isset($settings->text) && $settings->text) ? $settings->text : '';
		$dropcap = (isset($settings->dropcap) && $settings->dropcap) ? $settings->dropcap : 0;

		$dropcapCls = '';

		if ($dropcap)
		{
			$dropcapCls = ' sppb-dropcap';
		}

		// Output
		$output  = '<div class="sppb-addon sppb-addon-text-block' . $dropcapCls . ' ' . $class . '" >';
		$output .= ($title) ? '<' . $heading_selector . ' class="sppb-addon-title">' . $title . '</' . $heading_selector . '>' : '';
		$output .= '<div class="sppb-addon-content">';
		$output .= $text;
		$output .= '</div>';
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
		$dropCap = !empty($settings->dropcap);

		$settings->alignment = CSSHelper::parseAlignment($settings, 'alignment');

		$css = '';

		$dropcapStyle = $cssHelper->generateStyle('.sppb-dropcap .sppb-addon-content:first-letter, .sppb-dropcap .sppb-addon-content p:first-letter', $settings, ['dropcap_color' => 'color', 'dropcap_font_size' => ['font-size', 'line-height']], ['dropcap_color' => false]);
		$textFontStyle = $cssHelper->typography('.sppb-addon-text-block .sppb-addon-content', $settings, 'text_typography', [
			'font'        => 'text_font_family',
			'size'        => 'text_fontsize',
			'line_height' => 'text_lineheight',
			'weight'      => 'text_fontweight'
		]);

		if ($dropCap)
		{
			$css .= $dropcapStyle;
		}
		$css .= $cssHelper->generateStyle('.sppb-addon-text-block', $settings, ['alignment'  => 'text-align'], false);
		$css .= $textFontStyle;

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
			var dropcap = "";

			if(data.dropcap){
				dropcap = "sppb-dropcap";
			}

			if(!data.heading_selector){
				data.heading_selector = "h3";
			}
		#>
		<style type="text/css">';
		// Text
		$textFallbacks = [
			'font'        => 'data.text_font_family',
			'size'        => 'data.text_fontsize',
			'line_height' => 'data.text_lineheight',
			'weight'      => 'data.text_fontweight',
		];
		// Title
		$titleFallbacks = [
			'font'           => 'data.font_family',
			'size'           => 'data.title_fontsize',
			'line_height'    => 'data.title_lineheight',
			'letter_spacing' => 'data.title_letterspace',
			'uppercase'      => 'data.title_font_style?.uppercase',
			'italic'         => 'data.title_font_style?.italic',
			'underline'      => 'data.title_font_style?.underline',
			'weight'         => 'data.title_font_style?.weight',
		];

		$output .= $lodash->alignment('text-align', '.sppb-addon-text-block', 'data.alignment');
		$output .= $lodash->color('color', '.sppb-addon-text-block .sppb-addon-title', 'data.title_text_color');
		$output .= $lodash->color('color', '.sppb-dropcap .sppb-addon-content:first-letter', 'data.dropcap_color');
		$output .= $lodash->unit('font-size', '.sppb-dropcap .sppb-addon-content:first-letter', 'data.dropcap_font_size', 'px');
		$output .= $lodash->unit('line-height', '.sppb-dropcap .sppb-addon-content:first-letter', 'data.dropcap_font_size', 'px');
		$output .= $lodash->typography('.sppb-addon-text-block .sppb-addon-content', 'data.text_typography', $textFallbacks);
		$output .= $lodash->typography('.sppb-addon-text-block .sppb-addon-title', 'data.title_typography', $titleFallbacks);
		$output .= '
		</style>
		<div class="sppb-addon sppb-addon-text-block {{ dropcap }} {{ data.class }}" >
			<#
			let heading_selector = data.heading_selector || "h3";
			if( !_.isEmpty( data.title ) ){ #><{{ heading_selector }} class="sppb-addon-title sp-inline-editable-element" data-id={{data.id}} data-fieldName="title" contenteditable="true">{{{ data.title }}}</{{ heading_selector }}><# } #>
			<div id="addon-text-{{data.id}}" class="sppb-addon-content sp-editable-content" data-id={{data.id}} data-fieldName="text">{{{ data.text }}}</div>
		</div>';
		return $output;
	}
}
