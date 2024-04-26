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
?>
<div class="tf-global-devices-selector">
	<div class="tf-global-devices-selector--items">
		<?php
		foreach ($devices as $breakpoint => $device)
		{
			?>
			<div
				class="tf-global-devices-selector--items--item<?php echo $breakpoint === 'desktop' ? ' is-active' : ''; ?>"
				data-breakpoint="<?php echo $breakpoint; ?>"
				title="<?php echo sprintf(JText::_('NR_SETUP_X_DEVICE_SETTINGS'), strtolower($device['label']), $device['desc']); ?>"
			>
				<?php echo $device['icon']; ?>
				<span class="tf-global-devices-selector--items--item--label"><?php echo $device['label']; ?></span>
			</div>
			<?php
		}
		?>
	</div>
</div>