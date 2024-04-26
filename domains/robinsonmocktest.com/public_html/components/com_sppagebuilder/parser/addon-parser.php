<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Access\Access;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Plugin\PluginHelper;

// No direct access
defined('_JEXEC') or die('Restricted access');

require_once __DIR__ . '/addons.php';
require_once __DIR__ . '/../helpers/helper.php';
require_once __DIR__ . '/../helpers/addon-helper.php';

require_once JPATH_ROOT . '/components/com_sppagebuilder/builder/classes/base.php';
require_once JPATH_ROOT . '/components/com_sppagebuilder/builder/classes/config.php';

/**
 * Addon Parser Class.
 * 
 * @since 1.0.0
 */
class AddonParser
{
	public  static $loaded_addon           = array();
	public  static $css_content            = array();
	public  static $module_css_content     = array();
	public  static $js_content             = '';
	private static $sppagebuilderAddonTags = array();
	private static $template               = '';
	public  static $authorised             = array();
	public  static $addon_interactions     = array();
	private static $contents               = array();
	private static $module_contents        = array();
	private static $deepAddonList          = ['accordion' => 'sp_accordion_item', 'tab' => 'sp_tab_item'];

	public static function addAddon($tag, $func)
	{
		if (is_callable($func))
			self::$sppagebuilderAddonTags[$tag] = $func;
	}

	public static function spDoAddon($content)
	{
		if (false === strpos($content, '['))
		{
			return $content;
		}

		if (empty(self::$sppagebuilderAddonTags) || !is_array(self::$sppagebuilderAddonTags))
			return $content;
		$pattern = self::getAddonRegex();

		return preg_replace_callback("/$pattern/s", array('AddonParser', 'doAddonTag'), $content);
	}

