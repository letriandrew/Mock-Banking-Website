<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Component\ComponentHelper;

require_once JPATH_COMPONENT . '/builder/classes/base.php';
require_once JPATH_COMPONENT . '/builder/classes/config.php';
require_once JPATH_COMPONENT . '/builder/classes/addon.php';

$doc = Factory::getDocument();
$app = Factory::getApplication();
$params = ComponentHelper::getParams('com_sppagebuilder');

if (!$params->get('enable_frontend_editing', 1))
{
	die("The frontend editing is disabled.");
}

$doc->addScriptdeclaration('var disableGoogleFonts = ' . $params->get('disable_google_fonts', 0) . ';');

if ($params->get('fontawesome', 1))
{
	SppagebuilderHelperSite::addStylesheet('font-awesome-5.min.css');
	SppagebuilderHelperSite::addStylesheet('font-awesome-v4-shims.css');
}



// load font assets form database
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

SppagebuilderHelperSite::addStylesheet('editor.css');

HTMLHelper::_('jquery.framework');
$doc->addScriptdeclaration('var pagebuilder_base="' . Uri::root() . '";');
SppagebuilderHelper::loadEditor();
SppagebuilderHelperSite::addScript('csslint.js'); // not necessary
SppagebuilderHelperSite::addScript('actions.js');

if ($this->item->extension === 'com_content' && $this->item->extension_view === 'article')
{
	$extension_view = 'article';
}
else
{
	$extension_view = 'page';
}

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

SpPgaeBuilderBase::loadAddons();

$fa_icon_list     = SpPgaeBuilderBase::getIconList(); // Icon List
$accessLevels     = SpPgaeBuilderBase::getAccessLevelList(); // Access Levels
$article_cats     = SpPgaeBuilderBase::getArticleCategories(); // Article Categories
$moduleAttr       = SpPgaeBuilderBase::getModuleAttributes(); // Module Positions and Module List
$rowSettings      = SpPgaeBuilderBase::getRowGlobalSettings(); // Row Settings Attributes
$columnSettings   = SpPgaeBuilderBase::getColumnGlobalSettings(); // Column Settings Attributes
$global_attributes = SpPgaeBuilderBase::addonOptions();
$user = Factory::getUser();

$userPermissions = SpPgaeBuilderBase::getUserPermissions();

$addons_list = SpAddonsConfig::$addons;

$globalDefaults = [];
$globalSettingsGroups = ['style', 'advanced', 'interaction'];

foreach ($globalSettingsGroups as $groupName)
{
	$globalDefaults = array_merge($globalDefaults, EditorUtils::extractSettingsDefaultValues($global_attributes[$groupName]));
}

$addons_list = array_map(function ($addon) use ($globalDefaults)
{
	$modernAddon =  AddonsHelper::modernizeAddonStructure($addon);
	$addonDefaults = EditorUtils::extractSettingsDefaultValues($modernAddon['settings']);
	$modernAddon['default'] = array_merge($globalDefaults, $addonDefaults);

	return $modernAddon;
}, $addons_list);

foreach ($addons_list as &$addon)
{
	if (!isset($addon['category']) || empty($addon['category']))
	{
		$addon['category'] = 'General';
	}

	$addon_name = preg_replace('/^sp_/i', '', $addon['addon_name']);
	$class_name = 'SppagebuilderAddon' . ucfirst($addon_name);

	if (method_exists($class_name, 'getTemplate'))
	{
		$addon['js_template'] = true;
	}

	Factory::getApplication()->triggerEvent('onBeforeAddonConfigure', array($addon_name, &$addon));
}

unset($addon);

Factory::getApplication()->triggerEvent('onBeforeRowConfigure', array(&$rowSettings));

$rowDefaultValue = EditorUtils::getSectionSettingsDefaultValues();
$rowSettings['default'] = $rowDefaultValue;

$columnDefaultValue = EditorUtils::getColumnSettingsDefaultValues();
$columnSettings['default'] = $columnDefaultValue;

