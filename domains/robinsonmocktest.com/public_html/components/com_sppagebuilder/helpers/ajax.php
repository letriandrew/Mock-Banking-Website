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
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Http\Http;

$cParams = ComponentHelper::getParams('com_sppagebuilder');

$app = Factory::getApplication();
$input = $app->input;
$http = new Http;

// Load Page Template List
if ($action === 'pre-page-list')
{

	$cache_path = JPATH_CACHE . '/sppagebuilder';
	$cache_file = $cache_path . '/templates.json';

	$output = array('status' => false, 'data' => 'Templates not found.');
	$templates = array(); // All pre-defined templates list
	$templatesData = '';

	if (!Folder::exists($cache_path))
	{
		Folder::create($cache_path, 0755);
	}

	if (File::exists($cache_file) && (filemtime($cache_file) > (time()  - (24 * 60 * 60))))
	{
		$templatesData = file_get_contents($cache_file);
	}
	else
	{
		$templateApi = 'https://www.joomshaper.com/index.php?option=com_layouts&view=templates&layout=json&support=4beyond';
		$templatesResponse = $http->get($templateApi);
		$templatesData = $templatesResponse->body;

		if ($templatesResponse->code !== 200)
		{
			$output = ['status' => false, 'data' => $templatesData->error->message];
		}

		if (!empty($templatesData))
		{
			File::write($cache_file, $templatesData);
		}
	}

	if (!empty($templatesData))
	{
		$templates = json_decode($templatesData);
		$pages = [];

		foreach ($templates as $template)
		{
			if (!empty($template->templates))
			{
				foreach ($template->templates as $item)
				{
					if (!empty($item->layouts))
					{
						foreach ($item->layouts as $layout)
						{
							$key = strtolower($layout->title);
							$pages[$key] = (object) [
								'label' => $layout->title,
								'value' => $key
							];
						}
					}
				}
			}
		}

		if (!empty($templates))
		{
			$response = [
				'status' => true,
				'data' => [
					'pages' => array_values($pages),
					'layouts' => $templates
				]
			];

			echo json_encode($response);
			die();
		}
	}

	echo json_encode($output);
	die();
}

// Load Page Template List
if ($action === 'get-pre-page-data')
{
	$layout_id = $input->post->get('layout_id', '', 'NUMBER');
	$output = array('status' => false, 'data' => 'Page not found.');
	$args = '&email=' . $cParams->get('joomshaper_email') . '&api_key=' . $cParams->get('joomshaper_license_key');
	$pageApi = 'https://www.joomshaper.com/index.php?option=com_layouts&task=template.download&support=4beyond&id=' . $layout_id . $args;

	$pageResponse = $http->get($pageApi);
	$pageData = $pageResponse->body;

	if ($pageResponse->code !== 200)
	{
		$output = ['status' => false, 'data' => $pageData->error->message];
	}

	if (!empty($pageData))
	{
		$pageData = json_decode($pageData);

		if (isset($pageData->status) && $pageData->status)
		{
			$output['status'] = true;
			$output['data'] = $pageData->content;
			echo json_encode($output);
			die();
		}
		elseif (isset($pageData->authorised))
		{
			$output['status'] = false;
			$output['data'] = $pageData->authorised;
			echo json_encode($output);
			die();
		}
	}

	echo json_encode($output);
	die();
}

if ($action === 'pre-section-list')
{

	$cache_path = JPATH_CACHE . '/sppagebuilder';
	$cache_file = $cache_path . '/sections.json';

	$output = array('status' => false, 'data' => 'Sections not found.');
	$sections = array(); // All pre-defined templates list
	$sectionsData = '';

	if (!Folder::exists($cache_path))
	{
		Folder::create($cache_path, 0755);
	}

	if (File::exists($cache_file) && (filemtime($cache_file) > (time()  - (24 * 60 * 60))))
	{
		$sectionsData = file_get_contents($cache_file);
	}
	else
	{
		$args = '&email=' . $cParams->get('joomshaper_email') . '&api_key=' . $cParams->get('joomshaper_license_key');
		$sectionApi = 'https://www.joomshaper.com/index.php?option=com_layouts&task=block.list&support=4beyond' . $args;

		$sectionResponse = $http->get($sectionApi);
		$sectionsData = $sectionResponse->body;

		if ($sectionResponse->code !== 200)
		{
			$output = ['status' => false, 'data' => $sectionsData->error->message];
		}

		if (!empty($sectionsData))
		{
			File::write($cache_file, $sectionsData);
		}
	}

	if (!empty($sectionsData))
	{
		$sections = json_decode($sectionsData);

		/** Sanitize the blocks data before sending. */
		if (!empty($sections->blocks))
		{
			foreach ($sections->blocks as $i => &$groups)
			{
				if (!empty($groups->blocks))
				{
					foreach ($groups->blocks as $j => &$block)
					{
						if (!empty($block->json))
						{
							$content = json_decode($block->json);

							if (\is_object($content))
							{
								$content = json_encode([$content]);
							}
							elseif (\is_array($content))
							{
								$content = json_encode($content);
							}

							$json = SppagebuilderHelperSite::sanitize($content);
							// $parse = json_decode($json);

							// if (\is_array($parse) && !empty($parse))
							// {
							// 	$json = json_encode($parse[0]);
							// }

							$block->json = $json;
						}
					}

					unset($block);
				}
			}

			unset($groups);
		}

		if ((is_array($sections) && count($sections)) || is_object($sections))
		{
			$output['status'] = true;
			$output['data'] = $sections;
			echo json_encode($output);
			die();
		}
	}

	echo json_encode($output);
	die();
}

// Load page from uploaded page
if ($action === 'upload-page')
{
	if (isset($_FILES['page']) && $_FILES['page']['error'] === 0)
	{
		$file_name = $_FILES['page']['name'];
		$file_extension = substr($file_name, -5);
		$file_extension_lower = strtolower($file_extension);

		if ($file_extension_lower === '.json')
		{
			$content = file_get_contents($_FILES['page']['tmp_name']);

			$importingContent = (object)['template' => '', 'css' => ''];

			if (!empty($content))
			{
				$parsedContent = json_decode($content);

				if (!isset($parsedContent->template))
				{
					$importingContent->template = json_decode($content);
				}
				else
				{
					$importingContent = $parsedContent;
				}
			}

			if (!empty($importingContent))
			{
				require_once JPATH_COMPONENT_SITE . '/builder/classes/addon.php';
				$content = ApplicationHelper::sanitizePageText(json_encode($importingContent->template));
				$content = json_encode($content);

				/** Sanitize the old data with new data format. */
				$importingContent->template = SppagebuilderHelperSite::sanitizeImportJSON($content);

				echo json_encode(array('status' => true, 'data' => $importingContent));
				die;
			}
		}
	}

	echo json_encode(array('status' => false, 'data' => 'Something wrong there.'));
	die;
}
