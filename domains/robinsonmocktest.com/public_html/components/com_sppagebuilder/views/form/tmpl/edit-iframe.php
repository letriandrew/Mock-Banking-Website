<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;

$doc = Factory::getDocument();
$app = Factory::getApplication();
$params = ComponentHelper::getParams('com_sppagebuilder');

$doc->addScriptdeclaration('var disableGoogleFonts = ' . $params->get('disable_google_fonts', 0) . ';');

if ($params->get('fontawesome', 1))
{
	SppagebuilderHelperSite::addStylesheet('font-awesome-5.min.css');
	SppagebuilderHelperSite::addStylesheet('font-awesome-v4-shims.css');
}

// assets
SppagebuilderHelperSite::loadAssets();

if (!$params->get('disableanimatecss', 0))
{
	SppagebuilderHelperSite::addStylesheet('animate.min.css');
}

if (!$params->get('disablecss', 0))
{
	SppagebuilderHelperSite::addStylesheet('sppagebuilder.css');
	SppagebuilderHelperSite::addContainerMaxWidth();
}

SppagebuilderHelperSite::addStylesheet('canvas.css');

HTMLHelper::_('jquery.framework');
$doc->addScriptdeclaration('var pagebuilder_base="' . Uri::root() . '";');
SppagebuilderHelperSite::addScript('jquery.parallax.js');
SppagebuilderHelperSite::addScript('sppagebuilder.js');

$menus = $app->getMenu();
$menu = $menus->getActive();
$menuClassPrefix = '';
$showPageHeading = 0;

// check active menu item
if ($menu)
{
	$menuClassPrefix 	= $menu->getParams()->get('pageclass_sfx');
	$showPageHeading 	= $menu->getParams()->get('show_page_heading');
	$menuheading 		= $menu->getParams()->get('page_heading');
}

require_once JPATH_COMPONENT . '/builder/classes/base.php';
require_once JPATH_COMPONENT . '/builder/classes/config.php';
require_once JPATH_COMPONENT . '/builder/classes/addon.php';


$this->item = ApplicationHelper::preparePageData($this->item);

SpPgaeBuilderBase::loadAddons();
$addons_list = SpAddonsConfig::$addons;

$addons_list = array_map(function ($addon)
{
	return AddonsHelper::modernizeAddonStructure($addon);
}, $addons_list);

SpPgaeBuilderBase::loadAssets($addons_list);
$addon_cats = SpPgaeBuilderBase::getAddonCategories($addons_list);
$doc->addScriptdeclaration('var addonsJSON=' . json_encode($addons_list) . ';');
$doc->addScriptdeclaration('var addonsFromDB=' . json_encode(SpAddonsConfig::loadAddonList()) . ';');
$doc->addScriptdeclaration('var addonCats=' . json_encode($addon_cats) . ';');
$doc->addScriptdeclaration('var sppbVersion="' . SppagebuilderHelperSite::getVersion() . '";');

if (!$this->item->text)
{
	$doc->addScriptdeclaration('var initialState=[];');
}
else
{
	$doc->addScriptdeclaration('var initialState=' . json_encode($this->item->text) . ';');
}
?>

<div id="sp-page-builder" class="sp-pagebuilder <?php echo $menuClassPrefix; ?> page-<?php echo $this->item->id; ?>">
	<div id="sp-pagebuilder-container">
		<div class="sp-pagebuilder-loading-wrapper">
			<div class="sp-pagebuilder-loading">
				<svg width="28" height="32" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M23.028 17.741c.855-.89 2.358-.856 3.219 0 .88.88.85 2.33 0 3.219-.89.929-1.79 1.848-2.743 2.719-5.846 5.35-13.194 8.56-21.204 8.292-1.235-.039-2.276-1.007-2.276-2.276 0-1.202 1.045-2.315 2.276-2.276 2.524.082 4.426-.083 6.765-.677a25.837 25.837 0 0 0 2.694-.846c.222-.083.443-.17.662-.262l.292-.132c.379-.174.758-.355 1.133-.544a29.604 29.604 0 0 0 4.192-2.612c.097-.072.19-.146.287-.213-.015.01-.31.242-.112.087.136-.107.273-.214.408-.325.264-.21.52-.429.774-.648.491-.424.967-.855 1.44-1.303a59.718 59.718 0 0 0 2.193-2.203Zm-12.85-1.124c.732-.39 1.431-.837 2.092-1.336a.424.424 0 0 0-.01-.681l-5.228-3.77a.424.424 0 0 0-.67.345l.018 6.288a.423.423 0 0 0 .531.409 14.164 14.164 0 0 0 3.268-1.255Z" fill="#2684FF" />
					<path d="M19.959 12.932 5.476 1.853C4.8 1.337 4.324.973 3.647.458L.35 1.124c-.686 1.172-.175 2.362.808 3.111 4.824 3.69 9.653 7.388 14.482 11.078.676.516 1.352 1.032 2.024 1.547.977.749 2.548.15 3.111-.817.687-1.172.166-2.364-.816-3.112Z" fill="url(#a)" />
					<path d="M9.226 23.655c1.42-.326 2.82-.934 4.12-1.571 2.703-1.318 5.208-3.214 7.075-5.579.389-.49.666-.952.666-1.609 0-.56-.248-1.225-.666-1.61-.822-.753-2.432-.997-3.219 0a16.981 16.981 0 0 1-2.407 2.495c-.15.127-.307.254-.458.38-.374.306.355-.258 0 0a22.162 22.162 0 0 1-3.282 1.993c-.249.123-.496.234-.744.346-.16.072-.536.184.17-.068-.122.043-.239.097-.355.146-.36.141-.725.277-1.095.398-.33.107-.665.215-1.007.292-1.161.263-1.954 1.668-1.59 2.802.39 1.21 1.547 1.867 2.792 1.585ZM3.716.505A2.243 2.243 0 0 0 2.306 0C1.07 0 .03 1.04.03 2.276v27.35c0 1.231 1.044 2.277 2.275 2.277 1.236 0 2.276-1.04 2.276-2.277V1.167L3.715.505h.001Z" fill="#2684FF" />
					<defs>
						<linearGradient id="a" x1="4.57476" y1="5.7046" x2="15.8692" y2="14.5689" gradientUnits="userSpaceOnUse">
							<stop stop-color="#0052CC" />
							<stop offset="1" stop-color="#2684FF" />
						</linearGradient>
					</defs>
				</svg>
			</div>
		</div>
	</div>
</div>

<style id="sp-pagebuilder-css" type="text/css">
	<?php echo $this->item->css; ?>
</style>

<?php
$doc->addScriptDeclaration('jQuery(document).ready(function($) {
	$(document).on("click", "a", function(e){
		e.preventDefault();
	});

	$(document).on("click", ".sp-editable-content, .sp-inline-editable-element", function(e){
		e.preventDefault();
		var ids = jQuery(this).attr("id");
		jQuery(this).attr("contenteditable", true);
		jQuery(this).focus();
	});
});');
