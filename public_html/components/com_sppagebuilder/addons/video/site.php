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
use Joomla\CMS\Language\Text;
use Joomla\CMS\Component\ComponentHelper;

class SppagebuilderAddonVideo extends SppagebuilderAddons
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
		$heading_selector = (isset($settings->heading_selector) && $settings->heading_selector) ? $settings->heading_selector : 'h3';

		// Options
		$url 			= (isset($settings->url) && $settings->url) ? $settings->url : '';
		$video_title 	= "";
		$no_cookie 		= (isset($settings->no_cookie) && $settings->no_cookie) ? $settings->no_cookie : 0;
		$show_rel_video = (isset($settings->show_rel_video) && $settings->show_rel_video) ? '&rel=0' : '&rel=1';
		$youtube_shorts = (isset($settings->youtube_shorts) && $settings->youtube_shorts) ? $settings->youtube_shorts : 0;
		$aspect_ratio   = (isset($settings->aspect_ratio) && $settings->aspect_ratio && $youtube_shorts) ? $settings->aspect_ratio : '16by9';

		$mp4_enable = (isset($settings->mp4_enable) && $settings->mp4_enable) ? $settings->mp4_enable : 0;
		$video_mute = (isset($settings->video_mute) && $settings->video_mute) ? $settings->video_mute : 0;

		$mp4_video = (isset($settings->mp4_video) && $settings->mp4_video) ? $settings->mp4_video : '';
		$mp4_video_src = isset($mp4_video->src) ? $mp4_video->src : $mp4_video;

		$vimeo_show_author 			= (isset($settings->vimeo_show_author) && $settings->vimeo_show_author) ? "byline=1" : "byline=0";
		$vimeo_mute_video  			= (isset($settings->vimeo_mute_video) && $settings->vimeo_mute_video) ? "muted=1" : "muted=0";
		$vimeo_show_video_title  	= (isset($settings->vimeo_show_video_title) && $settings->vimeo_show_video_title) ? "title=1" : "title=0";
		$vimeo_show_author_profile  = (isset($settings->vimeo_show_author_profile) && $settings->vimeo_show_author_profile) ? "portrait=1" : "portrait=0";

		if ($mp4_video_src && (strpos($mp4_video_src, "http://") !== false || strpos($mp4_video_src, "https://") !== false))
		{
			$mp4_video = $mp4_video_src;
		}
		else
		{
			if (!empty($mp4_video))
			{
				$mp4_video = Uri::base(true) . '/' . $mp4_video_src;
			}
		}

		$ogv_video = (isset($settings->ogv_video) && $settings->ogv_video) ? $settings->ogv_video : '';
		$ogv_video_src = isset($ogv_video->src) ? $ogv_video->src : $ogv_video;

		if ($ogv_video_src && (strpos($ogv_video_src, "http://") !== false || strpos($ogv_video_src, "https://") !== false))
		{
			$ogv_video = $ogv_video_src;
		}
		else
		{
			if (!empty($ogv_video))
			{
				$ogv_video = Uri::base(true) . '/' . $ogv_video_src;
			}
		}

		$show_control = (isset($settings->show_control) && $settings->show_control) ? $settings->show_control : 0;
		$video_loop = (isset($settings->video_loop) && $settings->video_loop) ? $settings->video_loop : 0;
		$autoplay_video = (isset($settings->autoplay_video) && $settings->autoplay_video) ? $settings->autoplay_video : 0;
		$video_poster = (isset($settings->video_poster) && $settings->video_poster) ? $settings->video_poster : '';
		$video_poster_src = isset($video_poster->src) ? $video_poster->src : $video_poster;

		if ($video_poster_src && (strpos($video_poster_src, "http://") !== false || strpos($video_poster_src, "https://") !== false))
		{
			$video_poster = $video_poster_src;
		}
		else
		{
			if (!empty($video_poster))
			{
				$video_poster = Uri::base(true) . '/' . $video_poster_src;
			}
		}

		// Image lazy load
		$config = ComponentHelper::getParams('com_sppagebuilder');
		$lazyload = $config->get('lazyloadimg', '0');

		// Output
		$output  = '';
		$src = '';

		if ($url)
		{
			$video = parse_url($url);

			$youtube_no_cookie = $no_cookie ? '-nocookie' : '';

			switch ($video['host'])
			{
				case 'youtu.be':
					$id 		 = trim($video['path'], '/');
					$src 		 = '//www.youtube' . $youtube_no_cookie . '.com/embed/' . $id . '?iv_load_policy=3' . $show_rel_video;
					$video_title = (isset($settings->video_title) && $settings->video_title) ? $settings->video_title : Text::_("COM_SPPAGEBUILDER_ADDON_VIDEO_TITLE_DEFAULT_TEXT");
					break;

				case 'www.youtube.com':
				case 'youtube.com':
					parse_str($video['query'], $query);
					$id  		 = ($youtube_shorts) ? trim($video['path'], '/shorts/') : $query['v'];			
					$src 		 = '//www.youtube' . $youtube_no_cookie . '.com/embed/' . $id . '?iv_load_policy=3' . $show_rel_video;
					$video_title = (isset($settings->video_title) && $settings->video_title) ? $settings->video_title : Text::_("COM_SPPAGEBUILDER_ADDON_VIDEO_TITLE_DEFAULT_TEXT");
					break;
					
				case 'vimeo.com':
				case 'www.vimeo.com':
					$id = trim($video['path'], '/');
					$initialSrc = "//player.vimeo.com/video/{$id}";
					$embeddedParameter = array($vimeo_mute_video, $vimeo_show_author, $vimeo_show_author_profile, $vimeo_show_video_title);
					$src = self::setEmbeddedParameter($embeddedParameter, $initialSrc);
			}
		}

		// Lazy load ifram
		$placeholder = $src == '' ? false : $this->get_image_placeholder($src);

		$output  .= '<div class="sppb-addon sppb-addon-video ' . $class . '">';
		$output .= ($title) ? '<' . $heading_selector . ' class="sppb-addon-title">' . $title . '</' . $heading_selector . '>' : '';
		if ($mp4_enable != 1)
		{			
			$output .= '<div class="sppb-video-block sppb-embed-responsive sppb-embed-responsive-'.$aspect_ratio.'">';
			$output .= '<iframe class="sppb-embed-responsive-item' . ($placeholder ? ' sppb-element-lazy' : '') . '" ' . ($placeholder ? 'style="background:url(' . $placeholder . '); background-size: cover;"' : 'src="' . $src . '"') . ' ' . ($placeholder ? 'data-large="' . $src . '"' : '') . ' ' . ($video_title ? 'title="'.$video_title. '"' : '') . ' allow="accelerometer" webkitAllowFullScreen mozallowfullscreen allowFullScreen loading="lazy" ></iframe>';
			$output .= '</div>';
			
		}
		else
		{
			if ($mp4_video || $ogv_video)
			{
				$output .= '<div class="sppb-addon-video-local-video-wrap">';
				$output .= '<video class="sppb-addon-video-local-source' . ($placeholder ? ' sppb-element-lazy' : '') . '"' . ($video_loop != 0 ? ' loop' : '') . '' . ($autoplay_video != 0 ? ' autoplay' : '') . '' . ($show_control != 0 ? ' controls' : '') . '' . ($video_mute ? ' muted' : '') . ' ' . ($lazyload ? 'data-poster="' . $video_poster . '"' : ' poster="' . $video_poster . '"') . ' controlsList="nodownload" playsinline>';
				if (!empty($mp4_video))
				{
					$output .= '<source ' . ($lazyload ? 'data-large="' . $mp4_video . '"' : 'src="' . $mp4_video . '"') . ' type="video/mp4">';
				}
				if (!empty($ogv_video))
				{
					$output .= '<source ' . ($lazyload ? 'data-large="' . $ogv_video . '"' : 'src="' . $ogv_video . '"') . ' type="video/ogg">';
				}
				$output .= '</video>';
				$output .= '</div>';
			}
		}

		$output .= '</div>';

		return $output;
	}

	/**
	 * Generate the lodash template string for the frontend editor.
	 *
	 * @return 	string 	The lodash template string.
	 * @since 	1.0.0
	 */
	public static function getTemplate()
	{

		$lodash = new Lodash("#sppb-addon-{{data.id}}");
		$output = '

			<#
				let videoUrl 		  = data.url || ""
				let video_title		  = "";
				let show_rel_video 	  = (typeof data.show_rel_video !== "undefined" && data.show_rel_video) ? "&rel=0" : "&rel=1";
				let embedSrc 		  = ""
				let youtube_no_cookie = data.no_cookie ? "-nocookie" : ""			
				let youtube_shorts 	  = data.youtube_shorts ? data.youtube_shorts : 0;
				let aspect_ratio	  = (data.aspect_ratio && youtube_shorts) ? data.aspect_ratio : "16by9";
				let mp4_enable 		  = (typeof data.mp4_enable == "undefined") ? 0 : data.mp4_enable;
				
				let vimeo_show_author 	   	   = data.vimeo_show_author ? "byline=1" : "byline=0";
				let vimeo_mute_video  	   	   = data.vimeo_mute_video ? "muted=1" : "muted=0";
				let vimeo_show_video_title 	   = data.vimeo_show_video_title ? "title=1" : "title=0";
				let vimeo_show_author_profile  = data.vimeo_show_author_profile ? "portrait=1" : "portrait=0";

				const embeddedParameter = [vimeo_show_author,vimeo_mute_video,vimeo_show_video_title,vimeo_show_author_profile];
				const separator 		= "&";
				let embeddedString      = "";

				let mp4_video = (!_.isEmpty(data.mp4_video) && data.mp4_video) ? data.mp4_video : "https://www.joomshaper.com/media/videos/2017/11/10/pb-intro-video.mp4";

				if (typeof mp4_video !== "undefined" && typeof mp4_video.src !== "undefined") {
					mp4_video = data.mp4_video
				} else {
					mp4_video = {src: data.mp4_video}
				}

				let ogv_video = (!_.isEmpty(data.ogv_video) && data.ogv_video) ? data.ogv_video : "https://www.joomshaper.com/media/videos/2017/11/10/pb-intro-video.mp4";

				if (typeof ogv_video !== "undefined" && typeof ogv_video.src !== "undefined") {
					ogv_video = data.ogv_video
				} else {
					ogv_video = {src: data.ogv_video}
				}

				let video_poster = (!_.isEmpty(data.video_poster) && data.video_poster) ? data.video_poster : "https://www.joomshaper.com/images/2017/11/10/real-time-frontend.jpg";
				
				if (typeof data.video_poster !== "undefined" && typeof data.video_poster.src !== "undefined") {
					video_poster = data.video_poster
				} else {
					video_poster = {src: data.video_poster}
				}

				if ( videoUrl ) {
					let tempAchor = document.createElement("a")
						tempAchor.href = videoUrl

					let videoObj = {
						host    :   tempAchor.hostname,
						path    :   tempAchor.pathname,
						query   :   tempAchor.search.substr(tempAchor.search.indexOf("?") + 1)
					}

					switch( videoObj.host ){
						case "youtu.be":
							var videoId = videoObj.path.trim();
							embedSrc 	= "//www.youtube"+youtube_no_cookie+".com/embed"+ videoId + "?iv_load_policy=3"+ show_rel_video
							video_title = data.video_title ? data.video_title : Joomla.Text._("COM_SPPAGEBUILDER_ADDON_VIDEO_TITLE_DEFAULT_TEXT");
							break;

						case "www.youtube.com":
						case "youtube.com":
							
							var queryStr = (youtube_shorts) ? videoObj.path.split("/shorts/") : videoObj.query.split("=");									
							embedSrc 	 = "//www.youtube"+youtube_no_cookie+".com/embed/"+ queryStr[1]+ "?iv_load_policy=3"+ show_rel_video
							video_title  = data.video_title ? data.video_title : Joomla.Text._("COM_SPPAGEBUILDER_ADDON_VIDEO_TITLE_DEFAULT_TEXT");
							break;

						case "www.vimeo.com":
						case "vimeo.com":
							
							var videoId  = videoObj.path.trim();
							embedSrc 	 = "//player.vimeo.com/video"+ videoId;

							_.forEach(embeddedParameter, function(value,key){
								embeddedString += (key > 0) ? separator + value : value;
							});
							
							embedSrc = embedSrc + "?" + embeddedString;							
							break;
					}
				}
			#>';
		$output .= '<style type="text/css">';
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
		$output .= $lodash->typography('.sppb-addon-title', 'data.title_typography', $titleTypographyFallbacks);
		$output .= '
			</style>
	 		<div class="sppb-addon sppb-addon-video {{ data.class }}">
		 		<# if( !_.isEmpty( data.title ) ){ #><{{ data.heading_selector }} class="sppb-addon-title sp-inline-editable-element" data-id={{data.id}} data-fieldName="title" contenteditable="true">{{{ data.title }}}</{{ data.heading_selector }}><# } #>
				<# if(mp4_enable != 1){ #>
					<div class="sppb-iframe-drag-overlay"></div>

					<div class="sppb-video-block sppb-embed-responsive sppb-embed-responsive-{{ aspect_ratio }}">
						<# if(embedSrc){ #>
						<iframe class="sppb-embed-responsive-item" src=\'{{ embedSrc }}\' title= \'{{ video_title }}\' allow="accelerometer"; webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>						
					</div>
					<# } #>
				 <# } else {
					if(mp4_video.src || ogv_video.src){
						#>
						<div class="sppb-addon-video-local-video-wrap">
							<video class="sppb-addon-video-local-source"{{(data.video_loop != 0 ? " loop" : "")}}{{(data.autoplay_video != 0 ? " autoplay" : "")}}{{(data.show_control != 0 ? " controls" : "")}}{{(data.video_mute != 0 ? " muted" : "")}}
							<# if(!_.isEmpty(video_poster.src)){
							if(video_poster.src.indexOf("http://") == -1 && video_poster.src.indexOf("https://") == -1){ #>
								poster=\'{{ pagebuilder_base + video_poster.src }}\'
							<# } else { #>
								poster=\'{{ video_poster.src }}\'
							<# }
							} #> 
							controlsList="nodownload">
							<# if(!_.isEmpty(mp4_video.src)){ #>
								<# if(mp4_video.src.indexOf("http://") == -1 && mp4_video.src.indexOf("https://") == -1){ #>
									<source src=\'{{ pagebuilder_base + mp4_video.src }}\' type="video/mp4">
								<# } else { #>
									<source src=\'{{ mp4_video.src }}\' type="video/mp4">
								<# } #> 
							<# }
							if(!_.isEmpty(ogv_video.src)){
							#>
								<# if(ogv_video.src.indexOf("http://") == -1 && ogv_video.src.indexOf("https://") == -1){ #>
									<source src=\'{{ pagebuilder_base + ogv_video.src }}\' type="video/mp4">
								<# } else { #>
									<source src=\'{{ ogv_video.src }}\' type="video/mp4">
								<# } #>
							<# } #>
							</video>
						</div>
					<# } #>
				<# } #>
	 		</div>
		';

		return $output;
	}

	/**
	 * Set embedded settings for vimeo video player
	 *
	 * @param  array 	$embeddedParameter   Array of embedded settings which will be added on vimeo player.
	 * @param  string 	$src				 "src" attribute of iframe
	 * @return void
	 * @since  4.0.8
	 */
	public static function setEmbeddedParameter($embeddedParameter, $src){
		
		$embeddedString = "";
		$separator 		= "&";	
			
		foreach ($embeddedParameter as $key => $value) {
			$embeddedString .= ($key > 0) ? $separator . $value : $value ;
		}

		$src = $src . '?' . $embeddedString;			
		return $src;
	}
}