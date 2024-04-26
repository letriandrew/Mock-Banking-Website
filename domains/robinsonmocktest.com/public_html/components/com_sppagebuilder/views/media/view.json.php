<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView;

//no direct access
defined('_JEXEC') or die('Restricted access');

if (!class_exists('SppagebuilderHelperSite'))
{
	require_once JPATH_ROOT . '/components/com_sppagebuilder/helpers/helper.php';
}
class SppagebuilderViewMedia extends HtmlView
{
	public function display($tpl = null)
	{
		$user = Factory::getUser();
		$authorised = $user->authorise('core.admin', 'com_sppagebuilder') || $user->authorise('core.manage', 'com_sppagebuilder');

		if (!$user->id || !$authorised)
		{
			throw new Exception(Text::_('COM_SPPAGEBUILDER_GLOBAL_UNAUTHORIZED_ACCESS'));
		}

		$input = Factory::getApplication()->input;
		$layout = $input->get('layout', 'browse', 'STRING');
		$this->date = $input->post->get('date', NULL, 'STRING');
		$this->start = $input->post->get('start', 0, 'INT');
		$this->search = $input->post->get('search', NULL, 'STRING');
		$this->limit = 30;

		$model = $this->getModel();

		if (($layout == 'browse') || ($layout == 'modal'))
		{
			$this->items = $model->getItems();
			$this->filters = $model->getDateFilters($this->date, $this->search);
			$this->total = $model->getTotalMedia($this->date, $this->search);
			$this->categories = $model->getMediaCategories();
		}
		else
		{
			$this->media = $model->getFolders();
		}

		SppagebuilderHelperSite::loadLanguage();

		parent::display($tpl);
	}
}
