<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Plugin\PluginHelper;

require_once JPATH_ROOT . '/components/com_sppagebuilder/builder/classes/base.php';

class SppagebuilderHelperSite
{
	/**
	 * Addon structures
	 *
	 * @var array
	 */
	private static $addonFieldStructures = [];

	/**
	 * Column settings structure
	 *
	 * @var array
	 */
	private static $columnFieldStructures = [];

	/**
	 * Section/row settings structure
	 *
	 * @var array
	 */
	private static $sectionFieldStructures = [];

	/**
	 * Section field types
	 *
	 * @var array
	 */
	private static $sectionFieldTypes = [];

	/**
	 * Column field types
	 *
	 * @var array
	 */
	private static $columnFieldTypes = [];

	/**
	 * The fields types
	 *
	 * @var array
	 */
	private static $addonFieldTypes = [];


	/**
	 * Check if the data come from the version 3 or bellow.
	 *
	 * @var boolean
	 */
	private static $isLegacyData = false;

	private static function getAddonGlobalFieldStructures()
	{
		$structures = [];

		$globalSettings = SpPgaeBuilderBase::addonOptions();

		foreach ($globalSettings as $setting)
		{
			$structures = array_merge($structures, $setting);
		}

		return $structures;
	}


	/**
	 * Load the addon structure from the admin.php files.
	 *
	 * @return void
	 */
	private static function prepareAddonFieldStructures()
	{
		self::loadLanguage();
		SpPgaeBuilderBase::loadAddons();
		$addons =  SpAddonsConfig::$addons;
		$globalStructures = self::getAddonGlobalFieldStructures();

		foreach ($addons as &$addon)
		{
			$addon = AddonsHelper::modernizeAddonStructure($addon);
			$addon['addon_name'] = preg_replace('/^sp_/i', '', $addon['addon_name']);
			$addon['settings'] = array_merge($addon['settings'], $globalStructures);
		}

		unset($addon);

		self::$addonFieldStructures = $addons;
	}

	private static function prepareSectionFieldStructures()
	{
		$sectionSettings = SpPgaeBuilderBase::getRowGlobalSettings();

		foreach ($sectionSettings as $setting)
		{
			self::$sectionFieldStructures = array_merge(self::$sectionFieldStructures, $setting);
		}
	}

	private static function prepareColumnFieldStructures()
	{
		$columnSettings = SpPgaeBuilderBase::getColumnGlobalSettings();

		foreach ($columnSettings as $setting)
		{
			self::$columnFieldStructures = array_merge(self::$columnFieldStructures, $setting);
		}
	}

	/**
	 * Predict the column's fill style if it is fit in a single line
	 * or multiple line.
	 *
	 * @param     array         $columns     The columns array.
	 *
	 * @return     stdClass     The fit value after prediction.
	 * @since     4.0.0
	 */
	private static function predictColumnFillStyle(array $columns): stdClass
	{
		$fitObject = (object) ['xl' => false, 'lg' => false, 'md' => false, 'sm' => false, 'xs' => false];

		foreach (['xl', 'lg', 'md', 'sm', 'xs'] as $key)
		{
			$total = 0;

			foreach ($columns as $column)
			{
				$width = self::getColumnWidth($column);
				$total += (float) $width->$key;
			}

			if ($total <= 100)
			{
				$fitObject->$key = true;
			}
		}

		return $fitObject;
	}

	/**
	 * Generate the column widths from the columns class_name property.
	 *
	 * @param     stdClass     $column     The column object.
	 *
	 * @return     stdClass                 The generated width object for multiple device.
	 * @since     4.0.0
	 */
	public static function getColumnWidth(stdClass $column): stdClass
	{
		$width = (object) ['xl' => '100%', 'lg' => '100%', 'md' => '100%', 'sm' => '100%', 'xs' => '100%'];
		$size = (int) \substr($column->class_name, 7);
		$value = self::calculateColumnPercent($size);

		$width->xl = $value;

		if (!empty($column->settings->sm_col))
		{
			$smSize = (int) \substr($column->settings->sm_col, 7);
			$width->md = self::calculateColumnPercent($smSize);
			$width->lg = self::calculateColumnPercent($smSize);
		}

		if (!empty($column->settings->xs_col))
		{
			$xsSize = (int) \substr($column->settings->xs_col, 7);
			$width->xs = self::calculateColumnPercent($xsSize);
			$width->sm = self::calculateColumnPercent($xsSize);
		}

		return $width;
	}

	private static function isValidWidthValue($width)
	{
		return preg_match("@^\d+(\.\d+)?%$@", $width);
	}

	public static function purifyColumnWidth($width)
	{
		$defaultDevice = SpPgaeBuilderBase::$defaultDevice;


		$smallDevices = ['sm', 'xs'];

		if (\is_object($width))
		{
			$deviceData = $width->$defaultDevice;

			foreach ($width as $device => $value)
			{
				if (!self::isValidWidthValue($value))
				{
					if (\in_array($device, $smallDevices))
					{
						$width->$device = '100%';
					}
					else
					{
						$width->$device = !empty($deviceData) ? $deviceData : '100%';
					}
				}
			}
		}

		return $width;
	}

	/**
	 * Calculate the column percentage from the column size.
	 *
	 * @param     int     $size     The size value ranged from 1 to 12.
	 *
	 * @return    string            The percentage value w.r.t the the size.
	 * @since     4.0.0
	 */
	private static function calculateColumnPercent(int $size): string
	{
		return ((100 / 12) * (int) $size) . '%';
	}

	// Generate unique id
	public static function nanoid(int $size = 21): string
	{
		$urlAlphabet = "ModuleSymbhasOwnPr-0123456789ABCDEFGHNRVfgctiUvz_KqYTJkLxpZXIjQW";
		$id = "";
		$i = $size;

		while ($i--)
		{
			$id .= $urlAlphabet[rand(0, 63) | 0];
		}

		return $id;
	}

	// Generate unique id
	public static function numberNanoid(int $size = 21): string
	{
		$urlAlphabet = "0123456789";
		$id = "";
		$i = $size;

		while ($i--)
		{
			$id .= $urlAlphabet[rand(0, 9) | 0];
		}

		return intval($id);
	}

