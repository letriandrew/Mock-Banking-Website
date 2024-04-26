<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Installer\Installer;

class com_sppagebuilderInstallerScript
{

    /**
     * method to uninstall the component
     *
     * @return void
     */

    public function uninstall($parent)
    {
        $db = Factory::getDBO();
        $status = new stdClass;
        $status->modules = array();
        $manifest = $parent->getParent()->manifest;

        

        // Uninstall Modules
        $modules = $manifest->xpath('modules/module');
        foreach ($modules as $module)
        {
            $name = (string)$module->attributes()->module;
            $client = (string)$module->attributes()->client;
            $db = Factory::getDBO();
            $query = "SELECT `extension_id` FROM `#__extensions` WHERE `type`='module' AND element = " . $db->Quote($name) . "";
            $db->setQuery($query);
            $extensions = $db->loadColumn();
            if (count((array) $extensions))
            {
                foreach ($extensions as $id)
                {
                    $installer = new Installer;
                    $result = $installer->uninstall('module', $id);
                }
                $status->modules[] = array('name' => $name, 'client' => $client, 'result' => $result);
            }
        }
    }



    /**
     * method to run after an install/update/uninstall method
     *
     * @return void
     */

    public function postflight($type, $parent)
    {
        if ($type == 'uninstall')
        {
            return true;
        }

        $status = new stdClass;
        $status->modules = array();
        $src = $parent->getParent()->getPath('source');
        $manifest = $parent->getParent()->manifest;

        $this->removeDashboardMenu();

        // Delete Old Modules
        $this->deleteExtension('mod_sppagebuilder_admin_menu');
        $this->deleteExtension('mod_sppagebuilder_icons');

        

        // Install Modules
        $modules = $manifest->xpath('modules/module');

        foreach ($modules as $module)
        {
            $name = (string)$module->attributes()->module;
            $client = (string)$module->attributes()->client;
            $path = $src . '/modules/' . $client . '/' . $name;

            $activate = (string)$module->attributes()->activate;
            $position = (isset($module->attributes()->position) && $module->attributes()->position) ? (string)$module->attributes()->position : '';
            $ordering = (isset($module->attributes()->ordering) && $module->attributes()->ordering) ? (string)$module->attributes()->ordering : 0;
            $platform = (isset($module->attributes()->platform) && $module->attributes()->platform) ? (string)$module->attributes()->platform : 'universal';

            $installer = new Installer;
            $result = $installer->install($path);

            if ($client === 'administrator')
            {
                $db = Factory::getDbo();
                $query = $db->getQuery(true);
                $fields = array();

                $fields[] = $db->quoteName('published') . ' = 1';

                if ($position)
                {
                    $fields[] = $db->quoteName('position') . ' = ' . $db->quote($position);
                }

                if ($ordering)
                {
                    $fields[] = $db->quoteName('ordering') . ' = ' . $db->quote($ordering);
                }

                $conditions = array(
                    $db->quoteName('module') . ' = ' . $db->quote($name)
                );

                $query->update($db->quoteName('#__modules'))->set($fields)->where($conditions);
                $db->setQuery($query);
                $db->execute();

                // Retrieve ID
                $db = Factory::getDbo();
                $query = $db->getQuery(true);
                $query->select($db->quoteName(['id']));
                $query->from($db->quoteName('#__modules'));
                $query->where($db->quoteName('module') . ' = ' . $db->quote($name));
                $db->setQuery($query);
                $id = (int) $db->loadResult();

                if ($id)
                {
                    $db = Factory::getDbo();
                    $db->setQuery("INSERT IGNORE INTO #__modules_menu (`moduleid`,`menuid`) VALUES (" . $id . ", 0)");
                    $db->execute();
                }
            }
        }

        $this->detectAndRenamePagebuilderOverrides();
        $this->fixDatabaseStructure();
    }


