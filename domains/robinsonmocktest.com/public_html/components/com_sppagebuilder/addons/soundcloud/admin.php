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
    'addon_name' => 'soundcloud',
    'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_SOUNDCLOUD'),
    'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_SOUNDCLOUD_DESC'),
    'category'   => 'Media',
    'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M31.536 17.71c-.7-1.74-2.752-3.263-5.112-3.296a9.442 9.442 0 00-3.266-5.41 8.847 8.847 0 00-6.619-1.946.999.999 0 00-.888.993v16.61a1.01 1.01 0 001.006 1.005h9.86c3.791 0 6.638-3.94 5.02-7.955zm-5.02 5.957h-8.863V9.002a6.881 6.881 0 014.246 1.554 7.448 7.448 0 012.687 5.048c.057.6.63 1.01 1.207.883 1.84-.399 3.465.915 3.891 1.972 1.08 2.688-.78 5.208-3.168 5.208zM13.74 24.667V10.564c0-1.322-2-1.324-2 0v14.103c0 1.322 2 1.323 2 0z" fill="currentColor"/><path opacity=".5" d="M9.825 24.667V14.41c0-1.323-2-1.324-2 0v10.256c0 1.322 2 1.323 2 0z" fill="currentColor"/><path d="M5.913 24.667V14.41c0-1.323-2-1.324-2 0v10.256c0 1.322 2 1.323 2 0z" fill="currentColor"/><path opacity=".5" d="M2 24.667v-7.692c0-1.323-2-1.324-2 0v7.692c0 1.322 2 1.323 2 0z" fill="currentColor"/></svg>',
    'pro'=>true
]
);