	/**
	 * Import/Include addon file
	 *
	 * @param string  $file_name  The addon name. Optional
	 *
	 * @since 1.0.8
	 */
	public static function getAddonPath($addon_name = '')
	{

		$template_path = JPATH_ROOT . '/templates/' . self::$template;
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


	private static function getAddonRegex()
	{
		$tagnames = array_keys(self::$sppagebuilderAddonTags);
		$tagregexp = join('|', array_map('preg_quote', $tagnames));
		// WARNING! Do not change this regex without changing do_addon_tag() and strip_addon_tag()
		// Also, see addon_unautop() and shortcode.js.
		return
			'\\['                              // Opening bracket
			. '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
			. "($tagregexp)"                     // 2: Shortcode name
			. '(?![\\w-])'                       // Not followed by word character or hyphen
			. '('                                // 3: Unroll the loop: Inside the opening shortcode tag
			.     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
			.     '(?:'
			.         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
			.         '[^\\]\\/]*'               // Not a closing bracket or forward slash
			.     ')*?'
			. ')'
			. '(?:'
			.     '(\\/)'                        // 4: Self closing tag ...
			.     '\\]'                          // ... and closing bracket
			. '|'
			.     '\\]'                          // Closing bracket
			.     '(?:'
			.         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
			.             '[^\\[]*+'             // Not an opening bracket
			.             '(?:'
			.                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
			.                 '[^\\[]*+'         // Not an opening bracket
			.             ')*+'
			.         ')'
			.         '\\[\\/\\2\\]'             // Closing shortcode tag
			.     ')?'
			. ')'
			. '(\\]?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
	}


	private static function doAddonTag($m)
	{
		// allow [[foo]] syntax for escaping a tag
		if ($m[1] == '[' && $m[6] == ']')
		{
			return substr($m[0], 1, -1);
		}
		$tag = $m[2];
		$attr = self::addonParseAtts($m[3]);
		if (isset($m[5]))
		{
			// enclosing tag - extra parameter
			return $m[1] . call_user_func(self::$sppagebuilderAddonTags[$tag], $attr, $m[5], $tag) . $m[6];
		}
		else
		{
			// self-closing tag
			return $m[1] . call_user_func(self::$sppagebuilderAddonTags[$tag], $attr, null,  $tag) . $m[6];
		}
	}


	private static function addonParseAtts($text)
	{
		$atts = array();
		$pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
		$text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
		if (preg_match_all($pattern, $text, $match, PREG_SET_ORDER))
		{
			foreach ($match as $m)
			{
				if (!empty($m[1]))
					$atts[strtolower($m[1])] = stripcslashes($m[2]);
				elseif (!empty($m[3]))
					$atts[strtolower($m[3])] = stripcslashes($m[4]);
				elseif (!empty($m[5]))
					$atts[strtolower($m[5])] = stripcslashes($m[6]);
				elseif (isset($m[7]) and strlen($m[7]))
					$atts[] = stripcslashes($m[7]);
				elseif (isset($m[8]))
					$atts[] = stripcslashes($m[8]);
			}
		}
		else
		{
			$atts = ltrim($text);
		}
		return $atts;
	}


	public static function getAddons()
	{
		self::$template = self::getTemplateName();

		require_once JPATH_ROOT . '/components/com_sppagebuilder/addons/module/site.php'; //include module manually

		$template_path = JPATH_ROOT . '/templates/' . self::$template;
		$tmpl_folders = array();
		if (file_exists($template_path . '/sppagebuilder/addons'))
		{
			$tmpl_folders = Folder::folders($template_path . '/sppagebuilder/addons');
		}


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
				$tmpl_file_path = $template_path . '/sppagebuilder/addons/' . $folder . '/site.php';
				$com_file_path = JPATH_ROOT . '/components/com_sppagebuilder/addons/' . $folder . '/site.php';
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
	}

	private function checkObjectKeyValue($object)
	{
		if (!is_object($object))
		{
			return false;
		}
	}

	private static function getRowById($id, $pageName)
	{
		$newContents = $pageName == "module" ? self::$module_contents : self::$contents;

		if (empty($newContents))
		{
			return null;
		}

		for ($i = count($newContents) - 1; $i >= 0; $i--)
		{
			if ($newContents[$i]->id === $id)
			{
				return $newContents[$i];
			}
		}

		return null;
	}


	public static function viewAddons($content, $fluid = 0, $pageName = 'none', $level = 1, $newModule = true)
	{
		SpPgaeBuilderBase::loadAddons();
		$addon_list = SpAddonsConfig::$addons;

		if (!\class_exists('SppagebuilderHelperSite'))
		{
			require_once JPATH_ROOT . '/components/com_sppagebuilder/helpers/helper.php';
		}

		if ($newModule && $pageName == 'module')
		{
			self::$module_contents = $content;
		}

		if (empty(self::$contents) && $pageName != 'module')
		{
			self::$contents = $content;
		}

		self::$authorised = Access::getAuthorisedViewLevels(Factory::getUser()->get('id'));

		$layout_path = JPATH_ROOT . '/components/com_sppagebuilder/layouts';

		$layouts =  new stdClass;

		$layouts->row_start       = new FileLayout('row.start', $layout_path);
		$layouts->row_end         = new FileLayout('row.end', $layout_path);
		$layouts->row_css         = new FileLayout('row.css', $layout_path);

		$layouts->column_start    = new FileLayout('column.start', $layout_path);
		$layouts->column_end      = new FileLayout('column.end', $layout_path);
		$layouts->column_css      = new FileLayout('column.css', $layout_path);

		$layouts->addon_start     = new FileLayout('addon.start', $layout_path);
		$layouts->addon_end       = new FileLayout('addon.end', $layout_path);
		$layouts->addon_css       = new FileLayout('addon.css', $layout_path);

		$doc = Factory::getDocument();
		$content = is_object($content) ? (array) $content : $content;

		if (is_array($content))
		{
			$output = '';

			foreach ($content as $row)
			{
				if (!isset($row)) break;

				$row->settings->dynamicId = $row->id;

				// Row Visibility and ACL
				if (isset($row->visibility) && !$row->visibility)
				{
					continue;
				}

				if ($level <= 1 && isset($row->parent) && $row->parent !== false)
				{
					continue;
				}

				if ($fluid === 1 || !empty($row->parent))
				{
					$row->settings->fullscreen = 1;
				}

				$row_css = $layouts->row_css->render(array('options' => $row->settings));

				if ($pageName === 'module')
				{
					array_push(self::$module_css_content, $row_css);
				}
				else
				{
					array_push(self::$css_content, $row_css);
				}

				$row->settings->isNestedRow = isset($row->parent) && $row->parent !== false;

				Factory::getApplication()->triggerEvent('onBeforeRowRender', array(&$row));

				$output .= $layouts->row_start->render(array('options' => $row->settings));

				foreach ($row->columns as $column)
				{
					if (!\is_object($column->settings))
					{
						$column->settings = !empty($column->settings) && \is_array($column->settings)
							? (object) $column->settings
							: new \stdClass;
					}

					$column->settings->cssClassName = $column->class_name;
					$column->settings->cssClassName = str_replace('column-parent ', '', $column->settings->cssClassName);
					$column->settings->cssClassName = str_replace('active-column-parent', '', $column->settings->cssClassName);
					$column->settings->dynamicId = $column->id;

					// Column Visibility and ACL
					if (isset($column->visibility) && !$column->visibility)
					{
						continue;
					}

					/** Inject the column width to the column settings. */
					if (!isset($column->settings->width))
					{
						$width = !empty($column->width) ? $column->width : SppagebuilderHelperSite::getColumnWidth($column);
						$column->settings->width = $width;

						if (isset($column->settings->width->unit))
						{
							unset($column->settings->width->unit);
						}
					}

					$column->settings->width = SppagebuilderHelperSite::purifyColumnWidth($column->settings->width);


					$column_css = $layouts->column_css->render(array('options' => $column->settings));

					if ($pageName === 'module')
					{
						array_push(self::$module_css_content, $column_css);
					}
					else
					{
						array_push(self::$css_content, $column_css);
					}

					$output .= $layouts->column_start->render(array('options' => $column->settings));

					foreach ($column->addons as $key => $addon)
					{
						// Interaction
						if (isset($addon->settings->mouse_movement) || isset($addon->settings->while_scroll_view))
						{
							$selectors =  ['while_scroll_view', 'mouse_movement'];

							if (!isset($addon->id))
							{
								continue;
							}

							self::parseInteractions($addon->id, $addon->settings, $selectors);
						}

						/** Addon Visibility */
						if (isset($addon->visibility) && !$addon->visibility)
						{
							continue;
						}

						/** Check for the ACL */
						if (!self::checkAddonACL($addon))
						{
							continue;
						}

						if (isset($addon->type) && $addon->type === 'nested_row')
						{
							$newPageName = $pageName === 'module' ? 'module' : 'none';
							$nestedRow = self::getRowById($addon->id, $newPageName);
							$output .= self::viewAddons([$nestedRow], 0, $newPageName, 2, false);
						}
						elseif (!empty($addon->name) && $addon->name === 'div' && empty($addon->parent))
						{
							$output .= self::getDivHTMLView($addon, $column->addons, $layouts, $pageName);
						}
						else
						{

							$addon->settings->row_id = $row->id;
							$addon->settings->column_id = $column->id;

							$output .= self::getAddonHtmlView($addon, $layouts, $pageName);
						}
					}

					$output .= $layouts->column_end->render(array('options' => $column->settings));
				}

				$output .=  $layouts->row_end->render(array('options' => $row->settings));
			}

			// interaction js
			if (count(self::$addon_interactions) > 0 && $pageName != 'none' && $pageName != 'module')
			{
				$doc->addScriptDeclaration('var addonInteraction = ' . json_encode(self::$addon_interactions) . ';');
			}

			if ($pageName === 'module')
			{
				return  AddonParser::spDoAddon($output) . '<style type="text/css">' . self::convertCssArrayToString(self::minifyCss(self::$module_css_content)) . '</style>';
			}
			else
			{
				if ($pageName !== 'none')
				{
					$app = Factory::getApplication();
					$params = $app->getParams('com_sppagebuilder');
					$production_mode = $params->get('production_mode', 0);

					$inline_css = self::convertCssArrayToString(self::minifyCss(self::$css_content));

					if ($production_mode)
					{
						$css_folder_path = JPATH_ROOT . '/media/com_sppagebuilder/css';
						$css_file_path = $css_folder_path . '/' . $pageName . '.css';
						$css_file_url = Uri::base(true) . '/media/com_sppagebuilder/css/' . $pageName . '.css';

						if (!Folder::exists($css_folder_path))
						{
							Folder::create($css_folder_path);
						}

						file_put_contents($css_file_path, $inline_css);

						if (file_exists($css_file_path))
						{
							$doc->addStylesheet($css_file_url);
						}
						else
						{
							$doc->addStyleDeclaration($inline_css);
						}
					}
					else
					{
						$doc->addStyleDeclaration($inline_css);
					}
				}

				return AddonParser::spDoAddon($output);
			}
		}
		else
		{
			return '<p>' . $content . '</p>';
		}
	}

	private static function getAddonById($addons, $id)
	{
		foreach ($addons as $index => $addon)
		{
			if ($addon->id === $id)
			{
				return [$index, $addon];
			}
		}

		return [-1, null];
	}

	private static function generateDivCSS($addon, $pageName, $layouts)
	{
		if (empty($addon->name))
		{
			return '';
		}

		$addonPath = AddonParser::getAddonPath($addon->name);
		$output = '';

		if (file_exists($addonPath . '/site.php'))
		{
			require_once $addonPath . '/site.php';

			$addonClassName = 'SppagebuilderAddon' . ucfirst($addon->name);
			$addonInstance = new $addonClassName($addon);

			$addonCss = $layouts->addon_css->render(array('addon' => $addon));
			self::$css_content[] = $addonCss;

			if (method_exists($addonClassName, 'css'))
			{
				if ($pageName === 'module')
				{
					$output .= '<style type="text/css">' . $addonCss . '</style>';
					$output .= '<style type="text/css">' . $addonInstance->css() . '</style>';
				}
				else
				{
					$cssContent = $addonInstance->css();
					self::$css_content[] = $cssContent;
				}
			}
		}

		return $output;
	}

	private static function generateAnimation($addon)
	{
		$animationClass = '';
		$animationAttributes = '';
		$settings = $addon->settings;

		if (!empty($settings->global_use_animation) && !empty($settings->global_animation))
		{
			$animationClass .= ' sppb-wow ' . $settings->global_animation;
		}

		if (!empty($settings->global_animationduration))
		{
			$animationAttributes .= ' data-sppb-wow-duration="' . $settings->global_animationduration . 'ms"';
		}
		
		if (!empty($settings->global_animationdelay))
		{
			$animationAttributes .= ' data-sppb-wow-delay="' . $settings->global_animationdelay . 'ms"';
		}

		return [
			'class' => $animationClass,
			'attributes' => $animationAttributes
		];
	}

	/**
	 * Generate the HTML structure of the nested DIV addon.
	 * The DIV addon can accepts other addons as a child addon,
	 * and the nesting could be more than one.
	 *
	 * @param 	stdClass 	$addon		The addon object with all the addon settings.
	 * @param 	array 		$addonList	The list of addons inside the parent column.
	 * @param 	stdClass 	$layouts	The layout object containing the addon_start, addon_end etc.
	 * @param 	string 		$pageName	The flag for defining is the addon is rendering inside a module or page.
	 *
	 * @return 	string 		The generated HTML for the DIV addon(s).
	 * @since 	4.0.0
	 */
	public static function getDivHTMLView($addon, &$addonList, $layouts, $pageName)
	{
		$divElement = '';

		if (isset($addon->visited) && $addon->visited)
		{
			return $divElement;
		}

		if (!empty($addon->name))
		{
			if ($addon->name === 'div')
			{
				$animation = self::generateAnimation($addon);
				
				$custom_class = "";
				$custom_class .= (isset($addon->settings->hidden_md) && filter_var($addon->settings->hidden_md, FILTER_VALIDATE_BOOLEAN)) ? 'sppb-hidden-md sppb-hidden-lg ' : '';
				$custom_class .= (isset($addon->settings->hidden_sm) && filter_var($addon->settings->hidden_sm, FILTER_VALIDATE_BOOLEAN)) ? 'sppb-hidden-sm ' : '';
				$custom_class .= (isset($addon->settings->hidden_xs) && filter_var($addon->settings->hidden_xs, FILTER_VALIDATE_BOOLEAN)) ? 'sppb-hidden-xs ' : '';

				$divElement .= self::generateDivCSS($addon, $pageName, $layouts);
				$divElement .= '<div id="sppb-addon-wrapper-' . $addon->id . '" class="sppb-addon-wrapper">';
				$divElement .= '<div id="sppb-addon-' . $addon->id . '" class="sppb-div-addon ' . $addon->settings->class . $custom_class . $animation['class'] . '" ' . $animation['attributes'] . '>';
			}
			else
			{
				$divElement .= self::getAddonHtmlView($addon, $layouts, $pageName, true);
			}
		}

		if (!empty($addon->children))
		{
			foreach ($addon->children as $child)
			{
				list($index, $childAddon) = self::getAddonById($addonList, $child);

				if ($index > -1)
				{
					$divElement .= self::getDivHTMLView($childAddon, $addonList, $layouts, $pageName);
					$addonList[$index]->visited = true;
				}
			}
		}

		if (!empty($addon->name) && $addon->name === 'div')
		{
			$divElement .= '</div></div>';
		}

		return $divElement;
	}

	public static function getAddonHtmlView($addon, $layouts, $pageName = 'none', $isChild = false)
	{
		/**
		 * If the addons ia a DIV addon then skip rendering the addon.
		 */
		if (isset($addon->name) && $addon->name === 'div')
		{
			return '';
		}

		/** Addon Visibility */
		if (isset($addon->visibility) && !$addon->visibility)
		{
			return;
		}

		/**
		 * If the addon has parent property and not forced to render
		 * i.e. the addon is not a child of a parent addon then skip.
		 */
		if (isset($addon->parent) && $addon->parent && !$isChild)
		{
			return '';
		}

		if (!isset($addon->name))
		{
			return '';
		}

		$addon_list = SpAddonsConfig::$addons;

		$addon_name = $addon->name;
		$class_name = 'SppagebuilderAddon' . ucfirst($addon_name);
		$addon_path = AddonParser::getAddonPath($addon_name);

		$doc = Factory::getDocument();

		$output = '';

		if (file_exists($addon_path . '/site.php'))
		{
			$addon_options = array();

			if (isset($addon_list[$addon->name]['attr']) && $addon_list[$addon->name]['attr'])
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
				$deviceObject = (object) [
					'xl' => '',
					'lg' => '',
					'md' => '',
					'sm' => '',
					'xs' => '',
				];

				/** If the data is old one, then- */
				if (isset($setting->md) && !isset($setting->xl))
				{
					$deviceObject->xl = $setting->md;
					$deviceObject->lg = $setting->md;
				}

				if (\is_object($setting))
				{
					$original = \json_decode(\json_encode($setting));
				}

				$originalKey = $key . '_original';

				if (SppagebuilderHelperSite::hasMultiDeviceSettings($setting))
				{
					foreach ($deviceObject as $device => $_)
					{
						if (isset($setting->$device))
						{
							$deviceObject->$device = $setting->$device;
						}
					}

					$unit = (isset($setting->unit) && $addon_name !== 'clients') ? $setting->unit : "";

					if (!empty($unit))
					{
						$deviceObject->unit = $unit;
					}

					$store->$originalKey = $deviceObject;
					$defaultDevice = SpPgaeBuilderBase::$defaultDevice;
					$store->$key = $deviceObject->$defaultDevice;

					$keySm = $key . '_sm';
					$keyXs = $key . '_xs';
					$keyLg = $key . '_lg';
					$keyXl = $key . '_xl';
					$keyMd = $key . '_md';

					$addon->settings->$keySm = \is_string($deviceObject->sm) ? $deviceObject->sm . $unit : $deviceObject->sm;
					$addon->settings->$keyXs = \is_string($deviceObject->xs) ? $deviceObject->xs . $unit : $deviceObject->xs;
					$addon->settings->$keyXl = \is_string($deviceObject->xl) ? $deviceObject->xl . $unit : $deviceObject->xl;
					$addon->settings->$keyLg = \is_string($deviceObject->lg) ? $deviceObject->lg . $unit : $deviceObject->lg;
					$addon->settings->$keyMd = \is_string($deviceObject->md) ? $deviceObject->md . $unit : $deviceObject->md;
				}

				if (isset($addon_options[$key]['selector']))
				{
					$addon_selector = $addon_options[$key]['selector'];
					if (isset($addon->settings->$key) && !empty($addon->settings->$key))
					{
						$selector_value = $addon->settings->$key;
						$keySelector = $key . '_selector';
						$addon->settings->$keySelector = str_replace('{{ VALUE }}', $selector_value, $addon_selector);
					}
				}
			}


			unset($setting);

			foreach ($store as $key => $value)
			{
				$addon->settings->$key = $value;
			}

			//plugin support for addonRender
			PluginHelper::importPlugin('system');
			Factory::getApplication()->triggerEvent('onBeforeAddonRender', array(&$addon));
			// End plugin support for addonRender

			$output .= $layouts->addon_start->render(array('addon' => $addon)); // start addon
			require_once $addon_path . '/site.php';

			$hasRepeatableItems = self::checkRepeatableItems($addon->settings, $addon->name);

			if (!empty($hasRepeatableItems))
			{
				self::processRepeatableItems($hasRepeatableItems, $addon->name, $layouts, $pageName);
			}

			if (class_exists($class_name))
			{
				// Instantiate addon class
				$addon_obj  = new $class_name($addon);


				$output .= $addon_obj->render();

				// Scripts
				if (method_exists($class_name, 'scripts'))
				{
					$scripts = $addon_obj->scripts();

					if (!empty($scripts))
					{
						foreach ($scripts as $key => $script)
						{
							$doc->addScript($script);
						}
					}
				}

				// JS
				if (method_exists($class_name, 'js'))
				{
					if (!empty($addon_obj->js()))
					{
						$doc->addScriptDeclaration($addon_obj->js());
					}
				}

				// Stylesheets
				if (method_exists($class_name, 'stylesheets'))
				{
					$stylesheets = $addon_obj->stylesheets();

					if (!empty($stylesheets))
					{
						foreach ($stylesheets as $key => $stylesheet)
						{
							$doc->addStyleSheet($stylesheet);
						}
					}
				}

				$addon_css = $layouts->addon_css->render(array('addon' => $addon));

				if ($pageName == 'module')
				{
					$output .= '<style type="text/css">' . $addon_css . '</style>';
				}
				else
				{
					// array_unshift(self::$css_content, $addon_css);
					array_push(self::$css_content, $addon_css);
				}

				// css
				if (method_exists($class_name, 'css'))
				{
					if ($pageName == 'module')
					{
						$output .= '<style type="text/css">' . $addon_obj->css() . '</style>';
					}
					else
					{
						$cssContent = $addon_obj->css();
						// array_unshift(self::$css_content, $cssContent);
						array_push(self::$css_content, $cssContent);
					}
				}
			}
			else
			{
				$output .= htmlspecialchars_decode(AddonParser::spDoAddon(AddonParser::generateShortcode($addon)));
			}

			$output .= $layouts->addon_end->render();
		}

		return $output;
	}

