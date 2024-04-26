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

defined('_JEXEC') or die('Restricted access');

class ConvertFormsModelCampaigns extends JModelList
{
    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     *
     * @see        JController
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields']))
        {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'state', 'a.state',
                'created', 'a.created',
                'search',
                'ordering', 'a.ordering',
                'service', 'a.service',
                'name','a.name',
                'leads', 'issues'
            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * This method should only be called once per instantiation and is designed
     * to be called on the first call to the getState() method unless the model
     * configuration flag to ignore the request is set.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @param   string  $ordering   An optional ordering field.
     * @param   string  $direction  An optional direction (asc|desc).
     *
     * @return  void
     *
     * @since   3.7.0
     */
    protected function populateState($ordering = 'a.id', $direction = 'desc')
    {
        // List state information.
        parent::populateState($ordering, $direction);
    }

    /**
     * Method to build an SQL query to load the list data.
     *
     * @return      string  An SQL query
     */
    protected function getListQuery()
    {
        // Create a new query object.           
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        // Select some fields from the item table
        $query
            ->select('a.*')
            ->from('#__convertforms_campaigns a');
        
        // Filter State
        $filter = $this->getState('filter.state');
        if (is_numeric($filter))
        {
            $query->where('a.state = ' . ( int ) $filter);
        }
        else if ($filter == '')
        {
            $query->where('( a.state IN (0,1,2))');
        }

        // Filter the list over the search string if set.
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            if (stripos($search, 'id:') === 0)
            {
                $query->where('a.id = ' . ( int ) substr($search, 3));
            }
            else
            {
                $search = $db->quote('%' . $db->escape($search, true) . '%');
                $query->where(
                    '( `name` LIKE ' . $search . ' )'
                );
            }
        }

        // Confirmed Total Leads
        $query->select('
            (select count(c.id) from #__convertforms_conversions as c where c.campaign_id = a.id) as leads'
        );

        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering', 'a.id');
        $orderDirn = $this->state->get('list.direction', 'desc');
        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    /**
     *  [getItems description]
     *
     *  @return  object
     */
    public function getItems()
    {
        $items = parent::getItems();

        foreach ($items as $key => $item)
        {
            if (!$item->params)
            {
                continue;
            }

            $params = json_decode($item->params);
            $items[$key] = (object) array_merge((array) $item, (array) $params);
            unset($items[$key]->params);
        }

        return $items;
    }
}