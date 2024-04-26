<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Language\Text;

//no direct access
defined('_JEXEC') or die('Restricted access');
require_once __DIR__ . '/base.php';
require_once __DIR__ . '/config.php';

$app = Factory::getApplication();

$isSite = $app->isClient('site');
if ($isSite)
{
	if (!class_exists('SppagebuilderHelper'))
	{
		require_once JPATH_ROOT . '/components/com_sppagebuilder/helpers/helper.php';
	}
	require_once JPATH_ROOT . '/components/com_sppagebuilder/parser/addon-parser.php';
}

class SpPageBuilderAddonHelper
{
	/**
	 * convert JSON
	 *
	 * @param  string  $json
	 * @param  boolean $frontend
	 * @param  array   $addonList
	 * @return string
	 */
	public static function __($json = '[]', $frontend = false, $addonList = array())
	{
		$datas  = json_decode($json);

		if(is_string($datas)) {
			$datas = json_decode($datas);
		}
		if (!count((array) $datas)) return $json;

		$uniqueId 	= strtotime('now');
		$first_row 	= $datas[0];

		if (!isset($first_row->id))
		{
			foreach ($datas as &$row)
			{
				self::rowFallback($row, $uniqueId);
				foreach ($row->columns as &$column)
				{
					self::columnFallback($column, $uniqueId);
					foreach ($column->addons as &$addon)
					{
						// Inner Row data regenerate
						if (isset($addon->type) && $addon->type == 'sp_row')
						{
							self::rowFallback($addon, $uniqueId, true);
							foreach ($addon->columns as &$column)
							{
								self::columnFallback($column, $uniqueId);
								foreach ($column->addons as &$addon)
								{
									self::addonFallback($addon, $uniqueId);
								}
							}
						}
						else
						{
							self::addonFallback($addon, $uniqueId);
						}
					}
				}
			}
		}

		// Frontend editing
		if ($frontend)
		{

			$addons = array();
			if (!empty($addonList))
			{
				foreach ($addonList as $addonListItem)
				{
					if (isset($addonListItem['inline']))
					{
						unset($addonListItem['inline']);
					}

					if (isset($addonListItem['attr']))
					{
						unset($addonListItem['attr']);
					}

					$addon_name = preg_replace('/' . preg_quote('sp_', '/') . '/', '', $addonListItem['addon_name'], 1);
					$addonListItem['addon_name'] = $addon_name;
					$addons[$addon_name] = $addonListItem;

					// todo: implement status later
					// $dbAddon = $model->getAddon($addon_name);

					// if(!empty($dbAddon))
					// {
					// 	$addonListItem['status'] = $dbAddon->status;
					// }
					// else
					// {
					// 	$addonListItem['status'] = 1;
					// }
				}
			}

			return self::getFontendEditingPage(json_encode($datas), $addons);
		}

		return json_encode($datas);
	}

	// Row data regenerate for version < 2.0
	public static function rowFallback(&$row, &$id, $inner = false)
	{
		$row->id = $id;
		$row->visibility = (isset($row->disable) && $row->disable) ? '' : 1;

		if ($row->layout != '12')
		{
			$chars = str_split($row->layout);
			$row->layout = join(',', $chars);
		}
		$row->columns =  $row->attr;

		if (!$inner)
		{
			$row->collapse = '';
			$row->title = 'Row';
			unset($row->type);
			unset($row->disable);
		}
		else
		{
			$row->type = 'inner_row';
		}
		$id = $id + 1;
		unset($row->attr);
	}

	// Column data regenerate for version < 2.0
	public static function columnFallback(&$column, &$id)
	{
		$column->id = $id;
		$column->addons = $column->attr;
		$column->visibility = 1;
		$column->class_name = str_replace('column-parent ', '', $column->class_name);
		$id = $id + 1;

		unset($column->settings->sortableitem);
		unset($column->attr);
		unset($column->type);
	}

