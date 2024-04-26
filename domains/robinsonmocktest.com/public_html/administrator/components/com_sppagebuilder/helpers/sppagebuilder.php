<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Component\ComponentHelper;

require_once JPATH_ROOT . '/components/com_sppagebuilder/helpers/autoload.php';

BuilderAutoload::loadClasses();
BuilderAutoload::loadHelperClasses();

JLoader::register('BuilderIntegrationHelper', JPATH_ROOT . '/components/com_sppagebuilder/helpers/integration-helper.php');

final class SppagebuilderHelper
{

	public static $extension = 'com_sppagebuilder';

	public static function addScript($script, $client = 'admin', $version = true)
	{
		$doc = Factory::getDocument();

		$script_url = Uri::root(true) . ($client == 'admin' ? '/administrator' : '') . '/components/com_sppagebuilder/assets/js/' . $script;
		if ($version)
		{
			$script_url .= '?' . self::getVersion(true);
		}

		$doc->addScript($script_url);
	}

	public static function addStylesheet($stylesheet, $client = 'admin', $version = true)
	{
		$doc = Factory::getDocument();
		$stylesheet_url = Uri::root(true) . ($client == 'admin' ? '/administrator' : '') . '/components/com_sppagebuilder/assets/css/' . $stylesheet;

		if ($version)
		{
			$stylesheet_url .= '?' . self::getVersion(true);
		}

		$doc->addStylesheet($stylesheet_url);
	}

	public static function loadAssets($type = 'all')
	{
		$doc = Factory::getDocument();
		$cParams = ComponentHelper::getParams('com_sppagebuilder');
		HTMLHelper::_('jquery.framework');

		if ($type == 'all' || $type == 'css')
		{
			self::addStylesheet('font-awesome-5.min.css', 'site');
			self::addStylesheet('font-awesome-v4-shims.css', 'site');
			self::addStylesheet('sppagebuilder.css');
		}
	}

