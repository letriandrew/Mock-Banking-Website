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
    'addon_name' => 'pie_progress',
    'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_PIE_PROGRESS'),
    'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_PIE_PROGRESS_DESC'),
    'category'   => 'Content',
    'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M15.7 19.7c-1.7 0-3-1.3-3-3V7.6C7.3 8.1 3 12.7 3 18.3 3 24.2 7.8 29 13.7 29c2.8 0 5.4-1.1 7.5-3 1.7-1.7 2.8-3.9 3.2-6.2h-8.7v-.1zm10.7-1c-.1 3.3-1.5 6.4-3.9 8.7-2.4 2.3-5.5 3.6-8.9 3.6C6.7 31 1 25.3 1 18.3c0-7 5.7-12.7 12.7-12.7.5 0 1 .4 1 1v10.2c0 .6.4 1 1 1h9.8c.3 0 .5.1.7.3.2.1.2.4.2.6z" fill="currentColor"/><path opacity=".5" d="M17.8 1c-.5 0-1 .4-1 1v12.2c0 .5.4 1 1 1h11.8c.5 0 .9-.4 1-.9v-.4C30.5 6.7 24.8 1 17.8 1z" fill="currentColor"/></svg>',
    'pro'=>true
]
);