<?php

/**
 * @package SP Page Builder
 * @author JoomShaper https://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

use Joomla\CMS\Uri\Uri;

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Helper class for handling the lodash string.
 *
 * @since	4.0.0
 */
final class Lodash extends HelperBase
{
	/**
	 * Device Size
	 *
	 * @var array
	 */
	private $sizes = ["lg", "md", "sm", "xs"];
	/**
	 * The constructor function for the lodash.
	 *
	 * @param string $id 	The unique CSS ID.
	 * @param bool $force	Flag to set the ID as whatever it is provided, no sanitization is required.
	 */
	public function __construct(string $id = '', bool $force = false)
	{
		parent::__construct($id, $force);
	}

	/**
	 * Manage border style css property
	 *
	 * @param 	string 	$property	CSS Property.
	 * @param 	string 	$selector	CSS Selector.
	 * @param 	string 	$data		Value for CSS Property.
	 * @param 	string 	$static		Provided any static style value. If provided that would be contacted.
	 * 
	 * @return 	string 	CSS Value for Border.
	 * @since 	4.0.0
	 */
	public function border(string $property, string $selector, string $data, $static = ''): string
	{
		$selector = $this->generateSelector($selector);

		$css   = [];
		$css[] = $selector . ' {';
		if (!empty($static))
		{
			$css[] = $static;
		}
		$css[] = '<# if(_.isObject(' . $data . ') && !_.isEmpty(' . $data . ')) { #>';
		$css[] = $property . ': ' . $this->inlineBlock($data . '[window.builderDefaultDevice]') . ';';
		$css[] = '<# } else { #>';
		$css[] = $property . ': ' . $this->inlineBlock($data) . ';';
		$css[] = '<# } #>';
		$css[] = '}';

		return implode("\n", $css);
	}

	/**
	 * manage alignment css
	 *
	 * @param	string 	$property
	 * @param 	string 	$selector
	 * @param 	string 	$data
	 * 
	 * @return 	string
	 * @since 	4.0.0
	 */
	public function alignment(string $property, string $selector, string $data): string
	{
		$selector = $this->generateSelector($selector);
		$css = [];

		if (isset($property) && isset($selector) && isset($data))
		{
			$css[] = $selector . ' {';
			$css[] = '<# if(_.isObject(' . $data . ') && !_.isEmpty(' . $data . ')) { #>';
			$css[] = $property . ': ' . $this->inlineBlock($data . '[window.builderDefaultDevice]') . ';';
			$css[] = '<# } else { #>';
			$css[] = '<# if ( ' . $data . '== "sppb-text-center" ||  ' . $data . '== "center" ) { #>';
			$css[] = $property . ': center;';
			$css[] = '<# } else if ( ' . $data . '== "sppb-text-right" || ' . $data . '== "right" ) { #>';
			$css[] = $property . ': right;';
			$css[] = '<# } else if ( ' . $data . '== "sppb-text-left" || ' . $data . '== "left") { #>';
			$css[] = $property . ': left;';
			$css[] = '<# } #>';
			$css[] = '<# } #>';
			$css[] = '}';

			// render media query css
			$css[] = '<# if(_.isObject(' . $data . ') && !_.isEmpty(' . $data . ')) { #>';
			$css[] = $this->mediaQuery($property, $selector, $data);
			$css[] = '<# } #>';
		}

		return implode("\n", $css);
	}

