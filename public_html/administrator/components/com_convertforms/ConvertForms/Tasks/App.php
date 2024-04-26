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

use Joomla\Registry\Registry;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\CMSPlugin;

defined('_JEXEC') or die('Restricted access');

class App extends CMSPlugin
{
    /**
     *  Application Object
     *
     *  @var  object
     */
    protected $app;

    /**
     *  Auto loads the plugin language file
     *
     *  @var  boolean
     */
    protected $autoloadLanguage = true;

	/**
	 * An array of error messages or Exception objects.
	 */
	protected $errors = [];

    /**
     * Represents the form tasks created by the user
     *
     * @var array
     */
    protected $formTasks;

    /**
     * The app's parameters
     *
     * @var object
     */
    protected $options;

    /**
     * The options required by the Task to run
     *
     * @var object
     */
    protected $payload;

    /**
     * A list of events this app can listen to in order to run triggers.
     *
     * @var array
     */
    protected $supportedTriggers = [
        'onNewSubmission',     // When a new submission is stored.
        //'onSubmissionEdit',  // When a submission is modified.
        //'onSubmissionDelete' // When a submission is delete.
    ];

    /**
     * Set the App's parameters
     *
     * @param  array $data
     * @return void
     */
    public function setParams($data)
    {
        $this->params = new Registry($data);

        if ($this->params->get('options'))
        {
            $this->options = $this->params->toArray()['options'];
        }
    }

    public function setPayload($payload)
    {
        $this->payload = $payload;
    }

    public function setError($error)
    {
        $this->errors[] = $error;
    }

	/**
	 * Return all errors, if any.
	 *
	 * @return  array  Array of error messages.
	 */
	public function getErrors()
	{
		return $this->errors;
	}

    /**
     * Fired when we need to get a list of all available apps
     *
     * @return array
     */
    public function onConvertFormsAppInfo($tasks)
    {
        $this->formTasks = $tasks;

        return $this->getInfo();
    }

    /**
     * Get app's information object
     *
     * @return array
     */
	public function getInfo()
	{
        return [
            'label'    => $this->lang('ALIAS'),
            'desc'     => $this->lang('DESC'),
            'value'    => $this->getName(),
            'logo'     => $this->getLogo(),
            'triggers' => $this->getTriggers(),
            'actions'  => $this->getActions(),
            'error'    => $this->checkUsageRequirements()
        ];
	}

    /**
     * An App may have some requirements to meet to be able to use it in a task. A requirement can be a Joomla Extension, a specific global option or a custom check.
     *
     * To add a requirement, declare a method with the prefix 'req'. Eg: reqAwesomeCheck(). When the requirement is met return null. Otherwise return the following array:
     * 
     * [
     *    'text' => 'here goes the error message',
     *    'type' => info|proonly|error
     * ]
     * 
     * These checks prevent the user from selecting the App in the App selection step of the Task Builder. A tooltip will display the returned error. 
     * 
     * @return mixed null when there's no requirements, array when the requirement is not met.
     */
    public function checkUsageRequirements()
    {
        $methods = get_class_methods($this);

        $deps = [];

        foreach ($methods as $method)
        {
            if (substr($method, 0, 3) == 'req')
            {
                $result = $this->$method();

                if (!is_null($result))
                {
                    return $result;
                }
            }
        }
    }

    public function getTriggers()
    {
        $result = [];

        foreach ($this->supportedTriggers as $trigger)
        {
            $result[] = [
                'value' => $trigger,
                'label' => Text::_('COM_CONVERTFORMS_TASKS_TRIGGER_' . $trigger),
                'desc'  => Text::_('COM_CONVERTFORMS_TASKS_TRIGGER_' . $trigger . '_DESC')
            ];
        }

        return $result;
    }

    /**
     * Get app's supported actions
     *
     * @return array
     */
	public function getActions()
	{
        $methods = get_class_methods($this);

        $actions = [];

        foreach ($methods as $method)
        {
            if (substr($method, 0, 6) == 'action')
            {
                $action_name = str_replace('action', '', $method);

                $actions[] = [
                    'value' => strtolower($action_name),
                    'label' => $this->lang('ACTION_' . $action_name),
                    'desc'  => $this->lang('ACTION_' . $action_name . '_DESC')
                ];
            }
        }

        return $actions;
	}

