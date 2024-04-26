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

defined('_JEXEC') or die;

extract($displayData);

$options = isset($options) ? $options : $displayData;

if ($options['load_css_vars'] && !empty($options['custom_css']))
{
	JFactory::getDocument()->addStyleDeclaration($options['custom_css']);
}
?>
<div class="nrf-widget openstreetmap map-widget nr-address-component<?php echo $options['css_class']; ?>" id="<?php echo $id; ?>" data-options="<?php echo htmlspecialchars(json_encode($options)); ?>">
	<div class="map-item"></div>
</div>