	/**
	 * Flex Alignment function
	 *
	 * @param 	string 	$selector
	 * @param 	string 	$data
	 * 
	 * @return 	string
	 * @since 	4.0.0
	 */
	public function flexAlignment(string $selector, string $data): string
	{
		$selector = $this->generateSelector($selector);
		$css = [];

		$css[] = $selector . ' {';
		$css[] = '<# if(_.isObject(' . $data . ') && !_.isEmpty(' . $data . ')) { #>';
		$css[] = '<# if (' . $data . '[window.builderDefaultDevice] == "left") { #>';
		$css[] = '-webkit-box-pack: start;';
		$css[] = '-ms-flex-pack: start;';
		$css[] = 'justify-content: flex-start;';
		$css[] = '<# } else if (' . $data . '[window.builderDefaultDevice] == "right") { #>';
		$css[] = '-webkit-box-pack: end;';
		$css[] = '-ms-flex-pack: end;';
		$css[] = 'justify-content: flex-end;';
		$css[] = '<# } else if (' . $data . '[window.builderDefaultDevice] == "center") { #>';
		$css[] = '-webkit-box-pack: center;';
		$css[] = '-ms-flex-pack: center;';
		$css[] = 'justify-content: center;';
		$css[] = '<# } #>';
		$css[] = '<# } else {#>';
		$css[] = '<# if (' . $data . '== "left" || ' . $data . ' == "sppb-text-left")  { #>';
		$css[] = '-webkit-box-pack: start;';
		$css[] = '-ms-flex-pack: start;';
		$css[] = 'justify-content: flex-start;';
		$css[] = '<# } else if (' . $data . '== "right" || ' . $data . ' == "sppb-text-right") { #>';
		$css[] = '-webkit-box-pack: end;';
		$css[] = '-ms-flex-pack: end;';
		$css[] = 'justify-content: flex-end;';
		$css[] = '<# } else if (' . $data . '== "center" || ' . $data . ' == "sppb-text-center") { #>';
		$css[] = '-webkit-box-pack: center;';
		$css[] = '-ms-flex-pack: center;';
		$css[] = 'justify-content: center;';
		$css[] = '<# } #>';
		$css[] = '<# } #>';
		$css[] = '}';

		foreach ($this->sizes as $size)
		{
			$css[] = $this->mediaQueryDevice($size);
			$css[] = $selector . ' {';
			$css[] = '<# if (' . $data . '?.' . $size . ' == "left") { #>';
			$css[] = '-webkit-box-pack: start;';
			$css[] = '-ms-flex-pack: start;';
			$css[] = 'justify-content: flex-start;';
			$css[] = '<# } else if (' . $data . '?.' . $size . ' == "right") { #>';
			$css[] = '-webkit-box-pack: end;';
			$css[] = '-ms-flex-pack: end;';
			$css[] = 'justify-content: flex-end;';
			$css[] = '<# } else if (' . $data . '?.' . $size . ' == "center") { #>';
			$css[] = '-webkit-box-pack: center;';
			$css[] = '-ms-flex-pack: center;';
			$css[] = 'justify-content: center;';
			$css[] = '<# } #>';
			$css[] = '}';
			$css[] = '}';
		}

		return implode("\n", $css);
	}

	/**
	 * Flex function
	 *
	 * @param 	string 	$property
	 * @param 	string 	$selector
	 * @param 	string 	$data
	 * @param 	string 	$unit
	 * 
	 * @return 	string
	 * @since 	4.0.0
	 */
	public function flex(string $property, string $selector, string $data, $unit = ''): string
	{

		$selector = $this->generateSelector($selector);

		$css   = [];
		$css[] = $selector . ' {';
		$css[] = '<# if (_.isObject(' . $data . ') && !_.isEmpty(' . $data . ') ) { #>';
		$css[] = $property . ': 0 0 ' . $this->inlineBlock($data . '?.[window.builderDefaultDevice]') . $unit . ';';
		$css[] = '<# } else { #>';
		$css[] = $property . ': 0 0 ' . $this->inlineBlock($data) . $unit . ';';
		$css[] = '<# } #>';
		$css[] = '}';

		foreach ($this->sizes as $size)
		{
			$css[] = $this->mediaQueryDevice($size);
			$css[] = $selector . ' {';
			$css[] = '<# if (_.isObject(' . $data . ') && !_.isEmpty(' . $data . ') ) { #>';
			$css[] = $property . ': 0 0 ' . $this->inlineBlock($data . '?.' . $size) . $unit . ';';
			$css[] = '<# } else { #>';
			$css[] = $property . ': 0 0 ' . $this->inlineBlock($data) . $unit . ';';
			$css[] = '<# } #>';
			$css[] = '}';
			$css[] = '}';
		}
		return implode("\n", $css);
	}

	/**
	 * Manage spacing property
	 *
	 * @param 	string  $property 	CSS property name.
	 * @param 	string  $selector 	CSS selector name.
	 * @param 	string  $data		CSS value.
	 * @param 	boolean $isStatic 	Check value is static.
	 * 
	 * @return 	string 				The generated spacing string.
	 * @since 	4.0.0
	 */
	public function spacing(string $property, string $selector, string $data, $isStatic = false): string
	{
		$selector = $this->generateSelector($selector);

		$css   = [];
		$css[] = '
		<#
		var value = window.getMarginPadding( ' . $data . ', `' . $property . '`);
		
		#>
		';
		$css[] = $selector . ' {';

		if ($isStatic)
		{
			$css[] = $property . ': ' . $data . ';';
		}
		else
		{
			$css[] = '<# if(_.isObject(value) && !_.isEmpty(value)) { #>';
			$css[] =  $this->inlineBlock('value?.[window.builderDefaultDevice]');
			$css[] = '<# } else { #>';
			$css[] = $this->inlineBlock('value');
			$css[] = '<# } #>';
		}

		$css[] = '}';

		foreach ($this->sizes as $size)
		{
			$css[] = $this->mediaQueryDevice($size);
			$css[] = $selector . ' {';
			$css[] = '<# if(_.isObject(value) && !_.isEmpty(value)) { #>';
			$css[] =  $this->inlineBlock('value?.' . $size);
			$css[] = '<# } else { #>';
			$css[] = $this->inlineBlock('value');
			$css[] = '<# } #>';
			$css[] = '}';
			$css[] = '}'; // end selector
		}

		return implode("\n", $css);
	}

