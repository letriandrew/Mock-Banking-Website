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
	'type'       => 'structure',
	'addon_name' => 'div',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_DIV'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_DIV_DESC'),
	'category'   => 'Structure',
	'icon'       => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path fill-rule="evenodd" clip-rule="evenodd" d="M0 5.1h2.9V2.9h2.2V0H0v5.1z" fill="currentColor"></path><path d="M19.7 0h-7.3v2.9h7.3V0z" fill="currentColor"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M26.9 0v2.9h2.2v2.2H32V0h-5.1z" fill="currentColor"></path><path d="M2.9 12.4H0v7.3h2.9v-7.3zM32 12.4h-2.9v7.3H32v-7.3z" fill="currentColor"></path><path fill-rule="evenodd" clip-rule="evenodd" d="M2.9 26.9H0V32h5.1v-2.9H2.9v-2.2zM29.1 29.1h-2.2V32H32v-5.1h-2.9v2.2z" fill="currentColor"></path><path d="M19.7 29.1h-7.3V32h7.3v-2.9z" fill="currentColor"></path></svg>',
	'settings' => [
		'content' => [
			'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_GENERAL'),
			'fields' => [
				'display' => [
					'type'   => 'select',
					'title'  => Text::_("COM_SPPAGEBUILDER_ADDON_DIV_DISPLAY"),
					'desc'   => Text::_("COM_SPPAGEBUILDER_ADDON_DIV_DISPLAY_DESC"),
					'values' => [
						'block'        => Text::_("COM_SPPAGEBUILDER_ADDON_DISPLAY_BLOCK"),
						'inline-block' => Text::_("COM_SPPAGEBUILDER_ADDON_DISPLAY_INLINE_BLOCK"),
						'flex'         => Text::_("COM_SPPAGEBUILDER_ADDON_DISPLAY_FLEX"),
						'inline-flex'  => Text::_("COM_SPPAGEBUILDER_ADDON_DISPLAY_INLINE_FLEX"),
					],
					'std' => 'block'
				],

				'flex_direction' => [
					'type' => 'buttons',
					'title' => Text::_("COM_SPPAGEBUILDER_ADDON_DIV_DIRECTION"),
					'std'        => ['xl' => 'row', 'lg' => '', 'md' => '', 'sm' => '', 'xs' => ''],
					'responsive' => true,
					'values' => [
						['label' => 'Horizontal', 'value' => 'row'],
						['label' => 'Vertical', 'value' => 'column'],
					],
					'depends' => [['display', '!=', 'block'], ['display', '!=', 'inline-block'], ['display', '!=', '']],
				],

				'reverse_direction' => [
					'type' => 'checkbox',
					'title' => Text::_("COM_SPPAGEBUILDER_ADDON_DIV_REVERSED_DIRECTION"),
					'std'        => ['xl' => 0, 'lg' => 0, 'md' => 0, 'sm' => 0, 'xs' => 0],
					'responsive' => true,
					'depends' => [['display', '!=', 'block'], ['display', '!=', 'inline-block'], ['display', '!=', ''], ['flex_direction', '!=', '']],
				],

				'justify_content' => [
					'type' => 'buttons',
					'title' => Text::_("COM_SPPAGEBUILDER_ADDON_DISPLAY_FLEX_JUSTIFY"),
					'std'        => ['xl' => 'center', 'lg' => '', 'md' => '', 'sm' => '', 'xs' => ''],
					'responsive' => true,
					'values' => [
						[
							'label' => [
								'tooltip' => Text::_("COM_SPPAGEBUILDER_ADDON_DISPLAY_FLEX_START"),
								'icon' => 'justifyStart'
							],
							'value' => 'flex-start'
						],
						[
							'label' => [
								'tooltip' => Text::_("COM_SPPAGEBUILDER_ADDON_DISPLAY_FLEX_END"),
								'icon' => 'justifyEnd'
							],
							'value' => 'flex-end'
						],
						[
							'label' => [
								'tooltip' => Text::_("COM_SPPAGEBUILDER_ADDON_DISPLAY_FLEX_CENTER"),
								'icon' => 'justifyCenter'
							],
							'value' => 'center'
						],
						[
							'label' => [
								'tooltip' => Text::_("COM_SPPAGEBUILDER_ADDON_DISPLAY_SPACE_BETWEEN"),
								'icon' => 'justifySpaceBetween'
							],
							'value' => 'space-between'
						],
						[
							'label' => [
								'tooltip' => Text::_("COM_SPPAGEBUILDER_ADDON_DISPLAY_SPACE_AROUND"),
								'icon' => 'justifySpaceAround'
							],
							'value' => 'space-around'
						],
						[
							'label' => [
								'tooltip' => Text::_("COM_SPPAGEBUILDER_ADDON_DISPLAY_SPACE_EVENLY"),
								'icon' => 'justifySpaceEvenly'
							],
							'value' => 'space-evenly'
						],
					],

					'depends' => [['display', '!=', 'block'], ['display', '!=', 'inline-block'], ['display', '!=', '']],
				],

				'align_items' => [
					'type' => 'buttons',
					'title' => Text::_("COM_SPPAGEBUILDER_ADDON_DISPLAY_FLEX_ALIGN"),
					'std'        => ['xl' => 'center', 'lg' => '', 'md' => '', 'sm' => '', 'xs' => ''],
					'responsive' => true,
					'values' => [
						[
							'label' => [
								'tooltip' => Text::_("COM_SPPAGEBUILDER_ADDON_DISPLAY_ALIGN_START"),
								'icon' => 'alignStart'
							],
							'value' => 'flex-start'
						],
						[
							'label' => [
								'tooltip' => Text::_("COM_SPPAGEBUILDER_ADDON_DISPLAY_ALIGN_CENTER"),
								'icon' => 'alignCenter'
							],
							'value' => 'center'
						],
						[
							'label' => [
								'tooltip' => Text::_("COM_SPPAGEBUILDER_ADDON_DISPLAY_ALIGN_END"),
								'icon' => 'alignEnd'
							],
							'value' => 'flex-end'
						],
						[
							'label' => [
								'tooltip' => Text::_("COM_SPPAGEBUILDER_ADDON_DISPLAY_ALIGN_STRETCH"),
								'icon' => 'alignStretch'
							],
							'value' => 'stretch'
						],
					],

					'depends' => [['display', '!=', 'block'], ['display', '!=', 'inline-block'], ['display', '!=', '']],
				],

				'flex_wrap' => [
					'type' => 'buttons',
					'title' => Text::_("COM_SPPAGEBUILDER_ADDON_DISPLAY_FLEX_WRAP"),
					'std'        => ['xl' => 'nowrap', 'lg' => '', 'md' => '', 'sm' => '', 'xs' => ''],
					'responsive' => true,
					'values' => [
						[
							'label' => [
								'tooltip' => Text::_("COM_SPPAGEBUILDER_ADDON_DISPLAY_FLEX_WRAP"),
								'icon' => 'flexWrap'
							],
							'value' => 'wrap'
						],
						[
							'label' => [
								'tooltip' => Text::_("COM_SPPAGEBUILDER_ADDON_DISPLAY_FLEX_NO_WRAP"),
								'icon' => 'flexNoWrap'
							],
							'value' => 'nowrap'
						],
						[
							'label' => [
								'tooltip' => Text::_("COM_SPPAGEBUILDER_ADDON_DISPLAY_FLEX_WRAP_REVERSE"),
								'icon' => 'flexWrapReverse'
							],
							'value' => 'wrap-reverse'
						],
					],

					'depends' => [['display', '!=', 'block'], ['display', '!=', 'inline-block'], ['display', '!=', '']],
				],

				'flex_gap' => [
					'type' => 'text',
					'title' => Text::_("COM_SPPAGEBUILDER_ADDON_DISPLAY_FLEX_GAP"),
					'desc' => Text::_("COM_SPPAGEBUILDER_ADDON_DISPLAY_FLEX_GAP_DESC"),
					'std'        => ['xl' => '', 'lg' => '', 'md' => '', 'sm' => '', 'xs' => ''],
					'responsive' => true,
					'depends' => [['display', '!=', 'block'], ['display', '!=', 'inline-block'], ['display', '!=', '']],
				]
			],
		],

		'advanced' => [
			'title' => Text::_("COM_SPPAGEBUILDER_GLOBAL_OPTIONS"),
			'fields' => [
				'width' => [
					'type' => 'slider',
					'title' => Text::_("COM_SPPAGEBUILDER_ADDON_DIV_WIDTH"),
					'desc' => Text::_("COM_SPPAGEBUILDER_ADDON_DIV_WIDTH_DESC"),
					'min' => 0,
					'max' => 1000
				],

				'height' => [
					'type' => 'slider',
					'title' => Text::_("COM_SPPAGEBUILDER_ADDON_DIV_HEIGHT"),
					'desc' => Text::_("COM_SPPAGEBUILDER_ADDON_DIV_HEIGHT_DESC"),
					'min' => 0,
					'max' => 1000
				],

				'overflow' => [
					'type' => 'select',
					'title' => Text::_("COM_SPPAGEBUILDER_ADDON_DIV_OVERFLOW"),
					'values' => [
						'visible' => Text::_("COM_SPPAGEBUILDER_ADDON_DIV_OVERFLOW_VISIBLE"),
						'hidden' => Text::_("COM_SPPAGEBUILDER_ADDON_DIV_OVERFLOW_HIDDEN"),
						'scroll' => Text::_("COM_SPPAGEBUILDER_ADDON_DIV_OVERFLOW_SCROLL"),
						'auto' => Text::_("COM_SPPAGEBUILDER_ADDON_DIV_OVERFLOW_AUTO")
					],
					'std' => 'visible'
				],
			],
		],
	],
]);
