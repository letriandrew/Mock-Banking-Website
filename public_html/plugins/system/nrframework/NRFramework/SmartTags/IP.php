<?php

/**
 * @author          Tassos.gr
 * @link            https://www.tassos.gr
 * @copyright       Copyright © 2023 Tassos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework\SmartTags;

use NRFramework\User;

defined('_JEXEC') or die('Restricted access');

class IP extends SmartTag
{
    /**
     * Returns the IP address
     * 
     * @return  string
     */
    public function getIP()
    {
        return User::getIP();
    }
}