	/**
	 * manage unit
	 *
	 * @param	string 	$property 
	 * @param	string 	$selector
	 * @param	string 	$data
	 * @param	string 	$unit
	 * @param	boolean $responsive
	 * @param	string 	$prefix
	 * @param	string 	$static	Provided any static style value. If provided that would be contacted.
	 * 
	 * @return	string
	 * @since 4.0.0
	 */
	public function unit(string $property, string $selector, string $data, $unit = '', $responsive = true, $prefix = '', $static = ''): string
	{
		$selector = $this->generateSelector($selector);

		$css      = [];
		$css[]    = $selector . ' {';
		if (!empty($static))
		{
			$css[] = $static;
		}
		$css[]    = '<# if(_.isObject(' . $data . ') && !_.isEmpty(' . $data . ')) { #>';
		$css[]    = $property . ': ' . $prefix . $this->inlineBlock($data . '?.[window.builderDefaultDevice]') . $unit . ';';
		$css[]    = '<# } else { #>';
		$css[]    = $property . ': ' . $prefix . $this->inlineBlock($data) . $unit . ';';
		$css[]    = '<# } #>';
		$css[]    = '}';

		if ($responsive)
		{
			$css[] = '<# if(_.isObject(' . $data . ') && !_.isEmpty(' . $data . ')) { #>';
			$css[] = $this->mediaQuery($property, $selector, $data, $unit, false, $prefix);
			$css[] = '<# } #>';
		}

		return implode("\n", $css);
	}

