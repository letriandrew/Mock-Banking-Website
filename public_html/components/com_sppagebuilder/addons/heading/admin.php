<?php

/**
 * @package SP Page Builder
 * @author JoomShaper https://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;

SpAddonsConfig::addonConfig([
	'type'       => 'content',
	'addon_name' => 'heading',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_HEADING'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_HEADING_DESC'),
	'category'   => 'General',
	'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M18.631 30v-1.648h.517c.445 0 .86-.032 1.248-.094.39-.065.727-.197 1.012-.394.286-.196.516-.482.688-.851.172-.37.257-.862.257-1.477v-9.19H9.65v9.19c0 .614.085 1.105.256 1.477.172.369.401.655.688.851.286.199.625.328 1.02.394.395.063.807.095 1.24.095h.517V30H2v-1.647h.497c.444 0 .86-.032 1.249-.095a2.524 2.524 0 001.021-.394c.292-.198.52-.482.688-.85.165-.37.246-.864.246-1.478V6.31c0-.574-.086-1.036-.257-1.388a2.007 2.007 0 00-.698-.814 2.506 2.506 0 00-1.022-.374 8.227 8.227 0 00-1.228-.086H2V2h11.37v1.647h-.517a7.68 7.68 0 00-1.24.096 2.456 2.456 0 00-1.02.393c-.286.197-.515.481-.688.852-.171.37-.256.862-.256 1.475v7.927H22.35V6.463c0-.614-.085-1.105-.256-1.476-.171-.37-.401-.655-.688-.851a2.452 2.452 0 00-1.012-.393 7.601 7.601 0 00-1.249-.096h-.516V2H30v1.647h-.496c-.445 0-.86.031-1.249.096a2.529 2.529 0 00-1.021.393c-.292.197-.52.481-.687.851-.167.37-.248.862-.248 1.476v19.265c0 .575.087 1.037.257 1.388.171.352.405.617.697.794.291.18.63.297 1.02.356.39.058.799.086 1.23.086H30V30H18.631z" fill="currentColor"/></svg>',
	'pro'=>true
]
);