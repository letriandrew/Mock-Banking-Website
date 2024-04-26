<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Uri\Uri;

class SppagebuilderAddonImage extends SppagebuilderAddons
{
	/**
	 * The addon frontend render method.
	 * The returned HTML string will render to the frontend page.
	 *
	 * @return  string  The HTML string.
	 * @since   1.0.0
	 */
	public function render()
	{
		$settings = $this->addon->settings;
		$class = (isset($settings->class) && $settings->class) ? $settings->class : '';
		$title = (isset($settings->title) && $settings->title) ? $settings->title : '';
		$title_position = (isset($settings->title_position) && $settings->title_position) ? $settings->title_position : 'top';
		$heading_selector = (isset($settings->heading_selector) && $settings->heading_selector) ? $settings->heading_selector : 'h3';

		// Options
		$image = (isset($settings->image) && $settings->image) ? $settings->image : '';

		$image_src = isset($image->src) ? $image->src : $image;
		$image_width = (isset($image->width) && $image->width) ? $image->width : '';
		$image_height = (isset($image->height) && $image->height) ? $image->height : '';

		// Image 2x
		$image_2x = (isset($settings->image_2x) && $settings->image_2x) ? $settings->image_2x : '';
		$image_2x_src = isset($image_2x->src) ? $image_2x->src : $image_2x;
		$image_2x_src = ctype_space($image_2x_src) ? "" : $image_2x_src;

		$alt_text = (isset($settings->alt_text) && $settings->alt_text) ? $settings->alt_text : '';
		$open_lightbox = (isset($settings->open_lightbox) && $settings->open_lightbox) ? $settings->open_lightbox : 0;
		$image_overlay = (isset($settings->overlay_color) && $settings->overlay_color) ? 1 : 0;

		list($link, $target) = AddonHelper::parseLink($settings, 'link', ['url' => 'link', 'new_tab' => 'target']);

		// Lazy image loading
		$placeholder = $image_src === '' ? false : $this->get_image_placeholder($image_src);

		if (strpos($image_src, "http://") !== false || strpos($image_src, "https://") !== false) {
			$image_src = $image_src;
		} else {
			$original_src = Uri::base(true) . '/' . $image_src;
			$image_src = SppagebuilderHelperSite::cleanPath($original_src);
		}

		$output = '';

		if ($image_src) {
			$output .= '<div class="sppb-addon sppb-addon-single-image ' . ' ' . $class . '">';
			$output .= ($title && $title_position != 'bottom') ? '<' . $heading_selector . ' class="sppb-addon-title">' . $title . '</' . $heading_selector . '>' : '';
			$output .= '<div class="sppb-addon-content">';
			$output .= '<div class="sppb-addon-single-image-container">';

			if (empty($alt_text)) {
				if (!empty($title)) {
					$alt_text = $title;
				} else {
					$alt_text = basename($image_src);
				}
			}

			if ($image_overlay && $open_lightbox) {
				$output .= '<div class="sppb-addon-image-overlay">';
				$output .= '</div>';
			}

			if ($open_lightbox) {
				$output .= '<a class="sppb-magnific-popup sppb-addon-image-overlay-icon" data-popup_type="image" data-mainclass="mfp-no-margins mfp-with-zoom" href="' . $image_src . '">+</a>';
			}

			if (!$open_lightbox) {
				$output .= !empty($link) ? '<a href="' . $link . '" ' . $target . '>' : '';
			}

			$dimension = '';

			if ($placeholder) {
				$dimension = $this->get_image_dimension($image_src);
				$dimension = !empty($dimension) ? \implode(' ', $dimension) : '';
			}

			$image2x = empty($image_2x_src) ? "" : 'srcset="' . $image_2x_src . ' 2x"';

			$output .= '<img class="sppb-img-responsive' . ($placeholder ? ' sppb-element-lazy "' : '') . '" src="' . ($placeholder ? $placeholder : $image_src)  . '" ' . $image2x . ($placeholder ? 'data-large="' . $image_src . '"' : '') . ' alt="' . $alt_text . '" title="' . $title . '" ' . ($image_width ? 'width="' . $image_width . '"' : '') . ' ' . ($image_height ? 'height="' . $image_height . '"' : '') . ($placeholder ? 'loading="lazy"' : '') . ' ' . $dimension . '/>';

			if (!$open_lightbox) {
				$output .= !empty($link) ? '</a>' : '';
			}

			$output  .= '</div>';
			$output .= ($title && $title_position === 'bottom') ? '<' . $heading_selector . ' class="sppb-addon-title" style="display: block;">' . $title . '</' . $heading_selector . '>' : '';
			$output  .= '</div>';
			$output  .= '</div>';
		}

		return $output;
	}

