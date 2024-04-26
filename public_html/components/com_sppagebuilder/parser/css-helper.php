<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Uri\Uri;

/** No direct access. */
defined('_JEXEC') or die('Restricted access');

/**
 * Migrate the CSS method used in the site.php file.
 *
 * @since	4.0.0
 */
class CSSHelper extends HelperBase
{
	/**
	 * The default device
	 *
	 * @var 	string
	 * @since 	4.0.0
	 */
	private static $device;

	/**
	 * The font registry array. Registering all the fonts here.
	 *
	 * @var 	array
	 * @since 	4.0.0
	 */
	private $fontRegistry = [];

	/**
	 * The constructor function of the CSS Migrator.
	 *
	 * @param 	string 	$id		The addon ID selector.
	 * @param 	bool 	$force	Flag to set the ID as whatever it is provided, no sanitization is required.
	 *
	 * @since 	4.0.0
	 */
	public function __construct(string $id, bool $force = false)
	{
		parent::__construct($id, $force);
		self::$device = SpPgaeBuilderBase::$defaultDevice;
	}

	private function getFallbackValue($settings, $prop)
	{
		$keys = explode('.', $prop, 2);

		if (empty($keys)) return null;

		if (count($keys) > 1)
		{
			list($key1, $key2) = $keys;

			$originalKey1 = $key1 . '_original';
			$originalKey2 = $key2 . '_original';

			if (isset($settings->$originalKey1))
			{
				$key1 = $originalKey1;
			}

			if (isset($settings->$originalKey2))
			{
				$key2 = $originalKey2;
			}

			return isset($settings->$key1->$key2) ? $settings->$key1->$key2 : null;
		}

		$key1 = $keys[0];

		$originalKey = $key1 . '_original';

		if (isset($settings->$originalKey))
		{
			$key1 = $originalKey;
		}

		return isset($settings->$key1) ? $settings->$key1 : null;
	}

	/**
	 * Register a font name to the font registry.
	 *
	 * @param string $fontName	The font name which will be stored to the registry.
	 *
	 * @return 	void
	 * @since 	4.0.0
	 */
	private function registerFont(string $fontName)
	{
		if (!\in_array($fontName, $this->fontRegistry))
		{
			$this->fontRegistry[] = $fontName;
		}
	}

	/**
	 * Get the font registry array.
	 *
	 * @return 	array
	 * @since	4.0.0
	 */
	private function getFontRegistry(): array
	{
		return $this->fontRegistry;
	}

	/**
	 * Load the google font for the typography fields.
	 *
	 * @return 	void
	 * @since 	4.0.0
	 */
	public function loadGoogleFont(string $fontName)
	{
		$systemFonts = array(
			'Arial',
			'Tahoma',
			'Verdana',
			'Helvetica',
			'Times New Roman',
			'Trebuchet MS',
			'Georgia'
		);

		if (!empty($fontName))
		{
			if (!\in_array($fontName, $systemFonts))
			{
				$fontPath =  '/media/com_sppagebuilder/assets/google-fonts/' . $fontName . '/stylesheet.css';
				$params = ComponentHelper::getParams('com_sppagebuilder');
				$disableGoogleFonts = (bool) $params->get('disable_google_fonts', 0);

				if ($disableGoogleFonts)
				{
					return;
				}

				if (file_exists(JPATH_ROOT . $fontPath))
				{
					Factory::getDocument()->addStylesheet(Uri::root(true) . $fontPath);
				}
				else
				{
					$linkTagUrl = "https://fonts.googleapis.com/css?family=" . $fontName . ":100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&display=swap";
					Factory::getDocument()->addStylesheet($linkTagUrl);
				}
			}
		}
	}

	public function loadLocalFont(string $fontName)
	{
		$fontPath =  '/media/com_sppagebuilder/assets/custom-fonts/' . $fontName . '/stylesheet.css';

		if (file_exists(JPATH_ROOT . $fontPath))
		{
			Factory::getDocument()->addStylesheet(Uri::root(true) . $fontPath);
		}
	}

