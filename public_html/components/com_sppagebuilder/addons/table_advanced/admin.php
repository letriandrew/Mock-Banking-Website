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
	'type'       => 'repeatable',
	'addon_name' => 'table_advanced',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_TABLE_ADVANCED'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_TABLE_ADVANCED_DESC'),
	'category'   => 'Content',
	'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.27 4.27A4.333 4.333 0 014.332 3h23.334A4.333 4.333 0 0132 7.333v17.334A4.333 4.333 0 0127.667 29H4.333A4.333 4.333 0 010 24.667V7.333C0 6.184.457 5.082 1.27 4.27zM2 13.666v5.666h13v-5.666H2zm15 0v5.666h13v-5.666H17zm13-2V7.333A2.333 2.333 0 0027.667 5H4.333A2.333 2.333 0 002 7.333v4.334h28zm0 9.666H17V27L27.667 27A2.333 2.333 0 0030 24.667v-3.334zM15 27v-5.666H2v3.334A2.333 2.333 0 004.333 27H15z" fill="currentColor"/></svg>',
	'pro'=>true
]
);