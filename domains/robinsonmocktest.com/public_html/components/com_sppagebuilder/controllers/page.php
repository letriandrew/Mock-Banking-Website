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
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Http\Http;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\MVC\Controller\FormController;

class SppagebuilderControllerPage extends FormController
{

	public function __construct($config = array())
	{
		parent::__construct($config);

		if (!Session::checkToken())
		{
			$response = [
				'status' => false,
				'message' => Text::_('JLIB_ENVIRONMENT_SESSION_EXPIRED')
			];

			echo json_encode($response);
			die();
		}

		// check have access
		$user = Factory::getUser();
		$authorised = $user->authorise('core.edit', 'com_sppagebuilder');

		if (!$authorised)
		{
			$response = [
				'status' => false,
				'message' => Text::_('JERROR_ALERTNOAUTHOR')
			];

			echo json_encode($response);
			die();
		}
	}

	public function getModel($name = 'form', $prefix = '', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	/**
	 * Exit from the edit page.
	 *
	 * @return 	void
	 * @since 	4.0.0
	 */
	public function exitExitPage()
	{
		$app 		= Factory::getApplication('site');
		$input 		= $app->input;
		$user		= Factory::getUser();
		$model 		= $this->getModel();
		$pageId = $input->get('id', 0, 'INT');

		$root = Uri::base();
		$root = new Uri($root);

		$response = ['response' => Text::_("COM_SPPAGEBUILDER_ERROR_MSG"), 'status' => false, 'code' => 500];

		if (!$pageId)
		{
			$response['response'] = 'Invalid page ID!';
			$response['status'] = false;
			$response['code'] = 404;
		}

		$table = $model->getTable();

		try
		{
			if ($table->checkIn($pageId))
			{
				$response['response'] = Uri::base() . 'index.php?option=com_sppagebuilder&view=dashboard&tmpl=component';
				$response['status'] = true;
				$response['code'] = 200;
			}
			else
			{
				$response['response'] = 'Cannot checking out the user!';
				$response['status'] = false;
				$response['code'] = 500;
			}
		}
		catch (Exception $e)
		{
			$response['response'] = $e->getMessage();
			$response['status'] = false;
			$response['code'] = 500;
		}

		$statusCode = $response['code'];
		unset($response['code']);

		$app->setHeader('status', $statusCode, true);
		$app->sendHeaders();
		echo new JsonResponse($response);
		$app->close();
	}


	public function save($key = null, $urlVar = null)
	{

		$user = Factory::getUser();
		$app      = Factory::getApplication();
		$input = $app->input;
		$Itemid = $input->get('Itemid', 0, 'INT');

		$root = Uri::base();
		$root = new Uri($root);
		$link = $root->getScheme() . '://' . $root->getHost();

		$model    = $this->getModel('Form');
		$data     = $this->input->post->get('jform', array(), 'array');
		$task     = $this->getTask();
		$context  = 'com_sppagebuilder.edit.page';
		$recordId = $data['id'];
		$output = array();

		//Authorized
		if (empty($recordId))
		{
			$authorised = $user->authorise('core.create', 'com_sppagebuilder') || (count((array) $user->getAuthorisedCategories('com_sppagebuilder', 'core.create')));
		}
		else
		{
			$authorised = $user->authorise('core.edit', 'com_sppagebuilder') || $user->authorise('core.edit', 'com_sppagebuilder.page.' . $recordId) || ($user->authorise('core.edit.own', 'com_sppagebuilder.page.' . $recordId) && $data['created_by'] == $user->id);
		}

		if ($authorised !== true)
		{
			$output['status'] = false;
			$output['message'] = Text::_('JERROR_ALERTNOAUTHOR');
			echo json_encode($output);
			die();
		}

		// Check for request forgeries.
		$output['status'] = false;
		$output['message'] = Text::_('JINVALID_TOKEN');
		Session::checkToken() or die(json_encode($output));

		$output['status'] = true;

		// Check for validation errors.
		if ($data === false)
		{
			// Get the validation messages.
			$errors = $model->getErrors();

			$output['status'] = false;
			$output['message'] = '';

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count((array) $errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$output['message'] .= $errors[$i]->getMessage();
				}
				else
				{
					$output['message'] .= $errors[$i];
				}
			}

			// Save the data in the session.
			$app->setUserState('com_sppagebuilder.edit.page.data', $data);

			// Redirect back to the edit screen.
			$url = $link . 'index.php?option=com_sppagebuilder&view=form&layout=edit&tmpl=component&id=' . $recordId . '&Itemid=' . $Itemid;
			$output['redirect'] = $url;
			echo json_encode($output);
			die();
		}

		if ($itemOld = $model->getPageItem($recordId))
		{
			if ($itemOld->extension == 'com_content' && $itemOld->extension_view == 'article' && $itemOld->view_id)
			{
				$data['catid'] = $itemOld->catid;
			}
		}

		// Attempt to save the data.
		if (!$model->save($data))
		{
			// Save the data in the session.
			$app->setUserState('com_sppagebuilder.edit.page.data', $data);

			// Redirect back to the edit screen.
			$output['status'] = false;
			$output['message'] = Text::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError());
			$output['redirect'] = $link . 'index.php?option=com_sppagebuilder&view=form&layout=edit&tmpl=component&id=' . $recordId . '&Itemid=' . $Itemid;
			echo json_encode($output);
			die();
		}

