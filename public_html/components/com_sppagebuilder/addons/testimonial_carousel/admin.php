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
    'type' => 'repeatable',
    'addon_name' => 'testimonial_carousel',
    'title' => Text::_('COM_SPPAGEBUILDER_ADDON_TESTIMONIAL_CAROUSEL'),
    'desc' => Text::_('COM_SPPAGEBUILDER_ADDON_TESTIMONIAL_CAROUSEL_DESC'),
    'category' => 'Slider',
    'icon' => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><circle opacity=".5" cx="10.5" cy="30.5" r="1.5" fill="currentColor"/><circle opacity=".5" cx="22.5" cy="30.5" r="1.5" fill="currentColor"/><circle cx="16.5" cy="30.5" r="1.5" fill="currentColor"/><path fill-rule="evenodd" clip-rule="evenodd" d="M22 2H10v21h12V2zM10 0a2 2 0 00-2 2v21a2 2 0 002 2h12a2 2 0 002-2V2a2 2 0 00-2-2H10z" fill="currentColor"/><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M6 4a1 1 0 00-1-1H1a1 1 0 000 2h3v15H1a1 1 0 100 2h4a1 1 0 001-1V4zm20 17a1 1 0 001 1h4a1 1 0 100-2h-3V5h3a1 1 0 100-2h-4a.996.996 0 00-1 1v17z" fill="currentColor"/><path d="M13 7.496c0 .381.309.69.69.69h.779v.297c0 .527-.364.975-.858 1.097-.148.037-.273.157-.273.31v.848c0 .152.124.277.275.26a2.538 2.538 0 002.256-2.515V5.69a.69.69 0 00-.69-.69h-1.49a.69.69 0 00-.689.69v1.806zM16.6 7.496c0 .381.309.69.69.69h.779v.297c0 .527-.364.975-.858 1.097-.148.037-.273.157-.273.31v.848c0 .152.124.277.275.26a2.538 2.538 0 002.256-2.515V5.69a.69.69 0 00-.69-.69h-1.49a.69.69 0 00-.689.69v1.806z" fill="currentColor"/><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M12 15a1 1 0 011-1h6a1 1 0 110 2h-6a1 1 0 01-1-1zM13 19a1 1 0 011-1h4a1 1 0 110 2h-4a1 1 0 01-1-1z" fill="currentColor"/></svg>',
    'pro'=>true
]
);