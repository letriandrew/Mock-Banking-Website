<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct access
defined ('_JEXEC') or die ('Restricted access');

use Joomla\CMS\Table\Table;

class SppagebuilderTableDashboard extends Table
{
	function __construct(&$db) {
		parent::__construct('#__sppagebuilder', 'id', $db);
	}

	public function store($updateNulls = false)
	{
		return parent::store($updateNulls);
	}
}
