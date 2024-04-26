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

namespace ConvertForms\Field;

defined('_JEXEC') or die('Restricted access');

class Radio extends \ConvertForms\FieldChoice
{
	/**
	 *  Remove common fields from the form rendering
	 *
	 *  @var  mixed
	 */
	protected $excludeFields = array(
		'placeholder',
		'browserautocomplete',
		'size',
	);

	/**
	 *  Radio buttons expect single text value. So we need to transform the submitted array data to string.
	 *
	 *  @param   mixed  $input   User input value
	 *
	 *  @return  mixed           The filtered user input
	 */
	public function filterInput($input)
	{
		$value = parent::filterInput($input);

		if (is_array($value) && isset($value[0]))
		{
			return $value[0];
		}

		return $value;
	}
}

?>