	/**
	 * manage color
	 *
	 * @param 	string 	$property
	 * @param 	string 	$selector
	 * @param 	string 	$data
	 * @param 	array 	$dependencies
	 * 
	 * @return 	string
	 * @since 	4.0.0
	 */
	public function color(string $property, string $selector, string $data, $dependencies = []): string
	{
		$selector = $this->generateSelector($selector);

		$css = [];
		$css[]    = $selector . ' {';

		if ($property === 'background-color' || $property === 'background')
		{
			$css[] = '<# if (!_.isEmpty(' . $data . ')) { #>';
			$css[] = '<# if (_.isObject(' . $data . ') && ' . $data . '?.type == "solid") { #>';
			$css[] = 'background-color: ' . $this->inlineBlock($data . '.color') . ';';
			$css[] = '<# } else if (_.isObject(' . $data . ') && ' . $data . '?.type == "linear") { #>';
			$css[] = 'background: -webkit-linear-gradient(' . $this->inlineBlock($data . '?.deg || 0') . 'deg, ' .  $this->inlineBlock($data . '?.color || "#222"') . ' ' . $this->inlineBlock($data . '?.pos || 0') . '%, ' . $this->inlineBlock($data . '?.color2 || "#222"') . ' ' . $this->inlineBlock($data . '?.pos2 || 100') . '%' . ');';
			$css[] = 'background: linear-gradient(' . $this->inlineBlock($data . '?.deg || 0') . 'deg, ' .  $this->inlineBlock($data . '?.color || "#222"') . ' ' . $this->inlineBlock($data . '?.pos || 0') . '%, ' . $this->inlineBlock($data . '?.color2 || "#222"') . ' ' . $this->inlineBlock($data . '?.pos2 || 100') . '%' . ');';
			$css[] = '<# } else if (_.isObject(' . $data . ') && ' . $data . '?.type == "radial") { #>';
			$css[] = 'background: -webkit-radial-gradient(at ' . $this->inlineBlock($data . '?.radialPos || "center center"') . ', ' .  $this->inlineBlock($data . '?.color || "#222"') . ' ' . $this->inlineBlock($data . '?.pos || 0') . '%, ' . $this->inlineBlock($data . '?.color2 || "#222"') . ' ' . $this->inlineBlock($data . '?.pos2 || 100') . '%' . ');';
			$css[] = 'background: radial-gradient(at ' . $this->inlineBlock($data . '?.radialPos || "center center"') . ', ' .  $this->inlineBlock($data . '?.color || "#222"') . ' ' . $this->inlineBlock($data . '?.pos || 0') . '%, ' . $this->inlineBlock($data . '?.color2 || "#222"') . ' ' . $this->inlineBlock($data . '?.pos2 || 100') . '%' . ');';
			$css[] = '<# } else { #>';
			$css[] = 'background-color: ' . $this->inlineBlock($data) . ';';
			$css[] = '<# } #>';
			$css[] = '<# } #>';
		}
		else if ($property == 'color')
		{
			$css[] = '<# if (!_.isEmpty(' . $data . ')) { #>';
			$css[] = '<# if (_.isObject(' . $data . ') && ' . $data . '?.type == "solid") { #>';
			$css[] = 'color: ' . $this->inlineBlock($data . '.color') . ';';
			$css[] = '<# } else if (_.isObject(' . $data . ') && ' . $data . '?.type == "linear") { #>';
			$css[] = '-webkit-background-clip: text;';
			$css[] = 'background-clip: text;';
			$css[] = '-webkit-text-fill-color: transparent;';
			$css[] = 'background-image: -webkit-linear-gradient(' . $this->inlineBlock($data . '?.deg || 0') . 'deg, ' .  $this->inlineBlock($data . '?.color || "#398AF1"') . ' ' . $this->inlineBlock($data . '?.pos || 0') . '%, ' . $this->inlineBlock($data . '?.color2 || "#5EDCED"') . ' ' . $this->inlineBlock($data . '?.pos2 || 100') . '%' . ');';
			$css[] = 'background-image: linear-gradient(' . $this->inlineBlock($data . '?.deg || 0') . 'deg, ' .  $this->inlineBlock($data . '?.color || "#398AF1"') . ' ' . $this->inlineBlock($data . '?.pos || 0') . '%, ' . $this->inlineBlock($data . '?.color2 || "#5EDCED"') . ' ' . $this->inlineBlock($data . '?.pos2 || 100') . '%' . ');';
			$css[] = '<# } else if (_.isObject(' . $data . ') && ' . $data . '?.type == "radial") { #>';
			$css[] = '-webkit-background-clip: text;';
			$css[] = 'background-clip: text;';
			$css[] = '-webkit-text-fill-color: transparent;';
			$css[] = 'background-image: -webkit-radial-gradient(at ' . $this->inlineBlock($data . '?.radialPos || "center center"') . ', ' .  $this->inlineBlock($data . '?.color || "#398AF1"') . ' ' . $this->inlineBlock($data . '.pos || 0') . '%, ' . $this->inlineBlock($data . '?.color2 || "#5EDCED"') . ' ' . $this->inlineBlock($data . '.pos2 || 100') . '%' . ');';
			$css[] = 'background-image: radial-gradient(at ' . $this->inlineBlock($data . '?.radialPos || "center center"') . ', ' .  $this->inlineBlock($data . '?.color || "#398AF1"') . ' ' . $this->inlineBlock($data . '.pos || 0') . '%, ' . $this->inlineBlock($data . '?.color2 || "#5EDCED"') . ' ' . $this->inlineBlock($data . '.pos2 || 100') . '%' . ');';
			$css[] = '<# } else { #>';
			$css[] = 'color: ' . $this->inlineBlock($data) . ';';
			$css[] = '<# } #>';
			$css[] = '<# } #>';
		}

		$css[] = ' }';

		return implode("\n", $css);
	}

