<?php

/**
 * @package         Convert Forms
 * @version         4.2.1 Free
 * 
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            https://www.tassos.gr
 * @copyright       Copyright © 2023 Tassos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die('Restricted access');

extract($displayData);

if ($countdown_type === 'static' && (empty($value) || $value === '0000-00-00 00:00:00'))
{
	return;
}

if ($load_stylesheet)
{
	foreach (\NRFramework\Widgets\Countdown::getCSS($theme) as $path)
	{
		\JHtml::stylesheet($path, ['relative' => true, 'version' => 'auto']);
	}
}

if ($load_css_vars && !empty($custom_css))
{
	JFactory::getDocument()->addStyleDeclaration($custom_css);
}

foreach (\NRFramework\Widgets\Countdown::getJS() as $path)
{
	\JHtml::script($path, ['relative' => true, 'version' => 'auto']);
}
?>
<div class="nrf-widget nrf-countdown<?php echo $css_class; ?>" id="<?php echo $id; ?>" <?php echo $atts; ?>></div>