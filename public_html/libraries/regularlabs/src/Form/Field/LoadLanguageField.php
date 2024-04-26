<?php
/**
 * @package         Regular Labs Library
 * @version         23.10.17780
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            https://regularlabs.com
 * @copyright       Copyright © 2023 Regular Labs All Rights Reserved
 * @license         GNU General Public License version 2 or later
 */

namespace RegularLabs\Library\Form\Field;

defined('_JEXEC') or die;

use RegularLabs\Library\Form\FormField as RL_FormField;
use RegularLabs\Library\Language as RL_Language;

class LoadLanguageField extends RL_FormField
{
    function loadLanguage($extension, $admin = 1)
    {
        if ( ! $extension)
        {
            return;
        }

        RL_Language::load($extension, $admin ? JPATH_ADMINISTRATOR : JPATH_SITE);
    }

    protected function getInput()
    {
        $extension = $this->get('extension');
        $admin     = $this->get('admin', 1);

        self::loadLanguage($extension, $admin);

        return '';
    }

    protected function getLabel()
    {
        return '';
    }
}
