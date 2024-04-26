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

JHtml::_('script', 'com_convertforms/recaptcha_v2_invisible.js', ['version' => 'auto', 'relative' => true]);
JHtml::_('script', 'https://www.google.com/recaptcha/api.js?onload=ConvertFormsInitInvisibleReCaptcha&render=explicit&hl=' . JFactory::getLanguage()->getTag());
?>
<div class="g-invisible-recaptcha" data-sitekey="<?php echo $site_key; ?>" data-badge="<?php echo $badge; ?>"></div>