	public static function loadEditor()
	{
		$app = Factory::getApplication();
		$doc = Factory::getDocument();
		$conf = Factory::getConfig();

		if (JVERSION < 4)
		{
			$doc->addScript(Uri::root(true) . '/media/editors/tinymce/tinymce.min.js');
			$doc->addScriptdeclaration('var tinyTheme="modern";');
		}
		else
		{
			$wa = $doc->getWebAssetManager();

			if (!$wa->assetExists('script', 'tinymce'))
			{
				$wa->registerScript('tinymce', 'media/vendor/tinymce/tinymce.min.js', [], ['defer' => true]);
			}

			if (!$wa->assetExists('script', 'plg_editors_tinymce'))
			{
				$wa->registerScript('plg_editors_tinymce', 'plg_editors_tinymce/tinymce.min.js', [], ['defer' => true], ['core', 'tinymce']);
			}

			$wa->useScript('tinymce')
				->useScript('plg_editors_tinymce');

			$doc->addScriptdeclaration('var tinyTheme="silver";');
			$doc->addStyledeclaration('.tox-tinymce-aux {z-index: 130012 !important;}');
		}

		// JCE Editor
		$editor  = $conf->get('editor');

		if ($editor == 'jce')
		{
			require_once(JPATH_ADMINISTRATOR . '/components/com_jce/includes/base.php');
			wfimport('admin.models.editor');
			$editor = new WFModelEditor();

			$settings = $editor->getEditorSettings();

			$app->triggerEvent('onBeforeWfEditorRender', array(&$settings));
			echo $editor->render($settings);
		}
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

	// 3rd party

	public static function onAfterIntegrationSave($attribs)
	{

		if (!self::getIntegration($attribs['option'])) return;

		$attribs['css'] = '';

		$db = Factory::getDbo();

		if (self::checkPage($attribs['option'], $attribs['view'], $attribs['id']) || $attribs['action'] == 'delete' || $attribs['action'] == 'stateChange')
		{

			if ($attribs['action'] == 'stateChange')
			{
				$fields = array(
					$db->quoteName('published') . ' = ' . $db->quote($attribs['published'])
				);
				self::updatePage($attribs['id'], $fields);
			}
			elseif ($attribs['action'] == 'delete')
			{
				self::deleteArticlePage($attribs);
			}
			else
			{
				$fields = array(
					$db->quoteName('title') . ' = ' . $db->quote($attribs['title']),
					// $db->quoteName('text') . ' = ' . $db->quote($attribs['text']),
					$db->quoteName('published') . ' = ' . $db->quote($attribs['published']),
					$db->quoteName('catid') . ' = ' . $db->quote($attribs['catid']),
					$db->quoteName('access') . ' = ' . $db->quote($attribs['access']),
					$db->quoteName('modified') . ' = ' . $db->quote($attribs['modified']),
					$db->quoteName('modified_by') . ' = ' . $db->quote($attribs['modified_by']),
					$db->quoteName('active') . ' = ' . $db->quote($attribs['active'])
				);

				self::updatePage($attribs['id'], $fields, $attribs['view']);
			}
		}
		else
		{
			$values = array(
				$db->quote($attribs['title']),
				$db->quote('[]'),
				$db->quote($attribs['text']),
				$db->quote($attribs['option']),
				$db->quote($attribs['view']),
				$db->quote($attribs['id']),
				$db->quote($attribs['active']),
				$db->quote($attribs['published']),
				$db->quote($attribs['catid']),
				$db->quote($attribs['access']),
				$db->quote($attribs['created_on']),
				$db->quote($attribs['created_by']),
				$db->quote($attribs['modified']),
				$db->quote($attribs['modified_by']),
				$db->quote($attribs['language']),
				$db->quote($attribs['css']),
				$db->quote($attribs['version'])
			);

			self::insertPage($values);
		}

		return true;
	}

	public static function onIntegrationPrepareContent($text, $option, $view, $id = 0)
	{

		if (!self::getIntegration($option))
		{
			return $text;
		}

		$pageName = $view . '-' . $id;

		$page_content = self::getPageContent($option, $view, $id);

		if ($page_content)
		{
			$page_content = ApplicationHelper::preparePageData($page_content);
			$page_content->text = !is_string($page_content->text) ? json_encode($page_content->text) : $page_content->text;

			require_once JPATH_ROOT . '/components/com_sppagebuilder/parser/addon-parser.php';
			$doc = Factory::getDocument();
			$params = ComponentHelper::getParams('com_sppagebuilder');

			if ($params->get('fontawesome', 1))
			{
				self::addStylesheet('font-awesome-5.min.css', 'site');
				self::addStylesheet('font-awesome-v4-shims.css', 'site');
			}

			if (!$params->get('disableanimatecss', 0))
			{
				self::addStylesheet('animate.min.css', 'site');
			}

			if (!$params->get('disablecss', 0))
			{
				self::addStylesheet('sppagebuilder.css', 'site');
			}

			HTMLHelper::_('jquery.framework');
			HTMLHelper::_('script', 'components/com_sppagebuilder/assets/js/jquery.parallax.js', ['version' => SppagebuilderHelperSite::getVersion(true)]);
			HTMLHelper::_('script', 'components/com_sppagebuilder/assets/js/sppagebuilder.js', ['version' => SppagebuilderHelperSite::getVersion(true)], ['defer' => true]);

			$page_content->text = SppagebuilderHelperSite::sanitizeImportJSON($page_content->text);
			return '<div id="sp-page-builder" class="sp-page-builder sppb-' . $view . '-page-wrapper"><div class="page-content">' . AddonParser::viewAddons(json_decode($page_content->text), 0, $pageName) . '</div></div>';
		}

		return $text;
	}

	public static function getPageContent($extension, $extension_view, $view_id = 0)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__sppagebuilder'));
		$query->where($db->quoteName('extension') . ' = ' . $db->quote($extension));
		$query->where($db->quoteName('extension_view') . ' = ' . $db->quote($extension_view));
		$query->where($db->quoteName('view_id') . ' = ' . $db->quote($view_id));
		$query->where($db->quoteName('active') . ' = 1');
		$db->setQuery($query);
		$result = $db->loadObject();

		if (count((array) $result))
		{
			return $result;
		}

		return false;
	}

