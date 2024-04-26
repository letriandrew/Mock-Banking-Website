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

JFormHelper::loadFieldClass('text');

class JFormFieldTFPhoneControl extends JFormFieldText
{
    /**
     * Method to get a list of options for a list input.
     * @return  array  An array of JHtml options.
     */
    protected function getInput()
    {
        $this->assets();
        
        $class = $this->element['class'] ? (string) $this->element['class'] : '';
        $input_class = $this->element['input_class'] ? (string) $this->element['input_class'] : '';

        $value = $this->value;

        if (is_scalar($value))
        {
            $value = [
                'code' => '',
                'value' => $value
            ];
        }

        $payload = [
            'name' => $this->name,
            'value' => $value,
            'class' => $class,
            'input_class' => $input_class,
            'required' => $this->required,
            'readonly' => $this->readonly,
            'placeholder' => (string) $this->element['placeholder'],
            'browserautocomplete' => (string) $this->element['browserautocomplete'] !== '1'
        ];

        $layout = new JLayoutFile('phonecontrol', JPATH_PLUGINS . '/system/nrframework/layouts/controls');
        return $layout->render($payload);
    }

    /**
     * Load field assets.
     * 
     * @return  void
     */
    private function assets()
    {
        JHtml::stylesheet('plg_system_nrframework/vendor/choices.min.css', ['relative' => true, 'versioning' => 'auto']);
        JHtml::script('plg_system_nrframework/vendor/choices.min.js', ['relative' => true, 'versioning' => 'auto']);
        
        JHtml::stylesheet('plg_system_nrframework/controls/phone.css', ['relative' => true, 'versioning' => 'auto']);
        JHtml::script('plg_system_nrframework/controls/phone.js', ['relative' => true, 'versioning' => 'auto']);
    }
}