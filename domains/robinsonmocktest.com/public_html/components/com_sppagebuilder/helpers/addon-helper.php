<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Router\Route;

/** No direct access. */
defined('_JEXEC') or die('Restricted access');

/**
 * The helper class for migrating from 3.x to 4.x
 *
 * @since	4.0.0
 */
final class AddonHelper
{
	public static $deviceList = ["xl", "lg", "md", "sm", "xs"];

	/**
	 * Generate the media query with respect to the device
	 *
	 * @param 	string 	$device
	 *
	 * @return 	string
	 * @since 	4.0.0
	 */
	public static function mediaQuery(string $device): string
	{
		$MEDIA_QUERY_MAP = [
			'xl' => '@media (max-width: 1399.98px) {',
			'lg' => '@media (max-width: 1199.98px) {',
			'md' => '@media (max-width: 991.98px) {',
			'sm' => '@media (max-width: 767.98px) {',
			'xs' => '@media (max-width: 575.98px) {',
		];

		return $MEDIA_QUERY_MAP[$device];
	}


	/**
	 * Initialize the device object with help of the device list.
	 *
	 * @param 	string 	$type	The type value. Accepted values are 'string', 'number', 'boolean'
	 *
	 * @return 	\stdClass
	 * @since 	4.0.0
	 */
	public static function initDeviceObject(string $type = 'string'): \stdClass
	{
		$DEVICE_MAP = ['string' => '', 'number' => 0, 'boolean' => false];
		$deviceObject = new \stdClass;

		foreach (self::$deviceList as $device)
		{
			$deviceObject->$device = $DEVICE_MAP[$type];
		}

		return $deviceObject;
	}

	/**
	 * Generate Multiple device wise object from settings object.
	 *
	 * @param 	\stdClass 	$settings	The settings object.
	 * @param 	string 		$prop		The settings property.
	 * @param 	string 		$cssProp	The CSS property.
	 * @param 	string 		$device		The device value.
	 * @param 	boolean 	$important	If the CSS property need to append the !important keyword.
	 * @param 	string 		$unit		The unit of the CSS property.
	 *
	 * @return 	\stdClass
	 * @since 	4.0.0
	 */
	public static function generateMultiDeviceObject($settings, $prop, $cssProp, $device, $important = false, $unit = "px")
	{
		$data = self::initDeviceObject();

		if (!isset($settings->$prop)) return $data;

		if (\is_string($cssProp))
		{
			$cssProp = [$cssProp];
		}

		$value = $settings->$prop;


		if (empty($unit))
		{
			$unit = '';
		}
		else
		{
			if (!empty($value->unit))
			{
				$unit = $value->unit;
			}
		}

		if (\is_object($value))
		{
			foreach ($data as $key => $value)
			{
				if (isset($settings->$prop->$key))
				{
					if (\is_object($settings->$prop->$key))
					{
						$_value = $settings->$prop->$key->value;

						if (preg_replace("@\s+@", '', $_value) !== '')
						{
							$_unit =  $settings->$prop->$key->unit;

							foreach ($cssProp as $css)
							{
								$data->$key = strpos($css, '%s') !== false
									? \sprintf($css, $_value)
									: $css . ': ' . ($_value ?? '');
								$data->$key .= $_unit ?? '';
								$data->$key .= $important ? ' !important;' : ';';
							}
						}
					}
					else
					{
						if (preg_replace("@\s+@", '', $settings->$prop->$key) !== '')
						{
							foreach ($cssProp as $css)
							{
								$data->$key .= strpos($css, '%s') !== false
									? \sprintf($css, $settings->$prop->$key)
									: $css . ': ' . ($settings->$prop->$key ?? '');

								$data->$key .= $unit ?? '';
								$data->$key .= $important ? ' !important;' : ';';
							}
						}
					}
				}
			}
		}
		else
		{
			if (isset($settings->$prop))
			{
				if (preg_replace("@\s+@", '', $settings->$prop) !== '')
				{
					foreach ($cssProp as $css)
					{
						$data->$device .= strpos($css, '%s') !== false
							? \sprintf($css, $settings->$prop)
							: $css . ': ' . ($settings->$prop ?? '');

						$data->$device .= $unit;
						$data->$device .= $important ? ' !important;' : ';';
					}
				}
			}
		}

		return $data;
	}

