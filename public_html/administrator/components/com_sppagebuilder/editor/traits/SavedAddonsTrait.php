<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

use Joomla\CMS\Factory;

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Trait of saved addons providers.
 * 
 * @version 4.1.0
 */
trait SavedAddonsTrait
{
    /**
     * Icon API endpoint for saved addons.
     * 
     * @return void
     * @version 4.1.0
     */
    public function savedAddons()
    {
        $method = $this->getInputMethod();
        $this->checkNotAllowedMethods(['PUT', 'PATCH'], $method);

        switch ($method)
        {
            case 'GET':
                $this->getSavedAddons();
                break;
            case 'POST':
                $this->saveAddon();
                break;
            case 'DELETE':
                $this->deleteAddon();
                break;
        }
    }

    /**
     * Get all the saved addons form the database.
     * 
     * @return void
     */
    private function getSavedAddons()
    {
        try
        {
            $db = Factory::getDbo();
            $query = $db->getQuery(true);
            $query->select($db->quoteName(array('id', 'title', 'code', 'created', 'created_by')));
            $query->from($db->quoteName('#__sppagebuilder_addons'));
            $query->order($db->quoteName('ordering') . ' ASC');
            $db->setQuery($query);
            $results = $db->loadObjectList();

            if (!empty($results))
            {
                foreach ($results as &$result)
                {
                    $result->created = (new DateTime($result->created))->format('j F, Y');
                    $result->author = Factory::getUser($result->created_by)->get('name');
                    $result->code = SppagebuilderHelper::formatSavedAddon($result->code);

                    unset($result->created_by);
                }

                unset($result);
            }

            $this->sendResponse($results);
        }
        catch (\Exception $e)
        {
            $response['message'] = $e->getMessage();
            $this->sendResponse($response, 500);
        }
    }

    /**
     * Save Addon for future use.
     *
     * @return void
     */
    private function saveAddon()
    {
        $title = $this->getInput('title', '', 'STRING');
        $code = $this->getInput('code', '', 'RAW');

        if (empty($title) || empty($code))
        {
            $response['message'] = 'Information missing';
            $this->sendResponse($response, 404);
        }

        if (is_array($code))
        {
            $code = json_encode($code);
        }

        $data = new stdClass;
        $data->title = $title;
        $data->code = $code;
        $data->created = Factory::getDate()->toSql();
        $data->created_by = Factory::getUser()->id;

        try
        {
            $db = Factory::getDbo();
            $db->insertObject('#__sppagebuilder_addons', $data, 'id');

            $this->sendResponse($db->insertid(), 201);
        }
        catch (\Exception $e)
        {
            $response['message'] = $e->getMessage();
            $this->sendResponse($response, 500);
        }
    }

    /**
     * Delete saved addons form the database.
     * 
     * @return void
     */
    private function deleteAddon()
    {
        $id = $this->getInput('id', '', 'INT');

        if (empty($id))
        {
            $response['message'] = 'Information missing';
            $this->sendResponse($response, 404);
        }

        try
        {
            $db = Factory::getDbo();

            $query = $db->getQuery(true);
            $query->delete($db->quoteName('#__sppagebuilder_addons'));
            $query->where($db->quoteName('id') . ' = ' . $db->quote($id));

            $db->setQuery($query);
            $db->execute();

            $this->sendResponse('Addon deleted successfully!', 200);
        }
        catch (Exception $e)
        {
            $response['message'] = $e->getMessage();
            $this->sendResponse($response, 500);
        }
    }
}
