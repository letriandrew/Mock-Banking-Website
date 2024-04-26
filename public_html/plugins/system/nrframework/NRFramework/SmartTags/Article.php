<?php

/**
 * @author          Tassos.gr
 * @link            https://www.tassos.gr
 * @copyright       Copyright © 2023 Tassos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework\SmartTags;

use NRFramework\Conditions\Conditions\Component\ContentBase;
use Joomla\CMS\Factory;
use NRFramework\Cache;
use Joomla\Registry\Registry;

defined('_JEXEC') or die('Restricted access');

class Article extends SmartTag
{
    /**
     * The data object of the article loaded
     *
     * @var mixed
     */
    protected $article;

    /**
     * Class constructor
     *
     * @param [type] $factory
     * @param [type] $options
     */
    public function __construct($factory = null, $options = null)
    {
        parent::__construct($factory, $options);

        $article_id = $this->parsedOptions->get('id', null);

        $contentAssignment = new ContentBase();
        
        $this->article = $contentAssignment->getItem($article_id);
    }

    /**
     * Fetch a property from the User object
     *
     * @param   string  $key   The name of the property to return
     *
     * @return  mixed   Null if property is not found, mixed if property is found
     */
    public function fetchValue($key)
    {
        if (!$this->article)
        {
            return;
        }

        // Case SEF URL: {article.link}
        if ($key == 'link')
        {
            if (!defined('nrJ4') && !class_exists('ContentHelperRoute'))
            {
                \JLoader::register('ContentHelperRoute', JPATH_ROOT . '/components/com_content/helpers/route.php');
            }

            $routerHelper = defined('nrJ4') ? '\Joomla\Component\Content\Site\Helper\RouteHelper' : '\ContentHelperRoute';
            return \JRoute::_($routerHelper::getArticleRoute($this->article->id, $this->article->catid, $this->article->language));
        }

        // Case custom fields: {article.field.age}
        if (strpos($key, 'field.') !== false)
        {
            $fieldParts = explode('.', $key);

            $fieldname = $fieldParts[1];

            // Case {article.field.age.rawvalue}
            $fieldProp = isset($fieldParts[2]) ? $fieldParts[2] : 'value';

            if ($fields = $this->fetchCustomFields())
            {
                return $fields->get($fieldname . '.' . $fieldProp);
            }

            return;
        }

        $articleRegistry = new Registry($this->article);

        return $articleRegistry->get($key);
    }

    /**
     * Return an assosiative array with user custoom fields
     *
     * @return mixed    Array on success, null on failure
     */
    private function fetchCustomFields()
    {
        if (!$this->article)
        {
            return;
        }

        $callback = function()
        { 
            \JLoader::register('FieldsHelper', JPATH_ADMINISTRATOR . '/components/com_fields/helpers/fields.php');
    
            if (!$fields = \FieldsHelper::getFields('com_content.article', $this->article, true))
            {
                return;
            }

            $fieldsAssoc = [];

            foreach ($fields as $field)
            {
                $fieldsAssoc[$field->name] = $field;
            }

            return new Registry($fieldsAssoc);
        };

        return Cache::memo('fetchCustomFields' . $this->article->id, $callback);
    }
}