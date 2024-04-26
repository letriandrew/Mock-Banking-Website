<?php

/**
* @package SP Page Builder
* @author JoomShaper https://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2023 JoomShaper
* @license https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct access
defined ('_JEXEC') or die ('Restricted access');

use Joomla\CMS\Language\Text;

SpAddonsConfig::addonConfig([
	'type'       => 'content',
	'addon_name' => 'animated_heading',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_ANIMATED_HEADING'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_ANIMATED_HEADING_DESC'),
	'category'   => 'General',
	'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path opacity=".5" d="M18.2 12c-2.1.3-4.1 2.1-5.5 4.4C10.8 11.3 7.1 7 2.5 7c-.4 0-.8 0-1.1.1-.8.1-1.4.8-1.4 1.6 0 1 .9 1.7 1.9 1.6.2-.1.4-.1.6-.1 4.3 0 8.2 7 8.2 13.1 0 .8.6 1.5 1.3 1.6 1.1.2 2-.6 2-1.6 0-3.1 2.3-7.3 4.3-8-.1-.6-.2-1.2-.2-1.8 0-.6 0-1 .1-1.5z" fill="currentColor"/><path d="M26.3 19.3c3.2 0 5.7-2.6 5.7-5.7s-2.6-5.7-5.7-5.7-5.7 2.6-5.7 5.7 2.5 5.7 5.7 5.7z" fill="currentColor"/></svg>',
	'pro'=>true
]
);