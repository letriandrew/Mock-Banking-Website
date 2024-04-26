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
	'addon_name' => 'countdown',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_COUTNDOWN'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_COUTNDOWN_DESC'),
	'category'   => 'Content',
	'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M16.853 4.627V2.285h1.142a1.142 1.142 0 100-2.285h-4.57a1.143 1.143 0 000 2.285h1.143v2.342a13.596 13.596 0 00-7.7 3.199l-.972-.971.343-.331A1.147 1.147 0 004.617 4.9L2.332 7.186A1.147 1.147 0 003.954 8.81l.332-.343.97.97a13.71 13.71 0 1011.597-4.809zM15.71 29.704a11.424 11.424 0 110-22.848 11.424 11.424 0 010 22.848z" fill="currentColor"/><path opacity=".5" d="M24.998 17.98c0-4.299-3.113-7.897-7.299-8.775-1.08-.227-1.986.69-1.986 1.795v5.575a2 2 0 01-1.323 1.882l-5.478 1.97c-1.051.379-1.605 1.564-.982 2.49 2.309 3.438 6.864 4.996 10.995 3.536 3.697-1.313 6.16-4.702 6.073-8.472z" fill="currentColor"/></svg>',
	'pro'=>true
]
);