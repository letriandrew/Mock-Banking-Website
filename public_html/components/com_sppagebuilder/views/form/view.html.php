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
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Uri\Uri;

class SppagebuilderViewForm extends HtmlView
{
	protected $form;
	protected $item;

	function display($tpl = null)
	{
		$user = Factory::getUser();
		$app  = Factory::getApplication();

		$this->item = $this->get('Item');
		$this->form = $this->get('Form');

		$this->item = ApplicationHelper::preparePageData($this->item);

		if (!$user->id)
		{
			$uri = Uri::getInstance();
			$pageURL = $uri->toString();
			$return_url = base64_encode($pageURL);
			$joomlaLoginUrl = 'index.php?option=com_users&view=login&return=' . $return_url;
			$app->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'notice');
			$app->redirect(Uri::base() . $joomlaLoginUrl, 403);
		}

		$input = $app->input;
		$pageid = $input->get('id', '', 'INT');
		$item_info  = SppagebuilderModelPage::getPageInfoById($pageid);
		$authorised = $user->authorise('core.edit', 'com_sppagebuilder.page.' . $pageid) || ($user->authorise('core.edit.own',   'com_sppagebuilder.page.' . $pageid) && $item_info->created_by == $user->id);

		// checkout
		if (!($this->item->checked_out == 0 || $this->item->checked_out == $user->id))
		{
			$app->enqueueMessage(Text::_('COM_SPPAGEBUILDER_ERROR_CHECKED_IN'), 'warning');
			$app->redirect($this->item->link, 403);
			return false;
		}

		if ($authorised !== true)
		{
			$app->enqueueMessage(Text::_('COM_SPPAGEBUILDER_ERROR_EDIT_PERMISSION'), 'warning');
			$app->redirect($this->item->link, 403);
			return false;
		}

		// Check for errors.
		if (count($errors = (array) $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
		}


		$this->_prepareDocument($this->item->title);
		SppagebuilderHelperSite::loadLanguage();
		parent::display($tpl);
	}

	/**
	 * Prepare Page Title and Site Name
	 *
	 * @param string $title
	 * @return void
	 */
	protected function _prepareDocument($title = '')
	{
		$config 	= Factory::getConfig();
		$app 		= Factory::getApplication();
		$doc 		= Factory::getDocument();
		$menus   	= $app->getMenu();
		$menu 		= $menus->getActive();

		if (isset($menu))
		{
			if ($menu->getParams()->get('page_title', ''))
			{
				$title = $menu->getParams()->get('page_title');
			}
			else
			{
				$title = $menu->title;
			}
		}

		//Include Site title
		$sitetitle = $title;
		if ($config->get('sitename_pagetitles') == 2)
		{
			$sitetitle = Text::sprintf('JPAGETITLE', $sitetitle, $app->get('sitename'));
		}
		elseif ($config->get('sitename_pagetitles') == 1)
		{
			$sitetitle = Text::sprintf('JPAGETITLE', $app->get('sitename'), $sitetitle);
		}

		$doc->setTitle($sitetitle);
	}
}
