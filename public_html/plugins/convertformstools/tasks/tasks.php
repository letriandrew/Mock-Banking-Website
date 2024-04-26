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

use ConvertForms\Tasks\ModelTasks;

class PlgConvertFormsToolsTasks extends JPlugin
{
    /**
     *  Application Object
     *
     *  @var  object
     */
    protected $app;

    /**
     *  Every time the form is saved in the backend, move actions information from #__convertforms to the #__convertforms_tasks table.
     *  
     *  @param   string  $context  The context of the content passed to the plugin (added in 1.6)
     *  @param   object  $article  A JTableContent object
     *  @param   bool    $isNew    If the content has just been created
     *
     *  @return  void
     */
    public function onContentAfterSave($context, $article, $isNew, $data = [])
    {
        if ($context !== 'com_convertforms.form' || !$this->app->isClient('administrator'))
        {
            return;
        }

        $params = json_decode($article->params, true);

        if (!isset($params['tasks']))
        {
            return;
        }

        $form_id = $article->id;
        $actions = json_decode($params['tasks'], true);

        // Remove deleted tasks from the database
        $existingActions = ModelTasks::getItems($form_id, true);

        if (!empty($existingActions))
        {
            if ($actionsToDelete = array_diff(array_keys($existingActions), array_column($actions, 'id')))
            {
                $table = ModelTasks::getTable();

                foreach ($actionsToDelete as $id)
                {
                    $table->delete($id);
                }
            }
        }

        if ($actions)
        {
            foreach ($actions as $ordering => $action)
            {
                $action['ordering'] = $ordering;
                $action['form_id'] = $form_id;
    
                ModelTasks::save($action);
            }
        }

        // Remove tasks information from the form row
        unset($params['tasks']);
        $article->params = json_encode($params);
        $article->store();
    }

    /**
     *  Add plugin fields to the form
     *
     *  @param   JForm   $form  
     *  @param   object  $data
     *
     *  @return  boolean
     */
    public function onConvertFormsFormPrepareForm($form, $data)
    {
        // Load form's tasks
        $data->tasks = $data->id ? ModelTasks::getItems($data->id, false, true) : [];

        // Load form fields
        $form->loadFile(__DIR__ . '/form/form.xml');
    }

    /**
     * Run actions when a new submissions comes in
     *
     * @param  object $submission
     * 
     * @return void
     */
    public function onConvertFormsSubmissionAfterSave($submission)
    {
        $form_tasks = ModelTasks::getItems($submission->form_id);

        $actions = new \ConvertForms\Tasks\Tasks($form_tasks, $submission);
        $actions->run();
    }
}