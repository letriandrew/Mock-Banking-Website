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
	'type'       => 'repeatable',
	'addon_name' => 'carousel',
	'category'   => 'Slider',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_CAROUSEL'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_CAROUSEL_DESC'),
	'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M23 5H9v22h14V5zM9 3c-1.1 0-2 .9-2 2v22c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2H9z" fill="currentColor"/><path d="M27.9 19.5v-6.9c0-.5.5-.7.8-.4l3.1 3.5c.2.2.2.6 0 .8L28.7 20c-.2.2-.8-.1-.8-.5zM4.1 12.5v6.9c0 .5-.5.7-.8.4L.2 16.3c-.2-.2-.2-.6 0-.8L3.3 12c.3-.2.8.1.8.5z" fill="currentColor"/><g opacity=".5" fill-rule="evenodd" clip-rule="evenodd" fill="currentColor"><path d="M25 16.2L20.9 20c-1.8 1.7-5 1.5-6.5-.4-.8-1-2.4-1.1-3.4-.2L8.6 22 7 20.8l2.5-2.5c1.8-1.8 5.1-1.7 6.6.3.8 1 2.4 1.1 3.3.2l4.1-3.8 1.5 1.2zM17.5 9c-.8 0-1.5.7-1.5 1.5s.7 1.5 1.5 1.5 1.5-.7 1.5-1.5S18.3 9 17.5 9zM14 10.5C14 8.6 15.6 7 17.5 7S21 8.6 21 10.5 19.4 14 17.5 14 14 12.4 14 10.5z"/></g></svg>',
	'settings' => [
		'carousel_items' => [
			'title' => Text::_('COM_SPPAGEBUILDER_ADDON_CAROUSEL_ITEMS'),
			'fields' => [
				'sp_carousel_item' => [
					'type' => 'repeatable',
					'title' => Text::_('COM_SPPAGEBUILDER_ADDON_CAROUSEL_ITEMS'),
					'attr'  => [
						'title' => [
							'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_TITLE'),
							'fields' => [
								'title' => [
									'type'  => 'text',
									'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_TITLE'),
									'std'   => 'Where Art and Technology Collide',
								],

								'title_padding' => [
									'type'       => 'padding',
									'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_CAROUSEL_ITEM_TITLE_PADDING'),
									'std'        => ['xl' => '0px 0px 0px 0px', 'lg' => '', 'md' => '', 'sm' => '', 'xs' => ''],
									'responsive' => true
								],

								'title_margin' => [
									'type'       => 'margin',
									'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_CAROUSEL_ITEM_TITLE_MARGIN'),
									'std'        => ['xl' => '0px 0px 0px 0px', 'lg' => '', 'md' => '', 'sm' => '', 'xs' => ''],
									'responsive' => true
								],
							],
						],

						'content' => [
							'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_CONTENT'),
							'fields' => [
								'content' => [
									'type'  => 'editor',
									'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_CONTENT'),
									'desc'  => Text::_('COM_SPPAGEBUILDER_ADDON_CAROUSEL_ITEM_CONTENT_DESC'),
									'std'   => 'You might remember the Dell computer commercials in which a youth reports this exciting news to his friends.<br />That they are about to get their new computer.'
								],

								'content_padding' => [
									'type'       => 'padding',
									'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_CAROUSEL_ITEM_CONTENT_PADDING'),
									'std'        => ['xl' => '20px 0px 30px 0px', 'lg' => '', 'md' => '', 'sm' => '', 'xs' => ''],
									'responsive' => true
								],

								'content_margin' => [
									'type'       => 'margin',
									'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_CONTENT_MARGIN'),
									'std'        => ['xl' => '0px 0px 0px 0px', 'lg' => '', 'md' => '', 'sm' => '', 'xs' => ''],
									'responsive' => true
								],

								'bg' => [
									'type'   => 'media',
									'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_IMAGE'),
									'desc'   => Text::_('COM_SPPAGEBUILDER_ADDON_CAROUSEL_ITEM_IMAGE_DESC'),
									'format' => 'image',
									'std'    => ['src' => 'https://sppagebuilder.com/addons/carousel/carousel-bg.jpg']
								],
							],
						],

						'button' => [
							'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON'),
							'fields' => [
								'button_text' => [
									'type'  => 'text',
									'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_TEXT'),
									'desc'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_TEXT_DESC'),
									'std'   => 'Learn More',
								],

								'button_url' => [
									'type'         => 'link',
									'title'        => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_URL'),
									'desc'         => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_URL_DESC'),
								],

								'button_icon' => [
									'type'    => 'icon',
									'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_ICON'),
									'desc'    => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_ICON_DESC'),
								],

								'button_icon_position' => [
									'type'   => 'select',
									'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_ICON_POSITION'),
									'values' => [
										'left'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_LEFT'),
										'right' => Text::_('COM_SPPAGEBUILDER_GLOBAL_RIGHT'),
									],
								],
							],
						],
					],
				],

				'alignment_separator' => [
					'type'   => 'separator',
				],

				'alignment' => [
					'type'   => 'alignment',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_ALIGNMENT'),
					'std' 	 => 'center',
				],
			],
		],

		'slider_options' => [
			'title' => Text::_('COM_SPPAGEBUILDER_ADDON_CAROUSEL_SLIDER_OPTIONS'),
			'fields' => [
				'autoplay' => [
					'type'   => 'checkbox',
					'title'  => Text::_('COM_SPPAGEBUILDER_ADDON_CAROUSEL_AUTOPLAY'),
					'desc'   => Text::_('COM_SPPAGEBUILDER_ADDON_CAROUSEL_AUTOPLAY_DESC'),
					'values' => [
						1 => Text::_('JYES'),
						0 => Text::_('JNO'),
					],
					'std' => 1,
				],

				'interval' => [
					'type'    => 'slider',
					'title'   => Text::_('COM_SPPAGEBUILDER_ADDON_CAROUSEL_INTERVAL'),
					'desc'    => Text::_('COM_SPPAGEBUILDER_ADDON_CAROUSEL_INTERVAL_DESC'),
					'std'     => 5,
					'depends' => [['autoplay', '=', 1]]
				],

				'speed' => [
					'type'  => 'slider',
					'title' => Text::_('COM_SPPAGEBUILDER_ADDON_CAROUSEL_SPEED'),
					'desc'  => Text::_('COM_SPPAGEBUILDER_ADDON_CAROUSEL_SPEED_DESC'),
					'std'   => 600,
				],

				'controllers' => [
					'type'   => 'checkbox',
					'title'  => Text::_('COM_SPPAGEBUILDER_ADDON_CAROUSEL_SHOW_CONTROLLERS'),
					'values' => [
						1 => Text::_('JYES'),
						0 => Text::_('JNO'),
					],
					'std' => 1,
				],

				'arrows' => [
					'type'   => 'checkbox',
					'title'  => Text::_('COM_SPPAGEBUILDER_ADDON_CAROUSEL_SHOW_ARROWS'),
					'values' => [
						1 => Text::_('JYES'),
						0 => Text::_('JNO'),
					],
					'std' => 1,
				],
			],
		],

		'options' => [
			'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_OPTIONS'),
			'fields' => [
				'options_tab' => [
					'type'   => 'buttons',
					'std'    => 'normal',
					'values' => [
						['label' => Text::_('COM_SPPAGEBUILDER_GLOBAL_TYPOGRAPHY'), 'value' => 'typography'],
						['label' => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'), 'value' => 'color'],
						['label' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON'), 'value' => 'button'],
					],
					'std'   => 'typography',
					'tabs'    => true,
				],

				// typography
				'item_title_typography' => [
					'type' => 'typography',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_TITLE'),
					'depends' => [['options_tab', '=', 'typography']],
				],

				'item_content_typography' => [
					'type' => 'typography',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_CONTENT'),
					'depends' => [['options_tab', '=', 'typography']],
				],

				// color
				'item_title_color' => [
					'type'   => 'color',
					'title'	 => Text::_('COM_SPPAGEBUILDER_GLOBAL_TITLE'),
					'depends' => [['options_tab', '=', 'color']],
				],

				'item_content_color' => [
					'type'   => 'color',
					'title'	 => Text::_('COM_SPPAGEBUILDER_GLOBAL_CONTENT'),
					'depends' => [['options_tab', '=', 'color']],
				],

				// button
				'button_typography' => [
					'type' => 'typography',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_TYPOGRAPHY'),
					'depends' => [['options_tab', '=', 'button']],
				],

				'button_type' => [
					'type'   => 'select',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_STYLE'),
					'desc'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_STYLE_DESC'),
					'values' => [
						'default'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_DEFAULT'),
						'primary'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_PRIMARY'),
						'secondary' => Text::_('COM_SPPAGEBUILDER_GLOBAL_SECONDARY'),
						'success'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_SUCCESS'),
						'info'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_INFO'),
						'warning'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_WARNING'),
						'danger'    => Text::_('COM_SPPAGEBUILDER_GLOBAL_DANGER'),
						'dark'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_DARK'),
						'link'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_LINK'),
						'custom'    => Text::_('COM_SPPAGEBUILDER_GLOBAL_CUSTOM'),
					],
					'std'   => 'custom',
					'depends' => [['options_tab', '=', 'button']],
				],

				'link_button_padding_bottom' => [
					'type'    => 'slider',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_PADDING_BOTTOM'),
					'max'     => 100,
					'depends' => [
						['button_type', '=', 'link'],
						['options_tab', '=', 'button']
					],
				],

				'button_appearance' => [
					'type'   => 'select',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_APPEARANCE'),
					'desc'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_APPEARANCE_DESC'),
					'values' => [
						''         => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_APPEARANCE_FLAT'),
						'gradient' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_APPEARANCE_GRADIENT'),
						'outline'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_APPEARANCE_OUTLINE'),
					],
					'depends' => [
						['options_tab', '=', 'button'],
						['button_type', '!=', 'link'],
					],
				],

				'button_shape' => [
					'type'   => 'select',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SHAPE'),
					'desc'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SHAPE_DESC'),
					'values' => [
						'rounded' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SHAPE_ROUNDED'),
						'square'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SHAPE_SQUARE'),
						'round'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SHAPE_ROUND'),
					],
					'std'   => 'rounded',
					'depends' => [
						['options_tab', '=', 'button'],
						['button_type', '!=', 'link'],
					],
				],

				'button_size' => [
					'type'   => 'select',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SIZE'),
					'desc'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SIZE_DESC'),
					'values' => [
						''    => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SIZE_DEFAULT'),
						'lg'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SIZE_LARGE'),
						'xlg' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SIZE_XLARGE'),
						'sm'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SIZE_SMALL'),
						'xs'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SIZE_EXTRA_SAMLL'),
						'custom' => Text::_('COM_SPPAGEBUILDER_GLOBAL_CUSTOM'),
					],
					'depends' => [['options_tab', '=', 'button']],
				],

				'button_padding' => [
					'type'    => 'padding',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_PADDING'),
					'responsive' => true,
					'std' => ['xl' => '8px 22px 10px 22px', 'lg' => '', 'md' => '', 'sm' => '', 'xs' => ''],
					'depends' => [
						['button_size', '=', 'custom'],
						['options_tab', '=', 'button']
					],
				],

				'button_block' => [
					'type'   => 'select',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_BLOCK'),
					'values' => [
						''               => Text::_('JNO'),
						'sppb-btn-block' => Text::_('JYES'),
					],
					'depends' => [
						['options_tab', '=', 'button'],
						['button_type', '!=', 'link'],
					],
				],

				'button_colors_tab' => [
					'type'   => 'buttons',
					'std'    => 'normal',
					'values' => [
						['label' => Text::_('COM_SPPAGEBUILDER_GLOBAL_NORMAL'), 'value' => 'normal'],
						['label' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HOVER'), 'value' => 'hover'],
					],
					'std'   => 'typography',
					'tabs'    => true,
					'depends' => [
						['options_tab', '=', 'button'],
						['button_type', '=', 'custom'],
					],
				],

				'button_color' => [
					'type' => 'color',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
					'std' => '#FFFFFF',
					'depends' => [
						['options_tab', '=', 'button'],
						['button_type', '=', 'custom'],
						['button_colors_tab', '!=', 'hover'],
					],
				],

				'button_color_hover' => [
					'type' => 'color',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
					'std' => '#FFFFFF',
					'depends' => [
						['options_tab', '=', 'button'],
						['button_type', '=', 'custom'],
						['button_colors_tab', '=', 'hover'],
					],
				],

				'button_background_color' => [
					'type'    => 'color',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND'),
					'std'     => '#3366FF',
					'depends' => [
						['options_tab', '=', 'button'],
						['button_type', '=', 'custom'],
						['button_appearance', '!=', 'gradient'],
						['button_colors_tab', '!=', 'hover'],
					],
				],

				'button_background_color_hover' => [
					'type'    => 'color',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND'),
					'std'     => '#0037DD',
					'depends' => [
						['options_tab', '=', 'button'],
						['button_type', '=', 'custom'],
						['button_appearance', '!=', 'gradient'],
						['button_colors_tab', '=', 'hover'],
					],
				],

				'button_background_gradient' => [
					'type' => 'gradient',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND'),
					'std' => [
						"color"  => "#3366FF",
						"color2" => "#0037DD",
						"deg" => "45",
						"type" => "linear"
					],
					'depends' => [
						['options_tab', '=', 'button'],
						['button_type', '=', 'custom'],
						['button_appearance', '=', 'gradient'],
						['button_colors_tab', '!=', 'hover'],
					],
				],

				'button_background_gradient_hover' => [
					'type' => 'gradient',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND'),
					'std' => [
						"color"  => "#0037DD",
						"color2" => "#3366FF",
						"deg" => "45",
						"type" => "linear"
					],
					'depends' => [
						['options_tab', '=', 'button'],
						['button_type', '=', 'custom'],
						['button_appearance', '=', 'gradient'],
						['button_colors_tab', '=', 'hover'],
					],
				],

				// Link style button
				'link_button_colors_tab' => [
					'type'   => 'buttons',
					'std'    => 'normal',
					'values' => [
						['label' => Text::_('COM_SPPAGEBUILDER_GLOBAL_NORMAL'), 'value' => 'normal'],
						['label' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HOVER'), 'value' => 'hover'],
					],
					'std'   => 'typography',
					'tabs'    => true,
					'depends' => [
						['options_tab', '=', 'button'],
						['button_type', '=', 'link'],
					],
				],

				'link_button_color' => [
					'type'    => 'color',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
					'depends' => [
						['options_tab', '=', 'button'],
						['button_type', '=', 'link'],
						['link_button_colors_tab', '!=', 'hover'],
					],
				],

				'link_button_border_width' => [
					'type'    => 'slider',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_WIDTH'),
					'max'     => 30,
					'depends' => [
						['options_tab', '=', 'button'],
						['button_type', '=', 'link'],
						['link_button_colors_tab', '!=', 'hover'],
					],
				],

				'link_button_border_color' => [
					'type'    => 'color',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_COLOR'),
					'std'     => '',
					'depends' => [
						['options_tab', '=', 'button'],
						['button_type', '=', 'link'],
						['link_button_colors_tab', '!=', 'hover'],
					],
				],

				//Link Hover
				'link_button_hover_color' => [
					'type'    => 'color',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
					'std'     => '',
					'depends' => [
						['options_tab', '=', 'button'],
						['button_type', '=', 'link'],
						['link_button_colors_tab', '=', 'hover'],
					],
				],

				'link_button_border_hover_color' => [
					'type'    => 'color',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_COLOR'),
					'std'     => '',
					'depends' => [
						['options_tab', '=', 'button'],
						['button_type', '=', 'link'],
						['link_button_colors_tab', '=', 'hover'],
					],
				],
			],
		],
	],
]);
