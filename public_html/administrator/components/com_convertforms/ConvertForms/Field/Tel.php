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

use Joomla\CMS\Filter\InputFilter;
use NRFramework\Countries;

class Tel extends \ConvertForms\Field
{
	protected $inheritInputLayout = 'text';

	/**
	 *  Remove common fields from the form rendering
	 *
	 *  @var  mixed
	 */
	protected $excludeFields = [
		'size',
		'inputcssclass'
	];

	
	/**
	 *  Renders the field's input element
	 *
	 *  @return  string  	HTML output
	 */
	protected function getInput()
	{
		return parent::getInput();
	}

	

	/**
	 * Prepare value to be displayed to the user as HTML/text
	 *
	 * @param   mixed   $value
	 *
	 * @return  string
	 */
	public function prepareValueHTML($value)
	{
		$value = $this->prepareValue($value);

		
		
		return '<a href="tel:' . $value . '">' . $value . '</a>';
	}
}