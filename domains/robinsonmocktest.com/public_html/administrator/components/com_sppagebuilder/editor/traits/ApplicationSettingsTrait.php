<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Uri\Uri;

// No direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Application Settings traits
 */
trait ApplicationSettingsTrait
{
	public function applicationSettings()
	{
		$method = $this->getInputMethod();
		$this->checkNotAllowedMethods(['POST', 'PATCH', 'DELETE'], $method);

		if ($method === 'GET')
		{
			$this->getComponentSettings();
		}
		else if ($method === 'PUT')
		{
			$this->saveApplicationSettings();
		}
	}

	private function getComponentSettings()
	{
		$params = ComponentHelper::getParams('com_sppagebuilder');

		if ($params->exists('ig_token'))
		{
			$params->set('ig_token', \json_decode($params->get('ig_token')));
		}

		if (!$params->exists('enable_frontend_editing'))
		{
			$params->set('enable_frontend_editing', '1');
		}

		if(!$params->get('lazyplaceholder')) 
		{	
			$params->set('lazyplaceholder', '/components/com_sppagebuilder/assets/images/lazyloading-placeholder.svg');
		}

		$colors = $this->getColors();
		$params->set('colors', $colors);

		$this->sendResponse($params);
	}

	private function saveApplicationSettings()
	{
		$productionMode = $this->getInput('production_mode', 0, 'INT');
		$gmapApi = $this->getInput('gmap_api', '', 'STRING');
		$igToken = $this->getInput('ig_token', '', 'RAW');
		$fontAwesome = $this->getInput('fontawesome', 1, 'INT');
		$disableGoogleFonts = $this->getInput('disable_google_fonts', 0, 'INT');
		$lazyLoadimg = $this->getInput('lazyloadimg', 0, 'INT');
		$lazyPlaceholder = $this->getInput('lazyplaceholder', '', 'STRING');
		$disableAnimateCSS = $this->getInput('disableanimatecss', 0, 'INT');
		$disableCSS = $this->getInput('disablecss', 0, 'INT');
		$disableOG = $this->getInput('disable_og', 0, 'INT');
		$fbAppID = $this->getInput('fb_app_id', '', 'STRING');
		$disableTc = $this->getInput('disable_tc', 0, 'INT');
		$joomshaperEmail = $this->getInput('joomshaper_email', '', 'STRING');
		$joomshaperLicenseKey = $this->getInput('joomshaper_license_key', '', 'STRING');
		$colors = $this->getInput('colors', '', 'RAW');
		$googleFontsApiKey = $this->getInput('google_font_api_key', '', 'STRING');
		$enableFrontendEditing = $this->getInput('enable_frontend_editing', 1, 'INT');
		$containerMaxWidth = $this->getInput('container_max_width', 0, 'INT');
		$containerMaxWidth = max(1140, $containerMaxWidth);

		$params = ComponentHelper::getParams('com_sppagebuilder');
		$componentId = ComponentHelper::getComponent('com_sppagebuilder')->id;

		$joomshaperLicenseKey = trim($joomshaperLicenseKey);
		$joomshaperEmail = trim($joomshaperEmail);

		$params->set('production_mode', $productionMode);
		$params->set('gmap_api', trim($gmapApi));
		$params->set('ig_token', trim($igToken));
		$params->set('fontawesome', $fontAwesome);
		$params->set('disable_google_fonts', $disableGoogleFonts);
		$params->set('lazyloadimg', $lazyLoadimg);
		$params->set('lazyplaceholder', $lazyPlaceholder);
		$params->set('disableanimatecss', $disableAnimateCSS);
		$params->set('disablecss', $disableCSS);
		$params->set('disable_og', $disableOG);
		$params->set('fb_app_id', $fbAppID);
		$params->set('disable_tc', $disableTc);
		$params->set('joomshaper_email', $joomshaperEmail);
		$params->set('joomshaper_license_key', $joomshaperLicenseKey);
		$params->set('google_font_api_key', trim($googleFontsApiKey));
		$params->set('enable_frontend_editing', $enableFrontendEditing);
		$params->set('container_max_width', $containerMaxWidth);

		if (!empty($joomshaperEmail) && !empty($joomshaperLicenseKey))
		{
			if (!$this->updateLicenseKey($joomshaperEmail, $joomshaperLicenseKey))
			{
				$response['message'] = Text::_("COM_SPPAGEBUILDER_ERROR_MSG_FOR_FAILED_LICESE_KEY");
				$this->sendResponse($response, 500);
			}
		}

		if (!empty($colors))
		{
			$this->saveColors($colors);
		}

		$table = Table::getInstance('extension');

		if (!$table->load($componentId))
		{
			$response['message'] = Text::_("COM_SPPAGEBUILDER_ERROR_MSG_FOR_FAILED_LOAD_EXTENSION");
			$this->sendResponse($response, 500);
		}

		$table->params = \json_encode($params);

		if (!$table->store())
		{
			$response['message'] = Text::_("COM_SPPAGEBUILDER_ERROR_MSG_FOR_FAILED_STORE_EXTENSION");
			$this->sendResponse($response, 500);
		}

		$this->sendResponse(true);
	}

