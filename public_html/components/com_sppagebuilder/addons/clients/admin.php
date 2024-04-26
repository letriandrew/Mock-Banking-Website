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
	'type'       => 'repeatable',
	'addon_name' => 'clients',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_CLIENTS'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_CLIENTS_DESC'),
	'category'   => 'Content',
	'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path opacity=".5" d="M26.7 16.7h-2.3c.2.6.4 1.3.4 2.1v8.8c0 .3-.1.6-.1.9h3.8c1.4 0 2.6-1.2 2.6-2.6v-4.8c-.1-2.4-2-4.4-4.4-4.4zM7.3 18.8c0-.7.1-1.4.4-2.1H5.3C2.9 16.7 1 18.6 1 21v4.8c0 1.4 1.2 2.6 2.6 2.6h3.8c-.1-.3-.1-.6-.1-.9v-8.7z" fill="currentColor"/><path d="M23 28.4H9v-9.6c0-2.4 1.9-4.3 4.3-4.3h5.3c2.4 0 4.3 1.9 4.3 4.3v9.6h.1zm-12-2h10v-7.6c0-1.3-1-2.3-2.3-2.3h-5.3c-1.3 0-2.3 1-2.3 2.3v7.6H11zM16 13.4c-.9 0-1.8-.2-2.5-.7-1.6-.9-2.7-2.7-2.7-4.5C10.8 5.3 13.1 3 16 3c2.9 0 5.2 2.3 5.2 5.2 0 1.9-1 3.6-2.7 4.5-.7.5-1.6.7-2.5.7zM16 5c-1.8 0-3.2 1.4-3.2 3.2 0 1.2.6 2.2 1.6 2.8 1 .5 2.2.5 3.1 0 1-.6 1.6-1.6 1.6-2.8C19.2 6.4 17.8 5 16 5z" fill="currentColor"/><path opacity=".5" d="M6.9 7.9C4.7 7.9 3 9.6 3 11.7c0 2.1 1.7 3.9 3.9 3.9.5 0 1.1-.1 1.5-.3.8-.4 1.5-1 1.9-1.7.3-.5.5-1.2.5-1.8-.1-2.2-1.8-3.9-3.9-3.9zM25.1 7.9c-2.1 0-3.9 1.7-3.9 3.9 0 .7.2 1.3.5 1.8.4.8 1.1 1.4 1.9 1.7.5.2 1 .3 1.5.3 2.1 0 3.9-1.7 3.9-3.9 0-2.1-1.7-3.8-3.9-3.8z" fill="currentColor"/></svg>',
	'pro'=>true
]
);