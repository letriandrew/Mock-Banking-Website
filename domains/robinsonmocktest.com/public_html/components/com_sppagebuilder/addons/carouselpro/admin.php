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
	'addon_name' => 'carouselpro',
	'category'   => 'Slider',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_CAROUSEL_ADVANCED'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_CAROUSEL_ADVANCED_DESC'),
	'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M27.9 19.5v-6.9c0-.5.5-.7.8-.4l3.1 3.5c.2.2.2.6 0 .8L28.7 20c-.3.2-.8-.1-.8-.5zM4.2 12.5v6.9c0 .5-.5.7-.8.4L.3 16.3c-.2-.2-.2-.6 0-.8L3.4 12c.3-.2.8.1.8.5z" fill="currentColor"/><path fill-rule="evenodd" clip-rule="evenodd" d="M23 5H9v22h14V5zM9 3c-1.1 0-2 .9-2 2v22c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2H9z" fill="currentColor"/><path fill-rule="evenodd" clip-rule="evenodd" d="M21 21c0 .6-.4 1-1 1h-8c-.6 0-1-.4-1-1s.4-1 1-1h8c.6 0 1 .4 1 1zM17 24c0 .6-.4 1-1 1h-4c-.6 0-1-.4-1-1s.4-1 1-1h4c.6 0 1 .4 1 1z" fill="currentColor"/><g opacity=".5" fill-rule="evenodd" clip-rule="evenodd" fill="currentColor"><path d="M25 12.2L20.9 16c-1.8 1.7-5 1.5-6.5-.4-.8-1-2.4-1.1-3.4-.2L8.6 18 7 16.8l2.5-2.5c1.8-1.8 5.1-1.7 6.6.3.8 1 2.4 1.1 3.3.2l4.1-3.8 1.5 1.2z"/><path d="M17.5 8c-.8 0-1.5.7-1.5 1.5s.7 1.5 1.5 1.5 1.5-.7 1.5-1.5S18.3 8 17.5 8zM14 9.5C14 7.6 15.6 6 17.5 6S21 7.6 21 9.5 19.4 13 17.5 13 14 11.4 14 9.5z"/></g></svg>',
	'pro'=>true
]
);