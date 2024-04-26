<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            https://www.tassos.gr
 * @copyright       Copyright © 2023 Tassos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

// No direct access to this file
defined('_JEXEC') or die;

class JFormFieldTFAddressLookup extends JFormField
{
	public function getInput()
	{
		$group_class = isset($this->element['group_class']) ? (string) $this->element['group_class'] : 'stack mb-0';
		$label = isset($this->element['label']) ? (string) $this->element['label'] : 'NR_ADDRESS';

		\JText::script('NR_UNTITLED_MARKER');

		$payload = [
			'id' => $this->id,
			'label' => $label,
			'name' => $this->name,
			'value' => $this->value,
			'visible' => true,
			'autocomplete' => true,
			'group_class' => $group_class
		];

        $layout = new JLayoutFile('addresslookup', JPATH_PLUGINS . '/system/nrframework/layouts');
        return $layout->render($payload);
	}
}