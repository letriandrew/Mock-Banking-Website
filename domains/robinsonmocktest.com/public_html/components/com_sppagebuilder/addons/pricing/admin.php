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
	'addon_name' => 'pricing',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_PRICING'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_PRICING_DESC'),
	'category'   => 'Content',
	'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M.15 9A2.85 2.85 0 013 6.15h5v1.7H3A1.15 1.15 0 001.85 9v14c0 .635.515 1.15 1.15 1.15h5v1.7H3A2.85 2.85 0 01.15 23V9zM31.85 23A2.85 2.85 0 0129 25.85h-5v-1.7h5A1.15 1.15 0 0030.15 23V9A1.15 1.15 0 0029 7.85h-5v-1.7h5A2.85 2.85 0 0131.85 9v14z" fill="currentColor"/><path fill-rule="evenodd" clip-rule="evenodd" d="M23 1.707H9c-.22 0-.4.19-.4.426v27.734c0 .235.18.426.4.426h14c.22 0 .4-.19.4-.426V2.133c0-.235-.18-.426-.4-.426zM9 0C7.895 0 7 .955 7 2.133v27.734C7 31.045 7.895 32 9 32h14c1.105 0 2-.955 2-2.133V2.133C25 .955 24.105 0 23 0H9z" fill="currentColor"/><path d="M18.24 11.04c0 .587-.187 1.063-.56 1.43-.373.36-.86.577-1.46.65V14h-.67v-.87c-.56-.04-1.04-.197-1.44-.47a2.264 2.264 0 01-.86-1.11l1.19-.69c.213.533.583.837 1.11.91v-1.73h-.01l-.02-.01a8.986 8.986 0 01-.57-.23 4.433 4.433 0 01-.52-.29 2.134 2.134 0 01-.46-.39 2.306 2.306 0 01-.29-.53 1.979 1.979 0 01-.12-.7c0-.587.19-1.057.57-1.41.387-.353.86-.557 1.42-.61V5h.67v.89c.907.107 1.547.577 1.92 1.41l-1.16.68c-.16-.4-.413-.643-.76-.73v1.67c.62.247 1.037.443 1.25.59.513.367.77.877.77 1.53zM14.93 7.9c0 .147.047.277.14.39.093.107.253.217.48.33v-1.4c-.2.04-.353.12-.46.24a.639.639 0 00-.16.44zm1.29 3.85c.427-.093.64-.327.64-.7a.59.59 0 00-.16-.42c-.1-.113-.26-.22-.48-.32v1.44z" fill="currentColor"/><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M21 16.5a.5.5 0 01-.5.5h-9a.5.5 0 010-1h9a.5.5 0 01.5.5zM19 18.5a.5.5 0 01-.5.5h-5a.5.5 0 010-1h5a.5.5 0 01.5.5z" fill="currentColor"/><rect x="11" y="24" width="10" height="3" rx="1.5" fill="currentColor"/></svg>',
	'pro'=>true
]
);