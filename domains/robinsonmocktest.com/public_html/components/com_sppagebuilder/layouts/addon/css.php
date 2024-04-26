<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Component\ComponentHelper;

$doc = Factory::getDocument();

//Image lazy load
$config = ComponentHelper::getParams('com_sppagebuilder');
$lazyload = $config->get('lazyloadimg', '0');
$placeholder = $config->get('lazyplaceholder', '');
$lazy_bg_image = '';
$placeholder_bg_image = '';

if (!class_exists('SppbCustomCssParser'))
{
	require_once JPATH_ROOT . '/components/com_sppagebuilder/helpers/css-parser.php';
}

$selector_css = new FileLayout('addon.css.selector', JPATH_ROOT . '/components/com_sppagebuilder/layouts');
$addon = $displayData['addon'];
$settings = $addon->settings;
$inlineCSS = '';
$addon_css = '';
$addon_link_css = '';
$addon_link_hover_css = '';
$addon_id = "#sppb-addon-" . $addon->id;
$addon_wrapper_id = "#sppb-addon-wrapper-" . $addon->id;
$cssHelper = new CSSHelper($addon_id);

/** Addon styles. */
$propsMap = [
	'global_text_color' => 'color',
	'global_border_width' => !empty($settings->global_user_border) ? 'border-width' : null,
	'global_border_color' => !empty($settings->global_user_border) ? 'border-color' : null,
	'global_boder_style' => !empty($settings->global_user_border) ? 'border-style' : null,
	'global_border_radius' => 'border-radius',
	'global_padding' => 'padding',
];

$units = ['global_text_color' => false, 'global_border_color' => false, 'global_boder_style' => false, 'global_padding' => false];
$modifier = ['global_padding' => 'spacing'];
$addonStyle = $cssHelper->generateStyle(':self', $settings, $propsMap, $units, $modifier);

/** Addon wrapper styles. */

/** If the addon is not a DIV addon then apply the style to the addon wrapper. */
if (isset($addon->name) && $addon->name !== 'div')
{
	$cssHelper->setID($addon_wrapper_id);
}

$propsMap = [
	'global_margin' => 'margin',
	'global_seclect_position' => !empty($settings->global_custom_position) ? 'position' : null,
	'global_addon_position_left' => !empty($settings->global_custom_position) ? 'left' : null,
	'global_addon_position_top' => !empty($settings->global_custom_position) ? 'top' : null,
	'global_addon_z_index' => !empty($settings->global_custom_position) ? 'z-index' : null,
	'global_width' => !empty($settings->use_global_width) ? 'width' : null,
];
$units = [
	'global_margin' => false,
	'global_seclect_position' => false,
	'global_addon_z_index' => false,
	'global_width' => '%'
];
$modifier = ['global_margin' => 'spacing'];
$wrapperStyle = $cssHelper->generateStyle(':self', $settings, $propsMap, $units, $modifier);

$cssHelper->setID($addon_id);

if (isset($settings->global_link_color) && $settings->global_link_color)
{
	$addon_link_css .= "\tcolor: " . $settings->global_link_color . ";\n";
}

if (isset($settings->global_link_hover_color) && $settings->global_link_hover_color)
{
	$addon_link_hover_css .= "\tcolor: " . $settings->global_link_hover_color . ";\n";
}

// Background
$global_background_image = (isset($settings->global_background_image) && $settings->global_background_image) ? $settings->global_background_image : '';
$global_background_image_src = isset($global_background_image->src) ? $global_background_image->src : $global_background_image;

