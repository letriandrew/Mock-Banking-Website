<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http:   //www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http:   //www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;

SpAddonsConfig::addonConfig([
	'type'       => 'content',
	'addon_name' => 'lottie',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_LOTTIE'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_LOTTIE_DESC'),
	'category'   => 'Media',
	'icon'       => '<svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M2.1 27.6c0 1.2 1 2.2 2.2 2.2h23.2c1.2 0 2.2-1 2.2-2.2V4.4c0-1.2-1-2.2-2.2-2.2H4.4c-1.2 0-2.2 1-2.2 2.2v23.2h-.1zm2.3 4.2c-2.3 0-4.2-1.9-4.2-4.2V4.4C.2 2 2 .2 4.4.2h23.2c2.3 0 4.2 1.9 4.2 4.2v23.2c0 2.3-1.9 4.2-4.2 4.2H4.4z" fill="currentColor"/><path opacity=".5" d="M23.5 7c-4.2 0-6.6 4.2-8.85 8.25C12.85 18.7 11.05 22 8.5 22c-.9 0-1.5.6-1.5 1.5S7.6 25 8.5 25c4.2 0 6.6-4.2 8.85-8.25 1.8-3.45 3.6-6.75 6.15-6.75.9 0 1.5-.6 1.5-1.5S24.4 7 23.5 7z" fill="currentColor"/></svg>',
	'pro'=>true
]
);