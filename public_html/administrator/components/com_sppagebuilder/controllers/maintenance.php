<?php
/**
* @package SP Page Builder
* @author JoomShaper http://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2021 JoomShaper
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/

// No Direct Access
defined('_JEXEC') or die('Resticted Aceess');

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\CMS\Response\JsonResponse;


/**
 * maintenance list controller class.
 *
 * @since  1.0.0
 */
class SppagebuilderControllerMaintenance extends AdminController
{

	public function fix()
	{
		$app 		= Factory::getApplication('site');
		$input 		= $app->input;

		$html = [];

		if (!class_exists('Maintenance'))
		{
			require_once JPATH_ADMINISTRATOR . '/components/com_sppagebuilder/helpers/maintenance.php';
		}
		
		$maintenance = new Maintenance;
		$maintenance->fix();
		$buffer = $maintenance->getBuffer('fixed');
		$errors = $maintenance->getErrors();

		$encounter = 1;

		$response = [
			'html' => implode("\n", $buffer),
			'errors' => $errors
		];

		$app->setHeader('status', 200, true);
		$app->sendHeaders();
		echo new JsonResponse($response);
		$app->close();
	}
}

