<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            https://www.tassos.gr
 * @copyright       Copyright © 2023 Tassos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die;

class JFormFieldCSSSelector extends JFormField
{
	/**
     * Render the Opening Hours
     * 
     * @return string
     */
    protected function getInput()
    {
        $elName = (string) $this->element['name'];
        $groups = explode('.', $this->group);
        $groups[] = $elName;

        $xml = array_map(function($group) {
            return '<fields name="' . $group . '">';
        }, $groups);

        $xml = implode(' ', $xml);

        $fieldsetUniqueName = $this->group . $elName;

        $xml .= '
            <fieldset name="' . $fieldsetUniqueName . '">
                <field name="selector" type="text" 
                    hiddenLabel="true"
                    filter="raw"
                    hint="NR_CSS_SELECTOR_ENTER"
                />
                <field name="task" type="list" 
                    hiddenLabel="true"
                    default="text">
                    <option value="text">NR_CSS_SELECTOR_TEXT</option>
                    <option value="html">NR_CSS_SELECTOR_HTML</option>
                    <option value="innerhtml">NR_CSS_SELECTOR_INNER_HTML</option>
                    <option value="attr">NR_CSS_SELECTOR_ATTR</option>
                    <option value="count">NR_CSS_SELECTOR_TOTAL</option>
                </field>
                <field name="attr" type="text" 
                    showon="task:attr"
                    hiddenLabel="true"
                    hint="NR_CSS_SELECTOR_ATTR_NAME"
                />
            </fieldset>
        ';

        $xml .= str_repeat('</fields>', count($groups));

        $this->form->setField(new SimpleXMLElement($xml));

        $html = $this->form->renderFieldSet($fieldsetUniqueName);

        JFactory::getDocument()->addStyleDeclaration('
            .css_selector_container {
                display:flex;
                gap:10px;
            }
            .css_selector_container .control-group {
                margin:0;
                width:270px;
            }
        ');

        return '<div class="css_selector_container">' . $html . '</div>';
    }
}