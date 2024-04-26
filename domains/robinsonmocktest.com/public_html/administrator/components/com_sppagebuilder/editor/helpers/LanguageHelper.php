<?php

use Joomla\CMS\Language\Text;

class LanguageHelper
{
	public static function registerLanguageKeys()
	{
		$languageKeysPath = JPATH_ROOT . '/administrator/components/com_sppagebuilder/editor/data/translations.json';
		$languageKeys = \file_get_contents($languageKeysPath);

		if (!empty($languageKeys))
		{
			$languageKeys = \json_decode($languageKeys);

			foreach ($languageKeys as $key => $_)
			{
				Text::script($key);
			}
		}
	}
}
