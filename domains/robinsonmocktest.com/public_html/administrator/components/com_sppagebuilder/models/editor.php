<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Language\Text;
use Joomla\String\StringHelper;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\MVC\Model\AdminModel;

if (!\class_exists('EditorUtils')) {
	require_once __DIR__ . './../editor/helpers/EditorUtils.php';
}

class SppagebuilderModelEditor extends AdminModel
{

	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
	 * Method for getting a form.
	 *
	 * @param array $data Data for the form.
	 * @param bool $loadData True if the form is to load its own data (default case), false if not.
	 * @return void
	 */
	public function getForm($data = array(), $loadData = true)
	{
	}

	public function getTable($name = 'Editor', $prefix = 'SppagebuilderTable', $options = array())
	{
		return Table::getInstance($name, $prefix, $options);
	}

	public function sortBy(string $param)
	{
		$firstCharacter = substr($param, 0, 1);
		$orderDirection = "ASC";

		if ($firstCharacter === '-') {
			$param = substr($param, 1);
			$orderDirection = "DESC";
		}

		$ordering = new stdClass();
		$ordering->field = $param;
		$ordering->direction = $orderDirection;

		return $ordering;
	}

	public function getPages($pageData)
	{
		$search = $pageData->search;
		$offset = $pageData->offset;
		$limit = $pageData->limit;
		$sortBy = $pageData->sortBy;
		$category = $pageData->category;
		$language = $pageData->language;
		$status = $pageData->status;

		$response = new stdClass();

		try {
			$db = Factory::getDbo();
			$query = $db->getQuery(true);

			$query->select('p.id, p.title, p.text, p.content, p.extension_view, p.catid, p.published, p.created_on, p.created_by, p.language, p.hits, p.checked_out, p.css');
			$query->select('c.title as category');

			$query->from($db->quoteName('#__sppagebuilder', 'p'));
			$query->where($db->quoteName('p.extension') . ' = ' . $db->quote('com_sppagebuilder'));

			$query->select('l.title AS language_title')
				->join('LEFT', $db->quoteName('#__languages', 'l') . ' ON l.lang_code = p.language');

			$query->join('LEFT', $db->quoteName('#__categories', 'c') . ' ON (' . $db->quoteName('p.catid') . ' = ' . $db->quoteName('c.id') . ')');

			$query->select('ug.title AS access_title')
				->join('LEFT', $db->quoteName('#__viewlevels', 'ug') . ' ON (' . $db->quoteName('ug.id') . ' = ' . $db->quoteName('p.access') . ')');

			if (is_numeric($status)) {
				$query->where($db->quoteName('p.published') . ' = '  . $db->quote($status));
			} else {
				$query->where($db->quoteName('p.published') . ' IN (0, 1)');
			}

			if (!empty($access)) {
				$query->where($db->quoteName('p.access') . ' = ' . $db->quote($access));
			}

			if (!empty($category)) {
				$query->where($db->quoteName('p.catid') . ' = ' . $db->quote($category));
			}

			if (!empty($language)) {
				$query->where($db->quoteName('p.language') . ' = ' . $db->quote($language));
			}

			if (!empty($search)) {
				$search = preg_replace("@\s+@", ' ', $search);
				$search = explode(' ', $search);
				$search = array_filter($search, function ($word) {
					return !empty($word);
				});
				$search = implode('|', $search);
				$search = preg_quote($search, '/');
				$query->where($db->quoteName('p.title') . ' REGEXP ' . $db->quote($search));
			}

			if (!empty($sortBy)) {
				$ordering = $this->sortBy($sortBy);
				$query->order($db->quoteName('p.' . $ordering->field) . ' ' . $ordering->direction);
			}

			$fullQuery = $db->getQuery(true);
			$fullQuery = $query->__toString();

			if (!empty($limit)) {
				$query->setLimit($limit, $offset);
			}

			$db->setQuery($query);

			try {
				$results = $db->loadObjectList();
			} catch (\Exception $e) {
				$results = [];
			}

			if (!empty($results)) {
				if (!class_exists('SppagebuilderHelperRoute')) {
					require_once JPATH_ROOT . '/components/com_sppagebuilder/helpers/route.php';
				}

				foreach ($results as &$result) {
					if ($result->created_on) {
						$result->created = (new DateTime($result->created_on))->format('j F, Y');
						unset($result->created_on);
					}

					if (!empty($result->created_by)) {
						$result->author = Factory::getUser($result->created_by)->name;
					}

					if (empty($result->category)) {
						$result->category = '-';
					}

					if (!empty($result->content)) {
						$result->text = $result->content;
						unset($result->content);
					}

					$result->url = SppagebuilderHelperRoute::getFormRoute($result->id, $result->language);
					$result->preview = SppagebuilderHelperRoute::getPageRoute($result->id, $result->language);
				}

				unset($result);
			}

			$db->setQuery($fullQuery);
			$db->execute();
			$allItems = $db->getNumRows();

			$response->totalItems = $allItems;
			$response->totalPages = ceil($allItems / $limit);
			$response->results = EditorUtils::parsePageListData($results);
			$response->code = 200;

			return $response;
		} catch (Exception $e) {
			$response->totalItems = 0;
			$response->totalPages = 0;
			$response->results = $e->getMessage();
			$response->code = 500;

			return $response;
		}
	}

