<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Language\Multilanguage;

JLoader::register('SppagebuilderHelperRoute', JPATH_ROOT . '/components/com_sppagebuilder/helpers/route.php');
JLoader::register('SppagebuilderHelper', JPATH_ADMINISTRATOR . '/components/com_sppagebuilder/helpers/sppagebuilder.php');

class JFormFieldPagebuilder extends FormField
{
	protected	$type = 'Pagebuilder';

	protected function getInput()
	{
		$output = '';
		$id = (int) Factory::getApplication()->input->get('id', 0, 'INT');

		if ($id)
		{
			$pageData = $this->pageData($id);

			if (isset($pageData->id) && $pageData->id)
			{
				$view_id = $pageData->id;
				$language = $pageData->language;
			}
			else
			{
				$data = $this->form->getData();
				$title = $data->get('title');
				$language = $data->get('language');
				$access = $data->get('access');
				$published = $data->get('published');

				$view_id = $this->insertData($id, $title, '[]', $language, $access, $published);
			}

			$front_link = 'index.php?option=com_sppagebuilder&view=form&tmpl=component&layout=edit&extension=mod_sppagebuilder&extension_view=module&id=' . $view_id;
			$backend_link = 'index.php?option=com_sppagebuilder&view=editor&extension=com_content&extension_view=module&module_id=' . $id . '&tmpl=component#/editor/' . $view_id;

			if ($language && $language !== '*' && Multilanguage::isEnabled())
			{
				$front_link .= '&lang=' . $language;
				$backend_link .= '&lang=' . $language;
			}

			$front_link = str_replace('/administrator', '', SppagebuilderHelperRoute::buildRoute($front_link));

			$output = '<div style="display: flex; justify-content: center; gap: 10px; margin-top: 1rem;"><a class="builder-edit-btn btn btn-outline btn-large" style="border: 2px solid var(--template-bg-dark-60)" href="' . $backend_link . '" target="_blank">Edit with Backend Editor</a><a class="builder-edit-btn btn btn-primary btn-large" href="' . $front_link . '" target="_blank">Edit with Frontend Editor</a></div>';

			$output .= '<input type="hidden" name="' . $this->name . '" id="' . $this->id . '" value="">';
			$output .= '<input type="hidden" name="jform[content]" id="jform_content" value="">';
			$output .= '<input type="hidden" id="sppagebuilder_module_id" value="' . $id . '">';
		}
		else
		{
			$output .= '<div class="alert alert-info">Please save this module to activate Page Builder</div>';
		}

		$output .= '<style>#general .builder-edit-btn::before {content: "";} #general .control-group .control-label {display: none;} #general .control-group .controls {margin-left: 0;}</style>';

		return $output;
	}

	private function pageData($id)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__sppagebuilder'));
		$query->where($db->quoteName('extension') . ' = ' . $db->quote('mod_sppagebuilder'));
		$query->where($db->quoteName('extension_view') . ' = ' . $db->quote('module'));
		$query->where($db->quoteName('view_id') . ' = ' . $db->quote($id));
		$db->setQuery($query);
		$result = $db->loadObject();

		return $result;
	}

	private function insertData($id, $title, $content, $language, $access, $published)
	{
		$user = Factory::getUser();
		$date = Factory::getDate();
		$db = Factory::getDbo();
		$page = new stdClass();
		$page->title = $title;
		$page->text = '';
		$page->content = $content;
		$page->extension = 'mod_sppagebuilder';
		$page->extension_view = 'module';
		$page->view_id = $id;
		$page->published = $published;
		$page->created_by = (int) $user->id;
		$page->created_on = $date->toSql();
		$page->modified = $date->toSql();
		$page->language = $language;
		$page->access = $access;
		$page->css = '';
		$page->active = 1;
		$page->version = SppagebuilderHelper::getVersion();

		$db->insertObject('#__sppagebuilder', $page);

		return $db->insertid();
	}

	function isJson($string)
	{
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}
}
