<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('Restricted access');

class SppagebuilderAddonAlert extends SppagebuilderAddons
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
		$addon_id = '#sppb-addon-' . $this->addon->id;
		$class = (isset($settings->class) && $settings->class) ? ' ' . $settings->class : '';
		$type = (isset($settings->alrt_type) && $settings->alrt_type) ? ' sppb-alert-' . $settings->alrt_type : '';
		$title = (isset($settings->title) && $settings->title) ? $settings->title : '';
		$heading_selector = (isset($settings->heading_selector) && $settings->heading_selector) ? $settings->heading_selector : '';
		$close = (isset($settings->close) && $settings->close) ? $settings->close : false;
		$text = (isset($settings->text) && $settings->text) ? $settings->text : '';

		if ($text)
		{
			$output  = '<div class="sppb-addon sppb-addon-alert' . $class . '">';
			$output .= (!empty($title)) ? '<' . $heading_selector . ' class="sppb-addon-title">' . $title . '</' . $heading_selector . '>' : '';
			$output .= '<div class="sppb-addon-content">';
			$output .= '<div class="sppb-alert' . $type . ' sppb-fade in">';
			$output .= ($close) ? '<button type="button" class="sppb-close" data-dismiss="sppb-alert" aria-label="alert dismiss" data-id="'.$addon_id.'"><span aria-hidden="true">&times;</span></button>' : '';
			$output .= $text;
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';

			return $output;
		}

		return;
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

		$css = '';

		$textFontStyle = $cssHelper->typography('.sppb-addon-content', $settings, 'content_typography', ['font' => 'text_font_family',]);

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
		<style type="text/css">';
		// Title
		$titleTypographyFallbacks = [
			'font'           => 'data.font_family',
			'size'           => 'data.title_fontsize',
			'line_height'    => 'data.title_lineheight',
			'letter_spacing' => 'data.title_letterspace',
			'uppercase'      => 'data.title_font_style?.uppercase',
			'italic'         => 'data.title_font_style?.italic',
			'underline'      => 'data.title_font_style?.underline',
			'weight'         => 'data.title_font_style?.weight'
		];

		$output .= $lodash->typography('.sppb-addon-title', 'data.title_typography', $titleTypographyFallbacks);

		$output .= $lodash->unit('margin-top', '.sppb-addon-title', 'data.title_margin_top');
		$output .= $lodash->unit('margin-bottom', '.sppb-addon-title', 'data.title_margin_bottom');
		$output .= $lodash->color('color', '.sppb-addon-title', 'data.title_text_color');

		// Content
		$contentTypographyFallbacks = [
			'font' => 'data.text_font_family'
		];

		$output .= $lodash->typography('.sppb-addon-content', 'data.content_typography', $contentTypographyFallbacks);
		$output .= '
		</style>
		<div class="sppb-addon sppb-addon-alert {{ data.class }}">
			<# if( !_.isEmpty( data.title ) ){ #><{{ data.heading_selector }} class="sppb-addon-title sp-inline-editable-element" data-id={{data.id}} data-fieldName="title" contenteditable="true">{{{ data.title }}}</{{ data.heading_selector }}><# } #>
			<div class="sppb-addon-content">
				<div class="sppb-alert sppb-alert-{{ data.alrt_type }} sppb-fade in">
					<# if( data.close ){ #>
						<button type="button" class="sppb-close"><span aria-hidden="true">&times;</span></button>
					<# } #>
					<div id="addon-text-{{data.id}}" class="sp-editable-content" data-id={{data.id}} data-fieldName="text">{{{ data.text }}}</div>
				</div>
			</div>
		</div>';

		return $output;
	}
}