	/**
	 * Count total number of pages.
	 *
	 * @param 	string 	$keyword	The search keyword.
	 *
	 * @return 	int
	 * @since 	4.0.0
	 */
	public function countTotalPages($keyword = ''): int
	{
		$db 	= Factory::getDbo();
		$query 	= $db->getQuery(true);

		$query->select('COUNT(*)')
			->from($db->quoteName('#__sppagebuilder'))
			->where($db->quoteName('extension') . ' = ' . $db->quote('com_sppagebuilder'));

		if (!empty($keyword)) {
			$query->where($db->quoteName('title') . ' REGEXP ' . $db->quote($keyword));
		}

		$db->setQuery($query);

		try {
			return $db->loadResult();
		} catch (Exception $e) {
			return 0;
		}
	}

	public function getPageContent(int $id)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select([
			'id',
			'title',
			'text',
			'content',
			'extension',
			'extension_view',
			'view_id',
			'created_by',
			'catid',
			'access',
			'created_on',
			'attribs',
			'og_title',
			'og_description',
			'og_image',
			'language',
			'published',
			'css',
			'version'
		])->from($db->quoteName('#__sppagebuilder'))
			->where($db->quoteName('id') . ' = ' . $id);

		$db->setQuery($query);
		$result = null;

		try {
			$result = $db->loadObject();
		} catch (\Exception $e) {
			return $result;
		}

		$defaultAttributes = (object) [
			'meta_description' => '',
			'meta_keywords' => '',
			'robots' => 'global'
		];

		$defaultImage = (object) [
			'src' => ''
		];

		if (!empty($result)) {
			$result->text = !empty($result) ? \json_decode($result->text) : null;
			$result->attribs = \json_decode($result->attribs);
			$result->og_image = \json_decode($result->og_image);

			if (empty($result->attribs)) {
				$result->attribs = $defaultAttributes;
			}

			if (empty($result->og_image)) {
				$result->og_image = $defaultImage;
			}

			$result->url = SppagebuilderHelperRoute::getFormRoute($result->id, $result->language);
		}