	private static function parseRow(array &$rows, \stdClass &$row)
	{
		if (isset($row->settings))
		{
			$row->settings = self::shiftResponsiveSettings($row->settings);
		}

		if (!empty($row->columns))
		{
			if (!isset($row->settings->fit_columns))
			{
				$row->settings->fit_columns = self::predictColumnFillStyle($row->columns);
			}

			foreach ($row->columns as $i => &$column)
			{
				/** Predict the width from the column class name for the old layouts. */
				if (!isset($column->width))
				{
					$width = self::getColumnWidth($column);
					$column->width = $width;
				}

				if (\is_array($column->settings))
				{
					$column->settings = (object) $column->settings;
				}

				if (!isset($column->settings->width))
				{
					$width = !empty($column->width) ? $column->width : self::getColumnWidth($column);
					$column->settings->width = $width;

					if (isset($column->settings->width->unit))
					{
						unset($column->settings->width->unit);
					}
				}

				$column->settings->width = self::purifyColumnWidth($column->settings->width);

				if (isset($column->settings))
				{
					$column
						->settings = self::shiftResponsiveSettings($column->settings);
				}

				if (!empty($column->addons))
				{
					foreach ($column->addons as $j => &$addon)
					{
						if (isset($addon->settings))
						{
							$addon->settings = self::shiftResponsiveSettings($addon->settings);
						}

						/** Migrate the slideshow items. */
						if (isset($addon->name) && $addon->name === 'js_slideshow')
						{
							if (!empty($addon->settings->slideshow_items))
							{
								foreach ($addon->settings->slideshow_items as $x => &$slideshowItem)
								{
									if (isset($slideshowItem->slider_overlay_options) && $slideshowItem->slider_overlay_options === 'gradient_overaly')
									{
										$slideshowItem->slider_overlay_options = 'gradient_overlay';
									}

									if (isset($slideshowItem->slider_bg_gradient_overlay) && !isset($slideshowItem->slider_bg_gradient_overlay->type))
									{
										$slideshowItem->slider_bg_gradient_overlay->type = 'linear';
									}

									$slideshowItem = self::shiftResponsiveSettings($slideshowItem);

									if (!empty($slideshowItem->slideshow_inner_items))
									{
										foreach ($slideshowItem->slideshow_inner_items as $y => &$innerItem)
										{
											$innerItem = self::shiftResponsiveSettings($innerItem);
										}

										unset($innerItem);
									}
								}

								unset($slideshowItem);
							}
						}

						/** Migrate the responsive settings for the repeatable items. */
						if (isset($addon->name) && \in_array($addon->name, ['accordion', 'tab']))
						{
							$repeatableKey = 'sp_' . $addon->name . '_item';

							if (!empty($addon->settings->$repeatableKey))
							{
								foreach ($addon->settings->$repeatableKey as &$itemSetting)
								{
									$itemSetting = self::shiftResponsiveSettings($itemSetting);
								}

								unset($itemSetting);
							}
						}

						if (isset($addon->name) && \in_array($addon->name, ['accordion', 'tab']))
						{
							list($outerRows, $_addon) = self::migrateDeepAddon($addon, 'sp_' . $addon->name . '_item', $row, $column);
							$addon = $_addon;
							array_push($rows, ...$outerRows);
						}

						if (isset($addon->name) && $addon->name === 'table_advanced')
						{
							$nodeId = self::generateUUID();

							if (isset($addon->settings->sp_table_advanced_item))
							{
								foreach ($addon->settings->sp_table_advanced_item as $th => $thead)
								{
									if (isset($thead->content) && !\is_array($thead->content))
									{
										$thead = ['id' => $nodeId++, 'name' => 'text_block', 'visibility' => true, 'reference_id' => $addon->id, 'settings' => ['text' => $thead->content]];
										$thead = \json_decode(\json_encode($thead));
										$addon
											->settings
											->sp_table_advanced_item[$th]
											->content = [];
										$addon
											->settings
											->sp_table_advanced_item[$th]
											->content[] = $thead;
									}
									elseif (isset($thead->content) && \is_array($thead->content))
									{
										$contents = [];

										foreach ($thead->content as $content)
										{
											$content->reference_id = $addon->id;
											$contents[] = $content;
										}

										$addon
											->settings
											->sp_table_advanced_item[$th]
											->content = $contents;
									}
								}
							}

							foreach ($addon->settings->table_advanced_item as $r => $tRow)
							{
								if (isset($tRow->table_advanced_item))
								{
									foreach ($tRow->table_advanced_item as $c => $tCell)
									{
										if (isset($tCell->content) && !\is_array($tCell->content))
										{
											$td = ['id' => $nodeId++, 'name' => 'text_block', 'visibility' => true, 'reference_id' => $addon->id, 'settings' => ['text' => $tCell->content]];
											$td = \json_decode(\json_encode($td));

											$addon
												->settings
												->table_advanced_item[$r]
												->table_advanced_item[$c]
												->content = [];
											$addon
												->settings
												->table_advanced_item[$r]
												->table_advanced_item[$c]
												->content[] = $td;
										}
										elseif (isset($tCell->content) && \is_array($tCell->content))
										{
											$contents = [];

											foreach ($tCell->content as &$content)
											{
												$content->reference_id = $addon->id;
												$contents[] = $content;
											}

											$addon
												->settings
												->table_advanced_item[$r]
												->table_advanced_item[$c]
												->content = $contents;

											unset($content);
										}
									}
								}
							}
						}

						if (isset($addon->type) && $addon->type === 'inner_row')
						{
							$addon->id = self::nanoid();

							$nestedRowAddon = new \stdClass;
							$nestedRowAddon->type = 'nested_row';
							$nestedRowAddon->name = 'row';
							$nestedRowAddon->id = $addon->id;

							$addon->parent = new \stdClass;
							$addon->parent->rowId = $row->id;
							$addon->parent->columnId = $column->id;

							$rows[] = $addon;
							$addon = $nestedRowAddon;
						}
					}

					unset($addon);
				}
			}

			unset($column);
		}
	}

	/**
	 * sanitize import json for making the old data valid for the data structure.
	 *
	 * @param    string    $text    The json text of the page builder data.
	 *
	 * @return   string    The sanitized text.
	 * 
	 * @since    4.0.0
	 */
	public static function sanitizeImportJSON(string $text): string
	{
		$rows = json_decode($text);

		if (is_string($rows))
		{
			$rows = json_decode($rows);
		}

		if (!empty($rows))
		{
			foreach ($rows as $key => &$row)
			{
				self::parseRow($rows, $row);
			}

			unset($row);
		}
		else
		{
			return $text;
		}

		return json_encode($rows);
	}

	private static $defaultValueWithUnit = ['value' => '', 'unit' => 'px'];

	private static function getDefaultTypographyData()
	{
		$deviceData = [
			'xl' => self::$defaultValueWithUnit,
			'lg' => self::$defaultValueWithUnit,
			'md' => self::$defaultValueWithUnit,
			'sm' => self::$defaultValueWithUnit,
			'xs' => self::$defaultValueWithUnit,
		];

		return (object) [
			'font' => '',
			'size' => $deviceData,
			'line_height' => $deviceData,
			'letter_spacing' => $deviceData,
			'uppercase' => false,
			'italic' => false,
			'underline' => false,
			'weight' => '',
			'type' => 'google',
		];
	}

