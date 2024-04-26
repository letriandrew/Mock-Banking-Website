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
trait AuditTableFieldDefaultTrait
{
	private function auditDefaultValueMismatches()
	{
		$mismatches = [];

		if (!empty($this->defaults))
		{
			foreach ($this->defaults as $table => $fields)
			{
				$installedDefaults = $this->getTableInformation($table, 'default');
				$diff = [];

				foreach ($fields as $key => $field)
				{
					if ($installedDefaults[$key] !== $field)
					{
						$diff[$key] = $field;
					}
				}

				if (!empty($diff))
				{
					foreach (array_keys($diff) as $key)
					{
						if (!in_array($key, array_keys($installedDefaults)))
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

				foreach ($mismatch as $field => $value)
				{
					$info = $this->getTableInformation($table, 'default');
					$actual = isset($info[$field]) ? $info[$field] : 'n/a';
					$actual = is_null($actual) ? 'NULL' : $actual;
					$actual = $actual === '' ? "''" : $actual;

					$expected = is_null($value) ? 'NULL' : $value;
					$expected = $expected === '' ? "''" : $expected;

					$this->missingBuffer[] = "<li>";
					$this->missingBuffer[] = "Mismatch found on the field: <code>" . $field .
						"</code>, expected default value: <code>" . $expected .
						"</code>, found: <code>" . $actual . "</code>";
					$this->missingBuffer[] = "</li>";
				}

				$this->missingBuffer[] = "</ul>";	
			}

			$this->missingBuffer[] = "</div>";
		}

		return $mismatches;
	}

	private function fixDefaultValueMismatches($mismatches)
	{
		if (!empty($mismatches))
		{
			$this->fixedBuffer[] = "<div class='maintenance-issue-list'>";

			foreach ($mismatches as $table => $mismatch)
			{
				$this->fixedBuffer[] = "<h4><span class='maintenance-issue-resolved-icon'><span class='fa fa-check'></span></span> Table <code class='success'>" . $table . "</code></h4>";
				$this->fixedBuffer[] = "<ul>";

				foreach ($mismatch as $field => $value)
				{
					$info = $this->getTableInformation($table, 'default');
					$actual = isset($info[$field]) ? $info[$field] : 'n/a';
					$actual = is_null($actual) ? 'NULL' : $actual;
					$expected = is_null($value) ? 'NULL' : $value;

					try
					{
						$db = Factory::getDbo();
						$sql = "ALTER TABLE " . $db->quoteName($table) .
							" ALTER COLUMN " . $db->quoteName($field) .
							(is_null($value) ? " SET DEFAULT NULL" : " SET DEFAULT " . $db->quote($value));

						$db->setQuery($sql);
						$db->execute();

						$this->fixedBuffer[] = "<li>";
						$this->fixedBuffer[] = "Fixed: Modified mismatching field: <code class='success'>" . $field .
							"</code>, changed default value from <code class='success'>" . $actual .
							"</code> to <code class='success'>" . $expected . "</code>";
						$this->fixedBuffer[] = "</li>";
					}
					catch (Exception $e)
					{
						$this->errors[] = $e->getMessage();
						$this->fixedBuffer[] = "<li><span class='icon text-danger'>&#x274C;</span>Error: Failed to fix for the problem <code>" . $e->getMessage() . "</code></li>";
						continue;
					}
				}

				$this->fixedBuffer[] = "</ul>";
			}

			$this->fixedBuffer[] = "</div>";
		}
	}

	private function auditAndFixDefaultValueMismatches()
	{
		$mismatches = $this->auditDefaultValueMismatches();
		$this->fixDefaultValueMismatches($mismatches);

		return empty($mismatches);
	}
}