	public static function checkRepeatableItems($settings, $addon_name)
	{
		$repeatableItems = [];
		$itemKey = 'sp_' . $addon_name . '_item';
		$minimalItemKey = $addon_name . '_item';

		$isRepeatable = isset($settings->$itemKey) && !empty($settings->$itemKey);

		if ($isRepeatable)
		{
			array_push($repeatableItems, $settings->$itemKey);
		}

		if (isset($settings->$minimalItemKey) && !empty($settings->$minimalItemKey))
		{
			array_push($repeatableItems, $settings->$minimalItemKey);
		}

		return $repeatableItems;
	}

	public static function processRepeatableItems($repeatableItems, $addon_name, $layouts, $pageName)
	{
		$items = $repeatableItems;
		$newContent = '';

		foreach ($items as &$settings)
		{
			if (!empty($settings))
			{
				foreach ($settings as $key => &$item)
				{
					if (isset($item->content) && is_array($item->content))
					{
						$newContent = '';

						foreach ($item->content as $contentAddon)
						{
							// Addon Visibility and ACL
							if (isset($contentAddon->visibility) && !$contentAddon->visibility)
							{
								continue;
							}

							// Check for ACL
							$access = self::checkAddonACL($contentAddon);

							if (!$access)
							{
								continue;
							}

							if (isset($contentAddon->type) && $contentAddon->type === 'nested_row')
							{
								$newPageName = $pageName === 'module' ? 'module' : 'none';
								$nestedRow = self::getRowById($contentAddon->id, $newPageName);
								$newContent .= self::viewAddons([$nestedRow], 0, $newPageName, 2, false);
							}
							else
							{
								$newContent .= self::getAddonHtmlView($contentAddon, $layouts, $pageName);
							}
						}

						$item->content = $newContent;
					}
					else
					{
						$repeatableItems = self::checkRepeatableItems($item, $addon_name);

						if (!empty($repeatableItems))
						{
							$repeatableItems = self::processRepeatableItems($repeatableItems, $addon_name, $layouts, $pageName);
						}
					}
				}

				unset($item);
			}
		}

		unset($settings);

		return $items;
	}

