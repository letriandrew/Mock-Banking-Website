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
 * Media Folder Trait files for managing the folders operation.
 * 
 * @version 4.1.0
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Uri\Uri;
use Joomla\Filesystem\Path;

trait MediaFolderTrait
{
    /**
     * Media Folder endpoint for the API.
     * 
     * @return void
     * @version 4.1.0
     */
    public function folders()
    {
        $method = $this->getInputMethod();
        $this->checkNotAllowedMethods(['PUT'], $method);

        switch ($method)
        {
            case 'GET':
                $this->getAllFolders();
                break;
            case 'POST':
                $this->createMediaFolder();
                break;

            case 'DELETE':
                $this->deleteMediaFolders();
                break;
            case 'PATCH':
                $this->renameFolder();
                break;
        }
    }

    /**
     * Get all media files from the database.
     * 
     * @return void
     * @version 4.1.0
     */
    private function getAllFolders()
    {
        $model = $this->getModel('Media');
        $media = $model->getFolders();

        if (isset($media['status']) && !$media['status'])
        {
            $this->sendResponse(['message' => $media['message']], 500);
        }

        $report['breadcrumbs'] = $media['breadcrumbs'];
        $report['folders'] = $media['folders'];
        $report['folders_list'] = $media['folders_list'];

        $items = array();

        foreach ($media['items'] as $key => $item)
        {
            $item = str_replace('\\', '/', $item);
            $root_path = str_replace('\\', '/', JPATH_ROOT);
            $path = str_replace($root_path . '/', '', $item);

            $items[$key]['path'] = $path;
            $thumb = dirname($path) . '/_sp-pagebuilder_thumbs/' . basename($path);

            if (file_exists(JPATH_ROOT . '/' . $thumb))
            {
                $items[$key]['src'] = Uri::root(true) . '/' . $thumb;
            }
            else
            {
                $items[$key]['src'] = Uri::root(true) . '/' . $path;
            }

            $filename = basename($item);
            $title = File::stripExt($filename);
            $ext = File::getExt($filename);

            $items[$key]['id'] = 0;
            $items[$key]['title'] = $title;
            $items[$key]['ext'] = $ext;
            $items[$key]['type'] = ($ext == 'pdf') ? 'pdf' : 'image';
        }

        $report['items'] = $items;

        $this->sendResponse($report);
    }

    private function createMediaFolder()
    {
        $input = Factory::getApplication()->input;
        $folder = $input->get('folder', '', 'STRING');

        $user = Factory::getUser();
        $canCreate = $user->authorise('core.create', 'com_sppagebuilder');

        if (!$canCreate)
        {
            $this->sendResponse(['message' => Text::_('COM_SPPAGEBUILDER_NOT_AUTHORISED_TO_CREATE_FOLDER')], 403);
        }

        $dirname = dirname($folder);
        $basename = OutputFilter::stringURLSafe(basename($folder));
        $folder = $dirname . '/' . $basename;

        $report = array();
        $report['status'] = false;
        $fullName = JPATH_ROOT . $folder;

        try
        {
            $fullName = BuilderMediaHelper::checkForMediaActionBoundary($fullName);
        }
        catch (\Exception $e)
        {
            $response['message'] = $e->getMessage();
            $this->sendResponse($response, 403);
        }

        if (!SecurityHelper::isActionableFolder($folder))
        {
            $this->sendResponse(['message' => Text::_('COM_SPPAGEBUILDER_GLOBAL_UNAUTHORIZED_MEDIA_CREATION')], 403);
        }

        $folderToCreate = Path::clean(JPATH_ROOT . $folder);

        if (Folder::exists($folderToCreate))
        {
            $response['message'] = Text::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_FOLDER_EXISTS');
            $this->sendResponse($response, 400);
        }

        if (!Folder::create($folderToCreate, 0755))
        {
            $response['message'] = Text::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_FOLDER_CREATION_FAILED');
            $this->sendResponse($response, 500);
        }

        $folder_info['name'] = basename($folder);
        $folder_info['relname'] = $folder;
        $folder_info['fullname'] = $fullName;

        $report['status'] = true;
        $report['output'] = $folder_info;

        $this->sendResponse($report, 201);
    }