if (!isset($settings->global_background_type) && isset($settings->global_use_background) && $settings->global_use_background)
{
	if (isset($settings->global_background_color) && $settings->global_background_color)
	{
		$addon_css .= "\tbackground-color: " . $settings->global_background_color . ";\n";
	}

	if ($global_background_image_src)
	{
		if ($lazyload)
		{
			if ($placeholder)
			{
				$placeholder_bg_image .= 'background-image:url(' . $placeholder . ');';
			}

			if (strpos($global_background_image_src, "http://") !== false || strpos($global_background_image_src, "https://") !== false)
			{
				$lazy_bg_image .= "\tbackground-image: url(" . $global_background_image_src . ");\n";
			}
			else
			{
				$lazy_bg_image .= "\tbackground-image: url(" . Uri::base(true) . '/' . $global_background_image_src . ");\n";
			}
		}
		else
		{
			if (strpos($global_background_image_src, "http://") !== false || strpos($global_background_image_src, "https://") !== false)
			{
				$addon_css .= "\tbackground-image: url(" . $global_background_image_src . ");\n";
			}
			else
			{
				$addon_css .= "\tbackground-image: url(" . Uri::base(true) . '/' . $global_background_image_src . ");\n";
			}
		}

		if (isset($settings->global_background_repeat) && $settings->global_background_repeat)
		{
			$addon_css .= "\tbackground-repeat: " . $settings->global_background_repeat . ";\n";
		}

		if (isset($settings->global_background_size) && $settings->global_background_size)
		{
			$addon_css .= "\tbackground-size: " . $settings->global_background_size . ";\n";
		}

		if (isset($settings->global_background_attachment) && $settings->global_background_attachment)
		{
			$addon_css .= "\tbackground-attachment: " . $settings->global_background_attachment . ";\n";
		}

		if (isset($settings->global_background_position) && $settings->global_background_position)
		{
			$addon_css .= "background-position:" . $settings->global_background_position . ";";
		}
	}
}
elseif (isset($settings->global_background_type))
{
	if (($settings->global_background_type == 'color' || $settings->global_background_type == 'image') && isset($settings->global_background_color) && $settings->global_background_color)
	{
		$addon_css .= "\tbackground-color: " . $settings->global_background_color . ";\n";
	}

	if ($settings->global_background_type == 'gradient' && isset($settings->global_background_gradient) && is_object($settings->global_background_gradient))
	{
		$radialPos = (isset($settings->global_background_gradient->radialPos) && !empty($settings->global_background_gradient->radialPos)) ? $settings->global_background_gradient->radialPos : 'center center';
		$gradientColor = (isset($settings->global_background_gradient->color) && !empty($settings->global_background_gradient->color)) ? $settings->global_background_gradient->color : '';
		$gradientColor2 = (isset($settings->global_background_gradient->color2) && !empty($settings->global_background_gradient->color2)) ? $settings->global_background_gradient->color2 : '';
		$gradientDeg = (isset($settings->global_background_gradient->deg) && !empty($settings->global_background_gradient->deg)) ? $settings->global_background_gradient->deg : '0';
		$gradientPos = (isset($settings->global_background_gradient->pos) && !empty($settings->global_background_gradient->pos)) ? $settings->global_background_gradient->pos : '0';
		$gradientPos2 = (isset($settings->global_background_gradient->pos2) && !empty($settings->global_background_gradient->pos2)) ? $settings->global_background_gradient->pos2 : '100';

		if (isset($settings->global_background_gradient->type) && $settings->global_background_gradient->type === 'radial')
		{
			$addon_css .= "\tbackground-image: radial-gradient(at " . $radialPos . ", " . $gradientColor . " " . $gradientPos . "%, " . $gradientColor2 . " " . $gradientPos2 . "%);\n";
		}
		else
		{
			$addon_css .= "\tbackground-image: linear-gradient(" . $gradientDeg . "deg, " . $gradientColor . " " . $gradientPos . "%, " . $gradientColor2 . " " . $gradientPos2 . "%);\n";
		}
	}

	if ($settings->global_background_type == 'image' && $global_background_image_src)
	{
		if ($lazyload)
		{
			if ($placeholder)
			{
				$placeholder_bg_image .= 'background-image:url(' . $placeholder . ');';
			}

			if (strpos($global_background_image_src, "http://") !== false || strpos($global_background_image_src, "https://") !== false)
			{
				$lazy_bg_image .= "\tbackground-image: url(" . $global_background_image_src . ");\n";
			}
			else
			{
				$lazy_bg_image .= "\tbackground-image: url(" . Uri::base(true) . '/' . $global_background_image_src . ");\n";
			}
		}
		else
		{
			if (strpos($global_background_image_src, "http://") !== false || strpos($global_background_image_src, "https://") !== false)
			{
				$addon_css .= "\tbackground-image: url(" . $global_background_image_src . ");\n";
			}
			else
			{
				$addon_css .= "\tbackground-image: url(" . Uri::base(true) . '/' . $global_background_image_src . ");\n";
			}
		}

		if (isset($settings->global_background_repeat) && $settings->global_background_repeat)
		{
			$addon_css .= "\tbackground-repeat: " . $settings->global_background_repeat . ";\n";
		}

		if (isset($settings->global_background_size) && $settings->global_background_size)
		{
			$addon_css .= "\tbackground-size: " . $settings->global_background_size . ";\n";
		}

		if (isset($settings->global_background_attachment) && $settings->global_background_attachment)
		{
			$addon_css .= "\tbackground-attachment: " . $settings->global_background_attachment . ";\n";
		}

		if (isset($settings->global_background_position) && $settings->global_background_position)
		{
			$addon_css .= "background-position:" . $settings->global_background_position . ";";
		}
	}
}

