<?php

/**
 * @package SP Page Builder
 * @author JoomShaper https://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Helper class for handling the lodash string.
 *
 * @since	4.0.0
 */
final class AddonUtils
{
	public static $defaultDevice = 'xl';

	public static function parseMediaData($media)
	{
		if (empty($media))
		{
			return '';
		}

		if (\is_object($media) && isset($media->src))
		{
			return $media->src;
		}

		if (\is_array($media) && isset($media['src']))
		{
			return $media['src'];
		}

		return $media;
	}

	public static function parseDeviceData($data, $device = '')
	{
		if (empty($data))
		{
			return '';
		}

		$device = !empty($device) ? $device : self::$defaultDevice;

		if (\is_object($data) && isset($data->$device))
		{
			return $data->$device;
		}

		return $data;
	}
}
