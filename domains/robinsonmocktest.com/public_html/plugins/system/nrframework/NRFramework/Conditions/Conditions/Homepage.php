<?php

/**
 * @author          Tassos.gr <info@tassos.gr>
 * @link            https://www.tassos.gr
 * @copyright       Copyright © 2023 Tassos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework\Conditions\Conditions;

defined('_JEXEC') or die;

use NRFramework\Conditions\Condition;

class Homepage extends Condition
{
    /**
     * Pass the condition.
     * 
     * @return  bool
     */
	public function pass()
	{
		$menu = \JFactory::getApplication()->getMenu();
		$lang = \JFactory::getLanguage()->getTag();
		$is_front_page = ($menu->getActive() == $menu->getDefault($lang));
		
		return $this->options->get('params.operator', 'is') === 'is' ? $is_front_page : !$is_front_page;
	}
}