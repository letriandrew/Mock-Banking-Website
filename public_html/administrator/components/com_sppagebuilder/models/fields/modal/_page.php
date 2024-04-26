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
use Joomla\CMS\Form\FormField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Session\Session;

class JFormFieldModal_Page extends FormField
{

	protected $type = 'Modal_Page';

	protected function getInput()
	{
		// Load language
		Factory::getLanguage()->load('com_sppagebuilder', JPATH_ADMINISTRATOR);

		// Build the script.
		$script = array();

		// Select button script
		$script[] = '	function jSelectPage_' . $this->id . '(id, title, catid, object) {';
		$script[] = '		document.getElementById("' . $this->id . '_id").value = id;';
		$script[] = '		document.getElementById("' . $this->id . '_name").value = title;';

		$script[] = '		jQuery("#modalPage' . $this->id . '").modal("hide");';

		if ($this->required)
		{
			$script[] = '		document.formvalidator.validate(document.getElementById("' . $this->id . '_id"));';
			$script[] = '		document.formvalidator.validate(document.getElementById("' . $this->id . '_name"));';
		}

		$script[] = '	}';

		// Add the script to the document head.
		Factory::getDocument()->addScriptDeclaration(implode("\n", $script));

		// Setup variables for display.
		$html	= array();
		$link	= 'index.php?option=com_sppagebuilder&view=pages&amp;layout=modal&tmpl=component&function=jSelectPage_' . $this->id;

		if (isset($this->element['language']))
		{
			$link .= '&forcedLanguage=' . $this->element['language'];
		}

		if ((int) $this->value > 0)
		{
			$db	= Factory::getDbo();
			$query = $db->getQuery(true)
				->select($db->quoteName('title'))
				->from($db->quoteName('#__sppagebuilder'))
				->where($db->quoteName('id') . ' = ' . (int) $this->value);
			$db->setQuery($query);

			try
			{
				$title = $db->loadResult();
			}
			catch (RuntimeException $e)
			{
				throw new \Exception($e->getMessage());
			}
		}

		if (empty($title))
		{
			$title = Text::_('COM_SPPAGEBUILDER_SELECT_AN_PAGE');
		}
		$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

		// The active page id field.
		if ((int) $this->value === 0)
		{
			$value = '';
		}
		else
		{
			$value = (int) $this->value;
		}

		$url = $link . '&' . Session::getFormToken() . '=1';

		// The current article display field.
		$html[] = '<span class="input-append">';
		$html[] = '<input type="text" class="input-medium" id="' . $this->id . '_name" value="' . $title . '" disabled="disabled" size="35" />';
		$html[] = '<a href="#modalPage' . $this->id . '" class="btn hasTooltip" role="button"  data-toggle="modal" title="'
			. HTMLHelper::tooltipText('COM_SPPAGEBUILDER_CHANGE_PAGE') . '">'
			. '<span class="icon-file"></span> '
			. Text::_('JSELECT') . '</a>';

		$html[] = '</span>';

		// The class='required' for client side validation
		$class = '';

		if ($this->required)
		{
			$class = ' class="required modal-value"';
		}

		$html[] = '<input type="hidden" id="' . $this->id . '_id"' . $class . ' name="' . $this->name . '" value="' . $value . '" />';

		$html[] = HTMLHelper::_(
			'bootstrap.renderModal',
			'modalPage' . $this->id,
			array(
				'url' => $url,
				'title' => Text::_('COM_SPPAGEBUILDER_SELECT_AN_PAGE'),
				'width' => '800px',
				'height' => '400px',
				'footer' => '<button class="btn" data-dismiss="modal" aria-hidden="true">'
					. Text::_("JLIB_HTML_BEHAVIOR_CLOSE") . '</button>'
			)
		);
		return implode("\n", $html);
	}
}
