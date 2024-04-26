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
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Component\ComponentHelper;

class SppagebuilderViewPages extends HtmlView
{
	public $filterForm;

	public $activeFilters = [];

	protected $items = [];

	protected $pagination;

	protected $state;

	protected $databaseIssue;

	public function display($tpl = null)
	{
		$this->items         	= $this->get('Items');
		$this->pagination    	= $this->get('Pagination');
		$this->state         	= $this->get('State');
		$this->filterForm    	= $this->get('FilterForm');
		$this->activeFilters 	= $this->get('ActiveFilters');
		$this->databaseIssue	= false;

		//Joomla Component Helper
		$this->params = ComponentHelper::getParams('com_sppagebuilder');

		if (count($errors = $this->get('Errors')))
		{
			$app = Factory::getApplication();
			$app->enqueueMessage(implode('<br />', $errors), 'error');
			$app->setHeader('status', 500, true);
		}

		$this->addToolbar();

		parent::display($tpl);
	}

	protected function addToolBar()
	{
		$state	= $this->get('State');
		$canDo	= ContentHelper::getActions('com_sppagebuilder');
		$user	= Factory::getUser();

		// Set the title
		ToolbarHelper::title(Text::_('COM_SPPAGEBUILDER') . ' - ' . Text::_('COM_SPPAGEBUILDER_PAGES'), 'none pbfont pbfont-pagebuilder');

		if (JVERSION < 4)
		{
			// new page button
			if ($canDo->get('core.create') || count($user->getAuthorisedCategories('com_sppagebuilder', 'core.create')) > 0)
			{
				ToolbarHelper::addNew('page.add');
			}

			// publish and unpublish button
			if ($canDo->get('core.edit.state'))
			{
				ToolbarHelper::publish('pages.publish', 'JTOOLBAR_PUBLISH', true);
				ToolbarHelper::unpublish('pages.unpublish', 'JTOOLBAR_UNPUBLISH', true);
				ToolbarHelper::checkin('pages.checkin');
			}

			// delete and trush button
			if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete'))
			{
				ToolbarHelper::deleteList('', 'pages.delete', 'JTOOLBAR_EMPTY_TRASH');
			}
			elseif ($canDo->get('core.edit.state') && $canDo->get('core.delete'))
			{
				ToolbarHelper::trash('pages.trash');
			}

			if ($user->authorise('core.admin', 'com_sppagebuilder') || $user->authorise('core.options', 'com_sppagebuilder'))
			{
				ToolbarHelper::preferences('com_sppagebuilder');
			}
		}
		else
		{
			$toolbar = Toolbar::getInstance('toolbar');

			// new page button
			if ($canDo->get('core.create') || count($user->getAuthorisedCategories('com_sppagebuilder', 'core.create')) > 0)
			{
				$toolbar->addNew('page.add');
			}

			if ($canDo->get('core.edit.state'))
			{
				$dropdown = $toolbar->dropdownButton('status-group')
					->text('JTOOLBAR_CHANGE_STATUS')
					->toggleSplit(false)
					->icon('fas fa-ellipsis-h')
					->buttonClass('btn btn-action')
					->listCheck(true);

				$childBar = $dropdown->getChildToolbar();

				$childBar->publish('pages.publish')->listCheck(true);
				$childBar->unpublish('pages.unpublish')->listCheck(true);
				$childBar->checkin('pages.checkin')->listCheck(true);
				$childBar->trash('pages.trash')->listCheck(true);
			}

			if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete'))
			{
				$toolbar->delete('pages.delete')
					->text('JTOOLBAR_EMPTY_TRASH')
					->message('JGLOBAL_CONFIRM_DELETE')
					->listCheck(true);
			}

			if ($user->authorise('core.admin', 'com_sppagebuilder') || $user->authorise('core.options', 'com_sppagebuilder'))
			{
				$toolbar->preferences('com_sppagebuilder');
			}
		}
	}
}
