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

class PlgConvertFormsToolsCalculations extends JPlugin
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
     *  We need to load our assets regardless if the form doesn't include a field that supports calculations because
     *  user may add a field later. Thus we ensure the Calculation Builder is properly rendered.
     *
     *  @return  void
     */
    public function onConvertFormsBackendEditorDisplay()
    {
        JHtml::script('plg_convertformstools_calculations/calculation_builder.js', ['relative' => true, 'version' => 'auto']);
    }

    /**
     *  Add plugin fields to the form
     *
     *  @param   JForm   $form  
     *  @param   object  $data
     *
     *  @return  boolean
     */
    public function onConvertFormsBackendRenderOptionsForm($form, $field_type)
    {
        if (!in_array($field_type, ['text', 'number', 'hidden']))
        {
            return;
        }

        $form->loadFile(__DIR__ . '/form/form.xml');

        if ($field_type == 'number')
        {
            // A number field does not accept text in its value. Remove unsupported options.
            $form->removeField('prefix', 'calculations');
            $form->removeField('suffix', 'calculations');
            $form->removeField('thousand_separator', 'calculations');
        }
    }

    
}
