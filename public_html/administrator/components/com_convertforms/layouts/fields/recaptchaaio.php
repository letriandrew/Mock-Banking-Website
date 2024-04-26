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

extract($displayData);

$version = $field->version;

if (!$class->validKeys())
{
	echo JText::_('COM_CONVERTFORMS_FIELD_RECAPTCHAAIO') . ' ' . JText::_('COM_CONVERTFORMS_RECAPTCHA_' . strtoupper($version)) . ': ' . JText::_('COM_CONVERTFORMS_FIELD_RECAPTCHA_KEYS_NOTE');
	return;
}

$keys = $class->getKeys();

$payload = [
	'site_key' => $keys['site_key']
];

switch ($version)
{
	case 'v2_checkbox':
		$payload = array_merge($payload, [
			'theme' => $field->theme,
			'size' => $field->size
		]);
		break;

	case 'v2_invisible':
		$payload = array_merge($payload, [
			'badge' => $field->badge
		]);
		break;

	case 'v3':
		$payload = array_merge($payload, [
			'badge' => $field->badge_v3
		]);
		break;
}

$layout = new \JLayoutFile('recaptcha/' . $version, __DIR__);
echo $layout->render($payload);