<?php

/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            https://www.tassos.gr
 * @copyright       Copyright © 2023 Tassos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

namespace NRFramework;

use Joomla\String\StringHelper;
use NRFramework\Cache;

defined('_JEXEC') or die;

class DOMCrawler
{
	/**
	 * The content to craw
	 *
	 * @var string
	 */
    protected $content;

	/**
	 * The nodes discovered by crawling
	 *
	 * @var object
	 */
    public $nodes;

	/**
	 * Class constructor
	 *
	 * @param mixed $content	The content to crawl. Defaults
	 */
    public function __construct($content = null)
    {   
        if (is_null($content))
        {
            $content = \NRFramework\Functions::getBuffer();
        }

        $this->setContent($content);
    }

	/**
	 * Set content to crawl
	 *
	 * @param string $content	The content to crawl. Defaults

	 * @return void
	 */
    public function setContent($content)
    {
        $this->content = $this->stringToUTF8($content);
    }

	/**
	 * Filter dom elements with a CSS Selector or XPath expression
	 *
	 * @param	string	$expression	 A CSS Selector or XPath expression
	 * 
	 * @return	void
	 */
    public function filter($expression)
    {
        // If empty content, return
        if (empty($this->content))
        {
			return $this;
        }

        // If empty selector, return
		if (empty($expression))
		{
			return $this;
		}

		if (!class_exists('DOMDocument') || !class_exists('DOMXPath'))
		{
			return $this;
		}

		// Cache check
		$hash = md5($expression);

		if (Cache::has($hash))
		{
			$this->nodes = Cache::get($hash, false);
			return $this;
		}

		libxml_use_internal_errors(true);
		$dom = new \DOMDocument;
		$dom->loadHTML($this->content);
		$finder = new \DOMXPath($dom);
		
		// Check if we are writing our own XPath query
		// example: =//h1[contains(@class, "faq-question")]
		if (substr($expression, 0, 1) == '=')
		{
			$xpath = StringHelper::substr($expression, 1);
		}
		else
		{
			// Create the XPath via the provided selector
			$xpath = $this->cssSelectorToXPath($expression);
		}

    	$this->nodes = $finder->query($xpath);

		// Speed up filtering by caching results
		Cache::set($hash, $this->nodes);

        return $this;
    }

	/**
	 * Returns the HTML of the first discovered node
	 *
	 * @param	string	$fallback	The fallback text to return if no node is found
	 * @param	boolean $inner		If set to true, only the node's inner HTML will be returned.
	 * 
	 * @return	string
	 */
    public function html($fallback = '', $inner = false)
    {
		if ($this->nodes && $this->nodes->length)
		{
			if ($node = $this->nodes[0])
			{
				$html = ''; 

				if ($inner)
				{
					$children = $node->childNodes;
				
					foreach ($children as $child) 
					{ 
						$html .= $node->ownerDocument->saveHTML($child);
					}
				} else 
				{
					$html = $node->ownerDocument->saveHTML($node);
				}

				return $this->cleanText($html);
			}
		}

		return $fallback;
    }

	/**
	 * Returns the text of the 1st discovered node.
	 *
	 * @param	string	$fallback	The fallback text to return if no node is found
	 * 
	 * @return	string
	 */
    public function text($fallback = '')
    {
		return $this->nodes && $this->nodes->length ? $this->cleanText($this->nodes[0]->textContent) : $fallback;
    }

	/**
	 * Returns the attribute value of the 1st discovered node
	 *
	 * @param	string	$attribute_name		The name of the attribute
	 * @param	string	$fallback			The fallback text to return if no nodes found
	 * 
	 * @return string
	 */
    public function attr($attribute_name, $fallback = '')
    {
		return $this->nodes && $this->nodes->length ? $this->cleanText($this->nodes[0]->getAttribute($attribute_name)) : $fallback;
    }

	/**
	 * Returns the total number of nodes found
	 *
	 * @param	integer	$fallback	The fallback value number to return if no nodes found
	 * 
	 * @return	integer
	 */
    public function count($fallback = 0)
    {
		return $this->nodes && $this->nodes->length ? $this->nodes->length : $fallback;
    }

	/**
	 * Helper method to crawl page based on the value of a CSS Selector field.
	 *
	 * @param array $props	Expected properties: selector, task, attr
	 * 
	 * @return string
	 */
	public function readCSSSelectorField($props)
	{
		$props = (array) $props;

		$this->filter($props['selector']);

		switch ($props['task'])
		{
			case 'html':
				return $this->html();
			
			case 'innerhtml':
				return $this->html('', true);

			case 'attr':
				return $this->attr($props['attr']);

			case 'count':
				return $this->count();

			default:
				return $this->text();
		}
	}

	/**
	 * Helper method to clean the text 
	 *
	 * @param	string	$text	The text to clean
	 * 
	 * @return string
	 */
	private function cleanText($text)
	{
		return StringHelper::trim($text);
	}

    /**
	 * Transforms the CSS Selector to a valid XPath expression
	 * 
	 * @param   string  $selector	The CSS selector to transform
	 * 
	 * @return  string	XPath expression
	 */
	private function cssSelectorToXPath($selector)
	{
		// explode() the given selectors and create a XPath syntax
		$selectors = explode(' ', $selector);

		$xpath = '';

		foreach ($selectors as $selector)
		{
			// Check if the selector contains a class or ID
			$explode_class = explode('.', $selector);
			$explode_id = explode('#', $selector);

			// Selector contains a class
			if (count($explode_class) > 1)
			{
				$prefix = (isset($explode_class[0]) && !empty($explode_class[0])) ? $explode_class[0] : '*';
				$xpath .= '//' . $prefix . '[';

				// When we use a selector such as div.class1.class2 or .class1.class2
				// we need to use all classes in the xpath and no the first one only
				unset($explode_class[0]);
				$total = count($explode_class);
				$counter = 1;
				$xpath_and_prefix = 'and';

				foreach ($explode_class as $class)
				{
					$xpath .= ($counter != 1) ? $xpath_and_prefix : '';
					$xpath .=  ' contains(concat(" ", normalize-space(@class), " "), " ' . $class . ' ") ';
					$counter++;
				}
				
				$xpath .=  ']';
			}
			else if (count($explode_id) > 1) // Selector contains an ID
			{
				$prefix = (isset($explode_id[0]) && !empty($explode_id[0])) ? $explode_id[0] : '*';
				$xpath .= './/' . $prefix . '[@id="' . $explode_id[1] . '"]';
			}
			else // No class or ID given
			{
				$xpath .= '//' . $selector;
			}
		}

		return $xpath;
	}

    /**
	 * Convert a string to UTF8 encoding for non-latin languages
	 * 
	 * @param  string
	 * 
	 * @return string
	 */
	private function stringToUTF8($string)
	{
		if (!function_exists('mb_convert_encoding'))
		{
			return $string;
		}

		return mb_convert_encoding($string, 'html-entities', 'utf8');
	}
}