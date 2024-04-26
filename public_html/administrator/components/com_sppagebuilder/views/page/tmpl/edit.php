<?php
/**
* @package SP Page Builder
* @author JoomShaper http://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2021 JoomShaper
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Component\ComponentHelper;

HTMLHelper::_('jquery.framework');
HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('behavior.keepalive');

$doc = Factory::getDocument();
$app = Factory::getApplication();

//css
SppagebuilderHelper::loadAssets('css');
SppagebuilderHelper::addStylesheet('react-select.css');

//js
$doc->addScriptdeclaration('var pagebuilder_base="' . Uri::root() . '";');
SppagebuilderHelper::loadEditor();
SppagebuilderHelper::addScript('sidebar.js');
SppagebuilderHelper::addScript('script.js');
SppagebuilderHelper::addScript('actions.js');
//SppagebuilderHelper::addScript('csslint.js');

require_once JPATH_COMPONENT .'/builder/classes/base.php';
require_once JPATH_COMPONENT .'/builder/classes/config.php';
require_once JPATH_COMPONENT . '/helpers/language.php';

global $pageId;
global $language;

$pageId = $this->item->id;
$language = $this->item->language;

// Addon List Initialize
SpPgaeBuilderBase::loadAddons();
$fa_icon_list     = SpPgaeBuilderBase::getIconList(); // Icon List
$animateNames     = SpPgaeBuilderBase::getAnimationsList(); // Animation Names
$accessLevels     = SpPgaeBuilderBase::getAccessLevelList(); // Access Levels
$article_cats     = SpPgaeBuilderBase::getArticleCategories(); // Article Categories
$moduleAttr       = SpPgaeBuilderBase::getModuleAttributes(); // Module Postions and Module Lits
$rowSettings      = SpPgaeBuilderBase::getRowGlobalSettings(); // Row Settings Attributes
$columnSettings   = SpPgaeBuilderBase::getColumnGlobalSettings(); // Column Settings Attributes

// Addon List
$addons_list    = SpAddonsConfig::$addons;

usort($addons_list, function($a){
	if (isset($a['pro']) && $a['pro']) {
		return 1;
	}
});

$newAddonList = array();

foreach ( $addons_list as $addon ) {
  $default_value = SpPgaeBuilderBase::getSettingsDefaultValue($addon['attr']);
  $addon['default'] = $default_value['default'];
  if(isset($default_value['attr'])){
    $addon['attr'] = $default_value['attr'];
  }

  $addon_name = preg_replace('/^sp_/i', '', $addon['addon_name']);

  $newAddonList[$addon_name] = $addon;
}

$row_default_value = SpPgaeBuilderBase::getSettingsDefaultValue($rowSettings['attr']);
$rowSettings['default'] = $row_default_value;

$column_default_value = SpPgaeBuilderBase::getSettingsDefaultValue($columnSettings['attr']);
$columnSettings['default'] = $column_default_value;

$global_attributes = SpPgaeBuilderBase::addonOptions();
$doc->addScriptdeclaration('var addonsJSON=' . json_encode($newAddonList) . ';');

// Addon Categories
$addon_cats = SpPgaeBuilderBase::getAddonCategories($newAddonList);
$doc->addScriptdeclaration('var addonCats=' . json_encode($addon_cats) . ';');

// Global Attributes
$doc->addScriptdeclaration('var globalAttr=' . json_encode( $global_attributes ) . ';');
$doc->addScriptdeclaration('var faIconList=' . json_encode( $fa_icon_list ) . ';');
$doc->addScriptdeclaration('var animateNames=' . json_encode( $animateNames ) . ';');
$doc->addScriptdeclaration('var accessLevels=' . json_encode( $accessLevels ) . ';');
$doc->addScriptdeclaration('var articleCats=' . json_encode( $article_cats ) . ';');
$doc->addScriptdeclaration('var moduleAttr=' . json_encode( $moduleAttr ) . ';');
$doc->addScriptdeclaration('var rowSettings=' . json_encode( $rowSettings ) . ';');
$doc->addScriptdeclaration('var colSettings=' . json_encode( $columnSettings ) . ';');
// Media
$mediaParams = ComponentHelper::getParams('com_media');
$doc->addScriptdeclaration('var sppbMediaPath=\'/'. $mediaParams->get('file_path', 'images') .'\';');

$spPageBuilderParams = ComponentHelper::getParams('com_sppagebuilder');
$doc->addScriptdeclaration('var useGoogleFonts = ' . $spPageBuilderParams->get('google_fonts', 0) . ';');

if (!$this->item->text) {
  $doc->addScriptdeclaration('var initialState=[];');
} else {
  require_once JPATH_COMPONENT . '/builder/classes/addon.php';
  $this->item->text = SpPageBuilderAddonHelper::__($this->item->text);
  $doc->addScriptdeclaration('var initialState=' . $this->item->text . ';');
}
?>

<div class="sp-pagebuilder-admin">
	<form action="<?php echo Route::_('index.php?option=com_sppagebuilder&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">

		<div class="sp-pagebuilder-editor-wrapper">
			<div class="sp-pagebuilder-sidebar">
				<ul class="sp-pagebuilder-sidebar-tabs">
					<li class="sp-pagebuilder-sidebar-tab-item" aria-label="<?php echo Text::_('COM_SPPAGEBUILDER_FIELDSET_SEOSETTINGS'); ?>">
						<span class="fab fa-facebook" aria-hidden="true" data-pb-sidebar-action></span>
						<span class="sp-pagebuilder-sidebar-item-title"><?php echo Text::_('COM_SPPAGEBUILDER_FIELDSET_SEOSETTINGS'); ?></span>
						<ul>
							<li>
								<span class="sp-pagebuilder-close-sidebar" role="button"><span class="fas fa-times" area-hidden="true"></span></span>
								<?php echo $this->form->renderFieldset('seosettings'); ?>
							</li>
						</ul>
					</li>
					<li class="sp-pagebuilder-sidebar-tab-item" aria-label="<?php echo Text::_('COM_SPPAGEBUILDER_FIELD_CSS'); ?>">
						<span class="fab fa-css3" aria-hidden="true" data-pb-sidebar-action></span>	
						<span class="sp-pagebuilder-sidebar-item-title"><?php echo Text::_('COM_SPPAGEBUILDER_FIELD_CSS'); ?></span>
						<ul>
							<li>
								<span class="sp-pagebuilder-close-sidebar" role="button"><span class="fas fa-times" area-hidden="true"></span></span>
								<?php echo $this->form->renderFieldset('pagecss'); ?>
							</li>
						</ul>
					</li>
					<li class="sp-pagebuilder-sidebar-tab-item" aria-label="<?php echo Text::_('JGLOBAL_FIELDSET_PUBLISHING'); ?>">
						<span class="far fa-calendar-check" aria-hidden="true" data-pb-sidebar-action></span>
						<span class="sp-pagebuilder-sidebar-item-title"><?php echo Text::_('JGLOBAL_FIELDSET_PUBLISHING'); ?></span>
						<ul>
							<li>
								<span class="sp-pagebuilder-close-sidebar" role="button"><span class="fas fa-times" area-hidden="true"></span></span>
								<?php echo $this->form->renderFieldset('publishing'); ?>
							</li>
						</ul>
					</li>
					<li class="sp-pagebuilder-sidebar-tab-item" aria-label="<?php echo Text::_('Permissions'); ?>">
						<span class="fas fa-globe-europe" aria-hidden="true" data-pb-sidebar-action></span>
						<span class="sp-pagebuilder-sidebar-item-title"><?php echo Text::_('Permissions'); ?></span>
						<ul class="w-double">
							<li>
								<span class="sp-pagebuilder-close-sidebar" role="button"><span class="fas fa-times" area-hidden="true"></span></span>
								<div class="form-vertical">
									<?php echo $this->form->renderFieldset('permissions'); ?>
								</div>
							</li>
						</ul>
					</li>
				</ul>
			</div>

			<div class="sp-pagebuilder-main-editor">
				
				<div class="sp-pagebuilder-row mt-3 mb-5">
					<div class="col-lg-6">
						<div class="form-sp-pagebuilder-inline">
							<?php echo $this->form->renderField('title'); ?>
						</div>
					</div>

					<div class="col-lg-6">
						<div class="sp-pagebuilder-frontend-actions">
							<?php if($this->item->id) : ?>
								<a id="btn-page-frontend-editor" target="_blank" href="<?php echo $this->item->frontend_edit; ?>" class="btn btn-primary"><?php echo Text::_('COM_SPPAGEBUILDER_FRONTEND_EDITOR'); ?></a>
								<a id="btn-page-preview" target="_blank" href="<?php echo $this->item->preview; ?>" class="btn btn-outline-primary ml-3"><?php echo Text::_('COM_SPPAGEBUILDER_PREVIEW'); ?></a>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<div id="sp-pagebuilder-page-tools" class="sp-pagebuilder-page-tools"></div>
				
				<div class="sp-pagebuilder-sidebar-and-builder">
					<div id="sp-pagebuilder-section-lib" class="sp-pagebuilder-section-lib"></div>
					<div class="sp-pagebuilder-main-app">
						<div id="container"></div>
					</div>
				</div>

			</div>
		</div>

		<?php echo $this->form->renderField('text'); ?>
		<?php echo $this->form->renderField('id'); ?>
		<input type="hidden" name="task" value="item.edit" />
		<?php echo HTMLHelper::_('form.token'); ?>
	</form>

	<div class="sp-pagebuilder-notifications"></div>

	<div class="sp-pagebuilder-media-modal-overlay" style="display:none">
		<div id="sp-pagebuilder-media-modal"></div>
	</div>
</div>

<script type="text/javascript" src="<?php echo Uri::base(true) . '/components/com_sppagebuilder/assets/js/engine.js'; ?>" defer></script>
