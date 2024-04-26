<?php

/**
 * @package SP Page Builder
 * @author JoomShaper https://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;

SpAddonsConfig::addonConfig([
	'type'       => 'content',
	'addon_name' => 'animated_number',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_ANIMATED_NUMBER'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_ANIMATED_NUMBER_DESC'),
	'category'   => 'Content',
	'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M5.538 2.27h2.308v13.46H5.192V5.078l-2.538.711L2 3.52l3.538-1.25zM9.962 15.73v-1.98l4.596-4.73c1.025-1.078 1.538-1.975 1.538-2.693 0-.526-.166-.949-.5-1.27-.32-.32-.737-.48-1.25-.48-1.013 0-1.763.526-2.25 1.577l-2.23-1.308c.423-.923 1.031-1.628 1.826-2.115A4.91 4.91 0 0114.308 2c1.218 0 2.262.385 3.134 1.154.872.756 1.308 1.782 1.308 3.077 0 1.397-.737 2.833-2.212 4.308l-2.634 2.634h5.058v2.558h-9zM26.77 7.73c.91.27 1.647.744 2.21 1.424.578.667.866 1.474.866 2.423 0 1.384-.468 2.468-1.404 3.25-.923.782-2.057 1.173-3.404 1.173-1.05 0-1.993-.237-2.826-.711-.821-.488-1.417-1.2-1.789-2.135l2.27-1.308c.333 1.039 1.115 1.558 2.345 1.558.68 0 1.206-.16 1.577-.48.385-.334.577-.783.577-1.347 0-.551-.192-.994-.577-1.327-.371-.333-.897-.5-1.576-.5h-.577l-1.02-1.538 2.654-3.462h-5.27V2.27h8.462v2.192L26.77 7.73z" fill="currentColor"/><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M29 24a1 1 0 01-1 1H4a1 1 0 110-2h24a1 1 0 011 1zM24 29a1 1 0 01-1 1H8a1 1 0 110-2h15a1 1 0 011 1z" fill="currentColor"/></svg>',
	'pro'=>true
]
);