	/**
	 * Load google or local fonts
	 *
	 * @param	string 		$data
	 * @param	boolean 	$isFallbacks
	 * 
	 * @return 	string
	 * @since 	4.0.0
	 */
	private function loadFonts(string $data, $isFallbacks = false): string
	{
		$content = ($isFallbacks) ? $data . ';' : $data . '.font;';

		$installedGoogleFonts = SppagebuilderHelperSite::getInstalledGoogleFonts();

		$css  = '<# var sppb_head = window.frames["sp-pagebuilder-view"].window.document.head; #>';
		$css .= '<# var sppb_doc  = window.frames["sp-pagebuilder-view"].window.document;#>';
		$css .= '<# var sppb_fontFamily = ' . $content . ' #>';
		$css .= '<# const sppb_fontType = ' . $data . '?.type' . ' ?? "google" #>';
		$css .= '<# const installed_google_fonts = ' . json_encode($installedGoogleFonts) . ' #>';

		$css .= '<#
	
				const systemFonts = [
					"System",
					"Google Fonts",
					"Arial",
					"Tahoma",
					"Verdana",
					"Helvetica",
					"Times New Roman",
					"Trebuchet MS",
					"Georgia",
				];

				if (!_.isEmpty(sppb_fontFamily) && !systemFonts.includes(sppb_fontFamily) && !_.isEmpty(sppb_fontType) && (sppb_fontType !== "google" || (sppb_fontType === "google" && !disableGoogleFonts))){
					let linkTagIdPrefix = "sppagebuilder-google-font-";

					let fontLink = sppb_head.querySelector(
						`#${linkTagIdPrefix}${sppb_fontFamily.toLowerCase().replace(/\s+/g, "_")}`
					);

					linkTagLink = `' . Uri::root() . 'media/com_sppagebuilder/assets/google-fonts/${sppb_fontFamily}/stylesheet.css`;

					if(sppb_fontType === "google") {
						const isFontInstalled = installed_google_fonts?.includes(sppb_fontFamily);

						if(!isFontInstalled) {
							linkTagLink = `https://fonts.googleapis.com/css?family=${sppb_fontFamily}:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&display=swap`;
						}
					}

					if(sppb_fontType === "local") {
						linkTagIdPrefix = "sppagebuilder-local-font-";
						fontLink = sppb_head.querySelector(
							`#${linkTagIdPrefix}${sppb_fontFamily.toLowerCase().replace(/\s+/g, "_")}`
						);

						linkTagLink = `' . Uri::root() . 'media/com_sppagebuilder/assets/custom-fonts/${sppb_fontFamily}/stylesheet.css`;
					}

					if (!fontLink) {
						const fontLink = sppb_doc.createElement("link");
						fontLink.id = linkTagIdPrefix + sppb_fontFamily.toLowerCase().replace(/\s+/g, "_");
						fontLink.href = linkTagLink;
						fontLink.rel  = "stylesheet";
						fontLink.type = "text/css";
						sppb_head.appendChild(fontLink);
					}
				} #>';

		return $css;
	}

	/**
	 * Set css property with unit
	 *
	 * @param	string 		$data
	 * @param	string 		$property
	 * @param	string 		$key
	 * 
	 * @return 	string
	 * @since 	4.0.0
	 */
	private function setCssPropertyWithUnit(string $data, string $property, string $key): string
	{
		$css = [];

		$regex = '/\d+(px|em|rem|%)$/';
		$content = $data . '.' . $key . '[window.builderDefaultDevice].value';
		$condition = preg_match($regex, $this->inlineBlock($content)) ? false : true;

		$css[] = '<# if ( _.isObject(' . $data . '.' . $key . ') && ' . $data . '.' . $key . '[window.builderDefaultDevice].value && ' . $data . '.' . $key . '[window.builderDefaultDevice].unit) { #>';
		$css[] = $property . ': ' . $this->inlineBlock($content) . $this->inlineBlockWithCondition($condition, $data . '.' . $key . '[window.builderDefaultDevice].unit') . ';';
		$css[] = '<# } #>';

		return implode("\n", $css);
	}

