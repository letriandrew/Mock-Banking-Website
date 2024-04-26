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

JFormHelper::loadFieldClass('subform');

class JFormFieldCFSubform extends JFormFieldSubform
{
	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   3.6
	 */
	protected function getInput()
	{
		// The following script toggles the required attribute for all Email Notification options.
		JFactory::getDocument()->addScriptDeclaration('
			jQuery(function($) {
				$("input[name=\'jform[sendnotifications]\']").on("change", function() {
					var enabled = $(this).is(":checked");
					var exclude_fields = $("input[id*=reply_to], input[id$=attachments]");
					var fields = $("#behavior-emails .subform-repeatable-group").find("input, textarea").not(exclude_fields);

					if (enabled) {
						fields.attr("required", "required").addClass("required");
					} else {
						fields.removeAttr("required").removeClass("required");
					}
				});
			});
		');

		return parent::getInput();
	}
}