	private static function checkPage($extension, $extension_view, $view_id = 0)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('id')));
		$query->from($db->quoteName('#__sppagebuilder'));
		$query->where($db->quoteName('extension') . ' = ' . $db->quote($extension));
		$query->where($db->quoteName('extension_view') . ' = ' . $db->quote($extension_view));
		$query->where($db->quoteName('view_id') . ' = ' . $db->quote($view_id));
		$db->setQuery($query);

		return $db->loadResult();
	}

	private static function insertPage($content = array())
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$columns = array(
			'title',
			'text',
			'content',
			'extension',
			'extension_view',
			'view_id',
			'active',
			'published',
			'catid',
			'access',
			'created_on',
			'created_by',
			'modified',
			'modified_by',
			'language',
			'css',
			'version'
		);

		$query
			->insert($db->quoteName('#__sppagebuilder'))
			->columns($db->quoteName($columns))
			->values(implode(',', $content));

		$db->setQuery($query);
		$db->execute();
	}

	private static function updatePage($view_id, $content, $extension_view = '')
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$condition = array($db->quoteName('view_id') . ' = ' . $db->quote($view_id));

		if ($extension_view != '')
		{
			array_push($condition,  $db->quoteName('extension_view') . ' = ' . $db->quote($extension_view));
		}

		$query->update($db->quoteName('#__sppagebuilder'))->set($content)->where($condition);

		$db->setQuery($query);
		$db->execute();
	}

	public static function getIntegration($option)
	{
		$group = str_replace('com_', '', $option);
		$integrations = BuilderIntegrationHelper::getIntegrations();

		if (!isset($integrations[$group]))
		{
			return false;
		}

		$integration = $integrations[$group];
		$name = $integration['name'];

		$enabled = PluginHelper::isEnabled($group, $name);

		if ($enabled)
		{
			return $integration;
		}

		return false;
	}

	public static function getMenuId($pageId)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('id')));
		$query->from($db->quoteName('#__menu'));
		$query->where($db->quoteName('link') . ' LIKE ' . $db->quote('%option=com_sppagebuilder&view=page&id=' . $pageId . '%'));
		$query->where($db->quoteName('published') . ' = ' . $db->quote('1'));
		$db->setQuery($query);
		$result = $db->loadResult();

		if ($result)
		{
			return '&Itemid=' . $result;
		}

		return '';
	}

	private static function deleteArticlePage($params)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$conditions = array(
			$db->quoteName('extension') . ' = ' . $db->quote($params['option']),
			$db->quoteName('extension_view') . ' = ' . $db->quote($params['view']),
			$db->quoteName('view_id') . ' = ' . $db->quote($params['id']),
		);

		$query->delete($db->quoteName('#__sppagebuilder'));
		$query->where($conditions);
		$db->setQuery($query);
		$db->execute();
	}

	public static function formatSavedAddon($code)
	{
		$code = is_string($code) ? json_decode($code) : $code;

		if (!isset($code->addon))
		{
			$mockSection = self::createMockSection($code);
			$parseMockSection = SppagebuilderHelperSite::sanitize($mockSection);
			$parseMockSection = json_decode($parseMockSection);
			$addonData = $parseMockSection[0]->columns[0]->addons[0];

			$savedAddon = (object) [
				'name' => $code->name ?? '',
				'rows' => [],
				'addon' => [$addonData]
			];

			return json_encode($savedAddon);
		}

		return json_encode($code);
	}

	public static function createMockSection($addon)
	{
		$section = (object) [
			'id' => '',
			'visibility' => false,
			'collapse' => false,
			'settings' => new stdClass,
			'columns' => [
				(object) [
					'id' => "",
					'class_name' => "row-column",
					'visibility' => true,
					'settings' => new stdClass,
					'addons' => [$addon]
				]
			],
			'layout' => '12',
			'parent' => false
		];

		return json_encode([$section]);
	}

	public static function formatSavedSection($section)
	{
		$section = is_string($section) ? json_decode($section) : $section;
		$section = !is_array($section) ? [$section] : $section;

		return SppagebuilderHelperSite::sanitize(json_encode($section));
	}
}
