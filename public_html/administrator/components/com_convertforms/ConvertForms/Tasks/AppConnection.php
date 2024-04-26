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

use ConvertForms\Tasks\Connections;
use Joomla\Registry\Registry;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die('Restricted access');

trait AppConnection
{
    /**
     * Get app's information object
     *
     * @return array
     */
	public function getInfo()
	{
        $parent = parent::getInfo();
        $parent['requiresConnection'] = true;

        return $parent;
	}

    /**
     * Set the App's parameters
     *
     * @param  array $data
     * @return void
     */
    public function setParams($data)
    {
        parent::setParams($data);

        if ($this->params->get('connection_id'))
        {
            if ($connection_options = Connections::get($this->params->get('connection_id')))
            {
                $this->setConnection($connection_options['params']);
            }
        }
    }

    protected function getAjaxEndpoint($task = null)
    {
        $base = parent::getAjaxEndpoint($task);

        if ($cid = $this->params->get('connection_id'))
        {
            $base .= '&connection_id=' . $cid;
        }

        return $base;
    }

    public function setConnection($options)
    {
        $this->connection = new Registry($options);
    }

    /**
     * Get a list with the authorization fields needed to create a new App Connection.
     *
     * @return array
     */
	protected function getConnectionFormFields()
    {
        return
        [
            [
                'name'     => 'api_key',
                'label'    => Text::sprintf('COM_CONVERTFORMS_APP_API_KEY', $this->lang('ALIAS')),
                'hint'     => Text::sprintf('COM_CONVERTFORMS_APP_API_KEY_DESC', $this->lang('ALIAS')),
                'required' => true
            ]
        ];
	}

    public function getConnectionForm()
    {
        $commonFields = [
            [
                'name'     => 'title',
                'label'    => Text::sprintf('COM_CONVERTFORMS_TASKS_CONNECTION_NAME', $this->lang('ALIAS')),
                'hint'     => Text::sprintf('COM_CONVERTFORMS_TASKS_CONNECTION_NAME_DESC', $this->lang('ALIAS')),
                'required' => true
            ]
        ];

        $appFields = $this->getConnectionFormFields();

        $fields = array_merge($commonFields, $appFields);

        // If we have a connection ID bind connection data to form fields
        if ($connection_id = (int) $this->params->get('connection_id'))
        {
            $connection_data = Connections::get($connection_id);

            foreach ($fields as &$field)
            {
                if ($connection_data['params'] && array_key_exists($field['name'], $connection_data['params']))
                {
                    $field['value'] = $connection_data['params'][$field['name']];
                }

                if (array_key_exists($field['name'], $connection_data))
                {
                    $field['value'] = $connection_data[$field['name']];
                }
            }

            $fields[] = [
                'name'     => 'id',
                'type'     => 'hidden',
                'cssClass' => 'hidden',
                'value'    => $connection_id,
                'required' => true
            ];
        }

        return $fields;
    }

    /**
     * Get a list of connections assosiated with this app
     *
     * @return array
     */
    public function getConnections()
    {
        return Connections::getList($this->getName());
    }

    /**
     * Create a new connection for this app
     *
     * @param   string  $title  The title of the connection
     * @param   array   $params The connection information
     * 
     * @return  integer    The ID of tyhe newly created connection
     */
    public function addConnection($title, $params = [])
    {
        return Connections::add($this->getName(), $title, $params);
    }

    /**
     * Update connection's information
     *
     * @param   integer   $id     The ID of the connection
     * @param   string    $title  The title of the connection
     * @param   array     $data   The connections information
     * 
     * @return  bool 
     */
    public function updateConnection($id, $title, $data)
    {
        return Connections::update($id, $title, $data);
    }

    /**
     * Delete a connection
     *
     * @param  integer $id     The ID of the connection
     * 
     * @return bool
     */
    public function deleteConnection($id)
    {
        return Connections::delete($id);
    }

    /**
     * Verify the connection is valid and we can connect to the remove API
     *
     * @param  array   $connection_options     Optionally pass the connection credentials.
     * 
     * @return bool    True on success, false on failure.
     */
	abstract function testConnection($connection_options = null);

    /**
     * Return the App's class object that will help us connect to the 3rd party API. 
     * Eg: The MailChimp returns the \NRFramework\Integrations\MailChimp class)
     *
     * @return object
     */
    abstract function getApiOrDie();
}

?>