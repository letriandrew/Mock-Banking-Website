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
    'type' => 'content',
    'addon_name' => 'flip_box_pro',
    'title' => Text::_('COM_SPPAGEBUILDER_ADDON_FLIP_BOX_PRO'),
    'desc' => Text::_('COM_SPPAGEBUILDER_ADDON_FLIP_BOX_PRO_DESC'),
    'category' => 'Content',
    'icon' => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M27 3a3 3 0 00-3-3H8a3 3 0 00-3 3v10.188a1 1 0 102 0V3a1 1 0 011-1h16a1 1 0 011 1v26a1 1 0 01-1 1H8a1 1 0 01-1-1v-6.906a1 1 0 10-2 0V29a3 3 0 003 3h16a3 3 0 003-3V3z" fill="currentColor"/><path d="M18.332 21.539c.039 0 .079-.002.117-.006C26.428 20.835 32 17.32 32 12.987c0-1.338-.217-2.348-1.33-3.556a1.334 1.334 0 00-1.963 1.805c.548.595.626.908.626 1.75 0 2.758-4.882 5.345-11.116 5.89a1.334 1.334 0 00.115 2.663zM13.021 21.475a1.335 1.335 0 00.144-2.66c-6.28-.69-10.498-3.4-10.498-5.495 0-1.05.182-1.377.758-2.255a1.335 1.335 0 00-2.229-1.464C.452 10.736 0 11.551 0 13.32c0 3.9 5.415 7.325 12.873 8.147.05.005.099.008.148.008z" fill="currentColor"/><path d="M16.217 19.955l2.83-3.77a1 1 0 011.79.459l.94 6.6a1 1 0 01-1.59.94l-3.77-2.83a1 1 0 01-.2-1.399z" fill="currentColor"/></svg>',
    'pro'=>true
]
);