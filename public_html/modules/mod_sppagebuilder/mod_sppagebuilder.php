<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('restricted access');

use Joomla\CMS\Helper\ModuleHelper;

require_once __DIR__ . '/helper.php';
require_once JPATH_ROOT . '/components/com_sppagebuilder/helpers/autoload.php';
require_once JPATH_ROOT . '/components/com_sppagebuilder/helpers/constants.php';

BuilderAutoload::loadClasses();
BuilderAutoload::loadHelperClasses();

$data = ModSPagebuilderHelper::getData($module->id, $params);
$moduleclass_sfx = !empty($params->get('moduleclass_sfx')) ? htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8') : "";

require ModuleHelper::getLayoutPath('mod_sppagebuilder', $params->get('layout', 'default'));
