<?php
/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            https://www.tassos.gr
 * @copyright       Copyright © 2023 Tassos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined('_JEXEC') or die('Restricted access');

use NRFramework\Extension;

JFormHelper::loadFieldClass('groupedlist');

class JFormFieldNRConditions extends JFormFieldGroupedList
{
	/**
     * List of available conditions
     *
     * @var array
     */
    public static $conditions = [
		'NR_DATETIME' => [
			'Date\Date' => 'NR_DATE',
			'Date\Day' => 'NR_WEEKDAY',
			'Date\Month' => 'NR_MONTH',
			'Date\Time' => 'NR_TIME'
		],
		'Joomla' => [
			'com_content#Component\ContentArticle' => 'NR_CONTENT_ARTICLE',
			'com_content#Component\ContentCategory' => 'NR_CONTENT_CATEGORY',
			'com_content#Component\ContentView' => 'NR_CONTENT_VIEW',
			'Joomla\UserID' => 'NR_ASSIGN_USER_ID',
			'Joomla\UserGroup' => 'NR_USERGROUP',
			'Joomla\AccessLevel' => 'NR_USERACCESSLEVEL',
			'Joomla\Menu' => 'NR_MENU',
			'Joomla\Component' => 'NR_ASSIGN_COMPONENTS',
			'Joomla\Language' => 'NR_ASSIGN_LANGS'
		],
		'NR_TECHNOLOGY' => [
			'Device' => 'NR_ASSIGN_DEVICES',
			'Browser' => 'NR_ASSIGN_BROWSERS',
			'OS' => 'NR_ASSIGN_OS'
		],
		'NR_GEOLOCATION' => [
			'Geo\City' => 'NR_CITY',
			'Geo\Country' => 'NR_ASSIGN_COUNTRIES',
			'Geo\Region' => 'NR_REGION',
			'Geo\Continent' => 'NR_CONTINENT'
		],
		'NR_INTEGRATIONS' => [
			'com_rstbox#EngageBox'=> 'NR_VIEWED_ANOTHER_BOX',
			'com_convertforms#ConvertForms'=> 'NR_CONVERT_FORMS_CAMPAIGN',
			'com_acymailing#AcyMailing|com_acym#AcyMailing' => 'NR_ACYMAILING_LIST',
			'com_akeebasubs#AkeebaSubs' => 'NR_AKEEBASUBS_LEVEL',
		],
		'VirtueMart' => [
			'com_virtuemart#Component\VirtueMartCartContainsProducts' => 'NR_VM_CART_CONTAINS_PRODUCTS',
			'com_virtuemart#Component\VirtueMartCartContainsXProducts' => 'NR_VM_CART_CONTAINS_X_PRODUCTS',
			'com_virtuemart#Component\VirtueMartCartValue' => 'NR_VM_CART_VALUE',
			'com_virtuemart#Component\VirtueMartSingle' => 'NR_VM_PRODUCT',
			'com_virtuemart#Component\VirtueMartCategory' => 'NR_VM_CATEGORY'
		],
		'Hikashop' => [
			'com_hikashop#Component\HikashopCartContainsProducts' => 'NR_HIKA_CART_CONTAINS_PRODUCTS',
			'com_hikashop#Component\HikashopCartContainsXProducts' => 'NR_HIKA_CART_CONTAINS_X_PRODUCTS',
			'com_hikashop#Component\HikashopCartValue' => 'NR_HIKA_CART_VALUE',
			'com_hikashop#Component\HikashopSingle' => 'NR_HIKA_PRODUCT',
			'com_hikashop#Component\HikashopCategory' => 'NR_HIKA_CATEGORY'
		],
		'K2' => [
			'com_k2#Component\K2Item' => 'NR_K2_ITEM',
			'com_k2#Component\K2Category' => 'NR_K2_CATEGORY',
			'com_k2#Component\K2Tag' => 'NR_K2_TAG',
			'com_k2#Component\K2Pagetype' => 'NR_K2_PAGE_TYPE',
		],
		'NR_ADVANCED' => [
			'URL' => 'NR_URL',
			'Homepage' => 'NR_HOMEPAGE',
			'Referrer' => 'NR_ASSIGN_REFERRER',
			'IP' => 'NR_IPADDRESS',
			'Pageviews' => 'NR_ASSIGN_PAGEVIEWS_VIEWS',
			'Cookie' => 'NR_COOKIE',
			'PHP' => 'NR_ASSIGN_PHP',
			'TimeOnSite' => 'NR_ASSIGN_TIMEONSITE',
			'ReturningNewVisitor' => 'NR_NEW_RETURNING_VISITOR'
		]
	];

