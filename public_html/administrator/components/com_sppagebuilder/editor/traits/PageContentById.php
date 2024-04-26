<?php


/**
 * Sample trait for managing API endpoints.
 */
trait PageContentById
{
	public function pageContentById()
	{
		$method = $this->getInputMethod();
		$this->checkNotAllowedMethods(['POST', 'PUT', 'DELETE', 'PATCH'], $method);

		$this->getPageContentById();
	}

	public function getPageContentById()
	{
		$id = $this->getInput('id', null, 'INT');
		$model = $this->getModel('Editor');

		if (!$id)
		{
			$response['message'] = 'Missing Page ID';
			$this->sendResponse($response, 400);
		}

		$content = $model->getPageContent($id);

		if (empty($content))
		{
			$this->sendResponse(['message' => 'Requesting page not found!'], 404);
		}

		$content = ApplicationHelper::preparePageData($content);

		$content->url = SppagebuilderHelperRoute::getFormRoute($content->id, $content->language);
		unset($content->content);

		$this->sendResponse($content);
	}
}
