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
use Joomla\CMS\MVC\Model\ListModel;

/**
 * AppConfig Model Class for managing app configs.
 * 
 * @version 4.1.0
 */
class SppagebuilderModelAppconfig extends ListModel
{
	/**
	 * Media __construct function
	 * 
	 * @param mixed $config
	 */
	public function __construct($config = [])
	{
		parent::__construct($config);
	}

	public function getPageList()
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select(['id', 'title'])
			->from($db->quoteName('#__sppagebuilder'))
			->where($db->quoteName('published') . ' = 1')
			->order($db->quoteName('title') . ' ASC');

		$db->setQuery($query);

		try
		{
			return $db->loadObjectList();
		}
		catch (\Exception $e)
		{
			return [];
		}
	}

	public function getCategories()
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select(['id', 'title', 'level', 'lft'])
			->from($db->quoteName('#__categories'))
			->where($db->quoteName('published') . ' = 1')
			->where($db->quoteName('extension') . ' = ' . $db->quote('com_sppagebuilder'))
			->order($db->quoteName('lft') . ' ASC');

		$db->setQuery($query);

		try
		{
			return $db->loadObjectList();
		}
		catch (\Exception $e)
		{
			return [];
		}
	}

	public function getMenus()
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select(['id', 'title', 'link'])
			->from($db->quoteName('#__menu'))
			->where($db->quoteName('published') . ' = 1')
			->where($db->quoteName('id') . ' > 1')
			->where($db->quoteName('client_id') . ' = 0')
			->order($db->quoteName('title') . ' ASC');

		$db->setQuery($query);
		$menuItems = [];

		try
		{
			$menuItems = $db->loadObjectList();
		}
		catch (\Exception $e)
		{
			return [];
		}

		if (!empty($menuItems))
		{
			foreach ($menuItems as &$item)
			{
				$item->id = $item->link . '&Itemid=' . $item->id;
				unset($item->link);
			}

			unset($item);
		}

		return $menuItems;
	}

	public function getModules()
	{
		return [];
	}

	public function getAccessLevels()
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select(['id', 'title'])
			->from($db->quoteName('#__viewlevels'))
			->order($db->quoteName('ordering') . ' ASC');

		$db->setQuery($query);

		try
		{
			return $db->loadObjectList();
		}
		catch (\Exception $e)
		{
			return [];
		}
	}

	public function getLanguages()
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select([$db->quoteName('lang_code', 'id'), 'title'])
			->from($db->quoteName('#__languages'))
			->where($db->quoteName('published') . ' = 1')
			->order($db->quoteName('ordering') . ' ASC');

		$db->setQuery($query);

		try
		{
			return $db->loadObjectList();
		}
		catch (\Exception $e)
		{
			return [];
		}
	}

	public function getUserPermissions()
	{
		$user = Factory::getUser();

		if (!$user->id)
		{
			return [
				'admin' => false,
				'manage' => false,
				'create' => false,
				'edit' => false,
				'edit_state' => false,
				'edit_own' => false,
				'delete' => false,
			];
		}

		$isAdmin = $user->authorise('core.admin', 'com_sppagebuilder');
		$canManage = $user->authorise('core.manage', 'com_sppagebuilder');
		$canCreate = $user->authorise('core.create', 'com_sppagebuilder');
		$canEdit = $user->authorise('core.edit', 'com_sppagebuilder');
		$canEditState = $user->authorise('core.edit.state', 'com_sppagebuilder');
		$canEditOwn = $user->authorise('core.edit.own', 'com_sppagebuilder');
		$canDelete = $user->authorise('core.delete', 'com_sppagebuilder');

		return [
			'admin' => $isAdmin,
			'manage' => $canManage,
			'create' => $canCreate,
			'edit' => $canEdit,
			'edit_state' => $canEditState,
			'edit_own' => $canEditOwn,
			'delete' => $canDelete,
		];
	}
}
