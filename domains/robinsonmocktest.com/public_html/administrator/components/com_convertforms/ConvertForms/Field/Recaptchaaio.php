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

namespace ConvertForms\Field;

defined('_JEXEC') or die('Restricted access');

use \ConvertForms\Helper;

class Recaptchaaio extends \ConvertForms\Field
{
	/**
	 *  Exclude all common fields
	 *
	 *  @var  mixed
	 */
	protected $excludeFields = array(
		'name',
		'required',
		'size',
		'value',
		'placeholder',
		'browserautocomplete',
		'inputcssclass'
	);

	/**
	 *  Set field object
	 *
	 *  @param  mixed  $field  Object or Array Field options
	 */
	public function setField($field)
	{
		parent::setField($field);

		$this->field->required = true;

		return $this;
	}

	/**
	 *  Get a reCAPTCHA Key.
	 *
	 *  @return  string
	 */
	public function getKey($key)
	{
		return Helper::getComponentParams()->get($key);
	}

	/**
	 *  Validate field value
	 *
	 *  @param   mixed  $value           The field's value to validate
	 *
	 *  @return  mixed                   True on success, throws an exception on error
	 */
	public function validate(&$value)
	{
		if (!$this->field->get('required'))
		{
			return true;
		}

		// In case this is a submission via URL, skip the check.
		if (\JFactory::getApplication()->input->get('task') == 'optin')
		{
			return true;
		}

		$version = $this->field->get('version');

		if (!$keys = $this->getKeys($version))
		{
			throw new \Exception(\JText::_('COM_CONVERTFORMS_INVALID_RECAPTCHA_KEYS'));
		}

        $recaptcha = new \NRFramework\Integrations\ReCaptcha(
            ['secret' => $keys['secret_key']]
        );

		$response = isset($this->data['g-recaptcha-response']) ? $this->data['g-recaptcha-response'] : null;

        $recaptcha->validate($response);

		// v3 extra check
		if ($version === 'v3')
		{
			$body = $recaptcha->getLastResponse()->body;

			// get score from reCAPTCHA
			$score = isset($body['score']) ? $body['score'] : false;
			if (!$score)
			{
				throw new \Exception(\JText::_('COM_CONVERTFORMS_RECAPTCHA_V3_CANNOT_VALIDATE_NO_SCORE'));
			}

			// ensure score is valid
			$validScore = $score >= $this->field->get('score');
			if (!$validScore)
			{
				throw new \Exception(\JText::_('COM_CONVERTFORMS_RECAPTCHA_V3_CANNOT_VALIDATE_INVALID_SCORE'));
			}
		}

		if (!$recaptcha->success())
		{
			throw new \Exception($recaptcha->getLastError());
		}
	}

	/**
	 * Checks whether we have valid keys.
	 * 
	 * @param   string   $version
	 * 
	 * @return  bool
	 */
	public function validKeys($version = null)
	{
		if (!$version)
		{
			$version = isset($this->field->version) ? $this->field->version : null;
		}
		
		if (!$keys = $this->getKeys($version))
		{
			return;
		}

		if (!$keys['site_key'] || !$keys['secret_key'])
		{
			return;
		}

		return true;
	}

	/**
	 * Retrieves the reCAPTCHA keys based on version.
	 * 
	 * @param   string   $version
	 * 
	 * @return  array
	 */
	public function getKeys($version = null)
	{
		if (!$version)
		{
			$version = isset($this->field->version) ? $this->field->version : null;
		}

		$site_key = $secret_key = null;
		
		switch ($version)
		{
			case 'v2_checkbox':
				$site_key = $this->getKey('recaptcha_sitekey');
				$secret_key = $this->getKey('recaptcha_secretkey');
				break;
			
			case 'v2_invisible':
				$site_key = $this->getKey('recaptcha_sitekey_invs');
				$secret_key = $this->getKey('recaptcha_secretkey_invs');
				break;
			
			case 'v3':
				$site_key = $this->getKey('recaptcha_sitekey_v3');
				$secret_key = $this->getKey('recaptcha_secretkey_v3');
				break;
		}

		return [
			'site_key' => $site_key,
			'secret_key' => $secret_key
		];
	}

	/**
	 *  Display a text before the form options
	 *
	 * 	@param   object  $form
	 *
	 *  @return  string  The text to display
	 */
	protected function getOptionsFormHeader($form)
	{
		if ($this->validKeys($form->getField('version')->value))
		{
			return;
		}

		$url = \JURI::base() . 'index.php?option=com_config&view=component&component=com_convertforms#recaptcha';

		return
			\JText::_('COM_CONVERTFORMS_FIELD_RECAPTCHA_KEYS_NOTE') . 
			' <a onclick=\'window.open("' . $url . '", "cfrecaptcha", "width=1000, height=750");\' href="#">' 
				. \JText::_("COM_CONVERTFORMS_FIELD_RECAPTCHA_CONFIGURE") . 
			'</a>.';
	}
}

?>