<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct access
defined ('_JEXEC') or die ('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Access\Access;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\Utilities\ArrayHelper;

if(!class_exists('ContentHelperRoute')) require_once (JPATH_SITE . '/components/com_content/helpers/route.php');

abstract class SppagebuilderHelperArticles
{
	public static function getArticles( $count = 5, $ordering = 'latest', $catid = '', $include_subcategories = true, $post_format = '', $tagids = array() ) {

		$authorised = Access::getAuthorisedViewLevels(Factory::getUser()->get('id'));

		$app = Factory::getApplication();
		$db = Factory::getDbo();
		$nullDate = $db->quote($db->getNullDate());
		$nowDate  = $db->quote(Factory::getDate()->toSql());

		$query = $db->getQuery(true);

		$query
		->select('a.*')
		->from($db->quoteName('#__content', 'a'))
		->select($db->quoteName('b.alias', 'category_alias'))
		->select($db->quoteName('b.title', 'category'))
		->join('LEFT', $db->quoteName('#__categories', 'b') . ' ON (' . $db->quoteName('a.catid') . ' = ' . $db->quoteName('b.id') . ')')
		->where($db->quoteName('b.extension') . ' = ' . $db->quote('com_content'));

		if($post_format) {
			$query->where('('.$db->quoteName('a.attribs') . ' LIKE ' . $db->quote('%"post_format":"'. $post_format .'"%') . ' OR ' . $db->quoteName('a.attribs') . ' LIKE ' . $db->quote('%"helix_ultimate_article_format":"'. $post_format .'"%').')');
		}

		$query->where($db->quoteName('a.state') . ' = ' . $db->quote(1));

		// Category filter
		if (!is_array($catid))
		{
			$catid = [$catid];
		}

		if (!empty($catid))
		{
			
			$categories = self::getCategories($catid, $include_subcategories );
		
			$categories = array_filter(array_merge($categories, $catid));
	
			$query->where($db->quoteName('a.catid')." IN (" . implode( ',', $categories ) . ")");
		}

		// tags filter
		if (is_array($tagids) && count($tagids)) {
			$tagId = implode(',', ArrayHelper::toInteger($tagids));
			if ($tagId) {
				$subQuery = $db->getQuery(true)
					->select('DISTINCT content_item_id')
					->from($db->quoteName('#__contentitem_tag_map'))
					->where('tag_id IN (' . $tagId . ')')
					->where('type_alias = ' . $db->quote('com_content.article'));

				$query->innerJoin('(' . (string) $subQuery . ') AS tagmap ON tagmap.content_item_id = a.id');
			}
		}
		
		// publishing
		if ( JVERSION < 4)
		{
			$query->where('(a.publish_up = ' . $nullDate . ' OR a.publish_up <= ' . $nowDate . ')');
			$query->where('(a.publish_down = ' . $nullDate . ' OR a.publish_down >= ' . $nowDate . ')');
		}
		else
		{
			$nowDate = Factory::getDate()->toSql();
			$query->extendWhere(
				'AND',
				[
					$db->quoteName('a.publish_up') . ' IS NULL',
					$db->quoteName('a.publish_up') . ' <= :publishUp',
				],
				'OR'
			)->extendWhere(
				'AND',
				[
					$db->quoteName('a.publish_down') . ' IS NULL',
					$db->quoteName('a.publish_down') . ' >= :publishDown',
				],
				'OR'
			)->bind([':publishUp', ':publishDown'], $nowDate);
		}

		// has order by
		if ($ordering == 'hits') {
			$query->order($db->quoteName('a.hits') . ' DESC');
		} elseif($ordering == 'featured') {
			$query->where($db->quoteName('a.featured') . ' = ' . $db->quote(1));
			$query->order($db->quoteName('a.publish_up') . ' DESC');
		} elseif($ordering == 'oldest') {
			$query->order($db->quoteName('a.publish_up') . ' ASC');
		} elseif($ordering == 'alphabet_asc') {
			$query->order($db->quoteName('a.title') . ' ASC');
		} elseif($ordering == 'alphabet_desc') {
			$query->order($db->quoteName('a.title') . ' DESC');
		} elseif($ordering == 'random') {
			$query->order($query->Rand());
		} else {
			$query->order($db->quoteName('a.publish_up') . ' DESC');
		}

		// Language filter
		if ($app->isClient('site') && $app->getLanguageFilter()) {
			$query->where('a.language IN (' . $db->Quote(Factory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')');
		}

		// continue query
		$query->where($db->quoteName('a.access')." IN (" . implode( ',', $authorised ) . ")");
		$query->order($db->quoteName('a.created') . ' DESC')
		->setLimit($count);
		$db->setQuery($query);
		$items = $db->loadObjectList();

		

		foreach ($items as &$item) {
			
			$item->slug    	= $item->id . ':' . $item->alias;
			$item->catslug 	= $item->catid . ':' . $item->category_alias;
			$item->username = Factory::getUser($item->created_by)->name;
			$item->link 	= Route::_(version_compare(JVERSION, '4.0.0', '>=') ? Joomla\Component\Content\Site\Helper\RouteHelper::getArticleRoute($item->slug, $item->catid, $item->language) : ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language));
			$attribs 		= json_decode($item->attribs);

			$item->tags = new TagsHelper;
			$item->tags->getItemTags('com_content.article', $item->id);

			$feature_img = '';
			if (isset($attribs->helix_ultimate_image) && $attribs->helix_ultimate_image) {
				$feature_img = $attribs->helix_ultimate_image;
			} elseif (isset($attribs->spfeatured_image) && $attribs->spfeatured_image) {
				$feature_img = $attribs->spfeatured_image;
			}

			// Featured Image
			if(isset($feature_img) && $feature_img != NULL) {
				$item->featured_image = $featured_image = $feature_img;

				$img_baseurl = basename($featured_image);

				//Small
				$small = JPATH_ROOT . '/' . dirname($featured_image) . '/' . File::stripExt($img_baseurl) .  '_small.' . File::getExt($img_baseurl);
				if(file_exists($small)) {
					$item->image_small = Uri::root(true) . '/' . dirname($featured_image) . '/' . File::stripExt($img_baseurl) . '_small.' . File::getExt($img_baseurl);
				}

				//Thumb
				$thumbnail = JPATH_ROOT . '/' . dirname($featured_image) . '/' . File::stripExt($img_baseurl) .  '_thumbnail.' . File::getExt($img_baseurl);
				if(file_exists($thumbnail)) {
					$item->image_thumbnail = Uri::root(true) . '/' . dirname($featured_image) . '/' . File::stripExt($img_baseurl) . '_thumbnail.' . File::getExt($img_baseurl);
				} else {
					$item->image_thumbnail = Uri::root(true) . '/' . $item->featured_image;
				}

				//Medium
				$medium = JPATH_ROOT . '/' . dirname($featured_image) . '/' . File::stripExt($img_baseurl) .  '_medium.' . File::getExt($img_baseurl);
				if(file_exists($medium)) {
					$item->image_medium = Uri::root(true) . '/' . dirname($featured_image) . '/' . File::stripExt($img_baseurl) . '_medium.' . File::getExt($img_baseurl);
				}

				//Large
				$large = JPATH_ROOT . '/' . dirname($featured_image) . '/' . File::stripExt($img_baseurl) .  '_large.' . File::getExt($img_baseurl);
				if(file_exists($large)) {
					$item->image_large = Uri::root(true) . '/' . dirname($featured_image) . '/' . File::stripExt($img_baseurl) . '_large.' . File::getExt($img_baseurl);
				}
			} else {
				$images = json_decode($item->images);
				if(isset($images->image_intro) && $images->image_intro) {
					if(strpos($images->image_intro, "http://") !== false || strpos($images->image_intro, "https://") !== false){
						$item->image_thumbnail = $images->image_intro;
					} else {
						$item->image_thumbnail = Uri::root(true) . '/' . $images->image_intro;
					}
				} elseif (isset($images->image_fulltext) && $images->image_fulltext) {
					if(strpos($images->image_fulltext, "http://") !== false || strpos($images->image_fulltext, "https://") !== false){
						$item->image_thumbnail = $images->image_fulltext;
					} else {
						$item->image_thumbnail = Uri::root(true) . '/' . $images->image_fulltext;
					}
				} else {
					$item->image_thumbnail = false;
				}
			}

			// Post Format
			$item->post_format = 'standard';
			if(isset($attribs->helix_ultimate_article_format) && $attribs->helix_ultimate_article_format != '') {
				$item->post_format = $attribs->helix_ultimate_article_format;
			} elseif(isset($attribs->post_format) && $attribs->post_format != '') {
				$item->post_format = $attribs->post_format;
			}

			// Post Format Video
			if(isset($item->post_format) && $item->post_format == 'video') {
				
				$video_url = '';
				if (isset($attribs->helix_ultimate_video) && $attribs->helix_ultimate_video) {
					$video_url = $attribs->helix_ultimate_video;
				} elseif (isset($attribs->video) && $attribs->video) {
					$video_url = $attribs->video;
				}

				if(isset($video_url) && $video_url != NULL) {
					$video = parse_url($video_url);
					$video_src = '';
					switch($video['host']) {
						case 'youtu.be':
						$video_id 	= trim($video['path'],'/');
						$video_src 	= '//www.youtube.com/embed/' . $video_id;
						break;

						case 'www.youtube.com':
						case 'youtube.com':
						parse_str($video['query'], $query);
						$video_id 	= $query['v'];
						$video_src 	= '//www.youtube.com/embed/' . $video_id;
						break;

						case 'vimeo.com':
						case 'www.vimeo.com':
						$video_id 	= trim($video['path'],'/');
						$video_src 	= "//player.vimeo.com/video/" . $video_id;
					}

					$item->video_src = $video_src;
				} else {
					$item->video_src = '';
				}

				
			}

			// Post Format Audio
			if(isset($item->post_format) && $item->post_format == 'audio') {

				$audio_url = '';
				if (isset($attribs->helix_ultimate_audio) && $attribs->helix_ultimate_audio) {
					$audio_url = $attribs->helix_ultimate_audio;
				} elseif (isset($attribs->audio) && $attribs->audio) {
					$audio_url = $attribs->audio;
				}

				if(isset($audio_url) && $audio_url != NULL) {
					$item->audio_embed = $audio_url;
				} else {
					$item->audio_embed = '';
				}
			}

			// Post Format Quote
			if(isset($item->post_format) && $item->post_format == 'quote') {
				if(isset($attribs->quote_text) && $attribs->quote_text != NULL) {
					$item->quote_text = $attribs->quote_text;
				} else {
					$item->quote_text = '';
				}

				if(isset($attribs->quote_author) && $attribs->quote_author != NULL) {
					$item->quote_author = $attribs->quote_author;
				} else {
					$item->quote_author = '';
				}
			}

			// Post Format Status
			if(isset($item->post_format) && $item->post_format == 'status') {
				if(isset($attribs->post_status) && $attribs->post_status != NULL) {
					$item->post_status = $attribs->post_status;
				} else {
					$item->post_status = '';
				}
			}

			// Post Format Link
			if(isset($item->post_format) && $item->post_format == 'link') {
				if(isset($attribs->link_title) && $attribs->link_title != NULL) {
					$item->link_title = $attribs->link_title;
				} else {
					$item->link_title = '';
				}

				if(isset($attribs->link_url) && $attribs->link_url != NULL) {
					$item->link_url = $attribs->link_url;
				} else {
					$item->link_url = '';
				}
			}

			// Post Format Gallery
			if(isset($item->post_format) && $item->post_format == 'gallery') {

				$gallery_imgs = '';
				if (isset($attribs->helix_ultimate_gallery) && $attribs->helix_ultimate_gallery) {
					$gallery_imgs = $attribs->helix_ultimate_gallery;
				} elseif (isset($attribs->gallery) && $attribs->gallery) {
					$gallery_imgs = $attribs->gallery;
				}

				$item->imagegallery = new stdClass();
				$gallery_imgs;

				if(isset($gallery_imgs) && $gallery_imgs != NULL) {
					$gallery_img_decode = json_decode($gallery_imgs);
					$gallery_all_images = '';
					if (isset($gallery_img_decode->helix_ultimate_gallery_images) && $gallery_img_decode->helix_ultimate_gallery_images) {
						$gallery_all_images = $gallery_img_decode->helix_ultimate_gallery_images;
					} elseif (isset($gallery_img_decode->gallery_images) && $gallery_img_decode->gallery_images) {
						$gallery_all_images = $gallery_img_decode->gallery_images;
					}
					
					$gallery_images = array();
					if(isset($gallery_all_images) && is_array($gallery_all_images)){
						foreach ($gallery_all_images as $key=>$value) {
							$gallery_images[$key]['full'] = $value;
							$gallery_img_baseurl = basename($value);

							//Small
							$small = JPATH_ROOT . '/' . dirname($value) . '/' . File::stripExt($gallery_img_baseurl) .  '_small.' . File::getExt($gallery_img_baseurl);
							if(file_exists($small)) {
								$gallery_images[$key]['small'] = Uri::root(true) . '/' . dirname($value) . '/' . File::stripExt($gallery_img_baseurl) . '_small.' . File::getExt($gallery_img_baseurl);
							}

							//Thumbnail
							$thumbnail = JPATH_ROOT . '/' . dirname($value) . '/' . File::stripExt($gallery_img_baseurl) .  '_thumbnail.' . File::getExt($gallery_img_baseurl);
							if(file_exists($thumbnail)) {
								$gallery_images[$key]['thumbnail'] = Uri::root(true) . '/' . dirname($value) . '/' . File::stripExt($gallery_img_baseurl) . '_thumbnail.' . File::getExt($gallery_img_baseurl);
							}

							//Medium
							$medium = JPATH_ROOT . '/' . dirname($value) . '/' . File::stripExt($gallery_img_baseurl) .  '_medium.' . File::getExt($gallery_img_baseurl);
							if(file_exists($medium)) {
								$gallery_images[$key]['medium'] = Uri::root(true) . '/' . dirname($value) . '/' . File::stripExt($gallery_img_baseurl) . '_medium.' . File::getExt($gallery_img_baseurl);
							}

							//Large
							$large = JPATH_ROOT . '/' . dirname($value) . '/' . File::stripExt($gallery_img_baseurl) .  '_large.' . File::getExt($gallery_img_baseurl);
							if(file_exists($large)) {
								$gallery_images[$key]['large'] = Uri::root(true) . '/' . dirname($value) . '/' . File::stripExt($gallery_img_baseurl) . '_large.' . File::getExt($gallery_img_baseurl);
							}
						}
					}

					$item->imagegallery->images = $gallery_images;
				} else {
					$item->imagegallery->images = array();
				}

			}
		}

		return $items;
	}

	public static function getCategories($parent_id = [1], $include_subcategories = true, $child = false, $cats = array()) {

		$app = Factory::getApplication();
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select('*')
			->from($db->quoteName('#__categories'))
			->where($db->quoteName('extension') . ' = ' . $db->quote('com_content'))
			->where($db->quoteName('published') . ' = ' . $db->quote(1))
			->where($db->quoteName('access')." IN (" . implode( ',', Factory::getUser()->getAuthorisedViewLevels() ) . ")")
			->where($db->quoteName('language')." IN (" . $db->Quote(Factory::getLanguage()->getTag()).", ".$db->Quote('*') . ")");

		if (!empty(array_filter($parent_id)))
		{
			$query->where($db->quoteName('parent_id')." IN (" . implode( ',', $parent_id ) . ")");
		}

		$query->order($db->quoteName('lft') . ' ASC');

		$db->setQuery($query);
		$rows = $db->loadObjectList();
		
		foreach ($rows as $row) {

			if($include_subcategories) {
				array_push($cats, $row->id);
				if (self::hasChildren($row->id)) {
					$cats = self::getCategories(array($row->id), $include_subcategories, true, $cats);
				}
			}
		}

		return $cats;
	}

	private static function hasChildren($parent_id = 1) {

		$app = Factory::getApplication();
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select('*')
			->from($db->quoteName('#__categories'))
			->where($db->quoteName('extension') . ' = ' . $db->quote('com_content'))
			->where($db->quoteName('published') . ' = ' . $db->quote(1))
			->where($db->quoteName('access')." IN (" . implode( ',', Factory::getUser()->getAuthorisedViewLevels() ) . ")")
			->where($db->quoteName('language')." IN (" . $db->Quote(Factory::getLanguage()->getTag()).", ".$db->Quote('*') . ")")
			->where($db->quoteName('parent_id') . ' = ' . $db->quote($parent_id))
			->order($db->quoteName('created_time') . ' DESC');

		$db->setQuery($query);

		$childrens = $db->loadObjectList();



		if(is_array($childrens) && count($childrens)) {
			return true;
		}

		return false;
	}
}
