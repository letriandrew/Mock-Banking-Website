<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */


use Joomla\CMS\Filesystem\Folder;

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Trait for managing purging cached css.
 * 
 * @version 4.1.0
 */
trait PurgeCssTrait
{
	public function purgeCss()
	{
		$method = $this->getInputMethod();
		$this->checkNotAllowedMethods(['POST', 'PUT', 'DELETE', 'PATCH'], $method);

		$this->purgeCachedCss();
	}

	private function purgeCachedCss()
	{
		$cssFolderPath = JPATH_ROOT . '/media/com_sppagebuilder/css';

		if (Folder::exists($cssFolderPath))
		{
			Folder::delete($cssFolderPath);
		}

		$this->sendResponse(true);
	}
}