SpPgaeBuilderBase::loadAssets($addons_list);

$addon_cats = SpPgaeBuilderBase::getAddonCategories($addons_list);
$doc->addScriptdeclaration('var addonsJSON=' . json_encode($addons_list) . ';');
$doc->addScriptdeclaration('var addonsFromDB=' . json_encode(SpAddonsConfig::loadAddonList()) . ';');
$doc->addScriptdeclaration('var addonCats=' . json_encode($addon_cats) . ';');

// Global Attributes
$doc->addScriptdeclaration('var globalAttr=' . json_encode($global_attributes) . ';');
$doc->addScriptdeclaration('var faIconList=' . json_encode($fa_icon_list) . ';');
$doc->addScriptdeclaration('var accessLevels=' . json_encode($accessLevels) . ';');
$doc->addScriptdeclaration('var articleCats=' . json_encode($article_cats) . ';');
$doc->addScriptdeclaration('var moduleAttr=' . json_encode($moduleAttr) . ';');
$doc->addScriptdeclaration('var rowSettings=' . json_encode($rowSettings) . ';');
$doc->addScriptdeclaration('var colSettings=' . json_encode($columnSettings) . ';');
$doc->addScriptdeclaration('var sppbVersion="' . SppagebuilderHelperSite::getVersion() . '";');
$doc->addScriptDeclaration('var userPermissions=Object.freeze(' . json_encode($userPermissions) . ');');

// Media
$mediaParams = ComponentHelper::getParams('com_media');
$doc->addScriptdeclaration('var sppbMediaPath=\'/' . $mediaParams->get('file_path', 'images') . '\';');

$doc->addScriptdeclaration('var sppbSvgShape=' . json_encode(SppagebuilderHelperSite::getSvgShapes()) . ';');
$doc->addScriptdeclaration('var extensionView=\'' . $extension_view . '\';');

if (!$this->item->text)
{
	$doc->addScriptdeclaration('var initialState=[];');
}
else
{
	$doc->addScriptdeclaration('var initialState=' . json_encode($this->item->text) . ';');
}

$languageCode = '';

if (Multilanguage::isEnabled())
{
	$languageCode = '&lang=' . $this->item->language;
}

?>

<div id="sp-page-builder" class="sp-pagebuilder <?php echo $menuClassPrefix; ?> page-<?php echo $this->item->id; ?>" data-pageid="<?php echo $this->item->id; ?>">
	<form action="<?php echo Route::_('index.php?option=com_sppagebuilder&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate page-builder-form" style="display: none;">
		<div id="page-options">
			<?php $fieldsets = $this->form->getFieldsets(); ?>

			<div class="sp-pagebuilder-form-group-toggler active">
				<span>Basic <span class="fa fa-chevron-right"></span></span>
				<div>
					<?php foreach ($this->form->getFieldset('basic') as $key => $field) : ?>
						<div class="sp-pagebuilder-form-group">
							<?php echo $field->label; ?>
							<?php echo $field->input; ?>
							<?php if ($field->getAttribute('desc')) : ?>
								<span class="sp-pagebuilder-form-help"><?php echo Text::_($field->getAttribute('desc')); ?></span>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>

		<input type="hidden" id="form_task" name="task" value="page.apply" />
		<?php
		$app = Factory::getApplication();
		$input = $app->input;
		$Itemid = $input->get('Itemid', 0, 'INT');

		$extension = $input->get('extension', '', 'STRING');

		$url = Route::_('index.php?option=com_sppagebuilder&view=page&id=' . $this->item->id . '&Itemid=' . $Itemid);
		$root = Uri::base();
		$root = new Uri($root);
		$pageUrl = $root->getScheme() . '://' . $root->getHost() . $url;

		$iframeUrl = Uri::root() . 'index.php?option=com_sppagebuilder&view=form&id=' . $this->item->id . '&layout=edit-iframe&Itemid=' . $Itemid . $languageCode;

		if ($extension === 'mod_sppagebuilder')
		{
			$iframeUrl .= '&tmpl=component';
		}
		?>
		<input type="hidden" id="return_page" name="return_page" value="<?php echo base64_encode($pageUrl); ?>" />
		<?php echo HTMLHelper::_('form.token'); ?>
	</form>

	<iframe name="sp-pagebuilder-view" id="sp-pagebuilder-view" class="builder-iframe-laptop" data-url="<?php echo $iframeUrl; ?>"></iframe>

	<div id="sp-page-builder-main"></div>

	<!-- Never delete this element -->
	<div id="builder-dnd-provider-dom"></div>
