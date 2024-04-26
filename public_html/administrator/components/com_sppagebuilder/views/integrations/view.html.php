<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2022 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

class SppagebuilderViewIntegrations extends HtmlView {

	protected $items;
	protected $pagination;
	protected $state;

	public function display( $tpl = null )
	{
		$this->items = $this->get('Items');

		// Check for errors.
		if (count((array)$errors = $this->get('Errors')))
		{
			$app = Factory::getApplication();
			$app->enqueueMessage(implode("\n", $errors), 'error');
			$app->setHeader('status', 500, true);
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	
	protected function addToolBar() {
		// Set the title
		ToolbarHelper::title(Text::_('COM_SPPAGEBUILDER') . ' - ' . Text::_('COM_SPPAGEBUILDER_INTEGRATIONS'), 'none pbfont pbfont-pagebuilder');
	}
}