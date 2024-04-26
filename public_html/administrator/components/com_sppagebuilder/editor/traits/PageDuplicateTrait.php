<?php


/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Defines the trait for a Editor Controller Class.
 *
 * @since 4.1.0
 */
trait PageDuplicateTrait
{
    /**
     * Method to duplicate page item into the table.
     *
     * @param   integer     $id         Key to the sppagebuilder table.
     * @param   Table       $table      Content table object being loaded.
     *
     * @return  mixed       return the response.
     *
     * @since   4.1.0
     */
    public function duplicate()
    {
        $method = $this->getInputMethod();
        $this->checkNotAllowedMethods(['POST', 'PUT', 'DELETE', 'PATCH'], $method);

        $this->duplicatePage();
    }

    private function duplicatePage()
    {
        $id = $this->getInput('id', 0, 'INT');
        $model = $this->getModel('Editor');

        if (!$id)
        {
            $response['message'] = Text::_("COM_SPPAGEBUILDER_PAGE_ID_MISSING");
            $this->sendResponse($response, 400);
        }

        $data = $model->duplicatePage($id);

        $this->sendResponse($data->response, $data->code);
    }
}
