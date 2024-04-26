<?php

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Http\Http;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Installer\Installer;
use Joomla\CMS\Installer\InstallerHelper;

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Trait for managing language pack API endpoint.
 */
trait LanguageTrait
{
    public static $languageListPath = JPATH_ROOT . '/administrator/components/com_sppagebuilder/assets/data/languages.json';

    public function language()
    {
        $method = $this->getInputMethod();
        $this->checkNotAllowedMethods(['POST', 'DELETE', 'PATCH'], $method);

        switch ($method)
        {
            case 'GET':
                $this->getLanguageList();
                break;
            case 'PUT':
                $this->installLanguage();
                break;
        }
    }

    private function getLanguageList()
    {
        $model = $this->getModel();

        $response = new stdClass();

        try
        {
            $languagesData = file_get_contents(self::$languageListPath);

            if (empty($languagesData))
            {
                $response->message = Text::_("COM_SPPAGEBUILDER_ERROR_MSG_NO_RESULT_FOUND");
                return $this->sendResponse($response, 404);
            }

            $languages = json_decode($languagesData);

            if (empty($languages))
            {
                $response->message = Text::_("COM_SPPAGEBUILDER_ERROR_MSG_NO_RESULT_FOUND");

                return $this->sendResponse($response, 404);
            }

            $results = new stdClass;

            foreach ($languages as $key => $item)
            {
                $item->thumbnail = Uri::root() . 'media/mod_languages/images/' . strtolower(str_ireplace('-', '_', $item->lang_tag)) . '.gif';
                $installed = $model->checkLanguageIsInstalled($item->lang_tag);
                $item->state = -1;
                $item->status = Text::_("COM_SPPAGEBUILDER_DASHBOARD_PAGES_LANGUAGE_STATUS_NOT_INSTALLED");
                $item->updatable = false;

                if (is_object($installed))
                {
                    $item->state = $installed->state;

                    if ((int) $item->state === 1)
                    {
                        $item->status = Text::_("COM_SPPAGEBUILDER_DASHBOARD_PAGES_LANGUAGE_STATUS_ACTIVATED");
                    }
                    else
                    {
                        $item->status = Text::_("COM_SPPAGEBUILDER_DASHBOARD_PAGES_LANGUAGE_STATUS_INSTALLED");;
                    }

                    if ($item->version > $installed->version)
                    {
                        $item->updatable = true;
                    }
                }

                $results->$key = $item;
            }

            return $this->sendResponse($results);
        }
        catch (Exception $e)
        {
            $response->message = $e->getMessage();

            return $this->sendResponse($response, 404);
        }
    }

    public function installLanguage()
    {
        $user = Factory::getUser();
        $model = $this->getModel();

        $lang = $this->getInput('languageCode', null, 'STRING');

        $response = new stdClass();

        if (empty($lang))
        {
            $response->message = Text::_("COM_SPPAGEBUILDER_ERROR_MSG_FOR_LANGUAGE_CODE");
            $this->sendResponse($response, 404);
        }

        $authorised = $user->authorise('core.admin', 'com_sppagebuilder') || $user->authorise('core.manage', 'com_sppagebuilder');

        if (!$authorised)
        {
            $response->message = Text::_('JERROR_ALERTNOAUTHOR');
            $this->sendResponse($response, 403);
        }

        $output = file_get_contents(self::$languageListPath);
        $languages = !empty($output) ? json_decode($output) : [];

        if (!empty($languages->$lang->downloads->source))
        {
            $downloadURL = $languages->$lang->downloads->source;
            $language = $languages->$lang;
        }
        else
        {
            $response->message = Text::_("COM_SPPAGEBUILDER_ERROR_MSG_FOR_UNABLED_DWON_LANGUAGE");
            $this->sendResponse($response, 404);
        }

        $packageFile = InstallerHelper::downloadPackage($downloadURL);

        if (empty($packageFile))
        {
            $response->message = Text::_('COM_INSTALLER_MSG_INSTALL_INVALID_URL');
            $this->sendResponse($response, 404);
        }

        $config = Factory::getConfig();
        $tmpPath = $config->get('tmp_path');
        $package = InstallerHelper::unpack($tmpPath . '/' . $packageFile, true);

        $installer = Installer::getInstance();

        if ($installer->install($package['dir']))
        {
            $language->state = 1;
            $language->status = 'Activated';
            $response->message = Text::_('COM_SPPAGEBUILDER_SUCCESS_MSG_FOR_LANGUAGE_INSTALL');
            $model->storeLanguage($language);
            $this->sendResponse($response, 200);
        }

        $response->message = Text::_('COM_SPPAGEBUILDER_ERROR_MSG_FOR_FAILED_LANGUAGE_INSTALL');
        $this->sendResponse($response, 500);
    }
}