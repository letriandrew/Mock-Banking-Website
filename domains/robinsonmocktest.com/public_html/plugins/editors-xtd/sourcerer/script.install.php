<?php
/**
 * @package         Sourcerer
 * @version         10.0.1
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            https://regularlabs.com
 * @copyright       Copyright © 2023 Regular Labs All Rights Reserved
 * @license         GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Filesystem\File as JFile;
use Joomla\CMS\Filesystem\Folder as JFolder;

class PlgEditorsXtdSourcererInstallerScript
{
    public function postflight($install_type, $adapter)
    {
        if ( ! in_array($install_type, ['install', 'update']))
        {
            return true;
        }

        self::deleteJoomla3Files();

        return true;
    }

    private static function delete($files = [])
    {
        foreach ($files as $file)
        {
            if (is_dir($file))
            {
                JFolder::delete($file);
            }

            if (is_file($file))
            {
                JFile::delete($file);
            }
        }
    }

    private static function deleteJoomla3Files()
    {
        self::delete(
            [
                JPATH_SITE . '/plugins/editors-xtd/sourcerer/layouts',
                JPATH_SITE . '/plugins/editors-xtd/sourcerer/fields.xml',
                JPATH_SITE . '/plugins/editors-xtd/sourcerer/helper.php',
                JPATH_SITE . '/plugins/editors-xtd/sourcerer/popup.php',
                JPATH_SITE . '/plugins/editors-xtd/sourcerer/popup.tmpl.php',
            ]
        );
    }
}
