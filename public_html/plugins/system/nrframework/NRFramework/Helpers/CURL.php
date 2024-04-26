<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            https://www.tassos.gr
 * @copyright       Copyright © 2023 Tassos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

namespace NRFramework\Helpers;

defined('_JEXEC') or die;

class CURL
{
	/**
	 * Executes a GET cURL request.
	 * 
	 * @param   string  $url
	 * 
	 * @return  mixed
	 */
	public static function exec($url)
	{
		$response = \JHttpFactory::getHttp()->get($url);

		return $response->body;
	}
}