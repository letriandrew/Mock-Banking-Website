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
use Joomla\CMS\Component\ComponentHelper;

$params   = ComponentHelper::getParams('com_sppagebuilder');
$gmap_api = $params->get('gmap_api', '');

SpAddonsConfig::addonConfig([
	'type'       => 'content',
	'addon_name' => 'gmap',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_GMAP'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_GMAP_DESC'),
	'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><g opacity=".5" fill="currentColor"><path d="M16.238 15.429a4.268 4.268 0 01-4.286-4.286c0-1.62.953-2.762.953-2.762l-5.62 1.524L6.3 16.7c1.238 2.762 3.367 4.92 4.89 7.014L19.5 14c-.381.381-1.643 1.429-3.262 1.429z"/><path d="M19.8.7l-6.893 2.744v4.952s1.238-1.524 3.334-1.524c2.19 0 4.285 1.81 4.285 4.286 0 1.143-.74 2.461-1.026 2.842l6.74-8.08C24.718 3.253 22.563 1.652 19.8.7z"/></g><path d="M26.238 5.905l-15.047 17.81c.952 1.237 2 2.761 2.57 3.714.668 1.238.953 2.095 1.43 3.523.285.857.57 1.048 1.142 1.048s.858-.381 1.143-1.048c.476-1.428.762-2.476 1.334-3.428 1.047-1.905 2.476-3.62 3.714-5.334.38-.476 2.666-3.238 3.714-5.333 0 0 1.238-2.38 1.238-5.714 0-3.048-1.238-5.238-1.238-5.238zM12.905 8.38L19.8.701S18.143 0 16.143 0c-4 0-6.857 2-8.572 4C6.143 5.619 5 8.19 5 11.143 5 14.286 6.3 16.7 6.3 16.7l6.605-8.319z" fill="currentColor"/></svg>',
	'pro'=>true
]
);