<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2022 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

/**
 * Trait for handling table fields
 *
 * @since	1.0.0
 */
trait AuditTableFieldsTrait
{
	private function auditAndFixTableFields()
	{
		$missing = $this->auditMissingFields();
		$this->fixMissingTableFields($missing);

		$removed = $this->auditRemovedFields();
		$this->fixRemovedTableFields($removed);

		return empty($missing) && empty($removed);
	}

	private function auditMissingFields()
	{
		$missing = [];

		foreach ($this->tables as $name => $table)
		{
			$dbFields = $this->getTableColumns($name);
			$sqlFields = array_keys($this->fields[$name]);

			$diff = [];

			if (!empty($dbFields) && !empty($sqlFields))
			{
				$diff = array_diff($sqlFields, $dbFields);
			}

			if (!empty($diff))
			{
				foreach ($diff as $field)
				{
					$missing[$name][$field] = $this->fields[$name][$field];
				}
			}
		}

		if (!empty($missing))
		{
			$this->missingBuffer[] = "<div class='maintenance-issue-list'>";

			foreach ($missing as $table => $fields)
			{
				$this->missingBuffer[] = "<h4><span class='maintenance-issue-icon'><span class='fa fa-times'></span></span> Table <code>" . $table . "</code></h4>";
				$this->missingBuffer[] = "<ul class='maintenance-issue-list'>";

				foreach ($fields as $field => $structure)
				{
					$this->missingBuffer[] = "<li>";
					$this->missingBuffer[] = "<code>" . $structure . "</code> does not exist.";
					$this->missingBuffer[] = "</li>";
				}

				$this->missingBuffer[] = "</ul>";
			}

			$this->missingBuffer[] = "</div>";
		}

		return $missing;
	}

	private function auditRemovedFields()
	{
		$removed = [];

		foreach ($this->tables as $name => $table)
		{
			$dbFields = $this->getTableColumns($name);
			$sqlFields = array_keys($this->fields[$name]);

			$diff = array_diff($dbFields, $sqlFields);

			if (!empty($diff))
			{
				foreach ($diff as $field)
				{
					$removed[$name][] = $field;
				}
			}
		}

		if (!empty($removed))
		{
			$this->missingBuffer[] = "<div class='maintenance-issue-list'>";

			foreach ($removed as $table => $fields)
			{
				$this->missingBuffer[] = "<h4><span class='maintenance-issue-icon'><span class='fa fa-times'></span></span> Table <code>" . $table . "</code></h4>";
				$this->missingBuffer[] = "<ul>";

				foreach ($fields as $field)
				{
					$this->missingBuffer[] = "<li>";
					$this->missingBuffer[] = "Unused field: <code>" . $field . "</code>";
					$this->missingBuffer[] = "</li>";
				}

				$this->missingBuffer[] = "</ul>";
			}

			$this->missingBuffer[] = "</div>";
		}

		return $removed;
	}

	private function fixMissingTableFields($missingFields)
	{
		if (!empty($missingFields))
		{
			$this->fixedBuffer[] = "<div class='maintenance-issue-list'>";

			foreach ($missingFields as $table => $fields)
			{
				$this->fixedBuffer[] = "<h4><span class='maintenance-issue-resolved-icon'><span class='fa fa-check'></span></span> Table <code class='success'>" . $table . "</code></h4>";
				$this->fixedBuffer[] = "<ul>";

				foreach ($fields as $field => $structure)
				{
					try
					{
						$db = Factory::getDbo();
						$createFieldSql = "ALTER TABLE " . $db->quoteName($table) . " ADD " . $structure;
						$db->setQuery($createFieldSql);
						$db->execute();

						$this->fixedBuffer[] = "<li>";
						$this->fixedBuffer[] = "Fixed: Added the missing field: <code class='success'>" . $structure . "</code> successfully!";
						$this->fixedBuffer[] = "</li>";
					}
					catch (Exception $e)
					{
						$this->errors[] = $e->getMessage();
						$this->fixedBuffer[] = "<li>Error: Failed to fix for the problem <code>" . $e->getMessage() . "</code></li>";
						continue;
					}
				}

				$this->fixedBuffer[] = "</ul>";
			}

			$this->fixedBuffer[] = "</div>";
		}
	}

	private function fixRemovedTableFields($removedFields)
	{
		if (!empty($removedFields))
		{
			$this->fixedBuffer[] = "<div class='maintenance-issue-list'>";

			foreach ($removedFields as $table => $fields)
			{
				$this->fixedBuffer[] = "<h4><span class='maintenance-issue-resolved-icon'><span class='fa fa-check'></span></span> Table <code class='success'>" . $table . "</code></h4>";
				$this->fixedBuffer[] = "<ul>";

				foreach ($fields as $field)
				{
					try
					{
						$db = Factory::getDbo();
						$createFieldSql = "ALTER TABLE " . $db->quoteName($table) . " DROP " . $db->quoteName($field);
						$db->setQuery($createFieldSql);
						$db->execute();

						$this->fixedBuffer[] = "<li>";
						$this->fixedBuffer[] = "Fixed: Removed the unused field: <code class='success'>" . $field . "</code> successfully!";
						$this->fixedBuffer[] = "</li>";
					}
					catch (Exception $e)
					{
						$this->errors[] = $e->getMessage();
						$this->fixedBuffer[] = "<li>Failed to fix for the problem <code>" . $e->getMessage() . "</code></li>";
						continue;
					}
				}

				$this->fixedBuffer[] = "</ul>";
			}

			$this->fixedBuffer[] = "</div>";
		}
	}
}
