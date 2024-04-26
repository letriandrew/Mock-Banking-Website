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
 
// import Joomla view library
jimport('joomla.application.component.view');
 
class ConvertFormsViewConvertForms extends JViewLegacy
{
    /**
     * Items view display method
     * 
     * @return void
     */
    function display($tpl = null) 
    {
    	$this->config = JComponentHelper::getParams('com_convertforms');

        $model = \JModelLegacy::getInstance('Conversions', 'ConvertFormsModel', ['ignore_request' => true]);
        $model->setState('list.limit', 10);
        $model->setState('filter.state', 1);

        $this->latestleads = $model->getItems();

        // Load the ConvertForms Templates Library
        new ConvertForms\Library();

        if (!defined('nrJ4'))
        {
            JHTML::_('behavior.modal');
            JHtml::_('bootstrap.popover');
        }
        
        JHtml::stylesheet('jui/icomoon.css', array(), true);

        JToolBarHelper::title(JText::_('COM_CONVERTFORMS'));

        // Display the template
        parent::display($tpl);
    }
}