	/**
	 * Manage typography
	 *
	 * @param 	string 	$selector
	 * @param 	string 	$data
	 * @param 	array 	$fallbacks
	 * 
	 * @return 	string
	 * @since 	4.0.0
	 */
	public function typography(string $selector, string $data,  array $fallbacks = []): string
	{
		$selector = $this->generateSelector($selector);

		$css = [];

		$font           = (!empty($fallbacks) && array_key_exists('font', $fallbacks)) ? $fallbacks['font'] : "undefined";
		$size           = (!empty($fallbacks) && array_key_exists('size', $fallbacks)) ? $fallbacks['size'] : "undefined";
		$line_height    = (!empty($fallbacks) && array_key_exists('line_height', $fallbacks)) ? $fallbacks['line_height'] : "undefined";
		$letter_spacing = (!empty($fallbacks) && array_key_exists('letter_spacing', $fallbacks)) ? $fallbacks['letter_spacing'] : "undefined";
		$custom_letter_spacing = (!empty($fallbacks) && array_key_exists('custom_letter_spacing', $fallbacks)) ? $fallbacks['custom_letter_spacing'] : "undefined";
		$uppercase      = (!empty($fallbacks) && array_key_exists('uppercase', $fallbacks)) ? $fallbacks['uppercase'] : "undefined";
		$italic         = (!empty($fallbacks) && array_key_exists('italic', $fallbacks)) ? $fallbacks['italic'] : "undefined";
		$underline      = (!empty($fallbacks) && array_key_exists('underline', $fallbacks)) ? $fallbacks['underline'] : "undefined";
		$weight         = (!empty($fallbacks) && array_key_exists('weight', $fallbacks)) ? $fallbacks['weight'] : "undefined";

		$css[] = '<# if (!_.isEmpty(' . $data . ') && _.isObject(' . $data . ') ) { #>';

		$css[] = $this->loadFonts($data);

		$css[] = $selector . ' {';

		$css[] = '<# if ( typeof ' . $data . '.font !== "undefined" &&  ' . $data . '.font ) { #>';
		$css[] = 'font-family: "' . $this->inlineBlock($data . '.font') . '";';
		$css[] = '<# } #>';

		$css[] = '<# if ( typeof ' . $data . '.underline !== "undefined" &&  ' . $data . '.underline ) { #>';
		$css[] = 'text-decoration: underline;';
		$css[] = '<# } else {#>';
		$css[] = 'text-decoration: none;';
		$css[] = '<# } #>';

		$css[] = '<# if ( typeof ' . $data . '.italic !== "undefined" &&  ' . $data . '.italic ) { #>';
		$css[] = 'font-style: italic;';
		$css[] = '<# } else {#>';
		$css[] = 'font-style: normal;';
		$css[] = '<# } #>';

		$css[] = '<# if ( typeof ' . $data . '.uppercase !== "undefined" && ' . $data . '.uppercase) { #>';
		$css[] = 'text-transform: uppercase;';
		$css[] = '<# } #>';

		$css[] = '<# if ( ' . $data . '.weight) { #>';
		$css[] = 'font-weight: ' . $this->inlineBlock($data . '.weight') . ';';
		$css[] = '<# } #>';

		$css[] = $this->setCssPropertyWithUnit($data, 'font-size', 'size');
		$css[] = $this->setCssPropertyWithUnit($data, 'letter-spacing', 'letter_spacing');

		$css[] = '<# if ( _.isObject(' . $data . '.line_height) && ' . $data . '.line_height[window.builderDefaultDevice] && ' . $data . '.line_height[window.builderDefaultDevice].value && ' . $data . '.line_height[window.builderDefaultDevice].unit) { #>';
		$css[] = 'line-height: ' . $this->inlineBlock($data . '.line_height[window.builderDefaultDevice].value') . $this->inlineBlock($data . '.line_height[window.builderDefaultDevice].unit') . ';';
		$css[] = '<# } #>';

		$css[] = '}';


		$css[] = $this->mediaQuery('font-size', $selector, $data . '.size', '', true);
		$css[] = $this->mediaQuery('letter-spacing', $selector, $data . '.letter_spacing', '', true);
		$css[] = $this->mediaQuery('line-height', $selector, $data . '.line_height', '', true);


		$css[] = '<# } else { #>';

		$css[] = $this->loadFonts($font, true);

		// Fallback CSS
		$css[] = $selector . ' {';

		$css[] = '<# if ( typeof ' . $font . ' !== "undefined" &&  ' . $font . ' ) { #>';
		$css[] = 'font-family:"' . $this->inlineBlock($font) . '";';
		$css[] = '<# } #>';

		$css[] = '<# if(typeof ' . $size . ' !== "undefined" && _.isObject(' . $size . ') && !_.isString(' . $size . ') && !_.isEmpty(' . $size . ')) { #>';
		$css[] = 'font-size: ' . $this->inlineBlock($size . '[window.builderDefaultDevice]') . 'px;';
		$css[] = '<# } else if(typeof ' . $size . ' !== "undefined" && !_.isEmpty(' . $size . ')){ #>';
		$css[] = 'font-size: ' . $this->inlineBlock($size) . 'px;';
		$css[] = '<# } #>';

		$css[] = '<# if ( typeof ' . $line_height . ' !== "undefined" &&  ' . $line_height . ' ) { #>';
		$css[] = 'line-height: ' . $this->inlineBlock($line_height . '[window.builderDefaultDevice]') . 'px;';
		$css[] = '<# } else if(typeof ' . $size . ' !== "undefined" && !_.isEmpty(' . $size . ')){ #>';
		$css[] = 'line-height: ' . $this->inlineBlock($line_height) . 'px;';
		$css[] = '<# } #>';

		$css[] = '<# if ( typeof ' . $letter_spacing . ' !== "undefined" &&  ' . $letter_spacing . ' ) { #>';
		$css[] = '<# if (' . $letter_spacing . ' == "custom") {#>';
		$css[] = 'letter-spacing: ' . $this->inlineBlock($custom_letter_spacing) . ';';
		$css[] = '<# } else {#>';
		$css[] = 'letter-spacing: ' . $this->inlineBlock($letter_spacing) . ';';
		$css[] = '<# } #>';
		$css[] = '<# } #>';

		$css[] = '<# if ( typeof ' . $uppercase  . ' !== "undefined" &&  ' . $uppercase . ' ) { #>';
		$css[] = 'text-transform: uppercase;';
		$css[] = '<# } #>';

		$css[] = '<# if ( typeof ' . $italic  . ' !== "undefined" &&  ' . $italic  . ' ) { #>';
		$css[] = 'font-style: italic;';
		$css[] = '<# } #>';

		$css[] = '<# if ( typeof ' . $underline   . ' !== "undefined" &&  ' . $underline  . ' ) { #>';
		$css[] = 'text-decoration: "' . $this->inlineBlock($underline) . '";';
		$css[] = '<# } #>';

		$css[] = '<# if ( typeof ' . $weight . ' !== "undefined" &&  ' . $weight . ' ) { #>';
		$css[] = 'font-weight: ' . $this->inlineBlock($weight) . ';';
		$css[] = '<# } #>';

		$css[] = '}';

		$css[] = '<# } #>';


		return implode("\n", $css);
	}

