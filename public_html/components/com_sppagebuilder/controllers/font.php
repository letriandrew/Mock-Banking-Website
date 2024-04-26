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
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Response\JsonResponse;

/**
 * Fonts Controller class
 *
 * @since 5.0.0
 */
class SppagebuilderControllerFont extends FormController
{
    /**
     * Get installed fonts.
     *
     * @return	array	The fonts array.
     * @since	5.0.0
     */
    public function getInstalledFonts()
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true);

        $query->select('*')
            ->from($db->quoteName('#__sppagebuilder_fonts'))
            ->where($db->quoteName('published') . ' = 1');

        $db->setQuery($query);

        try {
            $response = $db->loadObjectList();


            if (isset($response)) {
                foreach ($response as $key => $value) {
                    if (isset($value->data)) {
                        $value->data = json_decode($value->data);
                    }
                }
            }
        } catch (\Exception $e) {
            $response = [];
        }

        $this->sendResponse($response);
    }

    /**
     * Send JSON Response to the client.
     *
     * @param	array	$response	The response array or data.
     * @param	int		$statusCode	The status code of the HTTP response.
     *
     * @return	void
     * @since	5.0.0
     */
    private function sendResponse($response, int $statusCode = 200): void
    {
        $app = Factory::getApplication();
        $app->setHeader('status', $statusCode, true);
        $app->sendHeaders();
        echo new JsonResponse($response);
        $app->close();
    }
}
