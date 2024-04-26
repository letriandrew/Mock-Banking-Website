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

// Get all countries data
$countries = \NRFramework\Countries::getCountriesData();

// Find the default country
$default_country_code = isset($value['code']) && !empty($value['code']) && isset($countries[$value['code']]) ? $value['code'] : 'AF';

// Find the default calling code
$default_calling_code = '+' . $countries[$default_country_code]['calling_code'];

$flag_base_url = implode('/', [rtrim(JURI::root(), '/'), 'media', 'plg_system_nrframework', 'img', 'flags']);
?>
<div class="tf-phone-control<?php echo $class ? ' ' . $class : ''; ?>"<?php echo $readonly ? ' readonly' : ''; ?> data-flags-base-url="<?php echo $flag_base_url; ?>">
	<div class="tf-phone-control--skeleton tf-phone-control--flag">
		<img width="27" height="13.5" src="<?php echo implode('/', [$flag_base_url, strtolower($default_country_code) . '.png']); ?>"></img>
		<svg class="tf-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="19"><path fill="currentColor" d="M480-333 240-573l51-51 189 189 189-189 51 51-240 240Z"/></svg>
		<span class="tf-flag-calling-code"><?php echo $default_calling_code; ?></span>
	</div>
	
	<select
		class="tf-phone-control--flag--selector noChosen"
		name="<?php echo $name ?>[code]"
		>
		<?php
		foreach ($countries as $key => $country)
		{
			$customProps = [
				'key' => $key,
				'label' => $country['name'],
				'calling_code' => $country['calling_code']
			];

			$selected = isset($value['code']) && $value['code'] === $key;
			?>
			<option value="<?php echo $key; ?>" data-custom-properties="<?php echo htmlspecialchars(json_encode($customProps)); ?>"<?php echo $selected ? ' selected' : ''; ?>></option>
			<?php
		}
		?>
	</select>
	
	<input
		type="tel"
		class="tf-phone-control--number<?php echo !empty($input_class) ? ' ' . $input_class : ''; ?>"
		<?php echo $required ? ' required' : ''; ?>
		<?php echo $readonly ? ' readonly' : ''; ?>
		<?php echo $browserautocomplete ? ' autocomplete="off"' : ''; ?>
		placeholder="<?php echo !empty($placeholder) ? $placeholder : '_ _ _ _ _ _'; ?>"
		value="<?php echo isset($value['value']) ? $value['value'] : ''; ?>"
		name="<?php echo $name; ?>[value]"
	/>
</div>
