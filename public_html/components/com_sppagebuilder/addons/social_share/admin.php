<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;

SpAddonsConfig::addonConfig([
    'type'       => 'content',
    'addon_name' => 'social_share',
    'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_SOCIAL_SHARE'),
    'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_SOCIAL_SHARE_DESC'),
    'category'   => 'Media',
    'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M24.5 2a3.5 3.5 0 100 7 3.5 3.5 0 000-7zM19 5.5a5.5 5.5 0 1111 0 5.5 5.5 0 01-11 0zM6.5 12.5a3.5 3.5 0 100 7 3.5 3.5 0 000-7zM1 16a5.5 5.5 0 1111 0 5.5 5.5 0 01-11 0zM24.5 23a3.5 3.5 0 100 7 3.5 3.5 0 000-7zM19 26.5a5.5 5.5 0 1111 0 5.5 5.5 0 01-11 0z" fill="currentColor"/><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M9.521 17.762a1 1 0 011.367-.361l10.246 5.97a1 1 0 01-1.007 1.728l-10.245-5.97a1 1 0 01-.361-1.367zM21.479 7.261a1 1 0 01-.36 1.368l-10.23 5.97a1 1 0 01-1.008-1.728l10.23-5.97a1 1 0 011.368.36z" fill="currentColor"/></svg>',
    'pro'=>true
]
);