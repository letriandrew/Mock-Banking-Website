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

class ArrayHelper
{
    /**
     * Add a postfix to all keys in an array
     *
     * @param array  $array
     * @param string $postfix
     *
     * @return array
     */
    public static function addPostfixToKeys($array, $postfix)
    {
        $pefixed = [];

        foreach ($array as $key => $value)
        {
            $pefixed[StringHelper::addPostfix($key, $postfix)] = $value;
        }

        return $pefixed;
    }

    /**
     * Add a postfix to all string values in an array
     *
     * @param array  $array
     * @param string $postfix
     *
     * @return array
     */
    public static function addPostfixToValues($array, $postfix)
    {
        foreach ($array as &$value)
        {
            $value = StringHelper::addPostfix($value, $postfix);
        }

        return $array;
    }

    /**
     * Add a prefix and postfix to all string values in an array
     *
     * @param array  $array
     * @param string $prefix
     * @param string $postfix
     * @param bool   $keep_leading_slash
     *
     * @return array
     */
    public static function addPreAndPostfixToValues($array, $prefix, $postfix, $keep_leading_slash = true)
    {
        foreach ($array as &$value)
        {
            $value = StringHelper::addPrefix($value, $prefix, $keep_leading_slash);
            $value = StringHelper::addPostfix($value, $postfix, $keep_leading_slash);
        }

        return $array;
    }

    /**
     * Add a prefix to all keys in an array
     *
     * @param array  $array
     * @param string $prefix
     *
     * @return array
     */
    public static function addPrefixToKeys($array, $prefix)
    {
        $pefixed = [];

        foreach ($array as $key => $value)
        {
            $pefixed[StringHelper::addPrefix($key, $prefix)] = $value;
        }

        return $pefixed;
    }

    /**
     * Add a prefix to all string values in an array
     *
     * @param array  $array
     * @param string $prefix
     * @param bool   $keep_leading_slash
     *
     * @return array
     */
    public static function addPrefixToValues($array, $prefix, $keep_leading_slash = true)
    {
        foreach ($array as &$value)
        {
            $value = StringHelper::addPrefix($value, $prefix, $keep_leading_slash);
        }

        return $array;
    }

    /**
     * Run a method over all values inside the array or object
     *
     * @param array  $attributes
     * @param string $class
     * @param string $method
     *
     * @return array|object
     */
    public static function applyMethodToKeys($attributes, $class = '', $method = '')
    {
        if ( ! $class || ! $method)
        {
            $caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1];

            $class  = $caller['class'];
            $method = $caller['function'];
        }

        $array = array_shift($attributes);

        if ( ! is_array($array) && ! is_object($array))
        {
            return null;
        }

        if (empty($array))
        {
            return $array;
        }

        $json = json_encode($array);

        foreach ($array as $key => $value)
        {
            $value_attributes = [$key, ...$attributes];

            $json = str_replace(
                '"' . $key . '":',
                '"' . $class::$method(...$value_attributes) . '":',
                $json
            );
        }

