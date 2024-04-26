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
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Filesystem\Path;

$options = $displayData['options'];
$custom_class  = (isset($options->class)) ? ' ' . $options->class : '';
$data_attr = '';
$doc = Factory::getDocument();
$device = SpPgaeBuilderBase::$defaultDevice;

$deviceList = array_filter(AddonHelper::$deviceList, function ($size) {
	return $size !== SpPgaeBuilderBase::$defaultDevice;
});

// Image lazy load
$config = ComponentHelper::getParams('com_sppagebuilder');
$lazyload = $config->get('lazyloadimg', '0');
$placeholder = $config->get('lazyplaceholder', '');
$lazy_bg_image = '';
$placeholder_bg_image = '';

// Style
$styleX = '';
$style = '';

$column_styles = '';
$columnSelector = '#column-id-' . $options->dynamicId;
$columnWrapSelector = '#column-wrap-id-' . $options->dynamicId;
$options->boxshadow = CSSHelper::parseBoxShadow($options, 'boxshadow');

$cssHelper = new CSSHelper($columnSelector);
$propMap = [
	'column_height' => 'height',
	'column_min_height' => 'min-height',
	'column_max_height' => 'max-height',
	'padding' => 'padding',
	'border_radius' => 'border-radius',
	'boxshadow' => "box-shadow"
];
$units = ['width' => false, 'boxshadow' => false];
$modifier = ['padding' => 'spacing'];

if (isset($options->use_border) && $options->use_border) {
	$propMap['border_width'] = 'border-width';
	$propMap['border_color'] = 'border-color';
	$propMap['boder_style'] = 'border-style';
	$units['border_color'] = false;
	$units['boder_style'] = false;
}

if (!empty($options->color)) {
	$propMap['color'] = 'color';
	$units['color'] = false;
}

$columnStyle = $cssHelper->generateStyle(':self', $options, $propMap, $units, $modifier);
$column_styles .= $columnStyle;

$cssHelper->setID($columnWrapSelector);
$columnWrapperPropMap = [
	'width' => ['max-width', 'flex-basis'],
	'margin' => 'margin'
];

$columnWrapperUnits = ['width' => false];
$columnWrapperModifiers = ['margin' => 'spacing'];

$columnWrapperStyle = $cssHelper->generateStyle(':self', $options, $columnWrapperPropMap, $columnWrapperUnits, $columnWrapperModifiers);
$column_styles .= $columnWrapperStyle;

$cssHelper->setID($columnSelector);
$borderRadiusStyle = $cssHelper->generateStyle('.sppb-column-overlay', $options, ['border_radius' => 'border-radius']);
$column_styles .= $borderRadiusStyle;

