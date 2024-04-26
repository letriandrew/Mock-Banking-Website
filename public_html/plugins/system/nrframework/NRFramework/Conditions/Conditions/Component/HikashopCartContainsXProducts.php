<?php

/**
 * @author          Tassos.gr
 * @link            https://www.tassos.gr
 * @copyright       Copyright © 2023 Tassos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework\Conditions\Conditions\Component;

defined('_JEXEC') or die;

class HikashopCartContainsXProducts extends HikashopBase
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
		
        return $this->passByOperator(count($cartProducts), $this->selection);
    }
}