        return json_decode($json, true);
    }

    /**
     * Run a method over all values inside the array or object
     */
    public static function applyMethodToValues(array $attributes, string $class = '', string $method = '', int $array_number_in_attributes = 0): array|object|null
    {
        if ( ! $class || ! $method)
        {
            $caller = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1];

            $class  = $caller['class'];
            $method = $caller['function'];
        }

        $array = $attributes[$array_number_in_attributes];

        if ( ! is_array($array) && ! is_object($array))
        {
            return null;
        }

        $as_object = is_object($array);

        foreach ($array as &$value)
        {
            if ( ! is_string($value) && ! is_null($value))
            {
                continue;
            }

            $value_attributes                              = array_values($attributes);
            $value_attributes[$array_number_in_attributes] = $value;

            $value = $class::$method(...$value_attributes);
        }

        return $as_object ? (object) $array : $array;
    }

    /**
     * Change the case of array keys
     *
     * @param array $array
     * @param array $key_format ('camel',  'dash', 'dot', 'underscore')
     * @param bool  $to_lowercase
     *
     * @return array
     */
    public static function changeKeyCase($array, $format, $to_lowercase = true)
    {
        return self::applyMethodToKeys(
            [$array, $format, $to_lowercase],
            '\RegularLabs\Library\StringHelper',
            'toCase'
        );
    }

    /**
     * Clean array by trimming values and removing empty/false values
     *
     * @param array $array
     *
     * @return array
     */
    public static function clean($array)
    {
        if ( ! is_array($array))
        {
            return $array;
        }

        $array = self::trim($array);
        $array = self::unique($array);
        $array = self::removeEmpty($array);

        return $array;
    }

    /**
     * Create a tree from a flat array based on the parent_id value
     *
     * @param array  $array
     * @param int    $level
     * @param string $parent
     * @param string $id_name
     * @param string $parent_id_name
     *
     * @return array
     */
    public static function createTreeArray($array, $level = 0, $parent = 0, $id_name = 'id', $parent_id_name = 'parent_id')
    {
        if (empty($array))
        {
            return $array;
        }

        $tree = [];

        foreach ($array as $item)
        {
            $id        = $item->{$id_name} ?? 0;
            $parent_id = $item->{$parent_id_name} ?? '';

            if ($parent_id !== $parent)
            {
                continue;
            }

            $item->level = $level;

            $tree[$id] = $item;

            $children = self::createTreeArray($array, $level + 1, $id, $id_name, $parent_id_name);

            if (empty($children))
            {
                continue;
            }

            $tree[$id]->children = $children;
        }

        return $tree;
    }

    /**
     * Check if any of the given values is found in the array
     *
     * @param array $needles
     * @param array $haystack
     * @param bool  $strict
     *
     * @return boolean
     */
    public static function find($needles, $haystack, $strict = true)
    {
        if ( ! is_array($haystack) || empty($haystack))
        {
            return false;
        }

        $needles = self::toArray($needles);

        foreach ($needles as $value)
        {
            if (in_array($value, $haystack, $strict))
            {
                return true;
            }
        }

        return false;
    }

    /**
     * Flatten an array of nested arrays, keeping the order
     *
     * @param array $array
     *
     * @return array
     */
    public static function flatten($array)
    {
        $flattened = [];

        foreach ($array as $nested)
        {
            if ( ! is_array($nested))
            {
                $flattened[] = $nested;
                continue;
            }

            $flattened = [...$flattened, ...self::flatten($nested)];
        }

        return $flattened;
    }

    /**
     * Flatten a tree array into a single dimension array
     *
     * @param array  $array
     * @param string $children_name
     *
     * @return array
     */
    public static function flattenTreeArray($array, $children_name = 'children')
    {
        $flat = [];

        foreach ($array as $key => $item)
        {
            $flat[$key] = $item;

            if ( ! isset($item->{$children_name}))
            {
                continue;
            }

            $children = $item->{$children_name};
            unset($flat[$key]->{$children_name});

            $flat = [...$flat, ...self::flattenTreeArray($children, $children_name)];
        }

        foreach ($flat as $key => $item)
        {
            $check = (array) $item;
            unset($check['level']);

            if (empty($check))
            {
                unset($flat[$key]);
            }
        }

        return $flat;
    }

    /**
     * Join array elements with a string
     *
     * @param array|string $pieces
     * @param string       $glue
     * @param string       $last_glue
     *
     * @return string
     */
    public static function implode($pieces, $glue = '', $last_glue = null)
    {
        if ( ! is_array($pieces))
        {
            $pieces = self::toArray($pieces, $glue);
        }

        if (
            is_null($last_glue)
            || $last_glue == $glue
            || count($pieces) < 2
        )
        {
            return implode($glue ?? '', $pieces);
        }

        $last_item = array_pop($pieces);

        return implode($glue ?? '', $pieces) . $last_glue . $last_item;
    }

    /**
     * Removes empty values from the array
     *
     * @param array $array
     *
     * @return array
     */
    public static function removeEmpty($array)
    {
        if ( ! is_array($array))
        {
            return $array;
        }

        foreach ($array as $key => &$value)
        {
            if ($key && ! is_numeric($key))
            {
                continue;
            }

            if ($value !== '')
            {
                continue;
            }

            unset($array[$key]);
        }

        return $array;
    }

    /**
     * Removes the trailing part of all keys in an array
     *
     * @param array  $array
     * @param string $postfix
     *
     * @return array
     */
    public static function removePostfixFromKeys($array, $postfix)
    {
        $pefixed = [];

        foreach ($array as $key => $value)
        {
            $pefixed[StringHelper::removePostfix($key, $postfix)] = $value;
        }

        return $pefixed;
    }

    /**
     * Removes the trailing part of all string values in an array
     *
     * @param array  $array
     * @param string $postfix
     *
     * @return array
     */
    public static function removePostfixFromValues($array, $postfix)
    {
        foreach ($array as &$value)
        {
            $value = StringHelper::removePostfix($value, $postfix);
        }

        return $array;
    }

    /**
     * Removes the first part of all keys in an array
     *
     * @param array  $array
     * @param string $prefix
     *
     * @return array
     */
    public static function removePrefixFromKeys($array, $prefix)
    {
        $pefixed = [];

        foreach ($array as $key => $value)
        {
            $pefixed[StringHelper::removePrefix($key, $prefix)] = $value;
        }

        return $pefixed;
    }

    /**
     * Removes the first part of all string values in an array
     *
     * @param array  $array
     * @param string $prefix
     * @param bool   $keep_leading_slash
     *
     * @return array
     */
    public static function removePrefixFromValues($array, $prefix, $keep_leading_slash = true)
    {
        foreach ($array as &$value)
        {
            $value = StringHelper::removePrefix($value, $prefix, $keep_leading_slash);
        }

        return $array;
    }

    /**
     * Set the level on each object based on the parent_id value
     *
     * @param array  $array
     * @param int    $starting_level
     * @param string $parent
     * @param string $id_name
     * @param string $parent_id_name
     *
     * @return array
     */
    public static function setLevelsByParentIds($array, $starting_level = 0, $parent = 0, $id_name = 'id', $parent_id_name = 'parent_id')
    {
        if (empty($array))
        {
            return $array;
        }

        $tree = self::createTreeArray($array, $starting_level, $parent, $id_name, $parent_id_name);

        return self::flattenTreeArray($tree);
    }

    /**
     * Sorts the array by keys based on the values of another array
     *
     * @param array $array
     * @param array $order
     *
     * @return array
     */
    public static function sortByOtherArray($array, $order)
    {
        if (empty($order))
        {
            return $array;
        }

        uksort($array, function ($key1, $key2) use ($order) {
            return array_search($key1, $order) > array_search($key2, $order);
        });

        return $array;
    }

    /**
     * Convert data (string or object) to an array
     *
     * @param mixed  $data
     * @param string $separator
     * @param bool   $unique
     *
     * @return array
     */
    public static function toArray($data, $separator = ',', $unique = false, $trim = true)
    {
        if (is_array($data))
        {
            return $data;
        }

        if (is_object($data))
        {
            return (array) $data;
        }

        if ($data === '' || is_null($data))
        {
            return [];
        }

        if ($separator === '')
        {
            return [$data];
        }

        // explode on separator, but keep escaped separators
        $splitter = uniqid('RL_SPLIT');
        $data     = str_replace($separator, $splitter, $data);
        $data     = str_replace('\\' . $splitter, $separator, $data);
        $array    = explode($splitter, $data);

        if ($trim)
        {
            $array = self::trim($array);
        }

        if ($unique)
        {
            $array = array_unique($array);
        }

        return $array;
    }

    /**
     * Convert an associative array or object to a html style attribute list
     *
     * @param array  $array
     * @param string $key_prefix
     *
     * @return string
     */
    public static function toAttributeString($array, $key_prefix = '')
    {
        $array = self::toArray($array);

        return implode(' ', array_map(
            fn($key, $value) => $key_prefix . $key . '="' . htmlspecialchars($value) . '"',
            array_keys($array), $array
        ));
    }

    /**
     * Clean array by trimming values
     *
     * @param array $array
     *
     * @return array
     */
    public static function trim($array)
    {
        if ( ! is_array($array))
        {
            return $array;
        }

        foreach ($array as &$value)
        {
            if ( ! is_string($value))
            {
                continue;
            }

            $value = trim($value);
        }

        return $array;
    }

    /**
     * Removes duplicate values from the array
     *
     * @param array $array
     *
     * @return array
     */
    public static function unique($array)
    {
        if ( ! is_array($array))
        {
            return $array;
        }

        $values = [];

        foreach ($array as $key => $value)
        {
            if ( ! is_numeric($key))
            {
                continue;
            }

            if ( ! in_array($value, $values, true))
            {
                $values[] = $value;
                continue;
            }

            unset($array[$key]);
        }

        return $array;
    }
}
