<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            https://www.tassos.gr
 * @copyright       Copyright © 2023 Tassos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

// No direct access to this file
defined('_JEXEC') or die;

require_once dirname(__DIR__) . '/helpers/field.php';

class JFormFieldNRRangeSlider extends NRFormField
{
    /**
     *  Method to render the input field
     *
     *  @return  string  
     */
    protected function getInput()
    {
        $min = (float) $this->element['min'];
        $max = (float) $this->element['max'];
        $step = (float) $this->element['step'];

        $slider = \NRFramework\Widgets\Helper::render('RangeSlider', [
            'name' => $this->name,
            'min' => $min,
            'max' => $max,
            'step' => $step,
            'value' => (float) $this->value
        ]);
        
        return $slider;
    }
}