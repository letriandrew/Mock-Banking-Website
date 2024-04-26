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
		'addon_name' => 'columns',
		'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_AJAX_CONTACT_COLUMN_OPTION'),
		'desc'       => Text::_('Columns Addon'),
		'category'   => 'Structure',
		'icon'       => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.143 3v26h8.571V3H3.143zM2.07 1C1.48 1 1 1.448 1 2v28c0 .552.48 1 1.071 1h10.715c.591 0 1.071-.448 1.071-1V2c0-.552-.48-1-1.071-1H2.07zM20.286 3v26h8.571V3h-8.571zm-1.072-2c-.591 0-1.071.448-1.071 1v28c0 .552.48 1 1.071 1H29.93C30.52 31 31 30.552 31 30V2c0-.552-.48-1-1.072-1H19.215z" fill="currentColor"></path></svg>',
		'attr'       => [
			'general' => [],
		],
	]
);
