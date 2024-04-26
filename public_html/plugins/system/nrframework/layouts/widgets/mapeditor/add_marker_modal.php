<?php

$form_source = new SimpleXMLElement('
<form>
	<fieldset name="add_marker_modal">
		<field name="address" type="TFAddressLookup"
			label="NR_LOCATION_ADDRESS"
			group_class="mb-0"
			class="tf-marker-repeater-address span12 full-width w-100"
		/>
		<field name="latitude" type="hidden"
			class="tf-marker-repeater-latitude"
		/>
		<field name="longitude" type="hidden"
			class="tf-marker-repeater-longitude"
		/>
	</fieldset>
</form>
');

$form = JForm::getInstance($options['name'] . '[add_marker]', $form_source->asXML(), ['control' => $options['name'] . '[add_marker]']);

echo \JHtml::_('bootstrap.renderModal', 'tfMapEditorMarkerAddModal', [
	'title'  => JText::_('NR_ADD_MARKER'),
	'modalWidth' => '40',
	'footer' => '<button type="button" class="btn btn-primary tf-mapeditor-save-new-marker tf-modal-btn-primary" data-bs-dismiss="modal" data-dismiss="modal" aria-hidden="true">' . JText::_('NR_ADD_MARKER') . '</button>'
], $form->renderFieldset('add_marker_modal'));