<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Trait for managing menu by page id API endpoint.
 */
trait MenuByPageIdTrait
{
    public function getMenuByPageId()
    {
        $method = $this->getInputMethod();
		$this->checkNotAllowedMethods(['POST', 'DELETE','PUT', 'PATCH'], $method);

        if ($method === 'GET')
        {
            $this->getMenu();
        }
    }

    /**
     * Get Menu Page Id.
     *
     * @return void
     * 
     * @since 4.0.0
     */
    public function getMenu()
    {
        $model = $this->getModel('Editor');
        $pageId = $this->getInput('id', 0, 'INT');

        $response = $model->getMenuByPageId($pageId);

        $this->sendResponse($response);
    }
}