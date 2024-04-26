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
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Component\ComponentHelper;

JLoader::register('SppagebuilderHelperSite', JPATH_SITE . '/components/com_sppagebuilder/helpers/helper.php');
require_once JPATH_ROOT . '/components/com_sppagebuilder/parser/addon-parser.php';

if (!class_exists('SpPageBuilderAddonHelper'))
{
	require_once JPATH_ROOT . '/components/com_sppagebuilder/builder/classes/addon.php';
}
$doc = Factory::getDocument();
$input = Factory::getApplication()->input;
$component_params = ComponentHelper::getParams('com_sppagebuilder');

if ($component_params->get('fontawesome', 1))
{
	SppagebuilderHelperSite::addStylesheet('font-awesome-5.min.css');
	SppagebuilderHelperSite::addStylesheet('font-awesome-v4-shims.css');
}

if (!$component_params->get('disableanimatecss', 0))
{
	SppagebuilderHelperSite::addStylesheet('animate.min.css');
}

if (!$component_params->get('disablecss', 0))
{
	SppagebuilderHelperSite::addStylesheet('sppagebuilder.css');
	SppagebuilderHelperSite::addContainerMaxWidth();
}

// load font assets form database
SppagebuilderHelperSite::loadAssets();

HTMLHelper::_('jquery.framework');
HTMLHelper::_('script', 'components/com_sppagebuilder/assets/js/jquery.parallax.js', ['version' => SppagebuilderHelperSite::getVersion(true)]);
HTMLHelper::_('script', 'components/com_sppagebuilder/assets/js/sppagebuilder.js', ['version' => SppagebuilderHelperSite::getVersion(true)], ['defer' => true]);



?>
<div class="mod-sppagebuilder <?php echo $moduleclass_sfx ?> sp-page-builder" data-module_id="<?php echo $module->id; ?>">
	<div class="page-content">
		<?php echo AddonParser::viewAddons(json_decode($data), 0, 'module'); ?>
	</div>
</div>