	/**
	 * Load external scripts.
	 *
	 * @return 	array
	 * @since 	1.0.0
	 */
	public function scripts()
	{
		return array(Uri::base(true) . '/components/com_sppagebuilder/assets/js/jquery.magnific-popup.min.js');
	}

	/**
	 * Load external stylesheets.
	 *
	 * @return 	array
	 * @since 	1.0.0
	 */
	public function stylesheets()
	{
		return array(Uri::base(true) . '/components/com_sppagebuilder/assets/css/magnific-popup.css');
	}

	/**
	 * Generate the CSS string for the frontend page.
	 *
	 * @return 	string 	The CSS string for the page.
	 * @since 	1.0.0
	 */
	public function css()
	{
		$settings = $this->addon->settings;
		$addon_id = '#sppb-addon-' . $this->addon->id;
		$cssHelper = new CSSHelper($addon_id);

		$open_lightbox = (isset($settings->open_lightbox) && $settings->open_lightbox) ? $settings->open_lightbox : 0;
		$border_radius = (isset($settings->border_radius) && $settings->border_radius) ? $settings->border_radius : 0;
		$settings->position = CSSHelper::parseAlignment($settings, 'position');

		if ($border_radius) {
			$border_radius = explode(" ", $settings->border_radius);
		}

		if (is_array($border_radius) && (count($border_radius) > 2)) {
			$imageStyle = $cssHelper->generateStyle(
				'img',
				$settings,
				[
					'border_radius' => 'border-radius',
					'image_width' => ['width', 'max-width'],
					'image_height' => 'height'
				],
				[
					'border_radius' => false
				],
				[
					'border_radius' => 'spacing'
				]
			);
			$imageOverlayStyle = $cssHelper->generateStyle(
				'.sppb-addon-image-overlay',
				$settings,
				[
					'overlay_color' => 'background-color',
					'border_radius' => 'border-radius',
					'image_width' => ['width', 'max-width'],
					'image_height' => 'height'
				],
				[
					'overlay_color' => false,
					'border-radius' => false
				],
				[
					'border_radius' => 'spacing'
				]
			);
		} else {
			$imageStyle = $cssHelper->generateStyle(
				'img',
				$settings,
				[
					'border_radius' => 'border-radius',
					'image_width' => ['width', 'max-width'],
					'image_height' => 'height'
				]
			);

			$imageOverlayStyle = $cssHelper->generateStyle(
				'.sppb-addon-image-overlay',
				$settings,
				[
					'overlay_color' => 'background-color',
					'border_radius' => 'border-radius',
					'image_width' => ['width', 'max-width'],
					'image_height' => 'height'
				],
				[
					'overlay_color' => false
				]
			);
		}


		$imagePositionStyle = $cssHelper->generateStyle(':self', $settings, ['position' => 'text-align'], false);

		$css = '';
		$css .= $imageStyle;
		$css .= $imagePositionStyle;
		$css .= $open_lightbox ? $imageOverlayStyle : '';

		return $css;
	}

	/**
	 * Generate the lodash template string for the frontend editor.
	 *
	 * @return 	string 	The lodash template string.
	 * @since 	1.0.0
	 */
	public static function getTemplate()
	{
		$lodash = new Lodash('#sppb-addon-{{ data.id }}');
		$output = '
		<#
			var image_overlay = 0;
			if(!_.isEmpty(data.overlay_color)){
				image_overlay = 1;
			}
			var open_lightbox = data.open_lightbox;

			var alt_text = data.alt_text;

			if(_.isEmpty(alt_text)){
				if(!_.isEmpty(data.title)){
					alt_text = data.title;
				}
			}
		#>
		<style type="text/css">';

		// global
		$output .= $lodash->alignment('text-align', '', 'data.position');

		// title
		$typographyFallbacks = [
			'font'           => 'data.title_font_family',
			'size'           => 'data.title_fontsize',
			'line_height'    => 'data.title_lineheight',
			'letter_spacing' => 'data.title_letterspace',
			'uppercase'      => 'data.title_font_style?.uppercase',
			'italic'         => 'data.title_font_style?.italic',
			'underline'      => 'data.title_font_style?.underline',
			'weight'         => 'data.title_font_style?.weight'
		];
		$output .= $lodash->typography('.sppb-addon-title', 'data.title_typography', $typographyFallbacks);
		$output .= $lodash->spacing('padding', '.sppb-addon-title', 'data.title_padding');

		// image
		$output .= '<# if((data.border_radius + "").split(" ").length < 2) { #>';
		$output .= $lodash->unit('border-radius', 'img', 'data.border_radius', 'px');
		$output .= '<# } else { #>';
		$output .= '#sppb-addon-{{data.id}} img {
			{{window.getSplitRadius(data.border_radius)}}	
		}';
		$output .= '<# } #>';
		$output .= $lodash->unit('height', 'img', 'data.image_height', 'px');
		$output .= $lodash->unit('width', 'img', 'data.image_width', 'px');
		$output .= $lodash->unit('max-width', 'img', 'data.image_width', 'px');
		$output .= $lodash->color('background-color', '.sppb-addon-image-overlay', 'data.overlay_color');
		$output .= '<# if((data.border_radius + "").split(" ").length < 2) { #>';
		$output .= $lodash->unit('border-radius', '.sppb-addon-image-overlay', 'data.border_radius', 'px');
		$output .= '<# } else { #>';
		$output .= $lodash->unit('border-radius', '.sppb-addon-image-overlay', 'data.border_radius', '');
		$output .= '<# } #>';

