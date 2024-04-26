<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

class SppagebuilderAddonAccordion extends SppagebuilderAddons
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
		$style = (isset($settings->style) && $settings->style) ? $settings->style : 'panel-default';
		$title = (isset($settings->title) && $settings->title) ? $settings->title : '';
		$heading_selector = (isset($settings->heading_selector) && $settings->heading_selector) ? $settings->heading_selector : 'h3';
		$icon_position = (isset($settings->icon_position) && $settings->icon_position) ? $settings->icon_position : '';

		$output   = '';
		$output  = '<div class="sppb-addon sppb-addon-accordion ' . $class . '">';

		if ($title) {
			$output .= '<' . $heading_selector . ' class="sppb-addon-title">' . $title . '</' . $heading_selector . '>';
		}

		$output .= '<div class="sppb-addon-content">';
		$output	.= '<div class="sppb-panel-group">';

		if (isset($settings->sp_accordion_item) && is_array($settings->sp_accordion_item) && count($settings->sp_accordion_item)) {
			foreach ($settings->sp_accordion_item as $key => $item) {
				$item_title = (isset($item->title) && $item->title) ? $item->title : '';

				$output  .= '<div class="sppb-panel sppb-' . $style . '">';
				$output  .= '<div class="sppb-panel-heading' . (($key == 0) ? ' active' : '') . ' ' . ($icon_position == 'right' ? 'sppb-accordion-icon-position-right' : '') . '" id="sppb-ac-heading-' . $this->addon->id . '-key-' . $key . '" aria-expanded="' . (($key == 0) ? 'true' : 'false') . '" aria-controls="sppb-ac-content-' . $this->addon->id . '-key-' . $key . '">';

				if (isset($item->icon) && $item->icon != '' && $style == 'panel-custom') {
					$output  .= '<span class="sppb-accordion-icon-wrap" aria-label="' . trim(strip_tags($item_title)) . '">';

					$icon_arr = array_filter(explode(' ', $item->icon));

					if (count($icon_arr) === 1) {
						$item->icon = 'fa ' . $item->icon;
					}

					$output  .= '<i class="' . $item->icon . '" aria-hidden="true"></i> ';
					$output  .= '</span>'; //.sppb-accordion-icon-wrap
				}

				$output  .= '<span class="sppb-panel-title" aria-label="' . trim(strip_tags($item_title)) . '">';

				if (isset($item->icon) && $item->icon != '' && $style !== 'panel-custom') {

					$icon_arr = array_filter(explode(' ', $item->icon));
					if (count($icon_arr) === 1) {
						$item->icon = 'fa ' . $item->icon;
					}

					$output  .= '<i class="' . $item->icon . '" aria-hidden="true"></i> ';
				}

				$output  .= $item_title;
				$output  .= '</span>'; //.sppb-panel-title

				if ($style !== 'panel-custom') {
					$output  .= '<span class="sppb-toggle-direction" aria-label="Toggle Direction Icon ' . ($key + 1) . '"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>';
				}

				$output  .= '</div>'; //.sppb-panel-heading
				$output  .= '<div id="sppb-ac-content-' . $this->addon->id . '-key-' . $key . '" class="sppb-panel-collapse"' . (($key != 0) ? ' style="display: none;"' : '') . ' aria-labelledby="sppb-ac-heading-' . $this->addon->id . '-key-' . $key . '">';
				$output  .= '<div class="sppb-panel-body">';
				$output  .= isset($item->content) ? $item->content : '';
				$output  .= '</div>'; //.sppb-panel-body
				$output  .= '</div>'; //.sppb-panel-collapse
				$output  .= '</div>'; //.sppb-panel
			}
		}


		$output  .= '</div>';
		$output  .= '</div>';
		$output  .= '</div>';

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
		$css = '';
		$cssHelper = new CSSHelper($addon_id);

		$itemHeaderFontStyle = $cssHelper->typography(
			'.sppb-panel-custom .sppb-panel-heading .sppb-panel-title',
			$settings,
			'item_title_typography',
			[
				'font'           => 'item_title_font_family',
				'size'           => 'item_title_fontsize',
				'line_height'    => 'item_title_lineheight',
				'letter_spacing' => 'item_title_letterspace',
				'uppercase'      => 'item_title_font_style.uppercase',
				'italic'         => 'item_title_font_style.italic',
				'underline'      => 'item_title_font_style.underline',
				'weight'         => 'item_title_font_style.weight',
			]
		);

		// @todo: item_margin is removed from the settings, but it is still used in the generateStyle method.
		$itemStyle = $cssHelper->generateStyle('.sppb-panel.sppb-panel-custom', $settings, ['item_margin' => 'margin', 'item_padding' => 'padding', 'item_bg' => 'background', 'item_border_color' => 'border-color', 'item_border_width' => 'border-style: solid; border-width', 'item_border_radius' => 'border-radius'], ['item_padding' => false, 'item_margin' => false, 'item_bg' => false, 'item_border_color' => false], ['item_padding' => 'spacing', 'item_margin' => 'spacing']);
		$spacingStyle = $cssHelper->generateStyle('.sppb-panel-group .sppb-panel.sppb-panel-custom:not(:last-child)', $settings, ['item_spacing' => 'margin-bottom']);
		$titleStyle = $cssHelper->generateStyle('.sppb-panel-custom .sppb-panel-heading', $settings, ['item_title_bg_color' => 'background', 'item_title_text_color' => 'color', 'item_title_padding' => 'padding'], false, ['item_title_padding' => 'spacing']);
		$iconStyle = $cssHelper->generateStyle('.sppb-panel-custom .sppb-accordion-icon-wrap', $settings, ['icon_margin' => 'margin', 'icon_text_color' => 'color', 'icon_fontsize' => 'font-size'], ['icon_margin' => false, 'icon_text_color' => false], ['icon_margin' => 'spacing']);
		$contentStyle = $cssHelper->generateStyle('.sppb-panel-custom .sppb-panel-body', $settings, ['item_content_padding' => 'padding', 'item_border_width' => 'border-top-style: solid; border-top-width', 'item_border_color' => 'border-top-color'], ['item_content_padding' => 'px', 'item_border_width' => 'px', 'item_border_color' => false], ['item_content_padding' => 'spacing']);
		$activeTitleStyle = $cssHelper->generateStyle('.sppb-panel-custom .sppb-panel-heading.active', $settings, ['active_title_bg_color' => 'background', 'active_title_text_color' => 'color'], false);
		$activeIconStyle = $cssHelper->generateStyle('.sppb-panel-custom .active .sppb-accordion-icon-wrap', $settings, ['active_icon_color' => 'color', 'active_icon_rotate' => 'transform: rotate(%sdeg)'], false);

		$css .= $itemStyle;
		$css .= $iconStyle;
		$css .= $titleStyle;
		$css .= $contentStyle;
		$css .= $spacingStyle;
		$css .= $activeIconStyle;
		$css .= $activeTitleStyle;
		$css .= $itemHeaderFontStyle;

		return $css;
	}

	/**
	 * Attach inline scripts.
	 *
	 * @return string
	 */
	public function js()
	{
		$settings = $this->addon->settings;
		$addon_id = '#sppb-addon-' . $this->addon->id;
		$openitem = (isset($settings->openitem) && $settings->openitem) ? $settings->openitem : '';

		if ($openitem) {
			$js = "jQuery(document).ready(function($){'use strict';
				if('" . $openitem . "' === 'hide') {
					$( '" . $addon_id . "' + ' .sppb-addon-accordion .sppb-panel-heading').removeClass('active');
				} else {
					$( '" . $addon_id . "' + ' .sppb-addon-accordion .sppb-panel-heading').addClass('active');
				}
				$( '" . $addon_id . "' + ' .sppb-addon-accordion .sppb-panel-collapse')." . $openitem . "();
			});";
			return $js;
		}
		return;
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

		<style  type="text/css">';

		// Title
		$titleTypographyFallbacks = [
			'font'           => 'data.title_font_family',
			'size'           => 'data.title_fontsize',
			'line_height'    => 'data.title_lineheight',
			'letter_spacing' => 'data.title_letterspace',
			'uppercase'      => 'data.title_font_style?.uppercase',
			'italic'         => 'data.title_font_style?.italic',
			'underline'      => 'data.title_font_style?.underline',
			'weight'         => 'data.title_font_style?.weight'
		];
		$output .= $lodash->unit('margin-top', '.sppb-addon-title', 'data.title_margin_top', 'px');
		$output .= $lodash->unit('margin-bottom', '.sppb-addon-title', 'data.title_margin_bottom', 'px');
		$output .= $lodash->color('color', '.sppb-addon-title', 'data.title_text_color');
		$output .= $lodash->typography('.sppb-addon-title', 'data.title_typography', $titleTypographyFallbacks);

		// Accordion
		$itemTitleTypographyFallbacks = [
			'font'           => 'data.item_title_font_family',
			'size'           => 'data.item_title_fontsize',
			'line_height'    => 'data.item_title_lineheight',
			'letter_spacing' => 'data.item_title_letterspace',
			'uppercase'      => 'data.item_title_font_style?.uppercase',
			'italic'         => 'data.item_title_font_style?.italic',
			'underline'      => 'data.item_title_font_style?.underline',
			'weight'         => 'data.item_title_font_style?.weight'
		];

		$output .= $lodash->typography('.sppb-panel-title', 'data.item_title_typography', $itemTitleTypographyFallbacks);
		$output .= $lodash->unit('font-size', '.sppb-accordion-icon-wrap', 'data.icon_fontsize', 'px');
		$output .= $lodash->spacing('margin', '.sppb-accordion-icon-wrap', 'data.icon_margin');
		$output .= $lodash->transform('rotate', '.active .sppb-accordion-icon-wrap', 'data.active_icon_rotate', 'deg');

		$output .= $lodash->spacing('padding', '.sppb-panel-body', 'data.item_content_padding');

		// custom
		$output .= '<# if (data.style == "panel-custom") { #>';
		$output .= $lodash->spacing('padding', '.sppb-panel.sppb-panel-custom', 'data.item_padding');
		$output .= $lodash->spacing('padding', '.sppb-panel-custom .sppb-panel-heading', 'data.item_title_padding');
		$output .= $lodash->unit('margin-bottom', '.sppb-panel-group .sppb-panel:not(:last-child)', 'data.item_spacing', 'px');

		// panel
		$output .= $lodash->color('background-color', '.sppb-panel', 'data.item_bg');
		$output .= $lodash->unit('border-radius', '.sppb-panel', 'data.item_border_radius', 'px');

		// icon
		$output .= $lodash->color('color', '.sppb-accordion-icon-wrap', 'data.icon_text_color');
		$output .= $lodash->color('color', '.active .sppb-accordion-icon-wrap', 'data.active_icon_color');

		// heading
		$output .= $lodash->color('color', '.sppb-panel-heading', 'data.item_title_text_color');
		$output .= $lodash->color('background-color', '.sppb-panel-heading', 'data.item_title_bg_color');
		$output .= $lodash->color('color', '.sppb-panel-heading.active', 'data.active_title_text_color');
		$output .= $lodash->color('background-color', '.sppb-panel-heading.active', 'data.active_title_bg_color');
		$output .= $lodash->spacing('margin', '.sppb-panel.sppb-panel-custom', 'data.item_margin');
		// accordion
		$output .= '<# if (!_.isEmpty(data.item_border_width)) { #>';
		$output .= '#sppb-addon-{{ data.id }} .sppb-panel.sppb-panel-custom {border-style: solid;}';
		$output .= $lodash->unit('border-width', '.sppb-panel.sppb-panel-custom', 'data.item_border_width', 'px', false);
		$output .= $lodash->unit('border-color', '.sppb-panel.sppb-panel-custom', 'data.item_border_color', '', false);

		$output .= '#sppb-addon-{{ data.id }} .sppb-panel-body {border-top-style: solid;}';
		$output .= $lodash->unit('border-top-width', '.sppb-panel-body', 'data.item_border_width', 'px', false);
		$output .= $lodash->unit('border-top-color', '.sppb-panel-body', 'data.item_border_color', '', false);
		$output .= '<# } #>';



		$output .= '<# } #>';

		$output .= '
		</style>
		';
		return $output;
	}
}
