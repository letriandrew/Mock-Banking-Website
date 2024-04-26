<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

//no direct access
defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT . '/builder/classes/ajax.php';
if (!class_exists('SppagebuilderHelperSite'))
{
	require_once JPATH_ROOT . '/components/com_sppagebuilder/helpers/helper.php';
}

$user = Factory::getUser();
$app  = Factory::getApplication();

$authorised = $user->authorise('core.edit', 'com_sppagebuilder') || ($user->authorise('core.edit.own', 'com_sppagebuilder') && ($this->item->created_by == $user->id));
if ($authorised !== true)
{
	$app->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'error');
	$app->setHeader('status', 403, true);

	return false;
}

SppagebuilderHelperSite::loadLanguage();

$input = Factory::getApplication()->input;
$action = $input->get('callback', '', 'STRING');

function sanitizeAddonData($data)
{
	if (\is_object($data))
	{
		foreach ($data as &$value)
		{
			if (\is_object($value))
			{
				$value = sanitizeAddonData($value);
			}
			else if (is_string($value))
			{
				switch (\strtolower($value))
				{
					case 'true':
						$value = '1';
						break;
					case 'false':
						$value = '0';
						break;
				}
			}
		}
		unset($value);
	}

	return $data;
}

// all settings loading
if ($action === 'addon')
{
	require_once JPATH_COMPONENT . '/parser/addon-parser.php';

	if (!class_exists('SpPageBuilderAddonHelper'))
	{
		require_once JPATH_ROOT . '/components/com_sppagebuilder/builder/classes/addon.php';
	}

	$post_data = $_POST['addon'];
	$addon = json_decode(json_encode($post_data));
	$addon = sanitizeAddonData($addon);

	$addon_name = $addon->name;
	$class_name = 'SppagebuilderAddon' . ucfirst($addon_name);
	$addon_path = AddonParser::getAddonPath($addon_name);

	$addon_options = [];

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

	$store = new \stdClass;



	foreach ($addon->settings as $key => &$setting)
	{
		if (\is_object($setting))
		{
			$original = \json_decode(\json_encode($setting));
		}

		if (isset($setting->md))
		{
			$md = isset($setting->md) ? $setting->md : "";
			$sm = isset($setting->sm) ? $setting->sm : "";
			$xs = isset($setting->xs) ? $setting->xs : "";

			$xl = isset($setting->xl) ? $setting->xl : $md;
			$lg = isset($setting->lg) ? $setting->lg : $md;

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
			$originalKey = $key . '_original';
			$store->$originalKey = $original;
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
				foreach ($setting as &$options)
				{
					foreach ($options as $key2 => &$opt)
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

	unset($setting);

	foreach ($store as $key => $value)
	{
		$addon->settings->$key = $value;
	}

	$output = '';

	require_once $addon_path . '/site.php';

	$assets = array();
	$css = LayoutHelper::render('addon.css', array('addon' => $addon));

	if (class_exists($class_name))
	{
		$addon_obj = new $class_name($addon);  // initialize addon class
		$addon_output = $addon_obj->render();


		// css
		if (method_exists($class_name, 'css'))
		{
			$css .= $addon_obj->css();
		}

		// js
		if (method_exists($class_name, 'js'))
		{
			$assets['js'] = $addon_obj->js();
		}
	}
	else
	{
		$addon_output = AddonParser::spDoAddon(AddonParser::generateShortcode($addon, 0, 0));
	}

	if ($css)
	{
		$assets['css'] = $css;
	}

	if (empty($addon_output))
	{
		$addon_output = '<div class="sppb-empty-addon"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="140.1px" height="24.2px" viewBox="0 0 140.1 24.2">
		<path class="st0" d="M19,13.5c-0.4-0.4-0.8-0.4-1.1,0.1c-0.9,1.1-1.9,2.1-2.9,3c-3.5,3-7.6,4.7-12.1,5.5
		c-0.6,0.1-0.8-0.1-0.8-0.7c0-0.9,0-1.9,0-2.8l0,0l0,0l0,0V5.5V4.9c0-0.2,0-0.4,0.3-0.5c0.4-0.3,0.7,0.2,1.1,0.5
		c3.4,2.4,6.8,4.9,10.2,7.3c0.5,0.3,0.5,0.5,0.1,0.9c-2.6,2.4-5.5,4.1-8.9,5.1c-1.2,0.3-1.2,0.3-1.2,1.6c0,0.5,0.1,0.6,0.6,0.5
		c1-0.2,2-0.5,2.9-0.8c3.7-1.4,6.8-3.5,9.4-6.5c0.6-0.7,0.6-0.6-0.1-1.2C11.1,7.9,5.9,4.2,0.7,0.5C0.6,0.4,0.4,0.3,0.3,0.4
		c-0.2,0-0.1,0.2-0.1,0.4c0,0.9,0,1.8,0,2.7c0,0.3,0,0.4,0,0.6v3.2l0,0v2.6v1.2V13v1.4v1.5l0,0l-0.1,4.3l0,0c0,0.3,0,0.5,0,0.7
		c0,0.8,0,1.7,0,2.5c0,0.4,0.1,0.6,0.6,0.6c2.1-0.1,4.1-0.4,6.1-1c5-1.5,9.1-4.2,12.5-8.1C19.9,14.2,19.9,14.2,19,13.5z"></path>
		<path class="st1" d="M9.1,12.3c0.1-0.1,0.1-0.2,0-0.3c-1.2-0.9-2.4-1.7-3.5-2.5C5.4,9.4,5.3,9.2,5.2,9.3
		c-0.1,0-0.1,0.1-0.1,0.2v0.2v4.5C6.8,14.1,8.2,13.1,9.1,12.3z"></path>
		</svg></div>';
	}

	if (!empty($addon_output))
	{
		$output .= LayoutHelper::render('addon.start', array('addon' => $addon)); // start addon
		$output .= $addon_output;
		$output .= LayoutHelper::render('addon.end'); // end addon
	}

	echo json_encode(array('html' => htmlspecialchars_decode($output), 'status' => 'true', 'assets' => $assets));
	die;
}

if ($action === 'get-page-data')
{
	$page_path = $_POST['pagepath'];
	if (File::exists($page_path))
	{
		$content = file_get_contents($page_path);

		if (is_array(json_decode($content)))
		{
			require_once JPATH_COMPONENT . '/builder/classes/addon.php';
			$content = SpPageBuilderAddonHelper::__($content, true);
			$content = SpPageBuilderAddonHelper::getFontendEditingPage($content);

			echo json_encode(array('status' => true, 'data' => $content));
			die;
		}
	}

	echo json_encode(array('status' => false, 'data' => 'Something worng there.'));
	die;
}

// all settings loading
if ($action === 'setting_value')
{
	require_once JPATH_COMPONENT . '/builder/classes/base.php';
	require_once JPATH_COMPONENT . '/builder/classes/config.php';

	$addon_name = $_POST['name'];
	$addon_id = $_POST['id'];
	SpPgaeBuilderBase::loadSingleAddon($addon_name);
	$addonList = SpAddonsConfig::$addons;
	$addonItem = $addonList[$addon_name];
	$addonItem = AddonsHelper::modernizeAddonStructure($addonItem);

	require_once JPATH_COMPONENT . '/parser/addon-parser.php';

	$settings = !empty($addonItem) ? EditorUtils::extractSettingsDefaultValues($addonItem['settings']) : [];
	$globalDefaults = [];
	$globalSettingsGroups = ['style', 'advanced', 'interaction'];
	$globalSettings = SpPgaeBuilderBase::addonOptions();

	foreach ($globalSettingsGroups as $groupName)
	{
		$globalDefaults = array_merge($globalDefaults, EditorUtils::extractSettingsDefaultValues($globalSettings[$groupName]));
	}

	$settings = array_merge($settings, $globalDefaults);

	$addon = json_decode(json_encode(array('id' => $addon_id, 'name' => $addon_name, 'settings' => $settings)));


	$class_name = 'SppagebuilderAddon' . ucfirst($addon_name);
	$addon_path = AddonParser::getAddonPath($addon_name);

	$output = '';

	require_once $addon_path . '/site.php';

	$assets = array();
	$css = LayoutHelper::render('addon.css', array('addon' => $addon));

	if (class_exists($class_name))
	{
		$addon_obj = new $class_name($addon);  // initialize addon class
		$addon_output = $addon_obj->render();

		// css
		if (method_exists($class_name, 'css'))
		{
			$css .= $addon_obj->css();
		}

		// js
		if (method_exists($class_name, 'js'))
		{
			$assets['js'] = $addon_obj->js();
		}
	}
	else
	{
		$addon_output = AddonParser::spDoAddon(AddonParser::generateShortcode($addon, 0, 0));
	}

	if ($css)
	{
		$assets['css'] = $css;
	}

	if (empty($addon_output))
	{
		$addon_output = '<div class="sppb-empty-addon"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="140.1px" height="24.2px" viewBox="0 0 140.1 24.2">
		<path class="st0" d="M19,13.5c-0.4-0.4-0.8-0.4-1.1,0.1c-0.9,1.1-1.9,2.1-2.9,3c-3.5,3-7.6,4.7-12.1,5.5
		c-0.6,0.1-0.8-0.1-0.8-0.7c0-0.9,0-1.9,0-2.8l0,0l0,0l0,0V5.5V4.9c0-0.2,0-0.4,0.3-0.5c0.4-0.3,0.7,0.2,1.1,0.5
		c3.4,2.4,6.8,4.9,10.2,7.3c0.5,0.3,0.5,0.5,0.1,0.9c-2.6,2.4-5.5,4.1-8.9,5.1c-1.2,0.3-1.2,0.3-1.2,1.6c0,0.5,0.1,0.6,0.6,0.5
		c1-0.2,2-0.5,2.9-0.8c3.7-1.4,6.8-3.5,9.4-6.5c0.6-0.7,0.6-0.6-0.1-1.2C11.1,7.9,5.9,4.2,0.7,0.5C0.6,0.4,0.4,0.3,0.3,0.4
		c-0.2,0-0.1,0.2-0.1,0.4c0,0.9,0,1.8,0,2.7c0,0.3,0,0.4,0,0.6v3.2l0,0v2.6v1.2V13v1.4v1.5l0,0l-0.1,4.3l0,0c0,0.3,0,0.5,0,0.7
		c0,0.8,0,1.7,0,2.5c0,0.4,0.1,0.6,0.6,0.6c2.1-0.1,4.1-0.4,6.1-1c5-1.5,9.1-4.2,12.5-8.1C19.9,14.2,19.9,14.2,19,13.5z"></path>
		<path class="st1" d="M9.1,12.3c0.1-0.1,0.1-0.2,0-0.3c-1.2-0.9-2.4-1.7-3.5-2.5C5.4,9.4,5.3,9.2,5.2,9.3
		c-0.1,0-0.1,0.1-0.1,0.2v0.2v4.5C6.8,14.1,8.2,13.1,9.1,12.3z"></path>
		</svg></div>';
	}

	$output .= LayoutHelper::render('addon.start', array('addon' => $addon)); // start addon
	$output .= $addon_output;
	$output .= LayoutHelper::render('addon.end'); // end addon

	echo json_encode(array('formData' => json_encode($settings), 'html' => htmlspecialchars_decode($output), 'status' => 'true', 'assets' => $assets));
	die;
}

require_once JPATH_COMPONENT . '/helpers/ajax.php';
