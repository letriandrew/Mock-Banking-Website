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

require_once JPATH_COMPONENT .'/builder/classes/ajax.php';

$input = Factory::getApplication()->input;
$action = $input->get('callback', '', 'STRING');

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/ajax.php';