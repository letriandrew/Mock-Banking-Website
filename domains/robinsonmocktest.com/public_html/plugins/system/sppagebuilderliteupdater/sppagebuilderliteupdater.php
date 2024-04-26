<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2022 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

use Joomla\CMS\Factory;
use Joomla\Registry\Registry;
use Joomla\CMS\Plugin\CMSPlugin;

//no direct access
defined('_JEXEC') or die('restricted access');
class  plgSystemSppagebuilderliteupdater extends CMSPlugin
{

    public function onExtensionAfterSave($option, $data)
    {

        if (($option == 'com_config.component') && ($data->element == 'com_sppagebuilder')) {
            $params = new Registry;
            $params->loadString($data->params);

            $url   = $params->get('updater', '');

            if (!empty($url)) {
                $db    = Factory::getDbo();
                $query = $db->getQuery(true)
                            ->update($db->quoteName('#__update_sites'))
                            ->set($db->quoteName('location') . '=' . $db->quote($url))
                            ->where($db->quoteName('name') . '=' . $db->quote('SP Page Builder'));
                $db->setQuery($query);
                $db->execute(); 
            }
        }
    }
}
