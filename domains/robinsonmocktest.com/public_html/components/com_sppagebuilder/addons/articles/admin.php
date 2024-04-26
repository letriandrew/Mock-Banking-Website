<?php

/**
 * @package SP Page Builder
 * @author JoomShaper https://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('resticted aceess');

use Joomla\CMS\Language\Text;

SpAddonsConfig::addonConfig([
    'type' => 'content',
    'addon_name' => 'articles',
    'title' => Text::_('COM_SPPAGEBUILDER_ADDON_ARTICLES'),
    'desc' => Text::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_DESC'),
    'category' => 'Content',
    'icon' => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path opacity=".5" d="M11.643 9.571h-.603L8.138 1.246A.363.363 0 007.804 1h-1.63a.363.363 0 00-.335.246L2.937 9.57h-.58c-.2 0-.357.179-.357.358v.714c0 .2.156.357.357.357h3.036a.367.367 0 00.357-.357v-.714a.384.384 0 00-.357-.358h-.536l.58-1.785h3.08l.604 1.785h-.514c-.2 0-.357.179-.357.358v.714c0 .2.156.357.357.357h3.036a.367.367 0 00.357-.357v-.714a.384.384 0 00-.357-.358zm-5.76-3.28l.938-2.769c.09-.357.157-.647.179-.78 0 .155.045.446.156.78l.938 2.768h-2.21z" fill="currentColor"/><path fill-rule="evenodd" clip-rule="evenodd" d="M30 16a1 1 0 01-1 1H3a1 1 0 110-2h26a1 1 0 011 1zM30 23a1 1 0 01-1 1H3a1 1 0 110-2h26a1 1 0 011 1zM16 30a1 1 0 01-1 1H3a1 1 0 110-2h12a1 1 0 011 1zM30 9a1 1 0 01-1 1H16a1 1 0 110-2h13a1 1 0 011 1zM30 2a1 1 0 01-1 1H16a1 1 0 110-2h13a1 1 0 011 1z" fill="currentColor"/></svg>',
    'pro'=>true
]
);