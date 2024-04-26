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
	'addon_name' => 'image_carousel',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_IMAGE_CAROUSEL'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_IMAGE_CAROUSEL_DESC'),
	'category'   => 'Slider',
	'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M22 5H10v21h12V5zM10 3a2 2 0 00-2 2v21a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H10z" fill="currentColor"/><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M23.721 17.693l-3.493 3.636a3.693 3.693 0 01-5.69-.443 1.693 1.693 0 00-2.66-.148L9.752 23.16 8.25 21.84l2.127-2.422a3.693 3.693 0 015.801.322 1.693 1.693 0 002.608.203l3.494-3.636 1.442 1.386zM17.5 9a1.5 1.5 0 100 3 1.5 1.5 0 000-3zM14 10.5a3.5 3.5 0 117 0 3.5 3.5 0 01-7 0zM6 7a1 1 0 00-1-1H1a1 1 0 000 2h3v15H1a1 1 0 100 2h4a1 1 0 001-1V7zm20 17a1 1 0 001 1h4a1 1 0 100-2h-3V8h3a1 1 0 100-2h-4a.996.996 0 00-1 1v17z" fill="currentColor"/></svg>',
	'pro'=>true
]
);