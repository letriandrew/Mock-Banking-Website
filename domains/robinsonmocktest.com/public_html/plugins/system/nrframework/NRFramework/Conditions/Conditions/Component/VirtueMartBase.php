<?php

/**
 * @author          Tassos.gr
 * @link            https://www.tassos.gr
 * @copyright       Copyright © 2023 Tassos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework\Conditions\Conditions\Component;

defined('_JEXEC') or die;

class VirtueMartBase extends ComponentBase
{
    /**
     * The component's Single Page view name
     *
     * @var string
     */
    protected $viewSingle = 'productdetails';

    /**
     * The component's option name
     *
     * @var string
     */
    protected $component_option = 'com_virtuemart';

    /**
	 * The request ID used to retrieve the ID of the product
	 * 
	 * @var  string
	 */
    protected $request_id = 'virtuemart_product_id';

    /**
     * Class Constructor
     *
     * @param object $options
     * @param object $factory
     */
    public function __construct($options, $factory)
	{
		parent::__construct($options, $factory);
        $this->request->id = $this->app->input->getInt($this->request_id);
    }

    /**
     * Get single page's assosiated categories
     *
     * @param   Integer  The Single Page id
	 * 
     * @return  array
     */
	protected function getSinglePageCategories($id)
	{
        $db = $this->db;

        $query = $db->getQuery(true)
            ->select('virtuemart_category_id')
            ->from('#__virtuemart_product_categories')
            ->where($db->quoteName($this->request_id) . '=' . $db->q($id));

        $db->setQuery($query);

        return $db->loadColumn();
	}

	/**
     * Returns Virtuemart cart data
     *
     * @return  mixed
     */
	protected function getCart()
	{
		// load the configuration wherever required as its not available everywhere
		if (!class_exists('VmConfig'))
		{
			@include_once JPATH_ADMINISTRATOR . '/components/com_virtuemart/helpers/config.php';
			
			\VmConfig::loadConfig();
		}
		
		@include_once JPATH_SITE . '/components/com_virtuemart/helpers/cart.php';
		
		if (!class_exists('VirtueMartCart'))
		{
			return;
		}
		
		$cart = \VirtueMartCart::getCart();
		$cart->prepareCartData();

		return $cart;
	}

	/**
	 * Returns the products in the cart
	 * 
	 * @return  array
	 */
	protected function getCartProducts()
	{
		if (!$cart = $this->getCart())
		{
			return [];
		}

		return $cart->products;
	}

	/*
	 *  Returns all parent rows
	 *
	 *  @param   integer  $id      Row primary key
	 *  @param   string   $table   Table name
	 *  @param   string   $parent  Parent column name
	 *  @param   string   $child   Child column name
	 *
	 *  @return  array             Array with IDs
	 */
	public function getParentIds($id = 0, $table = 'virtuemart_categories', $parent = 'category_parent_id', $child = 'virtuemart_category_id')
	{
		return parent::getParentIds($id, $table, $parent, $child);
	}
}