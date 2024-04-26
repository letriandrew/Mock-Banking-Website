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

$unit = isset($value['unit']) ? $value['unit'] : 'px';
?>
<div class="tf-dimension-control<?php echo $units ? ' has-units' : ''; ?>">
	<div class="tf-dimension-controls">
		<?php
		foreach ($dimensions as $key => $label)
		{
			$item_name = $name . '[' . $key . ']';
			$item_value = isset($value[$key]) ? $value[$key] : '';
			$is_linked = isset($value['linked']) ? $value['linked'] : $linked;
			$item_id = rtrim(str_replace(['][', '[', ']'], '_', $item_name), '_');
			?>
			<div class="tf-dimension-controls--item form-control<?php echo $item_value !== '' ? ' has-value' : ''; ?>">
				<input type="number" value="<?php echo $item_value; ?>" min="0" max="999" class="tf-dimension-controls--item--value" placeholder="<?php echo JText::_($label); ?>" id="<?php echo $item_id; ?>" name="<?php echo $item_name; ?>" />
				<?php
				if ($units)
				{
					$layout = new JLayoutFile('selector', JPATH_PLUGINS . '/system/nrframework/layouts/controls/unit');
					echo $layout->render([
						'name' => $name,
						'units' => $units,
						'unit' => $unit,
						'hide_input' => true
					]);
				}
				?>
			</div>
			<?php
		}
		?>
	</div>
	<?php
	$layout = new JLayoutFile('input_value', JPATH_PLUGINS . '/system/nrframework/layouts/controls/unit');
	echo $layout->render([
		'name' => $name,
		'unit' => $unit
	]);
	
	$layout = new JLayoutFile('linked', JPATH_PLUGINS . '/system/nrframework/layouts/controls/dimension');
	echo $layout->render([
		'dimension_control_locks' => $dimension_control_locks,
		'name' => $name,
		'is_linked' => $is_linked
	]);
	?>
</div>