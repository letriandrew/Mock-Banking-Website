<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

// No direct access
defined ('_JEXEC') or die ('Restricted access');

use Joomla\CMS\Factory;

class SppagebuilderRouterBase
{
	public static function buildRoute(&$query)
	{
		$segments = array();
		$menu = Factory::getApplication()->getMenu();

		// We need a menu item.  Either the one specified in the query, or the current active one if none specified
		if (empty($query['Itemid']))
		{
			$menuItem = $menu->getActive();
			$menuItemGiven = false;
		}
		else
		{
			$menuItem = $menu->getItem($query['Itemid']);
			$menuItemGiven = true;
		}

		// Check again
		if ($menuItemGiven && isset($menuItem) && $menuItem->component != 'com_sppagebuilder')
		{
			$menuItemGiven = false;
			unset($query['Itemid']);
		}

		if (isset($query['view']) && $query['view'])
		{
			$view = $query['view'];
		}
		else
		{
			// We need to have a view in the query or it is an invalid URL
			return $segments;
		}

		if (($menuItem instanceof stdClass) && $menuItem->query['view'] == $query['view'] && isset($query['id']) && $menuItem->query['id'] == (int) $query['id'])
		{
			unset($query['view']);
			unset($query['id']);

			return $segments;
		}

		if ($query['view'] == "page")
		{
			if (!$menuItemGiven)
			{
				$segments[] = $view;
				$segments[] = $query['id'];
			}
			unset($query['view']);
			unset($query['id']);
		}

		if(isset($query['view']) && $query['view'])
		{
			unset($query['view']);
		}

		if(isset($query['id']) && $query['id'])
		{
			$id = $query['id'];
			unset($query['id']);
		}

		if(isset($query['tmpl']) && $query['tmpl'])
		{
			unset($query['tmpl']);
		}

		if(isset($query['layout']) && $query['layout'])
		{
			$segments[] = $query['layout'];
			if(isset($id)) {
				$segments[] = $id;
			}
			unset($query['layout']);
		}

		return $segments;
	}

	// Parse
	public static function parseRoute(&$segments)
	{
		$app = Factory::getApplication();
		$menu = $app->getMenu();
		$item = $menu->getActive();
		$total = count((array) $segments);
		$vars = array();
		$view = (isset($item->query['view']) && $item->query['view']) ? $item->query['view'] : '';

		// Page
		if (count($segments) == 2 && $segments[0] == 'page')
		{
			$vars['view'] = $segments[0];
			$vars['id'] = (int) $segments[1];

			return $vars;
		}

		// Form
		if (count($segments) == 2 && $segments[0] == 'edit')
		{
			$vars['view'] = 'form';
			$vars['id'] = (int) $segments[1];
			$vars['tmpl'] = 'component';
			$vars['layout'] = 'edit';
			
			return $vars;
		}

		return $vars;
	}
}

if (JVERSION >= 4)
{
	class SppagebuilderRouter extends Joomla\CMS\Component\Router\RouterBase
	{
		public function build(&$query)
		{
			$segments = SppagebuilderRouterBase::buildRoute($query);
			return $segments;
		}

		public function parse(&$segments)
		{
			$vars = SppagebuilderRouterBase::parseRoute($segments);

			if (count($vars))
			{
				$segments = array();
			}

			return $vars;
		}
	}
}

/**
 * Build the route for the com_banners component
 *
 * This function is a proxy for the new router interface
 * for old SEF extensions.
 *
 * @param   array  &$query  An array of URL arguments
 *
 * @return  array  The URL arguments to use to assemble the subsequent URL.
 *
 * @since   3.3
 * @deprecated  4.0  Use Class based routers instead
 */
function SppagebuilderBuildRoute(&$query)
{
	$segments = SppagebuilderRouterBase::buildRoute($query);

	return $segments;
}

/**
 * Parse the segments of a URL.
 *
 * This function is a proxy for the new router interface
 * for old SEF extensions.
 *
 * @param   array  $segments  The segments of the URL to parse.
 *
 * @return  array  The URL attributes to be used by the application.
 *
 * @since   3.3
 * @deprecated  4.0  Use Class based routers instead
 */
function SppagebuilderParseRoute(&$segments)
{
	$vars = SppagebuilderRouterBase::parseRoute($segments);

	return $vars;
}