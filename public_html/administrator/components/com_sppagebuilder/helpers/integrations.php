<?php
/**
* @package SP Page Builder
* @author JoomShaper http://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2021 JoomShaper
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('Restricted access');

class SppagebuilderHelperIntegrations {

	public static function integrations()
	{
		$integrations = array(
			'content' => array(
				'title' => 'Joomla Article',
				'group' => 'content',
				'name' => 'sppagebuilder',
				'view' => 'article',
				'id_alias' => 'id'
			),

			'spsimpleportfolio' => array(
				'title' => 'SP Simple Portfolio',
				'group' => 'spsimpleportfolio',
				'name' => 'sppagebuilder',
				'view' => 'item',
				'id_alias' => 'id',
				'frontend_only' => true,
			),

			'k2' => array(
				'title' => 'K2',
				'group' => 'k2',
				'name' => 'sppagebuilder',
				'view' => 'item',
				'id_alias' => 'cid'
			)
		);

		return $integrations;
	}
}