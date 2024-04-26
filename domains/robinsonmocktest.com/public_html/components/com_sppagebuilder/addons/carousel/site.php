<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;

/**
 * Carousel addon class
 * 
 * @since 1.0.0
 */
class SppagebuilderAddonCarousel extends SppagebuilderAddons
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
		$class = (isset($settings->class) && $settings->class) ? ' ' . $settings->class : '';

		//Addons option
		$autoplay = (isset($settings->autoplay) && $settings->autoplay) ? 1 : 0;
		$controllers = (isset($settings->controllers) && $settings->controllers) ? $settings->controllers : 0;
		$arrows = (isset($settings->arrows) && $settings->arrows) ? $settings->arrows : 0;
		$interval = (isset($settings->interval) && $settings->interval) ? ((int) $settings->interval * 1000) : 5000;
		$carousel_autoplay = ($autoplay) ? ' data-sppb-ride="sppb-carousel"' : '';
		if ($autoplay == 0)
		{
			$interval = 'false';
		}
		$output  = '<div id="sppb-carousel-' . $this->addon->id . '" data-interval="' . $interval . '" class="sppb-carousel sppb-slide' . $class . '"' . $carousel_autoplay . '>';

		if ($controllers)
		{
			$output .= '<ol class="sppb-carousel-indicators">';
			foreach ($settings->sp_carousel_item as $key1 => $value)
			{
				$output .= '<li data-sppb-target="#sppb-carousel-' . $this->addon->id . '" ' . (($key1 == 0) ? ' class="active"' : '') . '  data-sppb-slide-to="' . $key1 . '"></li>' . "\n";
			}
			$output .= '</ol>';
		}

		$output .= '<div class="sppb-carousel-inner">';

		if (isset($settings->sp_carousel_item) && count((array) $settings->sp_carousel_item))
		{
			foreach ($settings->sp_carousel_item as $key => $value)
			{
				list($button_url, $button_target) = AddonHelper::parseLink($value, 'button_url', ['url' => 'button_url', 'new_tab' => 'button_target']);

				$bg_image = (isset($value->bg) && $value->bg) ? $value->bg : '';
				$bg_image_src = isset($bg_image->src) ? $bg_image->src : $bg_image;

				$output   .= '<div class="sppb-item sppb-item-' . $this->addon->id . $key . ' ' . ($bg_image_src ? ' sppb-item-has-bg' : '') . (($key == 0) ? ' active' : '') . '">';
				$alt_text = isset($value->title) ? $value->title : '';
				$output  .= $bg_image_src ? '<img src="' . $bg_image_src . '" alt="' . $alt_text . '">' : '';

				$output  .= '<div class="sppb-carousel-item-inner">';
				$output  .= '<div class="sppb-carousel-caption">';
				$output  .= '<div class="sppb-carousel-text">';

				if ((isset($value->title) && $value->title) || (isset($value->content) && $value->content))
				{
					$output  .= (isset($value->title) && $value->title) ? '<h2>' . $value->title . '</h2>' : '';
					$output  .= (isset($value->content) && $value->content) ? '<div class="sppb-carousel-content">' . $value->content . '</div>' : '';

					if (isset($value->button_text) && $value->button_text)
					{
						$button_class = (isset($settings->button_type) && $settings->button_type) ? ' sppb-btn-' . $settings->button_type : ' sppb-btn-default';
						$button_class .= (isset($settings->button_size) && $settings->button_size) ? ' sppb-btn-' . $settings->button_size : '';
						$button_class .= (isset($settings->button_shape) && $settings->button_shape) ? ' sppb-btn-' . $settings->button_shape : ' sppb-btn-rounded';
						$button_class .= (isset($settings->button_appearance) && $settings->button_appearance) ? ' sppb-btn-' . $settings->button_appearance : '';
						$button_class .= (isset($settings->button_block) && $settings->button_block) ? ' ' . $settings->button_block : '';
						$button_icon = (isset($value->button_icon) && $value->button_icon) ? $value->button_icon : '';
						$button_icon_position = (isset($value->button_icon_position) && $value->button_icon_position) ? $value->button_icon_position : 'left';


						$icon_arr = array_filter(explode(' ', $button_icon));

						if (count($icon_arr) === 1)
						{
							$button_icon = 'fa ' . $button_icon;
						}

						if ($button_icon_position == 'left')
						{
							$value->button_text = ($button_icon) ? '<i aria-hidden="true" class="' . $button_icon . '" aria-hidden="true"></i> ' . $value->button_text : $value->button_text;
						}
						else
						{
							$value->button_text = ($button_icon) ? $value->button_text . ' <i aria-hidden="true" class="' . $button_icon . '" aria-hidden="true"></i>' : $value->button_text;
						}

						$href = !empty($button_url) ? 'href="' . $button_url . '" ' : '';

						$output  .= '<a ' . $href . ' ' . $button_target . ' id="btn-' . ($this->addon->id . $key) . '" class="sppb-btn' . $button_class . '">' . $value->button_text . '</a>';
					}
				}

				$output  .= '</div>';
				$output  .= '</div>';

				$output  .= '</div>';
				$output  .= '</div>';
			}
		}


		$output	.= '</div>';

		if ($arrows)
		{
			$output	.= '<a href="#sppb-carousel-' . $this->addon->id . '" class="sppb-carousel-arrow left sppb-carousel-control" data-slide="prev" aria-label="' . Text::_('COM_SPPAGEBUILDER_ARIA_PREVIOUS') . '"><i class="fa fa-chevron-left" aria-hidden="true"></i></a>';
			$output	.= '<a href="#sppb-carousel-' . $this->addon->id . '" class="sppb-carousel-arrow right sppb-carousel-control" data-slide="next" aria-label="' . Text::_('COM_SPPAGEBUILDER_ARIA_NEXT') . '"><i class="fa fa-chevron-right" aria-hidden="true"></i></a>';
		}

		$output .= '</div>';

		return $output;
	}

	/**
	 * Generate the CSS string for the frontend page.
	 *
	 * @return 	string 	The CSS string for the page.
	 * @since 	1.0.0
	 */
	public function css()
	{
		$addon_id = '#sppb-addon-' . $this->addon->id;
		$layout_path = JPATH_ROOT . '/components/com_sppagebuilder/layouts';

		$cssHelper = new CSSHelper($addon_id);

		$settings = $this->addon->settings;

		$settings->alignment = CSSHelper::parseAlignment($settings, 'alignment');

		$css = '';

		// Buttons style
		foreach ($this->addon->settings->sp_carousel_item as $key => $value)
		{
			$options = new stdClass;
			$options->button_type = (isset($settings->button_type) && $settings->button_type) ? $settings->button_type : '';
			$options->button_appearance = (isset($settings->button_appearance) && $settings->button_appearance) ? $settings->button_appearance : '';
			$options->button_color = (isset($settings->button_color) && $settings->button_color) ? $settings->button_color : '';
			$options->button_color_hover = (isset($settings->button_color_hover) && $settings->button_color_hover) ? $settings->button_color_hover : '';
			$options->button_background_color = (isset($settings->button_background_color) && $settings->button_background_color) ? $settings->button_background_color : '';
			$options->button_background_color_hover = (isset($settings->button_background_color_hover) && $settings->button_background_color_hover) ? $settings->button_background_color_hover : '';
			$options->button_padding = null;

			if (isset($settings->button_padding_original))
			{
				$options->button_padding = $settings->button_padding_original;
			}
			elseif (isset($settings->button_padding))
			{
				$options->button_padding = $settings->button_padding;
			}

			$options->button_size = !empty($settings->button_size) ? $settings->button_size : null;
			$options->button_typography = !empty($settings->button_typography) ? $settings->button_typography : null;

			$css_path = new FileLayout('addon.css.button', $layout_path);
			$css .= $css_path->render(array('addon_id' => $addon_id, 'options' => $options, 'id' => 'btn-' . ($this->addon->id . $key)));
			// Title Margin
			$itemTitleMarginStyle = $cssHelper->generateStyle('.sppb-carousel-text h2', $value, ['title_margin' => 'margin', 'title_padding' => 'padding'], ['title_margin' => false, 'title_padding' => false], ['title_margin' => 'spacing', 'title_padding' => 'spacing']);
			$css .= $itemTitleMarginStyle;

			// Content Margin
			$itemContentMarginStyle = $cssHelper->generateStyle('.sppb-carousel-text .sppb-carousel-content', $value, ['content_margin' => 'margin', 'content_padding' => 'padding'], ['content_margin' => false, 'content_padding' => 'padding'], ['content_margin' => 'spacing', 'content_padding' => 'spacing']);
			$css .= $itemContentMarginStyle;
		}

		$speed = $cssHelper->generateStyle('.sppb-carousel-inner > .sppb-item', $settings, ['speed' => '-webkit-transition-duration', 'speed' => 'transition-duration'], 'ms');

		$css .= $speed;

		// Title Color 
		$itemAlignment = $cssHelper->generateStyle('.sppb-carousel-text', $settings, ['alignment' => 'text-align'], ['alignment' => false]);
		$css .= $itemAlignment;

		// Title
		$titleFontStyle = $cssHelper->typography('.sppb-carousel-text h2', $settings, 'item_title_typography');
		$css .= $titleFontStyle;

		// Title Color 
		$itemTitleColorStyle = $cssHelper->generateStyle('.sppb-carousel-text h2', $settings, ['item_title_color' => 'color'], ['item_title_color' => false]);
		$css .= $itemTitleColorStyle;

		// Content
		$contentTypography = $cssHelper->typography('.sppb-carousel-text .sppb-carousel-content', $settings, 'item_content_typography');
		$css .= $contentTypography;

		// Content Color
		$itemContentColorStyle = $cssHelper->generateStyle('.sppb-carousel-text .sppb-carousel-content', $settings, ['item_content_color' => 'color'], ['item_content_color' => false]);
		$css .= $itemContentColorStyle;

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
		let interval = data.interval ? parseInt(data.interval) * 1000 : 5000;
		if (data.autoplay==0)
		{
			interval = "false";
		}
		let autoplay = data.autoplay ? \'data-sppb-ride="sppb-carousel"\' : "";
		#>
		<style type="text/css">';
		// Alignment
		$output .= $lodash->alignment('text-align', '.sppb-carousel-caption .sppb-carousel-text', 'data.alignment');

		$output .= '
			#sppb-addon-{{ data.id }} .sppb-carousel-inner > .sppb-item{
				-webkit-transition-duration: {{ data.speed }}ms;
				transition-duration: {{ data.speed }}ms;
			}
			<# _.each(data.sp_carousel_item, function (carousel_item, key){ #>';
		// Custom
		$output .= '<# if (data.button_type == "custom") { #>';
		$output .= '<# if (data.button_appearance == "outline") { #>';
		$output .= $lodash->border('border-color', '.sppb-btn-custom', 'data.button_background_color');
		$output .= $lodash->border('border-color', '.sppb-btn-custom:hover', 'data.button_background_color_hover');
		$output .= '<# } else if (data.button_appearance == "gradient") { #>';
		$output .= '#sppb-addon-{{ data.id }} .sppb-btn-custom { border: none; }';
		$output .= $lodash->color('background-color', '.sppb-btn-custom', 'data.button_background_gradient');
		$output .= $lodash->color('background-color', '.sppb-btn-custom:hover', 'data.button_background_gradient_hover');
		$output .= '<# } else { #>';
		$output .= $lodash->color('background-color', '.sppb-btn-custom', 'data.button_background_color');
		$output .= '<# } #>';

		$output .= $lodash->color('color', '.sppb-btn-custom', 'data.button_color');
		$output .= $lodash->color('color', '.sppb-btn-custom:hover', 'data.button_color_hover');
		$output .= $lodash->color('background-color', '.sppb-btn-custom:hover', 'data.button_background_color_hover');
		$output .= '<# } #>';

		// Typography
		$output .= $lodash->typography('.sppb-btn-{{ data.button_type }}', 'data.button_typography');

		$output .= $lodash->typography('.sppb-carousel-caption h2', 'data.item_title_typography');
		$output .= $lodash->typography('.sppb-carousel-caption .sppb-carousel-content', 'data.item_content_typography');

		// Color
		$output .= $lodash->color('color', '.sppb-carousel-caption h2', 'data.item_title_color ');
		$output .= $lodash->color('color', '.sppb-carousel-caption .sppb-carousel-content', 'data.item_content_color');

		// Spacing
		$output .= $lodash->spacing('margin', '.sppb-carousel-caption h2', 'carousel_item.title_margin');
		$output .= $lodash->spacing('margin', '.sppb-carousel-caption .sppb-carousel-content', 'carousel_item.content_margin');
		$output .= $lodash->spacing('padding', '.sppb-carousel-caption h2', 'carousel_item.title_padding');
		$output .= $lodash->spacing('padding', '.sppb-carousel-caption .sppb-carousel-content', 'carousel_item.content_padding');
		$output .= '<# if (data.button_size == "custom") { #>';
		$output .= $lodash->spacing('padding', '.sppb-btn-{{ data.button_type }}', 'data.button_padding');
		$output .= '<# } #>';


		$output .= '		
			<# }); #>
		</style>
		<div class="sppb-carousel sppb-slide {{ data.class }}" id="sppb-carousel-{{ data.id }}" data-interval="{{ interval }}" {{{ autoplay }}}>
			<# if(data.controllers){ #>
				<ol class="sppb-carousel-indicators">
				<# _.each(data.sp_carousel_item, function (carousel_item, key){ #>
					<# var active = (key == 0) ? "active" : ""; #>
					<li data-sppb-target="#sppb-carousel-{{ data.id }}"  class="{{ active }}"  data-sppb-slide-to="{{ key }}"></li>
				<# }); #>
				</ol>
			<# } #>
			<div class="sppb-carousel-inner">
				<#
				_.each(data.sp_carousel_item, function (carousel_item, key){
					var carouselBg = {}
					if (typeof carousel_item.bg !== "undefined" && typeof carousel_item.bg.src !== "undefined") {
						carouselBg = carousel_item.bg
					} else {
						carouselBg = {src: carousel_item.bg}
					}
					var classNames = (key == 0) ? "active" : "";
					classNames += carouselBg.src ? " sppb-item-has-bg" : "";
					classNames += " sppb-item-"+data.id+""+key;
				#>
					<div class="sppb-item {{ classNames }}">
						<# if(carouselBg.src && carouselBg.src.indexOf("http://") == -1 && carouselBg.src.indexOf("https://") == -1){ #>
							<img src=\'{{ pagebuilder_base + carouselBg.src }}\' alt="{{ carousel_item.title }}">
						<# } else if(carouselBg.src){ #>
							<img src=\'{{ carouselBg.src }}\' alt="{{ carousel_item.title }}">
						<# } #>
						<div class="sppb-carousel-item-inner">
							<div class="sppb-carousel-caption">
								<div class="sppb-carousel-text">
									<# if(carousel_item.title || carousel_item.content) { #>
										<# if(carousel_item.title) { #>
											<h2 class="sp-editable-content" id="addon-title-{{data.id}}-{{key}}" data-id={{data.id}} data-fieldName="sp_carousel_item-{{key}}-title">{{ carousel_item.title }}</h2>
										<# } #>
										<div class="sppb-carousel-content sp-editable-content" id="addon-content-{{data.id}}-{{key}}" data-id={{data.id}} data-fieldName="sp_carousel_item-{{key}}-content">{{{ carousel_item.content }}}</div>
										<# if(carousel_item.button_text) { #>
											<#
												var btnClass = "";
												btnClass += data.button_type ? " sppb-btn-"+data.button_type : " sppb-btn-default" ;
												btnClass += data.button_size ? " sppb-btn-"+data.button_size : "" ;
												btnClass += data.button_shape ? " sppb-btn-"+data.button_shape : " sppb-btn-rounded" ;
												btnClass += data.button_appearance ? " sppb-btn-"+data.button_appearance : "" ;
												btnClass += data.button_block ? " "+data.button_block : "" ;
												var button_text = carousel_item.button_text;

												let icon_arr = (typeof carousel_item.button_icon !== "undefined" && carousel_item.button_icon) ? carousel_item.button_icon.split(" ") : "";
												let icon_name = icon_arr.length === 1 ? "fa "+carousel_item.button_icon : carousel_item.button_icon;

												if(carousel_item.button_icon_position == "left"){
													button_text = (carousel_item.button_icon) ? \'<i class="\'+icon_name+\'"></i> \'+carousel_item.button_text : carousel_item.button_text ;
												}else{
													button_text = (carousel_item.button_icon) ? carousel_item.button_text+\' <i class="\'+icon_name+\'"></i>\' : carousel_item.button_text ;
												}
											#>

											<#

											 const {button_url} =  carousel_item;
											 const isUrlObject = _.isObject(button_url) &&  (!!button_url.url || !!button_url.menu || !!button_url.page);
											 const isUrlString = _.isString(button_url) && button_url !== "";
											 
											 let target;
											 let href;
											 let rel;
											 let relData="";
											
											 if(isUrlObject || isUrlString){
												const urlObj = button_url?.url ? button_url : window.getSiteUrl(button_url, data.button_target);
												const {url, new_tab, nofollow, noopener, noreferrer, type} = urlObj;

										   		const buttonUrl = (type === "url" && url) || (type === "menu" && urlObj.menu) || ( (type === "page" && !!urlObj.page)  && "index.php/component/sppagebuilder/index.php?option=com_sppagebuilder&view=page&id=" + urlObj.page) || "";
												target = new_tab ? `target=_blank` : "";
												
												relData += nofollow ? "nofollow" : "";
												relData += noopener ? " noopener" : "";
												relData += noreferrer ? " noreferrer" : "";

												rel = `rel="${relData}"`;
											
												href = buttonUrl ? `href=${buttonUrl}`: "";
											 }
											 #>

											 <a {{href}} {{target}} rel="{{rel}}" id="btn-{{ data.id + "" + key}}" class="sppb-btn{{ btnClass }}">{{{ button_text }}}</a>
											
										<# } #>
									<# } #>
								</div>
							</div>
						</div>
					</div>
				<# }); #>
			</div>
			<# if(data.arrows) { #>
				<a href="#sppb-carousel-{{ data.id }}" class="sppb-carousel-arrow left sppb-carousel-control" data-slide="prev"><i class="fa fa-chevron-left"></i></a>
				<a href="#sppb-carousel-{{ data.id }}" class="sppb-carousel-arrow right sppb-carousel-control" data-slide="next"><i class="fa fa-chevron-right"></i></a>
			<# } #>
		</div>
		';

		return $output;
	}
}
