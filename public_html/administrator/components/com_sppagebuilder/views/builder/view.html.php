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
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

class SppagebuilderViewBuilder extends HtmlView
{

	public function display($tpl = null)
	{
		$app  = Factory::getApplication();
		$user = Factory::getUser();
		
		if (!$user->id)
		{
			$uri = Uri::getInstance();
			$pageURL = $uri->toString();
			$return_url = base64_encode($pageURL);
			$joomlaLoginUrl = 'index.php?option=com_users&view=login&return=' . $return_url;

			$app->redirect(Route::_($joomlaLoginUrl, false), Text::_('JERROR_ALERTNOAUTHOR'), 'message');
			return false;
		}

		$isAuthorised = $user->authorise('core.admin', 'com_sppagebuilder') || $user->authorise('core.manage', 'com_sppagebuilder') || $user->authorise('core.edit', 'com_sppagebuilder') || $user->authorise('core.edit.own', 'com_sppagebuilder');

		if (!$isAuthorised)
		{
			$app->enqueueMessage(Text::_('COM_SPPAGEBUILDER_ERROR_EDIT_PERMISSION'), 'error');
			return;
		}

		$this->addToolBar();

		parent::display($tpl);
	}

	protected function addToolBar()
	{
		ToolbarHelper::title(Text::_('COM_SPPAGEBUILDER'), 'pagebuilder');
	}
}
