<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            https://www.tassos.gr
 * @copyright       Copyright © 2023 Tassos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework;

// No direct access
defined('_JEXEC') or die;

use NRFramework\Mimes;
use NRFramework\File;

class Image
{
	/**
	 * Resize an image.
	 * 
	 * @param   string   $source
	 * @param   string   $width
	 * @param   string   $height
	 * @param   integer  $quality
	 * @param   string   $mode
	 * @param   boolean  $unique_filename
	 * @param   boolean  $fix_orientation
	 * 
	 * @return  mixed
	 */
	public static function resize($source, $width, $height, $quality = 80, $mode = 'crop', $destination = '', $unique_filename = false, $fix_orientation = true)
	{
		// Find thumbnail full file path
		$destination = empty($destination) ? $source : $destination;

		// size must be WIDTHxHEIGHT
		$size = $width . 'x' . $height;

		switch ($mode)
		{
			// Crop and Resize
			case 'crop':
				$mode = 5;
				break;
			// Scale Fill
			case 'stretch':
				$mode = 1;
				break;
			// Fit, will fill empty space with black
			case 'fit':
				$mode = 6;
				break;
			default:
				$mode = 5;
				break;
		}

		try {
			$image = new \JImage($source);

			// Determine the MIME of the original file to get the proper type
			$mime = Mimes::detectFileType($source);
			
			// PNG images should not have a quality value
			$options = $mime == 'image/png' ? [] : ['quality' => $quality];
			
			// Get the image type
			$image_type = self::getImageType($mime);

			if ($unique_filename)
			{
				// Make destination file unique
				File::uniquefy($destination);
			}

			$destination = \JPath::clean($destination);
			
			$source_orientation = self::getOrientation($source);

			// dont resize gif images
			if ($mime !== 'image/gif')
			{
				foreach ($image->generateThumbs($size, $mode) as $thumb)
				{
					$thumb->toFile($destination, $image_type, $options);
				}
			}
			// if we have uploaded a gif image, simply rename it
			else
			{
				$image->toFile($destination, $image_type, $options);
			}

			if ($fix_orientation)
			{
				self::fixOrientation($destination, $source_orientation, $image_type, $options);
			}

			return $destination;
		} catch(\Exception $e) {}

		return false;
	}

	/**
	 * Resizes an image by height.
	 * 
	 * @param   string  $src
	 * @param   string  $height
	 * @param   string  $destination
	 * @param   int	 	$quality
	 * @param   bool 	$unique_filename
	 * @param   bool 	$fix_orientation
	 * 
	 * @return  bool
	 */
	public static function resizeByHeight($src, $height, $destination = null, $quality = 80, $unique_filename = false, $fix_orientation = true)
	{
		// Create a new JImage object from the source image path
		$image = new \JImage($src);
	  
		// Determine the MIME of the original file to get the proper type
		$mime = Mimes::detectFileType($src);

		// Get the image type
		$image_type = self::getImageType($mime);

		if ($unique_filename)
		{
			// Make destination file unique
			File::uniquefy($destination);
		}

		$destination = \JPath::clean($destination);
		
		// PNG images should not have a quality value
		$options = $mime == 'image/png' ? [] : ['quality' => $quality];

		// Get the original width and height of the image
		$origWidth = $image->getWidth();
		$origHeight = $image->getHeight();
	  
		// Calculate the new width based on the desired height
		$newWidth = ($height / $origHeight) * $origWidth;
	  
		// Get source orientation
		$source_orientation = self::getOrientation($src);

		// Resize the image using the JImage::resize() method
		$resizedImage = $image->resize($newWidth, $height, \JImage::SCALE_INSIDE);
	  
		// Save the resized image to the destination image path
		$resizedImage->toFile($destination, $quality);
	  
		if ($fix_orientation)
		{
			self::fixOrientation($destination, $source_orientation, $image_type, $options);
		}

		// Return true if the image was successfully resized and saved, false otherwise
		return $destination;
	}