	/**
	 * transform
	 *
	 * @param 	string 	$property
	 * @param 	string 	$selector
	 * @param 	string 	$data
	 * @return 	string
	 * @since 	4.0.0
	 */
	public function transform(string $property, string $selector, string $data, string $unit = ''): string
	{
		$selector = $this->generateSelector($selector);

		$css = [];
		$css[]    = '<# if(!_.isEmpty(' . $data . ')) { #>';
		$css[]    = $selector . ' {';
		$css[]    = '-webkit-transform: ' . $property . '(' . $this->inlineBlock($data) . $unit . ');';
		$css[]    = 'transform: ' . $property . '(' . $this->inlineBlock($data) . $unit . ');';
		$css[]    = '}';
		$css[]    = '<# } #>';

		return implode("\n", $css);
	}

	/**
	 * adjustment function
	 *
	 * @param 	string 	$property
	 * @param 	string 	$selector
	 * @param 	string 	$prefix
	 * @param 	string 	$data
	 * @param 	string 	$unit
	 * 
	 * @return 	void
	 * @since 	4.0.0
	 */
	public function adjustment(string $property, string $selector, string $prefix, string $data, string $unit)
	{
		$selector = $this->generateSelector($selector);

		$css = [];
		$css[]    = '<# if(!_.isEmpty(' . $data . ')) { #>';
		$css[]    = $selector . ' {';
		$css[]    = '<# if(_.isObject(data.' . $data . ') ) { #>';
		$css[]    = $property . ':' . $prefix . '(' . $this->inlineBlock($data) . $unit . ');';
		$css[]    = '}';
		$css[]    = '<# } #>';

		return implode("\n", $css);
	}


	/**
	 * box shadow
	 *
	 * @param 	string 	$selector
	 * @param 	string 	$data
	 * 
	 * @return 	string
	 * @since 	4.0.0
	 */
	public function boxShadow(string $selector, string $data): string
	{
		$selector = $this->generateSelector($selector);

		$css = [];
		$css[]    = '<# if(!_.isEmpty(' . $data . ')) { #>';
		$css[]    = $selector . ' {';
		$css[]    = '<# if(_.isObject(' . $data . ')) { #>';
		$css[] 	  = 'box-shadow: ' . $this->inlineBlock($data . '.ho || 0') . 'px ' .  $this->inlineBlock($data . '.vo || 0') . 'px ' . $this->inlineBlock($data . '.blur || 0') . 'px ' . $this->inlineBlock($data . '.spread || 0') . 'px ' . $this->inlineBlock($data . '.color || "transparent"') . ';';
		$css[]    = '<# } else { #>';
		$css[]    = 'box-shadow: ' . $this->inlineBlock($data) . ';';
		$css[]    = '<# } #>';
		$css[]    = '}';
		$css[]    = '<# } #>';

		return implode("\n", $css);
	}

	/**
	 * text shadow
	 *
	 * @param	string 	$selector
	 * @param	string 	$data
	 * 
	 * @return	string
	 * @since 	4.0.0
	 */
	public function textShadow(string $selector, string $data): string
	{
		$selector = $this->generateSelector($selector);

		$css = [];
		$css[]    = '<# if(!_.isEmpty(' . $data . ')) { #>';
		$css[]    = $selector . ' {';
		$css[]    = '<# if(_.isObject(' . $data . ') && ' . $data . '.enabled) { #>';
		$css[] 	  = 'text-shadow: ' . $this->inlineBlock($data . '.ho || 0') . 'px ' .  $this->inlineBlock($data . '.vo || 0') . 'px ' . $this->inlineBlock($data . '.blur || 0') . 'px ' . $this->inlineBlock($data . '.color || "transparent"') . ';';
		$css[]    = '<# } else { #>';
		$css[]    = 'text-shadow: ' . $this->inlineBlock($data) . ';';
		$css[]    = '<# } #>';
		$css[]    = '}';
		$css[]    = '<# } #>';

		return implode("\n", $css);
	}


