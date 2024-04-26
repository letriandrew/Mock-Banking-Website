<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

use Joomla\Archive\Archive;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Helper\MediaHelper;


// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Upload font trait
 *
 * @since 5.0.0
 */
trait UploadFontTrait
{
	public function uploadFont()
	{
		$method = $this->getInputMethod();
		$this->checkNotAllowedMethods(['GET', 'PUT', 'PATCH', 'DELETE'], $method);

		if ($method === 'POST')
		{
			$this->uploadFontFiles();
		}
	}

	private function uploadFontFiles()
	{
		$input = Factory::getApplication()->input;
		$fontFile = $input->files->get('font');

		$params = ComponentHelper::getParams('com_media');
		$contentLength = (int) $_SERVER['CONTENT_LENGTH'];
		$mediaHelper = new MediaHelper;
		$postMaxSize = $mediaHelper->toBytes(ini_get('post_max_size'));
		$memoryLimit = $mediaHelper->toBytes(ini_get('memory_limit'));

		$config = Factory::getConfig();
		$tempPath = $config->get('tmp_path');

		$response = [
			'message' => '',
			'id' => null
		];

		if (empty($fontFile))
		{
			$response['message'] = 'No font file found.';
			$this->sendResponse($response, 400);
		}

		if ($fontFile['error'] !== UPLOAD_ERR_OK)
		{
			$response['message'] = 'Error uploading file.';
			$this->sendResponse($response, 500);
		}

		if (($postMaxSize > 0 && $contentLength > $postMaxSize) || ($memoryLimit !== -1 && $contentLength > $memoryLimit))
		{
			$response['message'] = 'Max size limit exceeded.';
			$this->sendResponse($response, 400);
		}

		$uploadMaxSize = $params->get('upload_maxsize', 0) * 1024 * 1024;
		$uploadMaxFileSize = $mediaHelper->toBytes(ini_get('upload_max_filesize'));

		if (($fontFile['error'] === 1) || ($uploadMaxSize > 0 && $fontFile['size'] > $uploadMaxSize) || ($uploadMaxFileSize > 0 && $fontFile['size'] > $uploadMaxFileSize))
		{
			$response['message'] = 'Max upload size limit exceeded.';
			$this->sendResponse($response, 400);
		}

		$fileExtension = strtolower(File::getExt($fontFile['name']));

		if ($fileExtension !== 'zip')
		{
			$response['message'] = 'Invalid file type.';
			$this->sendResponse($response, 400);
		}

		$zipPath = $tempPath . '/CustomFont.zip';

		if (File::exists($zipPath))
		{
			File::delete($zipPath);
		}

		if (File::upload($fontFile['tmp_name'], $zipPath, false, true))
		{
			$unzipped = $this->unzip($zipPath);
			$fontName = '';

			$fontFiles = $unzipped['files'];
			$extractDirectory = $unzipped['directory'];

			if (!empty($fontFiles))
			{
				foreach ($fontFiles as $file)
				{
					$extension = strtolower(File::getExt($file));

					if ($extension === 'css')
					{
						$fontName = $this->extractFontFamilyFromPath($file);
					}
				}
			}

			if (empty($fontName))
			{
				File::delete($zipPath);
				Folder::delete($extractDirectory);
				$response['message'] = 'Max size limit exceeded.';
				$this->sendResponse($response, 500);
			}

			if (!empty($fontName))
			{
				$mediaDirectory = JPATH_ROOT . '/media/com_sppagebuilder/assets/custom-fonts/' . $fontName;

				if (Folder::exists($mediaDirectory))
				{
					Folder::delete($mediaDirectory);
				}

				Folder::create($mediaDirectory, 0755);

				foreach ($fontFiles as $file)
				{
					File::move($file, $mediaDirectory . '/' . basename($file));
				}
			}

			File::delete($zipPath);
			Folder::delete($extractDirectory);

			$fontData = (object)[
				'id' => null,
				'family_name' => $fontName,
				'data' => (object) [
					'family' => $fontName,
					'variants' => ['regular'],
					'subsets' => ['latin'],
					'category' => '',
					'is_installed' => true
				],
				'type' => 'local',
				'created' => Factory::getDate()->toSql(),
				'created_by' => Factory::getUser()->id
			];

			$this->saveCustomFont($fontData);
		}

		$this->sendResponse($response);
	}

	private function unzip(string $filePath)
	{
		$config = Factory::getConfig();
		$tempPath = $config->get('tmp_path');

		$tempDirectory = uniqid('custom-font-');
		$extractDestination = Path::clean(dirname($filePath) . '/' . $tempDirectory);

		if (!Folder::exists($extractDestination))
		{
			Folder::create($extractDestination, 0755);
		}

		$archive = new Archive(['tmp_path' => $tempPath]);

		$extractedResult = $archive->extract(Path::clean($filePath), $extractDestination);

		$requiredFiles = [];

		if ($extractedResult)
		{
			$files = Folder::files($extractDestination);

			if (!empty($files))
			{
				foreach ($files as $file)
				{
					$extension = \strtolower(\pathinfo($file, PATHINFO_EXTENSION));

					if (\in_array($extension, ['css', 'woff', 'woff2']))
					{
						$requiredFiles[] = $extractDestination . '/' . $file;
					}
				}
			}
		}

		return ['files' => $requiredFiles, 'directory' => $extractDestination];
	}

	private function extractFontFamilyFromPath(string $path)
	{
		if (!File::exists($path))
		{
			return null;
		}

		$content = \file_get_contents($path);

		$pattern = "@font-family:\s*'(.*?)'@";
		preg_match($pattern, $content, $matches);

		return isset($matches[1]) ? $matches[1] : null;
	}

	private function saveCustomFont($data)
	{
		$db = Factory::getDbo();
		$data->data = \json_encode($data->data);
		$db->insertObject('#__sppagebuilder_fonts', $data, 'id');
		$response = (object) [
			'id' => $data->id,
			'message' => ''
		];

		$this->sendResponse($response);
	}
}
