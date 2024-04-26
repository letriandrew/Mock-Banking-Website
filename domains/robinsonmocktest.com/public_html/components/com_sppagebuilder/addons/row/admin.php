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

SpAddonsConfig::addonConfig(
	[
		'type'       => 'structure',
		'addon_name' => 'row',
		'title'      => Text::_('COM_SPPAGEBUILDER_ROW'),
		'desc'       => Text::_('Row addon'),
		'category'   => 'Structure',
		'icon'       => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path fill-rule="evenodd" clip-rule="evenodd" d="M3 28.857h26v-8.571H3v8.571zM1 29.93C1 30.52 1.448 31 2 31h28c.552 0 1-.48 1-1.071V19.214c0-.591-.448-1.071-1-1.071H2c-.552 0-1 .48-1 1.071V29.93zM3 11.714h26V3.143H3v8.571zm-2 1.072c0 .591.448 1.071 1 1.071h28c.552 0 1-.48 1-1.071V2.07C31 1.48 30.552 1 30 1H2c-.552 0-1 .48-1 1.071v10.715z" fill="currentColor"></path></svg>',
		'attr'       => [
			'general' => [],
		],
	]
);
