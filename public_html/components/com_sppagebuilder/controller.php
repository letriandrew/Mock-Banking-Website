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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\MVC\Controller\BaseController;

/**
 * SP Page Builder Base Controller class
 * 
 * @since 1.0.0
 */
class SppagebuilderController extends BaseController
{
	/**
	 * Display function
	 *
	 * @param  boolean $cachable
	 * @param  boolean $urlparams
	 * @return void
	 * @since 1.0.0
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$apps = Factory::getApplication();
		$viewStatus = false;

		$id    		= $this->input->getInt('id');
		$vName 		= $this->input->getCmd('view');

		$validViewNames = ['page', 'form', 'ajax', 'media'];
		$viewStatus = \in_array($vName, $validViewNames);

		if (!$viewStatus)
		{
			throw new Exception(Text::_('COM_SPPAGEBUILDER_ERROR_PAGE_NOT_FOUND'), 404);
		}

		$this->input->set('view', $vName);

		if ($vName == 'page')
		{
			$cachable = true;
		}

		$safeURLParams = array(
			'catid'  => 'INT',
			'id'     => 'INT',
			'cid'    => 'ARRAY',
			'return' => 'BASE64',
			'print'  => 'BOOLEAN',
			'lang'   => 'CMD',
			'Itemid' => 'INT'
		);


		$user = Factory::getUser();
		$isIgnoreView = ($this->input->getMethod() === 'POST' && (($vName === 'form' && ($this->input->get('layout') !== 'edit') || $this->input->get('layout') !== 'edit-iframe')));

		if ($user->get('id') || $isIgnoreView)
		{
			$cachable = false;
		}

		if ($vName === 'page')
		{
			$model = $this->getModel($vName);
			$model->hit();
		}

		parent::display($cachable, $safeURLParams);
	}

	/**
	 * Export template layout.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function export()
	{
		// check have access
		$user = Factory::getUser();
		$authorised = $user->authorise('core.edit', 'com_sppagebuilder');

		if (!$authorised)
		{
			die('Restricted Access');
		}

		$input  = Factory::getApplication()->input;
		$template = $input->get('template', '[]', 'RAW');
		$pageCss = $input->get('css', '', 'RAW');
		$filename = 'template' . rand(10000, 99999) . '.json';
		$filename = strlen($filename) <= PHP_MAXPATHLEN ? $filename : 'template' . SppagebuilderHelperSite::nanoid(6) . '.json';
		if ($template !== '[]')
		{
			$template  = json_decode($template);
			foreach ($template as &$row)
			{
				foreach ($row->columns as &$column)
				{
					foreach ($column->addons as &$addon)
					{
						if (isset($addon->type) && $addon->type === 'sp_row')
						{
							foreach ($addon->columns as &$column)
							{
								foreach ($column->addons as &$addon)
								{
									if (isset($addon->htmlContent))
									{
										unset($addon->htmlContent);
									}
									if (isset($addon->assets))
									{
										unset($addon->assets);
									}
								}
							}
						}
						else
						{
							if (isset($addon->htmlContent))
							{
								unset($addon->htmlContent);
							}
							if (isset($addon->assets))
							{
								unset($addon->assets);
							}
						}
					}
				}
			}
		}

		$content = (object) ['template' => $template, 'css' => $pageCss ?? ''];

		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header("Content-Disposition: attachment;filename=$filename");
		header("Content-Type: application/json");
		header("Content-Transfer-Encoding: binary ");

		echo json_encode($content);
		die();
	}

	/**
	 * AJAX function
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function ajax()
	{
		$app 	 = Factory::getApplication();
		$input   = $app->input;
		$format  = ($input->getWord('format')) ? strtolower($input->getWord('format')) : '';
		$results = null;
		$addon 	 = $input->get('addon', '', 'STRING');

		if ($addon)
		{
			$function = 'sp_' . $addon . '_get_ajax';
			$addon_class = 'SppagebuilderAddon' . ucfirst($addon);
			$method = $input->get('method', 'get', 'STRING');

			require_once JPATH_ROOT . '/components/com_sppagebuilder/parser/addon-parser.php';

			$core_path 		= JPATH_ROOT . '/components/com_sppagebuilder/addons/' . $input->get('addon') . '/site.php';
			$template_path 	= JPATH_ROOT . '/templates/' . SppagebuilderHelperSite::getTemplateName() . '/sppagebuilder/addons/' . $input->get('addon') . '/site.php';

			if (file_exists($template_path))
			{
				require_once $template_path;
			}
			else
			{
				require_once $core_path;
			}

			if (class_exists($addon_class))
			{

				if (method_exists($addon_class, $method . 'Ajax'))
				{
					try
					{
						$results = call_user_func($addon_class . '::' . $method . 'Ajax');
					}
					catch (Exception $e)
					{
						$results = $e;
					}
				}
				else
				{
					$results = new LogicException(Text::sprintf('COM_AJAX_METHOD_NOT_EXISTS', $method . 'Ajax'), 404);
				}
			}
			else
			{
				if (function_exists($function))
				{
					try
					{
						$results = call_user_func($function);
					}
					catch (Exception $e)
					{
						$results = $e;
					}
				}
				else
				{
					$results = new LogicException(Text::sprintf('Function %s does not exist', $function), 404);
				}
			}
		}

		echo new JsonResponse($results, null, false, $input->get('ignoreMessages', true, 'bool'));
		die;
	}
}
