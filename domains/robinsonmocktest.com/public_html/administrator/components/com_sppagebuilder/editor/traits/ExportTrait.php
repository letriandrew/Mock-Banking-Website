<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

use Joomla\CMS\Factory;

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Trait for managing export layout API endpoint.
 */
trait ExportTrait
{
	public function export()
	{
		$method = $this->getInputMethod();
		$this->checkNotAllowedMethods(['GET', 'DELETE', 'PUT', 'PATCH'], $method);

		if ($method === 'POST')
		{
			$this->exportLayout();
		}
	}

	/**
	 * Export template layout.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function exportLayout()
	{
		$user = Factory::getUser();
		$authorised = $user->authorise('core.edit', 'com_sppagebuilder');

		if (!$authorised)
		{
			die('Restricted Access');
		}

		$template = $this->getInput('template', '[]', 'RAW');
		$pageCss = $this->getInput('css', '', 'RAW');

		if ($template !== '[]')
		{
			$template  = json_decode($template);

			foreach ($template as &$row)
			{
				foreach ($row->columns as &$column)
				{
					foreach ($column->addons as &$addon)
					{
						if (isset($addon->type) && $addon->type === 'sp_row')
						{
							foreach ($addon->columns as &$column)
							{
								foreach ($column->addons as &$addon)
								{
									if (isset($addon->htmlContent))
									{
										unset($addon->htmlContent);
									}
									if (isset($addon->assets))
									{
										unset($addon->assets);
									}
								}
								unset($addon);
							}
							unset($column);
						}
						else
						{
							if (isset($addon->htmlContent))
							{
								unset($addon->htmlContent);
							}
							if (isset($addon->assets))
							{
								unset($addon->assets);
							}
						}
					}
					unset($addon);
				}
				unset($column);
			}
			unset($row);

			$template  = json_encode($template);
		}

		$content = (object) [
			'template' => $template,
			'css' => $pageCss
		];

		$this->sendResponse($content);
	}
}
