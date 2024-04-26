<?php

use Joomla\CMS\Table\Table;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Trait for managing add to menu API endpoint.
 */
trait AddToMenuTrait
{
    public function addToMenu()
    {
        $method = $this->getInputMethod();
        $this->checkNotAllowedMethods(['POST', 'GET', 'DELETE', 'PATCH'], $method);

        if ($method === "PUT")
        {
            $this->addMenuItem();
        }
    }

    private function addMenuItem()
    {
        $model = $this->getModel('Editor');

        $pageId = $this->getInput('page_id', 0, 'INT');
        $menuId = $this->getInput('menu_id', 0, 'INT');
        $parentId = $this->getInput('parent_id', 0, 'INT');
        $menuType = $this->getInput('menu_type', 'mainmenu', 'STRING');
        $title = $this->getInput('title', '', 'STRING');
        $alias = $this->getInput('alias', OutputFilter::stringURLSafe($title), 'STRING');
        $menuOrdering = $this->getInput('ordering', 0, 'INT');

        $componentId = ComponentHelper::getComponent('com_sppagebuilder')->id;

        $menu = $model->getMenuById($menuId);
        $home = (isset($menu->home) && $menu->home) ? $menu->home : 0;
        $link = 'index.php?option=com_sppagebuilder&view=page&id=' . (int) $pageId;

        BaseDatabaseModel::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_menus/models');
        Table::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_menus/tables');

        $menuModel = $this->getModel('Item', 'MenusModel');

        $menuData = [
            'id' => (int) $menuId,
            'link' => $link,
            'parent_id' => (int) $parentId,
            'menutype' => htmlspecialchars($menuType),
            'title' => htmlspecialchars($title),
            'alias' => htmlspecialchars($alias),
            'type' => 'component',
            'published' => 1,
            'language' => '*',
            'component_id' => $componentId,
            'menuordering' => (int) $menuOrdering,
            'home' => (int) $home,
        ];

        $response = new stdClass();

        try
        {
            $menuModel->save($menuData);
            $response->data = Text::_("COM_SPPAGEBUILDER_SUCCESS_MSG_FOR_PAGE_ADDED");

            $this->sendResponse($response);
        }
        catch (Exception $e)
        {
            $this->sendResponse(['message' => $e->getMessage()], 500);
        }
    }
}
