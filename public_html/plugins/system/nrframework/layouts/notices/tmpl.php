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
?>
<div
	class="tf-notices"
	data-ext-element="<?php echo $ext_element; ?>"
	data-ext-xml="<?php echo $ext_xml; ?>"
	data-ext-type="<?php echo $ext_type; ?>"
	data-exclude="<?php echo htmlspecialchars(json_encode($exclude)); ?>"
	data-root="<?php echo \JURI::base(); ?>"
    data-token="<?php echo JSession::getFormToken(); ?>"
    data-current-url="<?php echo \JURI::getInstance()->toString(); ?>"
>
</div>