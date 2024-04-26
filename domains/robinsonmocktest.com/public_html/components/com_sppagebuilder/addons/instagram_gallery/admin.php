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

SpAddonsConfig::addonConfig(
	[
		'type'       => 'content',
		'addon_name' => 'instagram_gallery',
		'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_INSTAGRAM_GALLERY'),
		'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_INSTAGRAM_GALLERY_DESC'),
		'category'   => 'Media',
		'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M8.5 2A6.5 6.5 0 002 8.5v15A6.5 6.5 0 008.5 30h15a6.5 6.5 0 006.5-6.5v-15A6.5 6.5 0 0023.5 2h-15zM0 8.5A8.5 8.5 0 018.5 0h15A8.5 8.5 0 0132 8.5v15a8.5 8.5 0 01-8.5 8.5h-15A8.5 8.5 0 010 23.5v-15z" fill="currentColor"/><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M16.798 10.99a5 5 0 10-1.466 9.892 5 5 0 001.466-9.893zm-3.957-1.268a7 7 0 116.448 12.426A7 7 0 0112.84 9.722zM23.25 7.75a1 1 0 011-1h.016a1 1 0 110 2h-.016a1 1 0 01-1-1z" fill="currentColor"/></svg>',
		'pro'=>true
]
);