		// Save succeeded, check-in the row.
		if ($model->checkin($data['id']) === false)
		{

			// Check-in failed, go back to the row and display a notice.
			$output['status'] = false;
			$output['message'] = Text::sprintf('JLIB_APPLICATION_ERROR_CHECKIN_FAILED', $model->getError());
			$output['redirect'] = $link . 'index.php?option=com_sppagebuilder&view=form&layout=edit&tmpl=component&id=' . $recordId . '&Itemid=' . $Itemid;
			echo json_encode($output);
			die();
		}

		$output['status'] = true;
		$output['message'] = Text::_('COM_SPPAGEBUILDER_PAGE_SAVE_SUCCESS');

		// Redirect the user and adjust session state based on the chosen task.
		switch ($task)
		{
			case 'apply':
				// Set the row data in the session.
				$this->holdEditId($context, $recordId);
				$app->setUserState('com_sppagebuilder.edit.page.data', null);

				// Convert json to readable article text
				$oldPage = $model->getItem($recordId);

				if ($oldPage->extension == 'com_content' && $oldPage->extension_view == 'article')
				{
					$model->addArticleFullText($oldPage->view_id, $oldPage->text);
				}

				// Delete generated CSS file
				$css_folder_path = JPATH_ROOT . '/media/com_sppagebuilder/css';
				$css_file_path = $css_folder_path . '/page-' . $recordId . '.css';
				if (file_exists($css_file_path))
				{
					JFile::delete($css_file_path);
				}

				// Redirect back to the edit screen.
				$output['redirect'] = $link . 'index.php?option=com_sppagebuilder&view=form&layout=edit&tmpl=component&id=' . $recordId . '&Itemid=' . $Itemid;
				$output['id'] = $recordId;
				break;

			default:
				// Clear the row id and data in the session.
				$this->releaseEditId($context, $recordId);
				$app->setUserState('com_sppagebuilder.edit.page.data', null);

				// Redirect to the list screen.
				$output['redirect'] = $link . 'index.php?option=' . $this->option . '&view=' . $this->view_list . $this->getRedirectToListAppend();
				break;
		}

