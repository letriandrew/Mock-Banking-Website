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
    'type'       => 'general',
    'addon_name' => 'button_group',
    'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_BUTTON_GROUP'),
    'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_BUTTON_GROUP_DESC'),
    'category'   => 'Content',
    'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M10 13a1 1 0 011-1h10a1 1 0 110 2H11a1 1 0 01-1-1z" fill="currentColor"/><path fill-rule="evenodd" clip-rule="evenodd" d="M5 7c-1.648 0-3 1.352-3 3v8c0 1.648 1.352 3 3 3h3a1 1 0 110 2H5c-2.752 0-5-2.248-5-5v-8c0-2.752 2.248-5 5-5h22c2.752 0 5 2.248 5 5v8c0 2.752-2.248 5-5 5h-3a1 1 0 110-2h3c1.648 0 3-1.352 3-3v-8c0-1.648-1.352-3-3-3H5z" fill="currentColor"/><path opacity=".5" d="M16 17c-2.75 0-5 2.25-5 5s2.25 5 5 5 5-2.25 5-5-2.25-5-5-5zm2 5.625h-1.375V24c0 .375-.25.625-.625.625s-.625-.25-.625-.625v-1.375H14c-.375 0-.625-.25-.625-.625s.25-.625.625-.625h1.375V20c0-.375.25-.625.625-.625s.625.25.625.625v1.375H18c.375 0 .625.25.625.625s-.25.625-.625.625z" fill="currentColor"/></svg>',
    'pro'=>true
]
);