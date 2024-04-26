<?php
/**
 * @package         Regular Labs Library
 * @version         23.10.17780
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            https://regularlabs.com
 * @copyright       Copyright © 2023 Regular Labs All Rights Reserved
 * @license         GNU General Public License version 2 or later
 */

namespace RegularLabs\Library;

defined('_JEXEC') or die;

use Joomla\CMS\Factory as JFactory;
use Joomla\Registry\Registry as JRegistry;

class Article
{
    static $articles = [];

    /**
     * Method to get article data.
     *
     * @param integer $id              The id, alias or title of the article.
     * @param boolean $get_unpublished Whether to also return the article if it is not published
     * @param array   $selects         Option array of stuff to select. Note: requires correct table alias prefixes
     *
     * @return  object|boolean Menu item data object on success, boolean false
     */
    public static function get($id = null, $get_unpublished = false, $selects = [])
    {
        $id = ($id ?? null) ?: (int) self::getId();

        $cache = new Cache;

        if ($cache->exists())
        {
            return $cache->get();
        }

        $db   = DB::get();
        $user = JFactory::getApplication()->getIdentity() ?: JFactory::getUser();

        $custom_selects = ! empty($selects);

        $query = DB::getQuery()
            ->select($custom_selects
                ? $selects
                :
                [
                    'a.id', 'a.asset_id', 'a.title', 'a.alias', 'a.introtext', 'a.fulltext',
                    'a.state', 'a.catid', 'a.created', 'a.created_by', 'a.created_by_alias',
                    // Use created if modified is 0
                    'CASE WHEN a.modified = ' . DB::quote($db->getNullDate()) . ' THEN a.created ELSE a.modified END as modified',
                    'a.modified_by', 'a.checked_out', 'a.checked_out_time', 'a.publish_up', 'a.publish_down',
                    'a.images', 'a.urls', 'a.attribs', 'a.version', 'a.ordering',
                    'a.metakey', 'a.metadesc', 'a.access', 'a.hits', 'a.metadata', 'a.featured', 'a.language',
                ]
            )
            ->from(DB::quoteName('#__content', 'a'));

        if ( ! is_numeric($id))
        {
            $query->where('(' .
                DB::is('a.title', $id)
                . ' OR ' .
                DB::is('a.alias', $id)
                . ')');
        }
        else
        {
            $query->where(DB::is('a.id', (int) $id));
        }

        // Join on category table.
        if ( ! $custom_selects)
        {
            $query->select([
                DB::quoteName('c.title', 'category_title'),
                DB::quoteName('c.alias', 'category_alias'),
                DB::quoteName('c.access', 'category_access'),
                DB::quoteName('c.lft', 'category_lft'),
                DB::quoteName('c.lft', 'category_ordering'),
            ]);
        }

        $query->innerJoin(DB::quoteName('#__categories', 'c') . ' ON ' . DB::quoteName('c.id') . ' = ' . DB::quoteName('a.catid'))
            ->where(DB::is('c.published', '>0'));

        // Join on user table.
        if ( ! $custom_selects)
        {
            $query->select(DB::quoteName('u.name', 'author'));
        }

        $query->join('LEFT', DB::quoteName('#__users', 'u') . ' ON ' . DB::quoteName('u.id') . ' = ' . DB::quoteName('a.created_by'));

        // Join over the categories to get parent category titles
        if ( ! $custom_selects)
        {
            $query->select([
                DB::quoteName('parent.title', 'parent_title'),
                DB::quoteName('parent.id', 'parent_id'),
                DB::quoteName('parent.path', 'parent_route'),
                DB::quoteName('parent.alias', 'parent_alias'),
            ]);
        }

        $query->join('LEFT', DB::quoteName('#__categories', 'parent') . ' ON ' . DB::quoteName('parent.id') . ' = ' . DB::quoteName('c.parent_id'));

        // Join on voting table
        if ( ! $custom_selects)
        {
            $query->select([
                'ROUND(v.rating_sum / v.rating_count, 0) AS rating',
                DB::quoteName('v.rating_count', 'rating_count'),
            ]);
        }

        $query->join('LEFT', DB::quoteName('#__content_rating', 'v') . ' ON ' . DB::quoteName('v.content_id') . ' = ' . DB::quoteName('a.id'));

        if ( ! $get_unpublished
            && ( ! $user->authorise('core.edit.state', 'com_content'))
            && ( ! $user->authorise('core.edit', 'com_content'))
        )
        {
            DB::addArticleIsPublishedFilters($query);
        }

        $db->setQuery($query);

        $data = $db->loadObject();

        if (empty($data))
        {
            return false;
        }

        if (isset($data->attribs))
        {
            // Convert parameter field to object.
            $data->params = new JRegistry($data->attribs);
        }

        if (isset($data->metadata))
        {
            // Convert metadata field to object.
            $data->metadata = new JRegistry($data->metadata);
        }

        return $cache->set($data);
    }