    private function removeDashboardMenu()
    {

        $dashboardViewPath = JPATH_ROOT . '/components/com_sppagebuilder/views/dashboard';
        
        if (Folder::exists($dashboardViewPath))
        {
            Folder::delete($dashboardViewPath);
        }

        $dashboardControllerPath = JPATH_ROOT . '/components/com_sppagebuilder/controllers/dashboard.php';

        if (File::exists($dashboardControllerPath))
        {
            File::delete($dashboardControllerPath);
        }

        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $query->delete($db->quoteName('#__menu'))
            ->where($db->quoteName('link') . ' = ' . $db->quote('index.php?option=com_sppagebuilder&view=dashboard'));
        $db->setQuery($query);

        try {
            $db->execute();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Read the install.mysql.utf8.sql file and find out the tables with its column structures.
     *
     * @return  array
     * @since   5.0.0
     */
    private function getDatabaseStructure()
    {
        $installationFilePath = JPATH_ROOT . '/administrator/components/com_sppagebuilder/sql/install/mysql/install.mysql.utf8.sql';

        if (!\file_exists($installationFilePath))
        {
            return [];
        }

        $sqlContent = file_get_contents($installationFilePath);
        $regex = "@CREATE TABLE IF NOT EXISTS `#__(.*?)` \(.*?\n(.*?)\n\) ENGINE=InnoDB DEFAULT CHARSET=utf8;@si";

        $matches = [];

        preg_match_all($regex, $sqlContent, $matches, PREG_SET_ORDER);

        $structure = [];

        foreach ($matches as $match)
        {
            $tableName = $match[1];
            $columnsStructure = $match[2];

            $columnPattern = "@`(.*?)`\s(.*?),@si";
            $columnMatches = [];
            preg_match_all($columnPattern, $columnsStructure, $columnMatches, PREG_SET_ORDER);

            $columns = [];

            foreach ($columnMatches as $columnMatch)
            {
                $columnName = $columnMatch[1];
                $columnStructure = $columnMatch[2];
                $columns[$columnName] = $columnStructure;
            }

            $structure[$tableName] = $columns;
        }

        return $structure;
    }

    /**
     * Detect the missing columns from the installed database tables.
     *
     * @param   string  $tableName          The table name where to search.
     * @param   array   $definedColumns     The column structure defined in the installer sql file.
     *
     * @return  array   The missing columns with its structures.
     * @since   5.0.0
     */
    private function detectMissingColumns(string $tableName, array $definedColumns)
    {
        $db = Factory::getDbo();
        $columns = $db->getTableColumns($tableName);

        $missing = [];

        foreach ($definedColumns as $columnName => $structure)
        {
            if (!isset($columns[$columnName]))
            {
                $missing[$columnName] = $structure;
            }
        }

        return $missing;
    }

    /**
     * Add the missing columns to the database table.
     *
     * @param   string  $tableName     The table name where to add the missing column.
     * @param   string  $columnName    The missing column name.
     * @param   string  $structure     The missing column structure.
     * 
     * @return  boolean true on successful execution, false otherwise.
     * @since   5.0.0
     */
    private function addMissingColumn(string $tableName, string $columnName, string $structure)
    {
        $db = Factory::getDbo();
        $sqlQuery = "ALTER TABLE " . $db->quoteName($tableName) . " ADD " . $db->quoteName($columnName) . " " . $structure;

        $db->setQuery($sqlQuery);

        try
        {
            $db->execute();

            return true;
        }
        catch (\Exception $e)
        {
            return false;
        }
    }

    /**
     * Set the SQL_MODE = '' so that the sql queries could run in strict mode.
     *
     * @return  boolean  True on successful execution, false otherwise.
     * @since   5.0.0
     */
    private function setSqlMode()
    {
        $db = Factory::getDbo();
        $query = "SET SQL_MODE=''";
        $db->setQuery($query);

        try
        {
            $db->execute();

            return true;
        }
        catch (\Exception $e)
        {
            return false;
        }
    }

    /**
     * Detecting any database anomaly and fix them accordingly.
     *
     * @return  void
     * @since   5.0.0
     */
    private function fixDatabaseStructure()
    {
        $structure = $this->getDatabaseStructure();

        if (!$this->setSqlMode())
        {
            return;
        }

        if (!empty($structure))
        {
            foreach ($structure as $tableName => $columns)
            {
                $tableNameWithPrefix = '#__' . $tableName;
                $missingColumns = $this->detectMissingColumns($tableNameWithPrefix, $columns);

                if (!empty($missingColumns))
                {
                    foreach ($missingColumns as $columnName => $structure)
                    {
                        if (!$this->addMissingColumn($tableNameWithPrefix, $columnName, $structure))
                        {
                            continue;
                        }
                    }
                }
            }
        }
    }

    private function detectAndRenamePagebuilderOverrides()
    {
        $templatePath = JPATH_ROOT . '/templates';
        $templateFolders = Folder::folders($templatePath);

        if (!empty($templateFolders))
        {
            foreach ($templateFolders as $template)
            {
                $overridePath = $templatePath . '/' . $template . '/html/com_sppagebuilder';
                $renamePath = $templatePath . '/' . $template . '/html/com_sppagebuilder_outdated';

                if (file_exists($overridePath))
                {
                    Folder::move($overridePath, $renamePath);
                }
            }
        }
    }

    private function deleteExtension($extension)
    {
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('extension_id')));
        $query->from($db->quoteName('#__extensions'));
        $query->where($db->quoteName('type') . ' = ' . $db->quote('module'));
        $query->where($db->quoteName('element') . ' = ' . $db->quote($extension));
        $db->setQuery($query);
        $id = (int) $db->loadResult();

        if (!empty($id))
        {
            $installer = new Installer;
            $installer->uninstall('module', $id);
        }
    }
}
