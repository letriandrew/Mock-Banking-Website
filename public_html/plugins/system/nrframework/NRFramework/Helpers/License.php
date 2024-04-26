<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            https://www.tassos.gr
 * @copyright       Copyright © 2023 Tassos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

namespace NRFramework\Helpers;

defined('_JEXEC') or die;

class License
{
	/**
	 * Returns the remote license data from the server for the given download key.
	 * 
	 * @return  array
	 */
	public static function getRemoteLicenseData($download_key = null)
	{
		if (!$download_key)
		{
			return;
		}
		
		// License Check Endpoint
		$url = TF_CHECK_LICENSE;
		// Set Download Key
		$url = str_replace('{{DOWNLOAD_KEY}}', $download_key, $url);
		
		// No response, abort
		if (!$response = CURL::exec($url))
		{
			return;
		}

		return json_decode($response, true);
	}
}