$settings->global_boxshadow = CSSHelper::parseBoxShadow($settings, 'global_boxshadow');

$boxShadowSelector = isset($addon->name) && ($addon->name === 'button' || $addon->name === 'button_group')  ? '.sppb-btn' : ':self';

$boxShadowStyle = $cssHelper->generateStyle($boxShadowSelector, $settings, ['global_boxshadow' => 'box-shadow'], false);
$inlineCSS .= $boxShadowStyle;

if (isset($settings->global_use_overlay) && $settings->global_use_overlay)
{
	$addon_css .= "position: relative;\noverflow: hidden;\n";
}

if ($addonStyle)
{
	$inlineCSS .= $addonStyle;
}

if ($wrapperStyle)
{
	$inlineCSS .= $wrapperStyle;
}

if ($addon_css)
{
	$inlineCSS .= $addon_id . " {\n" . $addon_css . "}\n";
	$inlineCSS .= $addon_id . " {\n" . $placeholder_bg_image . "}\n";
	$inlineCSS .= $addon_id . ".sppb-element-loaded {\n" . $lazy_bg_image . "}\n";
}

if (!isset($settings->global_overlay_type))
{
	$settings->global_overlay_type = 'overlay_color';
}

if (isset($settings->global_use_overlay) && $settings->global_use_overlay && isset($settings->global_background_overlay) && $settings->global_background_overlay && $settings->global_overlay_type === 'overlay_color')
{
	$inlineCSS .= $addon_id . " .sppb-addon-overlayer { background-color: {$settings->global_background_overlay}; }\n";
}

if (isset($settings->global_use_overlay) && $settings->global_use_overlay)
{
	$inlineCSS .= $addon_id . " > .sppb-addon { position: relative; }\n";
}

// Overlay
$global_pattern_overlay = (isset($settings->global_pattern_overlay) && $settings->global_pattern_overlay) ? $settings->global_pattern_overlay : '';
$global_pattern_overlay_src = isset($global_pattern_overlay->src) ? $global_pattern_overlay->src : $global_pattern_overlay;

