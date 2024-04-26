<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

use Joomla\CMS\Router\Route;
use Joomla\CMS\Filter\InputFilter;

/** No direct access */
defined('_JEXEC') or die('Restricted access');



/** Utils helper class */
final class EditorUtils
{
	public static function parsePageListData(array $pages)
	{
		foreach ($pages as &$page)
		{
			$page->catid = (int) $page->catid;
			$page->checked_out = (int) $page->checked_out;
			$page->created_by = (int) $page->created_by;
			$page->hits = (int) $page->hits;
			$page->id = (int) $page->id;
			$page->published = (int) $page->published;
		}

		unset($page);

		return $pages;
	}

	/**
	 * Clear XSS code from page data
	 *
	 * @param string $pageData
	 * @return mixed
	 * @since 5.0.2
	 */
	public static function cleanXSS($pageData)
	{
		$pageData = is_array($pageData) ? $pageData : json_decode($pageData, true);

		array_walk_recursive($pageData, function(&$value) {
			if (is_string($value)) {
				$allowedTags = ['iframe', 'script', 'style', 'canvas'];
				$tagBlacklist = property_exists(InputFilter::getInstance(), 'blockedTags') ? InputFilter::getInstance()->blockedTags : InputFilter::getInstance()->tagBlacklist;
				$blockedTags = array_filter($tagBlacklist, function($tag) use($allowedTags){ if (!in_array($tag, $allowedTags)){return $tag;}});
				$value = InputFilter::getInstance(
					$blockedTags,
					[],
					InputFilter::ONLY_BLOCK_DEFINED_TAGS,
					InputFilter::ONLY_BLOCK_DEFINED_ATTRIBUTES,
					0
				)->clean($value, 'html');
			}
		});

		return json_encode($pageData);
	}

	public static function extractSettingsDefaultValues($settings)
	{
		if (empty($settings))
		{
			return [];
		}

		$defaultValues = [];

		foreach ($settings as $setting)
		{
			$fields = $setting['fields'] ?? null;

			if (empty($fields))
			{
				continue;
			}

			foreach ($fields as $fieldName => $field)
			{
				if (isset($field['std']))
				{
					$defaultValues[$fieldName] = $field['std'];
				}
				elseif (isset($field['type']) &&  $field['type'] === 'repeatable' && !empty($field['attr']))
				{
					$defaultValues[$fieldName] = $defaultValues[$fieldName] ?? [];
					$defaultValues[$fieldName][] = self::extractRepeatableFieldsDefaultValues($field['attr']);
				}
				elseif (isset($field['type']) && $field['type'] === 'interaction_view' && !empty($field['attr']))
				{
					$defaultValues[$fieldName] = $defaultValues[$fieldName] ?? [];
					$defaultValues[$fieldName][] = self::extractInteractionFieldsDefaultValues($field['attr']);
				}
				elseif (isset($field['std']))
				{
					$defaultValues[$fieldName] = $field['std'];
				}
			}
		}

		return $defaultValues;
	}

	private static function extractRepeatableFieldsDefaultValues($groups)
	{
		if (empty($groups))
		{
			return [];
		}

		$defaultValues = [];

		foreach ($groups as $group)
		{
			$fields = !empty($group['fields']) ? $group['fields'] : [];

			foreach ($fields as $fieldName => $field)
			{
				if (!isset($field['std']))
				{
					continue;
				}

				if (!empty($field['type']) && $field['type'] === 'builder')
				{
					$currentDate = new DateTime();
					$defaultValues[$fieldName] = [
						[
							'id' => $currentDate->getTimestamp(),
							'name' => 'text_block',
							'settings' => ['text' => $field['std']], 'visibility' => true
						]
					];
				}
				else
				{
					$defaultValues[$fieldName] = $field['std'];
				}
			}
		}

		return $defaultValues;
	}

	private static function extractInteractionFieldsDefaultValues($attributes)
	{
		if (empty($attributes))
		{
			return [];
		}



		$defaultValues = [];

		foreach ($attributes as $fieldName => $field)
		{
			if (!isset($field['std']))
			{
				continue;
			}

			if (!empty($field['type']) && $field['type'] === 'builder')
			{
				$currentDate = new DateTime();
				$defaultValues[$fieldName] = [
					[
						'id' => $currentDate->getTimestamp(),
						'name' => 'text_block',
						'settings' => ['text' => $field['std']], 'visibility' => true
					]
				];
			}
			else
			{
				$defaultValues[$fieldName] = $field['std'];
			}
		}

		return $defaultValues;
	}

	public static function getSectionSettingsDefaultValues()
	{
		if (!\class_exists('SpPgaeBuilderBase'))
		{
			require_once JPATH_ROOT . '/components/com_sppagebuilder/builder/classes/base.php';
		}

		$sectionSettings = SpPgaeBuilderBase::getRowGlobalSettings();

		$sectionDefaults = [];
		$sectionSettingGroups = ['style', 'title', 'responsive', 'animation'];

		foreach ($sectionSettingGroups as $groupName)
		{
			$sectionDefaults = array_merge($sectionDefaults, self::extractSettingsDefaultValues($sectionSettings[$groupName]));
		}

		return $sectionDefaults;
	}

	public static function getColumnSettingsDefaultValues()
	{
		if (!\class_exists('SpPgaeBuilderBase'))
		{
			require_once JPATH_ROOT . '/components/com_sppagebuilder/builder/classes/base.php';
		}

		$columnSettings = SpPgaeBuilderBase::getColumnGlobalSettings();

		$columnDefaults = [];
		$columnSettingGroups = ['style', 'responsive', 'animation'];

		foreach ($columnSettingGroups as $groupName)
		{
			$columnDefaults = array_merge($columnDefaults, self::extractSettingsDefaultValues($columnSettings[$groupName]));
		}

		return $columnDefaults;
	}

	public static function stringifyMediaItem($value)
	{
		if (isset($value->src))
		{
			return $value->src;
		}

		return $value;
	}

	public static function stringifyLinkItem($value)
	{
		$response = (object) [
			'url' => '',
			'attributes' => ''
		];

		if (!\is_object($value) || !isset($value->type))
		{
			$response->url = $value;

			return $response;
		}

		switch ($value->type)
		{
			case 'url':
				$response->url = $value->url;
				break;
			case 'menu':
				$response->url = $value->menu;
				break;
			case 'page':
				$response->url = Route::_('index.php?option=com_sppagebuilder&view=page&id=' . $value->page);
				break;
		}

		if (!empty($value->new_tab))
		{
			$response->attributes = 'target="_blank" rel="';
		}
		else
		{
			return $response;
		}

		if (!empty($value->nofollow))
		{
			$response->attributes .= 'nofollow ';
		}

		if (!empty($value->noreferrer))
		{
			$response->attributes .= 'noreferrer ';
		}

		if (!empty($value->noopener))
		{
			$response->attributes .= 'noopener';
		}

		$response->attributes .= '"';

		return $response;
	}
}
