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


$atts = [
	'half_ratings' => $field->half_ratings,
	'icon' => basename($field->icon, '.svg'),
	'size' => (int) $field->size,
	'selected_color' => $field->selected_color,
	'unselected_color' => $field->unselected_color,
	'max_rating' => $field->max_rating,
	'load_css_vars' => true,
 ];

echo $class->toWidget($atts);

?>