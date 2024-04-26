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

$options = $displayData['options'];
$device = SpPgaeBuilderBase::$defaultDevice;
$doc = Factory::getDocument();

// Image lazy load
$config = ComponentHelper::getParams('com_sppagebuilder');
$lazyload = $config->get('lazyloadimg', '0');
$placeholder = $config->get('lazyplaceholder', '');
$lazy_bg_image = '';
$placeholder_bg_image = '';

$row_id = (isset($options->id) && $options->id) ? $options->id : 'section-id-' . $options->dynamicId;
$rowIdSelector = '.sp-page-builder .page-content #' . $row_id;
$cssHelper = new CSSHelper($rowIdSelector, true);

$row_styles = '';
$style = '';

$styleX = '';
$mediaStyle = '';

$propMaps = [
	'padding'            => 'padding',
	'margin'             => 'margin',
	'section_overflow_x' => 'overflow-x',
	'section_overflow_y' => 'overflow-y',
	'color'              => 'color',
	'row_border_radius'  => 'border-radius',
	'row_border_style'   => !empty($options->row_border) ? 'border-style' : null,
	'row_border_color'   => !empty($options->row_border) ? 'border-color' : null,
	'row_border_width'   => !empty($options->row_border) ? 'border-width' : null,
	'row_width'          => 'width',
	'row_max_width'      => 'max-width',
	'row_min_width'      => 'min-width',
];

$units = [
	'row_border_color'   => false,
	'row_border_style'   => false,
	'row_border_width'   => false,
	'padding'            => false,
	'margin'             => false,
	'section_overflow_x' => false,
	'section_overflow_y' => false,
	'color'              => false,
	'row_width'          => $options->row_width->unit ?? 'px',
	'row_max_width'      => $options->row_max_width->unit ?? 'px',
	'row_min_width'      => $options->row_min_width->unit ?? 'px',
];

$modifier = ['padding' => 'spacing', 'margin' => 'spacing'];
$default = [];

if (!empty($options->section_height_option) && $options->section_height_option === 'win-height') {
	$propMaps['section_height'] = 'height';
	$default['section_height'] = '100vh';
	$units['section_height'] = false;
} elseif (!empty($options->section_height_option) && $options->section_height_option === 'height') {
	$propMaps = array_merge($propMaps, [
		'section_height' => 'height'
	]);
}

$propMaps = array_merge($propMaps, [
	'row_width' => 'width',
	'section_min_height' => 'min-height',
	'section_max_height' => 'max-height',
	'row_max_width' => 'max-width',
	'row_min_width' => 'min-width'
]);

$sectionStyle = $cssHelper->generateStyle(':self', $options, $propMaps, $units, $modifier, $default);
$cssHelper->setID($rowIdSelector . '.sppb-row-overlay', true);
$sectionOverlay = $cssHelper->generateStyle(':self', $options, ['row_border_radius' => 'border-radius']);
$cssHelper->setID($rowIdSelector, true);

$sectionStyle .= $sectionOverlay;

$deviceList = array_filter(AddonHelper::$deviceList, function ($size) {
	return $size !== SpPgaeBuilderBase::$defaultDevice;
});

$background_image = (isset($options->background_image) && $options->background_image) ? $options->background_image : '';
$background_image_src = isset($background_image->src) ? $background_image->src : $background_image;

