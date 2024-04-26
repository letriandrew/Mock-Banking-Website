<?php
/**
 * @package     SP Page Builder
 *
 * @copyright   Copyright (c) 2010 - 2021 JoomShaper. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\HTML\HTMLHelper;

class JFormFieldResetcss extends FormField {

	protected $type = 'Resetcss';

	protected function getInput() {

		HTMLHelper::_('jquery.framework');
		$doc = Factory::getDocument();
		$doc->addScriptDeclaration('jQuery(function($) {
			$("#btn-reset-css").on("click", function(event) {
				event.preventDefault();
				var $this = $(this);
				$this.text($this.data("loading"));
				var request = {
					"option" : "com_sppagebuilder",
					"task" : "resetcss"
				};
				$.ajax({
					type   : "POST",
					data   : request,
					success: function (data) {
						$this.text($this.data("text"));
					}
				});
				
			});
		});');

		return '<a id="btn-reset-css" class="btn btn-default" data-text="'. Text::_('COM_SPPAGEBUILDER_RESET_CSS_TEXT') .'" data-loading="'. Text::_('COM_SPPAGEBUILDER_RESET_CSS_TEXT_LOADING') .'" href="#">'. Text::_('COM_SPPAGEBUILDER_RESET_CSS_TEXT') .'</a>';
	}
}