	private function purifyTypographyObject($obj)
	{
		$device = SpPgaeBuilderBase::$defaultDevice;
		$obj = $obj ?? '';

		if (!\is_object($obj))
		{
			if (preg_match("@[^-|\d+]@", $obj))
			{
				$obj = \preg_replace("@\D+$@", '', $obj);
			}

			$newObject = AddonHelper::initDeviceObject();

			foreach ($newObject as $k => &$v)
			{
				// Todo: need to replace unit value "px" with dynamic $obj unit value.
				$v =  (object) ['value' => $k === $device ? $obj : '', 'unit' => 'px'];
			}

			unset($v);

			return $newObject;
		}
		else
		{
			foreach ($obj as $k => &$v)
			{
				if (!\is_object($v))
				{
					$v = (object) ['value' => $v, 'unit' => 'px'];
				}
			}

			unset($v);
		}

		return $obj;
	}

	/**
	 * Typography style generation by using the settings and fallback array.
	 *
	 * @param 	string 	$selector	The CSS selector.
	 * @param 	object 	$settings	The settings object.
	 * @param 	string 	$prop		The settings prop or the typography field name.
	 * @param 	array 	$fallback	The fallback array.
	 *
	 * @return 	string
	 * @since 	4.0.0
	 */
	public function typography($selector, $settings, $prop, $fallback = [])
	{
		$hasFallback = !empty($fallback) && !isset($settings->$prop);

		if ($hasFallback)
		{
			$font          = array_key_exists('font', $fallback) ? $fallback['font'] : "";
			$size          = array_key_exists('size', $fallback) ? $fallback['size'] : "";
			$lineHeight    = array_key_exists('line_height', $fallback) ? $fallback['line_height'] : "";
			$letterSpacing = array_key_exists('letter_spacing', $fallback) ? $fallback['letter_spacing'] : "";
			$uppercase     = array_key_exists('uppercase', $fallback) ? $fallback['uppercase'] : "";
			$italic        = array_key_exists('italic', $fallback) ? $fallback['italic'] : "";
			$underline     = array_key_exists('underline', $fallback) ? $fallback['underline'] : "";
			$weight        = array_key_exists('weight', $fallback) ? $fallback['weight'] : "";

			$settings->$prop = new \stdClass;
			$settings->$prop->font = $this->getFallbackValue($settings, $font);
			$settings->$prop->weight = $this->getFallbackValue($settings, $weight);
			$settings->$prop->uppercase = $this->getFallbackValue($settings, $uppercase);
			$settings->$prop->italic = $this->getFallbackValue($settings, $italic);
			$settings->$prop->underline = $this->getFallbackValue($settings, $underline);
			$settings->$prop->size = $this->getFallbackValue($settings, $size);
			$settings->$prop->line_height = $this->getFallbackValue($settings, $lineHeight);
			$settings->$prop->letter_spacing = $this->getFallbackValue($settings, $letterSpacing);
		}

		if (!isset($settings->$prop)) return '';

		$typography = $settings->$prop;
		$objectKeys = ["letter_spacing", "line_height", "size"];

		foreach ($objectKeys as $key)
		{
			if (isset($typography->$key))
			{
				$typography->$key = $this->purifyTypographyObject($typography->$key);
			}
		}

		if (!empty($typography->weight) && \preg_match("@[^\d]+@", $typography->weight))
		{
			$typography->weight = (int) $typography->weight;
		}

		/** If font exists to the typography field then register the font and load it. */
		if (!empty($typography->font))
		{
			$this->registerFont($typography->font);

			if (isset($typography->type) && $typography->type === 'local')
			{
				$this->loadLocalFont($typography->font);
			}
			else
			{
				$this->loadGoogleFont($typography->font);
			}
		}

		$props = [
			'font'           => isset($typography->font) && !empty($typography->font) ? 'font-family' : null,
			'weight'         => isset($typography->weight) && !empty($typography->weight) ?  'font-weight' : null,
			'uppercase'      => isset($typography->uppercase) && !empty($typography->uppercase)  ? 'text-transform' : null,
			'italic'         => isset($typography->italic) && !empty($typography->italic) ? 'font-style' : null,
			'underline'      => isset($typography->underline) && !empty($typography->underline) ? 'text-decoration' : null,
			'size'           => isset($typography->size) && !empty($typography->size) ? 'font-size' : null,
			'line_height'    => isset($typography->line_height) && !empty($typography->line_height) ? 'line-height' : null,
			'letter_spacing' => isset($typography->letter_spacing) && !empty($typography->letter_spacing) ? 'letter-spacing' : null
		];
		$units = ['font' => false, 'weight' => false, 'size' => false, 'uppercase' => false, 'italic' => false, 'underline' => false];
		$modifiers = [];
		$defaults = ['uppercase' => 'uppercase', 'italic' => 'italic', 'underline' => 'underline'];
		$typographyStyle = $this->generateStyle($selector, $typography, $props, $units, $modifiers, $defaults);

		return $typographyStyle;
	}

