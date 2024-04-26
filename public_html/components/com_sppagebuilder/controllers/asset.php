<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\Archive\Archive;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Helper\MediaHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\Uri\Uri;

require_once JPATH_ROOT . '/components/com_sppagebuilder/helpers/assets-css-parser.php';
/**
 * Asset Controller class
 *
 * @since 4.0.0
 */
class SppagebuilderControllerAsset extends FormController
{
    /**
	 * Load custom icons.
	 *
	 * @return	void
	 * @since	4.0.0
	 */
	public function loadCustomIcons()
	{
		$app 		= Factory::getApplication('site');
		$input 		= $app->input;

        $model = $this->getModel();
        $response  = [
            'status' => true,
            'data' => $model->loadCustomIcons()
        ];

        $this->sendResponse($response, 200);
	}

    /**
     * Delete custom icon by id.
     *
     * @return  void
     * @since   4.0.0
     */
    public function deleteCustomIcon()
    {
        $app = Factory::getApplication();
        $input = $app->input;
        $model = $this->getModel();

        $id = $input->getInt('id', 0);

        $response = [
            'status' => true,
            'data' => $model->deleteCustomIcon($id)
        ];

        $this->sendResponse($response, 200);
    }

    /**
     * Change custom icon's status.
     *
     * @return  void
     * @since   4.0.0
     */
    public function changeCustomIconStatus()
    {
        $app = Factory::getApplication();
        $input = $app->input;
        $model = $this->getModel();

        $id = $input->getInt('id', 0);
        $status = $input->getInt('status', null);

        $response = [
            'status' => true,
            'data' => $model->changeCustomIconStatus($id, $status)
        ];

        $this->sendResponse($response, 200);
    }

    /**
     * Upload Custom Icons.
     *
     * @return void
     * @since 4.0.0
     */
    public function uploadCustomIcon()
    {

        $model = $this->getModel();
        // Get file form input
        $input = Factory::getApplication()->input;
        $user = Factory::getUser();

        $customIcon = $input->files->get('custom_icon', null);

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
            echo json_encode($report);
            die();
        }

        $fileDetails = \pathinfo($customIcon['name']);

         //check the root path
        if (!Folder::exists($rootPath)) {
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
                $error = true;
                echo json_encode($report);
                die;
            }
            
            $uploadMaxSize = $params->get('upload_maxsize', 0) * 1024 * 1024;
            $uploadMaxFileSize = $mediaHelper->toBytes(ini_get('upload_max_filesize'));
            
            if (($customIcon['error'] == 1) || ($uploadMaxSize > 0 && $customIcon['size'] > $uploadMaxSize) || ($uploadMaxFileSize > 0 && $customIcon['size'] > $uploadMaxFileSize))
            {
                $report['status'] = false;
                $report['output'] = Text::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_MEDIA_LARGE');
                $error = true;
            }

            if (!$error)
            {
                // get file name and file extension
                $package_name = File::stripExt($customIcon['name']);
                $name = File::stripExt($customIcon['name']);
                $report['name'] = $name;
                $ext = File::getExt($customIcon['name']);
                $tmp_dest = $rootPath . $customIcon['name'];
                $tmp_src = $customIcon['tmp_name'];

                $valid_icon = false;

                // $extract_path = $tmp_path . '/builderCustomIcon';
				$zip_file = $tmp_path . '/builderCustomIcon.zip';

                // Delete existing file and folder
				if (File::exists($zip_file))
				{
					File::delete($zip_file);
				}

				// if (Folder::exists($extract_path))
				// {
				// 	Folder::delete($extract_path);
				// }

                // Move uploaded file into asset iconfont folder.
                if( File::upload($tmp_src, $zip_file, false, true))
                {
                    $extract = $this->unpack($zip_file);

                    if($extract)
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
                            $assets = SppbAssetCssParser::getClassName($css, 'icofont');
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
                                File::copy($extract_path . '/'. $font_css, $rootPath . '/' . $fontFamily . '/' . $font_css);

                                $css = file_get_contents($rootPath . $fontFamily . '/' . $font_css);

                                $name = $fontFamily;
                                $title = $fontFamily == 'icomoon' ? 'IcoMoon' : ucfirst($fontFamily);
                                if (File::exists($extract_path . '/selection.json'))
                                {
                                    $assets = SppbAssetCssParser::getClassName($css, rtrim($prefix, '-'), true); 
                                }
                                else
                                {
                                    $assets = SppbAssetCssParser::getClassName($css, rtrim($prefix, '-'));
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
                        if(Folder::exists($extract['extractdir']))
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
                    }
                    else
                    {
                        $report['status'] = false;
                        $report['output'] = Text::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_FILE_NOT_SUPPORTED');
                    }
                }
                else
                {
                    $report['status'] = false;
                    $report['output'] = Text::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_UPLOAD_FAILED');
                }
            }
        }

        echo json_encode($report);
        die();
    }

    public function getIconProviders()
    {
        $model = $this->getModel();
        $report = $model->getAssetProviders();
        echo json_encode($report);
        die();
    }

    public function loadIcons()
    {
        $input 	= Factory::getApplication()->input;
        $name 	= $input->json->get('name', NULL, 'STRING');
        $title  = $input->json->get('title', NULL, 'STRING');

        $rootPath = Uri::base(true) . '/media/com_sppagebuilder/assets/iconfont/';
        $report = array();

        $css = $rootPath . $name . '/' . $name . '.css';

        $model = $this->getModel();
        $assets = $model->getIconList($name);
        $report['iconList'] = $assets;
        $report['css'] = $css;

        echo json_encode($report);
        die();

    }

    /**
     * Unpacks a file and verifies it as a icofont package
     * Supports .gz .tar .tar.gz and .zip
     *
     * @param   string   $fontPackage    The uploaded icon font package file
     * @param     string     $name    File name.
     * @return  boolean  boolean false on failure
     *
     * @since   4.0.0
     */
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
	 * Send JSON Response to the client.
	 *
	 * @param	array	$response	The response array or data.
	 * @param	int		$statusCode	The status code of the HTTP response.
	 *
	 * @return	void
	 * @since	4.0.0
	 */
	private function sendResponse($response, int $statusCode = 200) : void
	{
		$app = Factory::getApplication();
		$app->setHeader('status', $statusCode, true);
		$app->sendHeaders();
		echo new JsonResponse($response);
		$app->close();
	}
}
