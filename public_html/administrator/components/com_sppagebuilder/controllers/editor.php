<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\Session\Session;

$traits = [
	'PageTrait.php',
	'PageDuplicateTrait.php',
	'IconsListTrait.php',
	'ParentItemsTrait.php',
	'IntegrationTrait.php',
	'SavedAddonsTrait.php',
	'SavedAddonsOrderTrait.php',
	'PageContentById.php',
	'PageTemplateTrait.php',
	'SavedSectionsTrait.php',
	'SavedSectionsOrderTrait.php',
	'MenuByPageIdTrait.php',
	'LayoutImportTrait.php',
	'AddonsTrait.php',
	'AppConfig.php',
	'SettingsTrait.php',
	'Media.php',
	'MediaFolderTrait.php',
	'IconsTrait.php',
	'IconProvidersTrait.php',
	'MenuListTrait.php',
	'LanguageTrait.php',
	'SectionLibraryTrait.php',
	'AddToMenuTrait.php',
	'PageOrderTrait.php',
	'ImportTrait.php',
	'ExportTrait.php',
	'ApplicationSettingsTrait.php',
	'GlobalColorsTrait.php',
	'PurgeCssTrait.php',
	'SaveIgTokenTrait.php',
	'FontsTrait.php',
	'UploadFontTrait.php',
	'AllFontsTrait.php'
];

foreach ($traits as $trait)
{
	$filePath = dirname(__FILE__) . '/../editor/traits/' . $trait;

	if (\file_exists($filePath))
	{
		require_once $filePath;
	}
}
class SppagebuilderControllerEditor extends AdminController
{
	use PageTrait;
	use PageDuplicateTrait;
	use IntegrationTrait;
	use ParentItemsTrait;
	use IconsListTrait;
	use PageContentById;
	use PageTemplateTrait;
	use SavedAddonsTrait;
	use SavedAddonsOrderTrait;
	use SavedSectionsTrait;
	use SavedSectionsOrderTrait;
	use MenuListTrait;
	use MenuByPageIdTrait;
	use LayoutImportTrait;
	use AddToMenuTrait;
	use LanguageTrait;
	use AddonsTrait;
	use AppConfig;
	use SettingsTrait;
	use Media;
	use MediaFolderTrait;
	use IconsTrait;
	use IconProvidersTrait;
	use SectionLibraryTrait;
	use PageOrderTrait;
	use ImportTrait;
	use ExportTrait;
	use ApplicationSettingsTrait;
	use GlobalColorsTrait;
	use PurgeCssTrait;
	use SaveIgTokenTrait;
	use FontsTrait;
	use UploadFontTrait;
	use AllFontsTrait;

	protected $app = null;

	public function __construct($config = [])
	{
		parent::__construct($config);

		$this->app = Factory::getApplication();

		$user = Factory::getUser();
		$authorised = $user->authorise('core.admin', 'com_sppagebuilder') || $user->authorise('core.manage', 'com_sppagebuilder');

		if (!$authorised)
		{
			$response['message'] = Text::_('COM_SPPAGEBUILDER_EDITOR_ADMIN_ACCESS_REQUIRED');

			$this->sendResponse($response, 403);
		}

		if (!$user->id)
		{
			$response['message'] = Text::_('COM_SPPAGEBUILDER_EDITOR_LOGIN_SESSION_EXPIRED');
			$this->sendResponse($response, 401);
		}

		if (!Session::checkToken())
		{
			$response['message'] = Text::_('COM_SPPAGEBUILDER_EDITOR_SESSION_MISMATCHED');
			$this->sendResponse($response, 403);
		}
	}

	public function getModel($name = 'Editor', $prefix = 'SppagebuilderModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
	}


	/**
	 * Send JSON Response to the client.
	 * {"success":true,"message":"ok","messages":null,"data":[{"key":"value"}]}
	 *
	 * @param	mixed	$response	The response array or data.
	 * @param	int		$statusCode	The status code of the HTTP response.
	 *
	 * @return	void
	 * @since	4.1.0
	 */
	private function sendResponse($response, int $statusCode = 200)
	{
		$this->app->setHeader('Content-Type', 'application/json');

		$this->app->setHeader('status', $statusCode, true);

		$this->app->sendHeaders();

		echo new JsonResponse($response);

		$this->app->close();
	}

	/**
	 * Check given HTTP method is allowed or not
	 *
	 * @param array $notAllowedMethods
	 * @param string $method
	 * @return void
	 */
	private function checkNotAllowedMethods(array $notAllowedMethods, string $method)
	{
		if (in_array($method, $notAllowedMethods))
		{
			$response['message'] = Text::_('COM_SPPAGEBUILDER_EDITOR_METHOD_NOT_ALLOWED');
			$this->sendResponse($response, 405);
		}
	}



	/**
	 * An abstraction of the $input->get() method.
	 * Here we are just checking the null, true, false values those are coming as string.
	 * If we found those values then return the respective values,
	 * otherwise return the original filtered value.
	 *
	 * @param 	string 	$name		The request field name.
	 * @param 	mixed 	$default	Any default value.
	 * @param 	string 	$filter		The filter similar to the ->get() method.
	 *
	 * @return 	mixed
	 */
	private function getInput(string $name, $default = null, string $filter = 'cmd')
	{
		$input = Factory::getApplication()->input;
		$value = $input->get($name);

		if (empty($value))
		{
			return $input->get($name, $default, $filter);
		}

		if (is_array($value))
		{
			return $input->get($name, $default, $filter);
		}

		switch (strtolower($value))
		{
			case 'null':
				return null;
			case 'true':
				return 1;
			case 'false':
				return 0;
		}

		return $input->get($name, $default, $filter);
	}

	private function getInputMethod()
	{
		$input = Factory::getApplication()->input;
		$method = $input->getString('_method', 'GET');

		return \strtoupper($method);
	}

	public function getFilesInput($name, $default = null, $filter = 'cmd')
	{
		$data = $_FILES;

		if (isset($data[$name]))
		{
			$results = $this->decodeData(
				[
					$data[$name]['name'],
					$data[$name]['type'],
					$data[$name]['tmp_name'],
					$data[$name]['error'],
					$data[$name]['size'],
				]
			);

			return $results;
		}

		return $default;
	}

	/**
	 * Method to decode a data array.
	 *
	 * @param   array  $data  The data array to decode.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	protected function decodeData(array $data)
	{
		$result = [];

		if (\is_array($data[0]))
		{
			foreach ($data[0] as $k => $_)
			{
				$result[$k] = $this->decodeData([$data[0][$k], $data[1][$k], $data[2][$k], $data[3][$k], $data[4][$k]]);
			}

			return $result;
		}

		return ['name' => $data[0], 'type' => $data[1], 'tmp_name' => $data[2], 'error' => $data[3], 'size' => $data[4]];
	}
}
