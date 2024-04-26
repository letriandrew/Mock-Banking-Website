<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Http\Http;

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Section Library API trait
 */
trait SectionLibraryTrait
{
	public function sectionLibrary()
	{
		$method = $this->getInputMethod();
		$this->checkNotAllowedMethods(['POST', 'PUT', 'DELETE', 'PATCH'], $method);

		if ($method === 'GET')
		{
			$this->getSectionLibrary();
		}
	}

	private function getSectionLibrary()
	{
		$cParams = ComponentHelper::getParams('com_sppagebuilder');
		$http = new Http();

		if (!\class_exists('SppagebuilderHelperSite'))
		{
			require_once JPATH_ROOT . '/components/com_sppagebuilder/helpers/helper.php';
		}

		$cache_path = JPATH_CACHE . '/sppagebuilder';
		$cache_file = $cache_path . '/sections.json';

		$sections = [];
		$sectionsData = '';

		if (!Folder::exists($cache_path))
		{
			Folder::create($cache_path, 0755);
		}

		if (File::exists($cache_file) && (filemtime($cache_file) > (time() - (24 * 60 * 60))))
		{
			$sectionsData = file_get_contents($cache_file);
		}
		else
		{
			$args = '&email=' . $cParams->get('joomshaper_email') . '&api_key=' . $cParams->get('joomshaper_license_key');
			$sectionApi = 'https://www.joomshaper.com/index.php?option=com_layouts&task=block.list&support=4beyond' . $args;

			$sectionResponse = $http->get($sectionApi);
			$sectionsData = $sectionResponse->body;

			if ($sectionResponse->code !== 200)
			{
				$response['message'] = 'Error getting sections data.';
				$this->sendResponse($response, 500);
			}

			if (!empty($sectionsData))
			{
				File::write($cache_file, $sectionsData);
			}
		}

		if (!empty($sectionsData))
		{
			$sections = json_decode($sectionsData);

			/** Sanitize the blocks data before sending. */
			if (!empty($sections->blocks))
			{
				foreach ($sections->blocks as $i => &$groups)
				{
					if (!empty($groups->blocks))
					{
						foreach ($groups->blocks as $j => &$block)
						{
							if (!empty($block->json))
							{
								$content = json_decode($block->json);

								if (\is_object($content))
								{
									$content = json_encode([$content]);
								}
								elseif (\is_array($content))
								{
									$content = json_encode($content);
								}

								$json = SppagebuilderHelperSite::sanitize($content);
								$block->json = $json;
							}
						}

						unset($block);
					}
				}

				unset($groups);
			}

			if ((is_array($sections) && count($sections)) || is_object($sections))
			{
				$this->sendResponse($sections);
			}
		}

		$response['message'] = 'Sections not found!';
		$this->sendResponse($response, 500);
	}
}