	public static function minifyCss($cssCode)
	{
		// Remove comments
		$cssCode = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $cssCode);

		// Remove space after colons
		$cssCode = str_replace(': ', ':', $cssCode);

		// Remove whitespace
		$cssCode = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $cssCode);

		// Remove Empty Selectors without any properties
		$cssCode = preg_replace('/(?:(?:[^\r\n{}]+)\s?{[\s]*})/', '', $cssCode);

		// Remove Empty Media Selectors without any properties or selector
		$cssCode = preg_replace('/@media\s?\((?:[^\r\n,{}]+)\s?{[\s]*}/', '', $cssCode);

		return $cssCode;
	}

	public static function generateShortcode($addon)
	{

		if (!empty($addon->settings))
		{
			$addon->settings->dynamicId = $addon->id;
			$ops = AddonParser::generateShortcodeOps($addon->settings);
		}

		$output = '[sp_' . $addon->name;
		if (isset($ops['default']))
		{
			$output .= $ops['default'];
		}
		$output .= ']';
		if (isset($ops['repeat']))
		{
			$output .= $ops['repeat'];
		}
		$output .= '[/sp_' . $addon->name . ']';

		return $output;
	}

	public static function generateShortcodeOps($ops)
	{
		$default = '';
		$repeat  = '';

		foreach ($ops as $key => $val)
		{
			if (!is_array($val))
			{
				$default .= ' ' . $key . '="' . htmlspecialchars($val) . '"';
			}
			else
			{
				$temp = '';
				foreach ($val as $innerKey => $innerVal)
				{
					$temp .= '[' . $key;
					foreach ($innerVal as $inner_key => $inner_val)
					{
						$temp .= ' ' . $inner_key . '="' . htmlspecialchars($inner_val) . '"';
					}
					$temp .= '][/' . $key . ']';
				}
				$repeat .= $temp;
			}
		}

		if ($default) $result['default'] = $default;
		if ($repeat) $result['repeat'] = $repeat;

		return $result;
	}


	// Get list of plugin addons
	private static function getPluginsAddons()
	{
		$path = JPATH_PLUGINS . '/sppagebuilder';

		if (!Folder::exists($path)) return;

		$plugins = Folder::folders($path);
		if (!count((array) $plugins)) return;

		$elements = array();
		$addonPaths = [];

		foreach ($plugins as $plugin)
		{
			if (PluginHelper::isEnabled('sppagebuilder', $plugin))
			{
				$addonPaths[] = $path . '/' . $plugin . '/addons';
			}
		}

		foreach ($addonPaths as $addonsPath)
		{
			if (Folder::exists($addonsPath))
			{
				$addons = Folder::folders($addonsPath);

				foreach ($addons as $addon)
				{
					$addonPath = $addonsPath . '/' . $addon;

					if (File::exists($addonPath . '/site.php'))
					{
						$elements[$addon] = $addonPath;
					}
				}
			}
		}

		return $elements;
	}

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

	public static function convertCssArrayToString($cssArray = array())
	{
		$cssString = '';
		if (count((array) $cssArray) > 0)
		{
			foreach ($cssArray as $cssItem)
			{
				$cssString .= $cssItem;
			}
		}

		return $cssString;
	}

	public static function checkAddonACL($addon)
	{
		$access = true;
		if (isset($addon->settings->acl) && $addon->settings->acl)
		{
			$access_list = $addon->settings->acl;
			$access = false;
			foreach ($access_list as $acl)
			{
				if (in_array($acl, self::$authorised))
				{
					$access = true;
				}
			}
			unset($addon->settings->acl);
		}

		return $access;
	}

	/**
	 * Print interaction css and javascript object
	 */
	private static function parseInteractions($addonId, &$addonSettings, $selectors)
	{
		foreach ($selectors as $selector)
		{
			$interactions = isset($addonSettings->$selector) ? $addonSettings->$selector : [];

			if (!empty($interactions))
			{
				$interactions = $interactions[0];
				$animationCollection = new \stdClass;
				$animationCollection->addonId = $addonId;
				$animationCollection->enable_mobile = isset($interactions->enable_mobile) && $interactions->enable_mobile;
				$animationCollection->scrolling_options = isset($addonSettings->scrolling_options) ? $addonSettings->scrolling_options : 'viewport';

				$animationCollection->enable_tablet = isset($interactions->enable_tablet) && $interactions->enable_tablet;

				if ($selector === 'while_scroll_view' && $interactions->enable_while_scroll_view)
				{
					$animation = isset($interactions->on_scroll_actions) ? $interactions->on_scroll_actions : [];

					if (count($animation) > 1)
					{
						usort($animation, function ($x, $y)
						{
							return $x->keyframe - $y->keyframe;
						});
					}

					$animationCollection->animation = $animation;
					$animationCollection->name = 'custom';

					$addonSettings_transition_origin_x = isset($addonSettings->transition_origin_x) ? $addonSettings->transition_origin_x : '';
					$addonSettings_transition_origin_y = isset($addonSettings->transition_origin_y) ? $addonSettings->transition_origin_y : '';
					$interaction_transition_origin_x = isset($interactions->transition_origin_x) ? $interactions->transition_origin_x : '';
					$interaction_transition_origin_y = isset($interactions->transition_origin_y) ? $interactions->transition_origin_y : '';

					$xOffset = !empty($addonSettings_transition_origin_x) ? $addonSettings_transition_origin_x : $interaction_transition_origin_x;
					$yOffset = !empty($addonSettings_transition_origin_y) ? $addonSettings_transition_origin_y : $interaction_transition_origin_y;

					$animationCollection->origin = ['x_offset' => $xOffset, 'y_offset' => $yOffset];

					if (isset(self::$addon_interactions[$selector]))
					{
						array_push(self::$addon_interactions[$selector], $animationCollection);
					}
					else
					{
						self::$addon_interactions[$selector] = array($animationCollection);
					}
				}

				if ($selector === 'mouse_movement' && $interactions->enable_tilt_effect)
				{
					$animationCollection->animation = $interactions;

					if (isset(self::$addon_interactions[$selector]))
					{
						array_push(self::$addon_interactions[$selector], $animationCollection);
					}
					else
					{
						self::$addon_interactions[$selector] = array($animationCollection);
					}
				}
			}
		}
	}

	private static function shortByKeyFrame($x, $y)
	{
		return $x['keyframe'] - $y['keyframe'];
	}
}

function spAddonAtts($pairs, $atts, $shortcode = '')
{
	$atts = (array)$atts;
	$out = array();
	foreach ($pairs as $name => $default)
	{
		if (array_key_exists($name, $atts))
			$out[$name] = $atts[$name];
		else
			$out[$name] = $default;
	}

	return $out;
}

AddonParser::getAddons();
