<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\CMS\Plugin\PluginHelper;

//no direct access
defined('_JEXEC') or die('Restricted access');

JLoader::register('SppagebuilderHelperRoute', JPATH_ROOT . '/components/com_sppagebuilder/helpers/route.php');
/**
 * Page List class
 */
class SppagebuilderModelPage extends ItemModel
{

	protected $_context = 'com_sppagebuilder.page';

	protected function populateState()
	{
		$app = Factory::getApplication('site');

		$pageId = $app->input->getInt('id');
		$this->setState('page.id', $pageId);

		$user = Factory::getUser();

		if ((!$user->authorise('core.edit.state', 'com_sppagebuilder')) && (!$user->authorise('core.edit', 'com_sppagebuilder')))
		{
			$this->setState('filter.published', 1);
		}
	}

	public function getItem($pageId = null)
	{
		$user = Factory::getUser();

		$pageId = (!empty($pageId)) ? $pageId : (int)$this->getState('page.id');

		if ($this->_item == null)
		{
			$this->_item = array();
		}

		if (!isset($this->_item[$pageId]))
		{
			try
			{
				$db = $this->getDbo();
				$query = $db->getQuery(true);

				$query->select('a.*')
					->from($db->quoteName('#__sppagebuilder', 'a'))
					->where($db->quoteName('a.id') . ' = ' . (int) $pageId);

				$query->select($db->quoteName('l.title', 'language_title'))
					->join('LEFT', $db->quoteName('#__languages', 'l') . ' ON ' . $db->quoteName('l.lang_code') . ' = ' . $db->quoteName('a.language'));

				$query->select($db->quoteName('ua.name', 'author_name'))
					->join('LEFT', $db->quoteName('#__users', 'ua') . ' ON ' . $db->quoteName('ua.id') . ' = ' . $db->quoteName('a.created_by'));

				$query->where($db->quoteName('a.published') . ' = 1');

				$db->setQuery($query);
				$data = $db->loadObject();

				if (empty($data))
				{
					return Text::_('COM_SPPAGEBUILDER_ERROR_UNPUBLISHED_PAGE');
				}

				$data->link = SppagebuilderHelperRoute::getPageRoute($data->id, $data->language);
				$data->formLink = SppagebuilderHelperRoute::getFormRoute($data->id, $data->language);

				if(!empty($data->content))
				{
					$data->text = $data->content;
				}

				if ($this->getState('filter.access'))
				{
					$data->access_view = true;
				}
				else
				{
					$groups = $user->getAuthorisedViewLevels();

					$data->access_view = in_array($data->access, $groups);
				}

				$this->_item[$pageId] = $data;
			}
			catch (Exception $e)
			{
				if ($e->getCode() == 404)
				{
					throw new Exception($e->getMessage(), 'error');
				}
				else
				{
					$this->setError($e);
					$this->_item[$pageId] = false;
				}
			}
		}

		return $this->_item[$pageId];
	}

	/**
	 * Increment the hit counter for the page.
	 *
	 * @param   integer  $pk  Optional primary key of the page to increment.
	 *
	 * @return  boolean  True if successful; false otherwise and internal error set.
	 */
	public function hit($pk = 0)
	{
		$pk = (!empty($pk)) ? $pk : (int) $this->getState('page.id');
		$table = Table::getInstance('Page', 'SppagebuilderTable');
		$table->load($pk);
		$table->hit($pk);

		return true;
	}

