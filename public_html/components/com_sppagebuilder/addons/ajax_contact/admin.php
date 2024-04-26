<?php

/**
 * @package SP Page Builder
 * @author JoomShaper https://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;

SpAddonsConfig::addonConfig([
    'type'       => 'content',
    'addon_name' => 'ajax_contact',
    'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_AJAX_CONTACT'),
    'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_AJAX_CONTACT_DESC'),
    'category'   => 'Content',
    'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M10 30V2h3a1 1 0 100-2H5a1 1 0 100 2h3v28H5a1 1 0 100 2h8a1 1 0 100-2h-3z" fill="currentColor"/><g opacity=".5" fill="currentColor"><path d="M5 4a1 1 0 110 2H2v20h3a1 1 0 110 2H2a2 2 0 01-2-2V6a2 2 0 012-2h3zM30 4a2 2 0 012 2v20a2 2 0 01-2 2H13a1 1 0 110-2h17V6H13a1 1 0 110-2h17z"/></g></svg>',
    'pro'=>true
]
);