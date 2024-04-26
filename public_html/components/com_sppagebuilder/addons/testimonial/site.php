<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Uri\Uri;

class SppagebuilderAddonTestimonial extends SppagebuilderAddons
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

		$style = (isset($settings->style) && $settings->style) ? $settings->style : '';
		$title = (isset($settings->title) && $settings->title) ? $settings->title : '';
		$heading_selector = (isset($settings->heading_selector) && $settings->heading_selector) ? $settings->heading_selector : 'h3';
		$show_quote = (isset($settings->show_quote)) ? $settings->show_quote : true;
		$designation_position = (isset($settings->designation_position)) ? $settings->designation_position : 'bottom';

		// Options
		$review = (isset($settings->review) && $settings->review) ? $settings->review : '';
		$name = (isset($settings->name) && $settings->name) ? $settings->name : '';
		$company = (isset($settings->company) && $settings->company) ? $settings->company : '';
		$avatar_shape = (isset($settings->avatar_shape) && $settings->avatar_shape) ? $settings->avatar_shape : 'sppb-avatar-circle';
		// $link = (isset($settings->link) && $settings->link) ? $settings->link : '';
		// $link_target = (isset($settings->link_target) && $settings->link_target) ? ' rel="noopener noreferrer" target="' . $settings->link_target . '"' : '';

		list($link, $link_target) = AddonHelper::parseLink($settings, 'url', ['url' => 'link', 'new_tab' => 'link_target']);

		$avatar = (isset($settings->avatar) && $settings->avatar) ? $settings->avatar : '';
		$avatar_src = isset($avatar->src) ? $avatar->src : $avatar;
		$avatar_width = (isset($avatar->width) && $avatar->width) ? $avatar->width : '';
		$avatar_height = (isset($avatar->height) && $avatar->height) ? $avatar->height : '';

		// Lazy load image
		$placeholder = $avatar_src == '' ? false : $this->get_image_placeholder($avatar_src);
		$avatar_img = '';

		if (strpos($avatar_src, "http://") !== false || strpos($avatar_src, "https://") !== false) {
			$avatar_img = $avatar_src;
		} else {
			if ($avatar_src) {
				$avatar_img = Uri::base() . $avatar_src;
			}
		}

		// Rating
		$client_rating_enable = (isset($settings->client_rating_enable)) ? $settings->client_rating_enable : '';
		$client_rating = (isset($settings->client_rating)) ? $settings->client_rating : '';

		// Output
		$output  = '';
		$output  .= '<div class="sppb-addon sppb-addon-testimonial ' . $class . '">';
		$output .= ($title) ? '<' . $heading_selector . ' class="sppb-addon-title">' . $title . '</' . $heading_selector . '>' : '';
		$output .= '<div class="sppb-addon-content">';

		if ($show_quote && $designation_position !== 'top') {
			$output .= '<span class="fa fa-quote-left" aria-hidden="true"></span>';
		}

		if ($designation_position == 'top') {
			$output .= '<div class="sppb-testimonial-top-content sppb-addon-testimonial-footer">';
			$output .= $link ? '<a ' . $link_target . ' href="' . $link . '">' : '';
			$output .= '<div class="sppb-addon-testimonial-content-wrap">';
			$output .= $avatar_img ? '<img src="' . ($placeholder ? $placeholder : $avatar_img) . '" class="' . $avatar_shape . ' sppb-addon-testimonial-avatar' . ($placeholder ? ' sppb-element-lazy' : '') . '" alt="' . $name . '" ' . ($placeholder ? 'data-large="' . $avatar_img . '"' : '') . ' ' . ($avatar_width ? 'width="' . $avatar_width . '"' : '') . ' ' . ($avatar_height ? 'height="' . $avatar_height . '"' : '') . ' loading="lazy">' : '';
			$output .= '<span>';
			$output .= '<span class="sppb-addon-testimonial-client">' . $name . '</span>';
			$output .= '<br /><span class="sppb-addon-testimonial-client-url">' . $company . '</span>';
			$output .= '</span>';
			$output .= '</div>';
			$output .= $link ? '</a>' : '';

			if ($show_quote) {
				$output .= '<span class="fa fa-quote-right" aria-hidden="true"></span>';
			}

			$output .= '</div>';

			if ($client_rating_enable) {
				$output .= '<div class="sppb-addon-testimonial-rating">';

				if ($client_rating == 1) {
					$output .= '<i class="fa fa-star" aria-hidden="true"></i>';
					$output .= '<i class="fa fa-star-o" aria-hidden="true"></i>';
					$output .= '<i class="fa fa-star-o" aria-hidden="true"></i>';
					$output .= '<i class="fa fa-star-o" aria-hidden="true"></i>';
					$output .= '<i class="fa fa-star-o" aria-hidden="true"></i>';
				} elseif ($client_rating == 2) {
					$output .= '<i class="fa fa-star" aria-hidden="true"></i>';
					$output .= '<i class="fa fa-star" aria-hidden="true"></i>';
					$output .= '<i class="fa fa-star-o" aria-hidden="true"></i>';
					$output .= '<i class="fa fa-star-o" aria-hidden="true"></i>';
					$output .= '<i class="fa fa-star-o" aria-hidden="true"></i>';
				} elseif ($client_rating == 3) {
					$output .= '<i class="fa fa-star" aria-hidden="true"></i>';
					$output .= '<i class="fa fa-star" aria-hidden="true"></i>';
					$output .= '<i class="fa fa-star" aria-hidden="true"></i>';
					$output .= '<i class="fa fa-star-o" aria-hidden="true"></i>';
					$output .= '<i class="fa fa-star-o" aria-hidden="true"></i>';
				} elseif ($client_rating == 4) {
					$output .= '<i class="fa fa-star" aria-hidden="true"></i>';
					$output .= '<i class="fa fa-star" aria-hidden="true"></i>';
					$output .= '<i class="fa fa-star" aria-hidden="true"></i>';
					$output .= '<i class="fa fa-star" aria-hidden="true"></i>';
					$output .= '<i class="fa fa-star-o" aria-hidden="true"></i>';
				} elseif ($client_rating == 5) {
					$output .= '<i class="fa fa-star" aria-hidden="true"></i>';
					$output .= '<i class="fa fa-star" aria-hidden="true"></i>';
					$output .= '<i class="fa fa-star" aria-hidden="true"></i>';
					$output .= '<i class="fa fa-star" aria-hidden="true"></i>';
					$output .= '<i class="fa fa-star" aria-hidden="true"></i>';
				}
				$output .= '</div>';
			}
		}

		$output .= '<div class="sppb-addon-testimonial-review">';
		$output .= $review;
		$output .= '</div>';

		if ($designation_position !== 'top') {
			$output .= '<div class="sppb-addon-testimonial-footer">';
			$output .= $link ? '<a ' . $link_target . ' href="' . $link . '">' : '';
			$output .= '<div class="sppb-addon-testimonial-content-wrap">';
			$output .= $avatar_img ? '<img src="' . ($placeholder ? $placeholder : $avatar_img) . '" class="' . $avatar_shape . ' sppb-addon-testimonial-avatar' . ($placeholder ? ' sppb-element-lazy' : '') . '" alt="' . $name . '" ' . ($placeholder ? 'data-large="' . $avatar_img . '"' : '') . ' ' . ($avatar_width ? 'width="' . $avatar_width . '"' : '') . ' ' . ($avatar_height ? 'height="' . $avatar_height . '"' : '') . ' loading="lazy">' : '';
			$output .= '<span>';
			$output .= '<span class="sppb-addon-testimonial-client">' . $name . '</span>';
			$output .= '<br /><span class="sppb-addon-testimonial-client-url">' . $company . '</span>';
			$output .= '</span>';
			$output .= '</div>';
			$output .= $link ? '</a>' : '';
			$output .= '</div>';

			if ($client_rating_enable) {
				$output .= '<div class="sppb-addon-testimonial-rating">';

				if ($client_rating == 1) {
					$output .= '<i class="fas fa-star" aria-hidden="true"></i>';
					$output .= '<i class="fas fa-star-o" aria-hidden="true"></i>';
					$output .= '<i class="fas fa-star-o" aria-hidden="true"></i>';
					$output .= '<i class="fas fa-star-o" aria-hidden="true"></i>';
					$output .= '<i class="fas fa-star-o" aria-hidden="true"></i>';
				} elseif ($client_rating == 2) {
					$output .= '<i class="fas fa-star" aria-hidden="true"></i>';
					$output .= '<i class="fas fa-star" aria-hidden="true"></i>';
					$output .= '<i class="fas fa-star-o" aria-hidden="true"></i>';
					$output .= '<i class="fas fa-star-o" aria-hidden="true"></i>';
					$output .= '<i class="fas fa-star-o" aria-hidden="true"></i>';
				} elseif ($client_rating == 3) {
					$output .= '<i class="fas fa-star" aria-hidden="true"></i>';
					$output .= '<i class="fas fa-star" aria-hidden="true"></i>';
					$output .= '<i class="fas fa-star" aria-hidden="true"></i>';
					$output .= '<i class="fas fa-star-o" aria-hidden="true"></i>';
					$output .= '<i class="fas fa-star-o" aria-hidden="true"></i>';
				} elseif ($client_rating == 4) {
					$output .= '<i class="fas fa-star" aria-hidden="true"></i>';
					$output .= '<i class="fas fa-star" aria-hidden="true"></i>';
					$output .= '<i class="fas fa-star" aria-hidden="true"></i>';
					$output .= '<i class="fas fa-star" aria-hidden="true"></i>';
					$output .= '<i class="fas fa-star-o" aria-hidden="true"></i>';
				} elseif ($client_rating == 5) {
					$output .= '<i class="fas fa-star" aria-hidden="true"></i>';
					$output .= '<i class="fas fa-star" aria-hidden="true"></i>';
					$output .= '<i class="fas fa-star" aria-hidden="true"></i>';
					$output .= '<i class="fas fa-star" aria-hidden="true"></i>';
					$output .= '<i class="fas fa-star" aria-hidden="true"></i>';
				}

				$output .= '</div>';
			}
		}

		$output .= '</div>';
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
		$settings = $this->addon->settings;
		$addon_id = '#sppb-addon-' . $this->addon->id;
		$cssHelper = new CSSHelper($addon_id);

		$css = '';

		$settings->text_alignment = CSSHelper::parseAlignment($settings, 'alignment');
		$textAlignmentStyle 	= $cssHelper->generateStyle('.sppb-addon-testimonial', $settings, ['text_alignment' => 'text-align'], false);
		$settings->flex_alignment = CSSHelper::parseAlignment($settings, 'text_alignment', true);
		$flexAlignmentStyle 	= $cssHelper->generateStyle('.sppb-addon-testimonial-content-wrap', $settings, ['flex_alignment' => 'justify-content'], false);

		$reviewStyleProps = [
			'review_color' => 'color', 
			'review_margin' => 'margin',
		];

		$reviewStyle      = $cssHelper->generateStyle('.sppb-addon-testimonial-review', $settings, $reviewStyleProps, false, ['name_margin' => 'spacing']);

		$reviewFontStyle  = $cssHelper->typography('.sppb-addon-testimonial-review', $settings, 'review_typography', ['font' => 'review_font_family', 'size' => 'review_size', 'line_height' => 'review_line_height', 'weight' => 'review_fontweight']);
		$nameStyle        = $cssHelper->generateStyle('.sppb-addon-testimonial-footer .sppb-addon-testimonial-client', $settings, ['name_color' => 'color', 'name_margin' => 'margin'], false, ['name_margin' => 'spacing']);
		$nameFontStyle    = $cssHelper->typography('.sppb-addon-testimonial-footer .sppb-addon-testimonial-client', $settings, 'name_typography', ['font' => 'name_font_family', 'size' => 'name_font_size', 'line_height' => 'name_line_height', 'uppercase' => 'name_font_style.uppercase', 'italic' => 'name_font_style.italic', 'underline' => 'name_font_style.underline', 'weight' => 'name_font_style.weight']);
		$companyStyle     = $cssHelper->generateStyle('.sppb-addon-testimonial-footer .sppb-addon-testimonial-client-url', $settings, ['company_color' => 'color'], false);
		$companyFontStyle = $cssHelper->typography('.sppb-addon-testimonial-footer .sppb-addon-testimonial-client-url', $settings, 'designation_typography', ['font' => 'company_font_family', 'size' => 'company_font_size', 'line_height' => 'company_line_height', 'uppercase' => 'company_font_style.uppercase', 'italic' => 'company_font_style.italic', 'underline' => 'company_font_style.underline', 'weight' => 'company_font_style.weight']);
		$avatarStyle      = $cssHelper->generateStyle('.sppb-addon-testimonial-content-wrap img', $settings, ['avatar_width' => ['width', 'height'], 'avatar_margin' => 'margin'], ['avatar_margin' => false], ['avatar_margin' => 'spacing']);

		if (!empty($settings->avatar_dis_block)) {
			$avatarDisplayStyle = $cssHelper->generateStyle('.sppb-addon-testimonial-content-wrap > span,.sppb-addon-testimonial-content-wrap', $settings, [], false, null, null, false, 'display: block;');
			$css .= $avatarDisplayStyle;
		}

		$iconStyle          = $cssHelper->generateStyle('.sppb-addon-testimonial .fa-quote-left, .sppb-addon-testimonial .fa-quote-right', $settings, ['icon_color' => 'color', 'icon_size' => 'font-size'], ['icon_color' => false]);
		$ratingStyle        = $cssHelper->generateStyle('.sppb-addon-testimonial-rating i', $settings, ['client_rating_fontsize' => 'font-size', 'client_rating_margin' => 'margin'], ['client_rating_margin' => false], ['client_rating_margin' => 'spacing']);
		$clientRatingStyle  = $cssHelper->generateStyle('.sppb-addon-testimonial-rating i.fa-star', $settings, ['client_rating_color' => 'color'], false);
		$clientUnratedStyle = $cssHelper->generateStyle('.sppb-addon-testimonial-rating i.fa-star-o', $settings, ['client_unrated_color' => 'color'], false);

		$css .= $iconStyle;
		$css .= $nameStyle;
		$css .= $reviewStyle;
		$css .= $avatarStyle;
		$css .= $ratingStyle;
		$css .= $companyStyle;
		$css .= $nameFontStyle;
		$css .= $reviewFontStyle;
		$css .= $companyFontStyle;
		$css .= $clientRatingStyle;
		$css .= $textAlignmentStyle;
		$css .= $flexAlignmentStyle;
		$css .= $clientUnratedStyle;

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
				let avatar_position = data.avatar_position || "left"
				let avatar = ""
				if (typeof data.avatar !== "undefined" && typeof data.avatar.src !== "undefined") {
					avatar = data.avatar
				} else {
					avatar = {src: data.avatar}
				}
				let avatar_shape = data.avatar_shape || "sppb-avatar-circle";

				const urlObj = _.isObject(data.url) ? data.url : window.getSiteUrl(data?.link || "", data?.link_target || "");
				const {url, menu, page, type, new_tab, nofollow, noopener, noreferrer} = urlObj;
				const target = new_tab ? "_blank" : "";

				let relValue="";
				relValue += nofollow ? "nofollow" : "";
				relValue += noreferrer ? " noreferrer" : "";
				relValue += noopener ? " noopener" : "";
			
				let newUrl = "";
				if(type === "url" || !type) newUrl = url;
				if(type === "menu") newUrl = menu;
				if(type === "page") newUrl = page ? `index.php?option=com_sppagebuilder&view=page&id=${page}` : "";
				
				let reviewer_link = newUrl;
				let link_target = (new_tab)? "target=\'"+ target +"\'": "";
				let relfollow = (relValue)? relValue: "";

				if(!data.designation_position){
					data.designation_position = "bottom";
				}
			#>

		<style type="text/css">';

		$output .= '<# if (data.avatar_dis_block) { #>';
		$output .= '#sppb-addon-{{ data.id }} .sppb-addon-testimonial-content-wrap > span, #sppb-addon-{{ data.id }} .sppb-addon-testimonial-content-wrap { display:block; }';
		$output .= '<# } #>';

		$output .= '<# if (data.show_quote) { #>';
		$output .= $lodash->unit('font-size', '.sppb-addon-testimonial .fa-quote-left, .sppb-addon-testimonial .fa-quote-right', 'data.icon_size', 'px');
		$output .= $lodash->color('color', '.sppb-addon-testimonial .fa-quote-left, .sppb-addon-testimonial .fa-quote-right', 'data.icon_color');
		$output .= '<# } #>';
		$output .= '<# if (data.client_rating_enable) { #>';
		$output .= $lodash->unit('font-size', '.sppb-addon-testimonial-rating i', 'data.client_rating_fontsize', 'px');
		$output .= $lodash->spacing('margin', '.sppb-addon-testimonial-rating i', 'data.client_rating_margin');
		$output .= '<# } #>';
		$output .= $lodash->color('color', '.sppb-addon-testimonial-rating i.fa-star', 'data.client_rating_color');
		$output .= $lodash->color('color', '.sppb-addon-testimonial-rating i.fa-star-o', 'data.client_unrated_color');

		$output .= $lodash->alignment('text-align', '.sppb-addon-testimonial', 'data.alignment');
		$output .= $lodash->flexAlignment('.sppb-addon-testimonial-content-wrap', 'data.alignment');

		// Review
		$reviewTypographyFallbacks = [
			'font'        => 'data.review_font_family',
			'size'        => 'data.review_size',
			'line_height' => 'data.review_line_height',
			'weight'      => 'data.review_fontweight',
		];

		$output .= $lodash->typography('.sppb-addon-testimonial-review', 'data.review_typography', $reviewTypographyFallbacks);
		// Name
		$nameTypographyFallbacks = [
			'font'        => 'data.name_font_family',
			'size'        => 'data.name_font_size',
			'line_height' => 'data.name_line_height',
			'uppercase'   => 'data.name_font_style?.uppercase',
			'italic'      => 'data.name_font_style?.italic',
			'underline'   => 'data.name_font_style?.underline',
			'weight'      => 'data.name_font_style?.weight',
		];

		$output .= $lodash->typography('.sppb-addon-testimonial-footer .sppb-addon-testimonial-client', 'data.name_typography', $nameTypographyFallbacks);
		// Designation
		$designationTypographyFallbacks = [
			'font'        => 'data.company_font_family',
			'size'        => 'data.company_font_size',
			'line_height' => 'data.company_line_height',
			'uppercase'   => 'data.company_font_style?.uppercase',
			'italic'      => 'data.company_font_style?.italic',
			'underline'   => 'data.company_font_style?.underline',
			'weight'      => 'data.company_font_style?.weight',
		];
		$output .= $lodash->typography('.sppb-addon-testimonial-footer .sppb-addon-testimonial-client-url', 'data.designation_typography', $designationTypographyFallbacks);
		// Title
		$titleTypographyFallbacks = [
			'font'           => 'data.title_font_family',
			'size'           => 'data.title_fontsize',
			'line_height'    => 'data.title_lineheight',
			'letter_spacing' => 'data.title_letterspace',
			'uppercase'      => 'data.title_font_style?.uppercase',
			'italic'         => 'data.title_font_style?.italic',
			'underline'      => 'data.title_font_style?.underline',
			'weight'         => 'data.title_font_style?.weight',
		];

		$output .= $lodash->typography('.sppb-addon-testimonial .sppb-addon-title', 'data.title_typography', $titleTypographyFallbacks);
		$output .= $lodash->color('color', '.sppb-addon-testimonial-review', 'data.review_color');
		$output .= $lodash->color('color', '.sppb-addon-testimonial-footer .sppb-addon-testimonial-client', 'data.name_color');
		$output .= $lodash->color('color', '.sppb-addon-testimonial-footer .sppb-addon-testimonial-client-url', 'data.company_color');
		$output .= $lodash->spacing('margin', '.sppb-addon-testimonial-review', 'data.review_margin');
		$output .= $lodash->spacing('margin', '.sppb-addon-testimonial-footer .sppb-addon-testimonial-client', 'data.name_margin');
		$output .= $lodash->spacing('margin', '.sppb-addon-testimonial-footer .sppb-addon-testimonial-client-url', 'data.client_rating_margin');
		$output .= $lodash->spacing('margin', '.sppb-addon-testimonial-content-wrap img', 'data.avatar_margin');
		$output .= $lodash->unit('height', '.sppb-addon-testimonial-content-wrap img', 'data.avatar_width', 'px');
		$output .= $lodash->unit('width', '.sppb-addon-testimonial-content-wrap img', 'data.avatar_width', 'px');

		$output .= '
			</style>

			<div class="sppb-addon sppb-addon-testimonial {{ data.class }}">
				<# if( !_.isEmpty( data.title ) ){ #><{{ data.heading_selector }} class="sppb-addon-title sp-inline-editable-element" data-id={{data.id}} data-fieldName="title" contenteditable="true">{{ data.title }}</{{ data.heading_selector }}><# } #>
				<div class="sppb-addon-content">
					<# if(data.show_quote && data.designation_position !== "top"){ #>
						<span class="fa fa-quote-left"></span>
					<# } #>
					<# if(data.designation_position == "top") { #>
						<div class="sppb-testimonial-top-content sppb-addon-testimonial-footer">
							<# if (reviewer_link) { #>
								<a {{ link_target }} rel=\'{{ relfollow }}\' href=\'{{ reviewer_link }}\'>
							<# } #>
							<div class="sppb-addon-testimonial-content-wrap">
							<# if (avatar.src && avatar.src.indexOf("https://") == -1 && avatar.src.indexOf("http://") == -1) { #>
								<img class="{{ avatar_shape }} sppb-addon-testimonial-avatar" src=\'{{ pagebuilder_base + avatar.src }}\' width="{{ data.avatar_width }}" height="{{ data.avatar_width }}" alt="{{ data.name }}">
							<# } else if(avatar){ #>
								<img class="{{ avatar_shape }} sppb-addon-testimonial-avatar" src=\'{{ avatar.src }}\' width="{{ data.avatar_width }}" height="{{ data.avatar_width }}" alt="{{ data.name }}">
							<# } #>
							<span>
								<span class="sppb-addon-testimonial-client">{{ data.name }}</span>
								<br /><span class="sppb-addon-testimonial-client-url">{{ data.company }}</span>
							</span>
							</div>
							<# if (reviewer_link) { #>
								</a>
							<# } #>
							<# if(data.show_quote){ #>
								<span class="fa fa-quote-right"></span>
							<# } #>
						</div>
						<# if(data.client_rating_enable){ #>
							<div class="sppb-addon-testimonial-rating">
							<# if(data.client_rating == 1){ #>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star-o" aria-hidden="true"></i>
								<i class="fa fa-star-o" aria-hidden="true"></i>
								<i class="fa fa-star-o" aria-hidden="true"></i>
								<i class="fa fa-star-o" aria-hidden="true"></i>
							<# } else if(data.client_rating == 2){ #>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star-o" aria-hidden="true"></i>
								<i class="fa fa-star-o" aria-hidden="true"></i>
								<i class="fa fa-star-o" aria-hidden="true"></i>
							<# } else if(data.client_rating == 3){ #>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star-o" aria-hidden="true"></i>
								<i class="fa fa-star-o" aria-hidden="true"></i>
							<# } else if(data.client_rating == 4){ #>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star-o" aria-hidden="true"></i>
							<# } else if(data.client_rating == 5){ #>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
							<# } #>
							</div>
						<# }
					} #>
					<div id="addon-review-{{data.id}}" class="sppb-addon-testimonial-review sp-editable-content" data-id={{data.id}} data-fieldName="review">
					{{{ data.review }}}
					</div>
					<# if(data.designation_position !== "top") { #>
						<div class="sppb-addon-testimonial-footer">
						<# if (reviewer_link) { #>
							<a {{ link_target }} rel=\'{{ relfollow }}\' href=\'{{ reviewer_link }}\'>
						<# } #>
						<div class="sppb-addon-testimonial-content-wrap">
						<# if (avatar.src && avatar.src.indexOf("https://") == -1 && avatar.src.indexOf("http://") == -1) { #>
							<img class="{{ avatar_shape }} sppb-addon-testimonial-avatar" src=\'{{ pagebuilder_base + avatar.src }}\' width="{{ data.avatar_width }}" height="{{ data.avatar_width }}" alt="{{ data.name }}">
						<# } else if(avatar.src){ #>
							<img class="{{ avatar_shape }} sppb-addon-testimonial-avatar" src=\'{{ avatar.src }}\' width="{{ data.avatar_width }}" height="{{ data.avatar_width }}" alt="{{ data.name }}">
						<# } #>
						<span>
							<span class="sppb-addon-testimonial-client">{{ data.name }}</span>
							<br /><span class="sppb-addon-testimonial-client-url">{{ data.company }}</span>
						</span>
						</div>
						<# if (reviewer_link) { #>
							</a>
						<# } #>
						</div>
						<# if(data.client_rating_enable){ #>
							<div class="sppb-addon-testimonial-rating">
							<# if(data.client_rating == 1){ #>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star-o" aria-hidden="true"></i>
								<i class="fa fa-star-o" aria-hidden="true"></i>
								<i class="fa fa-star-o" aria-hidden="true"></i>
								<i class="fa fa-star-o" aria-hidden="true"></i>
							<# } else if(data.client_rating == 2){ #>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star-o" aria-hidden="true"></i>
								<i class="fa fa-star-o" aria-hidden="true"></i>
								<i class="fa fa-star-o" aria-hidden="true"></i>
							<# } else if(data.client_rating == 3){ #>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star-o" aria-hidden="true"></i>
								<i class="fa fa-star-o" aria-hidden="true"></i>
							<# } else if(data.client_rating == 4){ #>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star-o" aria-hidden="true"></i>
							<# } else if(data.client_rating == 5){ #>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
							<# } #>
							</div>
						<# }
					} #>
				</div>
			</div>
			';

		return $output;
	}
}