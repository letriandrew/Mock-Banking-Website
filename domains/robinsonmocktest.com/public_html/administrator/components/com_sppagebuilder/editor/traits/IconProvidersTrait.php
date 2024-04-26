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
 * Trait of Icons providers.
 * 
 * @version 4.1.0
 */
trait IconProvidersTrait
{
    /**
     * Icon API endpoint for CRUD operations.
     * 
     * @return void
     * @version 4.1.0
     */
    public function iconProviders()
	{
		$method = $this->getInputMethod();
		$this->checkNotAllowedMethods(['PUT', 'PATCH', 'POST', 'DELETE'], $method);

        switch($method)
        {
            case 'GET':
                $this->getIconProviders();
                break;
        }

	}

    private function getIconProviders()
    {
        $model = $this->getModel('Icon');
        $this->sendResponse($model->getAssetProviders());
    }
}