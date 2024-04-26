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
use Joomla\CMS\HTML\HTMLHelper as JHtml;
use Joomla\CMS\Language\Text as JText;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper as JFieldsHelper;

class Variables
{
    static $article;
    static $contact;
    static $profile;
    static $user;

    public static function replaceArticleTags(&$string, $article = null)
    {
        $matches = self::getSingleTagMatches($string, 'article');
        self::unique($matches);

        if (empty($matches))
        {
            return;
        }

        $article = self::getArticle($article);

        foreach ($matches as $match)
        {
            $replace = $article->{$match['value']} ?? '';
            $string  = str_replace($match[0], $replace, $string);
        }
    }

    public static function replaceDateTags(&$string)
    {
        $matches = self::getSingleTagMatches($string, 'date');
        self::unique($matches);

        foreach ($matches as $match)
        {
            $replace = self::getDateValue($match['value']);
            $string  = str_replace($match[0], $replace, $string);
        }
    }

    public static function replaceRandomTags(&$string)
    {
        $matches = self::getSingleTagMatches($string, 'random');

        foreach ($matches as $match)
        {
            $replace = self::getRandomValue($match['value']);
            $string  = StringHelper::replaceOnce($match[0], $replace, $string);
        }
    }

    public static function replaceReplaceTags(&$string)
    {
        self::replaceTextConversionTagsByType($string, 'replace');
    }

    public static function replaceTextConversionTags(&$string)
    {
        $types = [
            'escape',
            'lowercase',
            'uppercase',
            'notags',
            'nowhitespace',
            'toalias',
            'replace',
        ];

        foreach ($types as $type)
        {
            self::replaceTextConversionTagsByType($string, $type);
        }
    }

    public static function replaceTextTags(&$string)
    {
        $matches = self::getSingleTagMatches($string, 'j?text');
        self::unique($matches);

        foreach ($matches as $match)
        {
            $string = str_replace($match[0], JText::_($match['value']), $string);
        }
    }

    public static function replaceUserTags(&$string, $user = null)
    {
        $matches = self::getSingleTagMatches($string, 'user');
        self::unique($matches);

        foreach ($matches as $match)
        {
            $replace = self::geUserValue($match['value'], $user);
            $string  = str_replace($match[0], $replace, $string);
        }
    }

    private static function flattenObject(&$object)
    {
        $flat = (object) [];

        if (empty($object))
        {
            return $flat;
        }

        foreach ($object as $property_key => $property)
        {
            if (is_string($property))
            {
                $property = (string) $property;
            }

            if (is_string($property) && strlen($property) && $property[0] == '{')
            {
                $property = json_decode($property);
            }

            if (is_string($property) || is_numeric($property))
            {
                self::setParam($flat, $property_key, $property);
                continue;
            }

            if ( ! is_object($property) && ! is_array($property))
            {
                continue;
            }

            foreach ($property as $key => $value)
            {
                self::setParam($flat, $key, $value);
            }
        }

        return $flat;
    }

    private static function geUserValue($key, $user = null)
    {
        if ($key == 'password')
        {
            return '';
        }

        $user = self::getUser($user);

        if ($user->guest)
        {
            return '';
        }

        if (isset($user->{$key}))
        {
            return $user->{$key};
        }

        $contact = self::getContact();

        if (isset($contact->{$key}))
        {
            return $contact->{$key};
        }

        $profile = self::getProfile();

        if (isset($profile->{$key}))
        {

            return $profile->{$key};
        }

        return '';
    }

    private static function getArticle($article = null)
    {
        if ( ! $article && is_null(self::$article))
        {
            self::$article = Article::get();
        }

        $article = $article ?: self::$article;
        self::setArticleFieldsData($article);

        return self::flattenObject($article);
    }

    private static function getContact()
    {
        if (self::$contact)
        {
            return self::$contact;
        }

        $db = JFactory::getDbo();

        $query = 'SHOW TABLES LIKE ' . $db->quote($db->getPrefix() . 'contact_details');
        $db->setQuery($query);

        $has_contact_table = $db->loadResult();

        if ( ! $has_contact_table)
        {
            self::$contact = (object) [
                'x' => '',
            ];

            return self::$contact;
        }

        $query = $db->getQuery(true)
            ->select('c.*')
            ->from('#__contact_details as c')
            ->where('c.user_id = ' . (int) self::$user->id);
        $db->setQuery($query);
        self::$contact = $db->loadObject();

        if ( ! self::$contact)
        {
            self::$contact = (object) [
                'x' => '',
            ];

            return self::$contact;
        }

        self::flattenObject(self::$contact);

        return self::$contact;
    }

    private static function getDateFromFormat($date)
    {
        if ($date && str_contains($date, '%'))
        {
            $date = Date::strftimeToDateFormat($date);
        }

        $date = str_replace('[TH]', '[--==--]', $date);

        $date = JHtml::_('date', 'now', $date);

        self::replaceThIndDate($date, '[--==--]');

        return $date;
    }

