<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/

//no direct access
defined ('_JEXEC') or die ('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;



$font = $displayData['font'];


$system = array(
	'Arial',
	'Tahoma',
	'Verdana',
	'Helvetica',
	'Times New Roman',
	'Trebuchet MS',
	'Georgia'
);

if(!in_array($font, $system))
{
	$google_font = '//fonts.googleapis.com/css?family=' . str_replace(' ', '+', $font) . ':100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic&display=swap';
	$disableGoogleFonts = ComponentHelper::getParams("com_sppagebuilder")->get('google_fonts', 0);
	if ($disableGoogleFonts != 1)
	{
		Factory::getDocument()->addStylesheet($google_font);
	}
}
