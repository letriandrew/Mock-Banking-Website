<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;

$options = $displayData['options'];
$custom_class  = (isset($options->class)) ? ' ' . $options->class : '';
$hiddenClass = '';
$data_attr = '';
$doc = Factory::getDocument();

//Image lazy load
$config = ComponentHelper::getParams('com_sppagebuilder');
$lazyload = $config->get('lazyloadimg', '0');
$background_image = (isset($options->background_image) && $options->background_image) ? $options->background_image : '';
$background_image_src = isset($background_image->src) ? $background_image->src : $background_image;

if ($lazyload && $background_image_src)
{
	if ($options->background_type == 'image')
	{
		$custom_class .= ' sppb-element-lazy';
	}
}

// Responsive
if (isset($options->sm_col) && $options->sm_col)
{
	$options->cssClassName .= ' sppb-' . $options->sm_col;
}

if (isset($options->xs_col) && $options->xs_col)
{
	$options->cssClassName .= ' sppb-' . $options->xs_col;
}


if (isset($options->items_align_center) && $options->items_align_center)
{
	$options->cssClassName .= ' sppp-column-vertical-align';
}
//Column order
$column_order = '';
if (isset($options->tablet_order_landscape) && $options->tablet_order_landscape)
{
	$column_order .= ' sppb-order-lg-' . $options->tablet_order_landscape;
}
if (isset($options->tablet_order) && $options->tablet_order)
{
	$column_order .= ' sppb-order-md-' . $options->tablet_order;
}
if (isset($options->mobile_order_landscape) && $options->mobile_order_landscape)
{
	$column_order .= ' sppb-order-sm-' . $options->mobile_order_landscape;
}
if (isset($options->mobile_order) && $options->mobile_order)
{
	$column_order .= ' sppb-order-xs-' . $options->mobile_order;
}

// Visibility

if (isset($options->hidden_xl) && $options->hidden_xl)
{
	$hiddenClass .= ' sppb-hidden-xl';
}

if (isset($options->hidden_lg) && $options->hidden_lg)
{
	$hiddenClass .= ' sppb-hidden-lg';
}

if (isset($options->hidden_md) && $options->hidden_md)
{
	$hiddenClass .= ' sppb-hidden-md';
}

if (isset($options->hidden_sm) && $options->hidden_sm)
{
	$hiddenClass .= ' sppb-hidden-sm';
}

if (isset($options->hidden_xs) && $options->hidden_xs)
{
	$hiddenClass .= ' sppb-hidden-xs';
}

if (isset($options->items_content_alignment) && ($options->items_content_alignment == 'top' || $options->items_content_alignment == 'start'))
{
	$custom_class .= (isset($options->items_align_center) && $options->items_align_center) ?  ' sppb-align-items-top' : '';
}
else if (isset($options->items_content_alignment) && ($options->items_content_alignment == 'bottom' || $options->items_content_alignment == 'end'))
{
	$custom_class .= (isset($options->items_align_center) && $options->items_align_center) ?  ' sppb-align-items-bottom' : '';
}
else
{
	$custom_class .= (isset($options->items_align_center) && $options->items_align_center) ?  ' sppb-align-items-center' : '';
}

// Animation
$hasEnableAnimationProperty = property_exists($options, 'enable_animation');
$isAnimationEnabled = false;

if ($hasEnableAnimationProperty)
{
	$isAnimationEnabled = !empty($options->enable_animation) && !empty($options->animation);
}
else
{
	$isAnimationEnabled = !empty($options->animation);
}

if ($isAnimationEnabled)
{

	$custom_class .= ' sppb-wow ' . $options->animation;

	if (!empty($options->animationduration))
	{
		$data_attr .= ' data-sppb-wow-duration="' . $options->animationduration . 'ms"';
	}

	if (!empty($options->animationdelay))
	{
		$data_attr .= ' data-sppb-wow-delay="' . $options->animationdelay . 'ms"';
	}
}

$html  = '';
$html .= '<div class="sppb-' . $options->cssClassName . ' ' . $hiddenClass . ' ' . $column_order . '" id="column-wrap-id-' . $options->dynamicId . '">';
$html .= '<div id="column-id-' . $options->dynamicId . '" class="sppb-column ' . $custom_class . '" ' . $data_attr . '>';

if ($background_image_src)
{
	if (isset($options->overlay_type) && $options->overlay_type !== 'overlay_none')
	{
		$html .= '<div class="sppb-column-overlay"></div>';
	}
}

$html .= '<div class="sppb-column-addons">';

echo $html;
