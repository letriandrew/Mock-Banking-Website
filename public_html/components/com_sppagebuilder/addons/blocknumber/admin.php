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
	'addon_name' => 'blocknumber',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_BLOCKNUMBER'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_BLOCKNUMBER_DESC'),
	'category'   => 'Content',
	'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M32 4a1 1 0 01-1 1H12a1 1 0 110-2h19a1 1 0 011 1zM32 22.892a1 1 0 01-1 1H12a1 1 0 110-2h19a1 1 0 011 1zM23 9a1 1 0 01-1 1H12a1 1 0 110-2h10a1 1 0 011 1zM23 27.892a1 1 0 01-1 1H12a1 1 0 110-2h10a1 1 0 011 1z" fill="currentColor"/><path d="M3.485 12.108V4.652l-1.68.702L1 3.482l2.939-1.374h1.74v10H3.484zM4.086 28.171h3.671V30H1.13v-1.886l1.585-1.557c.21-.2.42-.405.629-.614.2-.21.386-.4.557-.572l.471-.471c.143-.143.248-.252.315-.329.276-.304.471-.571.585-.8A1.76 1.76 0 005.443 23a.91.91 0 00-.314-.7c-.2-.2-.481-.3-.843-.3s-.653.105-.872.314c-.21.2-.366.467-.471.8L1 22.314c.086-.295.219-.58.4-.857.181-.276.41-.519.686-.728.276-.22.6-.396.971-.529.381-.133.8-.2 1.257-.2.515 0 .976.076 1.386.229.41.142.752.342 1.029.6.285.257.504.561.657.914.152.352.228.733.228 1.143 0 .59-.138 1.138-.414 1.643-.267.495-.643.985-1.129 1.471l-2.057 2.029.072.142z" fill="currentColor"/></svg>',
	'pro'=>true
]
);