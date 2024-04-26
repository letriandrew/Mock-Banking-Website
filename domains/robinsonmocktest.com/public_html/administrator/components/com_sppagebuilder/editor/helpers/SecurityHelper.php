<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\Path;

/** No direct access */
defined('_JEXEC') or die('Restricted access');

final class SecurityHelper
{
	public static function isActionableFolder(string $folder)
	{
		$folder = strtolower(Path::clean($folder));
		$parts = explode(DIRECTORY_SEPARATOR, $folder);
		$parts = array_filter($parts, function ($part)
		{
			return !empty($part);
		});
		$parts = array_values($parts);

		if (empty($parts) || !is_array($parts) || count($parts) < 2 || $parts[0] !== 'images')
		{
			return false;
		}

		return true;
	}

	public static function isGetablePath(string $path)
	{
		$path = strtolower(Path::clean($path));
		$pathArray = explode(DIRECTORY_SEPARATOR, $path);
		$pathArray = array_filter($pathArray, function ($part)
		{
			return !empty($part);
		});

		$pathArray = array_values($pathArray);

		if (empty($pathArray) || !is_array($pathArray) || count($pathArray) < 1 || $pathArray[0] !== 'images')
		{
			return false;
		}

		return true;
	}
}
