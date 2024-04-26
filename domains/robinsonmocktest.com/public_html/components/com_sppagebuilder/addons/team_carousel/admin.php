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
	'addon_name' => 'team_carousel',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_TEAM_CAROUSEL'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_TEAM_CAROUSEL_DESC'),
	'category'   => 'Slider',
	'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><g opacity=".5" fill="currentColor"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 30.8c0-.6.4-1 1-1h6c.6 0 1 .4 1 1s-.4 1-1 1h-6c-.6 0-1-.4-1-1z"/><path d="M17.9 13.3h-3.8c-1.7 0-3.1 1.2-3.1 2.7v5.5c0 .3.3.5.6.5h8.8c.3 0 .6-.2.6-.5V16c0-1.4-1.4-2.7-3.1-2.7z"/></g><path fill-rule="evenodd" clip-rule="evenodd" d="M23 2.2H9V21h14V2.2zM9 .1C7.9.1 7 1 7 2.2V21c0 1.2.9 2.1 2 2.1h14c1.1 0 2-.9 2-2.1V2.2C25 1 24.1.1 23 .1H9zM9 26.8c0-.6.4-1 1-1h12c.6 0 1 .4 1 1s-.4 1-1 1H10c-.6 0-1-.4-1-1z" fill="currentColor"/><path d="M27.8 14.5v-7c0-.5.5-.7.8-.4l3.1 3.5c.2.2.2.6 0 .8l-3.1 3.5c-.3.3-.8 0-.8-.4zM4.1 7.5v6.9c0 .5-.5.7-.8.4L.2 11.3c-.2-.2-.2-.6 0-.8L3.3 7c.3-.2.8.1.8.5zM16 6.1c-1.8 0-3.3 1.5-3.3 3.3 0 1.2.7 2.3 1.7 2.9.5.3 1 .4 1.6.4.6 0 1.1-.2 1.6-.4 1-.6 1.7-1.6 1.7-2.9 0-1.8-1.5-3.3-3.3-3.3z" fill="currentColor"/></svg>',
	'pro'=>true
]
);