$background_image = (isset($options->background_image) && $options->background_image) ? $options->background_image : '';
$background_image_src = isset($background_image->src) ? $background_image->src : $background_image;
if (isset($options->background_type)) {
	if (($options->background_type == 'image' || $options->background_type == 'color') && isset($options->background) && $options->background) $style .= 'background-color:' . $options->background . ';';

	if ($options->background_type == 'image' && $background_image_src) {
		if ($lazyload) {
			if ($placeholder) {
				$placeholder_bg_image .= 'background-image:url(' . $placeholder . ');';
			}
			if (strpos($background_image_src, "http://") !== false || strpos($background_image_src, "https://") !== false) {
				$lazy_bg_image .= 'background-image:url(' . $background_image_src . ');';
			} else {
				$original_src = Uri::base(true) . '/' . $background_image_src;
				$lazy_bg_image .= 'background-image:url(' . SppagebuilderHelperSite::cleanPath($original_src) . ');';
			}
		} else {
			if (strpos($background_image_src, "http://") !== false || strpos($background_image_src, "https://") !== false) {
				$style .= 'background-image:url(' . $background_image_src . ');';
			} else {
				$original_src = Uri::base(true) . '/' . $background_image_src;
				$style .= 'background-image:url(' . SppagebuilderHelperSite::cleanPath($original_src) . ');';
			}
		}

		if (isset($options->background_repeat) && $options->background_repeat) $style .= 'background-repeat:' . $options->background_repeat . ';';
		if (isset($options->background_size) && $options->background_size && $options->background_size != 'custom') $style .= 'background-size:' . $options->background_size . ';';
		if (isset($options->background_attachment) && $options->background_attachment) $style .= 'background-attachment:' . $options->background_attachment . ';';
		if (isset($options->background_position) && $options->background_position && $options->background_position != 'custom') $style .= 'background-position:' . $options->background_position . ';';

		if (isset($options->background_size) && $options->background_size === 'custom') {
			if (isset($options->background_size_custom) && \is_object($options->background_size_custom)) {
				$customBackgroundSize = AddonHelper::generateMultiDeviceObject($options, 'background_size_custom', 'background-size', $device, false, ($options->background_size_custom->unit ?? 'px'));
				$styleX .= $customBackgroundSize->$device;
			}
		}
	}

	if (isset($options->background_position) && $options->background_position === 'custom') {
		$customBackgroundPosition = AddonHelper::initDeviceObject();

		foreach ($customBackgroundPosition as $key => $_) {
			if (isset($options->background_position_custom_x->$key) && isset($options->background_position_custom_y->$key)) {
				$xUnit = $options->background_position_custom_x->unit;
				$yUnit = $options->background_position_custom_y->unit;
				$customBackgroundPosition->$key = \is_object($options->background_position_custom_x) && \is_object($options->background_position_custom_y)
					? 'background-position: ' . $options->background_position_custom_x->$key . $xUnit . ' ' . $options->background_position_custom_y->$key . $yUnit . ';'
					: 'background-position: ' . $options->background_position_custom_x . $xUnit . ' ' . $options->background_position_custom_y . $yUnit . ';';
			}
		}

		$styleX .= $customBackgroundPosition->$device;
	}

	if ($options->background_type == 'gradient' && isset($options->background_gradient) && is_object($options->background_gradient)) {
		$radialPos = (isset($options->background_gradient->radialPos) && !empty($options->background_gradient->radialPos)) ? $options->background_gradient->radialPos : 'center center';

		$gradientColor = (isset($options->background_gradient->color) && !empty($options->background_gradient->color)) ? $options->background_gradient->color : '';

		$gradientColor2 = (isset($options->background_gradient->color2) && !empty($options->background_gradient->color2)) ? $options->background_gradient->color2 : '';

		$gradientDeg = (isset($options->background_gradient->deg) && !empty($options->background_gradient->deg)) ? $options->background_gradient->deg : '0';

		$gradientPos = (isset($options->background_gradient->pos) && !empty($options->background_gradient->pos)) ? $options->background_gradient->pos : '0';

		$gradientPos2 = (isset($options->background_gradient->pos2) && !empty($options->background_gradient->pos2)) ? $options->background_gradient->pos2 : '100';

		if (isset($options->background_gradient->type) && $options->background_gradient->type == 'radial') {
			$style .= "\tbackground-image: radial-gradient(at " . $radialPos . ", " . $gradientColor . " " . $gradientPos . "%, " . $gradientColor2 . " " . $gradientPos2 . "%);\n";
		} else {
			$style .= "\tbackground-image: linear-gradient(" . $gradientDeg . "deg, " . $gradientColor . " " . $gradientPos . "%, " . $gradientColor2 . " " . $gradientPos2 . "%);\n";
		}
	}
} else {
	if (isset($options->background) && $options->background) $style .= 'background-color:' . $options->background . ';';

	if ($background_image_src) {

		if ($lazyload) {
			if ($placeholder) {
				$placeholder_bg_image .= 'background-image:url(' . $placeholder . ');';
			}
			if (strpos($background_image_src, "http://") !== false || strpos($background_image_src, "https://") !== false) {
				$lazy_bg_image .= 'background-image:url(' . $background_image_src . ');';
			} else {
				$original_src = Uri::base(true) . '/' . $background_image_src;
				$lazy_bg_image .= 'background-image:url(' . SppagebuilderHelperSite::cleanPath($original_src) . ');';
			}
		} else {
			if (strpos($background_image_src, "http://") !== false || strpos($background_image_src, "https://") !== false) {
				$style .= 'background-image:url(' . $background_image_src . ');';
			} else {
				$original_src = Uri::base(true) . '/' . $background_image_src;
				$style .= 'background-image:url(' . SppagebuilderHelperSite::cleanPath($original_src) . ');';
			}
		}

		if (isset($options->background_repeat) && $options->background_repeat) $style .= 'background-repeat:' . $options->background_repeat . ';';
		if (isset($options->background_size) && $options->background_size && $options->background_size != 'custom') $style .= 'background-size:' . $options->background_size . ';';
		if (isset($options->background_attachment) && $options->background_attachment) $style .= 'background-attachment:' . $options->background_attachment . ';';
		if (isset($options->background_position) && $options->background_position && $options->background_position != 'custom') $style .= 'background-position:' . $options->background_position . ';';

		if (isset($options->background_size) && $options->background_size == 'custom') {
			if (isset($options->background_size_custom) && is_object($options->background_size_custom)) {
				$customBackgroundSize2 = AddonHelper::generateMultiDeviceObject($options, 'background_size_custom', 'background-size', $device, false, ($options->background_size_custom->unit ?? 'px'));
				$styleX .= $customBackgroundSize2->$device;
			}
		}
	}

	if (isset($options->background_position) && $options->background_position == 'custom') {
		$customBackgroundPosition2 = AddonHelper::initDeviceObject();

		foreach ($customBackgroundPosition2 as $key => $_) {
			if (isset($options->background_position_custom_x->$key) && isset($options->background_position_custom_y->$key)) {
				$xUnit = $options->background_position_custom_x->unit;
				$yUnit = $options->background_position_custom_y->unit;
				$customBackgroundPosition2->$key = \is_object($options->background_position_custom_x) && \is_object($options->background_position_custom_y)
					? 'background-position: ' . $options->background_position_custom_x->$key . $xUnit . ' ' . $options->background_position_custom_y->$key . $yUnit . ';'
					: 'background-position: ' . $options->background_position_custom_x . $xUnit . ' ' . $options->background_position_custom_y . $yUnit . ';';
			}
		}

		$styleX .= $customBackgroundPosition2->$device;
	}
}