	/**
	 * Return all css with media query for given size.
	 *
	 * @param	string 		$property
	 * @param	string 		$selector
	 * @param	string 		$data
	 * @param	string 		$unit
	 * @param	boolean 	$isTypography
	 * @param	boolean 	$prefix 
	 * 
	 * @return	string
	 * @since	4.0.0
	 */
	private function mediaQuery(string $property, string $selector, string $data,  string $unit = '', bool $isTypography = false, string $prefix  = ''): string
	{
		$css = [];

		foreach ($this->sizes as $size)
		{
			$css[] = $this->mediaQueryDevice($size);
			$css[] = $selector . ' {';
			$css[] = '<# if(_.isObject(' . $data . ') && !_.isEmpty(' . $data . ')) { #>';

			if ($isTypography)
			{
				$css[] = $property . ': ' . $prefix . $this->inlineBlock($data . '.' . $size . '.value') . $this->inlineBlock($data . '.' . $size . '.unit') . ';';
			}
			else
			{
				$css[] = $property . ': ' . $prefix . $this->inlineBlock($data . '.' . $size) . (!empty($unit) ? $unit : "") . ';';
			}

			$css[] = '<# } #>';
			$css[] = '}'; // closing bracket for selector.
			$css[] = '}'; // closing bracket for media query.
		}

		return implode("\n", $css);
	}

	/**
	 * Return media query 
	 *
	 * @param	string 	$device device size name
	 * 
	 * @return	string
	 * @since 	4.0.0
	 */
	private function mediaQueryDevice(string $device)
	{
		switch ($device)
		{
			case 'lg':
				return "@media ( max-width: 1199.98px ) { ";
			case 'md':
				return "@media ( max-width: 991.98px ) { ";
			case 'sm':
				return "@media ( max-width: 767.98px ) { ";
			case 'xs':
				return "@media ( max-width: 575.98px ) { ";
			default:
				return "";
		}
	}

	/**
	 * Generate the block of codes.
	 *
	 * @return	string
	 * @since	4.0.0
	 */
	private function block(string $content): string
	{
		return '<# ' . $content . ' #>';
	}


	/**
	 * Generate the inline block of codes.
	 *
	 * @return	string
	 * @since	4.0.0
	 */
	private function inlineBlock(string $content): string
	{
		return '{{' . $content . '}}';
	}

	/**
	 * Generate the inline block of codes if condition is true.
	 *
	 * @return	string
	 * @since	4.0.0
	 */
	private function inlineBlockWithCondition(bool $condition, string $content): string
	{
		return $condition ? '{{' .  $content  . '}}' : '';
	}

	/**
	 * Backdrop Filter
	 *
	 * @param string $property   CSS Property Name
	 * @param string $selector	 CSS Selector
	 * @param string $data		 CSS Value
	 * @param string $unit		 Unit of CSS Value
	 * @return string
	 * since 4.0.8
	 */
	public function backdrop_filter(string $property, string $selector, string $data, string $unit)
	{
		$selector = $this->generateSelector($selector);

		$css 	  = [];
		$css[]    = '<# if(!_.isEmpty(' . $data . ')) { #>';
		$css[]    = $selector . ' {';
		$css[]    = '-webkit-backdrop-filter: ' . $property . '(' . $this->inlineBlock($data) . $unit . ');';
		$css[]    = 'backdrop-filter: ' . $property . '(' . $this->inlineBlock($data) . $unit . ');';
		$css[]    = '}';
		$css[]    = '<# } #>';

		return implode("\n", $css);
	}

	/**
	 * Generate missing break points of field width for old layouts. 
	 *
	 * @param string $fieldWidth  given breakpoints of field width
	 * @return string
	 * @since 4.0.9
	 */
	public function generateMissingBreakpoints(string $fieldWidth)
	{
		$generateBreakPoints   = [];
		$generateBreakPoints[] = "const expectedBreakpoints = ['xl', 'lg'];";
		$generateBreakPoints[] = "_.forEach(expectedBreakpoints, (key) => {";
		$generateBreakPoints[] = "if(!_.has($fieldWidth, key)) {";
		$generateBreakPoints[] = "_.set($fieldWidth, key, _.get($fieldWidth, 'md', ''));";
		$generateBreakPoints[] = "}";
		$generateBreakPoints[] = "})";

		return implode("\n", $generateBreakPoints);
	}
}