    /**
     * Gets the current article id based on url data
     */
    public static function getId()
    {
        $input = JFactory::getApplication()->input;

        $id = $input->getInt('id');

        if ( ! $id
            || ! (
                ($input->get('option', '') == 'com_content' && $input->get('view', '') == 'article')
                || ($input->get('option', '') == 'com_flexicontent' && $input->get('view', '') == 'item')
            )
        )
        {
            return false;
        }

        return $id;
    }

    public static function getPageNumber(&$all_pages, $search_string)
    {
        if (is_string($all_pages))
        {
            $all_pages = self::getPages($all_pages);
        }

        if (count($all_pages) < 2)
        {
            return 0;
        }

        foreach ($all_pages as $i => $page_text)
        {
            if ($i % 2)
            {
                continue;
            }

            if ( ! str_contains($page_text, $search_string))
            {
                continue;
            }

            $all_pages[$i] = StringHelper::replaceOnce($search_string, '---', $page_text);

            return $i / 2;
        }

        return 0;
    }

    public static function getPages($string)
    {
        if (empty($string))
        {
            return [''];
        }

        return preg_split('#(<hr class="system-pagebreak" .*?>)#s', $string, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    }

    /**
     * Passes the different article parts through the given plugin method
     *
     * @param object $article
     * @param string $context
     * @param object $class
     * @param string $method
     * @param array  $params
     * @param array  $ignore_types
     */
    public static function process(&$article, &$context, &$class, $method, $params = [], $ignore_types = [])
    {
        self::processText('title', $article, $class, $method, $params, $ignore_types);
        self::processText('created_by_alias', $article, $class, $method, $params, $ignore_types);

        $has_text                  = isset($article->text);
        $has_description           = isset($article->description);
        $has_article_texts         = isset($article->introtext) && isset($article->fulltext);
        $text_same_as_description  = $has_text && $has_description && $article->text == $article->description;
        $text_same_as_article_text = false;

        self::processText('description', $article, $class, $method, $params, $ignore_types);

        // This is a category page with a category description. So skip the text processing
        if ($text_same_as_description)
        {
            $article->text = $article->description;

            return;
        }

        // Don't replace in text fields in the category list view, as they won't get used anyway
        if (Document::isCategoryList($context))
        {
            return;
        }

        // prevent fulltext from being messed with, when it is a json encoded string (Yootheme Pro templates do this for some weird f-ing reason)
        if ( ! empty($article->fulltext) && str_starts_with($article->fulltext, '<!-- {'))
        {
            self::processText('text', $article, $class, $method, $params, $ignore_types);

            return;
        }

        if ($has_text && $has_article_texts)
        {
            $check_text               = RegEx::replace('\s', '', $article->text);
            $check_introtext_fulltext = RegEx::replace('\s', '', $article->introtext . ' ' . $article->fulltext);

            $text_same_as_article_text = $check_text == $check_introtext_fulltext;
        }

        if ($has_article_texts && ! $has_text)
        {
            self::processText('introtext', $article, $class, $method, $params, $ignore_types);
            self::processText('fulltext', $article, $class, $method, $params, $ignore_types);

            return;
        }

        if ($has_article_texts && $text_same_as_article_text)
        {
            $splitter = '͞';

            if (
                str_contains($article->introtext, $splitter)
                || str_contains($article->fulltext, $splitter)
            )
            {
                $splitter = 'Ͽ';
            }

            $article->text = $article->introtext . $splitter . $article->fulltext;

            self::processText('text', $article, $class, $method, $params, $ignore_types);

            [$article->introtext, $article->fulltext] = explode($splitter, $article->text, 2);

            $article->text = str_replace($splitter, ' ', $article->text);

            return;
        }

        self::processText('text', $article, $class, $method, $params, $ignore_types);
        self::processText('introtext', $article, $class, $method, $params, $ignore_types);

        // Don't handle fulltext on category blog views
        if ($context == 'com_content.category' && JFactory::getApplication()->input->get('view', '') == 'category')
        {
            return;
        }

        self::processText('fulltext', $article, $class, $method, $params, $ignore_types);
    }

    /**
     * @param string $type
     * @param object $article
     * @param object $class
     * @param string $method
     * @param array  $params
     * @param array  $ignore_types
     */
    private static function processText($type, &$article, &$class, $method, $params = [], $ignore_types = [])
    {
        if (empty($article->{$type}))
        {
            return;
        }

        if (in_array($type, $ignore_types, true))
        {
            return;
        }

        call_user_func_array([$class, $method], [&$article->{$type}, ...$params]);
    }
}
