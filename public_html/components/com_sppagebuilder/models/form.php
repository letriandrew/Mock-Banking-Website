<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

//no direct access
defined('_JEXEC') or die;

JLoader::register('SppagebuilderHelperRoute', JPATH_ROOT . '/components/com_sppagebuilder/helpers/route.php');

// Base this model on the backend version.
JLoader::register('SppagebuilderModelPage', JPATH_ADMINISTRATOR . '/components/com_sppagebuilder/models/page.php');

class SppagebuilderModelForm extends SppagebuilderModelPage
{
	protected $_conText = 'com_sppagebuilder.page';
	protected $_item = array();

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
		$app = Factory::getApplication();
		$user = Factory::getUser();

		if($user->id === 0) {
			$loginUrl = Route::_('index.php?option=com_users&view=login');
			$app->redirect($loginUrl);
		}

		$canEdit = $user->authorise('core.edit', 'com_sppagebuilder');

		if (!$canEdit)
		{
			throw new Exception(Text::_('COM_SPPAGEBUILDER_INVALID_EDIT_ACCESS'), 403);
		}

		$pageId = (!empty($pageId)) ? $pageId : (int)$this->getState('page.id');


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

				$published = $this->getState('filter.published');

				if (is_numeric($published))
				{
					$query->where($db->quoteName('a.published') . ' = ' . (int) $published);
				}
				elseif ($published === '')
				{
					$query->where($db->quoteName('a.published') . ' IN (0, 1)');
				}

				$db->setQuery($query);
				$data = $db->loadObject();

				if (empty($data))
				{
					throw new Exception(Text::_('COM_SPPAGEBUILDER_ERROR_PAGE_NOT_FOUND'), 404);
				}

				if ($this->getState('filter.access'))
				{
					$data->access_view = true;
				}
				else
				{
					$user = Factory::getUser();
					$groups = $user->getAuthorisedViewLevels();

					$data->access_view = in_array($data->access, $groups);
				}

				if (isset($data->attribs))
				{
					$attribs = json_decode($data->attribs);
				}
				else
				{
					$attribs = new stdClass;
				}

				$data->link = SppagebuilderHelperRoute::getPageRoute($data->id, $data->language);
				$data->formLink = SppagebuilderHelperRoute::getFormRoute($data->id, $data->language);

				$data->meta_description = (isset($attribs->meta_description) && $attribs->meta_description) ? $attribs->meta_description : '';
				$data->meta_keywords = (isset($attribs->meta_keywords) && $attribs->meta_keywords) ? $attribs->meta_keywords : '';
				$data->robots = (isset($attribs->robots) && $attribs->robots) ? $attribs->robots : '';
				$data->og_type = (isset($attribs->og_type) && $attribs->og_type) ? $attribs->og_type : 'website';

				$menu = $this->getMenuByPageId($data->id);
				$data->menuid = (isset($menu->id) && $menu->id) ? $menu->id : 0;
				$data->menutitle = (isset($menu->title) && $menu->title) ? $menu->title : '';
				$data->menualias = (isset($menu->alias) && $menu->alias) ? $menu->alias : '';
				$data->menutype = (isset($menu->menutype) && $menu->menutype) ? $menu->menutype : '';
				$data->menuparent_id = (isset($menu->parent_id) && $menu->parent_id) ? $menu->parent_id : 0;
				$data->menuordering = (isset($menu->id) && $menu->id) ? $menu->id : -2;

