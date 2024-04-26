<?php

/**
 * @package SP Page Builder
 * @author JoomShaper https: //www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http: //www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('restricted access');

use Joomla\CMS\Language\Text;

SpAddonsConfig::addonConfig(
    [
        'type' => 'repeatable',
        'addon_name' => 'js_slideshow',
        'category' => 'Slider',
        'title' => Text::_('COM_SPPAGEBUILDER_ADDON_JS_SLIDER'),
        'desc' => Text::_('COM_SPPAGEBUILDER_ADDON_JS_SLIDER_DESC'),
        'icon' => '<svg viewbox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M17.656 10.518a1.138 1.138 0 1 0 0 2.276 1.138 1.138 0 0 0 0-2.276ZM15 11.656a2.656 2.656 0 1 1 5.312 0 2.656 2.656 0 0 1-5.312 0ZM10 27a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm12 0a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm-6.636-9.909c.885.842 1.854 1.306 2.88 1.345 1.014.038 1.939-.343 2.738-.91 1.563-1.108 2.888-3.095 3.906-5.067l-1.776-.918c-.985 1.906-2.13 3.533-3.287 4.354-.561.398-1.06.56-1.505.543-.433-.017-.96-.207-1.578-.796-1.163-1.106-2.319-1.737-3.458-1.923-1.157-.188-2.186.101-3.042.63-1.648 1.018-2.698 2.941-3.185 4.318l1.886.666c.42-1.19 1.265-2.612 2.35-3.282.511-.316 1.058-.457 1.67-.358.628.102 1.432.477 2.4 1.398Z" fill="currentColor"/><path fill-rule="evenodd" clip-rule="evenodd" d="M9 8h14v11H9V8ZM7 8a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2V8Zm-2.193 2.591A1 1 0 1 0 3.193 9.41L.236 13.444a1.246 1.246 0 0 0 0 1.46l2.703 3.687a1 1 0 1 0 1.613-1.182L2.18 14.174l2.626-3.583ZM16 27a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3ZM27.409 9.193a1 1 0 0 1 1.398.216l2.957 4.035c.315.43.315 1.03 0 1.46l-2.703 3.687a1 1 0 1 1-1.613-1.182l2.371-3.235-2.626-3.583a1 1 0 0 1 .216-1.398Z" fill="currentColor"/></svg>',
        'pro'=>true
]
);