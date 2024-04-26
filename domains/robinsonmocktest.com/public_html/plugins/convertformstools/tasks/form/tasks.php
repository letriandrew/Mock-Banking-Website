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

use Joomla\CMS\Language\LanguageHelper;

class JFormFieldTasks extends JFormFieldHidden
{
    /**
     * Method to get a list of options for a list input.
     *
     * @return      array           An array of JHtml options.
     */
    protected function getInput()
    {
        // Value is given as ARRAY by the Conversion model
        $this->value = $this->value ? json_encode($this->value) : '';

        $stringsCF = LanguageHelper::parseIniFile(JPATH_ADMINISTRATOR . '/components/com_convertforms/language/en-GB/en-GB.com_convertforms.ini');
        $stringsFramework = LanguageHelper::parseIniFile(JPATH_PLUGINS . '/system/nrframework/language/en-GB/en-GB.plg_system_nrframework.ini');
        $stringsJoomla = LanguageHelper::parseIniFile(JPATH_ROOT . '/language/en-GB/' . (defined('nrJ4') ? 'joomla' : 'en-GB') . '.ini');

        $strings = array_merge(array_keys($stringsCF), array_keys($stringsFramework), array_keys($stringsJoomla));

        $extraKeys = [
            'COM_CONVERTFORMS_SELECT_OPTION',
            'NR_ENTER_VALUE',
            'NR_SELECT_OPTION',
            'NR_CONTINUE',
            'NR_ARE_YOU_SURE',
            'NR_EDIT',
            'NR_AND',
            'NR_OR',
            'NR_REQUIRED',
            'NR_THIS_FIELD_IS_REQUIRED',
            'NR_SELECT_TRIGGER',
            'NR_SELECT_OPERATOR',
            'NR_RENAME',
            'NR_DUPLICATE',
            'NR_NEW',
            'JYES',
            'JNO',
            'JTRASH',
            'JOPTIONS'
        ];

        foreach ($strings as $key)
        {
            if (strpos($key, 'COM_CONVERTFORMS_TASK') === false && !in_array($key, $extraKeys))
            {
                continue;
            }

            JText::script($key);
        }

        JHtml::script('com_convertforms/tasks.js', ['relative' => true, 'version' => 'auto']);

        return '
            <div class="cf-tasks">
                <div id="cf-tasks-root"></div>
                ' . parent::getInput() . '
            </div>
        ';
    }
}