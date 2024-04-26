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

defined('_JEXEC') or die;

if ($this->params->get('load_css', true))
{
	JHtml::stylesheet('com_convertforms/submissions.css', ['relative' => true, 'version' => 'auto']);
}

?>
<div class="convertforms-submissions list">
	<?php if ($this->params->get('show_page_heading')) { ?>
		<h1><?php echo $this->params->get('page_heading', $this->params->get('page_title')) ?></h1>
	<?php } ?>
	<?php echo $this->loadTemplate(count($this->submissions) ? 'list' : 'noresults'); ?>
</div>