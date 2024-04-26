<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */


/** No direct access. */
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;

/**
 * Autoload the required classes in a required scope.
 *
 * @since 	4.0.0
 */
class BuilderAutoload
{
	/**
	 * Load the required classes for the application.
	 *
	 * @return 	void
	 * @since 	4.0.0
	 */
	public static function loadClasses()
	{
		require_once JPATH_ROOT . '/components/com_sppagebuilder/helpers/route.php';
		require_once JPATH_ROOT . '/components/com_sppagebuilder/helpers/helper.php';
		require_once JPATH_ROOT . '/components/com_sppagebuilder/helpers/auth-helper.php';
		require_once JPATH_ROOT . '/components/com_sppagebuilder/builder/classes/base.php';
		require_once JPATH_ROOT . '/components/com_sppagebuilder/builder/classes/config.php';
		require_once JPATH_ROOT . '/components/com_sppagebuilder/parser/helper-base.php';
		require_once JPATH_ROOT . '/components/com_sppagebuilder/parser/lodash.php';
		require_once JPATH_ROOT . '/components/com_sppagebuilder/parser/css-helper.php';
		require_once JPATH_ROOT . '/components/com_sppagebuilder/parser/addon-utils.php';
		require_once JPATH_ROOT . '/components/com_sppagebuilder/builder/classes/addon.php';
		require_once JPATH_ROOT . '/components/com_sppagebuilder/helpers/helper.php';
	}

	public static function loadHelperClasses()
	{
		if (!class_exists('AddonsHelper'))
		{
			require_once JPATH_ROOT . '/administrator/components/com_sppagebuilder/editor/helpers/AddonsHelper.php';
		}

		if (!class_exists('ApplicationHelper'))
		{
			require_once JPATH_ROOT . '/administrator/components/com_sppagebuilder/editor/helpers/ApplicationHelper.php';
		}

		if (!class_exists('SecurityHelper'))
		{
			require_once JPATH_ROOT . '/administrator/components/com_sppagebuilder/editor/helpers/SecurityHelper.php';
		}

		if (!class_exists('EditorUtils'))
		{
			require_once JPATH_ROOT . '/administrator/components/com_sppagebuilder/editor/helpers/EditorUtils.php';
		}

		if (!class_exists('FontHelper'))
		{
			require_once JPATH_ROOT . '/administrator/components/com_sppagebuilder/editor/helpers/FontHelper.php';
		}

		if (!class_exists('IconHelper'))
		{
			require_once JPATH_ROOT . '/administrator/components/com_sppagebuilder/editor/helpers/IconHelper.php';
		}

		if (!class_exists('LanguageHelper'))
		{
			require_once JPATH_ROOT . '/administrator/components/com_sppagebuilder/editor/helpers/LanguageHelper.php';
		}

		if (!class_exists('SppagebuilderHelper'))
		{
			require_once JPATH_ROOT . '/administrator/components/com_sppagebuilder/helpers/sppagebuilder.php';
		}

		if (!class_exists('BuilderMediaHelper'))
		{
			require_once JPATH_ROOT . '/administrator/components/com_sppagebuilder/helpers/media-helper.php';
		}
	}

	/**
	 * Load the global assets to the whole application.
	 *
	 * @return 	void
	 * @since 	4.0.0
	 */
	public static function loadGlobalAssets()
	{
		$doc = Factory::getDocument();
		$doc->addScript(Uri::root(true) . '/components/com_sppagebuilder/assets/js/common.js');
		HTMLHelper::_('behavior.core');
	}
}
