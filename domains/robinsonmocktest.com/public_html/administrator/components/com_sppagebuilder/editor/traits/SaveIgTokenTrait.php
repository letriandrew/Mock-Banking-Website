<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */


use Joomla\CMS\Filesystem\Folder;

// No direct access
defined('_JEXEC') or die('Restricted access');


use Joomla\CMS\Table\Table;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Language\Text;

/**
 * This trait is for Save IG Token
 *
 * @return    void
 * @since    4.1.0
 */
trait SaveIgTokenTrait
{
    public function saveIgToken()
    {
        $method = $this->getInputMethod();
        $this->checkNotAllowedMethods(['GET', 'PUT', 'DELETE', 'PATCH'], $method);

        switch($method)
        {
            case 'POST':
                $this->saveToken();
                break;
        }   
    }

    private function saveToken()
    {
        $igToken = $this->getInput('ig_token', [], 'ARRAY');

        $params = ComponentHelper::getParams('com_sppagebuilder');
        $componentId = ComponentHelper::getComponent('com_sppagebuilder')->id;

        $_token = json_decode($params->get('ig_token'), true);
        $token = array_merge($_token, $igToken);

        $params->set('ig_token', json_encode($token));

        $table = Table::getInstance('extension');

        if (!$table->load($componentId)) {
            $response = ['status' => false, 'message' => Text::_("COM_SPPAGEBUILDER_ERROR_MSG_FOR_FAILED_LOAD_EXTENSION")];
            $this->sendResponse($response, 500);
        }

        $table->params = json_encode($params);

        if (!$table->store()) {
            $response = ['status' => false, 'message' => Text::_("COM_SPPAGEBUILDER_ERROR_MSG_FOR_FAILED_STORE_EXTENSION")];
            $this->sendResponse($response, 500);
        }

        $response = ['status' => true, 'message' => Text::_("COM_SPPAGEBUILDER_SUCCESS_MSG_FOR_SETTINGS")];

        $this->sendResponse($response, 200);
    }
}
