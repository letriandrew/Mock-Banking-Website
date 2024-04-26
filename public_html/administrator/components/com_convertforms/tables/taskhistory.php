<?php

/**
 * @package         Convert Forms
 * @version         4.2.1 Free
 * 
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            https://www.tassos.gr
 * @copyright       Copyright © 2023Tassos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die('Restricted access');

class ConvertFormsTableTaskHistory extends JTable
{
    /**
     * Constructor
     *
     * @param object Database connector object
     */
    public function __construct(&$db) 
    {
        parent::__construct('#__convertforms_tasks_history', 'id', $db);
    }

    /**
     *  Method to perform sanity checks on the JTable instance properties to ensure
     *  they are safe to store in the database.  Child classes should override this
     *  method to make sure the data they are storing in the database is safe and
     *  as expected before storage.
     * 
     *  @return  boolean  True if the instance is sane and able to be stored in the database.
     */
    public function check()
    {
        $date = JFactory::getDate();

        $this->success = $this->success ? 1 : 0;

        if (!$this->id)
        {
            $this->created = $date->toSql();
            $this->created_by = JFactory::getUser()->id;

            if ($this->payload && is_array($this->payload))
            {
                $this->payload = json_encode($this->payload);
            }

            if ($this->errors && is_array($this->errors))
            {
                $this->errors = json_encode($this->errors);
            }
        }

        return true;
    }
}