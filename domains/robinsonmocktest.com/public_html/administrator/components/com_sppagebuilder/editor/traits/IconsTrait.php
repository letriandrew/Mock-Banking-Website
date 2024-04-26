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
use Joomla\Archive\Archive;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Helper\MediaHelper;
use Joomla\CMS\Component\ComponentHelper;

JLoader::register('IconHelper', JPATH_ROOT . '/administrator/components/com_sppagebuilder/editor/helpers/IconHelper.php');

/**
 * Trait of Icons for upload, delete and all custom icons.
 * 
 * @version 4.1.0
 */
trait IconsTrait
{
    /**
     * Icon API endpoint for CRUD operations.
     * 
     * @return void
     * @version 4.1.0
     */
    public function icons()
    {
        $method = $this->getInputMethod();
        $status = $this->getInput('status', null, 'INT');
        $this->checkNotAllowedMethods(['PUT'], $method);

        switch ($method)
        {
            case 'GET':
                $this->getAllIcons($status);
                break;
            case 'POST':
                $this->uploadIcons();
                break;
            case 'PATCH':
                $this->changeCustomIconStatus();
                break;
            case 'DELETE':
                $this->deleteCustomIcon();
                break;
        }
    }

    /**
     * Get all the published custom icons from the database.
     * 
     * @return void
     * @version 4.1.0
     */
    private function getAllIcons($status)
    {
        $model = $this->getModel('Icon');

        $icons = $model->getAllIcons($status);

        $this->sendResponse($icons);
    }

