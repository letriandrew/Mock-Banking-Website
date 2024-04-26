<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

use Joomla\CMS\Http\Http;
use Joomla\CMS\Component\ComponentHelper;

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Layout Import Trait
 */
trait LayoutImportTrait
{
	public function import()
	{
		$method = $this->getInputMethod();
		$this->checkNotAllowedMethods(['PUT', 'DELETE', 'PATCH'], $method);

		switch ($method)
		{
			case 'GET':
				$this->uploadLayout();
				break;
		}
	}

	private function uploadLayout()
	{
		$http = new Http;
		$params = ComponentHelper::getParams('com_sppagebuilder');

		$id = $this->getInput('id', 0, 'INT');

		$email = $params->get('joomshaper_email');
		$apiKey = $params->get('joomshaper_license_key');

		if (empty($id))
		{
			$response['message'] = 'Invalid layout ID given!';
			$this->sendResponse($response, 500);
		}

		if (empty($email) || empty($apiKey))
		{
			$response['message'] = 'Invalid email and license key!';
			$this->sendResponse($response, 500);
		}

		$apiURL = 'https://www.joomshaper.com/index.php?option=com_layouts&task=template.download&support=4beyond&id=' . $id . '&email=' . $email . '&api_key=' . $apiKey;
		$pageResponse = $http->get($apiURL);
		$pageData = $pageResponse->body;

		if ($pageResponse->code !== 200)
		{
			$response['message'] = 'Something wrong there.';
			$this->sendResponse($response, 500);
		}

		if (!empty($pageData))
		{
			$pageData = json_decode($pageData);

			if (isset($pageData->status) && !$pageData->status && $pageData->authorised)
			{
				$this->sendResponse(['message' => $pageData->authorised], 403);
			}

			$pageDataContent = $pageData->content;
			$content = (object) ['template' => '', 'css' => ''];

			if (!isset($pageDataContent->template))
			{
				$content->template = json_encode($pageDataContent);
			}
			else
			{
				$pageDataContent->template = !\is_string($pageDataContent->template)
					? json_encode($pageDataContent->template)
					: $pageDataContent->template;

				$content = $pageDataContent;
			}

			require_once JPATH_COMPONENT_SITE . '/helpers/helper.php';
			$content->template = ApplicationHelper::sanitizePageText($content->template);

			$this->sendResponse(($content), 200);
		}
	}
}