    /**
     *  Get plugin name alias
     *
     *  @return  string
     */
    public function getName()
    {
        return isset($this->name) ? $this->name : $this->_name;
    }

    /**
     * Get the URL of the app's logo
     *
     * @return string
     */
    public function getLogo()
    {
        return 'https://www.tassos.gr/images/apps/' . $this->getName() . '.png';
    }

    /**
     * The an app's translated string
     *
     * @param  string  $text  The text to translate through the app's language file
     * 
     * @return string  The translated string
     */
    public function lang($text)
    {
        return Text::_(strtoupper('PLG_CONVERTFORMSAPPS_' . $this->getName() . '_' . $text));
    }

    protected function getAjaxEndpoint($task = null)
    {
        $base = \JURI::root() . 'administrator/?option=com_convertforms&task=tasks.app&app=' . $this->getName();

        if ($task)
        {
            $base .= '&subtask=' . $task;
        }

        return $base;
    }

    public function runAction($action)
    {
        $actionMethod = 'action' . ucfirst($action);

        if (!method_exists($this, $actionMethod))
        {
            throw new \Exception('Action method not found' . $actionMethod);
        }

        return $this->$actionMethod();
    }

    /**
     * Stop execution by throwing an exception
     *
     * @param  string $error    The error message
     * 
     * @return void
     */
    public function die($error = null)
    {
        $error = is_null($error) ? $this->errors[0] : $error;
        $message = $this->lang('ALIAS') . ' - ' . $error;
        throw new \Exception($message);
    }

    /**
     * Helper method to return the most common properties of a field form
     *
     * @param   string  $name      The name of the field
     * @param   array   $options   Optional extra field properties to include
     * 
     * @return array
     */
    protected function field($name, $options = null)
    {
        $field = [
            'name' => $name,
            'label' => $this->lang($name),
            'hint' => $this->lang($name . '_DESC'),
            'required' => true,
            'includeSmartTags' => true,
            'creatable' => true,
        ];

        if ($options)
        {
            $field = array_merge($field, $options);
        }

        if ($field['creatable'] && !isset($field['clearable']))
        {
            $field['clearable'] = true;
        }

        return $field;
    }

    /**
     * Let's don't repeat ourselves. Helper method to return the most common fields.
     *
     * @param  string $commonField  The name of the common field
     * 
     * @return object
     */
    protected function commonField($commonField) 
    {
        switch ($commonField) {
            case 'email':
                return $this->field('email', [
                    'label' => Text::_('COM_CONVERTFORMS_APP_SUBSCRIBER_EMAIL'),
                    'hint'  => Text::_('COM_CONVERTFORMS_APP_SUBSCRIBER_EMAIL_DESC'),
                ]);
               
            case 'update_existing_subscriber':
                return $this->field('update_existing_subscriber', [
                    'type'  => 'bool',
                    'value' => '1',
                    'label' => Text::_('COM_CONVERTFORMS_APP_UPDATE_EXISTING_MEMBER'),
                    'hint'  => Text::_('COM_CONVERTFORMS_APP_UPDATE_EXISTING_MEMBER_DESC'),
                    'includeSmartTags' => 'Fields'
                ]);

            case 'double_optin':
                return $this->field('double_optin', [
                    'type'  => 'bool',
                    'value' => '1',
                    'label' => Text::_('COM_CONVERTFORMS_APP_DOUBLE_OPTIN'),
                    'hint'  => Text::sprintf('COM_CONVERTFORMS_APP_DOUBLE_OPTIN_DESC', $this->lang('ALIAS')),
                    'includeSmartTags' => 'Fields'
                ]);
                
            case 'state':
                return $this->field('state', [
                    'label' => Text::_('JSTATUS'),
                    'value' => '1',
                    'options' => [
                        [
                            'value' => '1',
                            'label' => Text::_('JPUBLISHED')
                        ],
                        [
                            'value' => '0',
                            'label' => Text::_('JUNPUBLISHED')
                        ],
                        [
                            'value' => '-2',
                            'label' => Text::_('JTRASHED')
                        ],
                        [
                            'value' => '2',
                            'label' => Text::_('JARCHIVED')
                        ]
                        ],
                        'includeSmartTags' => 'Fields'
                ]);
        }
    }
}

?>