    /**
     * Upload Custom Icons.
     *
     * @return void
     * @since 4.0.0
     */
    public function uploadIcons()
    {
        $model = $this->getModel('Icon');

        $customIcon = $this->getFilesInput('custom_icon', null);

        // Tmp path
        $tmp_path = Factory::getConfig()->get('tmp_path');

        // Root path
        $rootPath = JPATH_ROOT . '/media/com_sppagebuilder/assets/iconfont/';
        $rootUrl = 'media/com_sppagebuilder/assets/iconfont/';
        $report = array();

        if (empty($customIcon))
        {
            $report['status'] = false;
            $report['output'] = Text::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_UPLOAD_FAILED');
            $report['message'] = Text::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_UPLOAD_FAILED');
            $this->sendResponse($report, 400);
        }

        //check the root path
        if (!Folder::exists($rootPath))
        {
            Folder::create($rootPath);
        }

        if ($customIcon['error'] == UPLOAD_ERR_OK)
        {
            $error = false;
            $params = ComponentHelper::getParams('com_media');
            $contentLength = (int) $_SERVER['CONTENT_LENGTH'];
            $mediaHelper = new MediaHelper;
            $postMaxSize = $mediaHelper->toBytes(ini_get('post_max_size'));
            $memoryLimit = $mediaHelper->toBytes(ini_get('memory_limit'));

            // Check for the total size of post back data.
            if (($postMaxSize > 0 && $contentLength > $postMaxSize) || ($memoryLimit != -1 && $contentLength > $memoryLimit))
            {
                $report['status'] = false;
                $report['output'] = Text::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_MEDIA_TOTAL_SIZE_EXCEEDS');
                $report['message'] = Text::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_MEDIA_TOTAL_SIZE_EXCEEDS');

                $this->sendResponse($report, 400);
            }

            $uploadMaxSize = $params->get('upload_maxsize', 0) * 1024 * 1024;
            $uploadMaxFileSize = $mediaHelper->toBytes(ini_get('upload_max_filesize'));

            if (($customIcon['error'] == 1) || ($uploadMaxSize > 0 && $customIcon['size'] > $uploadMaxSize) || ($uploadMaxFileSize > 0 && $customIcon['size'] > $uploadMaxFileSize))
            {
                $report['status'] = false;
                $report['output'] = Text::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_MEDIA_LARGE');
                $report['message'] = Text::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_MEDIA_LARGE');
                $this->sendResponse($report, 400);
            }

            // get file name and file extension
            $name = File::stripExt($customIcon['name']);
            $report['name'] = $name;
            $tmp_src = $customIcon['tmp_name'];

            $valid_icon = false;

            // $extract_path = $tmp_path . '/builderCustomIcon';
            $zip_file = $tmp_path . '/builderCustomIcon.zip';

            // Delete existing file and folder
            if (File::exists($zip_file))
            {
                File::delete($zip_file);
            }

            // Move uploaded file into asset iconfont folder.
            if (File::upload($tmp_src, $zip_file, false, true))
            {
                $extract = $this->unpack($zip_file);

                if ($extract)
                {
                    $extract_path = $extract['dir'];

                    // check and delete the zip file.
                    if (File::exists($zip_file))
                    {
                        File::delete($zip_file);
                    }

                    // IcoFont
                    if (File::exists($extract_path . '/fonts/icofont.woff') && File::exists($extract_path . '/icofont.css') && File::exists($extract_path . '/icofont.min.css'))
                    {
                        if (Folder::exists($rootPath . '/icofont'))
                        {
                            Folder::delete($rootPath . '/icofont');
                        }

                        Folder::create($rootPath . '/icofont', 0755);

                        Folder::copy($extract_path . '/fonts', $rootPath . '/icofont/fonts');
                        File::copy($extract_path . '/icofont.css', $rootPath . '/icofont/icofont.css');
                        File::copy($extract_path . '/icofont.min.css', $rootPath . '/icofont/icofont.min.css');

                        $css = file_get_contents($rootPath . 'icofont/icofont.css');

                        $name = 'icofont';
                        $title = 'IcoFont';
                        $assets = IconHelper::getClassName($css, 'icofont');
                        $css_path = $rootUrl . 'icofont/icofont.min.css';

                        $valid_icon = true;
                    }
                    // custom font
                    elseif (File::exists($extract_path . '/config.json') || File::exists($extract_path . '/selection.json'))
                    {
                        if (File::exists($extract_path . '/selection.json'))
                        {
                            // Icomoon
                            $config = json_decode(file_get_contents($extract_path . '/selection.json'));
                            $fontFamily = isset($config->preferences->fontPref->metadata->fontFamily) ? $config->preferences->fontPref->metadata->fontFamily : '';
                            $prefix = isset($config->preferences->fontPref->prefix) ? $config->preferences->fontPref->prefix : '';
                            $font_path = 'fonts';
                            $font_css = 'style.css';
                        }
                        else
                        {
                            // Fontello
                            $config = json_decode(file_get_contents($extract_path . '/config.json'));
                            if (File::exists($extract_path . '/font/fontello.woff') && File::exists($extract_path . '/css/fontello.css'))
                            {
                                $fontFamily = 'fontello';
                                $prefix = isset($config->css_prefix_text) ? $config->css_prefix_text : 'icon-';
                                $font_path = 'font';
                                $font_css = 'css/fontello.css';
                            }
                            else
                            {
                                $fontFamily = isset($config->name) ? $config->name : '';
                                $prefix = isset($config->css_prefix_text) ? $config->css_prefix_text : '';
                                $font_path = 'font';
                                $font_css = 'css/' . $fontFamily . '.css';
                            }
                        }

                        if ($fontFamily && $prefix && Folder::exists($extract_path . '/' . $font_path) && File::exists($extract_path . '/' . $font_css))
                        {
                            if (Folder::exists($rootPath . '/' . $fontFamily))
                            {
                                Folder::delete($rootPath . '/' . $fontFamily);
                            }

                            Folder::create($rootPath . '/' . $fontFamily, 0755);
                            Folder::create($rootPath . '/' . $fontFamily . '/css', 0755); // fontello only

                            Folder::copy($extract_path . '/' . $font_path, $rootPath . '/' . $fontFamily . '/' . $font_path);
                            File::copy($extract_path . '/' . $font_css, $rootPath . '/' . $fontFamily . '/' . $font_css);

                            $css = file_get_contents($rootPath . $fontFamily . '/' . $font_css);

                            $name = $fontFamily;
                            $title = $fontFamily == 'icomoon' ? 'IcoMoon' : ucfirst($fontFamily);

                            if (File::exists($extract_path . '/selection.json'))
                            {
                                $assets = IconHelper::getClassName($css, rtrim($prefix, '-'), true);
                            }
                            else
                            {
                                $assets = IconHelper::getClassName($css, rtrim($prefix, '-'));
                            }

                            $css_path = $rootUrl . $fontFamily . '/' . $font_css;

                            $valid_icon = true;
                        }
                        else
                        {
                            $valid_icon = false;
                        }
                    }
                    else
                    {
                        $valid_icon = false;
                    }

                    // delete
                    if (Folder::exists($extract['extractdir']))
                    {
                        Folder::delete($extract['extractdir']);
                    }
                }
                else
                {
                    $valid_icon = false;
                }

                // valid upload
                if ($valid_icon)
                {
                    $assetData = [
                        'type' => 'iconfont',
                        'name' => $name,
                        'title' => $title,
                        'assets' => $assets,
                        'css_path' => $css_path,
                        'created' => Factory::getDate()->toSql(),
                        'created_by' => Factory::getUser()->id,
                        'published' => 1,
                        'access' => 1
                    ];

                    $newIcon = $model->insertAsset($assetData);

                    $report['data'] = $newIcon;
                    $report['status'] = true;
                    $report['output'] = Text::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_UPLOAD_DONE');
                    $this->sendResponse($report);
                }
                else
                {
                    $report['status'] = false;
                    $report['output'] = Text::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_FILE_NOT_SUPPORTED');
                    $report['message'] = Text::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_FILE_NOT_SUPPORTED');
                    $this->sendResponse($report, 400);
                }
            }
            else
            {
                $report['status'] = false;
                $report['output'] = Text::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_UPLOAD_FAILED');
                $report['message'] = Text::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_UPLOAD_FAILED');
                $this->sendResponse($report, 400);
            }
        }


        $this->sendResponse($report);
    }