	private static function isUnitValue($data)
	{
		return isset($data->value) && isset($data->unit);
	}

	private static function handleDeviceValues($data, $fieldKey)
	{
		$deviceData = [
			'xl' => self::$defaultValueWithUnit,
			'lg' => self::$defaultValueWithUnit,
			'md' => self::$defaultValueWithUnit,
			'sm' => self::$defaultValueWithUnit,
			'xs' => self::$defaultValueWithUnit,
		];
		$responsiveKeys = ['size', 'line_height', 'letter_spacing'];
		$pattern = "@px|rem|em|%@i";

		if (in_array($fieldKey, $responsiveKeys))
		{
			if (\is_object($data))
			{
				$finalValue = self::$defaultValueWithUnit;

				foreach ($data as $device => $value)
				{
					if (self::isUnitValue($value))
					{
						$finalValue = (array) $value;
						$finalValue['value'] = preg_match($pattern, $finalValue['value']) ? (float) $finalValue['value'] : $finalValue['value'];
						$finalValue['value'] = (string) $finalValue['value'];
					}
					else
					{
						$finalValue['value'] = $value ?? '';
						$finalValue['value'] = preg_match($pattern, $finalValue['value']) ? (float) $finalValue['value'] : $finalValue['value'];
						$finalValue['value'] = (string) $finalValue['value'];
					}

					$deviceData[$device] = $finalValue;
				}
			}
			else
			{
				$deviceData['xl']['value'] = preg_match($pattern, $data) ? (float) $data : $data;
				$deviceData['xl']['value'] = (string) $deviceData['xl']['value'];
			}

			if (!empty($deviceData['md']['value']) && empty($deviceData['xl']['value']))
			{
				$deviceData['xl'] = $deviceData['md'];
			}

			return (object) $deviceData;
		}

		return $data;
	}

	private static function getFieldsWithFallbacks($settings)
	{

		$fieldArray = [];

		if (empty($settings))
		{
			return $fieldArray;
		}

		foreach ($settings as $setting)
		{
			if (!empty($setting['fields']))
			{
				foreach ($setting['fields'] as $fieldName => &$field)
				{
					if (isset($field['type']) && $field['type'] === 'repeatable' && !empty($field['attr']))
					{
						$field['attr'] = self::getFieldsWithFallbacks($field['attr'] ?? []);

						if (!empty($field['attr']))
						{
							$fieldArray[$fieldName] = $field;
						}
					}

					if (!empty($field['fallbacks']))
					{
						$fieldArray[$fieldName] = $field;
					}
				}

				unset($field);
			}
		}

		return $fieldArray;
	}

	private static function getFieldStructureForTypography()
	{
		$fallbackFields = [];

		foreach (self::$addonFieldStructures as &$addon)
		{
			$addonName = $addon['addon_name'];
			$fallbackFields[$addonName] = self::getFieldsWithFallbacks($addon['settings'] ?? []);
		}

		unset($addon);

		return $fallbackFields;
	}

	private static function retrieveFieldTypesFromSettings($settings)
	{
		$types = [];

		foreach ($settings as $group)
		{
			if (!empty($group['fields']))
			{
				foreach ($group['fields'] as $key => $field)
				{
					$fieldtype = $field['type'] ?? '';

					if ($fieldtype === 'repeatable')
					{
						$types[$key] = [
							'type' => 'repeatable',
							'fields' => self::retrieveFieldTypesFromSettings($field['attr'])
						];
					}
					else
					{
						$types[$key] = $fieldtype;
					}
				}
			}
		}

		return $types;
	}

	private static function processFieldTypes($type)
	{
		switch ($type)
		{
			case 'section':
				self::$sectionFieldTypes = self::retrieveFieldTypesFromSettings(self::$sectionFieldStructures);
				break;
			case 'column':
				self::$columnFieldTypes = self::retrieveFieldTypesFromSettings(self::$columnFieldStructures);
				break;
			case 'addon':
				foreach (self::$addonFieldStructures as $addon)
				{
					$addonName = $addon['addon_name'];
					$addonFieldTypes[$addonName] = self::retrieveFieldTypesFromSettings($addon['settings']);
				}

				self::$addonFieldTypes = $addonFieldTypes;
				break;
		}
	}

	private static function parseResponsiveTypography($data)
	{
		if (!is_object($data))
		{
			return $data;
		}

		$keys = ['letter_spacing', 'size', 'line_height'];

		foreach ($keys as $key)
		{
			$typographyItem = $data->$key ?? null;

			if (!$typographyItem)
			{
				continue;
			}

			if (!empty($typographyItem->md->value) && empty($typographyItem->xl->value))
			{
				$data->$key->xl = clone $typographyItem->md;
			}
		}

		return $data;
	}

	private static function getFallbackValues($settings, string $fieldName, $field)
	{
		$fallbacks = $field['fallbacks'] ?? [];

		if (!isset($settings->$fieldName))
		{
			$settings->$fieldName = new stdClass;
			$isTypographyField = isset($field['type']) && $field['type'] === 'typography';

			if ($isTypographyField)
			{
				$settings->$fieldName = self::getDefaultTypographyData();
			}

			foreach ($fallbacks as $fieldKey => $referenceKey)
			{
				$fallbackParts = explode('.', $referenceKey, 2);
				$masterKey = $fallbackParts[0] ?? null;
				$slaveKey = $fallbackParts[1] ?? null;

				if (!empty($masterKey) && !empty($slaveKey))
				{
					$fallbackValue = isset($settings->$masterKey->$slaveKey) ? $settings->$masterKey->$slaveKey : '';
				}
				elseif (!empty($masterKey) && empty($slaveKey))
				{
					$fallbackValue = isset($settings->$masterKey) ? $settings->$masterKey : '';
				}

				if ($isTypographyField)
				{
					$settings->$fieldName->$fieldKey = self::handleDeviceValues($fallbackValue, $fieldKey);
				}
				else
				{
					$settings->$fieldName->$fieldKey = $fallbackValue;
				}
			}
		}
		else
		{
			$settings->$fieldName = self::parseResponsiveTypography($settings->$fieldName);
		}

		return $settings;
	}