		$output .= '
		</style>
		
		<# if(data.image) { 
			let media = {}
			if (typeof data.image !== "undefined" && typeof data.image.src !== "undefined") {
				media = data.image
			} else {
				media = {src: data.image, height:"", width:""}
			}
			#>
			<div class="sppb-addon sppb-addon-single-image {{ data.class }}">
				<# if( !_.isEmpty( data.title ) && data.title_position != "bottom" ){ #><{{ data.heading_selector }} class="sppb-addon-title sp-inline-editable-element" data-id={{data.id}} data-fieldName="title" contenteditable="true">{{ data.title }}</{{ data.heading_selector }}><# } #>
				<div class="sppb-addon-content">
					<div class="sppb-addon-single-image-container">
						<# if(image_overlay && open_lightbox) { #>
							<div class="sppb-addon-image-overlay"></div>
						<# } #>
						<# if(open_lightbox) { #>
							<a class="sppb-magnific-popup sppb-addon-image-overlay-icon" data-popup_type="image" data-mainclass="mfp-no-margins mfp-with-zoom" href=\'{{ media.src }}\'>+</a>
						<# } #>
			
						<# if(!open_lightbox) {  #>
							<# 
							const isUrlObject = _.isObject(data.link) && (data.link.url || data.link.menu || data.link.page );
							const isUrlString = _.isString(data.link) && data.link !== "";
							

							if(isUrlObject || isUrlString ) {
								const urlObj = data?.link?.url ? data.link : window.getSiteUrl(data.link, data.target);
								const {url, new_tab, nofollow, noopener, noreferrer, type} = urlObj;
								const target = new_tab ? "_blank" : "";
								
								let rel="";
								rel += nofollow ? "nofollow": "";
								rel += noopener ? " noopener": "";
								rel += noreferrer ? " noreferrer": "";
							
								const clickUrl = (type === "url" && url) || (type ==="menu" && urlObj.menu) || ( (type === "page" && !!urlObj.page) && "index.php/component/sppagebuilder/index.php?option=com_sppagebuilder&view=page&id=" +urlObj.page) || "";
								#>
								  <# if(clickUrl) {#>
									<a href=\'{{clickUrl.trim()}}\' target=\'{{target}}\' rel=\'{{rel}}\'  >
								  <#  }#>
							  <#} #>
  
						  <#  }#>
						
						<# if(media.src.indexOf("http://") == -1 && media.src.indexOf("https://") == -1){ #>
							<img class="sppb-img-responsive" src=\'{{ pagebuilder_base + media.src }}\' alt="{{ alt_text }}" title="{{ data.title }}">
						<# } else { #>
							<img class="sppb-img-responsive" src=\'{{ media.src }}\' alt="{{ alt_text }}" title="{{ data.title }}">
						<# } #>

						<# if(!open_lightbox) { #>
							</a>
						<# } #>

					</div>
					<# if( !_.isEmpty( data.title ) && data.title_position == "bottom" ){ #><{{ data.heading_selector }} class="sppb-addon-title" style="display: block;">{{ data.title }}</{{ data.heading_selector }}><# } #>
				</div>
			</div>
		<# } else { #>
			<div class="builder-canvas-placeholder-image">
				<div class="builder-icon">
					<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M25.884 2H5.046C3.366 2 2 3.406 2 5.139v19.726C2 26.595 3.366 28 5.046 28h20.838c1.68 0 3.045-1.405 3.045-3.135V5.139C28.929 3.406 27.564 2 25.884 2zm-6.47 4.668c1.624 0 2.941 1.357 2.941 3.03 0 1.673-1.317 3.03-2.942 3.03-1.624 0-2.941-1.357-2.941-3.03 0-1.673 1.317-3.03 2.941-3.03zM24.928 25.1H6.421c-.813 0-1.175-.605-.808-1.352l5.048-10.3c.366-.747 1.063-.813 1.557-.149l5.076 6.833c.494.665 1.357.722 1.928.126l1.242-1.295c.57-.596 1.412-.522 1.877.163l3.216 4.732c.465.686.185 1.242-.628 1.242z" fill="currentColor"/></svg>
				</div>
			</div>
		<# } #>';

		return $output;
	}
}