	/**
	 * Parse the color and generate a valid color property value.
	 *
	 * @param 	mixed 	$color	Object or string value of the color.
	 *
	 * @return 	string 	Returns the color value string.
	 * @since 	4.0.0
	 */
	public static function parseColor($settings, $prop): string
	{
		$originalProp = $prop . '_original';

		if (isset($settings->$originalProp))
		{
			$prop = $originalProp;
		}

		$color = isset($settings->$prop) ? $settings->$prop : '';

		if (\is_string($color))
		{
			return $color;
		}

		if (\is_object($color))
		{
			if (isset($color->type))
			{
				$color1 = "#398AF1";
				$color2 = "#5EDCED";

				if (!empty($color->color))
				{
					$color1 = !empty(preg_replace("@\s+@", "", $color->color)) ? $color->color : '#398AF1';
				}

				if (!empty($color->color2))
				{
					$color2 = !empty(preg_replace("@\s+@", "", $color->color2)) ? $color->color2 : '#5EDCED';
				}

				switch ($color->type)
				{
					case 'solid':
						return preg_replace("@\s+@", "", $color->color);
					case 'linear':
						$start = $color->pos ?? 0;
						$end = $color->pos2 ?? 100;
						return 'linear-gradient(' . ($color->deg ?? 0) . 'deg, ' . $color1 . ' ' . $start . '%, ' . $color2 . ' ' . $end . '%)';
					case 'radial':
						$start = $color->pos ?? 0;
						$end = $color->pos2 ?? 100;
						$position = $color->radialPos ?? 'center center';
						return 'radial-gradient(at ' . $position . ', ' . $color1 . ' ' . $start . '%, ' . $color2 . ' ' . $end . '%)';
					default:
						return $color->color ?? '';
				}
			}
		}

		return '';
	}

	/**
	 * Parse the alignment prop and generate the alignment value.
	 *
	 * @param 	object 	$settings	The settings object.
	 * @param 	string 	$prop		The alignment prop name.
	 * @param 	bool 	$flex		If the alignment for the flex or not.
	 *
	 * @return 	string 	The alignment value.
	 * @since 	4.0.0
	 */
	public static function parseAlignment($settings, $prop, $flex = false)
	{
		$primitiveProp = $prop;
		$originalProp = $prop . '_original';

		$prop = isset($settings->$originalProp) ? $originalProp : $prop;

		if (!isset($settings->$prop))
		{
			return '';
		}

		$alignmentMap = [
			'sppb-text-center' => 'center',
			'sppb-text-left'   => 'left',
			'sppb-text-right'  => 'right',
		];

		$flexMap = [
			'center' => 'center',
			'left'   => 'flex-start',
			'right'  => 'flex-end'
		];

		$alignment = $settings->$prop;

		if (self::hasMultiDeviceSettings($alignment))
		{
			if ($flex)
			{
				foreach ($alignment as &$value)
				{
					$value = isset($flexMap[$value]) ? $flexMap[$value] : $value;
				}

				unset($value);
			}
			else
			{
				foreach ($alignment as &$value)
				{
					$value = isset($alignmentMap[$value]) ? $alignmentMap[$value] : $value;
				}

				unset($value);
			}


			return $alignment;
		}

		$align =  isset($alignmentMap[$alignment]) ? $alignmentMap[$alignment] : $alignment;

		return $flex && isset($flexMap[$align]) ? $flexMap[$align] : $align;
	}

	private static function isEnabledBoxShadow($shadow)
	{
		if (empty($shadow))
		{
			return false;
		}

		if (\is_string($shadow))
		{
			$shadow = preg_replace("@\s+@", ' ', $shadow);
			$shadow = explode(' ', $shadow);
			$shadow = (object) $shadow;
		}

		$numberKeys = ['ho', 'vo', 'blur', 'spread'];

		if (\is_object($shadow))
		{

			if (isset($shadow->enabled))
			{
				return $shadow->enabled;
			}

			foreach ($shadow as $key => &$value)
			{
				if (\in_array($key, $numberKeys))
				{
					$value = (float) $value;

					if (!empty($value))
					{
						return true;
					}
				}

				if ($key === 'color')
				{
					$value = strtolower($value);

					return !($value === '#fff' || $value === '#ffffff');
				}
			}

			unset($value);
		}

		return false;
	}

