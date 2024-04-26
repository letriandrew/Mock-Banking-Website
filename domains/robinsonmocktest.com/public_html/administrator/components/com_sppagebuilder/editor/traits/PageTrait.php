<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Trait for managing page list
 */
trait PageTrait
{
	public function pages()
	{
		$method = $this->getInputMethod();
		$this->checkNotAllowedMethods(['DELETE'], $method);

		switch ($method) {
			case 'GET':
				$this->getPageList();
				break;
			case 'PUT':
				$this->savePage();
				break;
			case 'PATCH':
				$this->applyBulkActions();
				break;
			case 'POST':
				$this->createPage();
				break;
		}
	}

	public function getPageList()
	{
		$pageData = (object) [
			'limit' => $this->getInput('limit', 10, 'INT'),
			'offset' => $this->getInput('offset', 0, 'INT'),
			'search' => $this->getInput('search', '', 'STRING'),
			'sortBy' => $this->getInput('sortBy', '', 'STRING'),
			'access' => $this->getInput('access', '', 'STRING'),
			'category' => $this->getInput('category', 0, 'INT'),
			'language' => $this->getInput('language', '', 'STRING'),
			'status' => $this->getInput('status', '', 'STRING'),
		];

		$model = $this->getModel('Editor');
		$response = $model->getPages($pageData);

		$this->sendResponse($response, $response->code);
	}

	public function savePage()
	{
		$model = $this->getModel('Editor');

		$id = $this->getInput('id', 0, 'INT');
		$title = $this->getInput('title', '', 'STRING');
		$text = $this->getInput('text', '[]', 'RAW');
		$published = $this->getInput('published', 0, 'INT');
		$language = $this->getInput('language', '*', 'STRING');
		$catid = $this->getInput('catid', 0, 'INT');
		$access = $this->getInput('access', 1, 'INT');
		$attributes = $this->getInput('attribs', '', 'STRING');
		$openGraphTitle = $this->getInput('og_title', '', 'STRING');
		$openGraphDescription = $this->getInput('og_description', '', 'STRING');
		$openGraphImage = $this->getInput('og_image', '', 'STRING');
		$customCss = $this->getInput('css', '', 'RAW');
		$version = SppagebuilderHelper::getVersion();

		$pageCreator = $model->getPageCreator($id);

		$user = Factory::getUser();
		$canEdit = $user->authorise('core.edit', 'com_sppagebuilder');
		$canEditOwn = $user->authorise('core.edit.own', 'com_sppagebuilder');
		$canEditState = $user->authorise('core.edit.state', 'com_sppagebuilder');

		$canEditPage = $canEdit || ($canEditOwn && $user->id === $pageCreator);

		if (!$canEditPage) {
			$this->sendResponse(['message' => Text::_('COM_SPPAGEBUILDER_EDITOR_INVALID_EDIT_ACCESS')], 403);
		}

		$content = !empty($text) ? $text : '[]';
		$content = json_encode(json_decode($content));

		$data = (object) [
			'id' => $id,
			'title' => $title,
			'content' => $content,
			'published' => $published,
			'language' => $language,
			'catid' => $catid,
			'access' => $access,
			'attribs' => $attributes,
			'og_title' => $openGraphTitle,
			'og_description' => $openGraphDescription,
			'og_image' => $openGraphImage,
			'css' => $customCss ?? '',
			'version' => $version,
			'modified' => Factory::getDate()->toSql(),
			'modified_by' => $user->id,
		];

		if (!$canEditState) {
			unset($data->published);
		}

		try
		{
			$model->savePage($data);
		}
		catch (\Exception $e)
		{
			$this->sendResponse(['message' => $e->getMessage()], 500);
		}

		$this->sendResponse(true);
	}

	public function applyBulkActions()
	{
		$params = (object) [
			'ids' => $this->getInput('ids', '', 'STRING'),
			'type' => $this->getInput('type', '', 'STRING'),
			'value' => $this->getInput('value', '', 'STRING')
		];

		$user = Factory::getUser();
		$canEditState = $user->authorise('core.edit.state', 'com_sppagebuilder');
		$canDelete = $user->authorise('core.delete', 'com_sppagebuilder');

		$stateTypes = ['published', 'unpublished', 'check-in', 'rename'];
		$deleteTypes = ['trash', 'delete'];

		if (in_array($params->type, $stateTypes) && !$canEditState) {
			$this->sendResponse(['message' => Text::_('COM_SPPAGEBUILDER_EDITOR_INVALID_EDIT_STATE_ACCESS')], 403);
		}

		if (in_array($params->type, $deleteTypes) && !$canDelete) {
			$this->sendResponse(['message' => Text::_('COM_SPPAGEBUILDER_EDITOR_INVALID_DELETE_STATE')], 403);
		}

		$model = $this->getModel('Editor');
		$response = $model->applyBulkActions($params);

		$this->sendResponse($response);
	}

	public function createPage()
	{
		$title = $this->getInput('title', '', 'STRING');

		$model = $this->getModel('Editor');
		$data = [];
		$user = Factory::getUser();
		$version = SppagebuilderHelper::getVersion();

		$user = Factory::getUser();
		$canCreate = $user->authorise('core.create', 'com_sppagebuilder');

		if (!$canCreate) {
			$this->sendResponse([
				'message' => Text::_('COM_SPPAGEBUILDER_EDITOR_INVALID_CREATE_ACCESS')
			], 403);
		}

		$data = (object) [
			'id' => 0,
			'title' => $title,
			'text' => '[]',
			'css' => '',
			'catid' => 0,
			'language' => '*',
			'access' => 1,
			'created_on' => Factory::getDate()->toSql(),
			'created_by' => $user->id,
			'modified' => Factory::getDate()->toSql(),
			'version' => $version,
		];

		$result = $model->createPage($data);

		if (!empty($result['message'])) {
			$this->sendResponse($result, 500);
		}

		$response = (object) [
			'id' => $result
		];

		$this->sendResponse($response, 201);
	}
}
