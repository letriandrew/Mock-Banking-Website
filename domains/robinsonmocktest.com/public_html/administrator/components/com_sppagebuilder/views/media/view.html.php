<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2022 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('Restricted access');

require_once JPATH_ROOT . '/administrator/components/com_sppagebuilder/helpers/language.php';


use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Component\ComponentHelper;

class SppagebuilderViewMedia extends HtmlView
{
	public function display( $tpl = null )
	{
		$user = Factory::getUser();
		$app  = Factory::getApplication();

		$authorised = $user->authorise('core.edit', 'com_sppagebuilder') || $user->authorise('core.edit.own', 'com_sppagebuilder');
		
		if ($authorised !== true)
		{
			$app->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'error');
			$app->setHeader('status', 403, true);

			return false;
		}

		$input = Factory::getApplication()->input;
		$layout = $input->get('layout', 'browse', 'STRING');
		$this->date = $input->post->get('date', NULL, 'STRING');
		$this->start = $input->post->get('start', 0, 'INT');
		$this->search = $input->post->get('search', NULL, 'STRING');
		$this->limit = 18;

		$model = $this->getModel();
		$this->items = $model->getItems();
		$this->filters = $model->getDateFilters($this->date, $this->search);
		$this->total = $model->getTotalMedia($this->date, $this->search);
		$this->categories = $model->getMediaCategories();

		ToolbarHelper::title(Text::_('SP Page Builder - Media'));

		$mediaParams = ComponentHelper::getParams('com_media');
		Factory::getDocument()->addScriptdeclaration('var sppbMediaPath=\'/'. $mediaParams->get('file_path', 'images') .'\';');

		$this->prepareToolbar();

		parent::display($tpl);
	}

	/**
	 * Prepare the toolbar.
	 *
	 * @return  void
	 *
	 * @since   4.0.0
	 */
	protected function prepareToolbar()
	{
		$tmpl = Factory::getApplication()->input->getCmd('tmpl');
		
		if (JVERSION < 4)
		{
			$jversion = 'j3';
		}
		else
		{
			$jversion = 'j4';
		}

		// Get the toolbar object instance
		$toolbar = Toolbar::getInstance();
		$user = Factory::getUser();

		// Set the title
		ToolbarHelper::title(Text::_('COM_SPPAGEBUILDER') . ' - ' . Text::_('COM_SPPAGEBUILDER_MEDIA'), 'none pbfont pbfont-pagebuilder');

		if(JVERSION < 4)
		{
			// Add an upload button
			if ($user->authorise('core.create', 'com_media'))
			{
				// Instantiate a new JLayoutFile instance and render the layout
				$layout = new FileLayout('upload', JPATH_COMPONENT_ADMINISTRATOR . '/layouts/toolbar/' . $jversion);

				$toolbar->appendButton('Custom', $layout->render(array()), 'upload');
				ToolbarHelper::divider();

				// Add the create folder button
				$layout = new FileLayout('create-folder', JPATH_COMPONENT_ADMINISTRATOR . '/layouts/toolbar/' . $jversion);

				$toolbar->appendButton('Custom', $layout->render(array()), 'new');
				ToolbarHelper::divider();
			}

			// Add a delete button
			if ($user->authorise('core.delete', 'com_sppagebuilder'))
			{
				$layout = new FileLayout('delete', JPATH_COMPONENT_ADMINISTRATOR . '/layouts/toolbar/' . $jversion);

				$toolbar->appendButton('Custom', $layout->render(array()), 'delete');
				ToolbarHelper::divider();
			}

			if ($user->authorise('core.admin', 'com_sppagebuilder') || $user->authorise('core.options', 'com_sppagebuilder'))
			{
				ToolbarHelper::preferences('com_sppagebuilder');
			}
		}
		else
		{
			// Add the upload and create folder buttons
			if ($user->authorise('core.create', 'com_sppagebuilder'))
			{
				// Add the upload button
				$layout = new FileLayout('upload', JPATH_COMPONENT_ADMINISTRATOR . '/layouts/toolbar/' . $jversion);

				$toolbar->appendButton('Custom', $layout->render([]), 'upload');
				ToolbarHelper::divider();

				// Add the create folder button
				$layout = new FileLayout('create-folder', JPATH_COMPONENT_ADMINISTRATOR . '/layouts/toolbar/' . $jversion);

				$toolbar->appendButton('Custom', $layout->render([]), 'new');
				ToolbarHelper::divider();
			}

			// Add a delete button
			if ($user->authorise('core.delete', 'com_sppagebuilder'))
			{
				// Instantiate a new FileLayout instance and render the layout
				$layout = new FileLayout('delete', JPATH_COMPONENT_ADMINISTRATOR . '/layouts/toolbar/' . $jversion);

				$toolbar->appendButton('Custom', $layout->render([]), 'delete');
				ToolbarHelper::divider();
			}

			if ($user->authorise('core.admin', 'com_sppagebuilder') || $user->authorise('core.options', 'com_sppagebuilder'))
			{
				$toolbar->preferences('com_sppagebuilder');
			}
		}
	}
}