	/**
	 * Update license key.
	 *
	 * @param string $email
	 * @param string $key
	 * @return void
	 * 
	 * @since 4.0.0
	 */
	private function updateLicenseKey($email, $key)
	{
		$value = 'joomshaper_email=' . urlencode($email);
		$value .= '&amp;joomshaper_license_key=' . urlencode($key);

		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$fields = [
			$db->quoteName('extra_query') . ' = ' . $db->quote($value),
			$db->quoteName('last_check_timestamp') . ' = ' . $db->quote('0'),
		];

		$query->update($db->quoteName('#__update_sites'))
			->set($fields)
			->where($db->quoteName('name') . ' = ' . $db->quote('SP Page Builder'));

		$db->setQuery($query);

		try
		{
			$db->execute();

			return true;
		}
		catch (Exception $e)
		{
			return false;
		}
	}

	private function getColors()
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		$query->select(['id', 'name', 'colors'])
			->from($db->quoteName('#__sppagebuilder_colors'))
			->where($db->quoteName('published') . ' = 1');
		$db->setQuery($query);

		$colors = [];

		try
		{
			$colors = $db->loadObjectList();
		}
		catch (\Exception $e)
		{
			return [];
		}

		if (!empty($colors))
		{
			foreach ($colors as &$color)
			{
				$color->colors = \json_decode($color->colors);
			}

			unset($color);
		}

		return $colors;
	}

	private function saveColors(string $colorGroups)
	{
		if (!empty($colorGroups))
		{
			$colorGroups = \json_decode($colorGroups);
		}


		$savedColors = $this->getColors();

		if (!empty($savedColors))
		{
			$savedColorsIds = array_map(function ($item)
			{
				return $item->id;
			}, $savedColors);


			$payloadIds = array_map(function ($item)
			{
				return $item->id;
			}, $colorGroups);


			$removedColorsIds = array_filter($savedColorsIds, function ($item) use ($payloadIds)
			{
				return !\in_array($item, $payloadIds);
			});

			if (!empty($removedColorsIds))
			{
				$this->removeColor(array_values($removedColorsIds));
			}
		}

		if (!empty($colorGroups))
		{
			foreach ($colorGroups as $group)
			{
				$this->updateOrCreateColor($group, 'id');
			}
		}
	}

	private function updateOrCreateColor($data, string $primaryKey)
	{
		$isNew = true;

		if (!empty($data->$primaryKey))
		{
			$isNew = false;
		}

		$name = $data->name;
		$colors = !empty($data->colors) ? json_encode($data->colors) : '';
		$record = (object) [
			'id' => !$isNew ? $data->$primaryKey : null,
			'name' => $name,
			'colors' => $colors,
			'created_by' => Factory::getUser()->id,
			'created' => Factory::getDate()->toSql(),
			'published' => 1
		];

		if ($isNew)
		{
			try
			{
				$db = Factory::getDbo();
				return $db->insertObject('#__sppagebuilder_colors', $record, 'id');
			}
			catch (\Exception $e)
			{
				return false;
			}
		}
		else
		{
			try
			{
				$db = Factory::getDbo();
				return $db->updateObject('#__sppagebuilder_colors', $record, 'id', true);
			}
			catch (\Exception $e)
			{
				return false;
			}
		}
	}

	private function removeColor(array $ids)
	{
		$db = Factory::getDbo();
		$query = $db->getQuery(true);

		$query->delete($db->quoteName('#__sppagebuilder_colors'))
			->where($db->quoteName('id') . ' IN (' . implode(',', $ids) . ')');

		$db->setQuery($query);

		try
		{
			$db->execute();
			return true;
		}
		catch (\Exception $e)
		{
			return false;
		}
	}
}
