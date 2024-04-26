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

namespace RegularLabs\Library;

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper as JComponentHelper;
use Joomla\CMS\Factory as JFactory;
use Joomla\CMS\Filesystem\Folder as JFolder;
use Joomla\CMS\Helper\ModuleHelper as JModuleHelper;
use Joomla\CMS\Installer\Installer as JInstaller;
use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\Plugin\PluginHelper as JPluginHelper;

class Extension
{
    /**
     * Check if all extension types of a given extension are installed
     *
     * @param string $extension
     * @param array  $types
     *
     * @return bool
     */
    public static function areInstalled($extension, $types = ['plugin'])
    {
        foreach ($types as $type)
        {
            $folder = 'system';

            if (is_array($type))
            {
                [$type, $folder] = $type;
            }

            if ( ! self::isInstalled($extension, $type, $folder))
            {
                return false;
            }
        }

        return true;
    }

    public static function disable($alias, $type = 'plugin', $folder = 'system')
    {
        $element = self::getElementByAlias($alias);

        $element = match ($element)
        {
            'module'    => 'mod_' . $element,
            'component' => 'com_' . $element,
            default     => $element,
        };

        $db    = DB::get();
        $query = DB::getQuery()
            ->update(DB::quoteName('#__extensions'))
            ->set(DB::quoteName('enabled') . ' = 0')
            ->where(DB::is('element', $element))
            ->where(DB::is('type', $type));

        if ($type == 'plugin')
        {
            $query->where(DB::is('folder', $folder));
        }

        $db->setQuery($query);
        $db->execute();
    }

    /**
     * Return an alias and element name based on the given extension name
     *
     * @param string $name
     *
     * @return array
     */
    public static function getAliasAndElement(&$name)
    {
        $name    = self::getNameByAlias($name);
        $alias   = self::getAliasByName($name);
        $element = self::getElementByAlias($alias);

        return [$alias, $element];
    }

    /**
     * Return an alias based on the given extension name
     *
     * @param string $name
     *
     * @return string
     */
    public static function getAliasByName($name)
    {
        $alias = RegEx::replace('[^a-z0-9]', '', strtolower($name));

        return match ($alias)
        {
            'advancedmodules' => 'advancedmodulemanager',
            'what-nothing'    => 'whatnothing',
            default           => $alias
        };
    }

    public static function getById($id)
    {
        $db    = DB::get();
        $query = DB::getQuery()
            ->select(DB::quoteName(['extension_id', 'manifest_cache']))
            ->from(DB::quoteName('#__extensions'))
            ->where(DB::is('extension_id', (int) $id));
        $db->setQuery($query);

        return $db->loadObject();
    }

    /**
     * Return an element name based on the given extension alias
     *
     * @param string $alias
     *
     * @return string
     */
    public static function getElementByAlias($alias)
    {
        $alias = self::getAliasByName($alias);

        return match ($alias)
        {
            'advancedmodulemanager' => 'advancedmodules',
            default                 => $alias
        };
    }

    /**
     * Return the name based on the given extension alias
     *
     * @param string $alias
     *
     * @return string
     */
    public static function getNameByAlias($alias)
    {
        // Alias is a language string
        if ( ! str_contains($alias, ' ') && strtoupper($alias) == $alias)
        {
            return JText::_($alias);
        }

        // Alias has a space and/or capitals, so is already a name
        if (str_contains($alias, ' ') || $alias !== strtolower($alias))
        {
            return $alias;
        }

        return JText::_(self::getXMLValue('name', $alias));
    }

    /**
     * Get the full path to the extension folder
     *
     * @param string $extension
     * @param string $basePath
     * @param string $check_folder
     *
     * @return string
     */
    public static function getPath($extension = 'plg_system_regularlabs', $basePath = JPATH_ADMINISTRATOR, $check_folder = '')
    {
        $basePath = $basePath ?: JPATH_SITE;

        if ( ! in_array($basePath, [JPATH_ADMINISTRATOR, JPATH_SITE], true))
        {
            return $basePath;
        }

        $extension = str_replace('.sys', '', $extension);

        switch (true)
        {
            case (str_starts_with($extension, 'mod_')):
                $path = 'modules/' . $extension;
                break;

            case (str_starts_with($extension, 'plg_')):
                [$prefix, $folder, $name] = explode('_', $extension, 3);
                $path = 'plugins/' . $folder . '/' . $name;
                break;

            case (str_starts_with($extension, 'com_')):
            default:
                $path = 'components/' . $extension;
                break;
        }

        $check_folder = $check_folder ? '/' . $check_folder : '';

        if (is_dir($basePath . '/' . $path . $check_folder))
        {
            return $basePath . '/' . $path;
        }

        if (is_dir(JPATH_ADMINISTRATOR . '/' . $path . $check_folder))
        {
            return JPATH_ADMINISTRATOR . '/' . $path;
        }

        if (is_dir(JPATH_SITE . '/' . $path . $check_folder))
        {
            return JPATH_SITE . '/' . $path;
        }

        return $basePath;
    }

