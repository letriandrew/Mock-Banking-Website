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
	'addon_name' => 'testimonialpro',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_TESTIMONIAL_PRO'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_TESTIMONIAL_PRO_DESC'),
	'category'   => 'Slider',
	'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M12 3.329c0 .507.412.92.92.92h1.039v.394c0 .704-.485 1.3-1.144 1.464-.197.049-.364.21-.364.412V7.65c0 .203.165.37.367.348a3.384 3.384 0 003.007-3.355V.92a.92.92 0 00-.92-.919H12.92A.92.92 0 0012 .92v2.409zM16.8 3.329c0 .507.412.92.92.92h1.039v.394c0 .704-.485 1.3-1.144 1.464-.197.049-.364.21-.364.412V7.65c0 .203.165.37.367.348a3.384 3.384 0 003.007-3.355V.92a.92.92 0 00-.92-.919H17.72a.92.92 0 00-.92.92v2.409z" fill="currentColor"/><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M2 13c0-.552.464-1 1.037-1h25.926c.573 0 1.037.448 1.037 1s-.464 1-1.037 1H3.037C2.464 14 2 13.552 2 13zM2 17c0-.552.464-1 1.037-1h25.926c.573 0 1.037.448 1.037 1s-.464 1-1.037 1H3.037C2.464 18 2 17.552 2 17zM10 21a1 1 0 011-1h10a1 1 0 110 2H11a1 1 0 01-1-1z" fill="currentColor"/><circle opacity=".5" cx="10.5" cy="30.5" r="1.5" fill="currentColor"/><circle opacity=".5" cx="22.5" cy="30.5" r="1.5" fill="currentColor"/><circle cx="16.5" cy="30.5" r="1.5" fill="currentColor"/></svg>',
	'pro'=>true
]
);