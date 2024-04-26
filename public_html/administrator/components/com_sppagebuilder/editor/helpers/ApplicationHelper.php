<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

use Joomla\CMS\Factory;

/** No direct access */
defined('_JEXEC') or die('Restricted access');

final class ApplicationHelper
{
	public static function hasPageContent(int $id)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('content')
			->from($db->quoteName('#__sppagebuilder'))
			->where($db->quoteName('id') . ' = ' . $id);

		$db->setQuery($query);

		try
		{
			$result = $db->loadResult();

			if (\is_string($result) && !empty($result))
			{
				$result = \json_decode($result);
			}

			return !empty($result);
		}
		catch (\Exception $e)
		{
			return false;
		}
	}

	public static function sanitizePageText($text)
	{
		$text = $text ?? '[]';
		$text = !\is_string($text) ? json_encode($text) : $text;
		$parsed = SpPageBuilderAddonHelper::__($text);
		$parsed = SppagebuilderHelperSite::sanitize($parsed);

		return json_decode($parsed);
	}

	public static function preparePageData($pageData)
	{
		if (empty($pageData))
		{
			return (object) [
				'text' => new stdClass
			];
		}

		$content = [];

		if (is_null($pageData->content))
		{
			$pageData->text = SppagebuilderHelperSite::prepareSpacingData($pageData->text);
			$pageData->text = self::sanitizePageText($pageData->text);

			return $pageData;
		}

		if (\is_string($pageData->content))
		{
			$content = \json_decode($pageData->content);
		}

		if (is_null($content))
		{
			$pageData->text = SppagebuilderHelperSite::prepareSpacingData($pageData->text);
			$pageData->text = self::sanitizePageText($pageData->text);

			return $pageData;
		}

		$version = SppagebuilderHelper::getVersion();
		$storedVersion = $pageData->version;
		$pageData->text = $content;
		$pageData->text = SppagebuilderHelperSite::prepareSpacingData($pageData->text);
		$pageData->text = json_decode($pageData->text);

		if ($version !== $storedVersion)
		{
			$pageData->text = self::sanitizePageText(json_encode($pageData->text));
		}


		return $pageData;
	}
}
