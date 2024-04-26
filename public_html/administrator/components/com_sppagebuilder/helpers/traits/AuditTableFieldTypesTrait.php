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
trait AuditTableFieldTypesTrait
{
	private function auditFieldTypesMismatches()
	{
		$mismatches = [];

		if (!empty($this->types))
		{
			foreach ($this->types as $table => $types)
			{
				$installedTableType = $this->getTableInformation($table, 'type');
				$diff = array_diff_assoc($types, $installedTableType);

				if (!empty($diff))
				{
					$installedTableKeys = array_keys($installedTableType);

					foreach (array_keys($diff) as $key)
					{
						if (!in_array($key, $installedTableKeys))
						{
							unset($diff[$key]);
						}
					}

					if (!empty($diff))
					{
						$mismatches[$table] = $diff;
					}
				}
			}
		}

		/**
		 * Message for mismatches
		 */
		if (!empty($mismatches))
		{
			$this->missingBuffer[] = "<div class='maintenance-issue-list'>";

			foreach ($mismatches as $table => $mismatch)
			{
				$this->missingBuffer[] = "<h4><span class='maintenance-issue-icon'><span class='fa fa-times'></span></span> Table <code>" . $table . "</code></h4>";
				$this->missingBuffer[] = "<ul>";

				foreach ($mismatch as $field => $type)
				{
					$info = $this->getTableInformation($table, 'type');
					$actual = isset($info[$field]) ? $info[$field] : 'n/a';

					$this->missingBuffer[] = "<li>";
					$this->missingBuffer[] = "Mismatch found on the field: <code>" . $field .
						"</code>, Expected: <code>" . $type .
						"</code>, Found: <code>" . $actual . "</code>";
					$this->missingBuffer[] = "</li>";
				}

				$this->missingBuffer[] = "</ul>";
			}

			$this->missingBuffer[] = "</div>";
		}

		return $mismatches;
	}

	private function fixTypeMismatches($mismatches)
	{
		if (!empty($mismatches))
		{
			$this->fixedBuffer[] = "<div class='maintenance-issue-list'>";

			foreach ($mismatches as $table => $mismatch)
			{
				$this->fixedBuffer[] = "<h4><span class='maintenance-issue-resolved-icon'><span class='fa fa-check'></span></span> Table <code class='success'>" . $table . "</code></h4>";
				$this->fixedBuffer[] = "<ul>";

				foreach ($mismatch as $field => $type)
				{
					try
					{
						$info = $this->getTableInformation($table, 'type');
						$actual = isset($info[$field]) ? $info[$field] : 'n/a';

						$db = Factory::getDbo();
						$sql = "ALTER TABLE " . $db->quoteName($table) . " MODIFY " .
							$db->quoteName($field) . " " .
							$type;
						$db->setQuery($sql);
						$db->execute();

						$this->fixedBuffer[] = "<li>";
						$this->fixedBuffer[] = "Fixed: Modified mismatching field: <code class='success'>" . $field .
							"</code>, Changed from <code class='success'>" . $actual .
							"</code> to <code class='success'>" . $type . "</code>";
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

	private function auditAndFixFieldTypeMismatches()
	{
		$mismatches = $this->auditFieldTypesMismatches();
		$this->fixTypeMismatches($mismatches);

		return empty($mismatches);
	}
}