	// Addon data regenerate for version < 2.0
	public static function addonFallback(&$addon, &$id)
	{
		$addon->id = $id;
		$addon->settings = $addon->atts;
		$addon->visibility = 1;

		if (count((array) $addon->scontent))
		{

			$settings = array();
			foreach ($addon->scontent as $ops)
			{
				$settings[] = $ops->atts;
			}

			if (isset($form_fields[$addon->name]['attr']['repetable_item']['addon_name']))
			{
				$addon->settings->{$form_fields[$addon->name]['attr']['repetable_item']['addon_name']} = $settings;
			}
			else if (isset($addon->scontent[0]->name) && $addon->scontent[0]->name)
			{
				$addon->settings->{$addon->scontent[0]->name} = $settings;
			}
		}

		$id = $id + 1;

		unset($addon->atts);
		unset($addon->scontent);
	}

	/**
	 * Get Addon for editing page.
	 *
	 * @param  string $page
	 * @param  array  $addonList
	 * @return void
	 */
	public static function getFontendEditingPage($page = '', $addonList = array())
	{
		$datum  = json_decode($page);
		if (empty($datum)) return $page;

		foreach ($datum as &$row)
		{
			foreach ($row->columns as &$column)
			{
				foreach ($column->addons as &$addon)
				{
					if (!isset($addon->name))
					{
						continue;
					}

					if (isset($addon->type) && ($addon->type === 'sp_row' || $addon->type === 'inner_row'))
					{
						foreach ($addon->columns as &$column)
						{
							foreach ($column->addons as &$addon)
							{
								$addon_data = self::getAddonContent($addon);
								if (!isset($addon_data['jsTemplate']))
								{
									$addon->htmlContent = $addon_data['html'];
									$addon->assets = $addon_data['assets'];
								}

								$addonName = preg_replace('/' . preg_quote('sp_', '/') . '/', '', $addon->name, 1); // todo: will be replace with the function
								$addonInfo = isset($addonList[$addonName]) ? $addonList[$addonName] : array();

								if (isset($addonInfo['title']))
								{
									$addon->title = $addonInfo['title'];
								}
								if (!is_null(SpAddonsConfig::getIcon($addonInfo)))
								{
									$addon->icon = SpAddonsConfig::getIcon($addonInfo);
								}
							}
						}
					}
					else
					{
						$addon_data = self::getAddonContent($addon);
						if (!isset($addon_data['jsTemplate']))
						{
							$addon->htmlContent = $addon_data['html'];
							$addon->assets = $addon_data['assets'];
						}

						$addonName = preg_replace('/' . preg_quote('sp_', '/') . '/', '', $addon->name, 1); // todo: will be replace with the function
						$addonInfo = isset($addonList[$addonName]) ? $addonList[$addonName] : array();

						if (isset($addonInfo['title']))
						{
							$addon->title = $addonInfo['title'];
						}
						if (!is_null(SpAddonsConfig::getIcon($addonInfo)))
						{
							$addon->icon = SpAddonsConfig::getIcon($addonInfo);
						}
					}
				}

				unset($addon);
			}

			unset($column);
		}

		unset($row);

		return json_encode($datum);
	}

