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

use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Language\Text as JText;

class DependencyFieldHelper
{
    public static function setMessage($file, $name)
    {
        if ( ! $file)
        {
            return;
        }

        $file = JPATH_SITE . '/' . trim($file, '/');

        if (file_exists($file))
        {
            return;
        }

        $msg          = JText::sprintf('RL_THIS_EXTENSION_NEEDS_THE_MAIN_EXTENSION_TO_FUNCTION', JText::_($name));
        $messageQueue = JFactory::getApplication()->getMessageQueue();

        foreach ($messageQueue as $queue_message)
        {
            if ($queue_message['type'] == 'error' && $queue_message['message'] == $msg)
            {
                return;
            }
        }

        JFactory::getApplication()->enqueueMessage($msg, 'error');
    }
}
