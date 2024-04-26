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

$styles = [
	'border-top-style: ' . $field->border_style,
	'border-top-width: ' . (int) $field->border_width . 'px' ,
	'border-top-color: ' . $field->border_color,
	'margin-top:' . (int) $field->margin_top . 'px',
	'margin-bottom:' . (int) $field->margin_bottom . 'px'
];

?>

<div class="cf-divider" style="<?php echo implode(';', $styles); ?>"></div>