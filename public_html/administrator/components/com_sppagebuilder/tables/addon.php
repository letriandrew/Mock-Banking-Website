<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/

//No direct access
defined ('_JEXEC') or die ('Restricted access');

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;

class SppagebuilderTableAddon extends Table
{
    /**
     * Summary of __construct
	 * 
     * @param   DatabaseDriver	$db		DatabaseDriver object.
     */
	function __construct(&$db)
	{
		parent::__construct('#__sppagebuilder_addons', 'id', $db);
	}

	public function store($updateNulls = false)
	{
		return parent::store($updateNulls);
	}
}