	/**
	 * Resizes an image by keeping the aspect ratio
	 * 
	 * @param   string   $source
	 * @param   array    $width
	 * @param   integer  $quality
	 * @param   array    $destination
	 * @param   boolean  $unique_filename
	 * @param   boolean  $fix_orientation
	 * 
	 * @return  boolean
	 */
	public static function resizeAndKeepAspectRatio($source, $width, $quality = 80, $destination = '', $unique_filename = false, $fix_orientation = true)
	{
		// Ensure we have received valid image dimensions
		if (!count($image_dimensions = getimagesize($source)))
		{
			return false;
		}

		// Get the image width
		if (!$uploaded_image_width = (int) $image_dimensions[0])
		{
			return false;
		}

		// Get the image height
		if (!$uploaded_image_height = (int) $image_dimensions[1])
		{
			return false;
		}

		/**
		 * If the image width is less than the given width,
		 * set the image width we are resizing to the image's width.
		 */
		if ($uploaded_image_width < $width)
		{
			$width = $uploaded_image_width;
		}

		// Determine the MIME of the original file to get the proper type
		$mime = Mimes::detectFileType($source);

		// PNG images should not have a quality value
		$options = $mime == 'image/png' ? [] : ['quality' => $quality];

		// Get the image type
		$image_type = self::getImageType($mime);

		try {
			// Get image object
			$image = new \JImage($source);

			// Calculate aspect ratio
			$ratio = $uploaded_image_width / $uploaded_image_height;
	
			// Get new height based on aspect ratio
			$targetHeight = $width / $ratio;
	
			// GIF images are not resized
			$resizedImage = $mime == 'image/gif' ? $image : $image->resize($width, $targetHeight, true);

			// Output file name
			$destination = empty($destination) ? $source : $destination;

			if ($unique_filename)
			{
				// Make destination file unique
				File::uniquefy($destination);
			}
	
			$destination = \JPath::clean($destination);

			$source_orientation = self::getOrientation($source);
			
			// Store the resized image to a new file
			$resizedImage->toFile($destination, $image_type, $options);

			if ($fix_orientation)
			{
				self::fixOrientation($destination, $source_orientation, $image_type, $options);
			}

			return $destination;
		} catch(\Exception $e) {}

		return false;
	}

	/**
	 * Returns the orientation of the image.
	 * 
	 * @param   string  $path
	 * 
	 * @return  int
	 */
	public static function getOrientation($path)
	{
		if (!$exif = @exif_read_data($path))
		{
			return;
		}

		return intval(@$exif['Orientation']);
	}

	/**
	 * Fixes the orientation of the generated image and ensures it appears with the same orientation as the source.
	 * 
	 * @param    string  $path
	 * @param    int     $orientation
	 * @param    int	 $image_type
	 * @param    array	 $options
	 * 
	 * @return   void
	 */
	public static function fixOrientation($path, $orientation = null, $image_type = IMAGETYPE_JPEG, $options = [])
	{
		if (!$orientation)
		{
			return;
		}
		
		if(!in_array($orientation, [3, 6, 8]))
		{
			return;
		}

		$image = new \JImage($path);

		switch ($orientation)
		{
			case 3:
				$image = $image->rotate(180, false);
				break;
			case 6:
				$image = $image->rotate(-90, false);
				break;
			case 8:
				$image = $image->rotate(90, false);
				break;
		}

		// Store the resized image to a new file
		$image->toFile($path, $image_type, $options);

		return true;
	}

	/**
	 * Returns the image type based on its mime type
	 * 
	 * @param   string  $mime
	 * 
	 * @return  int
	 */
	public static function getImageType($mime)
	{
		switch ($mime)
		{
			case 'image/png':
				return IMAGETYPE_PNG;
				break;
			case 'image/gif':
				return IMAGETYPE_GIF;
				break;
			case 'image/jpeg':
			default:
				return IMAGETYPE_JPEG;
				break;
		}
	}
}