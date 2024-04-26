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

use Joomla\CMS\Form\FormHelper;

if (empty($html))
{
    return;
}

$styles = array_filter([
    'max-width' => $width
]);
$styles = array_map(function($k, $v) {
    return $k . ':' . $v . ';';
}, array_keys($styles), $styles);
?>
<div class="nr-responsive-control<?php echo $class; ?>"<?php echo $styles ? ' style="' . implode('', $styles) . '"' : ''; ?>>
    <div class="nr-responsive-control--item <?php echo $breakpoint; ?>">
        <?php echo $html; ?>
    </div>
</div>