<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('Restricted access');

class SppagebuilderHelperImage
{

	public $height;
	public $width;
	private $src;

	public function __construct($src = '')
	{
		$this->src = $src;
		list($this->width, $this->height) = getimagesize($src);
	}

	public function getDimension()
	{
		return [$this->width, $this->height];
	}

	/**
	 * Create thumb image with specifice height and width and with their ratio
	 */
	public function createThumb($size, $destination, $base_name, $ext, $quality = 100)
	{

		$img = $this->createImageFromType($ext);

		if (count((array) $size) && $img != null)
		{
			$targetWidth = $size[0];
			$targetHeight = $size[1];

			$ratio_thumb = $targetWidth / $targetHeight;
			$ratio_original = $this->width / $this->height;

			if ($ratio_original >= $ratio_thumb)
			{
				$height = $this->height;
				$width = ceil(($height * $targetWidth) / $targetHeight);
				$x = ceil(($this->width - $width) / 2);
				$y = 0;
			}
			else
			{
				$width = $this->width;
				$height = ceil(($width * $targetHeight) / $targetWidth);
				$y = ceil(($this->height - $height) / 2);
				$x = 0;
			}

			$targetWidth = (int) $targetWidth;
			$targetHeight = (int) $targetHeight;

			$new = imagecreatetruecolor($targetWidth, $targetHeight);

			if ($ext == "gif" or $ext == "png")
			{
				imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
				imagealphablending($new, false);
				imagesavealpha($new, true);
			}

			imagecopyresampled($new, $img, 0, 0, $x, $y, $targetWidth, $targetHeight, $width, $height);

			$dest = $destination . '/' . $base_name . '.' . $ext;

			$this->cloneImage($ext, $new, $dest, $quality);

			return true;
		}

		return false;
	}

	/**
	 * Clone Image from original to destination source based on extension
	 */
	private function cloneImage($ext, $new, $dest, $quality)
	{
		switch ($ext)
		{
			case 'bmp':
				imagewbmp($new, $dest);
				break;
			case 'webp':
				imagewebp($new, $dest, $quality);
				break;
			case 'gif':
				imagegif($new, $dest);
				break;
			case 'jpg':
				imagejpeg($new, $dest, $quality);
				break;
			case 'jpeg':
				imagejpeg($new, $dest, $quality);
				break;
			case 'png':
				imagepng($new, $dest, floor($quality / 11));
				break;
		}
	}

	/**
	 * Create Image from their specific extension
	 */
	private function createImageFromType($ext)
	{
		switch ($ext)
		{
			case 'bmp':
				$img = imagecreatefromwbmp($this->src);
				break;
			case 'webp':
				$img = imagecreatefromwebp($this->src);
				break;
			case 'gif':
				$img = imagecreatefromgif($this->src);
				break;
			case 'jpg':
				$img = imagecreatefromjpeg($this->src);
				break;
			case 'jpeg':
				$img = imagecreatefromjpeg($this->src);
				break;
			case 'png':
				$img = imagecreatefrompng($this->src);
				break;
		}
		return $img;
	}
}
