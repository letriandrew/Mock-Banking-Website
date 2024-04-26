<?php
// No direct access
defined('_JEXEC') or die;

// Include the Joomla Plugin class
jimport('joomla.plugin.plugin');

class plgSystemMyTypingPlugin extends JPlugin
{
    public function onBeforeRender()
    {
        // Get the current user's typing activity
        $username = JFactory::getApplication()->input->get('username', '', 'STRING');

        // Check if it's the first typing activity
        //if ($username && !$this->isTypingActivityLogged($username)) {
        if ($username) {
            // Log the activity to the database
            $this->logTypingActivity($username);
        }
    }

    // Check if typing activity is already logged
    private function isTypingActivityLogged($username)
    {
        // Implement your logic to check if the username's activity is already logged in the database
        // Return true if logged, false if not
    }

    // Log typing activity to the database
    private function logTypingActivity($username)
    {
        // Implement your code to log the activity in the database
        // You can use Joomla's database API for this
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        // Insert data into your database table
        $query
            ->insert($db->quoteName('your_table_name'))
            ->columns($db->quoteName('username'))
            ->values($db->quote($username));

        $db->setQuery($query);
        $db->execute();
    }
}
