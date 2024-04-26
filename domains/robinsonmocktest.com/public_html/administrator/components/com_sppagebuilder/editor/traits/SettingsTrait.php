<?php

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;

/**
 * Trait for managing app configs
 */
trait SettingsTrait
{
	public function settings()
	{
		$method = $this->getInputMethod();
		$this->checkNotAllowedMethods(['POST', 'PUT', 'DELETE', 'PATCH'], $method);

		$this->getSettings();
	}


	private function getSettings()
	{
		if (!\class_exists('SpPgaeBuilderBase'))
		{
			require_once JPATH_ROOT . '/components/com_sppagebuilder/builder/classes/base.php';
		}

		$sectionSettings = SpPgaeBuilderBase::getRowGlobalSettings();
		$columnSettings = SpPgaeBuilderBase::getColumnGlobalSettings();

		$sectionDefaults = EditorUtils::getSectionSettingsDefaultValues();
		$columnDefaults = EditorUtils::getColumnSettingsDefaultValues();

		PluginHelper::importPlugin('system');

		Factory::getApplication()->triggerEvent('onBeforeRowConfigure', [&$sectionSettings]);


		$this->sendResponse([
			'section' => $sectionSettings,
			'column' => $columnSettings,
			'sectionDefaults' => $sectionDefaults,
			'columnDefaults' => $columnDefaults
		]);
	}
}
