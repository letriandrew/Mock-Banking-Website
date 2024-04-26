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
 * API endpoints for menu list.
 */
trait MenuListTrait
{
    public function getMenuList()
    {
        $method = $this->getInputMethod();
		$this->checkNotAllowedMethods(['POST', 'DELETE', 'PATCH', 'PUT'], $method);

        if ($method === 'GET')
        {
            $this->getMenus();
        }

    }

    private function getMenus()
    {
        $model = $this->getModel();

        $response = $model->getMenus();
        
        $this->sendResponse($response->data, $response->code);
    }
}