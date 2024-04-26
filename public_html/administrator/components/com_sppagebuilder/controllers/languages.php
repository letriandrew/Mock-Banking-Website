<?php
/**
* @package SP Page Builder
* @author JoomShaper http://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2021 JoomShaper
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Installer\Installer;
use Joomla\CMS\Installer\InstallerHelper;
use Joomla\CMS\MVC\Controller\BaseController;

//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

class SppagebuilderControllerLanguages extends BaseController {

	public function install() {
		$report = array();
		$user = Factory::getUser();
		$input = Factory::getApplication()->input;
		$model = $this->getModel('languages');

		// Return if not authorised
		if (!$user->authorise('core.admin', 'com_sppagebuilder')) {
			$report['message'] = Text::_('JERROR_ALERTNOAUTHOR');
			$report['success'] = false;
			die(json_encode($report));
		}

		$language_api = 'http://sppagebuilder.com/api/languages/languages.json';

		if( ini_get('allow_url_fopen') ) {
			$ch_output = file_get_contents($language_api);
		} elseif(extension_loaded('curl')) {
			$ch_output = self::getCurlData($language_api);
		} else {
			$report['message'] = Text::_('Please enable \'cURL\' or url_fopen in PHP or Contact with your Server or Hosting administrator.');
			die(json_encode($report));
		}

		$languages = json_decode($ch_output);
		$component = $input->get('language', 'english', 'STRING');

		if(isset($languages->$component->downloads->source) && $languages->$component->downloads->source) {
			$url = $languages->$component->downloads->source;
			$language = $languages->$component;
		} else {
			$report['message'] = Text::_('Unsble to find the download language package');
			$report['success'] = false;
			die(json_encode($report));
		}
		//
		$p_file = InstallerHelper::downloadPackage($url);

		if (!$p_file) {
			$report['message'] = Text::_('COM_INSTALLER_MSG_INSTALL_INVALID_URL');
			$report['success'] = false;
			die(json_encode($report));
		}

		$config   = Factory::getConfig();
		$tmp_dest = $config->get('tmp_path');
		$package = InstallerHelper::unpack($tmp_dest . '/' . $p_file, true);
		$installer = Installer::getInstance();

		// Was the package unpacked?
		if (!$package || !$package['type']) {
			if (in_array($installType, array('upload', 'url'))) {
				InstallerHelper::cleanupInstall($package['packagefile'], $package['extractdir']);
			}

			$report['message'] = Text::_('COM_INSTALLER_UNABLE_TO_FIND_INSTALL_PACKAGE');
			$report['success'] = false;
			die(json_encode($report));
		}

		// Install the package.
		if (!$installer->install($package['dir'])) {
			// There was an error installing the package.
			$report['message'] = Text::sprintf('COM_INSTALLER_INSTALL_ERROR', Text::_('COM_INSTALLER_TYPE_TYPE_' . strtoupper($package['type'])));
			$report['success'] = false;
		} else {
			// Package installed sucessfully.
			$report['message'] = Text::sprintf('COM_INSTALLER_INSTALL_SUCCESS', Text::_('COM_INSTALLER_TYPE_TYPE_' . strtoupper($package['type'])));
			$report['success'] = true;
			$report['version'] = $model->storeInstall($language);
		}

		// Cleanup the install files.
		if (!is_file($package['packagefile'])) {
			$package['packagefile'] = $tmp_dest . '/' . $package['packagefile'];
		}

		InstallerHelper::cleanupInstall($package['packagefile'], $package['extractdir']);

		die(json_encode($report));
	}

	private static function getCurlData($url) {
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	    $data = curl_exec($ch);
	    curl_close($ch);
	    return $data;
	}

}
