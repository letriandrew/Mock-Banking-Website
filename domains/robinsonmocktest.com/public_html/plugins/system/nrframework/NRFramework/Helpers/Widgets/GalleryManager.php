<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            https://www.tassos.gr
 * @copyright       Copyright © 2023 Tassos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

namespace NRFramework\Helpers\Widgets;

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.path');

use NRFramework\File;
use NRFramework\Image;
use Joomla\Registry\Registry;
use NRFramework\Functions;

class GalleryManager
{
	/**
	 * How long the files can stay in the temp folder.
	 * 
	 * After each save a clean up is run and all files older
	 * than this value in days are removed.
	 * 
	 * @var  int
	 */
	private static $temp_files_cleanup_days = 1;
	
	/**
	 * Upload file
	 *
	 * @param	array	$file						The request file as posted by form
	 * @param	string	$upload_settings			The upload settings
	 * @param	array	$media_uploader_file_data	Media uploader related file settings
	 * @param   array   $resizeSettings				The resize settings
	 *
	 * @return	mixed	String on success, Null on failure
	 */
	public static function upload($file, $upload_settings, $media_uploader_file_data, $resizeSettings)
	{
		$ds = DIRECTORY_SEPARATOR;
		
		// The source file name
		$source = '';

		// Move the image to the tmp folder
		try {
			$source = File::upload($file, self::getFullTempFolder(), $upload_settings['allowed_types'], $upload_settings['allow_unsafe']);
		} catch (\Throwable $th)
		{
			return false;
		}

		// If the file came from the Media Manager file and we are copying it, fix its filename
		if ($media_uploader_file_data['is_media_uploader_file'])
		{
			$media_uploader_file_data['media_uploader_filename'] = self::getFilePathFromMediaUploaderFile($media_uploader_file_data['media_uploader_filename']);
		}

		// If we are copying the base image, copy it to the temp folder.
		if (!$source = File::move($source, $source, true))
		{
			return false;
		}

		// Check whether to copy and resize the original image
		if ($resizeSettings['original_image_resize'])
		{
			$source = Image::resizeAndKeepAspectRatio($source, $resizeSettings['original_image_resize_width'], $resizeSettings['original_image_resize_quality']);
		}

		// Generate thumbnails
		if (!$thumb_data = self::generateThumbnail($source, $resizeSettings))
		{
			return false;
		}

		return [
			'filename' => implode($ds, [self::getTempFolder(), $thumb_data['filename']]),
			'thumbnail' => implode($ds, [self::getTempFolder(), $thumb_data['resized_filename']])
		];
	}
	
	/**
	 * Moves all given `tmp` items over to the destination folder.
	 * 
	 * @param   array   $value
	 * @param   object  $field
	 * @param   string  $destination_folder
	 * 
	 * @return  void
	 */
	public static function moveTempItemsToDestination($value, $field, $destination_folder)
	{
		if (!$destination_folder)
		{
			return;
		}

		// Create destination folder if missing
		if (!File::createDirs($destination_folder))
		{
			return;
		}

		// Make field params use Registry
		if (!$field->fieldparams instanceof Registry)
		{
			$field->fieldparams = new Registry($field->fieldparams);
		}

		/**
		 * Prepare the items for backwards compatibility
		 */
		$items = is_string($value) ? json_decode($value, true) ?? [['value' => $value]] : $value;

		$items = isset($items['items']) ? $items['items'] : $items;

		if (isset($items['value']))
		{
			$items = [$items];
		}

		$limit_files = (int) $field->fieldparams->get('limit_files', 0);

		// Handle single file
		if ($limit_files === 1 && is_array($items))
		{
			$items = [reset($items)];
		}

		$ds = DIRECTORY_SEPARATOR;

		// Move all files from `tmp` folder over to the `upload folder`
		foreach ($items as $key => &$item)
		{
			/**
			 * Skip invalid files.
			 * 
			 * These "files" can appear when we try to move files
			 * over to the destination folder when the gallery manager
			 * is still working to upload queueed files.
			 * 
			 * Also skip any items that have no value.
			 */
			if ($key === 'ITEM_ID' || empty($item['thumbnail']))
			{
				continue;
			}
			
			$moved = false;

			// Ensure thumbnail in temp folder file exists
			$thumbnail_clean = pathinfo($item['thumbnail'], PATHINFO_BASENAME);
			$thumbnail_path = implode($ds, [JPATH_ROOT, $item['thumbnail']]);
			if (Functions::startsWith($item['thumbnail'], self::getTempFolder()) && file_exists($thumbnail_path))
			{
				// Move thumbnail
				$thumb = File::move($thumbnail_path, $destination_folder . $thumbnail_clean);

				// Update thumbnail file name
				$item['thumbnail'] = pathinfo($thumb, PATHINFO_BASENAME);

				$moved = true;
			}

			// Check if we have uploaded the full image as well and set it
			$image_clean = pathinfo($item['image'], PATHINFO_BASENAME);
			$image_path = implode($ds, [JPATH_ROOT, $item['image']]);
			if (Functions::startsWith($item['image'], self::getTempFolder()) && file_exists($image_path))
			{
				// Move image
				$image = File::move($image_path, $destination_folder . $image_clean);

				// Update image file name
				$item['image'] = pathinfo($image, PATHINFO_BASENAME);

				$moved = true;
			}

			if ($moved)
			{
				// Update destination path
				self::updateDestinationPath($item, $destination_folder);
			}
		}

		return $items;
	}

