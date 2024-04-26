<?php

use Joomla\CMS\Uri\Uri;

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Trait of Icons list.
 * 
 * @version 4.1.0
 */
trait IconsListTrait
{
    /**
     * Icon API endpoint for CRUD operations.
     * 
     * @return void
     * @version 4.1.0
     */
    public function getIcons()
	{
		$method = $this->getInputMethod();
		$this->checkNotAllowedMethods(['PUT', 'PATCH', 'POST', 'DELETE'], $method);

        switch($method)
        {
            case 'GET':
                $this->loadIcons();
                break;
        }

	}

    private function loadIcons()
    {
        $name 	= $this->getInput('name', '', 'STRING');

        $rootPath = Uri::base(true) . '/media/com_sppagebuilder/assets/iconfont/';
        $response = array();

        $response = [];

        if (empty($name))
        {
            $this->sendResponse($response, 404); 
        }

        $css = $rootPath . $name . '/' . $name . '.css';
        $css = str_replace('\\', '/', $css);

        $model = $this->getModel('Icon');
        $assets = $model->getIconList($name);
        $response['iconList'] = $assets;
        $response['css'] = $css;
        $this->sendResponse($response);
    }
}