	/**
	 * Generate Spacing object from settings object.
	 *
	 * @param 	\stdClass 	$settings	The settings object.
	 * @param 	string 		$type		The spacing type, i.e. padding or margin
	 * @param 	string 		$device		The device.
	 *
	 * @return 	\stdClass
	 * @since 	4.0.0
	 */
	public static function generateSpacingObject($settings, $prop, $cssProp, $device)
	{
		$object = self::initDeviceObject();
		$positions = ["top", "right", "bottom", "left"];
		$borderRadiusPositions = ["top-left", "top-right", "bottom-right", "bottom-left"];

		if (\is_string($cssProp))
		{
			$cssProp = [$cssProp];
		}

		$value = $settings->$prop;

		if (!isset($settings->$prop) || empty($settings->$prop)) return $object;

		if (\is_object($settings->$prop))
		{
			foreach ($object as $key => $_)
			{
				if (isset($settings->$prop->$key))
				{
					$value = (string) $settings->$prop->$key;
					$value = \preg_replace("@\s+@", ' ', $value);
					$valueArray = \ctype_space((string)$value) || $value === '' ? [] : explode(' ', $value);

					if (!empty($valueArray))
					{
						$object->$key = implode(
							"\r\n",
							array_map(function ($x, $i) use ($cssProp, $positions, $borderRadiusPositions)
							{
								$str = '';

								foreach ($cssProp as $attr)
								{
									$x = strpos($x, '%s') !== false ? \sprintf($attr, $x) : $x;

									if ($attr === "border-radius")
									{
										$attr = explode('-', $attr);

										$str .= isset($x) && !\ctype_space((string)$x) && !empty((string) $x) ? $attr[0] . '-' . $borderRadiusPositions[$i] . '-' . $attr[1] . ': ' . $x . ';' : '';
									}
									else
									{
										$str .= isset($x) && !\ctype_space((string) $x) && !empty((string) $x) ? $attr . '-' . $positions[$i] . ': ' . $x . ';' : '';
									}
								}

								return $str;
							}, $valueArray, array_keys($valueArray))
						);
					}
				}
			}
		}
		else
		{
			$value = isset($settings->$prop) ? (string) $settings->$prop : '';
			$value = \preg_replace("@\s+@", ' ', $value);
			$valueArray = \ctype_space((string)$value) || $value === '' ? [] : explode(' ', $value);

			$object->$device = implode(
				"\r\n",
				array_map(function ($x, $i) use ($cssProp, $positions, $borderRadiusPositions)
				{
					$str = '';

					foreach ($cssProp as $attr)
					{
						$x = strpos($x, '%s') !== false ? \sprintf($attr, $x) : $x;

						if ($attr === "border-radius")
						{
							$attr = explode('-', $attr);
							$str .= isset($x) ? $attr[0] . '-' . $borderRadiusPositions[$i] . '-' . $attr[1] . ': ' . $x . ';' : '';
						}
						else
						{
							$str .= isset($x) ? $attr . '-' . $positions[$i] . ': ' . $x . ';' : '';
						}
					}

					return $str;
				}, $valueArray, array_keys($valueArray))
			);
		}

		return $object;
	}

	/**
	 * Convert new link form old link.
	 *
	 * @param  \stdClass 	$settings	The settings object.
	 * @param  string 		$prop		The settings property.	
	 * @param  array  		$fallback	The fallback settings property.
	 * 
	 * @return array
	 * @since  4.0.0
	 */
	public static function parseLink($settings, $prop, $fallback = [])
	{
		$hasFallback = !empty($fallback) && !isset($settings->$prop);

		if ($hasFallback)
		{
			$url = array_key_exists('url', $fallback) ? $fallback['url'] : '';
			$newTab = array_key_exists('new_tab', $fallback) ? $fallback['new_tab'] : '';
			$nofollow = array_key_exists('nofollow', $fallback) ? $fallback['nofollow'] : '';
			$noreferrer = array_key_exists('noreferrer', $fallback) ? $fallback['noreferrer'] : '';
			$noopener = array_key_exists('noopener', $fallback) ? $fallback['noopener'] : '';

			$settings->$prop = new \stdClass;
			$settings->$prop->url = isset($settings->$url) && \is_string($settings->$url) ? $settings->$url : '';
			$settings->$prop->new_tab = $settings->$newTab ?? 0;
			$settings->$prop->nofollow = $settings->$nofollow ?? 0;
			$settings->$prop->noreferrer = $settings->$noreferrer ?? 0;
			$settings->$prop->noopener = $settings->$noopener ?? 0;
			$settings->$prop->type = 'url';
		}

		if (empty($settings->$prop)) return ['', ''];

		$link = $settings->$prop;

		if (\is_string($link))
		{
			return [$link, ''];
		}

		$url 	= '';
		$target = '';
		$rel 	= '';

		if (!empty($link->type))
		{
			switch ($link->type)
			{
				case 'menu':
					!empty($link->menu) ? $url = Route::_($link->menu) : $url = '';
					break;
				case 'page':
					!empty($link->page) ? $url = Uri::root(true) . '/index.php?option=com_sppagebuilder&view=page&id=' . $link->page : $url = '';
					break;
				case 'url':
					$url = $link->url;
					break;
				default:
					$url = '';
					$target = '';
					break;
			}
		}

		if (!empty($link->new_tab))
		{
			$target .= 'target="_blank"';
		}

		if (!empty($link->nofollow))
		{
			$rel 	 = "nofollow";
		}

		if (!empty($link->noreferrer))
		{
			$rel .= " noreferrer";
		}

		if (!empty($link->noopener))
		{
			$rel .= " noopener";
		}

		$target .= !empty($rel) ?  ' rel="' . trim($rel) . '"' : '';
		return [$url, $target];
	}
}
