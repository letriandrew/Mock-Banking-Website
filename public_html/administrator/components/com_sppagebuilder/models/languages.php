<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2022 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;

//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

class SppagebuilderModelLanguages extends ListModel {

	public function __construct($config = array()) {
		parent::__construct($config);
	}

	protected function getListQuery() {
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$user = Factory::getUser();

		$query->select(
			$this->getState(
				'list.select',
				'a.*'
			)
		);

		$query->from('#__sppagebuilder_languages as a');

		return $query;
	}

	public function storeInstall($language)
	{
		$db = Factory::getDbo();
		$input = Factory::getApplication()->input;
		$language_name = $input->get('language', 'en-GB', 'STRING');
		$result = $this->checkInstall($language->lang_tag);
		$version = $language->version;

		if ($result)
		{ // Update
			//self::updateLang($language);
			$values = array(
				'title' => $language->title,
				'description' => $language->description,
				'lang_key' => $language->lang_key,
				'version' => $language->version,
			);
			$version = self::updateLang($values, $language->lang_key);
		}
		else
		{
			$values = array(
				$db->quote($language->title),
				$db->quote($language->description),
				$db->quote($language->lang_tag),
				$db->quote($language->lang_key),
				$db->quote($language->version),
				1
			);
			$this->insertInstall($values);
		}

		return $version;
	}

	private function checkInstall($language = 'en-GB')
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->select($db->quoteName(array('id', 'state')));
		$query->from($db->quoteName('#__sppagebuilder_languages'));
		$query->where($db->quoteName('lang_tag') . ' = ' . $db->quote($language));

		$db->setQuery($query);

		return $db->loadObject();
	}

	private function insertInstall($values = array())
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$columns = array('title', 'description', 'lang_tag', 'lang_key', 'version', 'state');
		$query
		    ->insert($db->quoteName('#__sppagebuilder_languages'))
		    ->columns($db->quoteName($columns))
		    ->values(implode(',', $values));

		$db->setQuery($query);
		$db->execute();
		$insertid = $db->insertid();

		return $insertid;
	}

	public function updateLang($values = array(), $lang_tag = 'en-GB')
	{

		$db = Factory::getDbo();

		// Change state to database
		$query = $db->getQuery(true);
		$fields = array(
				$db->quoteName('title') . ' = ' . $db->quote($values['title']),
				$db->quoteName('description') . ' = ' . $db->quote($values['description']),
				$db->quoteName('lang_key') . ' = ' . $db->quote($values['lang_key']),
				$db->quoteName('version') . ' = ' . $db->quote($values['version']),
			);
		//$fields = array('title', 'description', 'lang_tag', 'lang_key', 'version', 'state');
		$conditions = array( $db->quoteName('lang_key') . ' = ' . $db->quote($lang_tag) );
		$query->update($db->quoteName('#__sppagebuilder_languages'))->set($fields)->where($conditions);
		$db->setQuery($query);
		$db->execute();

		return $values['version'];
	}

}
