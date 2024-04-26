<?php

/**
 * @package SP Page Builder
 * @author JoomShaper https://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;

SpAddonsConfig::addonConfig([
	'type'       => 'content',
	'addon_name' => 'optin_form',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_OPTIN_FORM'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_OPTIN_FORM_DESC'),
	'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M31.85 10.974L27.216 7.7V2.919A1.03 1.03 0 0026.333 2H6.032a1.03 1.03 0 00-.883.92v4.744l-4.818 3.31a.81.81 0 00-.331.588v17.47c.063.48.44.857.92.92h30.525c.405 0 .552-.479.552-.92v-17.47c0-.22.037-.441-.147-.588zm-4.634-1.508l3.163 2.17-3.163 2.39v-4.56zM6.62 3.47h19.125v11.696l-9.563 7.208-9.562-7.208V3.47zM5.15 9.43v4.634l-3.163-2.427 3.163-2.207zm-3.678 3.715L11.77 20.94 1.471 27.89V13.145zM3.237 28.48l9.783-6.583 2.61 1.986a.883.883 0 00.516.184c.147 0 .22-.074.367-.184l2.722-2.096 9.893 6.693H3.237zm27.289-.846l-10.077-6.767 10.077-7.723v14.49z" fill="currentColor"/><g opacity=".5" fill="currentColor"><path d="M10.298 8.252h3.31a.736.736 0 100-1.47h-3.31a.736.736 0 000 1.47zM10.298 11.562h11.769a.736.736 0 000-1.47h-11.77a.736.736 0 100 1.47zM22.802 14.137a.736.736 0 00-.735-.736h-11.77a.736.736 0 000 1.471h11.77c.406 0 .735-.329.735-.735z"/></g></svg>',
	'pro'=>true
]
);