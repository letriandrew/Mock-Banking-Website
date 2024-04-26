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
	'addon_name' => 'flickr',
	'category'   => 'Social',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_FLICKR'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_FLICKR_DESC'),
	'category'   => 'Media',
	'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M7.5 21.7a5.2 5.2 0 100-10.4 5.2 5.2 0 000 10.4zm0 2.3a7.5 7.5 0 100-15 7.5 7.5 0 000 15z" fill="currentColor"/><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M24.5 21.7a5.2 5.2 0 100-10.4 5.2 5.2 0 000 10.4zm0 2.3a7.5 7.5 0 100-15 7.5 7.5 0 000 15z" fill="currentColor"/></svg>',
	'pro'=>true
]
);