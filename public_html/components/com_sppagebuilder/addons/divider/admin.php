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
	'addon_name' => 'divider',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_DIVIDER'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_DIVIDER_DESC'),
	'category'   => 'General',
	'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M16 1c.518 0 .938.42.938.938v3.515a.937.937 0 11-1.875 0V1.937c0-.517.42-.937.937-.937zm0 10.547c.518 0 .938.42.938.937v7.032a.937.937 0 11-1.875 0v-7.032c0-.517.42-.937.937-.937zm0 14.062c.518 0 .938.42.938.938v3.515a.937.937 0 11-1.875 0v-3.515c0-.518.42-.938.937-.938z" fill="currentColor"/><path d="M21.625 23.3V8.7c0-1.011 1.198-1.518 1.898-.803l7.151 7.3a1.152 1.152 0 010 1.605l-7.151 7.3c-.7.716-1.898.21-1.898-.802z" fill="currentColor"/><path opacity=".5" d="M10.375 8.7v14.6c0 1.011-1.197 1.518-1.898.803l-7.151-7.3a1.152 1.152 0 010-1.605l7.151-7.3c.7-.716 1.898-.21 1.898.802z" fill="currentColor"/></svg>',
	'settings' => [
		'content' => [
			'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_CONTENT'),
			'fields' => [
				'divider_vertical' => [
					'type'    => 'checkbox',
					'title'   => Text::_('COM_SPPAGEBUILDER_ADDON_DIVIDER_VERTICAL'),
					'desc'    => Text::_('COM_SPPAGEBUILDER_ADDON_DIVIDER_VERTICAL_DESC'),
					'std'     => 0,
					'depends' => [['divider_type', '=', 'border']],
				],

				'divider_type' => [
					'type'   => 'radio',
					'title'  => Text::_('COM_SPPAGEBUILDER_ADDON_DIVIDER_TYPE'),
					'desc'   => Text::_('COM_SPPAGEBUILDER_ADDON_DIVIDER_TYPE_DESC'),
					'values' => [
						'border' => Text::_('COM_SPPAGEBUILDER_ADDON_DIVIDER_TYPE_BORDER'),
						'image'  => Text::_('COM_SPPAGEBUILDER_ADDON_IMAGE'),
					],
					'std' => 'border',
				],

				'label_border' => [
					'type' => 'header',
					'title'	=> Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER'),
					'group'	=> [
						'border_width',
						'border_style',
						'border_color',
					],
					'depends' => [['divider_type', '=', 'border']],
				],

				'border_width' => [
					'type'    => 'slider',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_WIDTH'),
					'min'	  => 1,
					'std'     => '1',
					'depends' => [['divider_type', '=', 'border']],
				],

				'border_style' => [
					'type'   => 'select',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_STYLE'),
					'values' => [
						'solid'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE_SOLID'),
						'dashed' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE_DASHED'),
						'dotted' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE_DOTTED'),
					],
					'std'     => 'solid',
					'inline'  => true,
					'depends' => [['divider_type', '=', 'border']],
				],

				'border_color' => [
					'type'    => 'color',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
					'std'     => '#cccccc',
					'depends' => [['divider_type', '=', 'border']],
				],

				'divider_height_vertical' => [
					'type'       => 'slider',
					'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_DIVIDER_HEIGHT'),
					'max'        => 2500,
					'responsive' => true,
					'std'        => ['xl' => 100],
					'depends'    => [['divider_vertical', '=', 1], ['divider_type', '=', 'border']],
				],

				'divider_image' => [
					'type'    => 'media',
					'title'   => Text::_('COM_SPPAGEBUILDER_ADDON_DIVIDER_IMAGE'),
					'desc'    => Text::_('COM_SPPAGEBUILDER_ADDON_DIVIDER_IMAGE_DESC'),
					'depends' => [['divider_type', '=', 'image']],
				],

				'background_repeat' => [
					'type'   => 'select',
					'title'  => Text::_('COM_SPPAGEBUILDER_BG_REPEAT'),
					'desc'   => Text::_('COM_SPPAGEBUILDER_BG_REPEAT_DESC'),
					'values' => [
						'no-repeat' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_NO_REPEAT'),
						'repeat'    => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_REPEAT_ALL'),
						'repeat-x'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_REPEAT_HORIZONTALLY'),
						'repeat-y'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_REPEAT_VERTICALLY'),
						'inherit'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_INHERIT'),
					],
					'std'     => 'no-repeat',
					'depends' => [['divider_type', '=', 'image']],
				],

				'divider_height' => [
					'type'        => 'slider',
					'title'       => Text::_('COM_SPPAGEBUILDER_ADDON_DIVIDER_HEIGHT'),
					'desc'        => Text::_('COM_SPPAGEBUILDER_ADDON_DIVIDER_HEIGHT_DESC'),
					'std'         => 10,
					'depends' 	  => [['divider_type', '=', 'image']],
				],

				'container_div_width' => [
					'type'    => 'slider',
					'title'   => Text::_('COM_SPPAGEBUILDER_ADDON_DIVIDER_CONTAINER_WIDTH'),
					'desc'    => Text::_('COM_SPPAGEBUILDER_ADDON_DIVIDER_CONTAINER_WIDTH_DESC'),
					'depends' => [['divider_vertical', '!=', 1]],
					'max'        => 2000,
					'responsive' => true,
				],

				'border_radius' => [
					'type'    => 'slider',
					'title'   => Text::_('COM_SPPAGEBUILDER_ADDON_DIVIDER_BORDER_RADIUS'),
					'desc'    => Text::_('COM_SPPAGEBUILDER_ADDON_DIVIDER_BORDER_RADIUS_DESC'),
					'max'     => 1000,
					'depends' => [['divider_type', '=', 'border']],
				],

				'margin_top' => [
					'type'       => 'slider',
					'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_DIVIDER_MARGIN_TOP'),
					'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_DIVIDER_MARGIN_TOP_DESC'),
					'std'        => ['xl' => 30],
					'responsive' => true
				],

				'divider_alignment_separator' => [
					'action' => 'separator',
				],

				'margin_bottom' => [
					'type'       => 'slider',
					'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_DIVIDER_MARGIN_BOTTOM'),
					'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_DIVIDER_MARGIN_BOTTOM_DESC'),
					'std'        => ['xl' => 30],
					'responsive' => true
				],

				'divider_position' => [
					'type'              => 'alignment',
					'responsive'        => true,
					'available_options' => ['left', 'center', 'right'],
				]
			],
		],
	],
]);