if (property_exists($options, 'background_type') && isset($options->background_type)) {
	if (($options->background_type == 'image' || $options->background_type == 'color') && isset($options->background_color) && $options->background_color) $style .= 'background-color:' . $options->background_color . ';';

	if (($options->background_type == 'image' && $background_image_src) || (isset($options->background_type) && $options->background_type == 'video')) {
		if ($lazyload) {
			if ($placeholder) {
				$placeholder_bg_image .= 'background-image:url(' . $placeholder . ');';
			}
			if (strpos($background_image_src, "http://") !== false || strpos($background_image_src, "https://") !== false) {
				$lazy_bg_image .= 'background-image:url(' . $background_image_src . ');';
			} else {
				$originalSrc = Uri::base(true) . '/' . $background_image_src;
				$lazy_bg_image .= 'background-image:url(' . SppagebuilderHelperSite::cleanPath($originalSrc) . ');';
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
			if (isset($options->background_size_custom) && \is_object($options->background_size_custom)) {
				$backgroundSize = AddonHelper::generateMultiDeviceObject($options, 'background_size_custom', 'background-size', $device, false, ($options->background_size_custom->unit ?? 'px'));
				$styleX .= $backgroundSize->$device;
			}
		}
	}

	if (isset($options->background_position) && $options->background_position == 'custom') {
		$backgroundPosition = AddonHelper::initDeviceObject();

		foreach ($backgroundPosition as $key => $_) {
			if (isset($options->background_position_custom_x->$key) && isset($options->background_position_custom_y->$key)) {
				$backgroundPosition->$key = \is_object($options->background_position_custom_x) && \is_object($options->background_position_custom_y)
					? 'background-position: ' . $options->background_position_custom_x->$key . $options->background_position_custom_x->unit . ' ' . $options->background_position_custom_y->$key . $options->background_position_custom_y->unit . ';'
					: 'background-position: ' . $options->background_position_custom_x . $options->background_position_custom_x->unit . ' ' . $options->background_position_custom_y . $options->background_position_custom_y->unit . ';';
			}
		}

		$styleX .= $backgroundPosition->$device;
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
	if (isset($options->background_color) && $options->background_color) $style .= 'background-color:' . $options->background_color . ';';

	if ($background_image_src || (isset($options->background_type) && $options->background_type == 'video')) {
		if ($lazyload) {
			if ($placeholder) {
				$placeholder_bg_image .= 'background-image:url(' . $placeholder . ');';
			}
			if (strpos($background_image_src, "http://") !== false || strpos($background_image_src, "https://") !== false) {
				$lazy_bg_image .= 'background-image:url(' . $background_image_src . ');';
			} else {
				$original_src = Uri::base(true) . '/' . $background_image_src;
				$lazy_bg_image .= 'background-image:url(' .  SppagebuilderHelperSite::cleanPath($original_src) . ');';
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
			if (isset($options->background_size_custom) && \is_object($options->background_size_custom)) {
				$backgroundSize = AddonHelper::generateMultiDeviceObject($options, 'background_size_custom', 'background-size', $device, false, ($options->background_size_custom->unit ?? 'px'));
				$styleX .= $backgroundSize->$device;
			}
		}

		if (isset($options->background_position) && $options->background_position == 'custom') {
			$backgroundPosition = AddonHelper::initDeviceObject();

			foreach ($backgroundPosition as $key => $_) {
				if (!empty($options->background_position_custom_x->$key) && !empty($options->background_position_custom_y->$key)) {
					$backgroundPosition->$key = \is_object($options->background_position_custom_x) && \is_object($options->background_position_custom_y)
						? 'background-position: ' . $options->background_position_custom_x->$key . $options->background_position_custom_x->unit . ' ' . $options->background_position_custom_y->$key . $options->background_position_custom_y->unit . ';'
						: 'background-position: ' . $options->background_position_custom_x . $options->background_position_custom_x->unit . ' ' . $options->background_position_custom_y . $options->background_position_custom_y->unit . ';';
				}
			}

			$styleX .= $backgroundPosition->$device;
		}
	}

	if ($style) {
		$row_styles .= '.sp-page-builder .page-content #' . $row_id . '{' . $style . '}';
	}
}

// Box Shadow
if (isset($options->row_boxshadow) && $options->row_boxshadow) {
	if (is_object($options->row_boxshadow)) {
		$ho = (isset($options->row_boxshadow->ho) && $options->row_boxshadow->ho != '') ? $options->row_boxshadow->ho . 'px' : '0px';
		$vo = (isset($options->row_boxshadow->vo) && $options->row_boxshadow->vo != '') ? $options->row_boxshadow->vo . 'px' : '0px';
		$blur = (isset($options->row_boxshadow->blur) && $options->row_boxshadow->blur != '') ? $options->row_boxshadow->blur . 'px' : '0px';
		$spread = (isset($options->row_boxshadow->spread) && $options->row_boxshadow->spread != '') ? $options->row_boxshadow->spread . 'px' : '0px';
		$color = (isset($options->row_boxshadow->color) && $options->row_boxshadow->color != '') ? $options->row_boxshadow->color : '#fff';

		$style .= "box-shadow: ${ho} ${vo} ${blur} ${spread} ${color};";
	} else {
		$style .= "box-shadow: " . $options->row_boxshadow . ";";
	}
}

$rowIdSelector 		= '.sp-page-builder .page-content #' . $row_id;
$backgroundSize 	= $backgroundSize ?? false;
$backgroundPosition = $backgroundPosition ?? false;


$mediaStyle = array_map(function ($size) use (
	$rowIdSelector,
	$backgroundSize,
	$backgroundPosition
) {
	$str = '';
	$str .= AddonHelper::mediaQuery($size);

	$str .= $rowIdSelector . '{';
	$str .= !empty($backgroundSize) ? $backgroundSize->$size : '';
	$str .= !empty($backgroundPosition) ? $backgroundPosition->$size : '';
	$str .= '}';
	$str .= '}';

	return $str;
}, $deviceList);


if ($styleX) {
	$row_styles .= '.sp-page-builder .page-content #' . $row_id . '{' . $styleX . '}';
}
$mediaStyle = implode("\n", $mediaStyle);

if ($mediaStyle) {
	$row_styles .= $mediaStyle;
}

if ($style) {
	$row_styles .= '.sp-page-builder .page-content #' . $row_id . '{' . $style . '}';
	$row_styles .= '.sp-page-builder .page-content #' . $row_id . '{' . $placeholder_bg_image . '}';
	$row_styles .= '.sp-page-builder .page-content #' . $row_id . '.sppb-element-loaded {' . $lazy_bg_image . '}';
}

$row_styles .= $sectionStyle;

// Overlay
if (isset($options->background_type)) {
	if ($options->background_type == 'image' || $options->background_type == 'video') {
		if (!isset($options->overlay_type)) {
			$options->overlay_type = 'overlay_color';
		}
		if (isset($options->overlay) && $options->overlay && $options->overlay_type == 'overlay_color') {
			$row_styles .= '.sp-page-builder .page-content #' . $row_id . ' > .sppb-row-overlay {background-color: ' . $options->overlay . '}';
		}
		if (isset($options->gradient_overlay) && $options->gradient_overlay && $options->overlay_type == 'overlay_gradient') {
			$overlay_radialPos = (isset($options->gradient_overlay->radialPos) && !empty($options->gradient_overlay->radialPos)) ? $options->gradient_overlay->radialPos : 'center center';

			$overlay_gradientColor = (isset($options->gradient_overlay->color) && !empty($options->gradient_overlay->color)) ? $options->gradient_overlay->color : '';

			$overlay_gradientColor2 = (isset($options->gradient_overlay->color2) && !empty($options->gradient_overlay->color2)) ? $options->gradient_overlay->color2 : '';

			$overlay_gradientDeg = (isset($options->gradient_overlay->deg) && !empty($options->gradient_overlay->deg)) ? $options->gradient_overlay->deg : '0';

			$overlay_gradientPos = (isset($options->gradient_overlay->pos) && !empty($options->gradient_overlay->pos)) ? $options->gradient_overlay->pos : '0';

			$overlay_gradientPos2 = (isset($options->gradient_overlay->pos2) && !empty($options->gradient_overlay->pos2)) ? $options->gradient_overlay->pos2 : '100';

			if (isset($options->gradient_overlay->type) && $options->gradient_overlay->type == 'radial') {
				$row_styles .= '.sp-page-builder .page-content #' . $row_id . ' > .sppb-row-overlay {
					background: radial-gradient(at ' . $overlay_radialPos . ', ' . $overlay_gradientColor . ' ' . $overlay_gradientPos . '%, ' . $overlay_gradientColor2 . ' ' . $overlay_gradientPos2 . '%) transparent;
				}';
			} else {
				$row_styles .= '.sp-page-builder .page-content #' . $row_id . ' > .sppb-row-overlay {
					background: linear-gradient(' . $overlay_gradientDeg . 'deg, ' . $overlay_gradientColor . ' ' . $overlay_gradientPos . '%, ' . $overlay_gradientColor2 . ' ' . $overlay_gradientPos2 . '%) transparent;
				}';
			}
		}
		if (isset($options->pattern_overlay) && $options->pattern_overlay && $options->overlay_type == 'overlay_pattern') {
			if (is_object($options->pattern_overlay)) {
				if (strpos($options->pattern_overlay->src, "http://") !== false || strpos($options->pattern_overlay->src, "https://") !== false) {
					$row_styles .= '.sp-page-builder .page-content #' . $row_id . ' > .sppb-row-overlay {
							background-image:url(' . $options->pattern_overlay->src . ');
							background-attachment: scroll;
						}';
					if (isset($options->overlay_pattern_color)) {
						$row_styles .= '.sp-page-builder .page-content #' . $row_id . ' > .sppb-row-overlay {
								background-color:' . $options->overlay_pattern_color . ';
							}';
					}
				} else {
					$original_src = Uri::base(true) . '/' . $options->pattern_overlay->src;
					$row_styles .= '.sp-page-builder .page-content #' . $row_id . ' > .sppb-row-overlay {
							background-image:url(' . SppagebuilderHelperSite::cleanPath($original_src) . ');
							background-attachment: scroll;
						}';
					if (isset($options->overlay_pattern_color)) {
						$row_styles .= '.sp-page-builder .page-content #' . $row_id . ' > .sppb-row-overlay {
								background-color:' . $options->overlay_pattern_color . ';
							}';
					}
				}
			} else {
				if (strpos($options->pattern_overlay, "http://") !== false || strpos($options->pattern_overlay, "https://") !== false) {
					$row_styles .= '.sp-page-builder .page-content #' . $row_id . ' > .sppb-row-overlay {
							background-image:url(' . $options->pattern_overlay . ');
							background-attachment: scroll;
						}';
					if (isset($options->overlay_pattern_color)) {
						$row_styles .= '.sp-page-builder .page-content #' . $row_id . ' > .sppb-row-overlay {
								background-color:' . $options->overlay_pattern_color . ';
							}';
					}
				} else {
					$original_src = Uri::base(true) . '/' . $options->pattern_overlay;
					$row_styles .= '.sp-page-builder .page-content #' . $row_id . ' > .sppb-row-overlay {
							background-image:url(' . SppagebuilderHelperSite::cleanPath($original_src) . ');
							background-attachment: scroll;
						}';
					if (isset($options->overlay_pattern_color)) {
						$row_styles .= '.sp-page-builder .page-content #' . $row_id . ' > .sppb-row-overlay {
								background-color:' . $options->overlay_pattern_color . ';
							}';
					}
				}
			}
		}
	}
} else {
	if (!isset($options->overlay_type)) {
		$options->overlay_type = 'overlay_color';
	}
	if (isset($options->overlay) && $options->overlay && $options->overlay_type == 'overlay_color') {
		$row_styles .= '.sp-page-builder .page-content #' . $row_id . ' > .sppb-row-overlay {background-color: ' . $options->overlay . '}';
	}
	if (isset($options->gradient_overlay) && $options->gradient_overlay && $options->overlay_type == 'overlay_gradient') {
		$overlay_radialPos = (isset($options->gradient_overlay->radialPos) && !empty($options->gradient_overlay->radialPos)) ? $options->gradient_overlay->radialPos : 'center center';

		$overlay_gradientColor = (isset($options->gradient_overlay->color) && !empty($options->gradient_overlay->color)) ? $options->gradient_overlay->color : '';

		$overlay_gradientColor2 = (isset($options->gradient_overlay->color2) && !empty($options->gradient_overlay->color2)) ? $options->gradient_overlay->color2 : '';

		$overlay_gradientDeg = (isset($options->gradient_overlay->deg) && !empty($options->gradient_overlay->deg)) ? $options->gradient_overlay->deg : '0';

		$overlay_gradientPos = (isset($options->gradient_overlay->pos) && !empty($options->gradient_overlay->pos)) ? $options->gradient_overlay->pos : '0';

		$overlay_gradientPos2 = (isset($options->gradient_overlay->pos2) && !empty($options->gradient_overlay->pos2)) ? $options->gradient_overlay->pos2 : '100';

		if (isset($options->gradient_overlay->type) && $options->gradient_overlay->type == 'radial') {
			$row_styles .= '.sp-page-builder .page-content #' . $row_id . ' > .sppb-row-overlay {
				background: radial-gradient(at ' . $overlay_radialPos . ', ' . $overlay_gradientColor . ' ' . $overlay_gradientPos . '%, ' . $overlay_gradientColor2 . ' ' . $overlay_gradientPos2 . '%) transparent;
			}';
		} else {
			$row_styles .= '.sp-page-builder .page-content #' . $row_id . ' > .sppb-row-overlay {
				background: linear-gradient(' . $overlay_gradientDeg . 'deg, ' . $overlay_gradientColor . ' ' . $overlay_gradientPos . '%, ' . $overlay_gradientColor2 . ' ' . $overlay_gradientPos2 . '%) transparent;
			}';
		}
	}
	if (isset($options->pattern_overlay) && $options->pattern_overlay && $options->overlay_type == 'overlay_pattern') {
		if (is_object($options->pattern_overlay)) {
			if (strpos($options->pattern_overlay->src, "http://") !== false || strpos($options->pattern_overlay->src, "https://") !== false) {
				$row_styles .= '.sp-page-builder .page-content #' . $row_id . ' > .sppb-row-overlay {
						background-image:url(' . $options->pattern_overlay->src . ');
						background-attachment: scroll;
					}';
				if (isset($options->overlay_pattern_color)) {
					$row_styles .= '.sp-page-builder .page-content #' . $row_id . ' > .sppb-row-overlay {
							background-color:' . $options->overlay_pattern_color . ';
						}';
				}
			} else {
				$original_src = Uri::base(true) . '/' . $options->pattern_overlay->src;
				$row_styles .= '.sp-page-builder .page-content #' . $row_id . ' > .sppb-row-overlay {
						background-image:url(' . SppagebuilderHelperSite::cleanPath($original_src) . ');
						background-attachment: scroll;
					}';
				if (isset($options->overlay_pattern_color)) {
					$row_styles .= '.sp-page-builder .page-content #' . $row_id . ' > .sppb-row-overlay {
							background-color:' . $options->overlay_pattern_color . ';
						}';
				}
			}
		} else {
			if (strpos($options->pattern_overlay, "http://") !== false || strpos($options->pattern_overlay, "https://") !== false) {
				$row_styles .= '.sp-page-builder .page-content #' . $row_id . ' > .sppb-row-overlay {
						background-image:url(' . $options->pattern_overlay . ');
						background-attachment: scroll;
					}';
				if (isset($options->overlay_pattern_color)) {
					$row_styles .= '.sp-page-builder .page-content #' . $row_id . ' > .sppb-row-overlay {
							background-color:' . $options->overlay_pattern_color . ';
						}';
				}
			} else {
				$original_src = Uri::base(true) . '/' . $options->pattern_overlay;
				$row_styles .= '.sp-page-builder .page-content #' . $row_id . ' > .sppb-row-overlay {
						background-image:url(' . SppagebuilderHelperSite::cleanPath($original_src) . ');
						background-attachment: scroll;
					}';
				if (isset($options->overlay_pattern_color)) {
					$row_styles .= '.sp-page-builder .page-content #' . $row_id . ' > .sppb-row-overlay {
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
			$row_styles .= '.sp-page-builder .page-content #' . $row_id . ' > .sppb-row-overlay {
				mix-blend-mode:' . $options->blend_mode . ';
			}';
		}
	}
}

