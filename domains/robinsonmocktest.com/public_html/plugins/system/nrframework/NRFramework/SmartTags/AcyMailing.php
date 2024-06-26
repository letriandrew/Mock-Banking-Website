<?php

/**
 * @author          Tassos.gr
 * @link            https://www.tassos.gr
 * @copyright       Copyright © 2023 Tassos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework\SmartTags;

defined('_JEXEC') or die('Restricted access');

class AcyMailing extends SmartTag
{
    /**
     * Returns the total numbers of subscribers of specific list.
     *
     * @return  mixed   Null if property is not found, mixed if property is found
     */
    public function getSubscribersCount()
    {
        if (!$list = $this->parsedOptions->get('list'))
        {
            return;
        }

        @include_once JPATH_ADMINISTRATOR . '/components/com_acym/helpers/helper.php';

        if (!$acym = acym_get('class.list'))
        {
            return;
        }

        return $acym->getSubscribersCountByListId($list);
    }
}