$customBackgroundSize = $customBackgroundSize ?? null;
$customBackgroundPosition = $customBackgroundPosition ?? null;
$customBackgroundSize2 = $customBackgroundSize2 ?? null;
$customBackgroundPosition2 = $customBackgroundPosition2 ?? null;

$columnMediaStyle = array_map(function ($size) use (
	$columnSelector,
	$customBackgroundSize,
	$customBackgroundPosition,
	$customBackgroundSize2,
	$customBackgroundPosition2
) {
	$str = '';
	$str .= AddonHelper::mediaQuery($size);
	$str .= $columnSelector . '{';
	$str .= $customBackgroundSize ? $customBackgroundSize->$size : '';
	$str .= $customBackgroundPosition ? $customBackgroundPosition->$size : '';
	$str .= $customBackgroundSize2 ? $customBackgroundSize2->$size : '';
	$str .= $customBackgroundPosition2 ? $customBackgroundPosition2->$size : '';
	$str .= '}';
	$str .= '}';

	return $str;
}, $deviceList);


$columnMediaStyle = implode("\r\n", $columnMediaStyle);

if ($styleX) {
	$column_styles .= $columnSelector . '{' . $styleX . '}';
}

if (!empty($borderRadius->$device)) {
	$column_styles .= '#column-id-' . $options->dynamicId . ' .sppb-column-overlay {' . $borderRadius->$device . '}';
}

if ($columnMediaStyle) {
	$column_styles .= $columnMediaStyle;
}

if ($style) {
	$column_styles .= '#column-id-' . $options->dynamicId . '{' . $style . '}';
	$column_styles .= '#column-id-' . $options->dynamicId . '{' . $placeholder_bg_image . '}';
	$column_styles .= '#column-id-' . $options->dynamicId . '.sppb-element-loaded {' . $lazy_bg_image . '}';
}

