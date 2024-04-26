<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

use Joomla\CMS\Language\Text;

/** No direct access. */
defined('_JEXEC') or die('Restricted access');


/**
 * Integration helper for the builder.
 *
 * @since 	4.0.0
 */
class BuilderIntegrationHelper
{
	public static function getIntegrations(): array
	{
		return [
			'content' => [
				'title' => Text::_("COM_SPPAGEBUILDER_JOOMLA_ARTICLE"),
				'group' => 'content',
				'name' => 'sppagebuilder',
				'view' => 'article',
				'id_alias' => 'id'
			],
			'spsimpleportfolio' => [
				'title' => Text::_("COM_SPPAGEBUILDER_SP_SIMPLE_PORTFOLIO"),
				'group' => 'spsimpleportfolio',
				'name' => 'sppagebuilder',
				'view' => 'item',
				'id_alias' => 'id',
				'frontend_only' => true,
			],
			'k2' => [
				'title' => 'K2',
				'group' => 'k2',
				'name' => 'sppagebuilder',
				'view' => 'item',
				'id_alias' => 'cid'
			]
		];
	}
}
