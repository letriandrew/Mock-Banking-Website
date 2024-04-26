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
    'type'       => 'general',
    'addon_name' => 'navigation',
    'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_LINK_LIST'),
    'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_LINK_LIST_DESC'),
    'category'   => 'Content',
    'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M3 5.5A1.5 1.5 0 014.5 4h23a1.5 1.5 0 010 3h-23A1.5 1.5 0 013 5.5zM3 15.5A1.5 1.5 0 014.5 14h23a1.5 1.5 0 010 3h-23A1.5 1.5 0 013 15.5zM3 25.5A1.5 1.5 0 014.5 24h23a1.5 1.5 0 010 3h-23A1.5 1.5 0 013 25.5z" fill="currentColor"/></svg>',
    'pro'=>true
]
);