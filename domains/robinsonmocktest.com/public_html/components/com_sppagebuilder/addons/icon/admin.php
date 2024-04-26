<?php
/**
* @package SP Page Builder
* @author JoomShaper https://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2023 JoomShaper
* @license https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct access
defined ('_JEXEC') or die ('Restricted access');

use Joomla\CMS\Language\Text;

SpAddonsConfig::addonConfig([
	'type'       => 'content',
	'addon_name' => 'icon',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_ICON'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_ICON_DESC'),
	'category'   => 'Media',
	'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M31.837 12.3c-.1-.4-.4-.6-.8-.7l-9.8-1.4-4.4-8.9c-.3-.7-1.5-.7-1.8 0l-4.4 8.9-9.8 1.4c-.4.1-.7.3-.8.7-.1.4 0 .8.3 1l7.1 6.9-1.7 9.8c-.1.4.1.8.4 1 .3.2.7.3 1.1.1l8.8-4.6 8.8 4.6c.1.1.3.1.5.1s.4-.1.6-.2c.3-.2.5-.6.4-1l-1.7-9.8 7.1-6.9c.1-.2.2-.6.1-1zm-9.2 6.9c-.2.2-.3.6-.3.9l1.4 8.3-7.5-3.9c-.1-.1-.3-.1-.5-.1s-.3 0-.5.1l-7.5 3.9 1.4-8.3c.1-.3-.1-.7-.3-.9l-6-5.9 8.4-1.2c.3 0 .6-.3.8-.5l3.8-7.6 3.7 7.6c.1.3.4.5.8.5l8.4 1.2-6.1 5.9z" fill="currentColor"/></svg>',
	'settings' => [
		'content' => [
			'title' => Text::_('COM_SPPAGEBUILDER_ADDON_ICON'),
			'fields' => [
				'name'=> [
					'type'      => 'icon',
					'title'     => Text::_('COM_SPPAGEBUILDER_GLOBAL_ICON_NAME'),
					'clearable' => true,
					'std'       => 'fas fa-cogs',
				],

				'size'=> [
					'type'       => 'slider',
					'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_ICON_SIZE'),
					'std'        => ['xl' => 36],
					'max'        => 400,
					'responsive' => true
				],

				'title_link'=> [
					'type'   => 'link',
					'format' => 'attachment',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_LINK'),
					'std'    => '',
				],
				
				'margin'=> [
					'type'        => 'margin',
					'title'       => Text::_('COM_SPPAGEBUILDER_GLOBAL_MARGIN'),
					'desc'        => Text::_('COM_SPPAGEBUILDER_GLOBAL_MARGIN_DESC'),
					'responsive'  => true
				],

				'hover_effect'=> [
					'type'   => 'select',
					'title'  => Text::_('COM_SPPAGEBUILDER_ADDON_ICON_HOVER_EFFECT'),
					'values' => [
						''         => Text::_('COM_SPPAGEBUILDER_ADDON_ICON_HOVER_EFFECT_NONE'),
						'zoom-in'  => Text::_('COM_SPPAGEBUILDER_ADDON_ICON_HOVER_EFFECT_ZOOM_IN'),
						'zoom-out' => Text::_('COM_SPPAGEBUILDER_ADDON_ICON_HOVER_EFFECT_ZOOM_OUT'),
						'rotate'   => Text::_('COM_SPPAGEBUILDER_ADDON_ICON_HOVER_EFFECT_ROTATE'),
					],
					'std' => 'zoom-in',
					'inline' => true,
				],

				'alignment' => [
					'type'              => 'alignment',
					'title'       		=> Text::_('COM_SPPAGEBUILDER_GLOBAL_ALIGNMENT'),
					'responsive'        => true,
					'available_options' => ['left', 'center', 'right'],
				]
			],
		],

		'size' => [
			'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_SIZE'),
			'fields' => [
				'width'=> [
					'type'       => 'slider',
					'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_WIDTH'),
					'max'        => 500,
					'responsive' => true
				],

				'height'=> [
					'type'       => 'slider',
					'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_HEIGHT'),
					'max'        => 500,
					'responsive' => true
				],

				'border_radius'=> [
					'type'        => 'slider',
					'title'       => Text::_('COM_SPPAGEBUILDER_GLOBAL_RADIUS'),
					'max'         => 500,
					'responsive'  => true
				],
			]
		],

		'color' => [
			'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
			'fields' => [
				'use_hover_state' => [
					'type'   => 'radio',
					'values' => [
						'normal' => Text::_('COM_SPPAGEBUILDER_GLOBAL_NORMAL'),
						'hover' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HOVER'),
					],
					'std' => 'normal',
					'depends' => [['hover_effect', '!=', '']]
				],

				'color' => [
					'type'   => 'color',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
					'depends' => [['use_hover_state', '!=', 'hover']]
				],

				'background' => [
					'type' => 'color',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND'),
					'depends' => [['use_hover_state', '!=', 'hover']]
				],

				'border_width' => [
					'type'        => 'slider',
					'title'       => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_WIDTH'),
					'responsive'  => true,
					'depends' => [['use_hover_state', '!=', 'hover']]
				],

				'border_color' => [
					'type'   => 'color',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_COLOR'),
					'depends' => [['use_hover_state', '!=', 'hover']]
				],

				// Hover
				'hover_color'=> [
					'type'    => 'color',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
					'depends' => [
						['hover_effect', '!=', ''],
						['use_hover_state', '=', 'hover']
					],
				],

				'hover_background'=> [
					'type'    => 'color',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND'),
					'depends' => [
						['hover_effect', '!=', ''],
						['use_hover_state', '=', 'hover']
					],
				],

				'hover_border_width'=> [
					'type'       => 'slider',
					'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_WIDTH'),
					'responsive' => true,
					'depends' => [
						['hover_effect', '!=', ''],
						['use_hover_state', '=', 'hover']
					],
				],

				'hover_border_color'=> [
					'type'    => 'color',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_COLOR'),
					'depends' => [
						['hover_effect', '!=', ''],
						['use_hover_state', '=', 'hover']
					],
				],

				'hover_border_radius'=> [
					'type'       => 'slider',
					'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_RADIUS'),
					'max'        => 500,
					'responsive' => true,
					'depends' => [
						['hover_effect', '!=', ''],
						['use_hover_state', '=', 'hover']
					],
				],
			],
		],
	],
]);