</div>
<script>
	window.builderDefaultDevice = 'xl';
</script>

<?php
$mediaQueries = [
	'xl' => '@media ( max-width: 1399.98px )',
	'lg' => '@media ( max-width: 1199.98px )',
	'md' => '@media ( max-width: 991.98px )',
	'sm' => '@media ( max-width: 767.98px )',
	'xs' => '@media ( max-width: 575.98px )',
];
$queryString = '';

foreach ($mediaQueries as $size => $media)
{
	$queryString .= $media . ' {';
	$queryString .= '
	
	#{{ addonId }}{
		<# if(_.isObject(data.global_border_width)){ #>
			<# if (!_.isEmpty(data.global_border_width.' . $size . ')) { #>
			border-width: {{data.global_border_width.' . $size . '}}px;
			<# } #>
		<# } #>
		
		<# if(_.isObject(borderRadius)){ #>
			<# if (!_.isEmpty(borderRadius.' . $size . ')) { #>
			border-radius: {{ borderRadius.' . $size . ' }}px;
			<# } #>
		<# }
		if(_.isObject(padding)){
		#>
			{{{ padding.' . $size . ' }}}
		<# } #>

	}
	#sp-page-builder div#addon-wrap-{{ data.id }} {
		<# if(data.global_custom_position && data.global_seclect_position){ #>
			<# if(_.isObject(data.global_addon_position_top)) { #>
				top:{{data.global_addon_position_top.' . $size . '}}{{unitTop}};
			<# }

			if(_.isObject(data.global_addon_position_left)) {
			#>
				left:{{data.global_addon_position_left.' . $size . '}}{{unitLeft}};
			<# }
		}
		if(_.isObject(margin)){
		#>
			{{{ margin.' . $size . ' }}}
		<# }
		if(typeof data.use_global_width !== "undefined" && data.use_global_width && typeof data.global_width !== "undefined" && _.isObject(data.global_width)) {
		#>
			width: {{data.global_width.' . $size . '}}%;
		<# } #>
	}
	
	<# if (!_.isEmpty(data.title)){ #>
		#sppb-addon-{{ data.id }} .sppb-addon-title{
			<# if(_.isObject(data.title_fontsize)){ #>
				font-size: {{ data.title_fontsize.' . $size . ' }}px;
				line-height: {{ data.title_fontsize.' . $size . ' }}px;
			<# } #>
			<# if(_.isObject(data.title_lineheight)){ #>
				line-height: {{ data.title_lineheight.' . $size . ' }}px;
			<# } else { #>
				line-height: {{ data.title_lineheight }}px;
			<# } #>
			<# if(_.isObject(data.title_margin_top)){ #>
				margin-top: {{ data.title_margin_top.' . $size . ' }}px;
			<# } #>
			<# if(_.isObject(data.title_margin_bottom)){ #>
				margin-bottom: {{ data.title_margin_bottom.' . $size . ' }}px;
			<# } #>
		}
	<# } #>
	';

	$queryString .= '}';
}

?>

