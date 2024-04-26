<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

use Joomla\CMS\Language\Text;

/** No direct access */
defined('_JEXEC') or die('Restricted access');

if (!\class_exists('SpAddonsConfig'))
{
	require_once JPATH_ROOT . '/components/com_sppagebuilder/builder/classes/config.php';
}

/** Addons helper class */
final class AddonsHelper
{
	private static $validFieldTypes = [
		'hidden',
		'text',
		'gmap',
		'select',
		'category',
		'accesslevel',
		'animation',
		'alert',
		'alignment',
		'headings',
		'link',
		'color',
		'advancedcolor',
		'textarea',
		'separator',
		'header',
		'number',
		'module',
		'checkbox',
		'radio',
		'advancedradio',
		'icon',
		'editor',
		'media',
		'padding',
		'margin',
		'builder',
		'boxshadow',
		'slider',
		'advancedslider',
		'fontstyle',
		'fonts',
		'advancedsettings',
		'typography',
		'codeeditor',
		'gradient',
		'buttons',
		'thumbnail',
		'timeline',
		'repeatable',
		'frontend_navigator',
		'interaction_view'
	];

	private static function hasTabGroups($fieldset)
	{
		return isset($fieldset['tab_groups']) && !empty($fieldset['tab_groups']);
	}

	private static function humanReadableGroupName($name)
	{
		$words = explode('_', $name);

		$words = array_map(function ($word)
		{
			return ucfirst($word);
		}, $words);

		return implode(' ', $words);
	}

	private static function createFieldsGroup($groupName)
	{
		return [
			'title' => self::humanReadableGroupName($groupName),
			'fields' => [],
			'client' => 'both',
			'visibility' => true
		];
	}

	private static function isField($field)
	{
		$hasFieldType = isset($field['type']) && in_array($field['type'], self::$validFieldTypes);

		return $hasFieldType || isset($field['attr']);
	}

	private static function generateUniqueGroupName($groupName, $suffix = false)
	{
		return $groupName . ($suffix ? '-' . uniqid() : '');
	}

	private static function parseField($field, $name)
	{
		unset($field['inline']);
		$field['visibility'] = true;

		if (!isset($field['title']))
		{
			$field['title'] = isset($field['tooltip']) ? $field['tooltip'] : self::humanReadableGroupName($name);
		}

		if (!isset($field['type']) && isset($field['attr']))
		{
			$field['type'] = 'repeatable';
		}

		return $field;
	}

	public static function groupingRepeatableFields($fields)
	{
		$groups = [];

		$groupName = self::generateUniqueGroupName('basic_options_group');
		$group = self::createFieldsGroup('Basic');

		$groups[$groupName] = $group;

		foreach ($fields as $name => &$field)
		{
			// not grouping inner repeatable
			if (!empty($field['type']) && !empty($field['std']) && $field['type'] === 'repeatable')
			{
				$field = self::groupingRepeatableFields($field['attr']);
			}

			if (isset($field['fields']))
			{
				$groups[$name] = $field;
			}
			else
			{
				$groups[$groupName]['fields'][$name] = $field;
			}
		}

		unset($field);

		return $groups;
	}