    /**
     * Return an extensions main xml array
     *
     * @param string $alias
     * @param string $type
     * @param string $folder
     *
     * @return array|bool
     */
    public static function getXML($alias, $type = '', $folder = '')
    {
        $file = self::getXMLFile($alias, $type, $folder);

        if ( ! $file)
        {
            return false;
        }

        return JInstaller::parseXMLInstallFile($file);
    }

    /**
     * Return an extensions main xml file name (including path)
     *
     * @param string $alias
     * @param string $type
     * @param string $folder
     *
     * @return string
     */
    public static function getXMLFile($alias, $type = '', $folder = '')
    {
        $element = self::getElementByAlias($alias);

        $files = [];

        // Components
        if (empty($type) || $type == 'component')
        {
            $files[] = JPATH_ADMINISTRATOR . '/components/com_' . $element . '/' . $element . '.xml';
            $files[] = JPATH_SITE . '/components/com_' . $element . '/' . $element . '.xml';
            $files[] = JPATH_ADMINISTRATOR . '/components/com_' . $element . '/com_' . $element . '.xml';
            $files[] = JPATH_SITE . '/components/com_' . $element . '/com_' . $element . '.xml';
        }

        // Plugins
        if (empty($type) || $type == 'plugin')
        {
            if ( ! empty($folder))
            {
                $files[] = JPATH_PLUGINS . '/' . $folder . '/' . $element . '/' . $element . '.xml';
            }

            // System Plugins
            $files[] = JPATH_PLUGINS . '/system/' . $element . '/' . $element . '.xml';

            // Editor Button Plugins
            $files[] = JPATH_PLUGINS . '/editors-xtd/' . $element . '/' . $element . '.xml';

            // Field Plugins
            $field_name = RegEx::replace('field$', '', $element);
            $files[]    = JPATH_PLUGINS . '/fields/' . $field_name . '/' . $field_name . '.xml';
        }

        // Modules
        if (empty($type) || $type == 'module')
        {
            $files[] = JPATH_ADMINISTRATOR . '/modules/mod_' . $element . '/' . $element . '.xml';
            $files[] = JPATH_SITE . '/modules/mod_' . $element . '/' . $element . '.xml';
            $files[] = JPATH_ADMINISTRATOR . '/modules/mod_' . $element . '/mod_' . $element . '.xml';
            $files[] = JPATH_SITE . '/modules/mod_' . $element . '/mod_' . $element . '.xml';
        }

        foreach ($files as $file)
        {
            if ( ! file_exists($file))
            {
                continue;
            }

            return $file;
        }

        return '';
    }

    /**
     * Return a value from an extensions main xml file based on the given key
     *
     * @param string $key
     * @param string $alias
     * @param string $type
     * @param string $folder
     *
     * @return string
     */
    public static function getXMLValue($key, $alias, $type = '', $folder = '')
    {
        $xml = self::getXML($alias, $type, $folder);

        if ( ! $xml)
        {
            return '';
        }

        if ( ! isset($xml[$key]))
        {
            return '';
        }

        return $xml[$key] ?? '';
    }

    public static function isAuthorised($require_core_auth = true)
    {
        $user = JFactory::getApplication()->getIdentity() ?: JFactory::getUser();

        if ($user->get('guest'))
        {
            return false;
        }

        if ( ! $require_core_auth)
        {
            return true;
        }

        if ( ! $user->authorise('core.edit', 'com_content')
            && ! $user->authorise('core.edit.own', 'com_content')
            && ! $user->authorise('core.create', 'com_content')
        )
        {
            return false;
        }

        return true;
    }

    /**
     * Check if the Regular Labs Library is enabled
     *
     * @return bool
     */
    public static function isEnabled($extension, $type = 'component', $folder = 'system')
    {
        $extension = strtolower($extension);

        if ( ! self::isInstalled($extension, $type, $folder))
        {
            return false;
        }

        switch ($type)
        {
            case 'component':
                $extension = str_replace('com_', '', $extension);

                return JComponentHelper::isEnabled('com_' . $extension);

            case 'module':
                $extension = str_replace('mod_', '', $extension);

                return JModuleHelper::isEnabled('mod_' . $extension);

            case 'plugin':
                return JPluginHelper::isEnabled($folder, $extension);

            default:
                return false;
        }
    }

