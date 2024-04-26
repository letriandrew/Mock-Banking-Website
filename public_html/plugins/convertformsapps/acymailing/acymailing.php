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

use ConvertForms\Tasks\App;
use Joomla\CMS\Language\Text;
use ConvertForms\Tasks\Helper;
use AcyMailing\Classes\FieldClass;
use ConvertForms\Tasks\LimitAppUsage;
use NRFramework\Extension;
class plgConvertFormsAppsAcyMailing extends App
{   
    
    // This app can only be used once in the Free version.
    use LimitAppUsage;
    

    /**
     * To be able to use this app, AcyMailing 6 or higher must be installed.
     *
     * @return mixed, null when the requirement is met, array otherwise.
     */
	public function reqAcym()
	{
        if (!Extension::isInstalled('acym'))
        {
            return [
                'text' => \JText::sprintf('COM_CONVERTFORMS_TASKS_EXTENSION_IS_MISSING', $this->lang('ALIAS'))
            ];
        }
	}

	/**
	 * The Subscribe trigger
	 *
	 * @return void
	 */
	public function actionSubscribe()
	{
        // Calculate merge tags
        $keysToRemove = [
            'lists',
            'email',
            'double_optin',
        ];

        $merge_tags = array_diff_key($this->options, array_flip($keysToRemove));

        return \ConvertForms\Helpers\AcyMailing::subscribe($this->options['email'], $merge_tags, $this->options['list'], $this->options['double_optin']);
	}

    /**
     * Get a list with the fields needed to setup the app's event.
     *
     * @return array
     */
	public function getActionSubscribeSetupFields()
	{
        $mergeTags = [
            $this->commonField('email')
        ];
        
        if ($customFields = $this->getCustomFields())
        {
            foreach ($customFields as $customField)
            {
                $mergeTags[] = $this->field($customField['tag'], [
                    'label' => $customField['label'],
                    'hint' => Text::sprintf('COM_CONVERTFORMS_TASKS_CUSTOM_FIELD', $customField['label'], $this->lang('ALIAS')),
                    'required' => $customField['required'] === 1
                ]);
            }
        }

        $fields = [
            [
                'name' => Text::_('COM_CONVERTFORMS_APP_SETUP_ACTION'),
                'fields' => [
                    $this->field('list', [
                        'loadOptions' => $this->getAjaxEndpoint('getLists'),
                        'includeSmartTags' => 'Fields'
                    ]),
                    $this->commonField('double_optin'),
                ]
            ],
            [
                'name' => Text::_('COM_CONVERTFORMS_APP_MATCH_FIELDS'),
                'fields' => $mergeTags
            ]
        ];

        return $fields;
	}

    /**
     * Returns all custom fields.
     * 
     * @return  array
     */
    public function getCustomFields()
    {
        $fields = [];

        $sql = 'SELECT id, name, required FROM #__acym_field WHERE active = 1 AND id NOT IN (2) ORDER BY ordering';

        foreach (acym_loadObjectList($sql) as $field)
        {
            // Name & Language built-in custom fields require a translation in order to detect the proper custom field name
            $tag = strpos($field->name, 'ACYM_') !== false ? acym_translation($field->name) : $field->name;
            
            $fields[] = [
                'tag' => $tag,
                'label' => acym_translation($field->name),
                'required' => $field->required
            ];
        }

        return $fields;
    }

    /**
     * Returns all lists.
     * 
     * @return  array
     */
    public function getLists()
    {
        @include_once(JPATH_ADMINISTRATOR . '/components/com_acym/helpers/helper.php');

        $lists = acym_get('class.list')->getAll();

        if (!is_array($lists))
        {
            return;
        }

        $lists_ = [];

        foreach ($lists as $list)
        {
            if (!$list->active)
            {
                continue;
            }

            $lists_[] = [
                'value' => $list->id,
                'label' => isset($list->display_name) && !empty($list->display_name) ? $list->display_name : $list->name,
                'desc'  => $list->description
            ];
        }

        return $lists_;
    }
}