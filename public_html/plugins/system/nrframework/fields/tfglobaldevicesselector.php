<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2021 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

// No direct access to this file
defined('_JEXEC') or die;

JFormHelper::loadFieldClass('text');

class JFormFieldTFGlobalDevicesSelector extends JFormFieldText
{
    /**
     *  Method to render the input field
     *
     *  @return  string  
     */
    public function getInput()
    {
        $this->assets();
        
        $payload = [
            'devices' => \NRFramework\Helpers\Responsive::getBreakpoints()
        ];
        
        $layout = new JLayoutFile('global_devices_selector', JPATH_PLUGINS . '/system/nrframework/layouts');
        return $layout->render($payload);
    }

    /**
     * Load field assets.
     * 
     * @return  void
     */
    private function assets()
    {
        JHtml::stylesheet('plg_system_nrframework/global_devices_selector.css', ['relative' => true, 'version' => 'auto']);
        JHtml::script('plg_system_nrframework/global_devices_selector.js', ['relative' => true, 'version' => 'auto']);
    }
}