				$this->_item[$pageId] = $data;
			}
			catch (Exception $e)
			{
				if ($e->getCode() == 404)
				{
					throw new Exception($e->getMessage(), 404);
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

	public function getForm($data = array(), $loadData = true)
	{
		$app = Factory::getApplication();
		$user = Factory::getUser();

		// Get the form.
		$form = $this->loadForm('com_sppagebuilder.page', 'page', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		// Manually check-out
		$pageId = (!empty($pageId)) ? $pageId : (int)$this->getState('page.id');
		if ($user->id)
		{
			$this->checkout($pageId);
		}


		return parent::getForm();
	}

	public function save($data)
	{
		$attribs = array();

		if (isset($data['meta_description']) && $data['meta_description'])
		{
			$attribs['meta_description'] = $data['meta_description'];
		}

		if (isset($data['meta_keywords']) && $data['meta_keywords'])
		{
			$attribs['meta_keywords'] = $data['meta_keywords'];
		}

		if (isset($data['robots']) && $data['robots'])
		{
			$attribs['robots'] = $data['robots'];
		}

		if (isset($data['og_type']) && $data['og_type'])
		{
			$attribs['og_type'] = $data['og_type'];
		}

		$data['attribs'] = json_encode($attribs);

		return parent::save($data);
	}

	public function getMenuByPageId($pageId = 0)
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select(array('a.*'));
		$query->from('#__menu as a');
		$query->where('a.link = ' . $db->quote('index.php?option=com_sppagebuilder&view=page&id=' . $pageId));
		$query->where('a.client_id = 0');
		$db->setQuery($query);

		return $db->loadObject();
	}

	public function getMenuById($menuId = 0)
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select(array('a.*'));
		$query->from('#__menu as a');
		$query->where('a.id = ' . $menuId);
		$query->where('a.client_id = 0');
		$db->setQuery($query);

		return $db->loadObject();
	}

	public function getMenuByAlias($alias, $menuId = 0)
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select(array('a.id', 'a.title', 'a.alias', 'a.menutype', 'a.parent_id', 'a.component_id'));
		$query->from('#__menu as a');
		$query->where('a.alias = ' . $db->quote($alias));
		if ($menuId)
		{
			$query->where('a.id != ' . (int) $menuId);
		}
		$query->where('a.client_id = 0');
		$db->setQuery($query);

		return $db->loadObject();
	}

	public function createNewPage($title)
	{
		$user = Factory::getUser();
		$date = Factory::getDate();
		$db = $this->getDbo();
		$page = new stdClass();
		$page->title = $title;
		$page->Text = '[]';
		$page->extension = 'com_sppagebuilder';
		$page->extension_view = 'page';
		$page->published = 1;
		$page->created_by = (int) $user->id;
		$page->created_on = $date->toSql();
		$page->language = '*';
		$page->access = 1;
		$db->insertObject('#__sppagebuilder', $page);

		return $db->insertid();
	}

	public function deletePage($id = 0)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$conditions = array(
			$db->quoteName('id') . ' = ' . $id
		);
		$query->delete($db->quoteName('#__sppagebuilder'));
		$query->where($conditions);
		$db->setQuery($query);
		$result = $db->execute();
		return $result;
	}

	public function getPageItem($id = 0)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('extension', 'extension_view', 'view_id', 'catid'));
		$query->from($db->quoteName('#__sppagebuilder'));
		$query->where($db->quoteName('id') . ' = ' . $db->quote($id));
		$db->setQuery($query);
		$result = $db->loadObject();

		if (count((array) $result))
		{
			return $result;
		}

		return false;
	}

	public function addArticleFullText($id, $data)
	{
		$article = new stdClass();
		$article->id = $id;
		$article->fulltext = SppagebuilderHelperSite::getPrettyText($data);

		Factory::getDbo()->updateObject('#__content', $article, 'id');
	}
	/**
	 * Save Data function
	 *
	 * @param  array $data
	 * @return void
	 * 
	 * @since 4.0.0
	 */
	public function saveData($data, $id)
	{
		$attrKeys = [
			'meta_description',
			'meta_keywords',
			'robots',
			'seo_spacer',
			'og_type'			
		];

		$menuKeys = [
			'menuid',
			'menutitle',
			'menualias',
			'menutype',
			'menuparent_id',
			'menuordering'
		];


		$attribs = [];

		foreach ($data as $key => $value)
		{
			if (in_array($key, $attrKeys))
			{
				$attribs[$key] = $value;
				unset($data[$key]);
			}

			if (in_array($key, $menuKeys)) {
				unset($data[$key]);
			} 
		}

		$data['attribs'] = json_encode($attribs);

		if (!empty($data['og_image']) && !is_string($data['og_image']))
		{
			$data['og_image'] = json_encode($data['og_image']);
		}

		if (empty($data['catid']))
		{
			$data['catid'] = 0;
		}

		$data = (object) $data;

		if (empty($id))
		{
			$response['status'] = false;
			$response['message'] = 'No ID Provided!';

			echo json_encode($response);
			die();
		}

		$this->setState('filter.access', $data->access);

		try
		{
			$db = Factory::getDbo();
			$db->updateObject('#__sppagebuilder', $data, 'id', true);

			$response['status'] = true;
			$response['message'] = 'Updated';
		}
		catch (Exception $e)
		{
			$response['status'] = false;
			$response['message'] = $e->getMessage();
		}

		return $response;
	}
}
