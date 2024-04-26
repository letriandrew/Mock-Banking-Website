<?php

/**
 * @package         Convert Forms
 * @version         4.2.1 Free
 * 
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            https://www.tassos.gr
 * @copyright       Copyright © 2023 Tassos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace ConvertForms\Tasks;

use Joomla\CMS\Factory;

defined('_JEXEC') or die('Restricted access');

class Connections
{
    public static function getList($app)
    {
		$db = Factory::getDbo();

		$query = $db->getQuery(true)
			->select($db->quoteName('id') . 'as value')
			->select($db->quoteName('title') . 'as label')
			->select($db->quoteName('created'))
			->select($db->quoteName('params'))
			->from($db->quoteName('#__convertforms_connections'))
			->where($db->quoteName('app') . '=' . $db->q($app));

		$db->setQuery($query);

		$rows = $db->loadAssocList();

		foreach ($rows as &$row)
		{
			$row['params'] = json_decode($row['params']);
			$row['created_time_ago'] = str_replace('.', '', \Joomla\CMS\HTML\HTMLHelper::_('date.relative', $row['created']));
		}

		return $rows;
    }

    public static function add($app, $title, $params = [])
    {
		$db = Factory::getDbo();

		$title = !$title ? ucfirst(strtolower($app)) . ' - Untitled Connection' : $title;

		$connection = new \stdClass();
		$connection->app    = $app;
		$connection->title  = $title;
		$connection->params = json_encode($params);
		$connection->created = Factory::getDate()->toSql();

		$db->insertObject('#__convertforms_connections', $connection);

		return $db->insertid();
    }

    public static function update($id, $title = null, $params = null)
    {
		$db = Factory::getDbo();

        $connection = new \stdClass();
        $connection->id     = $id;
        $connection->title  = $title;
        $connection->params = $params ? json_encode($params) : null;

        return $db->updateObject('#__convertforms_connections', $connection, 'id');
    }

    public static function delete($id)
    {
		$db = Factory::getDbo();

        $query = $db->getQuery(true)
            ->delete($db->quoteName('#__convertforms_connections'))
            ->where($db->quoteName('id') . '=' . $db->q($id));

        $db->setQuery($query);

        return $db->execute();
    }

    public static function get($id)
    {
		$db = Factory::getDbo();

        $query = $db->getQuery(true)
            ->select('*')
			->from($db->quoteName('#__convertforms_connections'))
            ->where($db->quoteName('id') . '=' . $db->q($id));

        $db->setQuery($query);

        if (!$result = $db->loadAssoc())
		{
			return;
		}

		$result['params'] = json_decode($result['params'], true);

		return $result;
    }
}

?>