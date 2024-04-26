<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('Restricted access');

$addon_id = $displayData['addon_id'];
$id = $displayData['id'];
$options = $displayData['options'];
$css = '';

$cssHelper = new CSSHelper($addon_id);

$btn_style = (isset($options->button_type) && $options->button_type) ? $options->button_type : '';
$appearance = (isset($options->button_appearance) && $options->button_appearance) ? $options->button_appearance : '';

$custom_style = '';
$custom_style_sm = '';
$custom_style_xs = '';

$customProps = [];
$customUnits = [];

$hoverProps = [];
$hoverUnits = [];

if ($btn_style === 'custom')
{
	$hoverProps = [
		'button_background_color_hover' => ['background-color'],
		'button_color_hover' => 'color'
	];
	$hoverUnits = ['button_background_color_hover' => false, 'button_color_hover' => false];
}

if ($appearance === 'outline')
{
	$customProps = [
		'button_background_color' => 'background-color: transparent; border-color',
		'button_border_width' => 'border-width'
	];
	$customUnits = ['button_background_color' => false];

	$hoverProps['button_background_color_hover'][] = 'background-color;border-color';
}
elseif ($appearance === 'gradient')
{
	$options->button_background_gradient = CSSHelper::parseColor($options, 'button_background_gradient');
	$customProps = [
		'button_background_gradient' => 'border:none; background-image'
	];
	$customUnits = ['button_background_gradient' => false];

	$options->button_background_gradient_hover = CSSHelper::parseColor($options, 'button_background_gradient_hover');
	$hoverProps['button_background_gradient_hover'] = 'border:none; background-image';
	$hoverUnits['button_background_gradient_hover'] = false;
}
else
{
	$customProps = [
		'button_background_color' => 'background-color'
	];
	$customUnits = ['button_background_color' => false];
}

$customProps['button_color'] = 'color';
$customUnits['button_color'] = false;

$buttonProps = ['button_padding' => 'padding'];
$buttonUnits = ['button_padding' => false];

$buttonStyle = $cssHelper->generateStyle('#' . $id . '.sppb-btn-' . $btn_style, $options, $buttonProps, $buttonUnits);
$customStyle = $cssHelper->generateStyle('#' . $id . '.sppb-btn-custom', $options, $customProps, $customUnits, ['button_padding' => 'spacing']);
$hoverStyle = $cssHelper->generateStyle('#' . $id . '.sppb-btn-custom:hover', $options, $hoverProps, $hoverUnits);

$fallback = [
	'font'           => 'font_family',
	'size'           => 'fontsize',
	'letter_spacing' => 'button_letterspace',
	'uppercase'      => 'button_font_style.uppercase',
	'italic'         => 'button_font_style.italic',
	'underline'      => 'button_font_style.underline',
	'weight'         => 'button_font_style.weight',
];

$buttonTypography = $cssHelper->typography('#' . $id . '.sppb-btn-' . $btn_style, $options, 'button_typography', $fallback);

$linkButtonStyle = $cssHelper->generateStyle(
	' #' . $id . '.sppb-btn-link',
	$options,
	[
		'link_button_color' => 'color',
		'link_border_color' => 'border-color',
		'link_button_border_width' => 'border-width: 0 0 %spx 0',
		'link_button_padding_bottom' => 'padding: 0 0 %spx 0'
	],
	[
		'link_button_color' => false,
		'link_border_color' => false,
		'link_button_border_width' => false,
		'link_button_padding_bottom' => false,
	],
	null,
	null,
	false,
	'text-decoration:none;border-radius: 0;'
);

$linkHoverStyle = $cssHelper->generateStyle(
	' #' . $id . '.sppb-btn-link:hover, ' . '#' . $id . '.sppb-btn-link:focus',
	$options,
	[
		'link_button_hover_color' => 'color',
		'link_button_border_hover_color' => 'border-color'
	],
	false
);

$css .= $buttonStyle;
$css .= $buttonTypography;
$css .= $btn_style === 'custom' ? $customStyle : '';
$css .= $hoverStyle;
$css .= $btn_style === 'link' ? $linkButtonStyle . $linkHoverStyle : '';

echo $css;