<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Language\Text;
use Joomla\String\StringHelper;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\MVC\Model\AdminModel;

if (!\class_exists('EditorUtils')) {
	require_once __DIR__ . './../editor/helpers/EditorUtils.php';
}

class SppagebuilderModelSection extends AdminModel
{

	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	/**
	 * Method for getting a form.
	 *
	 * @param array $data Data for the form.
	 * @param bool $loadData True if the form is to load its own data (default case), false if not.
	 * @return void
	 */
	public function getForm($data = array(), $loadData = true)
	{
	}

	public function getTable($name = 'Section', $prefix = 'SppagebuilderTable', $options = array())
	{
		return Table::getInstance($name, $prefix, $options);
	}
}
