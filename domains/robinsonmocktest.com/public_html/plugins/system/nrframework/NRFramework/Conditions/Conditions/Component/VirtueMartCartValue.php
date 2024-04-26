<?php

/**
 * @author          Tassos.gr
 * @link            https://www.tassos.gr
 * @copyright       Copyright © 2023 Tassos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework\Conditions\Conditions\Component;

defined('_JEXEC') or die;

class VirtueMartCartValue extends VirtueMartBase
{
    /**
	 *  Pass check
	 *
	 *  @return  bool
	 */
	public function pass()
	{
		$exclude_shipping_cost = $this->params->get('exclude_shipping_cost', '0') === '1';
		
        return $this->passByOperator($this->getCartTotal($exclude_shipping_cost), $this->selection);
    }

    /**
	 * Returns the cart total billable cost
	 * 
	 * @param   bool   $exclude_shipping_cost
	 * 
	 * @return  int
	 */
	protected function getCartTotal($exclude_shipping_cost = false)
	{
		if (!$cart = $this->getCart())
		{
			return 0;
		}

		if (!isset($cart->cartPrices['billTotal']))
		{
			return 0;
		}

		@include_once JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/currencydisplay.php';

		if (!class_exists('CurrencyDisplay'))
		{
			return 0;
		}

		$total = $exclude_shipping_cost ? $cart->cartPrices['salesPrice'] : $cart->cartPrices['billTotal'];

		$currency = \CurrencyDisplay::getInstance();

		// billTotal is stored as a float like 70.00120120312
		// We use the `roundByPriceConfig` method to round it based on Virtuemart configuration,
		// in order to get the same total as seen in the cart and compare with this value instead of the raw one.
		return $currency->roundByPriceConfig($total);
	}
}