	/**
	 * Read the fallback values of the typography fields.
	 *
	 * @param 	stdClass 	$settings
	 * @param 	string 		$addonName
	 *
	 * @return 	stdClass 	The modified settings value.
	 * @since 	5.0.0
	 */
	private static function getTypographyFromFallbacks($settings, $addonName)
	{

		$fieldStructures = self::getFieldStructureForTypography();
		$fieldStructureByAddon = $fieldStructures[$addonName] ?? null;

		if (empty($fieldStructureByAddon))
		{
			return $settings;
		}

		foreach ($settings as $key => &$setting)
		{
			$field = !empty($fieldStructureByAddon[$key]) ? $fieldStructureByAddon[$key] : [];

			if (!empty($field['type']) && $field['type'] === 'repeatable' && !empty($field['attr']))
			{
				$attributes = $field['attr'];

				foreach ($attributes as $fieldName => $attribute)
				{
					$fallbacks = $attribute['fallbacks'] ?? [];

					if (is_array($setting) && !empty($setting))
					{
						foreach ($setting as &$value)
						{
							$value = self::getFallbackValues($value, $fieldName, $attribute);
						}

						unset($value);
					}
				}
			}
		}

		unset($setting);

		foreach ($fieldStructureByAddon as $fieldName => $field)
		{
			$fallbacks = $field['fallbacks'] ?? [];

			if (!empty($fallbacks))
			{
				$settings = self::getFallbackValues($settings, $fieldName, $field);
			}
		}

		return $settings;
	}

	private static function reshapeSpacingValues($spacing, $forceModern = false)
	{
		$isLegacy = self::$isLegacyData && !$forceModern;
		$reshapedValue = $isLegacy ? ['', '', '', ''] : ['0px', '0px', '0px', '0px'];

		$spacing = \ctype_space((string) $spacing) || $spacing === '' ? [] : explode(' ', $spacing);
		$spacing = array_map(function($value) use($isLegacy) {
			if (!$isLegacy && $value === '') {
				return '0px';
			}

			return $value;
		}, $spacing);

		$spacing = array_filter($spacing, function ($value)
		{
			return isset($value) && $value !== '';
		});

		$spacing = array_values($spacing);
		$newSpacing = [];

		switch (count($spacing))
		{
			case 0:
				$newSpacing = [''];
				break;
			case 1:
				$newSpacing = $isLegacy
					? array_fill(0, 4, $spacing[0])
					: array_replace($reshapedValue, $spacing);
				break;
			case 2:
				$newSpacing = $isLegacy
					? [$spacing[0], $spacing[1], $spacing[0], $spacing[1]]
					: array_replace($reshapedValue, $spacing);
				break;
			case 3:
				$newSpacing = $isLegacy
					? [$spacing[0], $spacing[1], $spacing[2], $spacing[1]]
					: array_replace($reshapedValue, $spacing);
				break;
			case 4:
				$newSpacing = $spacing;
				break;
		}

		return implode(" ", $newSpacing);
	}

	private static function transformSpacingValues($value, $forceModern = false)
	{
		if (self::hasMultiDeviceSettings($value))
		{
			foreach ($value as $device => $deviceData)
			{
				$value->$device = !empty($deviceData) ? self::reshapeSpacingValues($deviceData, $forceModern) : $deviceData;
			}
		}
		elseif (is_string($value))
		{
			$value = self::reshapeSpacingValues($value, $forceModern);
		}

		return $value;
	}

	private static function traverseSettingsToFixSpacingAnomalies($settings, $fieldTypes, $forceModern = false)
	{
		if ($fieldTypes === null)
		{
			return $settings;
		}

		if (!empty($settings))
		{
			foreach ($settings as $key => &$value)
			{
				$fieldType = $fieldTypes[$key] ?? null;

				if ($fieldType === null || (is_string($fieldType) && !in_array($fieldType, ['padding', 'margin'])))
				{
					continue;
				}

				if (is_array($fieldType))
				{
					$repeatableTypes = $fieldType['fields'];

					if (is_array($value))
					{
						foreach ($value as &$repeatableValue)
						{
							$repeatableValue = self::traverseSettingsToFixSpacingAnomalies($repeatableValue, $repeatableTypes, false);
						}
					}
				}
				else
				{
					$value = self::transformSpacingValues($value, $forceModern);
				}
			}

			unset($value);
		}

		return $settings;
	}

	private static function parsingSpacingValues($settings, $type, $addonName = '')
	{
		if ($type === 'addon'  && (empty($addonName) || !isset(self::$addonFieldTypes[$addonName])))
		{
			return $settings;
		}

		switch ($type)
		{
			case 'section':
				$fieldTypes = self::$sectionFieldTypes;
				break;
			case 'column':
				$fieldTypes = self::$columnFieldTypes;
				break;
			case 'addon':
				$fieldTypes = self::$addonFieldTypes[$addonName];
				break;
		}

		return self::traverseSettingsToFixSpacingAnomalies($settings, $fieldTypes, in_array($type, ['section', 'column']));
	}

	private static function hasLegacyDataInsideSettings($settings)
	{
		if (!empty($settings))
		{
			foreach ($settings as $setting)
			{
				if (self::hasMultiDeviceSettings($setting) && isset($setting->xl))
				{
					return false;
				}
			}
		}

		return true;
	}

	private static function isLegacyDataStructure($content)
	{
		foreach ($content as $row)
		{
			$settings = $row->settings;

			if (!self::hasLegacyDataInsideSettings($settings))
			{
				return false;
			}
		}

		return true;
	}

	public static function prepareSpacingData($text)
	{
		self::prepareSectionFieldStructures();
		self::prepareColumnFieldStructures();
		self::prepareAddonFieldStructures();
		self::processFieldTypes('section');
		self::processFieldTypes('column');
		self::processFieldTypes('addon');

		$content = is_string($text) ? json_decode($text) : $text;

		self::$isLegacyData = self::isLegacyDataStructure($content);

		if (!empty($content))
		{
			foreach ($content as &$section)
			{
				$section->settings = self::parsingSpacingValues($section->settings, 'section');

				if (!empty($section->columns))
				{
					foreach ($section->columns as &$column)
					{
						$column->settings = self::parsingSpacingValues($column->settings, 'column');

						if (!empty($column->addons))
						{
							foreach ($column->addons as &$addon)
							{
								if (!empty($addon->settings))
								{
									$addon->settings = self::parsingSpacingValues($addon->settings, 'addon', $addon->name ?? '');
								}
							}

							unset($addon);
						}
					}

					unset($column);
				}
			}

			unset($section);
		}

		return json_encode($content);
	}

