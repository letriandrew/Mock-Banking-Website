<?php

/**
 * @author          Tassos.gr
 * @link            https://www.tassos.gr
 * @copyright       Copyright © 2023 Tassos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework\Conditions\Conditions\Component;

defined('_JEXEC') or die;

class HikashopCartValue extends HikashopBase
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

		if (!isset($cart->total->prices[0]->price_value_with_tax))
		{
			return 0;
		}

		$priceWithoutShipping = $cart->total->prices[0]->price_value_with_tax;

		$shipping_cost = isset($cart->shipping[0]) ? $cart->shipping[0]->shipping_price_with_tax : 0;
		$total = $exclude_shipping_cost ? $priceWithoutShipping : $priceWithoutShipping + $shipping_cost;

		return $total;
	}
}