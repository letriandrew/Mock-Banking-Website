/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
jQuery(function ($) {
	if ($("#toolbar-save-copy").length > 0) {
		$("#toolbar-save-copy").remove();
	}

	if ($("#toolbar-apply .button-apply").length > 0) {
		$("#toolbar-apply .button-apply").removeAttr("onclick").removeAttr("onClick");
	}
	if ($("#toolbar-save .button-save").length > 0) {
		$("#toolbar-save .button-save").removeAttr("onclick").removeAttr("onClick");
	}
	if ($("#toolbar-save-new .button-save-new").length > 0) {
		$("#toolbar-save-new .button-save-new").removeAttr("onclick").removeAttr("onClick");
	}

	$("#toolbar-apply .button-apply, .button-save, .button-save-new").on("click", function (event) {
		event.preventDefault();

		var action_id = event.target.parentNode.id;
		var task = "module.apply";

		if (action_id == "toolbar-save") {
			task = "module.save";
		} else if (action_id == "toolbar-save-new") {
			task = "module.save2new";
		}

		var data = {
			id: $("#sppagebuilder_module_id").val(),
			title: $("#jform_title").val(),
			content: $("#jform_content_content").val(),
		};

		$.ajax({
			type: "POST",
			url: pagebuilder_base + "administrator/index.php?option=com_sppagebuilder&task=page.module_save",
			data: data,
			success: function (response) {
				var data = jQuery.parseJSON(response);
				if (data.status) {
					Joomla.submitbutton(task);
				} else {
					alert(data.message);
				}
			},
		});
	});
});
