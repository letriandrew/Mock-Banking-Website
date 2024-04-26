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

include_once JPATH_PLUGINS . '/system/nrframework/fields/tfphonecontrol.php';

$_field = new \JFormFieldTFPhoneControl;

$element = new \SimpleXMLElement('
	<field
		name="' . $field->input_name . '"
		required="' . ($field->required === '1') . '"
		readonly="' . ($field->readonly === '1') . '"
		placeholder="' . $field->placeholder . '"
		inputmask="' . htmlspecialchars(json_encode($field->inputmask)) . '"
		browserautocomplete="' . ($field->browserautocomplete === '1') . '"
		input_class="cf-input"
		type="TFPhoneControl"
	/>
');

$_field->setup($element, $field->value);
?>
<div class="cf-phone-number-wrapper"<?php echo $field->readonly === '1' ? ' readonly' : ''; ?>>
	<?php echo $_field->__get('input'); ?>
</div>