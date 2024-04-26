<?php

/**
 * @author          Tassos.gr
 * @link            https://www.tassos.gr
 * @copyright       Copyright © 2023 Tassos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework\Conditions\Conditions\Component;

defined('_JEXEC') or die;

class HikashopCartContainsProducts extends HikashopBase
{
    /**
	 *  Pass check
	 *
	 *  @return  bool
	 */
	public function pass()
	{
		// Get cart products
		if (!$cartProducts = $this->getCartProducts())
		{
			return false;
		}

		// Get condition products
		if (!$conditionProducts = $this->selection)
		{
			return false;
		}

		// Ensure all condition's products exist in the cart
		$foundCartProducts = array_filter(
			$cartProducts,
			function ($prod) use ($conditionProducts)
			{
				return in_array($prod->{$this->request_id}, $conditionProducts);
			}
		);

		return count($foundCartProducts);
    }
}