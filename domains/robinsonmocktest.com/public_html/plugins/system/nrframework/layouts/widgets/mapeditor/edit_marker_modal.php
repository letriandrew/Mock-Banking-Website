<?php

$form_source = new SimpleXMLElement('
<form>
	<fieldset name="edit_marker_modal">
		<field name="" type="hidden" class="tf-marker-repeater-id" />
		<field name="" type="TFAddressLookup"
			label="NR_LOCATION_ADDRESS"
			group_class="mb-0"
			class="tf-marker-repeater-address span12 full-width w-100"
		/>
		<field name="" type="text"
			label="NR_COORDINATES"
			hint="NR_LATITUDE"
			class="tf-marker-repeater-latitude span12 full-width w-100"
		/>
		<field name="" type="text"
			hiddenLabel="true"
			hint="NR_LONGITUDE"
			class="tf-marker-repeater-longitude span12 full-width w-100"
		/>
		<field name="" type="spacer" label="NR_INFO_WINDOW" class="tf-bold" />
		<field name="" type="text"
			label="NR_LABEL"
			class="tf-marker-repeater-label span12 full-width w-100"
			filter="safehtml"
		/>
		<field name="" type="textarea"
			label="NR_DESCRIPTION"
			class="tf-marker-repeater-description span12 full-width w-100"
			rows="4"
			filter="safehtml"
		/>
	</fieldset>
</form>
');

$form = JForm::getInstance($options['name'], $form_source->asXML(), ['control' => $options['name']]);

echo \JHtml::_('bootstrap.renderModal', 'tfMapEditorMarkerEditModal', [
	'title'  => JText::_('NR_EDIT_MARKER'),
	'modalWidth' => '50',
	'footer' => '<button type="button" class="btn btn-primary tf-mapeditor-save-marker tf-modal-btn-primary" data-bs-dismiss="modal" data-dismiss="modal" aria-hidden="true">' . JText::_('JAPPLY') . '</button>'
], $form->renderFieldset('edit_marker_modal'));