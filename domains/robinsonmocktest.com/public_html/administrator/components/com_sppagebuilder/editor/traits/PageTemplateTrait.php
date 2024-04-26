<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

use Joomla\CMS\Http\Http;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Trait for managing page template list
 */
trait PageTemplateTrait
{
    public function pageTemplateList()
    {
        $method = $this->getInputMethod();
		$this->checkNotAllowedMethods(['POST', 'DELETE','PATCH', 'PUT'], $method);

        switch ($method)
        {
            case 'GET':
                $this->getPageTemplateList();
                break;
        }
    }
    
    public function getPageTemplateList()
    {
        $cache_path = JPATH_CACHE . '/sppagebuilder';
        $cache_file = $cache_path . '/templates.json';
        $templates = array(); // All pre-defined templates list
        $templatesData = '';

        $response = new stdClass();

        $http = new Http;

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
                $response = 'Templates not found.';
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
                    'pages' => array_values($pages),
                    'layouts' => $templates
                ];

                $this->sendResponse($response);
            }
        }

        $response['message'] = 'No template found.';
        $this->sendResponse($response, 500);
    }
}
