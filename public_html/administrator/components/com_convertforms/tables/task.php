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

class ConvertFormsTableTask extends JTable
{
    /**
     * Constructor
     *
     * @param object Database connector object
     */
    public function __construct(&$db) 
    {
        parent::__construct('#__convertforms_tasks', 'id', $db);
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

        // In React App, we assign a unique ID for each new item. In this case, consider the record as New.
        if ($this->id && !is_numeric($this->id))
        {
            $this->id = null;
        }

        // Move conditions to its own column
        if (isset($this->options['conditions']))
        {
            $this->conditions = json_encode($this->options['conditions']);
            unset($this->options['conditions']);
        }

        $this->options = json_encode($this->options);

        // Convert true/false to int
        $this->state = $this->state ? 1 : 0;
        $this->silentfail = $this->silentfail ? 1 : 0;

        if ($this->id)
        {
            $this->modified = $date->toSql();
        } else 
        {
            $this->created = $date->toSql();
            $this->created_by = JFactory::getUser()->id;
        }

        return true;
    }
}