    public static function isEnabledInArea($params)
    {
        if ( ! isset($params->enable_frontend))
        {
            return true;
        }

        // Only allow in frontend
        if ($params->enable_frontend == 2 && Document::isClient('administrator'))
        {
            return false;
        }

        // Do not allow in frontend
        if ( ! $params->enable_frontend && Document::isClient('site'))
        {
            return false;
        }

        return true;
    }

    public static function isEnabledInComponent($params)
    {
        if ( ! isset($params->disabled_components))
        {
            return true;
        }

        return ! Protect::isRestrictedComponent($params->disabled_components);
    }

    /**
     * Check if the Regular Labs Library is enabled
     *
     * @return bool
     */
    public static function isFrameworkEnabled()
    {
        return JPluginHelper::isEnabled('system', 'regularlabs');
    }

    /**
     * Check if the given extension is installed
     *
     * @param string $extension
     * @param string $type
     * @param string $folder
     *
     * @return bool
     */
    public static function isInstalled($extension, $type = 'component', $folder = 'system')
    {
        $extension = strtolower($extension);

        switch ($type)
        {
            case 'component':
                $extension = str_replace('com_', '', $extension);

                return (file_exists(JPATH_ADMINISTRATOR . '/components/com_' . $extension . '/' . $extension . '.xml')
                    || file_exists(JPATH_SITE . '/components/com_' . $extension . '/' . $extension . '.xml')
                );

            case 'plugin':
                return file_exists(JPATH_PLUGINS . '/' . $folder . '/' . $extension . '/' . $extension . '.php');

            case 'module':
                $extension = str_replace('mod_', '', $extension);

                return (file_exists(JPATH_ADMINISTRATOR . '/modules/mod_' . $extension . '/' . $extension . '.php')
                    || file_exists(JPATH_ADMINISTRATOR . '/modules/mod_' . $extension . '/mod_' . $extension . '.php')
                    || file_exists(JPATH_SITE . '/modules/mod_' . $extension . '/' . $extension . '.php')
                    || file_exists(JPATH_SITE . '/modules/mod_' . $extension . '/mod_' . $extension . '.php')
                );

            case 'library':
                $extension = str_replace('lib_', '', $extension);

                return JFolder::exists(JPATH_LIBRARIES . '/' . $extension);

            default:
                return false;
        }
    }

    public static function orderPluginFirst($name, $folder = 'system')
    {
        $db    = DB::get();
        $query = DB::getQuery()
            ->select(['e.ordering'])
            ->from(DB::quoteName('#__extensions', 'e'))
            ->where(DB::is('e.type', 'plugin'))
            ->where(DB::is('e.folder', $folder))
            ->where(DB::is('e.element', $name));
        $db->setQuery($query);

        $current_ordering = $db->loadResult();

        if ($current_ordering == '')
        {
            return;
        }

        $query = DB::getQuery()
            ->select('e.ordering')
            ->from(DB::quoteName('#__extensions', 'e'))
            ->where(DB::is('e.type', 'plugin'))
            ->where(DB::is('e.folder', $folder))
            ->where(DB::like(DB::quoteName('e.manifest_cache'), '%"author":"Regular Labs%'))
            ->where(DB::isNot('e.element', $name))
            ->order('e.ordering ASC');
        $db->setQuery($query);

        $min_ordering = $db->loadResult();

        if ($min_ordering == '')
        {
            return;
        }

        if ($current_ordering < $min_ordering)
        {
            return;
        }

        if ($min_ordering < 1 || $current_ordering == $min_ordering)
        {
            $new_ordering = max($min_ordering, 1);

            $query = DB::getQuery()
                ->update(DB::quoteName('#__extensions'))
                ->set(DB::quoteName('ordering') . ' = ' . $new_ordering)
                ->where(DB::is('ordering', $min_ordering))
                ->where(DB::is('type', 'plugin'))
                ->where(DB::is('folder', $folder))
                ->where(DB::isNot('element', $name))
                ->where(DB::like(DB::quoteName('manifest_cache'), '%"author":"Regular Labs%'));
            $db->setQuery($query);
            $db->execute();

            $min_ordering = $new_ordering;
        }

        if ($current_ordering == $min_ordering)
        {
            return;
        }

        $new_ordering = $min_ordering - 1;

        $query = $db->getQuery(true)
            ->update(DB::quoteName('#__extensions'))
            ->set(DB::quoteName('ordering') . ' = ' . $new_ordering)
            ->where(DB::is('type', 'plugin'))
            ->where(DB::is('folder', $folder))
            ->where(DB::is('element', $name));
        $db->setQuery($query);
        $db->execute();
    }
}