	/**
	 * sanitize contents for making the old data valid for the data structure.
	 *
	 * @param    string    $text    The json text of the page builder data.
	 *
	 * @return   string    The sanitized text.
	 * 
	 * @since    4.0.0
	 */
	public static function sanitize(string $text): string
	{
		if (empty(self::$addonFieldStructures))
		{
			self::prepareAddonFieldStructures();
		}

		$rows = json_decode($text);

		if (!empty($rows))
		{
			foreach ($rows as $key => &$row)
			{
				if (isset($rows[$key]->settings))
				{
					$rows[$key]->settings = self::shiftResponsiveSettings($row->settings);
					$rows[$key]->settings = self::fixRowSettings($row->settings);
				}

				if (!empty($row->columns))
				{
					if (!isset($row->settings->fit_columns))
					{
						$row->settings->fit_columns = self::predictColumnFillStyle($row->columns);
					}

					foreach ($row->columns as $i => &$column)
					{
						/** Predict the width from the column class name for the old layouts. */
						if (!isset($column->width))
						{
							$width = self::getColumnWidth($column);
							$column->width = $width;
						}

						if (\is_array($column->settings))
						{
							$column->settings = (object) $column->settings;
						}

						if (!isset($column->settings->width))
						{
							$width = !empty($column->width) ? $column->width : self::getColumnWidth($column);
							$column->settings->width = $width;

							if (isset($column->settings->width->unit))
							{
								unset($column->settings->width->unit);
							}
						}

						$column->settings->width = self::purifyColumnWidth($column->settings->width);

						if (isset($rows[$key]->columns[$i]->settings))
						{
							$rows[$key]
								->columns[$i]
								->settings = self::shiftResponsiveSettings($rows[$key]->columns[$i]->settings);
						}

						if (!empty($column->addons))
						{
							foreach ($column->addons as $j => &$addon)
							{
								if (isset($rows[$key]->columns[$i]->addons[$j]->settings))
								{
									$rows[$key]
										->columns[$i]
										->addons[$j]
										->settings = self::shiftResponsiveSettings($rows[$key]->columns[$i]->addons[$j]->settings);

									if (isset($addon->name) && $addon->name === 'image_layouts')
									{
										$addonSettings = $addon->settings;

										$isBtnTextExist = property_exists($addonSettings, 'btn_text');
										$isButtonTextExist = property_exists($addonSettings, 'button_text');

										if (!$isButtonTextExist && $isBtnTextExist)
										{
											$addonSettings->button_text = $addonSettings->btn_text;
										}

										$isBtnTypeExist = property_exists($addonSettings, 'btn_type');
										$isButtonTypeExist = property_exists($addonSettings, 'button_type');

										if (!$isButtonTypeExist && $isBtnTypeExist)
										{
											$addonSettings->button_type = $addonSettings->btn_type;
										}

										$isBtnShapeExist = property_exists($addonSettings, 'btn_shape');
										$isButtonShapeExist = property_exists($addonSettings, 'button_shape');

										if (!$isButtonShapeExist && $isBtnShapeExist)
										{
											$addonSettings->button_shape = $addonSettings->btn_shape;
										}

										$isBtnSizeExist = property_exists($addonSettings, 'btn_size');
										$isButtonSizeExist = property_exists($addonSettings, 'button_size');

										if (!$isButtonSizeExist && $isBtnSizeExist)
										{
											$addonSettings->button_size = $addonSettings->btn_size;
										}

										$isBtnColorExist = property_exists($addonSettings, 'btn_color');
										$isButtonColorExist = property_exists($addonSettings, 'button_color');

										if (!$isButtonColorExist && $isBtnColorExist)
										{
											$addonSettings->button_color = $addonSettings->btn_color;
										}

										$isBtnColorHoverExist = property_exists($addonSettings, 'btn_color_hover');
										$isButtonColorHoverExist = property_exists($addonSettings, 'button_color_hover');

										if (!$isButtonColorHoverExist && $isBtnColorHoverExist)
										{
											$addonSettings->button_color_hover = $addonSettings->btn_color_hover;
										}

										$isBtnAppearanceExist = property_exists($addonSettings, 'btn_appearance');
										$isButtonAppearanceExist = property_exists($addonSettings, 'button_appearance');

										if (!$isButtonAppearanceExist && $isBtnAppearanceExist)
										{
											$addonSettings->button_appearance = $addonSettings->btn_appearance;
										}

										$isBtnBackgroundColorExist = property_exists($addonSettings, 'btn_background_color');
										$isButtonBackgroundColorExist = property_exists($addonSettings, 'button_background_color');

										if (!$isButtonBackgroundColorExist && $isBtnBackgroundColorExist)
										{
											$addonSettings->button_background_color = $addonSettings->btn_background_color;
										}

										$isBtnBackgroundColorHoverExist = property_exists($addonSettings, 'btn_background_color_hover');
										$isButtonBackgroundColorHoverExist = property_exists($addonSettings, 'button_background_color_hover');

										if (!$isButtonBackgroundColorHoverExist && $isBtnBackgroundColorHoverExist)
										{
											$addonSettings->button_background_color_hover = $addonSettings->btn_background_color_hover;
										}

										$isBtnBackgroundGradientExist = property_exists($addonSettings, 'btn_background_gradient');
										$isButtonBackgroundGradientExist = property_exists($addonSettings, 'button_background_gradient');

										if (!$isButtonBackgroundGradientExist && $isBtnBackgroundGradientExist)
										{
											$addonSettings->button_background_gradient = $addonSettings->btn_background_gradient;
										}

										$isBtnBackgroundGradientHoverExist = property_exists($addonSettings, 'btn_background_gradient_hover');
										$isButtonBackgroundGradientHoverExist = property_exists($addonSettings, 'button_background_gradient_hover');

										if (!$isButtonBackgroundGradientHoverExist && $isBtnBackgroundGradientHoverExist)
										{
											$addonSettings->button_background_gradient_hover = $addonSettings->btn_background_gradient_hover;
										}
									}
								}

								if (isset($addon->name) && isset($addon->settings))
								{
									$addon->settings = self::getTypographyFromFallbacks($addon->settings, $addon->name);
								}

								/** Migrate the slideshow items. */
								if (isset($addon->name) && $addon->name === 'js_slideshow')
								{
									if (!empty($addon->settings->slideshow_items))
									{
										foreach ($addon->settings->slideshow_items as $x => &$slideshowItem)
										{
											if (isset($slideshowItem->slider_overlay_options) && $slideshowItem->slider_overlay_options === 'gradient_overaly')
											{
												$slideshowItem->slider_overlay_options = 'gradient_overlay';
											}

											if (isset($slideshowItem->slider_bg_gradient_overlay) && !isset($slideshowItem->slider_bg_gradient_overlay->type))
											{
												$slideshowItem->slider_bg_gradient_overlay->type = 'linear';
											}

											$slideshowItem = self::shiftResponsiveSettings($slideshowItem);

											if (!empty($slideshowItem->slideshow_inner_items))
											{
												foreach ($slideshowItem->slideshow_inner_items as $y => &$innerItem)
												{
													$innerItem = self::shiftResponsiveSettings($innerItem);
												}

												unset($innerItem);
											}
										}

										unset($slideshowItem);
									}
								}

								/** Migrate the responsive settings for the repeatable items. */
								if (isset($addon->name) && \in_array($addon->name, ['accordion', 'tab']))
								{
									$repeatableKey = 'sp_' . $addon->name . '_item';

									if (!empty($addon->settings->$repeatableKey))
									{
										foreach ($rows[$key]->columns[$i]->addons[$j]->settings->$repeatableKey as &$itemSetting)
										{
											$itemSetting = self::shiftResponsiveSettings($itemSetting);
										}

										unset($itemSetting);
									}
								}

								if (isset($addon->name) && \in_array($addon->name, ['accordion', 'tab']))
								{
									list($outerRows, $addon) = self::migrateDeepAddon($addon, 'sp_' . $addon->name . '_item', $row, $column);
									$rows[$key]->columns[$i]->addons[$j] = $addon;
									array_push($rows, ...$outerRows);
								}

								if (isset($addon->name) && $addon->name === 'table_advanced')
								{
									$nodeId = self::generateUUID();

									if (isset($addon->settings->sp_table_advanced_item))
									{
										foreach ($addon->settings->sp_table_advanced_item as $th => $thead)
										{
											if (isset($thead->content) && !\is_array($thead->content))
											{
												$thead = ['id' => $nodeId++, 'name' => 'text_block', 'visibility' => true, 'reference_id' => $addon->id, 'settings' => ['text' => $thead->content]];
												$thead = \json_decode(\json_encode($thead));
												$rows[$key]
													->columns[$i]
													->addons[$j]
													->settings
													->sp_table_advanced_item[$th]
													->content = [];
												$rows[$key]
													->columns[$i]
													->addons[$j]
													->settings
													->sp_table_advanced_item[$th]
													->content[] = $thead;
											}
											elseif (isset($thead->content) && \is_array($thead->content))
											{
												$contents = [];

												foreach ($thead->content as $content)
												{
													$content->reference_id = $addon->id;
													$contents[] = $content;
												}

												$rows[$key]
													->columns[$i]
													->addons[$j]
													->settings
													->sp_table_advanced_item[$th]
													->content = $contents;
											}
										}
									}

									foreach ($addon->settings->table_advanced_item as $r => $tRow)
									{
										if (isset($tRow->table_advanced_item))
										{
											foreach ($tRow->table_advanced_item as $c => $tCell)
											{
												if (isset($tCell->content) && !\is_array($tCell->content))
												{
													$td = ['id' => $nodeId++, 'name' => 'text_block', 'visibility' => true, 'reference_id' => $addon->id, 'settings' => ['text' => $tCell->content]];
													$td = \json_decode(\json_encode($td));

													$rows[$key]
														->columns[$i]
														->addons[$j]
														->settings
														->table_advanced_item[$r]
														->table_advanced_item[$c]
														->content = [];
													$rows[$key]
														->columns[$i]
														->addons[$j]
														->settings
														->table_advanced_item[$r]
														->table_advanced_item[$c]
														->content[] = $td;
												}
												elseif (isset($tCell->content) && \is_array($tCell->content))
												{
													$contents = [];

													foreach ($tCell->content as &$content)
													{
														$content->reference_id = $addon->id;
														$contents[] = $content;
													}

													$rows[$key]
														->columns[$i]
														->addons[$j]
														->settings
														->table_advanced_item[$r]
														->table_advanced_item[$c]
														->content = $contents;

													unset($content);
												}
											}
										}
									}
								}

								if (isset($addon->type) && $addon->type === 'inner_row')
								{
									$nestedRowAddon = new \stdClass;
									$nestedRowAddon->type = 'nested_row';
									$nestedRowAddon->name = 'row';
									$nestedRowAddon->id = $addon->id;
									$addon->parent = new \stdClass;
									$addon->parent->rowId = $row->id;
									$addon->parent->columnId = $column->id;

									unset($addon->type);
									$rows[] = $addon;
									$rows[$key]->columns[$i]->addons[$j] = $nestedRowAddon;
								}
							}

							unset($addon);
						}
					}

					unset($column);
				}
			}

			unset($row);
		}
		else
		{
			return $text;
		}

		return json_encode($rows);
	}