		return $result;
	}

	public function savePage($data)
	{
		$db = Factory::getDbo();
		$data->css = $data->css ?? '';

		try
		{
			$db->updateObject('#__sppagebuilder', $data, 'id', true);
		}
		catch (\Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	}

	public function duplicatePage(int $id)
	{
		try {
			$db = Factory::getDbo();
			$query = $db->getQuery(true);
			$query->select("*");
			$query->from($db->quoteName('#__sppagebuilder'));
			$query->where($db->quoteName('id') . '=' . $db->quote($id));
			$db->setQuery($query);

			$page = $db->loadObject();

			if (!empty($page)) {
				$page->title =  $this->generatePageNewTitle($page->title);
				$page->hits = 0;
				$page->id = '';
				$db->insertObject('#__sppagebuilder', $page, 'id');
				$this->checkin($id);

				return (object)[
					'response' => [
						'status' => true,
						'id' => $page->id,
						'message' => Text::_("COM_SPPAGEBUILDER_SUCCESS_MSG_FOR_PAGE_DUPLICATED")
					],
					'code' => 201,
				];
			}
		} catch (Exception $e) {
			return (object)['status' => false, 'message' => $e->getMessage(), 'code' => 500];
		}
	}

	/**
	 * Generate page title
	 *
	 * @param string $title current page title.
	 * 
	 * @return string
	 */
	public function generatePageNewTitle($title)
	{
		$table = $this->getTable();

		while ($table->load(['title' => $title])) {
			$title = StringHelper::increment($title);
		}

		return $title;
	}

	/**
	 * Get Menu List
	 *
	 * @return	object	The response
	 * @since	4.0.0
	 */
	public function getMenus(): object
	{
		$response = new stdClass();

		try {
			$db 	= Factory::getDbo();
			$query 	= $db->getQuery(true);

			$query->select('id, menutype, title')
				->from($db->quoteName('#__menu_types'))
				->where($db->quoteName('client_id') . ' = 0');

			$db->setQuery($query);

			$result = $db->loadObjectList();
			$data = [];

			if (!empty($result)) {
				foreach ($result as $value) {
					$data[] = (object) [
						'value' => $value->menutype,
						'label' => $value->title
					];
				}
			}

			$response->data = $data;
			$response->code = 200;

			return $response;
		} catch (Exception $e) {
			$response->message = $e->getMessage();
			$response->code = 500;

			return $response;
		}
	}

	/**
	 * Get Menu List
	 *
	 * @param	string	$menuType 	The menu type
	 * @return	object	The response
	 * @since	4.0.0
	 */
	public function getParentItems(string $menuType, int $id = 0): object
	{
		$response = new stdClass();

		try {
			$db 	= Factory::getDbo();
			$query 	= $db->getQuery(true);
			$query->select('DISTINCT(a.id) AS value, a.title AS text, a.level, a.lft')
				->from($db->quoteName('#__menu', 'a'))
				->where($db->quoteName('a.menutype') . ' = ' . $db->quote($menuType))
				->where($db->quoteName('a.client_id') . ' = 0');

			if ($id > 0) {
				$query->join('LEFT', $db->quoteName('#__menu') . ' AS p ON p.id = ' . (int) $id)
					->where('NOT(a.lft >= p.lft AND a.rgt <= p.rgt)');
			}

			$query->where('a.published != -2')
				->order('a.lft ASC');

			$db->setQuery($query);

			$result = $db->loadObjectList();
			$data = [];

			if (!empty($result)) {
				foreach ($result as $value) {
					$data[] = (object) [
						'value' => $value->value,
						'label' => $value->text
					];
				}
			}

			$rootItem = (object) ['value' => 1, 'label' => Text::_('COM_SPPAGEBUILDER_MENU_ITEM_ROOT')];
			array_unshift($data, $rootItem);

			$response->data = $data;
			$response->code = 200;

			return $response;
		} catch (Exception $e) {
			$response->data = $e->getMessage();
			$response->code = 500;

			return $response;
		}
	}

	public function getMenuByPageId($pageId = 0)
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__menu');
		$query->where($db->quoteName('link') . ' = ' . $db->quote('index.php?option=com_sppagebuilder&view=page&id=' . $pageId));
		$query->where($db->quoteName('client_id') . '= 0');
		$db->setQuery($query);

		return $db->loadObject();
	}

	public function applyBulkActions(object $params)
	{
		switch ($params->type) {
			case 'published':
				return $this->changeStatus($params->ids, 1);
			case 'unpublished':
				return $this->changeStatus($params->ids, 0);
			case 'trash':
				return $this->changeStatus($params->ids, -2);
			case 'delete':
				return $this->deletePages($params->ids);
			case 'check-out':
				return $this->checkOutItems($params->ids);
			case 'rename':
				return $this->renamePage($params->ids, $params->value);
		}
	}

	private function renamePage(string $id, string $value)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->update($db->quoteName('#__sppagebuilder'))
			->set($db->quoteName('title') . ' = ' . $db->quote($value))
			->where($db->quoteName('id') . ' = ' . $id);

		$db->setQuery($query);

		try {
			$db->execute();
			return true;
		} catch (\Exception $e) {
			return false;
		}
	}

	private function changeStatus(string $ids, int $status)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->update($db->quoteName('#__sppagebuilder'))
			->set($db->quoteName('published') . ' = ' . $status)
			->where($db->quoteName('id') . ' IN (' . $ids . ')');

		$db->setQuery($query);

		try {
			$db->execute();
			return true;
		} catch (\Exception $e) {
			return false;
		}
	}

	private function deletePages(string $ids)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->delete($db->quoteName('#__sppagebuilder'))
			->where($db->quoteName('id') . ' IN (' . $ids . ')');

		$db->setQuery($query);

		try {
			$db->execute();
			return true;
		} catch (\Exception $e) {
			return false;
		}
	}

	private function checkOutItems(string $ids)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->update($db->quoteName('#__sppagebuilder'))
			->set($db->quoteName('checked_out') . ' = 0')
			->where($db->quoteName('id') . ' IN (' . $ids . ')');
		$db->setQuery($query);

		try {
			$db->execute();
			return true;
		} catch (\Exception $e) {
			return false;
		}
	}


	public function checkLanguageIsInstalled($language = 'en-GB')
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('id', 'state', 'version')));
		$query->from($db->quoteName('#__sppagebuilder_languages'));
		$query->where($db->quoteName('lang_tag') . ' = ' . $db->quote($language));
		$db->setQuery($query);

		$result = $db->loadObject();

		return !empty($result) ? $result : false;
	}

	public function storeLanguage($language)
	{
		$db = Factory::getDbo();
		$result = $this->checkLanguageIsInstalled($language->lang_tag);
		$version = $language->version;

		if ($result) {
			$values = array(
				'title' => $language->title,
				'description' => $language->description,
				'lang_key' => $language->lang_key,
				'version' => $language->version,
			);
			$version = $this->updateLanguage($values, $language->lang_key);
		} else {
			$values = array(
				$db->quote($language->title),
				$db->quote($language->description),
				$db->quote($language->lang_tag),
				$db->quote($language->lang_key),
				$db->quote($language->version),
				1
			);
			$this->insertLanguage($values);
		}

		return $version;
	}

	private function insertLanguage($values = array())
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$columns = array('title', 'description', 'lang_tag', 'lang_key', 'version', 'state');
		$query
			->insert($db->quoteName('#__sppagebuilder_languages'))
			->columns($db->quoteName($columns))
			->values(implode(',', $values));

		$db->setQuery($query);
		$db->execute();

		return $db->insertid();
	}

	private function updateLanguage($values = array(), $lang_tag = 'en-GB')
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$fields = array(
			$db->quoteName('title') . ' = ' . $db->quote($values['title']),
			$db->quoteName('description') . ' = ' . $db->quote($values['description']),
			$db->quoteName('lang_key') . ' = ' . $db->quote($values['lang_key']),
			$db->quoteName('version') . ' = ' . $db->quote($values['version']),
		);

		$conditions = array($db->quoteName('lang_key') . ' = ' . $db->quote($lang_tag));
		$query->update($db->quoteName('#__sppagebuilder_languages'))->set($fields)->where($conditions);
		$db->setQuery($query);
		$db->execute();

		return $values['version'];
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

	public function createPage(object $data)
	{
		$db = Factory::getDbo();

		try {
			$db->insertObject('#__sppagebuilder', $data, 'id');

			return $data->id;
		} catch (\Exception $e) {
			return ['message' => $e->getMessage()];
		}
	}

	/**
	 * Toggle Integration
	 * 
	 * @return 	boolean
	 * @since 	4.0.0
	 */
	public function toggleIntegration($group = '', $name = '')
	{
		$enabled = PluginHelper::isEnabled($group, $name);
		$status = $enabled ? 0 : 1;

		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$fields = [$db->quoteName('enabled') . ' = ' . $status];

		$conditions = [
			$db->quoteName('type') . ' = ' . $db->quote('plugin'),
			$db->quoteName('element') . ' = ' . $db->quote($name),
			$db->quoteName('folder') . ' = ' . $db->quote($group)
		];

		$query->update($db->quoteName('#__extensions'))->set($fields)->where($conditions);
		$db->setQuery($query);
		$db->execute();

		return $status;
	}

	public function getPageCreator($id)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select('created_by')
			->from($db->quoteName('#__sppagebuilder'))
			->where($db->quoteName('id') . ' = ' . $id);

		$db->setQuery($query);

		try {
			return $db->loadResult();
		} catch (\Exception $e) {
			return 0;
		}
	}
}