//Overlay
$pattern_overlay = (isset($options->pattern_overlay) && $options->pattern_overlay) ? $options->pattern_overlay : '';
$pattern_overlay_src = isset($pattern_overlay->src) ? $pattern_overlay->src : $pattern_overlay;

if (isset($options->background_type)) {
	if ($options->background_type == 'image' && $background_image_src) {
		if (!isset($options->overlay_type)) {
			$options->overlay_type = 'overlay_color';
		}
		if (isset($options->overlay) && $options->overlay && $options->overlay_type === 'overlay_color') {
			$column_styles .= '#column-id-' . $options->dynamicId . ' > .sppb-column-overlay {background-color: ' . $options->overlay . '}';
		}
		if (isset($options->gradient_overlay) && $options->gradient_overlay && $options->overlay_type == 'overlay_gradient') {
			$overlay_radialPos = (isset($options->gradient_overlay->radialPos) && !empty($options->gradient_overlay->radialPos)) ? $options->gradient_overlay->radialPos : 'center center';
			$overlay_gradientColor = (isset($options->gradient_overlay->color) && !empty($options->gradient_overlay->color)) ? $options->gradient_overlay->color : '';
			$overlay_gradientColor2 = (isset($options->gradient_overlay->color2) && !empty($options->gradient_overlay->color2)) ? $options->gradient_overlay->color2 : '';
			$overlay_gradientDeg = (isset($options->gradient_overlay->deg) && !empty($options->gradient_overlay->deg)) ? $options->gradient_overlay->deg : '0';
			$overlay_gradientPos = (isset($options->gradient_overlay->pos) && !empty($options->gradient_overlay->pos)) ? $options->gradient_overlay->pos : '0';
			$overlay_gradientPos2 = (isset($options->gradient_overlay->pos2) && !empty($options->gradient_overlay->pos2)) ? $options->gradient_overlay->pos2 : '100';

			if (isset($options->gradient_overlay->type) && $options->gradient_overlay->type == 'radial') {
				$column_styles .= '#column-id-' . $options->dynamicId . ' > .sppb-column-overlay {
					background: radial-gradient(at ' . $overlay_radialPos . ', ' . $overlay_gradientColor . ' ' . $overlay_gradientPos . '%, ' . $overlay_gradientColor2 . ' ' . $overlay_gradientPos2 . '%) transparent;
				}';
			} else {
				$column_styles .= '#column-id-' . $options->dynamicId . ' > .sppb-column-overlay {
					background: linear-gradient(' . $overlay_gradientDeg . 'deg, ' . $overlay_gradientColor . ' ' . $overlay_gradientPos . '%, ' . $overlay_gradientColor2 . ' ' . $overlay_gradientPos2 . '%) transparent;
				}';
			}
		}

		if ($pattern_overlay_src && $options->overlay_type == 'overlay_pattern') {

			if (strpos($pattern_overlay_src, "http://") !== false || strpos($pattern_overlay_src, "https://") !== false) {
				$column_styles .= '#column-id-' . $options->dynamicId . ' > .sppb-column-overlay {
					background-image:url(' . $pattern_overlay_src . ');
					background-attachment: scroll;
				}';
				if (isset($options->overlay_pattern_color)) {
					$column_styles .= '#column-id-' . $options->dynamicId . ' > .sppb-column-overlay {
						background-color:' . $options->overlay_pattern_color . ';
					}';
				}
			} else {
				$original_src = Uri::base(true) . '/' . $pattern_overlay_src;
				$column_styles .= '#column-id-' . $options->dynamicId . ' > .sppb-column-overlay {
					background-image:url(' . SppagebuilderHelperSite::cleanPath($original_src) . ');
					background-attachment: scroll;
				}';
				if (isset($options->overlay_pattern_color)) {
					$column_styles .= '#column-id-' . $options->dynamicId . ' > .sppb-column-overlay {
						background-color:' . $options->overlay_pattern_color . ';
					}';
				}
			}
		}
	}
} else {
	if (isset($options->background_image) && $options->background_image) {
		if (!isset($options->overlay_type)) {
			$options->overlay_type = 'overlay_color';
		}
		if (isset($options->overlay) && $options->overlay && $options->overlay_type == 'overlay_color') {
			$column_styles .= '#column-id-' . $options->dynamicId . ' > .sppb-column-overlay {background-color: ' . $options->overlay . '}';
		}
		if (isset($options->gradient_overlay) && $options->gradient_overlay && $options->overlay_type == 'overlay_gradient') {
			$overlay_radialPos = (isset($options->gradient_overlay->radialPos) && !empty($options->gradient_overlay->radialPos)) ? $options->gradient_overlay->radialPos : 'center center';

			$overlay_gradientColor = (isset($options->gradient_overlay->color) && !empty($options->gradient_overlay->color)) ? $options->gradient_overlay->color : '';
			$overlay_gradientColor2 = (isset($options->gradient_overlay->color2) && !empty($options->gradient_overlay->color2)) ? $options->gradient_overlay->color2 : '';
			$overlay_gradientDeg = (isset($options->gradient_overlay->deg) && !empty($options->gradient_overlay->deg)) ? $options->gradient_overlay->deg : '0';
			$overlay_gradientPos = (isset($options->gradient_overlay->pos) && !empty($options->gradient_overlay->pos)) ? $options->gradient_overlay->pos : '0';
			$overlay_gradientPos2 = (isset($options->gradient_overlay->pos2) && !empty($options->gradient_overlay->pos2)) ? $options->gradient_overlay->pos2 : '100';

			if (isset($options->gradient_overlay->type) && $options->gradient_overlay->type == 'radial') {
				$column_styles .= '#column-id-' . $options->dynamicId . ' > .sppb-column-overlay {
					background: radial-gradient(at ' . $overlay_radialPos . ', ' . $overlay_gradientColor . ' ' . $overlay_gradientPos . '%, ' . $overlay_gradientColor2 . ' ' . $overlay_gradientPos2 . '%) transparent;
				}';
			} else {
				$column_styles .= '#column-id-' . $options->dynamicId . ' > .sppb-column-overlay {
					background: linear-gradient(' . $overlay_gradientDeg . 'deg, ' . $overlay_gradientColor . ' ' . $overlay_gradientPos . '%, ' . $overlay_gradientColor2 . ' ' . $overlay_gradientPos2 . '%) transparent;
				}';
			}
		}
		if ($pattern_overlay_src && $options->overlay_type == 'overlay_pattern') {
			if (strpos($pattern_overlay_src, "http://") !== false || strpos($pattern_overlay_src, "https://") !== false) {
				$column_styles .= '#column-id-' . $options->dynamicId . ' > .sppb-column-overlay {
					background-image:url(' . $pattern_overlay_src . ');
					background-attachment: scroll;
				}';
				if (isset($options->overlay_pattern_color)) {
					$column_styles .= '#column-id-' . $options->dynamicId . ' > .sppb-column-overlay {
						background-color:' . $options->overlay_pattern_color . ';
					}';
				}
			} else {
				$original_src = Uri::base(true) . '/' . $pattern_overlay_src;
				$column_styles .= '#column-id-' . $options->dynamicId . ' > .sppb-column-overlay {
					background-image:url(' . SppagebuilderHelperSite::cleanPath($original_src) . ');
					background-attachment: scroll;
				}';
				if (isset($options->overlay_pattern_color)) {
					$column_styles .= '#column-id-' . $options->dynamicId . ' > .sppb-column-overlay {
						background-color:' . $options->overlay_pattern_color . ';
					}';
				}
			}
		}
	}
}

//Blend Mode
if (isset($options->background_type) && $options->background_type) {
	if ($options->background_type == 'image') {
		if (isset($options->blend_mode) && $options->blend_mode) {
			$column_styles .= '#column-id-' . $options->dynamicId . ' > .sppb-column-overlay {
				mix-blend-mode:' . $options->blend_mode . ';
			}';
		}
	}
}

echo $column_styles;