	public static function fixRowSettings($settings)
	{
		if (isset($settings->background_type))
		{
			return $settings;
		}

		$settings->background_type = 'none';

		$keyMap = [
			'background_image' => 'image',
			'background_color' => 'color',
			'background_gradient' => 'gradient',
			'background_video' => 'video',
			'background_video_mp4' => 'video',
			'background_video_ogv' => 'video',
			'background_external_video' => 'video'
		];

		foreach ($keyMap as $key => $value)
		{
			if (!empty($settings->$key))
			{
				$settings->background_type = $value;
				break;
			}
		}

		return $settings;
	}

	/**
	 * Migrate the accordion addon to the current structure.
	 *
	 * @param     \stdClass     $addon    The accordion addon object.
	 *
	 * @return     array
	 * @since     4.0.0
	 */
	private static function migrateDeepAddon(\stdClass $addon, $key, $row, $column): array
	{
		$addon = json_decode(json_encode($addon));
		$addonCollection = [];
		$outerRows = [];

		if (!isset($addon->parent) || (isset($addon->parent) && !$addon->parent))
		{
			$addon->id = self::nanoid();
		}

		if (isset($addon->settings->$key))
		{
			foreach ($addon->settings->$key as $itemIndex => $item)
			{
				$addonCollection = [];

				if (isset($item->content) && \is_array($item->content))
				{
					foreach ($item->content as $deepAddon)
					{
						if (isset($deepAddon->type) && $deepAddon->type === 'nested_row')
						{
							continue;
						}

						$addonCollection[] = $deepAddon;
					}

					if (\count($addonCollection) > 0)
					{
						$_parent = ['rowId' => $row->id, 'columnId' => $column->id];
						$_parent = (object) $_parent;
						$row = self::createRow('12', $addonCollection, $_parent);
						$row->parent_addon = $addon->id;

						$outerRows[] = $row;
						$nestedRow = ['type' => 'nested_row', 'id' => $row->id, 'name' => 'row'];
						$nestedRow = (object) $nestedRow;
						$addon->settings->$key[$itemIndex]->content = [];
						$addon->settings->$key[$itemIndex]->content[] = $nestedRow;
					}
				}
				else if (isset($item->content) && \is_string($item->content))
				{
					$textAddon = ['id' => self::nanoid(), 'name' => 'text_block', 'settings' => ['text' => $item->content]];
					$addonCollection[] = $textAddon;

					$_parent = ['rowId' => $row->id, 'columnId' => $column->id];
					$_parent = (object) $_parent;
					$row = self::createRow('12', $addonCollection, $_parent);
					$row->parent_addon = $addon->id;

					$outerRows[] = $row;
					$nestedRow = ['type' => 'nested_row', 'id' => $row->id, 'name' => 'row'];
					$nestedRow = (object) $nestedRow;
					$addon->settings->$key[$itemIndex]->content = [];
					$addon->settings->$key[$itemIndex]->content[] = $nestedRow;
				}
			}
		}

		return [$outerRows, $addon];
	}

