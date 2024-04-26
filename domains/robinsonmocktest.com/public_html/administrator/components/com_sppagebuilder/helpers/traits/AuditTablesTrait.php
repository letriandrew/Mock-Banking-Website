<?php
/**
 * @package Plg_Lighthouse
 * @author JoomShaper <support@joomshaper.com>
 * @copyright Copyright (c) 2010 - 2022 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

/**
 * Audit tables trait
 *
 * @since	1.0.0
 */
trait AuditTablesTrait
{
	private function auditTables()
	{
		$installedTables = $this->getAllTheInstalledTables();
		$tableNames = $this->getTableNames();
		$missingTables = [];

		foreach ($tableNames as $name)
		{
			if (!in_array($this->getRealTableName($name), $installedTables))
			{
				$missingTables[] = $name;
			}
		}

		if (!empty($missingTables))
		{
			$this->missingBuffer[] = "<div class='maintenance-issue-list'>";
			$this->missingBuffer[] = "<h4><span class='maintenance-issue-icon'><span class='fa fa-times'></span></span> Missing Tables</h4>";
			$this->missingBuffer[] = "<ul>";

			foreach ($missingTables as $tbl)
			{
				$this->missingBuffer[] = "<li>";
				$this->missingBuffer[] = "Table <code>" . $tbl . "</code> does not exist.";
				$this->missingBuffer[] = "</li>";
			}

			$this->missingBuffer[] = "</ul>";
			$this->missingBuffer[] = "</div>";
		}

		return $missingTables;
	}

	private function fixMissingTables($tables)
	{
		if (!empty($tables))
		{
			$this->fixedBuffer[] = "<div class='maintenance-issue-list'>";
			$this->fixedBuffer[] = "<h4><span class='maintenance-issue-resolved-icon'><span class='fa fa-check'></span></span> Fixed Missing Tables</h4>";
			$this->fixedBuffer[] = "<ul>";

			foreach ($tables as $table)
			{
				$createSql = implode("\n", $this->tables[$table]);

				try
				{
					$db = Factory::getDbo();
					$db->setQuery($createSql);
					$db->execute();

					$this->fixedBuffer[] = "<li>";
					$this->fixedBuffer[] = "Created <code class='success'>" . $table . "</code> table successfully!";
					$this->fixedBuffer[] = "</li>";
				}
				catch (Exception $e)
				{
					$this->errors[] = $e->getMessage();
					$this->fixedBuffer[] = "<li>Failed to fix for the problem <code>" . $e->getMessage() . "</code></li>";
					continue;
				}
			}

			$this->fixedBuffer[] = "</div>";
		}
	}

	private function auditAndFixTables()
	{
		$missing = $this->auditTables();
		$this->fixMissingTables($missing);
	}
}
