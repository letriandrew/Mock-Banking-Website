<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */


//no direct access
defined('_JEXEC') or die('Restricted access');

if (!class_exists('SppbCustomCssParser'))
{
    /**
     * Class Parse CSS code for SP Page Builder
     * 
     * @since 4.0.0
     */
    class SppbCustomCssParser
    {
      /**
       * CSS date
       *
       * @var array
       * @since 1.0.0
       */
      protected $cssData;

      protected static $propCounter;

      /**
       * CSS value
       *
       * @var string
       * @since 1.0.0
       */
      protected $css;

      /**
       * Generated New CSS string
       *
       * @var string
       * @since 1.0.0
       */
      protected $newCss;

      /**
       * TAG ID
       *
       * @var string
       * @since 1.0.0
       */
      protected $id;

      /**
       * Addon Wrapper ID
       *
       * @var string
       * @since 1.0.0
       */
      protected $addonWrapperId;

      /**
       * Class instance
       *
       * @var SppbCustomCssParser
       * @since 1.0.0
       */
      private static $instance;
    
      /**
       * Generate new CSS value
       *
       * @param string $css             CSS value
       * @param string $id              Tag ID
       * @param string $addonWrapperId  Addon Wrapper ID
       * 
       * @return void
       * @since 1.0.0
       */
      public function cssWork($addonName, $css, $id, $addonWrapperId)
      {
        $this->cssData = ['all'=>[]];
        $this->css = $css;
        $this->id = $id;
        $this->addonWrapperId = $addonWrapperId;
        $this->parseCss();
        $this->addId($addonName);
    
        return $this->newCss;
      }
    
      /**
       * Get Generated CSS value.
       *
       * @param string $css CSS value
       * @param string $id  Tag ID
       * @param string $addonWrapperId  Addon Wrapper ID
       * 
       * @return void
       * @since 1.0.0
       */
      public static function getCss($addonName, $css, $id, $addonWrapperId = null)
      {
        if (empty(trim($css)))
        {
          return false;
        }
    
        if (self::$instance === null)
        {
          self::$instance = new SppbCustomCssParser();
        }
    
        $parsedCss = self::$instance->cssWork($addonName, $css, $id, $addonWrapperId);
    
        return $parsedCss;
      }

      /**
       * Add Id into section
       *
       * @return void
       * @since 1.0.0
       */
      protected function addId($addonName)
      {
        $newCss = '';
        $id = $this->id;
        $addonWrapperId = $this->addonWrapperId;

        if (count((array) $this->cssData))
        {
          foreach ($this->cssData as $media => $mediaCss)
          {
            if ($media != 'all')
            {
              $newCss .= "@media ${media}{";
            }

            foreach ($mediaCss as $selector => $values)
            {
                $selectors = explode(',', $selector);
                $newSelectors = array();
        
                foreach ($selectors as $tmpSelector)
                {
                    if (preg_match("/#addonId/", $tmpSelector))
                    {
                        $tmpSelector = str_replace("#addonId", $id, $tmpSelector);
                        $tmpSelector = str_replace("#addonWrapper", $addonWrapperId, $tmpSelector);
                        $newSelectors[] = "${tmpSelector}";
                    }
                    elseif(preg_match("/#addonWrapper/", $tmpSelector))
                    {
                      $tmpSelector = str_replace("#addonWrapper", $addonWrapperId, $tmpSelector);
                      $newSelectors[] = "${tmpSelector}";
                    }
                    else
                    {
                        $newSelectors[] = $addonName === 'div' ? "${id}${tmpSelector}" : "${id} ${tmpSelector}";
                    }
                }
                
                $newSelector = implode(',', $newSelectors);
        
                $newCss .= "${newSelector}{";

                foreach ($values as $cssProp => $cssValue)
                {
                    $newCss .= "${cssProp}:${cssValue};";
                }

                $newCss .= "}";
            }
    
            if ($media != 'all')
            {
              $newCss .= "}";
            }
          }
        }
        $this->newCss = $newCss;
      }

      /**
       * Parse CSS
       *
       * @return void
       * @since 1.0.0
       */
      protected function parseCss()
      {
        $currentMedia = 'all';
        $mediaList = array();
        $section = false;
        $css = trim($this->css);

        if (strlen($css) == 0)
        {
          return false;
        }

        $css = preg_replace('/\/\*.*\*\//Us', '', $css);
        while (preg_match('/^\s*(\@(media|import|local)([^\{\}]+)(\{)|([^\{\}]+)(\{)|([^\{\}]*)(\}))/Usi', $css, $match))
        {
          if (isset($match[8]) && ($match[8] == '}'))
          {
            if ($section !== false)
            {
              $code = trim($match[7]);
              if (empty($code) || is_null($code))
              {
                  break;
              }

              $idx = 0;
              $inQuote = false;
              $property = false;
              $codeLen = strlen($code);
              $parenthesis = array();

              while ($idx < $codeLen)
              {

                $c = isset($code[$idx]) ? $code[$idx] : '';
                $idx++;

                if ($inQuote !== false)
                {
                  if ($inQuote === $c)
                  {
                    $inQuote = false;
                  }
                } 
                elseif (($inQuote === false) && ($c == '('))
                {
                  array_push($parenthesis, '(');
                } 
                elseif (($inQuote === false) && ($c == ')'))
                {
                  array_pop($parenthesis);
                } 
                elseif (($c == '\'') || ($c == '"'))
                {
                  $inQuote = $c;
                }
                elseif (($property === false) && ($c == ':'))
                {
                  $property = trim(substr($code, 0, $idx - 1));
                  if (preg_match('/^(.*)\[([0-9]*)\]$/Us', $property, $propMatch))
                  {
                    $property = $propMatch[1].'['.static::$propCounter.']';
                    static::$propCounter += 1;
                  }
                  $code = substr($code, $idx);
                  $idx = 0;
                }
                elseif((count((array) $parenthesis) == 0) && ($c == ';'))
                {
                  $value = trim(substr($code, 0, $idx - 1));
                  $code = substr($code, $idx);
                  $idx = 0;
                  $this->AddProperty($currentMedia, $section, $property, $value);
                  $property = false;
                }
              }
              if (($idx > 0) && ($property !== false))
              {
                $value = trim($code);
                $this->AddProperty($currentMedia, $section, $property, $value);
              }
              $section = false;
            }
            elseif(count((array) $mediaList) > 0)
            {
              array_pop($mediaList);
              if (count((array) $mediaList) > 0)
              {
                $currentMedia = end($mediaList);
              }
              else
              {
                $currentMedia = 'all';
              }
            }
          }
          elseif (isset($match[6]) && ($match[6] == '{'))
          {
            $section = trim($match[5]);
            if (!isset($this->cssData[$currentMedia][$section]))
            {
              $this->cssData[$currentMedia][$section] = array();
            }
          }
          elseif (isset($match[4]) && ($match[4] == '{'))
          {
            if ($match[2] == 'media')
            {
              // New media
              $media = trim($match[3]);
              $mediaList[] = $media;
              $currentMedia = $media;
              if (!isset($this->cssData[$currentMedia]))
              {
                $this->cssData[$currentMedia] = array();
              }
            }
          }
    
          $stripCount = strlen($match[0]);
          $css = trim(substr($css, $stripCount));
        }
      }
    
      /**
       * Add CSS value into a property
       *
       * @param string $media Media type value
       * @param string $section HTML Section
       * @param string $property  CSS Property
       * @param string $value CSS Value
       * 
       * @return void
       * @since 1.0.0
       */
      protected function AddProperty($media, $section, $property, $value)
      {
    
        $media = trim($media);
        if ($media == '')
        {
          $media = 'all';
        }
        $section = trim($section);
        $property = trim($property);
        if (strlen($property) > 0)
        {
          $value = trim($value);
          if ($media == 'all')
          {
            $this->cssData[$media][$section][$property] = $value;
            $keys = array_keys($this->cssData);
            foreach ($keys as $key)
            {
              if (!isset($this->cssData[$key][$section]))
              {
                $this->cssData[$key][$section] = array();
              }
              $this->cssData[$key][$section][$property] = $value;
            }
          }
          else
          {
            if (!isset($this->cssData[$media]))
            {
              $this->cssData[$media] = $this->cssData['all'];
            }

            if (!isset($this->cssData[$media][$section]))
            {
              $this->cssData[$media][$section] = array();
            }
            $this->cssData[$media][$section][$property] = $value;
          }
        }
      }
    
    }
}