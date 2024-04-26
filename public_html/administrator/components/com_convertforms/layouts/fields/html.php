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

// Since 27 Jun, .cf-control-input makes uses display:flex; which obviously puts each element on new line. To prevent this from happening in the HTML field, we need to wrap it with an extra <div>
?>

<div>
    <?php echo $field->text; ?>
</div>