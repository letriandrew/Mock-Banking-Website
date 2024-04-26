<?php
/**
 * @package SP Page Builder
 * @author JoomShaper https://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined ('_JEXEC') or die ('Restricted access');

use Joomla\CMS\Language\Text;

SpAddonsConfig::addonConfig([
	'type'       => 'general',
	'addon_name' => 'progress_bar',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_PROGRESS_BAR'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_PROGRESS_BAR_DESC'),
	'category'   => 'Content',
	'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M27.938 4H4.062a1.062 1.062 0 000 2.123h23.876a1.062 1.062 0 100-2.123zM4.062 3a2.062 2.062 0 100 4.123h23.876a2.062 2.062 0 100-4.123H4.062z" fill="currentColor"/><rect x="2" y="3" width="7" height="4.123" rx="2.062" fill="currentColor"/><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M27.938 25.877H4.062a1.062 1.062 0 000 2.123h23.876a1.062 1.062 0 100-2.123zm-23.876-1a2.062 2.062 0 000 4.123h23.876a2.062 2.062 0 100-4.123H4.062z" fill="currentColor"/><rect x="2" y="24.877" width="14" height="4.123" rx="2.062" fill="currentColor"/><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M27.938 15.34H4.062a1.062 1.062 0 100 2.122h23.876a1.062 1.062 0 100-2.123zm-23.876-1a2.062 2.062 0 100 4.122h23.876a2.062 2.062 0 100-4.123H4.062z" fill="currentColor"/><rect x="2" y="14.339" width="20" height="4.123" rx="2.062" fill="currentColor"/></svg>',
	'pro'=>true
]
);