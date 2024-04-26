<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2022 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('Restricted access');

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

class SppagebuilderViewAbout extends HtmlView {

	public function display( $tpl = null ) {

		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolBar() {
		ToolbarHelper::title(Text::_('COM_SPPAGEBUILDER') . ' - ' . Text::_('COM_SPPAGEBUILDER_ABOUT'), 'none pbfont pbfont-pagebuilder');
	}
}
