<?php

/**
 * @author          Tassos.gr
 * @link            https://www.tassos.gr
 * @copyright       Copyright © 2023 Tassos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace NRFramework\SmartTags;

use NRFramework\DOMCrawler;

defined('_JEXEC') or die('Restricted access');

/**
 * Crawl DOM elements with a Smart Tag
 * 
 * Return Text: {crawler --selector=selector [--fallback=value]}
 * Return HTML: {crawler.html --selector=selector [--fallback=value]}
 * Return Inner HTML: {crawler.html --selector=selector --innerhtml=true [--fallback=value] }
 * Return Count: {crawler.count --selector=selector [--fallback=value]}
 * 
 * Note: If this Smart Tag is called before the onAfterRender event and the given CSS selector represents elements in the modules output it's high likely no nodes are found, because modules output is not rendered yet.
 */
class Crawler extends SmartTag
{
    public function fetchValue($key)
    {
        // Sanity check.
        if (!$css_selector = $this->parsedOptions->get('selector'))
        {
            return;
        }
        
        $crawler = new DOMCrawler();
        $crawler->filter($css_selector);
        
        $fallback = $this->parsedOptions->get('fallback');

        switch ($key)
        {
            case 'html':
                return $crawler->html($fallback, $this->parsedOptions->get('innerhtml', false));
          
            case 'attr':
                return $crawler->attr($this->parsedOptions->get('attr'), $fallback);

            case 'count':
                return $crawler->count($fallback);

            // text
            default:
                return $crawler->text($fallback);
        }
    }
}