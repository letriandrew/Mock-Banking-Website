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
	'addon_name' => 'modal',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_MODAL'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_MODAL_DESC'),
	'category'   => 'General',
	'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M10.294 24.88l4.722 4.752c.73.49 1.663.49 2.394 0l4.721-4.752h5.616c1.138 0 2.253-1.021 2.253-2.48V4.528C29.916 3.008 28.759 2 27.641 2H4.252C3.115 2 2 3.021 2 4.48V22.4c0 1.459 1.115 2.48 2.252 2.48h6.042zM32 4.48V22.4c0 2.464-1.914 4.48-4.253 4.48h-4.784l-4.252 4.28a4.135 4.135 0 01-4.997 0l-4.252-4.28h-5.21C1.914 26.88 0 24.864 0 22.4V4.48C0 2.016 1.914 0 4.252 0h23.39C29.98 0 31.893 2.016 32 4.48z" fill="currentColor"/><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M27 10.5a1.5 1.5 0 01-1.5 1.5h-19a1.5 1.5 0 010-3h19a1.5 1.5 0 011.5 1.5zM23 16.5a1.5 1.5 0 01-1.5 1.5h-11a1.5 1.5 0 010-3h11a1.5 1.5 0 011.5 1.5z" fill="currentColor"/></svg>',
	'pro'=>true
]
);