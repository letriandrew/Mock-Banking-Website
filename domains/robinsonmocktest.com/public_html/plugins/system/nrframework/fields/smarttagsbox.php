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

use NRFramework\SmartTags;

class JFormFieldSmartTagsBox extends JFormField
{
    /**
     * Undocumented variable
     *
     * @var string
     */
    public $input_selector = '.show-smart-tags';

    /**
     *  Disable field label
     *
     *  @return  boolean
     */
    protected function getLabel()
    {
        return false;
    }

    /**
     * Method to get a list of options for a list input.
     *
     * @return  array   An array of JHtml options.
     */
    protected function getInput()
    {
        JHtml::_('script', 'plg_system_nrframework/smarttagsbox.js', ['version' => 'auto', 'relative' => true]);
        JHtml::_('stylesheet', 'plg_system_nrframework/smarttagsbox.css', ['version' => 'auto', 'relative' => true]);

        JText::script('NR_SMARTTAGS_NOTFOUND');
        JText::script('NR_SMARTTAGS_SHOW');

        JFactory::getDocument()->addScriptOptions('SmartTagsBox', [
            'selector' => $this->input_selector,
            'tags'     => [
                'Joomla' => [
                    '{page.title}'     => JText::_('NR_TAG_PAGETITLE'),
                    '{url}'            => JText::_('NR_TAG_URL'),
                    '{url.path}'       => JText::_('NR_TAG_URLPATH'),
                    '{page.lang}'      => JText::_('NR_TAG_PAGELANG'),
                    '{page.langurl}'   => JText::_('NR_TAG_PAGELANGURL'),
                    '{page.desc}'      => JTEXT::_('NR_TAG_PAGEDESC'),
                    '{site.name}'      => JTEXT::_('NR_TAG_SITENAME'),
                    '{site.url}'       => JText::_('NR_TAG_SITEURL'),
                    '{site.email}'     => JText::_('NR_TAG_SITEEMAIL'),
                    '{user.id}'        => JText::_('NR_TAG_USERID'),
                    '{user.username}'  => JText::_('NR_USER_USERNAME'),
                    '{user.email}'     => JText::_('NR_TAG_USEREMAIL'),
                    '{user.name}'      => JText::_('NR_TAG_USERNAME'),
                    '{user.firstname}' => JText::_('NR_TAG_USERFIRSTNAME'),
                    '{user.lastname}'  => JText::_('NR_TAG_USERLASTNAME'),
                    '{user.groups}'    => JText::_('NR_TAG_USERGROUPS'),
                    '{user.registerdate}' => JText::_('NR_USER_REGISTRATION_DATE'),
                ],
                JText::_('NR_VISITOR') => [
                    '{client.device}'    => JText::_('NR_TAG_CLIENTDEVICE'),
                    '{ip}'               => JText::_('NR_TAG_IP'),
                    '{client.browser}'   => JText::_('NR_TAG_CLIENTBROWSER'),
                    '{client.os}'        => JText::_('NR_TAG_CLIENTOS'),
                    '{client.useragent}' => JText::_('NR_TAG_CLIENTUSERAGENT'),
                    '{client.id}'        => JText::_('NR_TAG_CLIENTID'),
                    '{geo.country}'      => JText::_('NR_TAG_GEOCOUNTRY'),
                    '{geo.countrycode}'  => JText::_('NR_TAG_GEOCOUNTRYCODE'),
                    '{geo.city}'         => JText::_('NR_TAG_GEOCITY'),
                    '{geo.location}'     => JText::_('NR_TAG_GEOLOCATION'),
                ],
                JText::_('NR_OTHER') => [
                    '{date}'  => JText::_('NR_DATE'),
                    '{time}'  => JText::_('NR_TIME'),
                    '{day}'   => JText::_('NR_TAG_DAY'),
                    '{month}' => JText::_('NR_TAG_MONTH'),
                    '{year}'  => JText::_('NR_TAG_YEAR'),
                    '{referrer}' => JText::_('NR_ASSIGN_REFERRER'),
                    '{randomid}' => JText::_('NR_TAG_RANDOMID'),
                    '{querystring.YOUR_KEY}' => JText::_('NR_QUERY_STRING'),
                    '{language.YOUR_KEY}' => JText::_('NR_LANGUAGE_STRING'),
                    '{post.YOUR_KEY}' => JText::_('NR_POST_DATA'),
                    '{cookie.YOUR_KEY}' => JText::_('NR_COOKIE')
                ]
            ]
        ]);

        // Render box layout
        $layout = new JLayoutFile('smarttagsbox', JPATH_PLUGINS . '/system/nrframework/layouts');
        return $layout->render();
    }
}