	public static function modernizeAddonStructure($addon)
	{
		$addon['icon'] = self::getAddonIcon($addon);

		if (empty($addon))
		{
			return $addon;
		}

		$isOldStructure = false;

		if (empty($addon['settings']) && (!empty($addon['inline']['buttons']) || !empty($addon['attr'])))
		{
			$isOldStructure = true;
		}

		if (isset($addon['settings']))
		{
			$addon['settings'] = array_map(function ($group)
			{
				$group['visibility'] = true;
				return $group;
			}, $addon['settings']);

			foreach ($addon['settings'] as &$setting)
			{
				if (!empty($setting['fields']))
				{
					foreach ($setting['fields'] as &$field)
					{
						if (!empty($field['type']) && !empty($field['attr']))
						{
							$field['attr'] = self::groupingRepeatableFields($field['attr']);
							$field['visibility'] = true;
						}

						if (empty($field['type']) && !empty($field['attr']))
						{
							$field['type'] = 'repeatable';
						}
					}

					unset($field);
				}
			}

			unset($setting);

			return $addon;
		}

		$modernAddon = $addon;
		$modernAddon['settings'] = [];

		// @TODO: uncomment this if you need to show deprecation message to the old addons
		// if ($isOldStructure)
		// {
		// 	$groupName = self::generateUniqueGroupName(Text::_('COM_SPPAGEBUILDER_EDITOR_GROUP_ATTENTION'));
		// 	$modernAddon['settings'][$groupName] = self::createFieldsGroup($groupName);
		// 	$modernAddon['settings'][$groupName]['fields']['deprecation_message'] = [
		// 		'type'  => 'alert',
		// 		'title' => Text::_('COM_SPPAGEBUILDER_EDITOR_GROUP_ATTENTION_MESSAGE_TITLE'),
		// 		'is_old_addon' => true,
		// 		'message' => Text::_('COM_SPPAGEBUILDER_EDITOR_GROUP_ATTENTION_MESSAGE'),
		// 	];
		// }

		if (!empty($addon['attr']))
		{
			if (is_array($addon['attr']) || is_object($addon['attr']))
			{
				foreach ($addon['attr'] as $key => $attr)
				{
					$groupName = self::generateUniqueGroupName($key);
					$modernAddon['settings'][$groupName] = self::createFieldsGroup($key);

					$repeatableGroupName = self::generateUniqueGroupName($key, true);
					$modernAddon['settings'][$repeatableGroupName] = self::createFieldsGroup('Inner Items');

					foreach ($attr as $fieldName => $attrItem)
					{
						if (self::isField($attrItem))
						{
							$modernAddon['settings'][$groupName]['fields'][$fieldName] = self::parseField($attrItem, $fieldName);
						}
						else
						{
							if (!empty($attrItem['attr']))
							{
								$attrItem['is_repeatable'] = true;
								$attrItem['type'] = 'repeatable';

								foreach ($attrItem['attr'] as $attrFieldName => &$attrFieldItem)
								{
									$attrFieldItem = self::parseField($attrFieldItem, $attrFieldName);
								}

								unset($attrFieldItem);

								$modernAddon['settings'][$repeatableGroupName]['fields'][$fieldName] = self::parseField($attrItem, $fieldName);
							}
						}
					}
				}
			}
		}

		if (!empty($addon['inline']['buttons']))
		{
			foreach ($addon['inline']['buttons'] as $buttonName => $button)
			{
				$fieldset = $button['fieldset'] ?? null;

				if (!empty($fieldset))
				{
					if (self::hasTabGroups($fieldset))
					{
						$tabGroups = $fieldset['tab_groups'];

						if (!empty($tabGroups))
						{
							foreach ($tabGroups as $tabGroupName => $tabGroup)
							{
								$groupName = self::generateUniqueGroupName($tabGroupName, true);
								$modernAddon['settings'][$groupName] = self::createFieldsGroup($tabGroupName);

								$fields = $tabGroup['fields'] ?? null;

								if (!empty($fields))
								{
									foreach ($fields as $fieldName => $field)
									{
										if (self::isField($field))
										{
											$modernAddon['settings'][$groupName]['fields'][$fieldName] = self::parseField($field, $fieldName);
										}
										else
										{
											foreach ($field as $fieldName => $fieldItem)
											{
												if (self::isField($fieldItem))
												{
													$modernAddon['settings'][$groupName]['fields'][$fieldName] = self::parseField($fieldItem, $fieldName);
												}
											}
										}
									}
								}
							}
						}
					}

					/** If has no tab groups  */
					else
					{
						$groupName = self::generateUniqueGroupName($buttonName);
						$modernAddon['settings'][$groupName] = self::createFieldsGroup($groupName);

						foreach ($fieldset as $fieldName => $field)
						{
							if (self::isField($field))
							{
								$modernAddon['settings'][$groupName]['fields'][$fieldName] = self::parseField($field, $fieldName);
							}
							else
							{
								foreach ($field as $fieldName => $fieldItem)
								{
									if (self::isField($fieldItem))
									{
										$modernAddon['settings'][$groupName]['fields'][$fieldName] = self::parseField($fieldItem, $fieldName);
									}
								}
							}
						}
					}
				}
			}
		}

		$modernAddon['attr'] = [];
		$modernAddon['inline'] = [];

		foreach ($modernAddon['settings'] as &$setting)
		{
			if (!empty($setting['fields']))
			{
				foreach ($setting['fields'] as &$field)
				{
					if (!empty($field['type']) && !empty($field['attr']))
					{
						$field['attr'] = self::groupingRepeatableFields($field['attr']);
						$field['visibility'] = true;
					}
				}

				unset($field);
			}
		}

		unset($setting);

		return $modernAddon;
	}

	public static function getAddonIcon($addon)
	{
		if (empty($addon['icon']))
		{
			return '';
		}

		$extensions = ['png', 'jpg', 'jpeg', 'gif', 'svg'];
		$extension = strtolower(pathinfo($addon['icon'], PATHINFO_EXTENSION));

		if (empty($extension) || !\in_array($extension, $extensions))
		{
			return $addon['icon'];
		}

		return '<img src="' . $addon['icon'] . '" alt="' . $addon['addon_name'] . '" />';
	}
}
