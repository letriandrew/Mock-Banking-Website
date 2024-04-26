<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Http\Http;

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Google fonts trait
 *
 * @since 5.0.0
 */
trait FontsTrait
{
	public function fonts()
	{
		$method = $this->getInputMethod();
		$this->checkNotAllowedMethods(['PUT', 'PATCH'], $method);

		if ($method === 'GET')
		{
			$this->getFonts();
		}
		elseif ($method === 'POST')
		{
			$this->installFont();
		}
		elseif ($method === 'DELETE')
		{
			$this->uninstallFont();
		}
	}

	private function getInstalledFont(string $familyName, string $fontType)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select(['id', 'data'])
			->from($db->quoteName('#__sppagebuilder_fonts'))
			->where($db->quoteName('family_name') . ' = ' . $db->quote($familyName))
			->where($db->quoteName('type') . ' = ' . $db->quote($fontType));

		$db->setQuery($query);

		try
		{
			$font = $db->loadObject();

			if (!empty($font->data))
			{
				$font->data = \json_decode($font->data);
			}

			return $font;
		}
		catch (\Exception $e)
		{
			return null;
		}
	}

	private function isInstalledFont(string $familyName, string $fontType)
	{
		return !empty($this->getInstalledFont($familyName, $fontType));
	}

	private function getFonts()
	{
		$installed = $this->getInput('installed', 0, 'INT');

		if ($installed)
		{
			$this->loadInstalledFonts();
		}
		else
		{
			$this->loadGoogleFonts();
		}
	}

	private function loadInstalledFonts()
	{
		$limit = $this->getInput('limit', 10, 'INT');
		$offset = $this->getInput('offset', 0, 'INT');
		$search = $this->getInput('search', '', 'STRING');
		$type = $this->getInput('type', '', 'STRING');

		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('*')
			->from($db->quoteName('#__sppagebuilder_fonts'));

		if (!empty($search))
		{
			$search = preg_replace("@\s+@", ' ', $search);
			$search = explode(' ', $search);
			$search = array_filter($search, function ($word)
			{
				return !empty($word);
			});

			$search = implode('|', $search);

			$query->where($db->quoteName('family_name') . ' REGEXP ' . $db->quote($search));
		}

		if (!empty($type))
		{
			$query->where($db->quoteName('type') . ' = ' . $db->quote($type));
		}

		$fullQuery = $db->getQuery(true);
		$fullQuery = $query->__toString();

		$results = [];

		if (!empty($limit))
		{
			$query->setLimit($limit, $offset);
		}

		$db->setQuery($query);

		try
		{
			$results = $db->loadObjectList();
		}
		catch (\Exception $e)
		{
			$this->sendResponse(['message' => $e->getMessage()], 500);
		}

		$db->setQuery($fullQuery);
		$db->execute();
		$allItems = $db->getNumRows();

		if (!empty($results))
		{
			foreach ($results as &$result)
			{
				$result->data = !empty($result->data) ? \json_decode($result->data) : null;

				if ($result->type === 'google')
				{
					$installedFont = $this->getInstalledFont($result->family_name, 'google');
					$result->installed_variants = $installedFont->data->variants;
				}
			}

			unset($result);
		}

		$response = (object) [
			'totalItems' => $allItems,
			'totalPages' => ceil($allItems / $limit),
			'results' => $results,
		];

		$this->sendResponse($response);
	}

	private function loadGoogleFonts()
	{
		$limit = $this->getInput('limit', 10, 'INT');
		$offset = $this->getInput('offset', 0, 'INT');
		$search = $this->getInput('search', '', 'STRING');
		$category = $this->getInput('category', '', 'STRING');

		$cParams = ComponentHelper::getParams('com_sppagebuilder');
		$http = new Http();
		$response = ['message' => ''];

		$API_KEY = $cParams->get('google_font_api_key', '');


		if (empty($API_KEY))
		{
			$response['message'] = 'Invalid api key.';
			$this->sendResponse($response, 403);
		}

		$endpoint = 'https://www.googleapis.com/webfonts/v1/webfonts?key=' . trim($API_KEY);

		$cachePath = JPATH_CACHE . '/sppagebuilder';
		$cacheFile = $cachePath . '/google-web-fonts.json';

		if (!Folder::exists($cachePath))
		{
			Folder::create($cachePath, 0755);
		}

		$fonts = [];

		if (File::exists($cacheFile) && (filemtime($cacheFile) > (time() - (24 * 60 * 60))))
		{
			$fonts = \file_get_contents($cacheFile);
		}
		else
		{
			try
			{
				$apiResponse = $http->get($endpoint);
			}
			catch (\Exception $e)
			{
				$this->sendResponse(['message' => $e->getMessage()], 500);
			}

			if ($apiResponse->code === 400)
			{
				$responseBody = \is_string($apiResponse->body) ? \json_decode($apiResponse->body) : $apiResponse->body;
				$response['message'] = $responseBody->error->message;
				$this->sendResponse($response, 400);
			}

			if ($apiResponse->code >= 400)
			{
				$responseBody = \is_string($apiResponse->body) ? \json_decode($apiResponse->body) : $apiResponse->body;
				$response['message'] = $responseBody->error->message;
				$this->sendResponse($response, $apiResponse->code);
			}

			$fonts = $apiResponse->body;

			if (!empty($fonts))
			{
				if (!File::write($cacheFile, $fonts))
				{
					$response['message'] = 'Error caching the font data.';
					$this->sendResponse($response, 500);
				}
			}
		}

		if (!empty($fonts))
		{
			$fonts = \json_decode($fonts);

			if (\json_last_error() > 0)
			{
				$response['message'] = 'Error parsing font data.';
				$this->sendResponse($response, 500);
			}

			$fonts = !empty($fonts->items) ? $fonts->items : [];


			if (!empty($search))
			{
				$search = preg_replace("@\s+@", ' ', $search);
				$search = explode(' ', $search);
				$search = array_filter($search, function ($word)
				{
					return !empty($word);
				});

				$search = implode('|', $search);

				$fonts = array_filter($fonts, function ($font) use ($search)
				{
					return  preg_match("@$search@i", $font->family);
				});

				$fonts = array_values($fonts);
			}

			if (!empty($category))
			{
				$fonts = array_filter($fonts, function ($font) use ($category)
				{
					return $font->category === $category;
				});

				$fonts = array_values($fonts);
			}

			$resultChunk = array_slice($fonts, $offset, $limit);

			foreach ($resultChunk as &$item)
			{
				unset($item->version, $item->lastModified, $item->files, $item->kind, $item->menu);
				$item->is_installed = $this->isInstalledFont($item->family, 'google');
				$item->installed_variants = [];

				if ($item->is_installed)
				{
					$installedFont = $this->getInstalledFont($item->family, 'google');
					$item->installed_variants = $installedFont->data->variants;
				}
			}

			unset($item);

			$totalFonts = count($fonts);
			$response = (object) [
				'totalItems' => $totalFonts,
				'totalPages' => ceil($totalFonts / $limit),
				'results' => $resultChunk,
			];

			$this->sendResponse($response);
		}

		$response['message'] = 'Fonts not found.';
		$this->sendResponse($response, 404);
	}

	private function installFont()
	{
		$familyName = $this->getInput('family_name', '', 'STRING');
		$type = $this->getInput('type', 'google', 'STRING');
		$fontData = $this->getInput('data', '', 'RAW');
		$response = ['message' => ''];

		if (empty($familyName) || empty($fontData))
		{
			$this->sendResponse(['message' => 'Missing request data.'], 400);
		}

		$cParams = ComponentHelper::getParams('com_sppagebuilder');
		$API_KEY = $cParams->get('google_font_api_key', '');

		if (empty($API_KEY))
		{
			$response['message'] = 'Invalid API KEY';
			$this->sendResponse($response, 403);
		}

		if ($type === 'google')
		{
			$this->installGoogleFont($fontData);
		}
	}

	private function saveFontData(string $familyName, string $type, $fontData)
	{
		$data = (object) [
			'id' => null,
			'family_name' => $familyName,
			'data' => $fontData,
			'type' => $type,
			'created_by' => Factory::getUser()->id,
			'created' => Factory::getDate()->toSql(),
			'published' => 1
		];

		$db = Factory::getDbo();

		try
		{
			$installedFont = $this->getInstalledFont($familyName, $type);

			if (!empty($installedFont->id))
			{
				$data->id = $installedFont->id;
				$db->updateObject('#__sppagebuilder_fonts', $data, 'id', true);
				$response = (object) [
					'id' => $data->id
				];

				$this->sendResponse($response, 200);
			}
			else
			{
				$db->insertObject('#__sppagebuilder_fonts', $data, 'id');
				$response = (object) [
					'id' => $data->id
				];

				$this->sendResponse($response, 201);
			}
		}
		catch (\Exception $e)
		{
			$this->sendResponse(['message' => $e->getMessage()], 500);
		}
	}

	private function hasNumber($str)
	{
		return preg_match('/\d/', $str);
	}

	private function extractWeightAndStyle($str)
	{
		preg_match('/\d+/', $str, $weightMatches);
		$weight = $weightMatches[0] ?? '';

		return [
			'weight' => $weight ? (int)$weight : 400,
			'style' => preg_match('/[a-zA-Z]+/', $str, $styleMatches) ? $styleMatches[0] : '',
		];
	}

	private function generateFamily($data)
	{
		$family = $data->family;
		$variants = $data->variants;

		$variantString = implode('', $variants);
		$hasItalic = strpos($variantString, 'italic') !== false;
		$hasWeight = $this->hasNumber($variantString);

		$individualStyles = array_map(function ($variant) use ($hasWeight, $hasItalic)
		{
			$parsedData = $this->extractWeightAndStyle($variant);
			$parsedWeight = $parsedData['weight'];
			$style = $parsedData['style'];
			$weight = $parsedWeight;

			if ($hasWeight && ($style === 'regular' || $style === 'italic') && !$weight)
			{
				$weight = 400;
			}

			if ($hasItalic)
			{
				if ($hasWeight)
				{
					return ($style === 'italic' ? 1 : 0) . ',' . $weight;
				}
				return $style === 'italic' ? 1 : 0;
			}

			return (string)$weight;
		}, $variants);

		sort($individualStyles);
		$individualStylesString = implode(';', $individualStyles);

		$linkFamily = str_replace(' ', '+', $family) .
			($hasItalic ? ':ital' : '') .
			($hasItalic && $hasWeight ? ',' : '') .
			(!$hasItalic && $hasWeight ? ':' : '') .
			($hasWeight ? 'wght' : '') .
			(($hasWeight || $hasItalic) && $individualStylesString ? '@' . $individualStylesString : '');

		return $linkFamily;
	}

	private function installGoogleFont($fontData)
	{
		$fontData = is_string($fontData) ? \json_decode($fontData) : $fontData;

		$familyName = $this->generateFamily($fontData);


		$url = "https://fonts.googleapis.com/css2?family=" . $familyName;
		$url .= '&display=swap';

		$options  = [
			'ssl' => [
				'verify_peer' => false,
				'verify_peer_name' => false,
			],
			'http' => [
				'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.82 Safari/537.36'
			],
		];

		$context  = stream_context_create($options);
		$content = file_get_contents($url, false, $context);

		$matches = [];
		preg_match_all("@url\((.*?)\)@", $content, $matches);

		$fontUrls = !empty($matches[1]) ? $matches[1] : [];

		$baseDirectory = JPATH_ROOT . '/media/com_sppagebuilder/assets/google-fonts/' . $fontData->family;

		if (\file_exists($baseDirectory))
		{
			Folder::delete($baseDirectory);
		}

		Folder::create($baseDirectory, 0755);

		if (!empty($fontUrls))
		{
			foreach ($fontUrls as $url)
			{
				$fileName = \basename($url);
				$content = str_replace($url, $fileName, $content);
				$fileContent = \file_get_contents($url);

				\file_put_contents($baseDirectory . '/' . $fileName, $fileContent);
			}
		}

		if (!empty($content))
		{
			\file_put_contents($baseDirectory . '/stylesheet.css', $content);
		}

		$this->saveFontData($fontData->family, 'google', json_encode($fontData));
	}

	private function uninstallFont()
	{
		$familyName = $this->getInput('family_name', '', 'STRING');
		$type = $this->getInput('type', '', 'STRING');

		$baseDirectory = JPATH_ROOT . '/media/com_sppagebuilder/assets/';
		$directory = $type === 'google' ? 'google-fonts' : 'custom-fonts';

		if (!$this->isInstalledFont($familyName, $type))
		{
			$this->sendResponse(['message' => 'Do not find the font to uninstall.'], 400);
		}

		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->delete('#__sppagebuilder_fonts')
			->where($db->quoteName('family_name') . ' = ' . $db->quote($familyName))
			->where($db->quoteName('type') . ' = ' . $db->quote($type));

		$db->setQuery($query);

		try
		{
			$db->execute();
			$fontFile = $baseDirectory . $directory . '/' . $familyName;

			if (\file_exists($fontFile))
			{
				Folder::delete($fontFile);
			}

			$this->sendResponse(true);
		}
		catch (\Exception $e)
		{
			$this->sendResponse(['message' => $e->getMessage()], 500);
		}
	}
}
