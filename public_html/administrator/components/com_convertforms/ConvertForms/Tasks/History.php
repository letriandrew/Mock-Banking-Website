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

class History
{
    public static function log($app, $app_trigger, $event, $success, $error, $ref_type, $ref_id)
    {
        $db = \JFactory::getDbo();

        $query = $db->getQuery(true)
            ->insert($db->quoteName('#__convertforms_tasks_history'))
            ->columns($db->quoteName(['app', 'app_trigger', 'event', 'success', 'error', 'ref_type', 'ref_id']))
            ->values(implode(',', [$app, $app_trigger, $event, $success, $error, $ref_type, $ref_id]));

        $db->setQuery($query);
        $db->execute();
    }
}

?>