	/**
	 * Create Row function
	 *
	 * @param string $layout Default layout size
	 * @param array $addons Addons
	 * @param mixed $parent Parent row.
	 *   
	 * @return object
	 * 
	 * @since 4.0.0
	 */
	public static function createRow(string $layout = '12', array $addons = [], $parent = null)
	{
		$rowId = self::nanoid();
		$layouts = explode('+', $layout);

		$rowDefaultValues = EditorUtils::getSectionSettingsDefaultValues();
		$columnDefaultValues = EditorUtils::getColumnSettingsDefaultValues();

		$rowDefaultValues = json_decode(json_encode($rowDefaultValues));
		$columnDefaultValues = json_decode(json_encode($columnDefaultValues));

		$columns = array_map(function ($col) use ($columnDefaultValues, $addons)
		{
			$width = (float) ((100 / (12 / (int) $col))) . '%';
			$widthObject = ['xl' => $width, 'lg' => $width, 'md' => $width, 'sm' => '100%', 'xs' => '100%'];
			$widthObject = (object) $widthObject;

			$columnObject = [
				'id' => self::nanoid(),
				'class_name' => 'row-column',
				'visibility' => true,
				'settings' => $columnDefaultValues,
				'addons' => $addons,
				'width' => $widthObject,
			];

			return (object) $columnObject;
		}, $layouts);

		$rowDefaultValues->padding = '5px 0px 5px 0px';
		$rowDefaultValues->margin = '0px 0px 0px 0px';

		$rowObject = [
			'id' => $rowId,
			'visibility' => true,
			'collapse' => false,
			'settings' => $rowDefaultValues,
			'layout' => $layout,
			'columns' => $columns,
			'parent' => $parent ? $parent : false,
		];

		return (object) $rowObject;
	}

	/**
	 * Generate a unique ID by using microtime.
	 *
	 * @return     integer
	 * @since     4.0.0
	 */
	public static function generateUUID(): int
	{
		return (int) (microtime(true) * 1000);
	}

	/**
	 * Shift responsive device settings for with the new device structure.
	 *
	 * @param     \stdClass     $settings    The settings value.
	 *
	 * @return     \stdClass | null
	 * @since     4.0.0
	 */
	public static function shiftResponsiveSettings($settings)
	{
		if (!empty($settings))
		{
			foreach ($settings as $key => $setting)
			{
				if (\is_object($setting) && isset($setting->md) && !isset($setting->xl))
				{
					$tmp = (object) ['xl' => '', 'lg' => '', 'md' => '', 'sm' => '', 'xs' => ''];

					if (isset($setting->md))
					{
						$tmp->xl = $setting->md;
					}

					if (isset($setting->sm))
					{
						$tmp->lg = $setting->sm;
						$tmp->md = $setting->sm;
					}

					if (isset($setting->xs))
					{
						$tmp->sm = $setting->xs;
						$tmp->xs = $setting->xs;
					}

					if (isset($setting->unit))
					{
						$tmp->unit = $setting->unit;
					}

					$settings->$key = $tmp;
				}
			}

			if (isset($settings->hidden_md) && !isset($settings->hidden_xl))
			{
				if (isset($settings->hidden_md))
				{
					$settings->hidden_xl = $settings->hidden_md;
				}

				if (isset($settings->hidden_sm))
				{
					$settings->hidden_lg = $settings->hidden_sm;
					$settings->hidden_md = $settings->hidden_sm;
				}

				if (isset($settings->hidden_xs))
				{
					$settings->hidden_sm = $settings->hidden_xs;
					$settings->hidden_xs = $settings->hidden_xs;
				}
			}
		}

		return $settings;
	}


	/**
	 * Remove sp_ from the addon name
	 *
	 * @return    void
	 * @since    4.0.0
	 */
	public static function sanitize_addon_name($addon_name)
	{
		$from = '/' . preg_quote('sp_', '/') . '/';
		return preg_replace($from, '', $addon_name, 1);
	}

	/**
	 * Load Language File
	 *
	 * @param boolean $forceLoad
	 * @return void
	 */
	public static function loadLanguage($forceLoad = false)
	{
		$lang = Factory::getLanguage();

		$app = Factory::getApplication();
		$template = $app->getTemplate();

		if ($app->isClient('administrator'))
		{
			$template = self::getTemplate();
		}

		$com_option = $app->input->get('option', '', 'STR');
		$com_view = $app->input->get('view', '', 'STR');
		$com_id = $app->input->get('id', 0, 'INT');

		if (($com_option == 'com_sppagebuilder' && $com_view == 'form' && $com_id) || $forceLoad)
		{
			$lang->load('com_sppagebuilder', JPATH_ADMINISTRATOR, null, true);
		}

		// Load template language file
		$lang->load('tpl_' . $template, JPATH_SITE, null, true);

		self::setPluginsAddonsLanguage();

		require_once JPATH_ROOT . '/administrator/components/com_sppagebuilder/helpers/language.php';
	}

	private static function getTemplate()
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('template')
			->from($db->quoteName('#__template_styles'))
			->where($db->quoteName('client_id') . ' = 0')
			->where($db->quoteName('home') . ' = 1');

		$db->setQuery($query);