    private static function getDateValue($value)
    {
        return self::getDateFromFormat($value);
    }

    /**
     * double [[tag]]...[[/tag]] style tag on multiple lines
     *
     * @param $string
     * @param $type
     *
     * @return array
     */
    private static function getDoubleTagMatches($string, $type)
    {
        if ( ! RegEx::match('\[\[/' . $type . '\]\]', $string))
        {
            return [];
        }

        RegEx::matchAll('\[\[' . $type . '(?<attributes>(?: [^\]]+)?)\]\](?<content>.*?)\[\[/' . $type . '\]\]', $string, $matches);

        return $matches ?: [];
    }

    private static function getProfile()
    {
        if (self::$profile)
        {
            return self::$profile;
        }

        $db    = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('p.profile_key, p.profile_value')
            ->from('#__user_profiles as p')
            ->where('p.user_id = ' . (int) self::$user->id);
        $db->setQuery($query);
        $rows = $db->loadObjectList();

        $profile    = (object) [];
        $profile->x = '';

        foreach ($rows as $row)
        {
            $data = json_decode($row->profile_value);

            if (is_null($data))
            {
                $data = (object) [];
            }

            $profile->{substr($row->profile_key, 8)} = $data;
        }

        self::$profile = $profile;

        return self::$profile;
    }

    private static function getRandomValue($value)
    {
        $values = ArrayHelper::toArray($value);

        foreach ($values as $i => $value)
        {
            if (RegEx::match('^([0-9]+)-([0-9]+)$', trim($value), $range))
            {
                $values[$i] = self::getRandomValueFromRange($range);
            }
        }

        return $values[rand(0, count($values) - 1)];
    }

    private static function getRandomValueFromRange($range)
    {
        return rand((int) $range[1], (int) $range[2]);
    }

    /**
     * single [[tag:...]] style tag on single line
     *
     * @param $string
     * @param $type
     *
     * @return array
     */
    private static function getSingleTagMatches($string, $type)
    {
        if ( ! RegEx::match('\[\[' . $type . '\:', $string))
        {
            return [];
        }

        RegEx::matchAll('\[\[' . $type . '\:(?<value>.*?)\]\]', $string, $matches);

        return $matches ?: [];
    }

    private static function getUser($user = null)
    {
        if (is_null($user) && is_null(self::$user))
        {
            self::$user = JFactory::getUser();
        }

        $user = $user ?? self::$user;

        return self::flattenObject($user);
    }

    /**
     * @param $string
     * @param $type
     */
    private static function replaceTextConversionTagsByType(&$string, $type)
    {
        $matches = self::getDoubleTagMatches($string, $type);
        self::unique($matches);

        foreach ($matches as $match)
        {
            $attributes = PluginTag::getAttributesFromString($match['attributes']);

            $replace = StringHelper::applyConversion($type, $match['content'], $attributes);
            $string  = str_replace($match[0], $replace, $string);
        }
    }

    private static function replaceThIndDate(&$date, $th = '[TH]')
    {
        if ( ! str_contains($date, $th))
        {
            return;
        }

        RegEx::matchAll('([0-9]+)' . RegEx::quote($th), $date, $date_matches);

        if (empty($date_matches))
        {
            $date = str_replace($th, 'th', $date);

            return;
        }

        foreach ($date_matches as $date_match)
        {
            $suffix = match ($date_match[1])
            {
                1, 21, 31 => 'st',
                2, 22, 32 => 'nd',
                3, 23     => 'rd',
                default   => 'th',
            };

            $date = StringHelper::replaceOnce($date_match[0], $date_match[1] . $suffix, $date);
        }

        $date = str_replace($th, 'th', $date);
    }

    private static function setArticleFieldsData(&$article)
    {
        if (empty($article->id))
        {
            return;
        }

        $fields = $article->jcfields ?? JFieldsHelper::getFields('com_content.article', $article, true);

        foreach ($fields as $field)
        {
            if ( ! isset($field->name) || isset($article->{$field->name}))
            {
                continue;
            }

            $article->{$field->name}          = $field->value;
            $article->{$field->name . '-raw'} = ArrayHelper::implode($field->rawvalue);
        }
    }

    private static function setParam(&$object, $key, $value)
    {
        if (
            isset($object->{$key})
            || is_numeric($key)
            || is_object($value)
            || is_array($value)
        )
        {
            return;
        }

        $object->{$key} = $value;
    }

    private static function unique(&$matches)
    {
        $unique_matches = [];

        foreach ($matches as $match)
        {
            if (in_array($match[0], $unique_matches))
            {
                continue;
            }

            $unique_matches[] = $match;
        }

        $matches = $unique_matches;
    }
}
