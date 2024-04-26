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
	'addon_name' => 'person',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_PERSON'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_PERSON_DESC'),
	'category'   => 'Content',
	'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path opacity=".5" d="M19.418 14.634h-6.836C9.504 14.634 7 16.772 7 19.4v9.648c0 .526.5.953 1.116.953h15.768C24.5 30 25 29.573 25 29.047v-9.648c0-2.627-2.504-4.765-5.582-4.765z" fill="currentColor"/><path d="M16 2c-3.23 0-5.86 2.57-5.86 5.73 0 2.144 1.21 4.016 2.997 4.998a5.925 5.925 0 002.863.733c1.04 0 2.016-.267 2.863-.733 1.787-.982 2.996-2.854 2.996-4.998C21.86 4.57 19.231 2 16 2z" fill="currentColor"/></svg>',
	'pro'=>true
]
);