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

$imageURL = null;

if ($version === 'v2_checkbox')
{
	$size = $field->size === 'normal' ? '' : '_' . $field->size;

	$imageURL = JURI::root() . 'media/com_convertforms/img/recaptcha_' . $field->theme . $size . '.png';
}
else
{
	$imageURL = JURI::root() . 'media/com_convertforms/img/recaptcha_invisible.png';
}


if ($version === 'v2_checkbox')
{
	?><img src="<?php echo $imageURL ?>" style="align-self: flex-start;" /><?php
}
else if ($version === 'v2_invisible')
{
	if ($field->badge !== 'inline')
	{
		?>
		<div class="badge_<?php echo $field->badge ?>"></div>
		<style>
			.badge_bottomleft, .badge_bottomright {
				position: absolute;
				bottom: 30px;
				left: 0;
				width: 70px;
				height: 60px;
				overflow: hidden;
				background-image:url("<?php echo $imageURL ?>");
				border:solid 1px #ccc;
			}
			.badge_bottomright {
				left:auto;
				right:0;
			}
		</style>
		<?php
	}
	else
	{
		?><img src="<?php echo $imageURL ?>" style="align-self: flex-start;" /><?php
	}
}
else if ($version === 'v3')
{
	if ($field->badge_v3 !== 'inline')
	{
		?>
		<div class="badge_<?php echo $field->badge_v3 ?>"></div>
		<style>
			.badge_bottomleft, .badge_bottomright {
				position: absolute;
				bottom: 30px;
				left: 0;
				width: 70px;
				height: 60px;
				overflow: hidden;
				background-image:url("<?php echo $imageURL ?>");
				border:solid 1px #ccc;
			}
			.badge_bottomright {
				left:auto;
				right:0;
			}
		</style>
		<?php
	}
	else
	{
		?>
		<style>
			.cf-recaptcha-v3-text-badge {
				margin: 0;
				font-size: 11px;
				color: #999;
			}
			.cf-recaptcha-v3-text-badge a {
				color: #999;
				text-decoration: none;
			}
			.cf-recaptcha-v3-text-badge a:hover {
				text-decoration: underline;
			}
		</style>
		<div class="cf-recaptcha-v3-text-badge"><?php echo JText::_('COM_CONVERTFORMS_RECAPTCHA_V3_TEXT_BADGE'); ?></div>
		<?php
	}
}