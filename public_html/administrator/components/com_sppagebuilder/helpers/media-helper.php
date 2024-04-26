<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Filesystem\Path;
use Joomla\Filesystem\Exception\FilesystemException;

//no direct access
defined('_JEXEC') or die('Restricted access');

class BuilderMediaHelper
{
	public static function isValidImagesPath(string $path): bool
	{
		$mediaParams = ComponentHelper::getParams('com_media');
		$filePath = Path::clean($mediaParams->get('file_path', 'images'));
		$fullFilePath = Path::clean(JPATH_ROOT . '/' . $filePath);

		return strpos($path, $fullFilePath) === 0;
	}

	public static function checkForMediaActionBoundary(string $path)
	{
		try
		{
			$cleanedPath = Path::check($path);
		}
		catch (\Exception $e)
		{
			throw new FilesystemException($e->getMessage());
		}

		// TODO: Need to check this later
		// if (!self::isValidImagesPath($cleanedPath))
		// {
		// 	throw new FilesystemException('Invalid path for performing this action.');
		// }

		return $cleanedPath;
	}
}
