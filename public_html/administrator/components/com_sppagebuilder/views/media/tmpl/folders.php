<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2022 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined('_JEXEC') or die ('Restricted access');

$input 				= JFactory::getApplication()->input;
$mediaParams = JComponentHelper::getParams('com_media');
$media_path = $mediaParams->get('file_path', 'images');
$m_source  = $input->get('source', '', 'STRING');

if ($m_source == 'page') {

$input 	= JFactory::getApplication()->input;
$path 	= $input->post->get('path', '/images', 'PATH');
$media 	= $this->media;

$report['folders'] = $media['folders'];
$report['folders_list'] = $media['folders_list'];

$images = array();

foreach ($media['images'] as $key => $image) {

	$image 			= str_replace('\\', '/', $image);
	$root_path 		= str_replace('\\', '/', JPATH_ROOT);
	$path 			= str_replace($root_path . '/', '', $image);

	$images[$key]['path'] 	= $path;

	$thumb = dirname($path) . '/_sp-pagebuilder_thumbs/' . basename($path);
	if(file_exists(JPATH_ROOT . '/' . $thumb)) {
		$images[$key]['src'] = JURI::root(true) . '/' . $thumb;
	} else {
		$images[$key]['src'] = JURI::root(true) . '/' . $path;
	}

	$filename = basename($image);
	$title = JFile::stripExt($filename);
	$ext = JFile::getExt($filename);

	$images[$key]['title'] 	= $title;
	$images[$key]['ext'] 		= $ext;
}

$report['images'] = $images;

echo json_encode($report); die;

} else {

	$input 	= JFactory::getApplication()->input;
	$path 	= $input->post->get('path', '/images', 'PATH');

	$report = array();
	$media = $this->media;

	$report['output'] 	= '';
	$report['count'] = 0;

	$tree = '<option value="/'. $media_path .'">/'. $media_path .'</option>';
	foreach ( $media['folders'] as $folder ) {
		$value = str_replace('\\', '/', $folder['relname']);
		if($path == $value) {
			$tree .= '<option value="'. $value .'" selected>'. str_replace('\\', '/', $folder['relname']) .'</option>';
		} else {
			$tree .= '<option value="'. $value .'">'. str_replace('\\', '/', $folder['relname']) .'</option>';
		}
	}
	$report['folders_tree'] = $tree; // End folders tree

	$report['output'] .= '<ul class="sp-pagebuilder-media">';

	if(isset($media['folders_list']) && count((array) $media['folders_list'])) {
		foreach ($media['folders_list'] as $single_folder) {
			$report['output'] .= '<li class="sp-pagebuilder-media-folder sp-pagebuilder-media-to-folder" data-path="'. $path . '/' . $single_folder .'">';

			$report['output'] .= '<div class="sp-pagebuilder-media-item-directory">';
			$report['output'] .= '<div title="' . $single_folder . '" class="sp-pagebuilder-media-title">' . $single_folder .'</div>';
			$report['output'] .= '<div class="sp-pagebuilder-media-item-preview"><i class="fas fa-folder" area-hidden="true"></i></div>';
			$report['output'] .= '</div>';

			$report['output'] .= '</li>';
		}

		// Get Folders count
		$report['count'] += (isset($media['folders_list']) && count((array) $media['folders_list'])) ? count((array) $media['folders_list']) : 0;
	}

	if(isset($media['images']) && count((array) $media['images'])) {
		foreach ($media['images'] as $image) {

			$image = str_replace('\\', '/',$image);
			$root_path = str_replace('\\', '/', JPATH_ROOT);
			$path = str_replace($root_path . '/', '', $image);

			$filename = basename($image);
			$title = JFile::stripExt($filename);
			$ext = JFile::getExt($filename);

			if($ext == 'pdf')
			{
				$box_class = 'attachment-pdf';
				$icon_class = 'file-pdf-o';

				$report['output'] .= '<li class="sp-pagebuilder-media-item" data-type="image" data-src="'. JURI::root(true) . '/' . $path .'" data-path="'. $path .'">';

				$report['output'] .= '<div class="sp-pagebuilder-media-item-'. $box_class .'">';
				$report['output'] .= '<div title="'.$filename.'" class="sp-pagebuilder-media-title">' . $filename .'</div>';
				$report['output'] .= '<div class="sp-pagebuilder-media-item-preview"><i class="fa fa-'.$icon_class.'" area-hidden="true"></i></div>';
				$report['output'] .= '</div>';
	
				$report['output'] .= '</li>';
			}
			else
			{
				$report['output'] .= '<li class="sp-pagebuilder-media-item" data-type="image" data-src="'. JURI::root(true) . '/' . $path .'" data-path="'. $path .'">';

				$thumb = dirname($path) . '/_sp-pagebuilder_thumbs/' . basename($path);
				if(file_exists(JPATH_ROOT . '/' . $thumb)) {
					$thumbnail = JURI::root(true) . '/' . $thumb;
				} else {
					$thumbnail = JURI::root(true) . '/' . $path;
				}
			
				$report['output'] .= '<div class="sp-pagebuilder-media-item-image">';
				$report['output'] .= '<div title="'.$filename.'" class="sp-pagebuilder-media-title">' . $title . '.' . $ext .'</div>';
				$report['output'] .= '<div class="sp-pagebuilder-media-item-thumbnail" style="background-image: url('. $thumbnail .');"></div>';
				$report['output'] .= '</div>';
	
				$report['output'] .= '</li>';
			}
			
		}
	}

	$report['output'] .= '</ul>';

	// Get Media count
	$report['count'] += (isset($media['images']) && count((array) $media['images'])) ? count((array) $media['images']) : 0;

	echo json_encode($report);

	die;

}