	/**
	 * Parse the box shadow values and generate the box-shadow CSS property.
	 *
	 * @param 	object 	$settings		The addon settings.
	 * @param 	string 	$prop			The box-shadow settings key inside the $settings object.
	 * @param 	boolean $isTextShadow	Data comes from text-shadow or box shadow.
	 *
	 * @return 	string 	The generated box-shadow.
	 * @since 	4.0.0
	 */
	public static function parseBoxShadow($settings, string $prop, $isTextShadow = false): string
	{
		if (!isset($settings->$prop))
		{
			return '';
		}

		$box = $settings->$prop;

		if (\is_object($box))
		{
			$horizontalOffset = !empty($box->ho) ? $box->ho . 'px' : '0px';
			$verticalOffset = !empty($box->vo) ? $box->vo . 'px' : '0px';
			$blur = !empty($box->blur) ? $box->blur . 'px' : '0px';
			$spread = (!$isTextShadow) ? (!empty($box->spread) ? $box->spread . 'px' : '0px') : ' ';
			$color = !empty($box->color) ? $box->color : '';

			return self::isEnabledBoxShadow($box) ? $horizontalOffset . " " . $verticalOffset . " " . $blur . " " . $spread . " " . $color : '';
		}

		if ($box === '0 0 0 0 #fff')
		{
			return '';
		}

		return $box;
	}

	/**
	 * Check if the value has a multi-device settings.
	 *
	 * @param 	mixed 		$value		The settings value.
	 *
	 * @return 	boolean
	 * @since	4.0.0
	 */
	private static function hasMultiDeviceSettings($value): bool
	{
		return isset($value->xl)
			|| isset($value->lg)
			|| isset($value->md)
			|| isset($value->sm)
			|| isset($value->xs);
	}

	/**
	 * Extract the value field, if the field has a unit property then add the unit with it.
	 *
	 * @param 	object 	$value	The value object.
	 *
	 * @return 	string 	The extracted string value.
	 * @since 	4.0.0
	 */
	private function extractValue($value): string
	{
		if (\is_object($value))
		{
			$_value = '';

			if (isset($value->value))
			{
				$_value = is_object($value->value) ? $value->value->value : $value->value;
			}

			if (!empty($_value) && isset($value->unit))
			{
				$_value .= is_object($value->unit) ? $value->unit->unit : $value->unit;
			}

			return $_value;
		}

		if (\is_array($value) && empty($value))
		{
			return $value = '';
		}

		return (string) $value;
	}

