<?php

/**
 * @author          Tassos.gr
 * @link            https://www.tassos.gr
 * @copyright       Copyright © 2023 Tassos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework\SmartTags;

defined('_JEXEC') or die('Restricted access');

class Post extends SmartTag
{
	/**
	 * Fetch value of a specific query string
	 * 
	 * @param   string  $key
	 * 
	 * @return  string
	 */
	public function fetchValue($key)
	{
		$filter = $this->parsedOptions->get('filter', 'STRING');
		$default_value = $this->parsedOptions->get('default', '');
		
		return $this->app->input->post->get($key, $default_value, $filter);
	}
}