		return $db->loadResult();
	}

	/**
	 * Load Plugin addons language files.
	 *
	 * @return void
	 */
	private static function setPluginsAddonsLanguage()
	{
		$path = JPATH_PLUGINS . '/sppagebuilder';
		if (!Folder::exists($path)) return;

		$plugins = Folder::folders($path);
		if (!count((array) $plugins)) return;

		foreach ($plugins as $plugin)
		{
			if (PluginHelper::isEnabled('sppagebuilder', $plugin))
			{
				$lang = Factory::getLanguage();
				$lang->load('plg_' . $plugin, JPATH_ADMINISTRATOR, null, true);
			}
		}
	}

	/**
	 * Convert Padding Margin Value.
	 *
	 * @param string $main_value CSS value
	 * @param string $type  CSS property
	 * 
	 * @return string
	 * 
	 * @since 4.0.0
	 */
	public static function getPaddingMargin($main_value, $type): string
	{
		$css = '';
		$pos = array('top', 'right', 'bottom', 'left');
		if (is_string($main_value) && trim($main_value) != "")
		{
			$values = explode(' ', $main_value);

			foreach ($values as $key => $value)
			{
				$value = preg_replace('@/s@', '', $value);

				if ($value !== "")
				{
					$css .= $type . '-' . $pos[$key] . ': ' . $value . ';';
				}
			}
		}

		return $css;
	}

	public static function getSvgShapes()
	{
		$shape_path = JPATH_ROOT . '/components/com_sppagebuilder/assets/shapes';
		$shapes = Folder::files($shape_path, '.svg');

		$shapeArray = array();

		if (count((array) $shapes))
		{
			foreach ($shapes as $shape)
			{
				$shapeArray[str_replace('.svg', '', $shape)] = base64_encode(file_get_contents($shape_path . '/' . $shape));
			}
		}

		return $shapeArray;
	}

	public static function getSvgShapeCode($shapeName, $invert)
	{
		if ($invert)
		{
			$shape_path = JPATH_ROOT . '/components/com_sppagebuilder/assets/shapes/' . $shapeName . '-invert.svg';
		}
		else
		{
			$shape_path = JPATH_ROOT . '/components/com_sppagebuilder/assets/shapes/' . $shapeName . '.svg';
		}

		$shapeCode = '';

		if (file_exists($shape_path))
		{
			$shapeCode = file_get_contents($shape_path);
		}

		return is_string($shapeCode) ? $shapeCode : '';
	}

	// Convert json code to plain text
	public static function getPrettyText($sections)
	{
		if (!class_exists('AddonParser'))
		{
			require_once JPATH_ROOT . '/components/com_sppagebuilder/parser/addon-parser.php';
		}
		if (!class_exists('SpPageBuilderAddonHelper'))
		{
			require_once JPATH_ROOT . '/components/com_sppagebuilder/builder/classes/addon.php';
		}

		$sections = SpPageBuilderAddonHelper::__($sections);
		$content = json_decode($sections);
		$htmlContent = AddonParser::viewAddons($content);
		$htmlContent = str_replace('><', '> <', $htmlContent);

		return trim(strip_tags($htmlContent));
	}

	public static function addScript($script, $client = 'site', $version = true)
	{
		$doc = Factory::getDocument();

		$script_url = Uri::base(true) . ($client == 'admin' ? '/administrator' : '') . '/components/com_sppagebuilder/assets/js/' . $script;

		if ($version)
		{
			$script_url .= '?' . self::getVersion(true);
		}

		$doc->addScript($script_url);
	}

	public static function addStylesheet($stylesheet, $client = 'site', $version = true)
	{
		$doc = Factory::getDocument();

		$stylesheet_url = Uri::base(true) . ($client == 'admin' ? '/administrator' : '') . '/components/com_sppagebuilder/assets/css/' . $stylesheet;

		if ($version)
		{
			$stylesheet_url .= '?' . self::getVersion(true);
		}

		$doc->addStylesheet($stylesheet_url);
	}

	public static function addContainerMaxWidth()
	{
		$doc = Factory::getDocument();
		$params = ComponentHelper::getParams('com_sppagebuilder');
		$containerMaxWidth = $params->get('container_max_width', 1320);
		$doc->addStyleDeclaration("@media(min-width: 1400px) {.sppb-row-container { max-width: " . $containerMaxWidth . "px; }}");
	}

	public static function getVersion($md5 = false)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true)
			->select('e.manifest_cache')
			->select($db->quoteName('e.manifest_cache'))
			->from($db->quoteName('#__extensions', 'e'))
			->where($db->quoteName('e.element') . ' = ' . $db->quote('com_sppagebuilder'));

		$db->setQuery($query);
		$manifest_cache = json_decode($db->loadResult());

		if (isset($manifest_cache->version) && $manifest_cache->version)
		{

			if ($md5)
			{
				return md5($manifest_cache->version);
			}

			return $manifest_cache->version;
		}

		return '1.0';
	}

	/**
	 * Load Assets form database table.
	 *
	 * @return void
	 */
	public static function loadAssets()
	{
		$doc = Factory::getDocument();
		$db = Factory::getDbo();
		$query = $db->getQuery(true)
			->select($db->quoteName(array('a.name', 'a.css_path')))
			->from($db->quoteName('#__sppagebuilder_assets', 'a'))
			->where($db->quoteName('a.published') . ' = 1');

		$db->setQuery($query);
		$assets = $db->loadObjectList();

		if (!empty($assets))
		{
			foreach ($assets as $asset)
			{
				$asset_url = Uri::base(true) . '/' . $asset->css_path . '?' . self::getVersion(true);
				$doc->addStylesheet($asset_url);
			}
		}
	}

	/**
	 * Get the current template name form database.
	 *
	 * @return void
	 */
	public static function getTemplateName()
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(['template']))
			->from($db->quoteName('#__template_styles'))
			->where($db->quoteName('client_id') . ' = 0')
			->where($db->quoteName('home') . ' = 1');
		$db->setQuery($query);

		return $db->loadObject()->template;
	}

	/**
	 * Get installed google fonts from database.
	 * 
	 * @return array
	 */
	public static function getInstalledGoogleFonts()
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('family_name')
			->from($db->quoteName('#__sppagebuilder_fonts'))
			->where($db->quoteName('type') . ' = ' . $db->quote('google'));

		$db->setQuery($query);

		try
		{
			return $db->loadColumn() ?? [];
		}
		catch (Exception $e)
		{
			return [];
		}
	}

	/**
	 * Checking multi device settings
	 *
	 * @param mixed $value
	 * @return boolean
	 * 
	 * @since 5.0.0
	 */
	public static function hasMultiDeviceSettings($value): bool
	{
		return isset($value->xl)
			|| isset($value->lg)
			|| isset($value->md)
			|| isset($value->sm)
			|| isset($value->xs);
	}

	/**
	 * Checking media item
	 *
	 * @param mixed $value
	 * @return boolean
	 * 
	 * @since 5.0.0
	 */
	public static function isMediaItemData($value): bool
	{
		return isset($value->src);
	}

	/**
	 * Clean media path
	 *
	 * @param string $path
	 * @return string
	 * 
	 * @since 5.0.3
	 */
	public static function cleanPath($path): string
	{
		$cleanedPath = Path::clean($path);
		$cleanedPath = str_replace("/\\","\\", $cleanedPath);
		$cleanedPath = str_replace("\\","/", $cleanedPath);
		return $cleanedPath;
	}
}