<?php
foreach ($addons_list as $addon)
{
	$addon_name = $addon['addon_name'];
	$addon_name = preg_replace('/^sp_/i', '', $addon['addon_name']);
	$class_name = 'SppagebuilderAddon' . ucfirst($addon_name);

	if (method_exists($class_name, 'getTemplate'))
	{
?>
		<script id="sppb-tmpl-addon-<?php echo $addon_name; ?>" type="x-tmpl-lodash">
			<#
			var addonClass = 'clearfix';
			var addonAttr = '';
			var addonId = 'sppb-addon-'+data.id;
			var addonName = '<?php echo $addon_name; ?>';

			var textColor = data.global_text_color || '';
			var linkColor = data.global_link_color || '';
			var linkHoverColor = data.global_link_hover_color || '';
			var backgroundRepeat = data.global_background_repeat || '';
			var backgroundSize = data.global_background_size || '';
			var backgroundAttachment = data.global_background_attachment || '';
			var backgroundPosition = data.global_background_position || '';
			var modern_font_style = false;
			var title_font_style = data.title_fontstyle || "";

			var backgroundColor = '';
			if(data.global_background_color){
				backgroundColor = data.global_background_color;
			}

			var backgroundImage = '';
			var globalBgImg = {}
			if (typeof data.global_background_image !== "undefined" && typeof data.global_background_image.src !== "undefined") {
				globalBgImg = data.global_background_image
			} else {
				globalBgImg = {src: data.global_background_image}
			}

			if(globalBgImg.src && (globalBgImg.src.indexOf('http://') != -1 || globalBgImg.src.indexOf('https://') != -1)){
				backgroundImage = 'url('+globalBgImg.src+')';
			} else if(globalBgImg.src){
				backgroundImage = 'url('+pagebuilder_base+globalBgImg.src+')';
			}

			var borderWidth = '';

			if (data.global_user_border) {
				if (_.isObject(data.global_border_width)) {
					borderWidth = data.global_border_width[window.builderDefaultDevice]+'px';
				} else {
					borderWidth = data.global_border_width+'px';
				}
			} 
			
			var borderColor = '';
			if(data.global_user_border && data.global_border_color){
				borderColor = data.global_border_color;
			}

			var borderStyle = '';
			if(data.global_user_border && data.global_boder_style){
				borderStyle = data.global_boder_style;
			}

			var borderRadius = data.global_border_radius || '';

			var margin = window.getMarginPadding(data.global_margin, 'margin');
			var padding = window.getMarginPadding(data.global_padding, 'padding');

			if(data.global_use_animation && data.global_animation){
				addonClass += ' sppb-wow '+data.global_animation;

				if(data.global_animationduration){
					addonAttr = ` data-sppb-wow-duration="${data.global_animationduration}ms"`;
				}

				if(data.global_animationdelay){
					addonAttr += ` data-sppb-wow-delay="${data.global_animationdelay}ms"`;
				}
			}

			if(_.isObject(data.global_boxshadow) && !data.global_boxshadow.enabled) {
				boxShadow = '';
			} else if(_.isObject(data.global_boxshadow)){
				var ho = data.global_boxshadow.ho + 'px' || '0px',
					vo = data.global_boxshadow.vo + 'px' || '0px',
					blur = data.global_boxshadow.blur + 'px' || '0px',
					spread = data.global_boxshadow.spread + 'px' || '0px',
					color = data.global_boxshadow.color || '';

				boxShadow = ho + ' ' + vo + ' ' + blur + ' ' + spread  + ' ' + color;
			} else {
				boxShadow = data.global_boxshadow || '';
			}
			
		#>
		<div id="{{ (data.table_advanced_item || data.sp_tab_item || data.sp_accordion_item || addonName === 'div') ? '' : addonId }}" class="{{ addonClass }}" {{{ addonAttr }}} >
			<# if(data.global_use_overlay){ #>
				<div class="sppb-addon-overlayer"></div>
			<# } #>
			<style type="text/css">
				<#
				var unitTop = typeof data.global_addon_position_top !== "undefined" && typeof data.global_addon_position_top.unit !== "undefined" ? data.global_addon_position_top.unit : "px";
				var unitLeft = typeof data.global_addon_position_left !== "undefined" && typeof data.global_addon_position_left.unit !== "undefined" ? data.global_addon_position_left.unit : "px";

				if(data.global_seclect_position == "absolute" || data.global_seclect_position == "fixed"){
				#>
					#sp-page-builder div#sppb-addon-{{ data.id }} { 
						margin: 0;
					}
				<# } #>
				#sp-page-builder div#addon-wrap-{{ data.id }} { 
					<# if (addonName === 'empty_space') { #>
						position: static;
					<#}#>
					<# if(data.global_custom_position && data.global_seclect_position){ #>
						<# if(data.global_seclect_position == "absolute"){ #>
							position:absolute;
						<# } else if(data.global_seclect_position == "fixed"){ #>
							position:fixed;
						<# }
						if(_.isObject(data.global_addon_position_top)) {
						#>
							top:{{data.global_addon_position_top[window.builderDefaultDevice]}}{{unitTop}};
						<# } else { #>
							top:{{data.global_addon_position_top}}{{unitTop}};
						<# }
						if(_.isObject(data.global_addon_position_left)) {
						#>
							left:{{data.global_addon_position_left[window.builderDefaultDevice]}}{{unitLeft}};
						<# } else { #>
							left:{{data.global_addon_position_left}}{{unitLeft}};
						<# }
						if(data.global_addon_z_index) {
						#>
							z-index:{{data.global_addon_z_index}};
						<# }
					} #>
					<# if(_.isObject(margin)){ #>
						{{{ margin[window.builderDefaultDevice] }}}
					<# } else { #>
						{{{ margin }}}
					<# } #>
					<# 
					if(typeof data.use_global_width !== "undefined" && data.use_global_width && typeof data.global_width !== "undefined" && _.isObject(data.global_width)) {
					#>
						width: {{data.global_width[window.builderDefaultDevice]}}%;
					<# } #>
				}

				<# if (addonName === "button" || addonName === "button_group")  { #>
					#{{ addonId }} .sppb-btn {
						<# if (!_.isEmpty(boxShadow)) { #>
							box-shadow: {{ boxShadow }};
						<# } #>
					}
				<# } else {#>
					#{{ addonId }} {
						<# if (!_.isEmpty(boxShadow)) { #>
							box-shadow: {{ boxShadow }};
						<# } #>
					}
				<# } #>

				#{{ addonId }}{
					<# if(!_.isEmpty(textColor)) { #>
					color: {{ textColor }};
					<# } #>
					<# if(typeof data.global_background_type === "undefined" && backgroundColor){ #>
						background-color: {{ backgroundColor }};
					<# } else { #>
						<# if(data.global_background_type == "color" || data.global_background_type == "image" && backgroundColor){ #>
							background-color: {{ backgroundColor }};
						<# } #>
					<# } #>
					<# if(data.global_background_type == "gradient" && _.isObject(data.global_background_gradient)){ #>
						<# if(typeof data.global_background_gradient.type !== 'undefined' && data.global_background_gradient.type == 'radial'){ #>
							background-image: radial-gradient(at {{ data.global_background_gradient.radialPos || 'center center'}}, {{ data.global_background_gradient.color }} {{ data.global_background_gradient.pos || 0 }}%, {{ data.global_background_gradient.color2 }} {{ data.global_background_gradient.pos2 || 100 }}%);
						<# } else { #>
							background-image: linear-gradient({{ data.global_background_gradient.deg || 0}}deg, {{ data.global_background_gradient.color }} {{ data.global_background_gradient.pos || 0 }}%, {{ data.global_background_gradient.color2 }} {{ data.global_background_gradient.pos2 || 100 }}%);
						<# } #>
					<# } #>
					<# if(typeof data.global_background_type === "undefined" ){ #>
						<# if(data.global_use_background){ #>
							background-image: {{ backgroundImage }};
							background-repeat: {{ backgroundRepeat }};
							background-size: {{ backgroundSize }};
							background-attachment: {{ backgroundAttachment }};
							background-position: {{ backgroundPosition }};
						<# } #>
					<# } else { #>
						<# if(data.global_background_type == "image" && backgroundImage){ #>
							background-image: {{ backgroundImage }};
							background-repeat: {{ backgroundRepeat }};
							background-size: {{ backgroundSize }};
							background-attachment: {{ backgroundAttachment }};
							background-position: {{ backgroundPosition }};
						<# } #>
					<# } #>
					<# if(_.isObject(borderRadius)){ #>
						<# if (!_.isEmpty(borderRadius[window.builderDefaultDevice])) {#>
							border-radius: {{ borderRadius[window.builderDefaultDevice] }}px;
						<# } #>
					<# } else { #>
						<# if (!_.isEmpty(borderRadius)){ #>
							border-radius: {{ borderRadius }}px;
						<# } #>
					<# } #>
					<# if(_.isObject(padding)) { #>
						{{{ padding[window.builderDefaultDevice] }}}
					<# } else { #>
						{{{ padding }}}
					<# } #>
					<# if (!_.isEmpty(borderWidth)) { #>
						border-width: {{ borderWidth }};
					<# } #>
					<# if (!_.isEmpty(borderColor)) { #>
						border-color: {{ borderColor }};
					<# } #>
					<# if (!_.isEmpty(borderStyle)) { #>
						border-style: {{ borderStyle }};
					<# } #>
					
					<# if(data.global_use_overlay){ #>
						position: relative;
						overflow: hidden;
					<# } #>
				}
				<# if(data.global_use_overlay){ #>
					#{{ addonId }} .sppb-addon-overlayer{

						<# if(typeof data.global_overlay_type == "undefined"){
							data.global_overlay_type = "overlay_color";
						} #>
						<# if(data.global_overlay_type == "overlay_color") { #>
							background-color: {{ data.global_background_overlay }};
						<# }

						if(data.global_background_type == "image" && backgroundImage){
							if(data.global_overlay_type == "overlay_gradient" && _.isObject(data.global_gradient_overlay)){
								if(typeof data.global_gradient_overlay.type !== 'undefined' && data.global_gradient_overlay.type == 'radial'){
						#>
									background: radial-gradient(at {{ data.global_gradient_overlay.radialPos || 'center center'}}, {{ data.global_gradient_overlay.color }} {{ data.global_gradient_overlay.pos || 0 }}%, {{ data.global_gradient_overlay.color2 }} {{ data.global_gradient_overlay.pos2 || 100 }}%);
								<# } else { #>
									background: linear-gradient({{ data.global_gradient_overlay.deg || 0}}deg, {{ data.global_gradient_overlay.color }} {{ data.global_gradient_overlay.pos || 0 }}%, {{ data.global_gradient_overlay.color2 }} {{ data.global_gradient_overlay.pos2 || 100 }}%);
								<# }
							}
							let patternImgBg = {}
							if (typeof data.global_pattern_overlay !== "undefined" && typeof data.global_pattern_overlay.src !== "undefined") {
								patternImgBg = data.global_pattern_overlay
							} else {
								patternImgBg = {src: data.global_pattern_overlay}
							}
							if(patternImgBg.src && data.global_overlay_type == "overlay_pattern"){
								let patternImg = '';
								if(patternImgBg.src && (patternImgBg.src.indexOf('http://') != -1 || patternImgBg.src.indexOf('https://') != -1)){
									patternImg = 'url('+patternImgBg.src+')';
								} else if(patternImgBg.src){
									patternImg = 'url('+pagebuilder_base+patternImgBg.src+')';
								}
							#>
								background-image:{{patternImg}};
								background-attachment: scroll;
								<# if(!_.isEmpty(data.global_overlay_pattern_color)){ #>
									background-color:{{data.global_overlay_pattern_color}};
								<# }
							}
						} #>
						position: absolute;
						top: 0;
						left: 0;
						width: 100%;
						height: 100%;
						z-index: 0;
						<# if(data.global_background_type == "image" && backgroundImage){ #>
							<# if (data.blend_mode) { #>
								mix-blend-mode:{{data.blend_mode}};
							<# } #>
						<# } #>
					}

					#{{ addonId }} > .sppb-addon{
						position: relative;
					}
				<# } #>
				#{{ addonId }} a{
					color: {{ linkColor }};
				}
				#{{ addonId }} a:hover,
				#{{ addonId }} a:focus,
				#{{ addonId }} a:active{
					color: {{ linkHoverColor }};
				}
				<# if (!_.isEmpty(data.title)){ #>
					#sppb-addon-{{ data.id }} .sppb-addon-title{
						<# if(_.isObject(data.title_fontsize)){ #>
							font-size: {{ data.title_fontsize[window.builderDefaultDevice] }}px;
							line-height: {{ data.title_fontsize[window.builderDefaultDevice] }}px;
						<# } else { #>
							font-size: {{ data.title_fontsize }}px;
							line-height: {{ data.title_fontsize }}px;
						<# } #>
						<# if(_.isObject(data.title_lineheight)){ #>
							line-height: {{ data.title_lineheight[window.builderDefaultDevice] }}px;
						<# } else { #>
							line-height: {{ data.title_lineheight }}px;
						<# }
						if(data.title_letterspace !== "custom") {
						#> 
							letter-spacing: {{ data.title_letterspace }};
						<# } else { #>
							letter-spacing: {{ data.custom_letterspacing }};
						<# } #>

						font-weight: {{ data.title_fontweight }};
						color: {{ data.title_text_color }};
						<# if(_.isObject(data.title_margin_top)){ #>
							margin-top: {{ data.title_margin_top[window.builderDefaultDevice] }}px;
						<# } else { #>
							margin-top: {{ data.title_margin_top }}px;
						<# } #>
						<# if(_.isObject(data.title_margin_bottom)){ #>
							margin-bottom: {{ data.title_margin_bottom[window.builderDefaultDevice] }}px;
						<# } else { #>
							margin-bottom: {{ data.title_margin_bottom }}px;
						<# } #>

						<# if(_.isObject(data.title_font_style) && data.title_font_style.underline) { #>
							text-decoration: underline;
							<# modern_font_style = true #>
						<# } #>

						<# if(_.isObject(data.title_font_style) && data.title_font_style.italic) { #>
							font-style: italic;
							<# modern_font_style = true #>
						<# } #>

						<# if(_.isObject(data.title_font_style) && data.title_font_style.uppercase) { #>
							text-transform: uppercase;
							<# modern_font_style = true #>
						<# } #>

						<# if(_.isObject(data.title_font_style) && data.title_font_style.weight) { #>
							font-weight: {{ data.title_font_style.weight }};
							<# modern_font_style = true #>
						<# } #>

						<# if(!modern_font_style) { #>
							<# if(_.isArray(title_font_style)) { #>
								<# if(title_font_style.indexOf("underline") !== -1){ #>
									text-decoration: underline;
								<# } #>
								<# if(title_font_style.indexOf("uppercase") !== -1){ #>
									text-transform: uppercase;
								<# } #>
								<# if(title_font_style.indexOf("italic") !== -1){ #>
									font-style: italic;
								<# } #>
								<# if(title_font_style.indexOf("lighter") !== -1){ #>
									font-weight: lighter;
								<# } else if(title_font_style.indexOf("normal") !== -1){#>
									font-weight: normal;
								<# } else if(title_font_style.indexOf("bold") !== -1){#>
									font-weight: bold;
								<# } else if(title_font_style.indexOf("bolder") !== -1){#>
									font-weight: bolder;
								<# } #>
							<# } #>
						<# } #>
					}
				<# } #>

				<?php echo $queryString; ?>
			</style>
			<?php echo $class_name::getTemplate(); ?>
		</div>
		</script>
<?php
	}
}
?>

<script type="text/javascript" src="<?php echo Uri::base(true) . '/components/com_sppagebuilder/assets/js/vendors.js?' . SppagebuilderHelperSite::getVersion(true); ?>"></script>
<script type="text/javascript" src="<?php echo Uri::base(true) . '/components/com_sppagebuilder/assets/js/engine.js?' . SppagebuilderHelperSite::getVersion(true); ?>"></script>