	/**
	 * Updates the destination path for the image and its thumbnail to the final destination folder.
	 * 
	 * @param   array   $item
	 * @param   string  $destination_folder
	 * 
	 * @return  mixed
	 */
	private static function updateDestinationPath(&$item, $destination_folder)
	{
		$ds = DIRECTORY_SEPARATOR;

		// Ensure destination folder is a relative path
		$destination_folder = ltrim(rtrim(str_replace(JPATH_ROOT, '', $destination_folder), $ds), $ds);

		$item = array_merge($item, [
			'thumbnail' => implode($ds, [$destination_folder, $item['thumbnail']]),
			'image' => implode($ds, [$destination_folder, $item['image']])
		]);
	}
	
	/**
	 * Media Uploader files look like: https://example.com/images/sampledata/parks/banner_cradle.png
	 * We remove the first part (https://example.com/images/) and keep the other part (relative path to image).
	 * 
	 * @param   string  $filename
	 * 
	 * @return  string
	 */
	private static function getFilePathFromMediaUploaderFile($filename)
	{
		$filenameArray = explode('images/', $filename, 2);
		unset($filenameArray[0]);
		$new_filepath = join($filenameArray);
		return 'images/' . $new_filepath;
	}

	/**
	 * Generates thumbnail
	 * 
	 * @param   string   $source			 	 Source image path.
	 * @param   array    $resizeSettings	 	 Resize Settings.
	 * @param   string   $destination_folder	 Destination folder.
	 * @param   boolean  $unique_filename		 Whether the thumbnails will have a unique filename.
	 * 
	 * @return  array
	 */
	public static function generateThumbnail($source, $resizeSettings, $destination_folder = null, $unique_filename = true)
	{
		$parts = pathinfo($source);
		$destination_folder = !is_null($destination_folder) ? $destination_folder : $parts['dirname'] . DIRECTORY_SEPARATOR;
		$destination = $destination_folder . $parts['filename'] . '_thumb.' . $parts['extension'];

		$resized_image = null;



		// If thumbnail width is null, and we have item height set, we are resizing by height
		if (is_null($resizeSettings['thumb_width']) && isset($resizeSettings['justified_item_height']))
		{
			$resized_image = Image::resizeByHeight($source, $resizeSettings['justified_item_height'], $destination, $unique_filename, $resizeSettings['thumb_resize_quality']);
		}
		else
		{
			/**
			 * If height is zero, then we suppose we want to keep aspect ratio.
			 * 
			 * Resize with width & height: If thumbnail height is not set
			 * Resize and keep aspect ratio: If thumbnail height is set
			 */
			$resized_image = !is_null($resizeSettings['thumb_height']) && $resizeSettings['thumb_height'] !== '0'
				?
				Image::resize($source, $resizeSettings['thumb_width'], $resizeSettings['thumb_height'], $resizeSettings['thumb_resize_quality'], $resizeSettings['thumb_resize_method'], $destination, $unique_filename)
				:
				Image::resizeAndKeepAspectRatio($source, $resizeSettings['thumb_width'], $resizeSettings['thumb_resize_quality'], $destination, $unique_filename);
		}
		
		if (!$resized_image)
		{
			return;
		}

		return [
			'filename' => basename($source),
			'resized_filename' => basename($resized_image)
		];
	}

	/**
	 * Deletes an uploaded file (resized original image and thumbnail).
	 *
	 * @param   string  $filepath		The filepath
	 * @param   string  $thumbnail		The thumbnail filepath
	 *
	 * @return  bool
	 */
	public static function deleteFile($filepath, $thumbnail)
	{
		if (empty($filepath))
		{
			return false;
		}

		return [
			'deleted_original_image' => self::findAndDeleteFile($filepath),
			'deleted_thumbnail' => self::findAndDeleteFile($thumbnail)
		];
	}

	/**
	 * Deletes the file.
	 * 
	 * @param   string  $filepath
	 * 
	 * @return  mixed
	 */
	private static function findAndDeleteFile($filepath)
	{
		$file = \JPath::clean(implode(DIRECTORY_SEPARATOR, [JPATH_ROOT, $filepath]));

		return \JFile::exists($file) ? \JFile::delete($file) : false;
	}

	/**
	 * Cleans the temp folder.
	 * 
	 * Removes any image that is 1 day or older.
	 * 
	 * @return  void
	 */
	public static function clean()
	{
		$temp_folder = self::getFullTempFolder();
		
		if (!is_dir($temp_folder))
		{
			return;
		}

		// Get images
		$files = array_diff(scandir($temp_folder), ['.', '..', '.DS_Store', 'index.html']);

		$found = [];

		foreach ($files as $key => $filename)
		{
			$file_path = implode(DIRECTORY_SEPARATOR, [$temp_folder, $filename]);
			
			// Skip directories
			if (is_dir($file_path))
			{
				continue;
			}

			$diff_in_miliseconds = time() - filemtime($file_path);

			// Skip the file if it's not old enough
			if ($diff_in_miliseconds < (60 * 60 * 24 * self::$temp_files_cleanup_days))
			{
				continue;
			}

			$found[] = $file_path;
		}

		if (!$found)
		{
			return;
		}

		// Delete found old files
		foreach ($found as $file)
		{
			unlink($file);
		}
	}

	/**
	 * Full temp directory where images are uploaded
	 * prior to them being saved in the final directory.
	 * 
	 * @return  string
	 */
	private static function getFullTempFolder()
	{
		return implode(DIRECTORY_SEPARATOR, [JPATH_ROOT, self::getTempFolder()]);
	}

	/**
	* Temp folder where images are uploaded
	* prior to them being saved in the final directory.
	* 
	* @var  string
	*/
	public static function getTempFolder()
	{
		return implode(DIRECTORY_SEPARATOR, ['media', 'acfgallery', 'tmp']);
	}
}