// Row Title
if ((isset($options->title) && $options->title) || (isset($options->subtitle) && $options->subtitle)) {
	if (!empty($options->title)) {
		$titleStyle = '';

		if (!empty($options->title_fontsize)) {
			$titleFontSize = AddonHelper::generateMultiDeviceObject($options, 'title_fontsize', ['font-size', 'line-height'], $device);
			$titleStyle .= $titleFontSize->$device;
		}

		if (!empty($options->title_position)) {
			$titleAlignment = CSSHelper::parseAlignment($options, 'title_position');
			$titleStyle .= 'text-align: ' . $titleAlignment . ';';
		}

		if (!empty($options->title_fontweight)) {
			$titleStyle .= 'font-weight: ' . $options->title_fontweight . ';';
		}

		if (!empty($options->title_text_color)) {
			$titleStyle .= 'color:' . $options->title_text_color . ';';
		}

		$titleMarginTop = AddonHelper::generateMultiDeviceObject($options, 'title_margin_top', 'margin-top', $device);
		$titleMarginBottom = AddonHelper::generateMultiDeviceObject($options, 'title_margin_bottom', 'margin-bottom', $device);

		$titleStyle .= $titleMarginTop->$device;
		$titleStyle .= $titleMarginBottom->$device;

		$titleSelector = '.sp-page-builder .page-content #' . $row_id . ' .sppb-section-title .sppb-title-heading';

		$titleFontSize = $titleFontSize ?? new stdClass;
		$titleStyleMedia = array_map(
			function ($size) use (
				$titleSelector,
				$titleFontSize,
				$titleMarginTop,
				$titleMarginBottom
			) {
				$str = AddonHelper::mediaQuery($size);
				$str .= $titleSelector . '{';
				$str .= $titleFontSize->$size ?? '';
				$str .= $titleMarginTop->$size ?? '';
				$str .= $titleMarginBottom->$size ?? '';
				$str .= '}';
				$str .= '}';

				return $str;
			},
			$deviceList
		);

		$titleStyleMedia = implode("\r\n", $titleStyleMedia);

		$row_styles .= !empty($titleStyle) ? '.sp-page-builder .page-content #' . $row_id . ' .sppb-section-title .sppb-title-heading {' . $titleStyle . '}' : '';
		$row_styles .= !empty($titleStyleMedia) ? $titleStyleMedia : '';
	}

	// Subtitle font size
	if (!empty($options->subtitle)) {
		$subTitleStyle = '';

		$subtitleFontSize = AddonHelper::generateMultiDeviceObject($options, 'subtitle_fontsize', 'font-size', $device);
		$subtitleSelector = '.sp-page-builder .page-content #' . $row_id . ' .sppb-section-title .sppb-title-subheading';

		if (!empty($options->title_position)) {
			$titleAlignment = CSSHelper::parseAlignment($options, 'title_position');
			$subTitleStyle .= 'text-align: ' . $titleAlignment . ';';
		}

		$subtitleStyleMedia = array_map(
			function ($size) use (
				$subtitleSelector,
				$subtitleFontSize
			) {
				$str = AddonHelper::mediaQuery($size);
				$str .= $subtitleSelector . '{';
				$str .= $subtitleFontSize->$size;
				$str .= '}';
				$str .= '}';

				return $str;
			},
			$deviceList
		);

		$subtitleStyleMedia = implode("\r\n", $subtitleStyleMedia);
		$row_styles .= '.sp-page-builder .page-content #' . $row_id . ' .sppb-section-title .sppb-title-subheading {' . $subtitleFontSize->$device . '}';
		$row_styles .= '.sp-page-builder .page-content #' . $row_id . ' .sppb-section-title .sppb-title-subheading {' . $subTitleStyle . '}';
		$row_styles .= !empty($subtitleStyleMedia) ? $subtitleStyleMedia : '';
	}
}