	/**
	 * Method to get the field option groups.
	 *
	 * @return  array  The field option objects as a nested array in groups.
	 */
	protected function getGroups()
	{
		$include_rules = empty($this->element['include_rules']) ? [] : explode(',', $this->element['include_rules']);
		$exclude_rules = empty($this->element['exclude_rules']) ? [] : explode(',', $this->element['exclude_rules']);
		$exclude_rules_pro = empty($this->element['exclude_rules_pro']) ? false : $this->element['exclude_rules_pro'];

		$groups[''][] = JHtml::_('select.option', null, JText::_('NR_CB_SELECT_CONDITION'));

		foreach (self::$conditions as $conditionGroup => $conditions)
		{
			foreach ($conditions as $conditionName => $condition)
			{
				$skip_condition = false;

				/**
				 * Checks conditions that have multiple components as dependency.
				 * Check for multiple given components for a particular condition, i.e. acymailing can be loaded via com_acymailing or com_acym
				 */
				$multiple_components = explode('|', $conditionName);
				if (count($multiple_components) >= 2)
				{
					foreach ($multiple_components as $component)
					{
						$skip_condition = false;

						if (!$conditionName = $this->getConditionName($component))
						{
							$skip_condition = true;
							continue;
						}
					}
				}
				
				// If the condition must be skipped, skip it
				if ($skip_condition)
				{
					continue;
				}

				// Checks for a single condition whether its component exists and can be used.
				if (!$conditionName = $this->getConditionName($conditionName))
				{
					continue;
				}

				// If its excluded, skip it
				if (!$exclude_rules_pro && !empty($exclude_rules) && in_array($conditionName, $exclude_rules))
				{
					continue;
				}

				// If its not included, skip it
				if (!$exclude_rules_pro && !empty($include_rules) && !in_array($conditionName, $include_rules))
				{
					continue;
				}

				$is_excluded_and_pro = ((count($exclude_rules) && in_array($conditionName, $exclude_rules)) || (count($include_rules) && !in_array($conditionName, $include_rules))) && $exclude_rules_pro;

				$value = $conditionName;
				$label = JText::_($condition);
				$disabled = false;

				if ($is_excluded_and_pro)
				{
					$disabled = true;
					$value = '__';
				}

				// Add condition to the group
				$groups[JText::_($conditionGroup)][] = JHtml::_('select.option', $value, JText::_($condition), 'value', 'text', $disabled);
			}
		}

		// Merge any additional groups in the XML definition.
		return array_merge(parent::getGroups(), $groups);
	}

	/**
	 * Returns the parsed condition name.
	 * 
	 * i.e. $condition: com_k2#Component\K2Item
	 * will return: Component\K2Item
	 * 
	 * @param   string  $condition
	 * 
	 * @return  mixed
	 */
	private function getConditionName($condition)
	{
		$conditionNameParts = explode('#', $condition);

		if (count($conditionNameParts) >= 2 && !Extension::isEnabled($conditionNameParts[0]))
		{
			return false;
		}
		
		return isset($conditionNameParts[1]) ? $conditionNameParts[1] : $conditionNameParts[0];
	}
}