    public static function unpack($packageFilename)
    {
        // Path to the archive
        $archivename = $packageFilename;

        // Temporary folder to extract the archive into
        $tmpdir = uniqid('builderCustomIcon_');

        // Clean the paths to use for archive extraction
        $extractdir = Path::clean(dirname($packageFilename) . '/' . $tmpdir);
        $archivename = Path::clean($archivename);

        // Do the unpacking of the archive
        try
        {
            $archive = new Archive(array('tmp_path' => Factory::getConfig()->get('tmp_path')));
            $extract = $archive->extract($archivename, $extractdir);
        }
        catch (\Exception $e)
        {
            return false;
        }

        if (!$extract)
        {
            return false;
        }

        /*
		 * Let's set the extraction directory and package file in the result array so we can
		 * cleanup everything properly later on.
		 */
        $retval['extractdir'] = $extractdir;
        $retval['packagefile'] = $archivename;

        /*
		 * Try to find the correct install directory.  In case the package is inside a
		 * subdirectory detect this and set the install directory to the correct path.
		 *
		 * List all the items in the installation directory.  If there is only one, and
		 * it is a folder, then we will set that folder to be the installation folder.
		 */
        $dirList = array_merge((array) Folder::files($extractdir, ''), (array) Folder::folders($extractdir, ''));

        if (count($dirList) === 1)
        {
            if (Folder::exists($extractdir . '/' . $dirList[0]))
            {
                $extractdir = Path::clean($extractdir . '/' . $dirList[0]);
            }
        }

        /*
		 * We have found the install directory so lets set it and then move on
		 * to detecting the extension type.
		 */
        $retval['dir'] = $extractdir;

        return $retval;
    }

    /**
     * Delete custom icon by id.
     *
     * @return  void
     * @since   4.0.0
     */
    public function deleteCustomIcon()
    {
        $model = $this->getModel('Icon');
        $id = $this->getInput('id', 0);

        $this->sendResponse($model->deleteCustomIcon($id));
    }

    /**
     * Change custom icon's status.
     *
     * @return  void
     * @since   4.0.0
     */
    public function changeCustomIconStatus()
    {
        $model = $this->getModel('Icon');
        $id = $this->getInput('id', 0, 'INT');
        $status = $this->getInput('status', null, 'INT');

        $this->sendResponse($model->changeCustomIconStatus($id, $status));
    }
}
