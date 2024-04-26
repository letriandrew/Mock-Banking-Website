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

$size = $field->size === 'normal' ? '' : '_' . $field->size;

$imageURL = JURI::root() . 'media/com_convertforms/img/recaptcha_' . $field->theme . $size . '.png';

?>

<img src="<?php echo $imageURL ?>" style="align-self: flex-start;" />