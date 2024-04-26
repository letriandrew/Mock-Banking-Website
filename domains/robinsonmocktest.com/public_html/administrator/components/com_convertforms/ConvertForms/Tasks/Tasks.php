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

use Joomla\CMS\Factory;
use ConvertForms\SmartTags;
use ConvertForms\Helper;
use ConvertForms\Form;
use ConvertForms\Tasks\ModelTasksHistory;
use Joomla\Registry\Registry;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Object\CMSObject;

defined('_JEXEC') or die('Restricted access');

class Tasks
{
    private $logs = [];

    /**
     * The list of tasks to run
     *
     * @var array
     */
    private $tasks;

    /**
     * The submitted data
     *
     * @var array
     */
    private $payload;

    /**
     * The form object settings
     *
     * @var object
     */
    private $form;

    /**
     * The submission object
     *
     * @var object
     */
    private $submission;

    /**
     * An object where we store all tasks responses
     *
     * @var object
     */
    private $responses;

    public function __construct($tasks, $submission)
    {  
        $this->tasks      = $tasks;
        $this->submission = $submission;
    }

    public function run()
    {
        $this->prepareTasks();
        $this->runTasks();
    }

    /**
     * Run all form tasks in sequence.
     *
     * @return array   A list of all Tasks responses
     */
    private function runTasks()
    {
        // The array to store each action response
        $this->responses = [];

        $smartTags = new \NRFramework\SmartTags([
            'prepareValue' => false // Always raw values
        ]);

        foreach ($this->tasks as $key => $task)
        {
            if ($prevResponse = end($this->responses))
            {
                $resultReg = new Registry($prevResponse);
                $tags = $resultReg->flatten();

                // Flatten() method removes the root properties. Let's add it back. This is useful when we want to access the previou response data in raw format.
                if (isset($prevResponse['response']))
                {
                    $tags['response'] = $prevResponse['response'];
                    $tags['request']  = $prevResponse['request'];
                }

                // Make user's life easier by making the previous tasks tags also accessible via {task.prev}
                $smartTags->removeTagsByPrefix('task.prev')->add($tags, 'task.prev.'); 
                
                $smartTags->add($tags, "task.$key.");

                $task = $smartTags->replace($task);
            }

            $resultData = [
                'request' => $task
            ];
            
            // Allow developers to manipulate each Action with a Joomla plugin
            PluginHelper::importPlugin('system');
            PluginHelper::importPlugin('convertformstools');
            PluginHelper::importPlugin('convertformsapps');

            // CMSObject makes it a lot easier to modify any property.
            $event = new CMSObject();
            $event->task = $task;
            $event->submission = $this->submission;

            Factory::getApplication()->triggerEvent('onConvertFormsTaskBeforeRun', [$event]);

            // Get the action object back from the event.
            $task = $event->task;

            if ($this->actionCanRun($task))
            {
                $actionReturnData = $this->runAction($task);

                $event->result = $actionReturnData;

                Factory::getApplication()->triggerEvent('onConvertFormsTaskAfterRun', [$event]);
            } else 
            {
                $actionReturnData = ['skipped' => true];
            }

            $this->responses[$key] = array_merge($actionReturnData, $resultData);
        }
    }

    private function actionCanRun($action)
    {
        // Is it enabled?
        if ($action['state'] == 0)
        {
            return false;
        }

        // If Conditional Logic is disabled, run the action.
        if (!$action['conditions']['enabled'])
        {
            return true;
        }

        // Otherwise, check condition rules
        return $this->passConditionSets($action['conditions']['conditions']);
    }

    private function runAction($action)
    {
        $app = Apps::getApp($action['app'], $action);

        $app->setPayload([
            'submission' => $this->submission,
            'form'       => Form::load($this->submission->form_id),
            'responses'  => $this->responses
        ]);

        // Measure action peformance
        $startTime = microtime(true); 

        $actionResult = $app->runAction($action['action']);

        // The time the action took to finish in seconds
        $elapsed = microtime(true) - $startTime;

        $errors = $app->getErrors();
        $success = empty($errors);

        // Log action to database
        ModelTasksHistory::add([
            'task_id' => $action['id'],
            'payload' => $action['options'],
            'success' => $success,
            'errors' => $errors,
            'execution_time' => number_format($elapsed, 3)
        ]);

        $returnData = [
            'success'  => $success,
            'errors'   => $errors,
            'response' => $actionResult
        ];

        if ($errors && !$action['silentfail'])
        {
            $app->die();
        }

        return $returnData;
    }

