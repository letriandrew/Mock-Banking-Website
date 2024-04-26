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
trait AuditTableFieldNullableTrait
{
	private function auditNullTypeMismatches()
	{
		$mismatches = [];

		if (!empty($this->nullables))
		{
			foreach ($this->nullables as $table => $fields)
			{
				$installedNullables = $this->getTableInformation($table, 'null');
				$diff = array_diff_assoc($fields, $installedNullables);

				if (!empty($diff))
				{
					foreach (array_keys($diff) as $key)
					{
						if (!in_array($key, array_keys($installedNullables)))
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
					$info = $this->getTableInformation($table, 'null');
					$actual = isset($info[$field]) ? $info[$field] : 'n/a';

					$this->missingBuffer[] = "<li>";
					$this->missingBuffer[] = JText::_('COM_SPPAGEBUILDER_MAINTENANCE_MISMATCH') . ": <code>" . $field .
						"</code>, Expected field is null: <code>" . $type .
						"</code>, Found: <code>" . $actual . "</code>";
					$this->missingBuffer[] = "</li>";
				}

				$this->missingBuffer[] = "</ul>";
			}

			$this->missingBuffer[] = "</div>";
		}

		return $mismatches;
	}

	private function fixNullTypeMismatches($mismatches)
	{
		if (!empty($mismatches))
		{
			$this->fixedBuffer[] = "<div class='maintenance-issue-list'>";

			foreach ($mismatches as $table => $mismatch)
			{
				$this->fixedBuffer[] = "<h4><span class='maintenance-issue-resolved-icon'><span class='fa fa-check'></span></span> Table <code>" . $table . "</code></h4>";
				$this->fixedBuffer[] = "<ul>";

				foreach ($mismatch as $field => $type)
				{
					$info = $this->getTableInformation($table, 'null');
					$actual = isset($info[$field]) ? $info[$field] : 'n/a';

					try
					{
						$db = Factory::getDbo();
						$sql = '';

						if (isset($this->types[$table], $this->types[$table][$field]))
						{
							if ($type === 'yes')
							{
								$sql = "ALTER TABLE " . $db->quoteName($table) .
									" MODIFY " . $db->quoteName($field) . " " .
									$this->types[$table][$field] . " DEFAULT NULL";
							}
							else
							{
								$sql = "ALTER TABLE " . $db->quoteName($table) .
									" MODIFY " . $db->quoteName($field) . " " .
									$this->types[$table][$field] . " NOT NULL";
							}
						}

						if (!empty($sql))
						{
							$db->setQuery($sql);
							$db->execute();
						}
						else
						{
							continue;
						}

						$this->fixedBuffer[] = "<li>";

						$this->fixedBuffer[] = "Fixed: Modified mismatching field: <code class='success'>" . $field .
							"</code>, Changed field is null from <code class='success'>" . $actual .
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

	private function auditAndFixNullTypeMismatches()
	{
		$mismatches = $this->auditNullTypeMismatches();
		$this->fixNullTypeMismatches($mismatches);

		return empty($mismatches);
	}
}
