<?php
/**
* @package SP Page Builder
* @author JoomShaper http://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2021 JoomShaper
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/

// No Direct Access
defined('_JEXEC') or die('Resticted Aceess');

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * View to list of Maintenance.
 *
 * @since  1.0.0
 */
class SppagebuilderViewMaintenance extends HtmlView
{
	public function display($tpl = null)
	{
		$this->addToolbar();
		return parent::display($tpl);
	}

	protected function addToolBar()
	{
		// Set the title
		ToolbarHelper::title(Text::_('COM_SPPAGEBUILDER') . ' - ' . Text::_('COM_SPPAGEBUILDER_MAINTENANCE'), 'none pbfont pbfont-pagebuilder');
	}
}

