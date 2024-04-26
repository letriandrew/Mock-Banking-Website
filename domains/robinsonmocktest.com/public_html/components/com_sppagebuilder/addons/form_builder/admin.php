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
    'type' => 'repeatable',
    'addon_name' => 'form_builder',
    'title' => Text::_('COM_SPPAGEBUILDER_ADDON_FORM_BUILDER'),
    'desc' => Text::_('COM_SPPAGEBUILDER_ADDON_FORM_BUILDER_DESC'),
    'category' => 'Content',
    'icon' => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M2 4a4 4 0 014-4h17a4 4 0 014 4v13a1 1 0 11-2 0V4a2 2 0 00-2-2H6a2 2 0 00-2 2v22a2 2 0 002 2h8.5a1 1 0 110 2H6a4 4 0 01-4-4V4z" fill="currentColor"/><path fill-rule="evenodd" clip-rule="evenodd" d="M21 8a1 1 0 01-1 1H9a1 1 0 110-2h11a1 1 0 011 1zM21 13a1 1 0 01-1 1H9a1 1 0 110-2h11a1 1 0 011 1zM15 18a1 1 0 01-1 1H9a1 1 0 110-2h5a1 1 0 011 1z" fill="currentColor"/><path opacity=".5" d="M29.38 26.843l-1.166-.673a5.272 5.272 0 000-1.921l1.166-.674a.33.33 0 00.15-.383 6.816 6.816 0 00-1.497-2.589.33.33 0 00-.405-.063l-1.166.673a5.166 5.166 0 00-1.664-.96v-1.344a.328.328 0 00-.257-.32 6.881 6.881 0 00-2.989 0 .328.328 0 00-.257.32v1.346a5.328 5.328 0 00-1.664.961l-1.163-.673a.325.325 0 00-.405.063 6.775 6.775 0 00-1.498 2.589.327.327 0 00.15.383l1.167.673a5.276 5.276 0 000 1.922l-1.166.673a.33.33 0 00-.15.383 6.817 6.817 0 001.497 2.59.33.33 0 00.405.063l1.166-.674c.49.422 1.053.747 1.664.96v1.348c0 .153.106.287.257.32a6.88 6.88 0 002.989 0 .328.328 0 00.257-.32v-1.347a5.328 5.328 0 001.664-.96l1.166.672a.325.325 0 00.405-.062 6.776 6.776 0 001.497-2.59.338.338 0 00-.153-.386zm-6.333.556a2.193 2.193 0 01-2.19-2.19c0-1.207.983-2.19 2.19-2.19 1.207 0 2.19.983 2.19 2.19 0 1.207-.983 2.19-2.19 2.19z" fill="currentColor"/></svg>',
    'pro'=>true
]
);