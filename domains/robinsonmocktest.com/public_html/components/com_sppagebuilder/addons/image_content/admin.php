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
	'addon_name' => 'image_content',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_IMAGE_CONTENT'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_IMAGE_CONTENT_DESC'),
	'category'   => 'Content',
	'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M11.084 15.049l-3.24-3.24-4.65 4.648h25.611L19.24 6.892l-8.156 8.157zm8.253-8.254zm9.703 9.897zM11.084 12.22l6.84-6.84a1.862 1.862 0 012.633 0l9.898 9.898a1.862 1.862 0 01-1.317 3.18H2.862a1.862 1.862 0 01-1.317-3.18l4.982-4.981a1.862 1.862 0 012.633 0l1.924 1.923z" fill="currentColor"/><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M31 25a1 1 0 01-1 1H2a1 1 0 110-2h28a1 1 0 011 1zM19 30a1 1 0 01-1 1H2a1 1 0 110-2h16a1 1 0 011 1z" fill="currentColor"/><path d="M7.789 5.544a2.772 2.772 0 100-5.544 2.772 2.772 0 000 5.544z" fill="currentColor"/></svg>',
	'pro'=>true
]
);