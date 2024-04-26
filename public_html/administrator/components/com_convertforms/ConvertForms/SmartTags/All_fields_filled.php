<?php

/**
 * @package         Convert Forms
 * @version         4.2.1 Free
 * 
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            https://www.tassos.gr
 * @copyright       Copyright © 2023 Tassos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace ConvertForms\SmartTags;

defined('_JEXEC') or die('Restricted access');

/**
 * @deprecated Use {all_fields --excludeEmpty=true} instead.
 */
class All_fields_filled extends All_fields
{
	/**
	 * Get All Fields Filled value
	 * 
	 * @return  string
	 */
	public function getAll_fields_filled()
	{	
		$this->parsedOptions->set('excludeempty', true);
		return $this->getAll_fields();
	}
}