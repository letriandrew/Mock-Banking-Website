<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;

SpAddonsConfig::addonConfig([
    'type' => 'content',
    'addon_name' => 'image_layouts',
    'title' => Text::_('COM_SPPAGEBUILDER_ADDON_IMAGE_LAYOUT'),
    'desc' => Text::_('COM_SPPAGEBUILDER_ADDON_IMAGE_LAYOUT_DESC'),
    'category' => 'Media',
    'icon' => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M13.6.1H.7C.4.1.2.3.2.6v12.9c0 .3.2.5.5.5h12.9c.3 0 .5-.2.5-.5V.7c0-.3-.2-.6-.5-.6zM9.8 2.8c.9 0 1.6.7 1.6 1.6 0 .9-.7 1.6-1.6 1.6-.9 0-1.6-.7-1.6-1.6 0-.9.7-1.6 1.6-1.6zm2.1 8.9c-.1.2-.3.3-.5.3H2.8c-.2 0-.3-.1-.4-.2-.1-.1-.1-.3-.1-.5l2.2-6.5c0-.2.2-.4.5-.4.2 0 .4.1.5.3l1.8 3.6 1.6-1.6c.1-.1.3-.1.5-.1s.3.1.4.3l2.2 4.3c0 .2 0 .4-.1.5z" fill="currentColor"/><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M31 18H1c-.6 0-1 .4-1 1s.4 1 1 1h30c.6 0 1-.4 1-1s-.5-1-1-1zM31 24H1c-.6 0-1 .4-1 1s.4 1 1 1h30c.6 0 1-.4 1-1s-.5-1-1-1zM31 10H19c-.6 0-1 .4-1 1s.4 1 1 1h12c.6 0 1-.4 1-1s-.5-1-1-1zM13 30H1c-.6 0-1 .4-1 1s.4 1 1 1h12c.6 0 1-.4 1-1s-.4-1-1-1zM19 5h12c.6 0 1-.4 1-1s-.4-1-1-1H19c-.6 0-1 .4-1 1s.4 1 1 1z" fill="currentColor"/></svg>',
    'pro'=>true
]
);