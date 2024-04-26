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

use Joomla\CMS\Factory;
use ConvertForms\Tasks\App;
use NRFramework\Email;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;
use ConvertForms\Tasks\LimitAppUsage;
class plgConvertFormsAppsEmail extends App
{
    
    // This app can only be used once in the Free version.
    use LimitAppUsage;
    

    /**
     * To be able to use this app, the Mail option in the Global Configuration page must be enabled.
     *
     * @return mixed, null when the requirement is met, array otherwise.
     */
    protected function reqMailOption()
    {
        if (!Factory::getConfig()->get('mailonline'))
        {
            return [
                'text' => $this->lang('SENDING_OFF')
            ];
        }
    }

	/**
	 * The trigger that sends the email
	 *
	 * @return void
	 */
	public function actionEmail()
	{
        // Disable cloaking of email addresses
        if (PluginHelper::isEnabled('content', 'emailcloak'))
        {
            $this->options['body'] .= '{emailcloak=off}';
        }

        // Trigger Content Plugins
        $this->options['body'] = \JHtml::_('content.prepare', $this->options['body']);

        // Since we are using a textarea, we need to convert new line characters to <br> to preserve new lines.
        // Consider this is as a temporary workaround until we replace the textarea with a Rich Text editor.
        $this->options['body'] = str_replace(["\r\n", "\r", "\n"], '<br>', $this->options['body']);

        $mailer = new Email($this->options);

        if (!$mailer->send())
        {
            $this->setError($mailer->error);
        }
	}

    /**
     * Get a list with the fields needed to setup the app's event.
     *
     * @return array
     */
	public function getActionEmailSetupFields()
	{
        return [
            [
                'name' => Text::_('COM_CONVERTFORMS_APP_SETUP_ACTION'),
                'fields' => [
                    $this->field('subject', ['value' => 'New Submission #{submission.id}']),
                    $this->field('recipient', ['value' => '{site.email}']),
                    $this->field('from_name', ['value' => '{site.name}']),
                    $this->field('from_email', ['value' => '{site.email}']),
                    $this->field('reply_to', ['required' => false]),
                    $this->field('reply_to_name', ['required' => false]),
                    $this->field('cc', ['required' => false]),
                    $this->field('bcc', ['required' => false]),
                    $this->field('body', ['type' => 'textarea', 'value' => '{all_fields}']),
                    $this->field('attachments', ['required' => false])
                ]
            ]
        ];
	}
}