	public static function getAddonContent($addon)
	{
		$addon_name = $addon->name;
		$class_name = 'SppagebuilderAddon' . ucfirst($addon_name);
		$addon_path = AddonParser::getAddonPath($addon_name);

		$addonFullPath = $addon_path . '/site.php';

		// if addon file path not found
		if (!file_exists($addonFullPath))
		{
			return array(
				'html' => '<div class="builder-custom-addon-not-found"><h4>' . Text::_("COM_SPPAGEBUILDER_ADDON_NOT_FOUND") . '</h4></div>',
				'assets' => ''
			);
		}

		require_once $addonFullPath;

		if (class_exists($class_name))
		{
			if (method_exists($class_name, 'getTemplate'))
			{
				return array('jsTemplate' => true);
			}
		}

		$addon_options = array();

		if ((!isset($addon->type) || $addon->type !== 'inner_row') && isset($addon_list[$addon->name]['attr']) && $addon_list[$addon->name]['attr'])
		{
			$addon_groups = $addon_list[$addon->name]['attr'];
			if (is_array($addon_groups))
			{
				foreach ($addon_groups as $addon_group)
				{
					$addon_options += $addon_group;
				}
			}
		}

		foreach ($addon->settings as $key => $setting)
		{
			if (isset($setting->md))
			{
				$md = isset($setting->md) ? $setting->md : "";
				$sm = isset($setting->sm) ? $setting->sm : "";
				$xs = isset($setting->xs) ? $setting->xs : "";

				$xl = isset($setting->xl) ? $setting->xl : $md;
				$lg = isset($setting->lg) ? $setting->lg : $sm;
				$md = isset($setting->md) ? $setting->md : $sm;

				$keySm = $key . '_sm';
				$keyXs = $key . '_xs';
				$keyLg = $key . '_lg';
				$keyXl = $key . '_xl';
				$keyMd = $key . '_md';
				$addon->settings->$keySm = $sm;
				$addon->settings->$keyXs = $xs;
				$addon->settings->$keyXl = $xl;
				$addon->settings->$keyLg = $lg;
				$addon->settings->$keyMd = $md;
			}

			if (isset($addon_options[$key]['selector']))
			{
				$addon_selector = $addon_options[$key]['selector'];
				if (isset($addon->settings->{$key}) && !empty($addon->settings->{$key}))
				{
					$selector_value = $addon->settings->{$key};
					$addon->settings->{$key . '_selector'} = str_replace('{{ VALUE }}', $selector_value, $addon_selector);
				}
			}

			// Repeatable
			if ((!isset($addon->type) || $addon->type !== 'inner_row') &&  (($key == 'sp_' . $addon->name . '_item') || ($key == $addon->name . '_item')))
			{
				if (count((array) $setting))
				{
					foreach ($setting as $options)
					{
						foreach ($options as $key2 => $opt)
						{

							if (isset($opt->md))
							{
								$md = isset($opt->md) ? $opt->md : "";
								$sm = isset($opt->sm) ? $opt->sm : "";
								$xs = isset($opt->xs) ? $opt->xs : "";
								$opt = $md;
								$options->{$key2 . '_sm'} = $sm;
								$options->{$key2 . '_xs'} = $xs;
							}

							if (isset($addon_options[$key]['attr'][$key2]['selector']))
							{
								$addon_selector = $addon_options[$key]['attr'][$key2]['selector'];
								if (isset($options->{$key2}) && !empty($options->{$key2}))
								{
									$selector_value = $options->{$key2};
									$options->{$key2 . '_selector'} = str_replace('{{ VALUE }}', $selector_value, $addon_selector);
								}
							}
						}
					}
				}
			}
		}

		$output = '';
		$output .= LayoutHelper::render('addon.start', array('addon' => $addon)); // start addon

		$assets = array();
		$css = LayoutHelper::render('addon.css', array('addon' => $addon));

		if (class_exists($class_name))
		{
			$addon_obj  = new $class_name($addon);  // initialize addon class
			$output .= $addon_obj->render();

			if (method_exists($class_name, 'css'))
			{
				$css .= $addon_obj->css();
			}

			if (method_exists($class_name, 'js'))
			{
				$assets['js'] = $addon_obj->js();
			}
		}
		else
		{
			$output .= AddonParser::spDoAddon(AddonParser::generateShortcode($addon, 0, 0));
		}

		$output .= LayoutHelper::render('addon.end'); // end addon

		if ($css)
		{
			$assets['css'] = $css;
		}

		return array('html' => $output, 'assets' => $assets);
	}
}