	public function getMySections()
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('id', 'title', 'section')));
		$query->from($db->quoteName('#__sppagebuilder_sections'));
		//$query->where($db->quoteName('profile_key') . ' LIKE '. $db->quote('\'custom.%\''));
		$query->order('id ASC');
		$db->setQuery($query);
		$results = $db->loadObjectList();
		return json_encode($results);
	}

	public function deleteSection($id)
	{
		$db = Factory::getDbo();

		$query = $db->getQuery(true);

		// delete all custom keys for user 1001.
		$conditions = array(
			$db->quoteName('id') . ' = ' . $id
		);

		$query->delete($db->quoteName('#__sppagebuilder_sections'));
		$query->where($conditions);

		$db->setQuery($query);

		return $db->execute();
	}

	public function saveSection($title, $section)
	{
		$db = Factory::getDbo();
		$user = Factory::getUser();
		$obj = new \stdClass;
		$obj->title = $title;
		$obj->section = $section;
		$obj->created = Factory::getDate()->toSql();
		$obj->created_by = $user->get('id');

		$db->insertObject('#__sppagebuilder_sections', $obj);

		return $db->insertid();
	}

	public function getMyAddons()
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('id', 'title', 'code', 'created', 'created_by')));
		$query->from($db->quoteName('#__sppagebuilder_addons'));

		$query->order('id ASC');
		$db->setQuery($query);
		$results = $db->loadObjectList();

		if (!empty($results))
		{
			foreach ($results as &$result)
			{
				$result->code = SppagebuilderHelper::formatSavedAddon($result->code);
			}

			unset($result);
		}

		return json_encode($results);
	}

	public function saveAddon($title, $section)
	{
		$db = Factory::getDbo();
		$user = Factory::getUser();
		$obj = new \stdClass;
		$obj->title = $title;
		$obj->code = $addon;
		$obj->created = Factory::getDate()->toSql();
		$obj->created_by = $user->get('id');

		$db->insertObject('#__sppagebuilder_addons', $obj);

		return $db->insertid();
	}

	public function deleteAddon($id)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		// delete all custom keys for user 1001.
		$conditions = array(
			$db->quoteName('id') . ' = ' . $id
		);

		$query->delete($db->quoteName('#__sppagebuilder_addons'));
		$query->where($conditions);
		$db->setQuery($query);

		return $db->execute();
	}

	public function getMyPages()
	{
		$user = Factory::getUser();
		$authorised = $user->authorise('core.create', 'com_sppagebuilder') || (count((array) $user->getAuthorisedCategories('com_sppagebuilder', 'core.create')));
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('a.id', 'a.title', 'a.published', 'a.catid', 'a.created_on', 'a.language')));
		$query->from($db->quoteName('#__sppagebuilder', 'a'));
		$query->select('c.title AS category_title, c.alias AS category_alias')
			->join('LEFT', '#__categories AS c ON c.id = a.catid');
		if (!$authorised)
		{
			$query->where($db->quoteName('a.created_by') . ' = ' . (int) $user->id);
		}
		$query->where($db->quoteName('a.published') . ' != ' . -2);
		$query->where($db->quoteName('a.extension') . ' = ' . $db->quote('com_sppagebuilder'));
		$query->order('ordering ASC');
		$db->setQuery($query);

		$categories = array();
		$categories['all'] = array(
			'alias' => 'all',
			'title' => 'Select Category'
		);
		$items = $db->loadObjectList();

		if (is_array($items) && count($items))
		{
			foreach ($items as $key => &$item)
			{
				if (!isset($item->category_alias))
				{
					$item->category_alias = 'all';
					$item->category_title = 'Select Category';
				}
				$item->created_date = HTMLHelper::_('date', $item->created_on, 'DATE_FORMAT_LC3');
				// get menu id
				$Itemid = $this->getMenuId($item->id);
				$item->link = 'index.php?option=com_sppagebuilder&task=page.edit&id=' . $item->id;
				// Get item language code
				$lang_code = (isset($item->language) && $item->language && explode('-', $item->language)[0]) ? explode('-', $item->language)[0] : '';
				// check language filter plugin is enable or not
				$enable_lang_filter = PluginHelper::getPlugin('system', 'languagefilter');
				// get joomla config
				$conf = Factory::getConfig();

				$item->preview = SppagebuilderHelperRoute::getPageRoute($item->id, $lang_code);

				$item->frontend_edit = SppagebuilderHelperRoute::getFormRoute($item->id, $lang_code);

				if (isset($item->category_title) && $item->category_title)
				{
					$categories[$item->category_alias] = array(
						'alias' => $item->category_alias,
						'title' => $item->category_title
					);
				}
			}

			$newCcategories = array();

			foreach ($categories as $category)
			{
				$newCcategories[] = $category;
			}

			echo json_encode(
				array(
					'status' => true,
					'pages' => $items,
					'categories' => $newCcategories
				)
			);

			die();
		}

		echo json_encode(
			array(
				'status' => false
			)
		);

		die();
	}

	public function getMenuId($pageId)
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
}
