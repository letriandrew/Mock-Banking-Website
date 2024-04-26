<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('Restricted access');

/**
 *  Assets Css Parser Class
 * 
 * @since 4.0.0
 */
class SppbAssetCssParser
{
    private static $instance;

    /**
     * Get the parsed class list
     *
     * @param string $css css file for parsing
     * @return object
     * @since 4.0.0
     */
    public static function getClassName($css, $prefix, $force = false)
    {
        if (empty(trim($css))) 
        {
            return false;
        }
        
        if (self::$instance === null)
        {
            self::$instance = new SppbAssetCssParser();
        }
    
        $parsedClassName = self::$instance->parseCssSelectors($css, $prefix, $force);
    
        return $parsedClassName;
    }

    /**
     * Parse CSS Selectors
     *
     * @param  [type]  $css     CSS file to parse.
     * @param  [type]  $prefix  Prefix.
     * @param  boolean $force   Add extra prefix.
     * @return void
     */
    private function parseCssSelectors($css, $prefix, $force = false)
    {
        $result = [];
        /**
         * Check css comment
         * preg_match_all('/([\/][\*]).*./',$css,$match);
         */
        preg_match_all('/([^\{\}]+)\{([^\}]*)\}|([\/\*])/ims', $css, $match);
        foreach ($match[0] as $i => $x)
        {
            $selector = trim($match[1][$i]);
            if (preg_match_all("/(^\." . $prefix. "\-)\w+(\-\w+)?(\-\w+)?(:before)/m", $selector))
            {
                if ($force)
                {
                    $result[] = $prefix . ' ' . (explode(':', explode('.', $selector)[1])[0]);
                }
                else
                {
                    $result[] = explode(':', explode('.', $selector)[1])[0];
                }
            }
        }

        return json_encode($result);
    }
}