<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Filesystem\File;

//no direct access
defined('_JEXEC') or die ('Restricted access');

$input 	= Factory::getApplication()->input;
$path 	= $input->post->get('path', '/images', 'PATH');
$media 	= $this->media;

$report['breadcrumbs'] = $media['breadcrumbs'];
$report['folders'] = $media['folders'];
$report['folders_list'] = $media['folders_list'];

$items = array();

foreach ($media['items'] as $key => $item) {

	$item 					= str_replace('\\', '/',$item);
	$root_path 				= str_replace('\\', '/', JPATH_ROOT);
	$path 					= str_replace($root_path . '/', '', $item);

	$items[$key]['path'] 	= $path;
	$thumb 					= dirname($path) . '/_sp-pagebuilder_thumbs/' . basename($path);

	if(file_exists(JPATH_ROOT . '/' . $thumb))
	{
		$items[$key]['src'] = Uri::root(true) . '/' . $thumb;
	}
	else
	{
		$items[$key]['src'] = Uri::root(true) . '/' . $path;
	}

	$filename 				= basename($item);
	$title 					= File::stripExt($filename);
	$ext 					= File::getExt($filename);

	$items[$key]['id'] 			= 0;
	$items[$key]['title'] 		= $title;
	$items[$key]['ext'] 		= $ext;
	$items[$key]['type'] 		= ( $ext == 'pdf' ) ? 'pdf' : 'image';
}

$report['items'] = $items;

echo json_encode($report); die;