    /**
     * Passes a set of conditions which are connected with the OR comparison operator.
     *
     * @param  array $conditionsSets
     * 
     * @return bool
     */
    private function passConditionSets($conditionsSets)
    {
        $pass = null;

        // Remove empty sets
        $conditionsSets = array_filter($conditionsSets);

        // If at least 1 Set returns true, pass the check.
        foreach ($conditionsSets as $conditionsSet)
        {
            if ($pass = $this->passConditionSet($conditionsSet))
            {
                break;
            }
        }

        return $pass;
    }

    /**
     * Undocumented function
     *
     * @param [type] $conditionsSet
     * @return void
     */
    private function passConditionSet($conditionsSet)
    {
        $pass = null;
        
        // Remove empty sets
        $conditionsSet = array_filter($conditionsSet);

        foreach ($conditionsSet as $condition)
        {
            // All Conditions in a Set must return true. If any fails, the whole Set fails.
            if (!$pass = $this->passCondition($condition))
            {
                break;
            }
        }

        return $pass;
    }

    private function passCondition($condition)
    {
        $pass = false;

        $haystack = $condition['triggervalue'];
        $needle   = isset($condition['uservalue']) ? $condition['uservalue'] : '';

        switch ($condition['comparator'])
        {
            case 'empty':
            case 'not_empty':
                $pass = empty($haystack);
                break;
                
            case 'contain':
            case 'not_contain':
                $pass = mb_strpos($this->toLowerCase($haystack), $this->toLowerCase($needle)) !== false;
                break;
                
            case 'start_with':
            case 'not_start_swith':
                $pass = mb_substr($this->toLowerCase($haystack), 0, strlen($needle)) === $this->toLowerCase($needle);
                break;
                
            case 'end_with':
            case 'not_end_with':
                $pass = mb_substr($this->toLowerCase($haystack), -strlen($needle)) === $this->toLowerCase($needle);
                break;
                
            case 'regex':
            case 'not_regex':
                $regex = trim($needle);
                $regex = ltrim($regex, '/');
                $regex = rtrim($regex, '/');

                preg_match_all('/' . $regex . '/m', $haystack, $matches, PREG_SET_ORDER, 0);
                $pass = count($matches) > 0;
                break;
                
            case 'less_than':
                $pass = $haystack < $needle;
                break;
                
            case 'less_equal':
                $pass = $haystack <= $needle;
                break;
                
            case 'greater_than':
                $pass = $haystack > $needle;
                break;
                
            case 'greater_equal':
                $pass = $haystack >= $needle;
                break;
                
            case 'total_items_equal':
                $pass = count($this->toArray($haystack)) == $needle;
                break;
                
            case 'total_items_less_than':
                $pass = count($this->toArray($haystack)) < $needle;
                break;
                
            case 'total_items_less_equal':
                $pass = count($this->toArray($haystack)) <= $needle;
                break;
                
            case 'total_items_greater_than':
                $pass = count($this->toArray($haystack)) > $needle;
                break;
                
            case 'total_items_greater_equal':
                $pass = count($this->toArray($haystack)) >= $needle;
                break;

            case 'has_selected':
            case 'not_has_selected':     
                $pass = in_array($this->toLowerCase($needle), $this->toLowerCase($this->toArray($haystack)));
                break;

            case 'equal':
            case 'not_equal':
            default:
                $pass = $this->toLowerCase($haystack) == $this->toLowerCase($needle);
        }

        $pass = mb_strpos($condition['comparator'], 'not_') === false ? $pass : !$pass;

        return $pass;
    }

    private function prepareTasks()
    {
        $this->tasks = SmartTags::replace($this->tasks, $this->submission, false);
    }

    private function toLowerCase($obj)
    {
        if (is_string($obj))
        {
            return \strtolower($obj);
        }
        
        if (\is_array($obj))
        {
            return array_map('strtolower', $obj);
        }

        return $obj;
    }

    private function toArray($obj)
    {
        if (is_array($obj))
        {
            return $obj;
        }

        $obj = explode(',', $obj);
        $obj = array_map('trim', $obj);

        return $obj;
    }
}

?>