    private function deleteMediaFolders()
    {
        $input = Factory::getApplication()->input;
        $folder = $input->json->get('folder', '', 'STRING');
        $deleteItem = $input->json->get('deleteItem', '', 'STRING');
        $model = $this->getModel('Media');

        $user = Factory::getUser();
        $canDelete = $user->authorise('core.delete', 'com_sppagebuilder');

        if (!$canDelete)
        {
            $response['message'] = Text::_('COM_SPPAGEBUILDER_NOT_AUTHORISED_TO_DELETE_MEDIA');
            $this->sendResponse($response, 403);
        }

        $dirname = dirname($folder);
        $basename = OutputFilter::stringURLSafe(basename($folder));
        $folder = $dirname . '/' . $basename;
        $cleanedFullPath = Path::clean(JPATH_ROOT . $folder);
        $report = array();
        $report['status'] = false;

        if (!SecurityHelper::isActionableFolder($folder))
        {
            $this->sendResponse([
                'status' => false,
                'message' => Text::_('COM_SPPAGEBUILDER_GLOBAL_UNAUTHORIZED_FOLDER_DELETION')
            ], 403);
        }

        if (!Folder::exists($cleanedFullPath))
        {
            $response['message'] = Text::_("COM_SPPAGEBUILDER_MEDIA_MANAGER_FOLDER_EXISTS");
            $this->sendResponse($response, 500);
        }

        if ($deleteItem === 'multiple')
        {
            $mediaDelete = $model->removeMediaByPath(substr($folder, 1) . '/');
        }
        else
        {
            $mediaDelete = true;
        }

        if ($mediaDelete === true)
        {
            if (!Folder::delete($cleanedFullPath))
            {
                $response['message'] = Text::_("COM_SPPAGEBUILDER_MEDIA_MANAGER_FOLDER_DELETE_FAILED");
                $this->sendResponse($response, 500);
            }

            $folder_info['name'] = basename($folder);
            $folder_info['relname'] = $folder;

            $report['status'] = true;
            $report['output'] = $folder_info;

            $this->sendResponse($report, 200);
        }
        else
        {
            $response['message'] = Text::_("COM_SPPAGEBUILDER_MEDIA_MANAGER_FOLDER_DELETE_FAILED");
            $this->sendResponse($response, 500);
        }
    }

    private function renameFolder()
    {
        $user = Factory::getUser();
        $canEdit = $user->authorise('core.edit', 'com_sppagebuilder');

        if (!$canEdit)
        {
            $response['message'] = Text::_('COM_SPPAGEBUILDER_NOT_AUTHORISED_TO_RENAME_MEDIA');
            $this->sendResponse($response, 403);
        }

        $input  = Factory::getApplication()->input;
        $model  = $this->getModel('Media');
        $currentfolder = $input->post->get('currentfolder', '', 'STRING');
        $newfolder = $input->post->get('newfolder', '', 'STRING');
        $renameItem = $input->post->get('renameItem', '', 'STRING');
        $dirname = dirname($currentfolder);
        $currentbasename = OutputFilter::stringURLSafe(basename($currentfolder));
        $newbasename = OutputFilter::stringURLSafe(basename($newfolder));
        $src = $dirname . '/' . $currentbasename;
        $cleanedSrc = Path::clean(JPATH_ROOT . $src);
        $dest = $dirname . '/' . $newbasename;
        $cleanedDest = Path::clean(JPATH_ROOT . $dest);

        if (!SecurityHelper::isActionableFolder($currentfolder) || !SecurityHelper::isActionableFolder($newfolder))
        {
            $this->sendResponse([
                'status' => false,
                'output' => Text::_('COM_SPPAGEBUILDER_GLOBAL_UNAUTHORIZED_FOLDER_RENAME'),
            ], 403);
        }

        if (Folder::exists(Path::clean(JPATH_ROOT . $currentfolder)))
        {
            if ($renameItem === 'multiple')
            {
                $mediaRename = $model->editMediaPathById(substr($src, 1) . '/', substr($dest, 1) . '/');
            }
            else
            {
                $mediaRename = true;
            }

            if ($mediaRename === true)
            {
                if (Folder::move($cleanedSrc, $cleanedDest, $path = '', $use_streams = false))
                {
                    $report['status'] = true;
                    $folder_info['name'] = basename($dest);
                    $folder_info['relname'] = $dest;
                    $folder_info['fullname'] = JPATH_ROOT . $dest;
                    $report['output'] = $folder_info;
                }
                else
                {
                    $report['output'] = Text::_("COM_SPPAGEBUILDER_MEDIA_FOLDER_RENAME_FAILED");
                }
            }
            else
            {
                $report['output'] = $mediaRename;
                //'MEDIA FILES COULD NOT BE RENAMED';
            }
        }
        else
        {
            $report['output'] = Text::_("COM_SPPAGEBUILDER_MEDIA_FOLDER_NOT_FOUND");
        }

        $this->sendResponse($report);
    }
}
