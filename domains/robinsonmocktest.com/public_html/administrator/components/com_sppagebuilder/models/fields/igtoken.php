<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Form\FormField;


class JFormFieldIgToken extends FormField
{
	protected $type = 'IgToken';

    protected function getInput()
    {
		$doc = Factory::getDocument();
		$doc->addScript(Uri::root() . 'administrator/components/com_sppagebuilder/assets/js/igtoken.js');

		$igConfig = [
			'base' => Uri::root() . 'administrator',
			'inputId' => $this->id
		];
		$doc->addScriptOptions('igConfig', $igConfig);

		if (empty($this->value))
		{
			$value = new stdClass;
			$value->appId = '';
			$value->appSecret = '';
			$value->accessToken = '';
			$value->igId = '';
		}
		else
		{
			$value = json_decode($this->value);
		}

		$appId = !empty($value->appId) ? $value->appId : '';
		$appSecret = !empty($value->appSecret) ? $value->appSecret : '';
		$allowAccessTokenAndIgId = !empty($appId) && !empty($appSecret);

		$accessToken = !empty($value->accessToken) && $allowAccessTokenAndIgId ? $value->accessToken : '';
		$igId = !empty($value->igId) && $allowAccessTokenAndIgId ? $value->igId : '';

		$isVisibleTokenIgId = $allowAccessTokenAndIgId && (!empty($accessToken) && !empty($igId));
		$value->accessToken = $accessToken;
		$value->igId = $igId;

		$this->value = json_encode($value);

		$html = [];

		$html[] = '<div class="igt-wrapper sppb-ig-token">';

		$html[] = 	'<div class="control-group">';
		$html[] = 		'<label class="control-label">' . Text::_('COM_SPPAGEBUILDER_FB_APP_ID') . '</label>';
		$html[] = 		'<div class="controls">';
		$html[] = 			'<input id="app_id" type="text" class="form-control" value="' . $appId . '" />';
		$html[] = 		'</div>';
		$html[] = 	'</div>';

		$html[] = 	'<div class="control-group">';
		$html[] = 		'<label class="control-label">' . Text::_('COM_SPPAGEBUILDER_FB_APP_SECRET') . '</label>';
		$html[] = 		'<div class="controls">';
		$html[] = 			'<input id="app_secret" type="text" class="form-control" value="' . $appSecret . '" />';
		$html[] = 		'</div>';
		$html[] = 	'</div>';



		$html[] = 	'<div class="control-group ' . (!$isVisibleTokenIgId ? 'hidden': '') . '">';
		$html[] = 		'<label class="control-label">' . Text::_('COM_SPPAGEBUILDER_FB_ACCESS_TOKEN') . '</label>';
		$html[] = 		'<div class="controls">';
		$html[] = 			'<input id="access_token" type="text" class="form-control" readonly="readonly" value="' . $accessToken . '" />';
		$html[] = 		'</div>';
		$html[] = 	'</div>';

		$html[] = 	'<div class="control-group ' . (!$isVisibleTokenIgId ? 'hidden': '') . '">';
		$html[] = 		'<label class="control-label">' . Text::_('COM_SPPAGEBUILDER_FB_IG_ID') . '</label>';
		$html[] = 		'<div class="controls">';
		$html[] = 			'<input id="ig_id" type="text" class="form-control" readonly="readonly" value="' . $igId . '" />';
		$html[] = 		'</div>';
		$html[] = 	'</div>';

		$task = JVERSION < 4 ? 'config.save.component.apply' : 'component.apply';

		$html[] = 	'<div class="control-group ' . (!empty($appId) && !empty($appSecret) ? 'hidden': '') . '">';
		$html[] = 		'<div class="controls">';
		$html[] = 			'<button type="button" onclick="Joomla.submitbutton(\'' . $task . '\');" id="ig_next" class="btn btn-primary">' . Text::_('COM_SPPAGEBUILDER_BTN_NEXT') . '</button>';
		$html[] = 		'</div>';
		$html[] = 	'</div>';

		$generateBtnText = Text::_('COM_SPPAGEBUILDER_GENERATE_ACCESS_TOKEN');

		if (!empty($accessToken) && !empty($igId))
		{
			$generateBtnText = Text::_('COM_SPPAGEBUILDER_REGENERATE_ACCESS_TOKEN');
		}

		$html[] = 	'<div class="control-group ' . (empty($appId) || empty($appSecret) ? 'hidden' : '') . '">';
		$html[] = 		'<div class="controls">';
		$html[] = 			'<button type="button" id="app_token" class="btn btn-success">' . $generateBtnText . '</button>';
		$html[] = 		'</div>';
		$html[] = 	'</div>';

		$html[] = '<input type="hidden" id="' . $this->id . '" name="' . $this->name . '" value=\'' . (!empty($this->value) ? $this->value : '') . '\' />';

		$html[] = '</div>';

		return implode("\n", $html);
    }
}