//container width
if (isset($options->fullscreen) && !$options->fullscreen && isset($options->container_width) && is_object($options->container_width) && isset($options->container_width->md) && $options->container_width->md != '') {
	$row_styles .= '@media (min-width: 1400px) {#' . $row_id . ' > .sppb-row-container { max-width:' . $options->container_width->md . 'px;}}';
} else if (isset($options->fullscreen) && !$options->fullscreen && isset($options->container_width) && !is_object($options->container_width)) {
	$row_styles .= '@media (min-width: 1400px) {#' . $row_id . ' > .sppb-row-container { max-width:' . $options->container_width . 'px;}}';
}

if (property_exists($options, 'no_gutter') && !$options->no_gutter) {
	if (!$options->no_gutter && isset($options->columns_gap) && is_object($options->columns_gap)) {
		$columnsGapMargin = AddonHelper::initDeviceObject();
		$columnsGapPadding = AddonHelper::initDeviceObject();
		$gapUnit = $options->columns_gap->unit;

		foreach ($columnsGapMargin as $key => $gaps) {
			if (!empty($options->columns_gap->$key)) {
				$gap = (int) $options->columns_gap->$key / 2;
				$columnsGapMargin->$key .= 'margin-left: -' . $gap . $gapUnit . ';';
				$columnsGapMargin->$key .= 'margin-right: -' . $gap . $gapUnit . ';';
				$columnsGapPadding->$key .= 'padding-left: ' . $gap . $gapUnit . ';';
				$columnsGapPadding->$key .= 'padding-right: ' . $gap . $gapUnit . ';';
			}
		}

		$rowContainerClass = (isset($options->fullscreen) && boolval($options->fullscreen)) ? ".sppb-container-inner" : ".sppb-row-container";
		$marginSelector = '#' . $row_id . ' > ' . $rowContainerClass . ' > .sppb-row ';
		$paddingSelector = '#' . $row_id . ' >  ' . $rowContainerClass . ' > .sppb-row > div';
		$columnsGapMarginStyle = $marginSelector . '{' . $columnsGapMargin->$device . '}';
		$columnsGapPaddingStyle = $paddingSelector . '{' . $columnsGapPadding->$device . '}';

		$columnsGapMedia = array_map(
			function ($size) use (
				$marginSelector,
				$paddingSelector,
				$columnsGapMargin,
				$columnsGapPadding
			) {
				$str = AddonHelper::mediaQuery($size);
				$str .= $marginSelector . '{';
				$str .= $columnsGapMargin->$size;
				$str .= '}';
				$str .= $paddingSelector . '{';
				$str .= $columnsGapPadding->$size;
				$str .= '}';
				$str .= '}';

				return $str;
			},
			$deviceList
		);

		$columnsGapMedia = implode("\r\n", $columnsGapMedia);


		$row_styles .= $columnsGapMarginStyle;
		$row_styles .= $columnsGapPaddingStyle;
		$row_styles .= $columnsGapMedia;

		// nested row
		$marginSelectorNested = '#' . $row_id . ' > .sppb-container-inner > .sppb-nested-row';
		$paddingSelectorNested = '#' . $row_id . ' > .sppb-container-inner > .sppb-nested-row > div';
		$columnsGapMarginStyleNested = $marginSelectorNested . '{' . $columnsGapMargin->$device . '}';
		$columnsGapPaddingStyleNested = $paddingSelectorNested . '{' . $columnsGapPadding->$device . '}';

		$columnsGapMediaNested = array_map(
			function ($size) use (
				$marginSelectorNested,
				$paddingSelectorNested,
				$columnsGapMargin,
				$columnsGapPadding
			) {
				$str = AddonHelper::mediaQuery($size);
				$str .= $marginSelectorNested . '{';
				$str .= $columnsGapMargin->$size;
				$str .= '}';
				$str .= $paddingSelectorNested . '{';
				$str .= $columnsGapPadding->$size;
				$str .= '}';
				$str .= '}';

				return $str;
			},
			$deviceList
		);

		$columnsGapMediaNested = implode("\r\n", $columnsGapMediaNested);


		$row_styles .= $columnsGapMarginStyleNested;
		$row_styles .= $columnsGapPaddingStyleNested;
		$row_styles .= $columnsGapMediaNested;
	}
}

echo $row_styles;
