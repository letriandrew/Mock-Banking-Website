<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\String\StringHelper;

//no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * SP Page Builder Base class
 * 
 * @since 1.0.0
 */
class SpPgaeBuilderBase
{
	/**
	 * Default Device size
	 *
	 * @var string
	 * @since 4.0.0
	 */
	public static $defaultDevice = 'xl';

	/**
	 * Remove sp_ form addon name
	 *
	 * @param string $from String to remove
	 * @param string $to	replace with string
	 * @param string $subject main string
	 * 
	 * @return void
	 * @since 1.0.0
	 */
	private static function str_replace_first($from, $to, $subject)
	{
		$from = '/' . preg_quote($from, '/') . '/';

		return preg_replace($from, $to, $subject, 1);
	}

	/**
	 * Load Custom Input Type
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function loadInputTypes()
	{
		$types = Folder::files(JPATH_ROOT . '/administrator/components/com_sppagebuilder/builder/types', '\.php$', false, true);

		foreach ($types as $type)
		{
			include_once $type;
		}
	}

	/**
	 * Get Current Template
	 *
	 * @return void
	 * @since 4.0.0
	 */
	private static function getTemplateName()
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('template')));
		$query->from($db->quoteName('#__template_styles'));
		$query->where($db->quoteName('client_id') . ' = 0');
		$query->where($db->quoteName('home') . ' = 1');
		$db->setQuery($query);

		return $db->loadObject()->template;
	}

	/**
	 * Load addons list from addons folders, Components, Template, Plugin
	 *
	 * @return void
	 * @since 4.0.0
	 */
	public static function loadAddons()
	{

		require_once JPATH_ROOT . '/components/com_sppagebuilder/addons/module/admin.php';

		$template_path = JPATH_ROOT . '/templates/' . self::getTemplateName(); // current template path
		$tmpl_folders = array();

		/**
		 * Load override or new addons list form template sp page builder addons folder.
		 */
		if (file_exists($template_path . '/sppagebuilder/addons'))
		{
			$tmpl_folders = Folder::folders($template_path . '/sppagebuilder/addons');
		}

		/**
		 * Load addons list form component addons folder.
		 */
		$folders = Folder::folders(JPATH_ROOT . '/components/com_sppagebuilder/addons');

		if ($tmpl_folders)
		{
			$merge_folders = array_merge($folders, $tmpl_folders);
			$folders = array_unique($merge_folders);
		}

		if (count((array) $folders))
		{
			foreach ($folders as $folder)
			{
				$tmpl_file_path = $template_path . '/sppagebuilder/addons/' . $folder . '/admin.php';
				$com_file_path = JPATH_ROOT . '/components/com_sppagebuilder/addons/' . $folder . '/admin.php';

				if ($folder != 'module')
				{
					if (file_exists($tmpl_file_path))
					{
						require_once $tmpl_file_path;
					}
					else if (file_exists($com_file_path))
					{
						require_once $com_file_path;
					}
				}
			}
		}

		self::loadPluginsAddons();
	}

	/**
	 * Load Single Addon
	 *
	 * @param string $name Addon Name
	 * 
	 * @return void
	 * @since 1.0.0
	 */
	public static function loadSingleAddon($name = '')
	{
		if (!$name) return;

		$name = self::str_replace_first('sp_', '', $name);
		$template_path = JPATH_ROOT . '/templates/' . self::getTemplateName(); // current template path
		$tmpl_addon_path = $template_path . '/sppagebuilder/addons/' . $name . '/admin.php';
		$com_addon_path = JPATH_ROOT . '/components/com_sppagebuilder/addons/' . $name . '/admin.php';

		$plugins = self::getPluginsAddons();

		if (file_exists($tmpl_addon_path))
		{
			require_once $tmpl_addon_path;
		}
		else if (file_exists($com_addon_path))
		{
			require_once $com_addon_path;
		}
		else
		{
			// Load from plugin
			if (isset($plugins[$name]) && $plugins[$name])
			{
				require_once $plugins[$name];
			}
		}
	}

	/**
	 * Load addons from plugins
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private static function loadPluginsAddons()
	{
		$path = JPATH_PLUGINS . '/sppagebuilder';
		if (!Folder::exists($path)) return;

		$plugins = Folder::folders($path);
		if (!count((array) $plugins)) return;

		foreach ($plugins as $plugin)
		{
			if (PluginHelper::isEnabled('sppagebuilder', $plugin))
			{
				$addons_path = $path . '/' . $plugin . '/addons';
				if (Folder::exists($addons_path))
				{
					$addons = Folder::folders($addons_path);
					foreach ($addons as $addon)
					{
						$admin_file = $addons_path . '/' . $addon . '/admin.php';
						if (File::exists($admin_file))
						{
							require_once $admin_file;
						}
					}
				}
			}
		}
	}

	/**
	 * Get list of plugin addons
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private static function getPluginsAddons()
	{
		$path = JPATH_PLUGINS . '/sppagebuilder';
		if (!Folder::exists($path)) return;

		$plugins = Folder::folders($path);
		if (!count((array) $plugins)) return;

		$elements = array();
		foreach ($plugins as $plugin)
		{
			if (PluginHelper::isEnabled('sppagebuilder', $plugin))
			{
				$addons_path = $path . '/' . $plugin . '/addons';
				if (Folder::exists($addons_path))
				{
					$addons = Folder::folders($addons_path);
					foreach ($addons as $addon)
					{
						$admin_file = $addons_path . '/' . $addon . '/admin.php';
						if (File::exists($admin_file))
						{
							$elements[$addon] = $admin_file;
						}
					}
				}
			}
		}

		return $elements;
	}

	/**
	 * Get Addon Global Options
	 *
	 * @return array
	 * @since 1.0.0
	 */
	public static function addonOptions()
	{
		require JPATH_ROOT . '/components/com_sppagebuilder/builder/settings/addon.php';

		return $addon_global_settings;
	}

	/**
	 * Get Addon Categories list
	 *
	 * @param array $addons	addons list
	 * @return void
	 * @since 4.0.0
	 */
	public static function getAddonCategories($addons)
	{
		$categories = array();
		foreach ($addons as $addon)
		{
			if (isset($addon['category']))
			{
				$categories[] = $addon['category'];
			}
		}

		$new_array = array_count_values($categories);

		$result[0]['name'] = 'All';
		$result[0]['count'] = count((array) $addons);
		if (count((array) $new_array))
		{
			$i = 1;
			foreach ($new_array as $key => $row)
			{
				$result[$i]['name'] = $key;
				$result[$i]['count'] = $row;
				$i = $i + 1;
			}
		}

		return $result;
	}
	/**
	 * Get article tags list
	 *
	 * @return void
	 * @since 4.0.0
	 */
	public static function getArticleTags()
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true)
			->select('DISTINCT a.id, a.title, a.level, a.published, a.lft, a.parent_id');
		$subQuery = $db->getQuery(true)
			->select('id,title,level,published,parent_id,lft,rgt')
			->from('#__tags')
			->where(
				$db->quoteName('published') . ' = ' . $db->quote(1)
			);

		$query->from('(' . $subQuery->__toString() . ') AS a')
			->join('LEFT', $db->quoteName('#__tags') . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt');
		$query->where($db->quoteName('a.level') . ' != ' . $db->quote(0));
		$query->order('a.lft ASC');
		$db->setQuery($query);
		$tags = $db->loadObjectList();

		$article_tags = array();
		if (count((array) $tags))
		{
			foreach ($tags as $tag)
			{
				$parent_tag = '';
				if ($tag->level > 1)
				{
					$parent_tag = self::getParentTag($tag->parent_id)->title . '/';
				}
				$article_tags[$tag->id] = $parent_tag . $tag->title;
			}
		}

		return $article_tags;
	}
	/**
	 *  get parent tag info by tag id
	 *
	 * @param string $parentid Tag parent id
	 * @return void
	 * @since 4.0.0
	 */
	private static function getParentTag($parentid = '')
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('a.id', 'a.title'));
		$query->from($db->quoteName('#__tags', 'a'));
		$query->where($db->quoteName('id') . " = " . $db->quote($parentid));
		$query->where($db->quoteName('published') . " = 1");
		$db->setQuery($query);
		$result = $db->loadObject();

		return $result;
	}

	/**
	 * Load CSS and JS files for all addons
	 *
	 * @param array $addons Addon lists
	 * 
	 * @return void
	 * @since 4.0.0
	 */
	public static function loadAssets($addons)
	{
		foreach ($addons as $key => $addon)
		{
			$class_name = 'SppagebuilderAddon' . ucfirst($key);
			$addon_path = AddonParser::getAddonPath($key);

			if (class_exists($class_name))
			{
				$obj = new $class_name($addon);

				// Scripts
				if (method_exists($class_name, 'scripts'))
				{
					$scripts = $obj->scripts();
					if (count((array) $scripts))
					{
						$doc = Factory::getDocument();
						foreach ($scripts as $key => $script)
						{
							$doc->addScript($script);
						}
					}
				}

				// Stylesheets
				if (method_exists($class_name, 'stylesheets'))
				{
					$stylesheets = $obj->stylesheets();
					if (count((array) $stylesheets))
					{
						$doc = Factory::getDocument();
						foreach ($stylesheets as $key => $stylesheet)
						{
							$doc->addStyleSheet($stylesheet);
						}
					}
				}
			}
		}
	}

	/**
	 * Get Addon Path
	 *
	 * @param string $addon_name
	 * @return void
	 * @since 1.0.0
	 */
	public static function getAddonPath($addon_name = '')
	{
		$app = Factory::getApplication();
		$template = $app->getTemplate();
		$template_path = JPATH_ROOT . '/templates/' . $template;
		$plugins = self::getPluginsAddons();

		if (file_exists($template_path . '/sppagebuilder/addons/' . $addon_name . '/site.php'))
		{
			return $template_path . '/sppagebuilder/addons/' . $addon_name;
		}
		elseif (file_exists(JPATH_ROOT . '/components/com_sppagebuilder/addons/' . $addon_name . '/site.php'))
		{
			return JPATH_ROOT . '/components/com_sppagebuilder/addons/' . $addon_name;
		}
		else
		{
			// Load from plugin
			if (isset($plugins[$addon_name]) && $plugins[$addon_name])
			{
				return $plugins[$addon_name];
			}
		}
	}

	/**
	 * Get addon icon list
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function getIconList()
	{
		include JPATH_ROOT . '/components/com_sppagebuilder/builder/settings/icon-font-awesome.php';

		return $icon_list;
	}

	/**
	 * Get all access levels form database.
	 *
	 * @return void
	 * @since 4.0.0
	 */
	public static function getAccessLevelList()
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true)
			->select($db->quoteName('a.id', 'value') . ', ' . $db->quoteName('a.title', 'label'))
			->from($db->quoteName('#__viewlevels', 'a'))
			->group($db->quoteName(array('a.id', 'a.title', 'a.ordering')))
			->order($db->quoteName('a.ordering') . ' ASC')
			->order($db->quoteName('title') . ' ASC');

		// Get the options.
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	/**
	 * Get Page Category list form database.
	 *
	 * @return object
	 * @since 4.0.0
	 */
	public static function getPageCategories()
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true)
			->select('id, title, parent_id, level, published, lft, language')
			->from($db->quoteName('#__categories'))
			->where($db->quoteName('extension') . '=' . $db->quote('com_sppagebuilder'))
			->group($db->quoteName(array('id', 'title')))
			->where($db->quoteName('published') . '=' . $db->quote('1'))
			->order($db->quoteName('title') . ' ASC');

		// Get the options.
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	/**
	 * Load Language list form database.
	 *
	 * @return void
	 * @since 4.0.0
	 */
	public static function getLanguageList()
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true)
			->select($db->quoteName('a.lang_code', 'value') . ', ' . $db->quoteName('a.title', 'label'))
			->from($db->quoteName('#__languages', 'a'))
			->group($db->quoteName(array('a.lang_code', 'a.title', 'a.ordering')))
			->order($db->quoteName('a.ordering') . ' ASC')
			->order($db->quoteName('title') . ' ASC');

		// Get the options.
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	/**
	 * Get Article Categories
	 *
	 * @return void
	 * @since 4.0.0
	 */
	public static function getArticleCategories()
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true)
			->select('DISTINCT a.id, a.title, a.level, a.published, a.lft');
		$subQuery = $db->getQuery(true)
			->select('id,title,level,published,parent_id,extension,lft,rgt')
			->from('#__categories')
			->where($db->quoteName('published') . ' = ' . $db->quote(1))
			->where($db->quoteName('extension') . ' = ' . $db->quote('com_content'));

		$query->from('(' . $subQuery->__toString() . ') AS a')
			->join('LEFT', $db->quoteName('#__categories') . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt');
		$query->order('a.lft ASC');

		$db->setQuery($query);
		$categories = $db->loadObjectList();

		$article_cats = array(0 => array('value' => '', 'label' => Text::_('COM_SPPAGEBUILDER_ADDON_ARTICLE_ALL_CAT')));


		if (!empty($categories))
		{
			foreach ($categories as $category)
			{
				$value = (object) [
					'value' => $category->id,
					'label' => str_repeat('- ', ($category->level - 1)) . $category->title
				];

				$article_cats[] = $value;
			}
		}

		return $article_cats;
	}

	/**
	 * Get Module Attributes
	 *
	 * @return void
	 * @since 4.0.0
	 */
	public static function getModuleAttributes()
	{
		$moduleAttr = array();

		// Module Name and ID
		$db     = Factory::getDbo();
		$query  = $db->getQuery(true);
		$query->select('id, title');
		$query->from('#__modules');
		$query->where('client_id = 0');
		$query->where('published = 1');
		$query->order('ordering, title');
		$db->setQuery($query);
		$modules = $db->loadObjectList();

		if (count((array) $modules))
		{
			$moduleName = array();
			foreach ($modules as  $key => $module)
			{
				$moduleName[$key]['value'] = $module->id;
				$moduleName[$key]['label'] = $module->title;
			}
			$moduleAttr['moduleName'] = $moduleName;
		}

		// Module positions
		$db     = Factory::getDbo();
		$query  = $db->getQuery(true);
		$query->select(array('position'))
			->from('#__modules')
			->where('client_id = 0')
			->where('published = 1')
			->group('position')
			->order('position ASC');
		$db->setQuery($query);
		$positions = $db->loadColumn();

		$template = self::getTemplateName();
		$templateXML = JPATH_SITE . '/templates/' . $template . '/templateDetails.xml';
		$template = simplexml_load_file($templateXML);

		foreach ($template->positions[0] as $position)
		{
			$positions[] =  (string) $position;
		}

		$positions = array_unique($positions);

		if (count((array) $positions))
		{
			$modulePoss = array();
			foreach ($positions as $key => $position)
			{
				$posArray['value'] = $position;
				$posArray['label'] = $position;
				array_push($modulePoss, $posArray);
			}
			$moduleAttr['modulePosition'] = $modulePoss;
		}

		return $moduleAttr;
	}

	/**
	 * Get Row Global Settings
	 *
	 * @return array
	 * @since 4.0.0
	 */
	public static function getRowGlobalSettings()
	{
		require JPATH_ROOT . '/components/com_sppagebuilder/builder/settings/row.php';

		return $row_settings;
	}

	/**
	 * Get Column Global Settings
	 *
	 * @return array
	 * @since 4.0.0
	 */
	public static function getColumnGlobalSettings()
	{
		require JPATH_ROOT . '/components/com_sppagebuilder/builder/settings/column.php';

		return $column_settings;
	}

	/**
	 * Get Settings Default Value
	 *
	 * @param array $addon_attr
	 * @return void
	 * @since 4.0.0
	 */
	public static function getSettingsDefaultValue($addon_attr = [])
	{
		$default = array();

		if (!is_array($addon_attr))
		{
			return array('default' => $default);
		}

		$sppbOneAddon = false;
		foreach ($addon_attr as $key => $options)
		{
			if (isset($options['type']) && !is_array($options['type']))
			{
				$sppbOneAddon = true;
				if ($options['type'] == 'repeatable')
				{
					$default[$key] = self::repeatableFieldVal($options['attr']);
				}
				else if (isset($options['std']))
				{
					$default[$key] = $options['std'];
				}
			}
			else
			{

				foreach ($options as $key => $option)
				{
					if (isset($option['std']))
					{
						$default[$key] = $option['std'];
					}
					else if (isset($option['attr']))
					{
						$default[$key] = self::repeatableFieldVal($option['attr']);
					}
					else
					{
						if (isset($option['std']))
						{
							$default[$key] = $option['std'];
						}
					}
				}
			}
		}
		$newAddonAttr = array();

		$newAddonAttr['default'] = $default;
		if ($sppbOneAddon)
		{
			$newAddonAttr['attr'] = array('general' => $addon_attr);
		}

		return $newAddonAttr;
	}

	/**
	 * Get Repeatable Field Value
	 *
	 * @param array $option
	 * 
	 * @return void
	 * @since 4.0.0
	 */
	public static function repeatableFieldVal($option = array())
	{
		$redefault = array();
		foreach ($option as $rkey => $reOption)
		{
			if (isset($reOption['std']))
			{
				$redefault[0][$rkey] = $reOption['std'];
			}
			if (isset($reOption['type']))
			{
				if ($reOption['type'] == 'repeatable' && isset($reOption['attr']))
				{
					$redefault[0][$rkey] = self::repeatableFieldVal($reOption['attr']);
				}
				if (isset($reOption['std']) && $reOption['type'] == 'builder')
				{
					$now = new DateTime();
					$redefault[0][$rkey] = [['id' => $now->getTimestamp(), 'name' => 'text_block', 'settings' => ['text' => $reOption['std']], 'visibility' => true]];
				}
			}
		}

		return $redefault;
	}

	/**
	 * Acymailing List
	 *
	 * @return void
	 * @since 4.0.0
	 */
	public static function acymailingList()
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.enabled');
		$query->from($db->quoteName('#__extensions', 'a'));
		$query->where('(' . $db->quoteName('a.element') . ' = ' . $db->quote($db->escape('com_acymailing')) . ' OR ' . $db->qn('a.element') . ' = ' . $db->q($db->escape('com_acym')) . ')');
		$db->setQuery($query);
		$is_enabled = $db->loadResult();

		if ($is_enabled)
		{
			// Get acymailing version
			$acym_version = self::getExtensionVersion(array('com_acymailing', 'com_acym'));

			$query2 = $db->getQuery(true);
			if ($acym_version >= 6)
			{
				$query2->select($db->quoteName(array('id', 'name')));
				$query2->from($db->quoteName('#__acym_list'));
				$query2->where($db->quoteName('active') . ' = ' . $db->quote(1));
			}
			else
			{
				$query2->select($db->quoteName(array('listid', 'name')));
				$query2->from($db->quoteName('#__acymailing_list'));
				$query2->where($db->quoteName('published') . ' = ' . $db->quote(1));
			}
			$query2->order('name DESC');
			$db->setQuery($query2);
			$lists = $db->loadObjectList();
			$listArray = array();
			if (count((array) $lists))
			{
				foreach ($lists as $list)
				{
					if ($acym_version >= 6)
					{
						$listArray[$list->id] = $list->name;
					}
					else
					{
						$listArray[$list->listid] = $list->name;
					}
				}
			}
			return $listArray;
		}

		return array();
	}

	/**
	 * Get extension version
	 *
	 * @param string $ext_name
	 * @return void
	 * @since 1.0.0
	 */
	public static function getExtensionVersion($ext_name = '')
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true)
			->select($db->quoteName('e.manifest_cache'))
			->from($db->quoteName('#__extensions', 'e'));

		// multiple extension names
		if (is_array($ext_name) && count((array)$ext_name))
		{
			$ext_elements = implode(' OR ', array_map(function ($entry)
			{
				return "e.element = '" . $entry . "'";
			}, $ext_name));
			$query->where($ext_elements);
		}
		else
		{
			$query->where($db->quoteName('e.element') . ' = ' . $db->quote($ext_name));
		}
		$db->setQuery($query);

		$manifest_cache = json_decode($db->loadResult());
		if (isset($manifest_cache->version) && $manifest_cache->version)
		{
			return $manifest_cache->version;
		}

		return '1.0';
	}

	/**
	 * Get K2 Category List
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public static function k2CatList()
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select('a.enabled');
		$query->from($db->quoteName('#__extensions', 'a'));
		$query->where($db->quoteName('a.name') . " = " . $db->quote('com_k2'));
		$db->setQuery($query);
		$is_enabled = $db->loadResult();

		$listArray = array('' => Text::_('COM_SPPAGEBUILDER_ADDON_ARTICLE_ALL_CAT'));

		if ($is_enabled)
		{
			$db = Factory::getDBO();
			$query = 'SELECT m.* FROM #__k2_categories m WHERE trash = 0 ORDER BY parent, ordering';
			$db->setQuery($query);
			$mitems = $db->loadObjectList();
			$children = array();
			if ($mitems)
			{
				foreach ($mitems as $v)
				{
					if (K2_JVERSION != '15')
					{
						$v->title = $v->name;
						$v->parent_id = $v->parent;
					}
					$pt = $v->parent;
					$list = @$children[$pt] ? $children[$pt] : array();
					array_push($list, $v);
					$children[$pt] = $list;
				}
			}

			$list = HTMLHelper::_('menu.treerecurse', 0, '', array(), $children, 9999, 0, 0);
			$mitems = array();

			if (count((array) $list))
			{
				foreach ($list as $item)
				{
					$item->treename = StringHelper::str_ireplace('&#160;', '- ', $item->treename);
					$mitems[] = HTMLHelper::_('select.option', $item->id, '   ' . $item->treename);
				}
			}

			if (count((array) $mitems))
			{
				foreach ($mitems as $key => $category)
				{
					$listArray[$category->value] = $category->text;
				}
			}
		}

		return $listArray;
	}

	public static function getUserPermissions()
	{
		$user = Factory::getUser();

		if (!$user->id)
		{
			return [
				'create' => false,
				'edit' => false,
				'edit_state' => false,
				'edit_own' => false,
				'delete' => false,
				'user_id' => $user->id
			];
		}

		$canCreate = $user->authorise('core.create', 'com_sppagebuilder');
		$canEdit = $user->authorise('core.edit', 'com_sppagebuilder');
		$canEditState = $user->authorise('core.edit.state', 'com_sppagebuilder');
		$canEditOwn = $user->authorise('core.edit.own', 'com_sppagebuilder');
		$canDelete = $user->authorise('core.delete', 'com_sppagebuilder');

		return [
			'create' => $canCreate,
			'edit' => $canEdit,
			'edit_state' => $canEditState,
			'edit_own' => $canEditOwn,
			'delete' => $canDelete,
			'user_id' => $user->id
		];
	}
}
