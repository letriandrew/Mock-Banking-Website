<?php

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Session\Session;

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('Restricted access');

class JFormFieldModal_Page extends FormField
{

	protected $type = 'Modal_Page';

	protected function getInput()
	{
		// Load language
		Factory::getLanguage()->load('com_sppagebuilder', JPATH_ADMINISTRATOR);

		$modalId = 'Page_' . $this->id;

		if (JVERSION >= 4)
		{

			/** @var \Joomla\CMS\WebAsset\WebAssetManager $wa */
			$wa = Factory::getApplication()->getDocument()->getWebAssetManager();

			// Add the modal field script to the document head.
			$wa->useScript('field.modal-fields');

			$wa->addInlineScript(
				"
				window.jSelectPage_" . $this->id . " = function (id, title, catid, object, url, language) {
					window.processModalSelect('Page', '" . $this->id . "', id, title, catid, object, url, language);
				}",
				[],
				['type' => 'module']
			);

			// Setup variables for display.
			$html	= array();
			$link	= 'index.php?option=com_sppagebuilder&amp;view=pages&amp;layout=modal&amp;tmpl=component&amp;function=jSelectPage_' . $this->id;

			if (isset($this->element['language']))
			{
				$link .= '&amp;forcedLanguage=' . $this->element['language'];
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
					$app = Factory::getApplication();
					$app->enqueueMessage($e->getMessage(), 'warning');
					$app->setHeader('status', '500', true);
				}
			}

			if (empty($title))
			{
				$title = Text::_('COM_SPPAGEBUILDER_SELECT_AN_PAGE');
			}

			$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

			// The active page id field.
			if (0 == (int) $this->value)
			{
				$value = '';
			}
			else
			{
				$value = (int) $this->value;
			}

			$url = $link . '&amp;' . Session::getFormToken() . '=1';
			$html = '';

			$html .= '<span class="input-group">';
			$html .= '<input class="form-control" id="' . $this->id . '_name" type="text" value="' . $title . '" readonly size="35">';
			$html .= '<button'
				. ' class="btn btn-primary"'
				. ' id="' . $this->id . '_edit"'
				. ' data-bs-toggle="modal"'
				. ' type="button"'
				. ' data-bs-target="#ModalSelect' . $modalId . '">'
				. '<span class="icon-file" aria-hidden="true"></span> ' . Text::_('JSELECT')
				. '</button>';
			$html .= '</span>';

			// The class='required' for client side validation
			$class = '';

			if ($this->required)
			{
				$class = ' class="required modal-value"';
			}

			$html .= HTMLHelper::_(
				'bootstrap.renderModal',
				'ModalSelectPage_' . $this->id,
				array(
					'url' => $url,
					'title' => Text::_('COM_SPPAGEBUILDER_SELECT_AN_PAGE'),
					'height'      => '400px',
					'width'       => '800px',
					'bodyHeight'  => 70,
					'modalWidth'  => 80,
					'footer' => '<button class="btn" data-bs-dismiss="modal" aria-hidden="true">'
						. Text::_("JLIB_HTML_BEHAVIOR_CLOSE") . '</button>'
				)
			);

			$html .= '<input type="hidden" id="' . $this->id . '_id"' . $class . ' name="' . $this->name . '" value="' . $value . '" />';

			return $html;
		}
		else
		{
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
			$link	= 'index.php?option=com_sppagebuilder&amp;view=pages&amp;layout=modal&amp;tmpl=component&amp;function=jSelectPage_' . $this->id;

			if (isset($this->element['language']))
			{
				$link .= '&amp;forcedLanguage=' . $this->element['language'];
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
					$app = Factory::getApplication();
					$app->enqueueMessage($e->getMessage(), 'warning');
					$app->setHeader('status', '500', true);
				}
			}

			if (empty($title))
			{
				$title = Text::_('COM_SPPAGEBUILDER_SELECT_AN_PAGE');
			}

			$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');

			// The active page id field.
			if (0 == (int) $this->value)
			{
				$value = '';
			}
			else
			{
				$value = (int) $this->value;
			}

			$url = $link . '&amp;' . Session::getFormToken() . '=1';

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
}