	/**
	 * Generate style for a specific selector with help of settings object, prop map, units etc.
	 *
	 * @param 	string 		$selector	The selector string where to apply the styles.
	 * @param 	\stdClass 	$settings	The settings object.
	 * @param 	array 		$propMap	The property map. The structure of the array is [prop => cssProp | [cssProps]]
	 * @param 	mixed 		$unit		The unit array. The structure of the array is [prop => unitValue | false]
	 * @param 	array 		$modifier	The modifier array. This array says if any modifier is needed to apply
	 * 									to this property. Currently we are covering the spacing property.
	 * 									if [prop => 'spacing'] is provided then handle the value as spacing value.
	 * @param 	mixed 		$default	The default values. This would be useful if we don't want to
	 * 									use the $settings->$prop value as a result but want
	 * 									user specific value as a value.
	 * @param 	boolean 	$important	The important specifier says if the property need
	 *									to append !important; at the end or not.
	 * @param 	string 		$static		Provided any static style value. If provided that would be contacted.
	 *
	 * @return string 					The CSS style with media query.
	 * @since 4.0.0
	 */
	public function generateStyle(string $selector, $settings, $propMap, $unit = 'px', $modifier = [], $default = null, $important = false, $static = ''): string
	{
		$device = self::$device;
		$selector = $this->generateSelector($selector);
		$css = [];
		$media = [];
		$_unit = 'px';
		$css[] = $selector . "{";

		if (!empty($static))
		{
			$css[] = $static;
		}

		foreach ($propMap as $prop => $cssProp)
		{
			if (\is_null($cssProp))
			{
				continue;
			}

			$primitiveProp = $prop;
			$originalProp = $prop . '_original';

			if (isset($settings->$originalProp))
			{
				$prop = $originalProp;
			}

			$_unit      = \is_array($unit) ? ($unit[$primitiveProp] ?? 'px') : $unit;
			$_important = \is_array($important) ? ($important[$primitiveProp] ?? false) : $important;
			$_default   = \is_array($default) ? ($default[$primitiveProp] ?? null) : $default;

			if (!isset($settings->$prop) && \is_null($_default))
			{
				continue;
			}

			if (isset($settings->$prop) && \is_string($settings->$prop) && preg_replace("@\s+@", '', $settings->$prop) === '' && \is_null($_default))
			{
				continue;
			}

			if (\is_string($cssProp))
			{
				$cssProp = [$cssProp];
			}

			if (!\is_null($_default))
			{
				$str = '';

				foreach ($cssProp as $attr)
				{
					$str .= $attr . ': ' . $_default . ';';
				}

				$css[] = $str;

				continue;
			}

			if (self::hasMultiDeviceSettings($settings->$prop))
			{
				$multiDeviceData = null;

				if (isset($modifier[$primitiveProp]) && $modifier[$primitiveProp] === 'spacing')
				{
					$multiDeviceData = AddonHelper::generateSpacingObject($settings, $prop, $cssProp, $device);
				}
				else
				{
					$multiDeviceData = AddonHelper::generateMultiDeviceObject($settings, $prop, $cssProp, $device, $_important, $_unit);
				}

				$media[] = $multiDeviceData;
				$css[] = $multiDeviceData->$device;
				unset($multiDeviceData);
			}
			else
			{
				if (isset($settings->$prop))
				{
					if (isset($modifier[$primitiveProp]) && $modifier[$primitiveProp] === 'spacing')
					{
						$_object = AddonHelper::generateSpacingObject($settings, $prop, $cssProp, $device);

						$media[] = $_object;
					}

					$val = $this->extractValue($settings->$prop);

					if (isset($val) && $val !== '')
					{
						foreach ($cssProp as $attr)
						{
							$str = "";

							if (!empty($_object) && !empty($_object->$device))
							{
								$str .= $_object->$device;
								unset($_object);
							}
							else
							{
								$str .= (strpos($attr, '%1$s') || strpos($attr, '%s')) !== false
									? \sprintf($attr, $this->extractValue($settings->$prop) ?? '')
									: $attr . ': ' . $this->extractValue($settings->$prop) ?? '';
							}

							if (!empty($_unit))
							{
								$str .= $_unit;
							}

							$str .= $_important ? ' !important' : '';

							if (substr($str, -1) !== ';')
							{
								$str .= ';';
							}

							$css[] = $str;
						}
					}
				}
			}
		}

		$css[] = "}";

		if (!empty($media))
		{
			$mediaStyle = array_map(function ($key) use ($selector, $media)
			{
				$str = AddonHelper::mediaQuery($key);
				$str .= $selector . "{";

				foreach ($media as $object)
				{
					$str .= $object->$key;
				}

				$str .= "}";
				$str .= "}";

				return $str;
			}, $this->getDeviceListExcludeDefault());

			$css[] = implode("\r\n", $mediaStyle);
		}

		$css = array_filter($css, function ($style)
		{
			return !empty($style);
		});

		return implode("\r\n", $css);
	}

	/**
	 * Parse the backdrop filter values and generate the backdrop filter CSS property.
	 *
	 * @param object $settings			The Add-On settings.
	 * @param string $property			The backdrop-filter settings key inside the $settings object.
	 * @param string $propertyValue		The backdrop settings value.
	 * @since  4.0.8
	 * @return string
	 */
	public static function parseBackDropFilter($settings, string $property, string $propertyValue)
	{
		if (!isset($settings->$property))
		{
			return '';
		}

		$unit 		 = ($settings->$property == 'blur') ? 'px' : '%';
		$parsedValue = $settings->$property . '(' . $settings->$propertyValue . $unit . ')';

		return $parsedValue;
	}

	/**
	 * Generate missing break points of field width for old layouts.
	 *
	 * @param  object $fieldWidth	given breakpoints of field width
	 *
	 * @return object
	 *
	 * @since  4.0.9
	 */
	public static function generateMissingBreakPoints($fieldWidth)
	{
		$missing_breakpoints = ['xl', 'lg'];

		foreach ($missing_breakpoints as $key)
		{
			if (!property_exists($fieldWidth, $key))
			{
				$fieldWidth->$key = isset($fieldWidth->md) && $fieldWidth->md ? $fieldWidth->md : "";
			}
		}

		return $fieldWidth;
	}
}
