<?php

/**
 * @author          Tassos.gr
 * @link            https://www.tassos.gr
 * @copyright       Copyright © 2023 Tassos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework\SmartTags;

defined('_JEXEC') or die('Restricted access');

class Year extends Date
{
    /**
     * Returns the current year
     * 
     * @return  string
     */
    public function getYear()
    {
        return $this->date->format('Y');
    }
}