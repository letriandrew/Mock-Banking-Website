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

require_once JPATH_PLUGINS . '/system/nrframework/helpers/field.php';

class JFormFieldNR_Choices extends NRFormField
{
    /**
     *  Get Input HTML
     *
     *  @return  string
     */
    protected function getInput()
    {
        $this->addMedia();

        $choiceType = $this->get("choicetype", "dropdown");

        // Settings
        $showValuesFieldName = $this->name . '[showvalues]';
        $showValuesFieldChecked = isset($this->value["showvalues"]) ? "checked" : "";

        $showCalcValuesFieldName = $this->name . '[showcalcvalues]';
        $showCalcValuesFieldChecked = isset($this->value["showcalcvalues"]) ? "checked" : "";

        // Choices
        $choices = $this->getChoices();
        $nextid  = max(array_keys($choices)) + 1;

        $html[] = '
            <div id="nr_choices_' . $this->id . '" class="nr_choices" data-min="1" data-fieldname="' . $this->name . '" data-nextid="' . $nextid . '">
        ';

        foreach ($choices as $key => $value)
        {
        	// Skip empty choices
        	if (!isset($value["label"]) || $value["label"] == '')
        	{
        		continue;
        	}

        	$choiceName  = $this->name . '[choices][' . $key . ']';
        	$checked     = (isset($value["default"]) && (bool) $value["default"] === true) ? "checked" : "";
            $choiceValue = isset($value["value"]) ? $value["value"] : "";
            $choiceCalcValue = isset($value["calc-value"]) ? $value["calc-value"] : "";
            $choiceLabel = $value["label"];

			$html[] = '
				<div class="nr-choice-item" data-id=' . $key . '>
                    <div>
	    			    <input tabindex="-1" 
                            class="nr-choice-default norender" 
                            type="'.($choiceType == "dropdown" ? "radio" : "checkbox") .'" 
                            name="' . $choiceName . '[default]" 
                            value="1" '.$checked .'>
                    </div>
                    <div class="nr-choice-sort">
	    			    <span class="cf-icon-menu"></span>
                    </div>
	    			<div class="nr-choice-input">
                        <input placeholder="' . JText::_('COM_CONVERTFORMS_ENTER_LABEL') . '" class="form-control nr-choice-label" name="' . $choiceName . '[label]" value="' . htmlspecialchars($choiceLabel) . '" type="text"/>
                        <input '.(!$showValuesFieldChecked ? "style=\"display:none;\"" : "").' placeholder="' . JText::_('COM_CONVERTFORMS_LIST_SAVED_VALUE') . '" class="form-control nr-choice-value" name="' . $choiceName . '[value]" value="'.$choiceValue.'" type="text"/>
                        <input '.(!$showCalcValuesFieldChecked ? "style=\"display:none;\"" : "").' placeholder="' . JText::_('COM_CONVERTFORMS_LIST_CALC_VALUE') . '" class="form-control nr-choice-calc-value" name="' . $choiceName . '[calc-value]" value="'.$choiceCalcValue.'" type="text"/>
                    </div>
                    <div class="nr-choice-control">
					    <a tabindex="-1" href="#" class="nr-choice-add"><span class="cf-icon-plus"></span></a>
					    <a tabindex="-1" href="#" class="nr-choice-remove"><span class="cf-icon-minus"></span></a>
                    </div>
	    		</div>
	    	';
        }

        // Add settings fields
        $html[] = '
            </div>
            <div class="nr-choice-settings">
                <span>
                    <input value"1" class="showvalues" type="checkbox" id="' . $showValuesFieldName . '" name="' . $showValuesFieldName . '" '.$showValuesFieldChecked.'>
                    <label title="' . JText::_('COM_CONVERTFORMS_FIELD_OPTIONS_SHOW_VALUES_DESC') . '" for="' . $showValuesFieldName . '">' . JText::_('COM_CONVERTFORMS_FIELD_OPTIONS_SHOW_VALUES') . '</label>
                </span>
                <span>
                    <input value"1" class="showcalcvalues" type="checkbox" id="' . $showCalcValuesFieldName . '" name="' . $showCalcValuesFieldName . '" '.$showCalcValuesFieldChecked.'>
                    <label title="' . JText::_('COM_CONVERTFORMS_FIELD_OPTIONS_CALC_VALUES_DESC') . '" for="' . $showCalcValuesFieldName . '">' . JText::_('COM_CONVERTFORMS_FIELD_OPTIONS_CALC_VALUES') . '</label>
                </span>
            </div>
        ';

        return implode(" ", $html);
    }

    /**
     *  Get Field Choices
     *
     *  @return  array  
     */
    private function getChoices()
    {
        // Setup some default choices if we don't have saved data
        if (!isset($this->value) || !isset($this->value["choices"]) || count($this->value["choices"]) == 0)
        {
            return [
                1 => ['label' => JText::_('COM_CONVERTFORMS_LIST_FIRST_CHOICE')],
                2 => ['label' => JText::_('COM_CONVERTFORMS_LIST_SECOND_CHOICE')],
                3 => ['label' => JText::_('COM_CONVERTFORMS_LIST_THIRD_CHOICE')]
            ];
        }

        return $this->value["choices"];
    }

    /**
     *  Adds CSS and JavaScript files to DOM
     */
    private function addMedia()
    {
        JHtml::script('https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js');
        JHtml::script('com_convertforms/choices.js', ['relative' => true, 'version' => 'auto']);
    }
}