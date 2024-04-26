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
	'addon_name' => 'image_overlay',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_IMAGE_OVERLAY'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_IMAGE_OVERLAY_DESC'),
	'category'   => 'Media',
	'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M31.288 10.71l-9.718 5.9a5 5 0 01-5.8-.436l-3.622-3.024a3 3 0 00-3.583-.196l-6.781 4.503-1.106-1.666 6.78-4.503a5 5 0 015.971.327l3.623 3.024a3 3 0 003.48.262L30.25 9l1.038 1.71z" fill="currentColor"/><path fill-rule="evenodd" clip-rule="evenodd" d="M27 21c0 .552-.41 1-.917 1H5.917C5.41 22 5 21.552 5 21s.41-1 .917-1h20.166c.507 0 .917.448.917 1zM16 26a1 1 0 01-1 1H6a1 1 0 110-2h9a1 1 0 011 1z" fill="currentColor"/><path fill-rule="evenodd" clip-rule="evenodd" d="M28 2H4a2 2 0 00-2 2v24a2 2 0 002 2h24a2 2 0 002-2V4a2 2 0 00-2-2zM4 0a4 4 0 00-4 4v24a4 4 0 004 4h24a4 4 0 004-4V4a4 4 0 00-4-4H4z" fill="currentColor"/><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M19.077 6a2.077 2.077 0 100 4.154 2.077 2.077 0 000-4.154zM15 8.077a4.077 4.077 0 118.154 0 4.077 4.077 0 01-8.154 0z" fill="currentColor"/></svg>',
	'pro'=>true
]
);