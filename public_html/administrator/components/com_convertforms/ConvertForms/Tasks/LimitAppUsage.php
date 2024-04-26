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

namespace ConvertForms\Tasks;

defined('_JEXEC') or die('Restricted access');

use \NRFramework\Extension;

trait LimitAppUsage
{
    /**
     * Limit app's usage to 1 task per form
     *
     * @return mixed, null when the requirement is met, array otherwise.
     */
    protected function reqAppUsage()
    {   
        // Sanity check to make sure we are on the Free version. This check also allow us to lift the limit in the development server.
        if (Extension::isPro('com_convertforms'))
        {
            return;
        }

        if ($this->limitReached())
        {
            return [
                'type' => 'info',
                'text' => \JText::sprintf('COM_CONVERTFORMS_TASKS_APP_LIMIT_REACH', $this->lang('ALIAS'))
            ];
        }
    }

    /**
     * The usage limit is reached when the user has created at least 1 task.
     *
     * @return bool  True when the limit is reached
     */
    private function limitReached()
    {
        if ($this->formTasks)
        {
            foreach ($this->formTasks as $task)
            {
                if ($task['app'] == $this->getName())
                {
                    return true;
                }
            }
        }
    }
}

?>