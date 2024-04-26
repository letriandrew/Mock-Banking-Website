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
	'type'       => 'content',
	'addon_name' => 'articles_scroller',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_SCROLLER'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_ARTICLES_SCROLLER_DESC'),
	'category'   => 'Content',
	'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M15.29.718a1 1 0 011.42 0l4.532 4.579c.625.631.178 1.703-.71 1.703h-9.063c-.889 0-1.336-1.072-.711-1.703L15.289.718zM16.71 31.282a1 1 0 01-1.42 0l-4.532-4.579c-.625-.631-.178-1.703.71-1.703h9.064c.888 0 1.335 1.072.71 1.703l-4.531 4.579z" fill="currentColor"/><g opacity=".5" fill="currentColor"><path d="M2 16a1 1 0 011-1h26a1 1 0 110 2H3a1 1 0 01-1-1zM2 11a1 1 0 011-1h26a1 1 0 110 2H3a1 1 0 01-1-1zM2 21a1 1 0 011-1h12a1 1 0 110 2H3a1 1 0 01-1-1z"/></g></svg>',
	'pro'=>true
]
);