<?php

/**
 * @package SP Page Builder
 * @author JoomShaper https://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;

SpAddonsConfig::addonConfig([
	'type'       => 'content',
	'addon_name' => 'audio',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_AUDIO'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_AUDIO_DESC'),
	'category'   => 'Media',
	'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M31.6.3c.2.2.4.6.4.9v21.4c0 .7-.5 1.2-1.2 1.2s-1.2-.5-1.2-1.2v-20L12.3 5.5v20.3c0 .7-.5 1.2-1.2 1.2s-1.2-.5-1.2-1.2V4.5c0-.6.4-1.1 1-1.2L30.6 0c.3 0 .7.1 1 .3z" fill="currentColor"/><path fill-rule="evenodd" clip-rule="evenodd" d="M6.1 22.1c-2.1 0-3.7 1.7-3.7 3.7s1.7 3.7 3.7 3.7 3.7-1.7 3.7-3.7-1.6-3.7-3.7-3.7zM0 25.9c0-3.4 2.8-6.1 6.1-6.1s6.1 2.8 6.1 6.1S9.5 32 6.1 32C2.7 32 0 29.2 0 25.9zM25.9 18.9c-2.1 0-3.7 1.7-3.7 3.7 0 2.1 1.7 3.7 3.7 3.7s3.7-1.7 3.7-3.7c0-2.1-1.7-3.7-3.7-3.7zm-6.2 3.7c0-3.4 2.8-6.1 6.1-6.1s6.1 2.8 6.1 6.1-2.8 6.1-6.1 6.1-6.1-2.7-6.1-6.1z" fill="currentColor"/></svg>',
	'pro'=>true
]
);