if (isset($settings->global_background_type))
{
	if ($settings->global_background_type == 'image')
	{
		if (isset($settings->global_gradient_overlay) && $settings->global_overlay_type == 'overlay_gradient')
		{
			$overlay_radialPos = (isset($settings->global_gradient_overlay->radialPos) && !empty($settings->global_gradient_overlay->radialPos)) ? $settings->global_gradient_overlay->radialPos : 'center center';
			$overlay_gradientColor = (isset($settings->global_gradient_overlay->color) && !empty($settings->global_gradient_overlay->color)) ? $settings->global_gradient_overlay->color : '';
			$overlay_gradientColor2 = (isset($settings->global_gradient_overlay->color2) && !empty($settings->global_gradient_overlay->color2)) ? $settings->global_gradient_overlay->color2 : '';
			$overlay_gradientDeg = (isset($settings->global_gradient_overlay->deg) && !empty($settings->global_gradient_overlay->deg)) ? $settings->global_gradient_overlay->deg : '0';
			$overlay_gradientPos = (isset($settings->global_gradient_overlay->pos) && !empty($settings->global_gradient_overlay->pos)) ? $settings->global_gradient_overlay->pos : '0';
			$overlay_gradientPos2 = (isset($settings->global_gradient_overlay->pos2) && !empty($settings->global_gradient_overlay->pos2)) ? $settings->global_gradient_overlay->pos2 : '100';

			if (isset($settings->global_gradient_overlay->type) && $settings->global_gradient_overlay->type == 'radial')
			{
				$inlineCSS .= $addon_id . ' .sppb-addon-overlayer {
					background: radial-gradient(at ' . $overlay_radialPos . ', ' . $overlay_gradientColor . ' ' . $overlay_gradientPos . '%, ' . $overlay_gradientColor2 . ' ' . $overlay_gradientPos2 . '%) transparent;
				}';
			}
			else
			{
				$inlineCSS .= $addon_id . ' .sppb-addon-overlayer {
					background: linear-gradient(' . $overlay_gradientDeg . 'deg, ' . $overlay_gradientColor . ' ' . $overlay_gradientPos . '%, ' . $overlay_gradientColor2 . ' ' . $overlay_gradientPos2 . '%) transparent;
				}';
			}
		}
		if ($global_pattern_overlay_src && $settings->global_overlay_type == 'overlay_pattern')
		{
			if (strpos($global_pattern_overlay_src, "http://") !== false || strpos($global_pattern_overlay_src, "https://") !== false)
			{
				$inlineCSS .= $addon_id . ' .sppb-addon-overlayer {
					background-image:url(' . $global_pattern_overlay_src . ');
					background-attachment: scroll;
				}';

				if (isset($settings->global_overlay_pattern_color))
				{
					$inlineCSS .= $addon_id . ' .sppb-addon-overlayer {
						background-color:' . $settings->global_overlay_pattern_color . ';
					}';
				}
			}
			else
			{
				$inlineCSS .= $addon_id . ' .sppb-addon-overlayer {
					background-image:url(' . Uri::base() . '/' . $global_pattern_overlay_src . ');
					background-attachment: scroll;
				}';

				if (isset($settings->global_overlay_pattern_color))
				{
					$inlineCSS .= $addon_id . ' .sppb-addon-overlayer {
						background-color:' . $settings->global_overlay_pattern_color . ';
					}';
				}
			}
		}
	}
}

//Blend Mode
if (isset($settings->global_background_type) && $settings->global_background_type)
{
	if ($settings->global_background_type === 'image')
	{
		if (isset($settings->blend_mode) && $settings->blend_mode)
		{
			$inlineCSS .= $addon_id . ' .sppb-addon-overlayer {
				mix-blend-mode:' . $settings->blend_mode . ';
			}';
		}
	}
}

if ($addon_link_css)
{
	$inlineCSS .= $addon_id . " a {\n" . $addon_link_css . "}\n";
}

if ($addon_link_hover_css)
{
	$inlineCSS .= $addon_id . " a:hover,\n#sppb-addon-" . $addon->id . " a:focus,\n#sppb-addon-" . $addon->id . " a:active {\n" . $addon_link_hover_css . "}\n";
}


$cssHelper->setID($addon_id);
$propsMap = [
	'title_margin_top' => 'margin-top',
	'title_margin_bottom' => 'margin-bottom',
	'title_text_color' => 'color',
];
$units = [
	'title_text_color' => false,
];
$titleStyle = $cssHelper->generateStyle('.sppb-addon-title', $settings, $propsMap, $units);

if (!empty($settings->title))
{
	$typographyFallbacks = [
		'font'           => 'font_family',
		'size'           => 'title_fontsize',
		'line_height'    => 'title_lineheight',
		'letter_spacing' => 'title_letterspace',
		'uppercase'      => 'title_font_style.uppercase',
		'italic'         => 'title_font_style.italic',
		'underline'      => 'title_font_style.underline',
		'weight'         => 'title_font_style.weight',
	];

	$typography = $cssHelper->typography('.sppb-addon-title', $settings, 'title_typography', $typographyFallbacks);
	$titleStyle .= "\r\n" . $typography;
}

$inlineCSS .= $titleStyle;

// Selector
$inlineCSS .= $selector_css->render(
	array(
		'options' => $settings,
		'addon_id' => $addon_id
	)
);

if (class_exists('SppbCustomCssParser') && isset($settings->global_custom_css) && !empty($settings->global_custom_css))
{
	$inlineCSS .= SppbCustomCssParser::getCss($addon->name, $settings->global_custom_css, $addon_id, $addon_wrapper_id);
}

echo $inlineCSS;