		echo json_encode($output);
		die();
	}

	public function getMySections()
	{
		$model = $this->getModel();
		die($model->getMySections());
	}

	public function deleteSection()
	{
		$model = $this->getModel();
		$app = Factory::getApplication();
		$input = $app->input;

		$id = $input->get('id', '', 'INT');

		die($model->deleteSection($id));
	}

	public function saveSection()
	{
		$model = $this->getModel();
		$app = Factory::getApplication();
		$input = $app->input;

		$title = htmlspecialchars($input->get('title', '', 'STRING'));
		$section = $input->get('section', '', 'RAW');

		if ($title && $section)
		{
			$section_id = $model->saveSection($title, $section);
			echo $section_id;
			die();
		}
		else
		{
			die('Failed');
		}
	}

	public function getMyAddons()
	{
		$model = $this->getModel();
		die($model->getMyAddons());
	}

	/**
	 * Save Addon method.
	 *
	 * @return	void
	 * @since	1.0.0
	 */
	public function saveCode()
	{
		$input = Factory::getApplication()->input;
		$title = $input->json->get('title', '', 'STRING');
		$code = $input->json->get('code', [], 'ARRAY');
		$codeCategory = $input->json->get('category', '', 'STRING');

		$response = [
			'status' => false,
			'data' => 'Something went wrong! Please try again later.'
		];

		if (empty($title) || empty($code) || empty($codeCategory))
		{
			$response['data'] = 'Information missing.';
			echo json_encode($response);
			die();
		}

		if (is_array($code))
		{
			$code = json_encode($code);
		}

		$data = new stdClass;
		$data->title = $title;

		if ($codeCategory === 'section')
		{
			$data->section = $code;
			$table = '#__sppagebuilder_sections';
		}
		elseif ($codeCategory === 'addon')
		{
			$data->code = $code;
			$table = '#__sppagebuilder_addons';
		}

		$data->created = Factory::getDate()->toSql();
		$data->created_by = Factory::getUser()->id;

		try
		{
			$db = Factory::getDbo();
			$db->insertObject($table, $data, 'id');

			$response = [
				'status' => true,
				'data' => ucfirst($codeCategory) . ' saved successfully!'
			];

			echo json_encode($response);
			die();
		}
		catch (Exception $e)
		{
			$response = [
				'status' => false,
				'data' => $e->getMessage()
			];
			echo json_encode($response);
			die();
		}

		echo json_encode($response);
		die();
	}

	public function deleteAddon()
	{
		$model = $this->getModel();
		$app = Factory::getApplication();
		$input = $app->input;

		$id = $input->get('id', '', 'INT');

		die($model->deleteAddon($id));
	}

	public function myPages()
	{
		$model = $this->getModel('Page');
		$model->getMyPages();
		die();
	}

	public function cancel($key = 'id')
	{
		parent::cancel($key);
		$return_url = Factory::getApplication()->input->get('return_page', null, 'base64');

		$this->setRedirect(base64_decode($return_url));
	}

	public function addToMenu()
	{
		$output = array();

		$data = $this->input->post->get('jform', array(), 'array');
		$pageId = (int) $this->input->get->get('pageId', 0, 'INT');

		BaseDatabaseModel::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_menus/models');
		Table::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_menus/tables');
		$formModel = $this->getModel('Form');
		$model = $this->getModel('Item', 'MenusModel');

		//Check menu
		$menuId = (isset($data['menuid']) && $data['menuid']) ? $data['menuid'] : 0;
		$menutitle = (isset($data['menutitle']) && $data['menutitle']) ? $data['menutitle'] : '';
		$menualias = (isset($data['menualias']) && $data['menualias']) ? $data['menualias'] : OutputFilter::stringURLSafe($menutitle);
		$menutype = (isset($data['menutype']) && $data['menutype']) ? $data['menutype'] : '';
		$menuparent_id = (isset($data['menuparent_id']) && $data['menuparent_id']) ? $data['menuparent_id'] : 0;
		$menuordering = (isset($data['menuordering']) && $data['menuordering']) ? $data['menuordering'] : -2;
		$link = 'index.php?option=com_sppagebuilder&view=page&id=' . (int) $pageId;
		$component_id = ComponentHelper::getComponent('com_sppagebuilder')->id;

		$menu = $formModel->getMenuById($menuId);
		$home = (isset($menu->home) && $menu->home) ? $menu->home : 0;

		$menuData = array(
			'id' => (int) $menuId,
			'link' => $link,
			'parent_id' => (int) $menuparent_id,
			'menutype' => htmlspecialchars($menutype),
			'title' => htmlspecialchars($menutitle),
			'alias' => htmlspecialchars($menualias),
			'type' => 'component',
			'published' => 1,
			'language' => '*',
			'component_id' => $component_id,
			'menuordering' => (int) $menuordering,
			'home' => (int) $home
		);

		$message = ($menuId) ? 'Menu successfully updated' : 'Added to a new menu';

		if ($model->save($menuData))
		{
			$menu = $formModel->getMenuByAlias($menualias);
			$menuId = $menu->id;
			$output['status'] = true;
			$output['alias'] = $menualias;
			$output['menuid'] = $menuId;
			$output['success'] = $message;

			$Itemid = $formModel->getMenuByPageId($pageId);
			$menuItemId = 0;
			if (isset($Itemid->id) && $Itemid->id)
			{
				$menuItemId = '&Itemid=' . $Itemid->id;
			}
			$root = Uri::base();
			$root = new Uri($root);
			$output['redirect'] = Uri::base() . 'index.php?option=com_sppagebuilder&view=form&layout=edit&tmpl=component&id=' . $pageId . $menuItemId;
		}
		else
		{
			$output['status'] = false;
			$output['error'] = $model->getError();
		}

		die(json_encode($output));
	}

	public function getMenuParentItem()
	{

		BaseDatabaseModel::addIncludePath(JPATH_SITE . '/administrator/components/com_menus/models');
		$app = Factory::getApplication();

		$results  = array();
		$menutype = $this->input->get->get('menutype', '');
		$parent_id = $this->input->get->get('parent_id', 0);

		if ($menutype)
		{
			$model = $this->getModel('Items', 'MenusModel', array());
			$model->getState();
			$model->setState('filter.menutype', $menutype);
			$model->setState('list.select', 'a.id, a.title, a.level');
			$model->setState('list.start', '0');
			$model->setState('list.limit', '0');

			$results = $model->getItems();

			for ($i = 0, $n = count($results); $i < $n; $i++)
			{
				$results[$i]->title = str_repeat(' - ', $results[$i]->level) . $results[$i]->title;
			}
		}

		echo json_encode($results);

		$app->close();
	}

	public function createNewPage()
	{
		$output = array();
		$app = Factory::getApplication();

		$user = Factory::getUser();
		$authorised = $user->authorise('core.create', 'com_sppagebuilder');

		if (!$authorised)
		{
			$output['status'] = false;
			$output['message'] = Text::_('JERROR_ALERTNOAUTHOR');
			die(json_encode($output));
		}

		$title = trim(htmlspecialchars($this->input->post->get('title', '', 'STRING')));
		$model = $this->getModel('Form');
		$id = $model->createNewPage($title);

		$root = Uri::base();
		$root = new Uri($root);
		$redirect = Uri::base() . 'index.php?option=com_sppagebuilder&view=form&layout=edit&tmpl=component&id=' . $id;

		$output['status'] = true;
		$output['message'] = Text::_('Page created successfully.');
		$output['redirect'] = $redirect;
		die(json_encode($output));
	}

	public function deletePage()
	{
		$output = array();
		$app = Factory::getApplication();

		$user = Factory::getUser();
		$authorised = $user->authorise('core.delete', 'com_sppagebuilder');

		if (!$authorised)
		{
			$output['status'] = false;
			$output['message'] = Text::_('JERROR_ALERTNOAUTHOR');
			die(json_encode($output));
		}

		$pageid = (int) $this->input->post->get('pageid', '', 'INT');

		$model = $this->getModel('Form');
		$result = $model->deletePage($pageid);

		if (!$result)
		{
			$output['message'] = Text::_('Unable to delete this page.');
		}

		$output['status'] = $result;
		die(json_encode($output));
	}

	private function getFields($fieldset, $xml)
	{
		// Make sure there is a valid JForm XML document.
		if (!($xml instanceof \SimpleXMLElement))
		{
			return false;
		}

		/*
		 * Get an array of <field /> elements that are underneath a <fieldset /> element
		 * with the appropriate name attribute, and also any <field /> elements with
		 * the appropriate fieldset attribute. To allow repeatable elements only fields
		 * which are not descendants of other fields are selected.
		 */
		$fields = $xml->xpath('(//fieldset[@name="' . $fieldset . '"]//field | //field[@fieldset="' . $fieldset . '"])[not(ancestor::field)]');

		return (array) $fields;
	}

	/**
	 * Read the page.xml form and extract information from it for rendering
	 * into reactJS.
	 *
	 * @return	void
	 * @since	4.0.0
	 */
	public function getPageForm()
	{
		$model = $this->getModel();
		$input = Factory::getApplication()->input;
		$id = $input->getInt('id', 0);
		$model->setState('page.id', $id);

		$data = [];
		$form = $model->getForm();
		$formXml = $form->getXml();
		$groups = $form->getFieldsets();

		foreach ($groups as $name => $group)
		{
			$fields = $this->getFields($name, $formXml);

			foreach ($fields as $field)
			{
				if (version_compare(PHP_VERSION, '7.4.0', '>='))
				{
					$simpleXMLObj = get_mangled_object_vars($field->attributes());
					$fieldArray = (new ArrayIterator($simpleXMLObj))->current();
				}
				else
				{
					$fieldArray = current($field->attributes());
				}


				$fieldName = $fieldArray['name'];

				$fieldArray['label'] = isset($fieldArray['label']) ? Text::_($fieldArray['label']) : '';
				$fieldArray['desc'] = isset($fieldArray['desc']) ? Text::_($fieldArray['desc']) : '';

				$value = $form->getValue($fieldName);
				$fieldArray['value'] = $value;

				$formField = $form->getField($fieldName);
				$options = $formField->options;

				if (isset($options))
				{
					foreach ($options as &$option)
					{
						$option->text = Text::_($option->text);
					}

					unset($option);

					$fieldArray['options'] = $options;
				}

				$data[$name][] = $fieldArray;
			}
		}

		$response = ['status' => true, 'data' => $data];

		echo json_encode($response);
		die();
	}

	/**
	 * Save the page data.
	 *
	 * @return	void
	 * @since	4.0.0
	 */
	public function saveData()
	{
		$input = Factory::getApplication()->input;
		$id = $input->get('id', 0, 'INT');
		$data = $input->json->get('data', [], 'ARRAY');

		if($data['published'] === '')
		{
			$data['published'] = '0';
		}

		$model = $this->getModel();

		$response = $model->saveData($data, $id);

		$model->checkin($id);

		echo json_encode($response);
		die();
	}

	/**
	 * Get importing layout page information from JoomShaper site.
	 *
	 * @return	void
	 * @since	4.0.0
	 */
	public function importLayout()
	{
		$http = new Http;
		$input = Factory::getApplication()->input;
		$params = ComponentHelper::getParams('com_sppagebuilder');

		$id = $input->get('id', 0, 'INT');

		$email = $params->get('joomshaper_email');
		$apiKey = $params->get('joomshaper_license_key');

		$response = [
			'status' => false,
			'data' => 'Page not found!'
		];

		if (empty($id))
		{
			$response = [
				'status' => false,
				'data' => 'Invalid layout ID given!'
			];

			echo json_encode($response);
			die();
		}

		if (empty($email) || empty($apiKey))
		{
			$response = [
				'status' => false,
				'data' => 'Invalid email and license key!'
			];

			echo json_encode($response);
			die();
		}

		$apiURL = 'https://www.joomshaper.com/index.php?option=com_layouts&task=template.download&support=4beyond&id=' . $id . '&email=' . $email . '&api_key=' . $apiKey;
		$pageResponse = $http->get($apiURL);
		$pageData = $pageResponse->body;

		if ($pageResponse->code !== 200)
		{
			$response = ['status' => false, 'data' => $pageData->error->message];
		}

		if (!empty($templatesData))
		{
			File::write($cache_file, $templatesData);
		}

		if (!empty($pageData))
		{
			$pageData = json_decode($pageData);
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

			if (!empty($pageData->status))
			{
				require_once JPATH_COMPONENT_SITE . '/builder/classes/addon.php';
				$content->template = ApplicationHelper::sanitizePageText($content->template);
				$content->template = json_encode($content->template);
				$content->template = json_decode(SppagebuilderHelperSite::sanitizeImportJSON($content->template));

				$response = [
					'status' => true,
					'data' => $content
				];

				echo json_encode($response);
				die();
			}
			elseif ($pageData->authorised)
			{
				$response = [
					'status' => false,
					'data' => $pageData->authorised
				];

				echo json_encode($response);
				die();
			}
		}

		echo json_encode($response);
		die();
	}

	/**
	 * Save page information to the database.
	 *
	 * @return	void
	 * @since	4.0.0
	 */
	public function savePage()
	{
		$input = Factory::getApplication()->input;
		$id = $input->getInt('id', 0);
		$data = $input->json->get('data', [], 'ARRAY');

		$response = [
			'status' => false,
			'message' => 'Something went wrong! Page not saved.'
		];

		if (empty($id))
		{
			$response['message'] = 'Invalid page id given!';
			echo json_encode($response);
			die();
		}

		if (!empty($data))
		{
			foreach ($data as &$row)
			{
				foreach ($row['columns'] as &$column)
				{
					foreach ($column['addons'] as &$addon)
					{
						if (isset($addon['type']) && ($addon['type'] === 'sp_row' || $addon['type'] === 'inner_row'))
						{
							foreach ($addon['columns'] as &$innerColumn)
							{
								foreach ($innerColumn['addons'] as &$innerAddon)
								{
									if (isset($innerAddon['htmlContent']))
									{
										unset($innerAddon['htmlContent']);
									}

									if (isset($innerAddon['assets']))
									{
										unset($innerAddon['assets']);
									}
								}

								unset($innerAddon);
							}

							unset($innerColumn);
						}
						else
						{
							if (isset($addon['htmlContent']))
							{
								unset($addon['htmlContent']);
							}

							if (isset($addon['assets']))
							{
								unset($addon['assets']);
							}
						}
					}

					unset($addon);
				}

				unset($column);
			}

			unset($row);
		}

		$version = SppagebuilderHelper::getVersion();
		$pageData 			   = new stdClass;
		$pageData->id 		   = $id;
		$pageData->content 	   = json_encode($data);
		// $pageData->content 	   = EditorUtils::cleanXSS($data);
		$pageData->modified    = Factory::getDate()->toSql();
		$pageData->modified_by = Factory::getUser()->get('id');
		$pageData->version	   = $version;

		$model     = $this->getModel('Form');
		$pageModel = $this->getModel();
		$oldPage   = $model->getItem($id);
		$oldPage = ApplicationHelper::preparePageData($oldPage);

		if ($oldPage->extension == 'com_content' && $oldPage->extension_view == 'article')
		{
			$model->addArticleFullText($oldPage->view_id, json_encode($oldPage->text));
		}

		try
		{
			$db = Factory::getDbo();
			$db->updateObject('#__sppagebuilder', $pageData, 'id', true);

			$pageModel->checkin($id);

			$response = [
				'status' => true,
				'message' => 'Page data saved successfully!'
			];

			echo json_encode($response);
			die();
		}
		catch (Exception $e)
		{
			$response = [
				'status' => false,
				'message' => $e->getMessage()
			];

			echo json_encode($response);
			die();
		}

		echo json_encode($response);
		die();
	}

	public function loadPagesList()
	{
		$result = [];

		$db 	= Factory::getDbo();
		$query 	= $db->getQuery(true);
		$query->select('id AS value, title AS label')
			->from($db->quoteName('#__sppagebuilder'))
			->where($db->quoteName('extension_view') . '=' . $db->quote('page'))
			->where($db->quoteName('published') . ' = 1');
		$query->order($db->quoteName('ordering') . ' ASC');
		$db->setQuery($query);

		try
		{
			$result = $db->loadObjectList();
		}
		catch (Exception $e)
		{
			echo json_encode([]);
			die;
		}

		echo \json_encode($result);
		die;
	}

	public function loadSiteMenus()
	{
		$result = [];

		$db 	= Factory::getDbo();
		$query 	= $db->getQuery(true);
		$query->select('id, title, link, menutype')
			->from($db->quoteName('#__menu'))
			->where($db->quoteName('id') . ' > 1')
			->where($db->quoteName('published') . ' = 1')
			->where($db->quoteName('client_id') . ' = 0');
		$query->order($db->quoteName('lft') . ' ASC');
		$db->setQuery($query);

		try
		{
			$result = $db->loadObjectList();
		}
		catch (Exception $e)
		{
			echo json_encode([]);
			die;
		}

		if (!empty($result))
		{
			$result = array_map(function ($item)
			{
				$obj = new \stdClass;
				$obj->value = $item->link . '&Itemid=' . $item->id;
				$obj->label = Text::_($item->title);

				return $obj;
			}, $result);
		}

		echo \json_encode($result);
		die;
	}
}
