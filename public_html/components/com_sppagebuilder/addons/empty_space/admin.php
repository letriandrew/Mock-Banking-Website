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
	'addon_name' => 'empty_space',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_EMPTY_SPACE'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_EMPTY_SPACE_DESC'),
	'category'   => 'General',
	'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M18.5 13.6h-4.9c-.3 0-.5-.4-.3-.7l2.5-2.5c.2-.2.4-.2.5 0l2.5 2.5c.2.2 0 .7-.3.7zM13.5 18.3h4.9c.3 0 .5.4.3.7l-2.5 2.5c-.2.2-.4.2-.5 0L13.2 19c-.2-.3 0-.7.3-.7z" fill="currentColor"/><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M29 22.9H2.7c-.9 0-1.7.8-1.7 1.7v5.7c0 .9.8 1.7 1.7 1.7.9 0 1.7-.8 1.7-1.7v-4h22.9v4c0 .9.8 1.7 1.7 1.7.9 0 1.7-.8 1.7-1.7v-5.7c0-1-.8-1.7-1.7-1.7zM29 0c-.9 0-1.7.8-1.7 1.7v4H4.4v-4C4.4.8 3.7 0 2.7 0S1 .8 1 1.7v5.7c0 .9.8 1.7 1.7 1.7H29c.9 0 1.7-.8 1.7-1.7V1.7C30.7.8 29.9 0 29 0z" fill="currentColor"/></svg>',
	'settings' => [
		'content' => [
			'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_CONTENT'),
			'fields' => [
				'gap' => [
					'type'       => 'slider',
					'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_EMPTY_SPACE_GAP'),
					'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_EMPTY_SPACE_GAP_DESC'),
					'min'        => 10,
					'max'        => 400,
					'std'        => ['xl' => 40],
					'responsive' => true
				],
			],
		],
	],
]);
