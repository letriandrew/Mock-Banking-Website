<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;

$addon_global_settings = [
	'style' => [
		'global_colors' => [
			'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
			'fields' => [
				'global_text_color' => [
					'type' => 'color',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_TEXT')
				],

				'global_link_color' => [
					'type' => 'color',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_LINK')
				],

				'global_link_hover_color' => [
					'type' => 'color',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_LINK_HOVER')
				],
			]
		],
		'global_spacing' => [
			'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_SPACING'),
			'fields' => [
				'global_padding' => [
					'type' => 'padding',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_PADDING'),
					'std' => '',
					'responsive' => true
				],

				'global_margin' => [
					'type' => 'margin',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_MARGIN'),
					'std' => '',
					'responsive' => true
				],
			]
		],

		'global_background_options' => [
			'title' => Text::_("COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_OPTIONS"),
			'fields' => [
				'global_background_type' => [
					'type' => 'buttons',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_ENABLE_BACKGROUND_OPTIONS'),
					'std' => 'none',
					'values' => [
						[
							'label' => 'None',
							'value' => 'none'
						],
						[
							'label' => 'Color',
							'value' => 'color'
						],
						[
							'label' => 'Image',
							'value' => 'image'
						],
						[
							'label' => 'Gradient',
							'value' => 'gradient'
						],
					],
				],

				'global_background_color' => [
					'type' => 'color',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_COLOR'),
					'depends' => [
						['global_background_type', '!=', 'none'],
						['global_background_type', '!=', 'video'],
						['global_background_type', '!=', 'gradient'],
					],
				],

				'global_background_gradient' => [
					'type' => 'gradient',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_GRADIENT'),
					'std' => [
						"color" => "#00c6fb",
						"color2" => "#005bea",
						"deg" => "45",
						"type" => "linear"
					],
					'depends' => [
						['global_background_type', '=', 'gradient']
					],
				],

				'global_background_image' => [
					'type' => 'media',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_IMAGE'),
					'depends' => [
						['global_background_type', '=', 'image']
					],
					'std' => [
						'src' => '',
					],
				],

				'global_background_repeat' => [
					'type' => 'select',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_REPEAT'),
					'values' => [
						'no-repeat' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_NO_REPEAT'),
						'repeat' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_REPEAT_ALL'),
						'repeat-x' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_REPEAT_HORIZONTALLY'),
						'repeat-y' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_REPEAT_VERTICALLY'),
						'inherit' => Text::_('COM_SPPAGEBUILDER_GLOBAL_INHERIT')
					],
					'std' => 'no-repeat',
					'depends' => [
						['global_background_type', '=', 'image'],
						['global_background_image', '!=', '']
					],
				],

				'global_background_size' => [
					'type' => 'select',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_SIZE'),
					'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_SIZE_DESC'),
					'values' => [
						'cover' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_SIZE_COVER'),
						'contain' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_SIZE_CONTAIN'),
						'inherit' => Text::_('COM_SPPAGEBUILDER_GLOBAL_INHERIT')
					],
					'std' => 'cover',
					'depends' => [
						['global_background_type', '=', 'image'],
						['global_background_image', '!=', '']
					],
				],

				'global_background_attachment' => [
					'type' => 'select',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_ATTACHMENT'),
					'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_ATTACHMENT_DESC'),
					'values' => [
						'fixed' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_ATTACHMENT_FIXED'),
						'scroll' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_ATTACHMENT_SCROLL'),
						'inherit' => Text::_('COM_SPPAGEBUILDER_GLOBAL_INHERIT')
					],
					'std' => 'inherit',
					'depends' => [
						['global_background_type', '=', 'image'],
						['global_background_image', '!=', '']
					],
				],

				'global_background_position' => [
					'type' => 'select',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_POSITION'),
					'values' => [
						'0 0' => Text::_('COM_SPPAGEBUILDER_LEFT_TOP'),
						'0 50%' => Text::_('COM_SPPAGEBUILDER_LEFT_CENTER'),
						'0 100%' => Text::_('COM_SPPAGEBUILDER_LEFT_BOTTOM'),
						'50% 0' => Text::_('COM_SPPAGEBUILDER_CENTER_TOP'),
						'50% 50%' => Text::_('COM_SPPAGEBUILDER_CENTER_CENTER'),
						'50% 100%' => Text::_('COM_SPPAGEBUILDER_CENTER_BOTTOM'),
						'100% 0' => Text::_('COM_SPPAGEBUILDER_RIGHT_TOP'),
						'100% 50%' => Text::_('COM_SPPAGEBUILDER_RIGHT_CENTER'),
						'100% 100%' => Text::_('COM_SPPAGEBUILDER_RIGHT_BOTTOM')
					],
					'std' => '50% 50%',
					'depends' => [
						['global_background_type', '=', 'image'],
						['global_background_image', '!=', '']
					],
				],

				'global_overlay_separator' => [
					'type' => 'separator',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_OVERLAY_OPTIONS'),
					'depends' => [
						['global_background_type', '=', 'image']
					],
				],

				'global_use_overlay' => [
					'type' => 'checkbox',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_ENABLE_BACKGROUND_OVERLAY'),
					'std' => 0,
					'depends' => [
						['global_background_type', '=', 'image']
					],
				],

				'global_overlay_type' => [
					'type' => 'buttons',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_OVERLAY_CHOOSE'),
					'std' => 'overlay_none',
					'values' => [
						[
							'label' => 'None',
							'value' => 'overlay_none'
						],
						[
							'label' => 'Color',
							'value' => 'overlay_color'
						],
						[
							'label' => 'Gradient',
							'value' => 'overlay_gradient'
						],
						[
							'label' => 'Pattern',
							'value' => 'overlay_pattern'
						],
					],
					'depends' => [
						['global_use_overlay', '!=', 0],
					],
				],

				'global_background_overlay' => [
					'type' => 'color',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_OVERLAY'),
					'depends' => [
						['global_background_type', '=', 'image'],
						['global_use_overlay', '=', 1],
						['global_overlay_type', '=', 'overlay_color']
					],
				],

				'global_gradient_overlay' => [
					'type' => 'gradient',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_OVERLAY_GRADIENT'),
					'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_OVERLAY_GRADIENT_DESC'),
					'std' => [
						"color" => "rgba(127, 0, 255, 0.8)",
						"color2" => "rgba(225, 0, 255, 0.7)",
						"deg" => "45",
						"type" => "linear"
					],
					'depends' => [
						['global_background_type', '=', 'image'],
						['global_use_overlay', '=', 1],
						['global_overlay_type', '=', 'overlay_gradient']
					],
				],

				'global_pattern_overlay' => [
					'type' => 'media',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_OVERLAY_PATTERN'),
					'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_OVERLAY_PATTERN_DESC'),
					'std' => [
						'src' => '',
					],
					'depends' => [
						['global_background_type', '=', 'image'],
						['global_use_overlay', '=', 1],
						['global_overlay_type', '=', 'overlay_pattern']
					],
				],

				'global_overlay_pattern_color' => [
					'type' => 'color',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_OVERLAY_PATTERN_COLOR'),
					'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_OVERLAY_PATTERN_COLOR_DESC'),
					'std' => '',
					'depends' => [
						['global_background_type', '=', 'image'],
						['global_use_overlay', '=', 1],
						['global_overlay_type', '=', 'overlay_pattern']
					],
				],

				'blend_mode' => [
					'type' => 'select',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BLEND_MODE'),
					'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BLEND_MODE_DESC'),
					'values' => [
						'normal' => 'Normal',
						'color' => 'Color',
						'color-burn' => 'Color Burn',
						'color-dodge' => 'Color Dodge',
						'darken' => 'Darken',
						'difference' => 'Difference',
						'exclusion' => 'Exclusion',
						'hard-light' => 'Hard Light',
						'hue' => 'Hue',
						'lighten' => 'Lighten',
						'luminosity' => 'Luminosity',
						'multiply' => 'Multiply',
						'overlay' => 'Overlay',
						'saturation' => 'Saturation',
						'screen' => 'Screen',
						'soft-light' => 'Soft Light'
					],
					'std' => 'normal',
					'depends' => [
						['global_background_type', '=', 'image'],
						['global_use_overlay', '=', 1]
					],
				],
			]
		],

		'global_border' => [
			'title' => Text::_("COM_SPPAGEBUILDER_GLOBAL_USE_BORDER"),
			'fields' => [
				'global_user_border' => [
					'type' => 'checkbox',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER'),
					'std' => 0,
					'is_header' => 1,
				],
				'global_border_width' => [
					'type' => 'slider',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_WIDTH'),
					'std' => ['xl' => '', 'lg' => '', 'md' => '', 'sm' => '', 'xs' => ''],
					'depends' => [['global_user_border', '=', 1]],
					'responsive' => true
				],

				'global_boder_style' => [
					'type' => 'select',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_STYLE'),
					'values' => [
						'none' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE_NONE'),
						'solid' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE_SOLID'),
						'double' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE_DOUBLE'),
						'dotted' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE_DOTTED'),
						'dashed' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE_DASHED')
					],
					'depends' => [['global_user_border', '=', 1]]
				],

				'global_border_color' => [
					'type' => 'color',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
					'depends' => [
						['global_user_border', '=', 1]
					],
				],
			]
		],

		'global_animation' => [
			'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_ANIMATION'),
			'fields' => [
				'global_use_animation' => [
					'type' => 'checkbox',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_USE_ANIMATION'),
					'std' => 0,
					'is_header' => 1
				],

				'global_animation' => [
					'type' => 'animation',
					'title' => Text::_('COM_SPPAGEBUILDER_ANIMATION'),
					'desc' => Text::_('COM_SPPAGEBUILDER_ANIMATION_DESC'),
					'depends' => [
						['global_use_animation', '=', 1]
					],
				],

				'global_animationduration' => [
					'type' => 'slider',
					'title' => Text::_('COM_SPPAGEBUILDER_ANIMATION_DURATION'),
					'desc' => Text::_('COM_SPPAGEBUILDER_ANIMATION_DURATION_DESC'),
					'std' => '300',
					'min' => 0,
					'max' => 10000,
					'depends' => [
						['global_use_animation', '=', 1],
						['global_animation', '!=', ''],
					],
				],

				'global_animationdelay' => [
					'type' => 'slider',
					'title' => Text::_('COM_SPPAGEBUILDER_ANIMATION_DELAY'),
					'desc' => Text::_('COM_SPPAGEBUILDER_ANIMATION_DELAY_DESC'),
					'std' => '0',
					'min' => 0,
					'max' => 10000,
					'depends' => [
						['global_use_animation', '=', 1],
						['global_animation', '!=', ''],
					],
				],
			]
		],

		'global_style_misc' => [
			'title' => Text::_("COM_SPPAGEBUILDER_GLOBAL_MISCELLANEOUS"),
			'fields' => [
				'global_border_radius' => [
					'type' => 'slider',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_RADIUS'),
					'std' => ['xl' => '', 'lg' => '', 'md' => '', 'sm' => '', 'xs' => ''],
					'max' => 100,
					'responsive' => true,
				],

				'global_boxshadow' => [
					'type' => 'boxshadow',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BOXSHADOW'),
					'std' => '0 0 0 0 #FFFFFF'
				],
				'class' => [
					'type' => 'text',
					'title' => Text::_('COM_SPPAGEBUILDER_ADDON_CLASS'),
					'desc' => Text::_('COM_SPPAGEBUILDER_ADDON_CLASS_DESC'),
					'std' => ''
				],

				'global_custom_css' => [
					'type' => 'codeeditor',
					'syntax' => 'css',
					'title' => Text::_('COM_SPPAGEBUILDER_CUSTOM_CSS'),
					'std' => ''
				],
			]
		],
	],

	'advanced' => [
		'global_positioning' => [
			'title' => Text::_("COM_SPPAGEBUILDER_GLOBAL_CUSTOM_POSITION"),
			'fields' => [
				'global_custom_position' => [
					'type' => 'checkbox',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_CUSTOM_POSITION'),
					'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_CUSTOM_POSITION_DESC'),
					'std' => 0,
					'is_header' => 1
				],
				'global_seclect_position' => [
					'type' => 'select',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_SELECT_POSITION'),
					'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_SELECT_POSITION_DESC'),
					'depends' => [
						['global_custom_position', '=', 1],
					],
					'values' => [
						'absolute' => Text::_('COM_SPPAGEBUILDER_GLOBAL_POSITION_ABSOLUTE'),
						'fixed' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_ATTACHMENT_FIXED'),
						'relative' => Text::_('COM_SPPAGEBUILDER_GLOBAL_POSITION_RELATIVE')
					],
					'std' => 'relative'
				],

				'global_addon_position_left' => [
					'type' => 'slider',
					'title' => Text::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_FROM_LEFT'),
					'depends' => [
						['global_custom_position', '=', 1]
					],
					'unit' => true,
					'max' => 2000,
					'min' => -2000,
					'responsive' => true,
					'std' => ['unit' => 'px']
				],

				'global_addon_position_top' => [
					'type' => 'slider',
					'title' => Text::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_FROM_TOP'),
					'depends' => [
						['global_custom_position', '=', 1]
					],
					'unit' => true,
					'max' => 1000,
					'min' => -1000,
					'responsive' => true,
					'std' => ['unit' => 'px']
				],

				'global_addon_z_index' => [
					'type' => 'slider',
					'title' => Text::_('COM_SPPAGEBUILDER_ADDON_ZINDEX'),
					'desc' => Text::_('COM_SPPAGEBUILDER_ADDON_ZINDEX_DESC'),
					'depends' => [
						['global_custom_position', '=', 1]
					],
					'max' => 1000,
					'min' => 1
				],
				'global_section_z_index' => [
					'type' => 'slider',
					'title' => Text::_('COM_SPPAGEBUILDER_SECTION_ZINDEX'),
					'depends' => [
						['global_custom_position', '=', 1]
					],
					'max' => 1000,
					'min' => 1
				],

			]
		],
		'global_width' => [
			'title' => Text::_("COM_SPPAGEBUILDER_GLOBAL_WIDTH"),
			'fields' => [
				'use_global_width' => [
					'type' => 'checkbox',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_USE_WIDTH'),
					'std' => '0',
					'id_header' => 1
				],

				'global_width' => [
					'type' => 'slider',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_WIDTH'),
					'max' => 100,
					'responsive' => true,
					'depends' => [
						['use_global_width', '=', 1]
					],
				],
			]
		],
		'global_responsive' => [
			'title' => Text::_("COM_SPPAGEBUILDER_GLOBAL_RESPONSIVE"),
			'fields' => [
				'hidden_xl' => [
					'type' => 'checkbox',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_XL'),
					'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_XL_DESC'),
					'std' => '0'
				],

				'hidden_lg' => [
					'type' => 'checkbox',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_LG'),
					'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_LG_DESC'),
					'std' => '0'
				],
				'hidden_md' => [
					'type' => 'checkbox',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_MD'),
					'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_MD_DESC'),
					'std' => '0'
				],

				'hidden_sm' => [
					'type' => 'checkbox',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_SM'),
					'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_SM_DESC'),
					'std' => '0'
				],

				'hidden_xs' => [
					'type' => 'checkbox',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_XS'),
					'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_XS_DESC'),
					'std' => '0'
				],
			]
		],
		'global_advanced_misc' => [
			'title' => Text::_("COM_SPPAGEBUILDER_GLOBAL_MISCELLANEOUS"),
			'fields' => [
				'acl' => [
					'type' => 'accesslevel',
					'title' => Text::_('COM_SPPAGEBUILDER_ACCESS'),
					'desc' => Text::_('COM_SPPAGEBUILDER_ACCESS_DESC'),
					'placeholder' => '',
					'std' 			=> '',
					'multiple' => true
				],

				'admin_label' => [
					'type' => 'text',
					'title' => Text::_('COM_SPPAGEBUILDER_ADDON_ADMIN_LABEL'),
					'desc' => Text::_('COM_SPPAGEBUILDER_ADDON_ADMIN_LABEL_DESC'),
					'std' => '',
				],
			]
		]
	],

	'interaction' => [
		'while_scroll_options' => [
			'title' => Text::_('COM_SPPAGEBUILDER_INTERACTION_WHILTE_SCROLL_VIEW'),
			'fields' => [
				'while_scroll_view' => [
					'type' => 'interaction_view',
					'title' => Text::_('COM_SPPAGEBUILDER_INTERACTION_WHILTE_SCROLL_VIEW'),
					"desc" => Text::_('COM_SPPAGEBUILDER_INTERACTION_WHILTE_SCROLL_VIEW_DESC'),
					'attr' => [
						'enable_while_scroll_view' => [
							'type' => 'checkbox',
							'title' => Text::_('COM_SPPAGEBUILDER_INTERACTION_WHILTE_SCROLL_VIEW_TITLE'),
							'desc' => Text::_('COM_SPPAGEBUILDER_INTERACTION_WHILTE_SCROLL_VIEW_TITLE_DESC'),
							'std' => 0,
							'is_header' => 1
						],
						'scrolling_options' => [
							'type' => 'select',
							'title' => Text::_('COM_SPPAGEBUILDER_INTERACTION_WHILTE_SCROLL_SCROLLING_OPTIONS'),
							'values' => [
								'fullpage' => Text::_('COM_SPPAGEBUILDER_INTERACTION_WHILTE_SCROLL_SCROLLING_OPTIONS_FULLPAGE'),
								'viewport' => Text::_('COM_SPPAGEBUILDER_INTERACTION_WHILTE_SCROLL_SCROLLING_OPTIONS_VIEWPORT'),
							],
							'std' => 'fullpage',
							'depends' => [
								['enable_while_scroll_view', '=', 1]
							],
						],
						'on_scroll_actions' => [
							'type' => 'timeline',
							'title' => Text::_('COM_SPPAGEBUILDER_INTERACTION_WHILTE_SCROLL_ACTION_TITLE'),
							'desc' => Text::_('COM_SPPAGEBUILDER_INTERACTION_WHILTE_SCROLL_ACTION_TITLE_DESC'),
							'depends' => [['enable_while_scroll_view', '=', 1]],
							'std' => [
								[
									'id' => "b3fdc1c1e6bfde5942ea",
									'index' => 0,
									'keyframe' => 0,
									'name' => 'move',
									'property' => [
										'x' => '0',
										'y' => '-100',
										'z' => '0'
									],
									'range' => [
										'max' => 500,
										'min' => -500,
										'stop' => 1
									],
									'single' => true,
									'title' => "Move"
								],

								[
									'id' => "936e0225e6dc8edfba7d",
									'index' => 1,
									'keyframe' => 100,
									'name' => 'move',
									'property' => [
										'x' => 0,
										'y' => 0,
										'z' => 0
									],
									'range' => [
										'max' => 500,
										'min' => -500,
										'stop' => 1
									],
									'single' => true,
									'title' => "Move"
								],
							],
							'options' => [
								[
									'name' => 'move',
									'title' => Text::_('COM_SPPAGEBUILDER_INTERACTION_WHILTE_SCROLL_ACTION_MOVE'),
									'property' => [
										'x' => '0',
										'y' => '0',
										'z' => '0'
									],
									'range' => [
										'max' => 500,
										'min' => -500,
										'step' => 1
									],
									'warning_message' => Text::_('COM_SPPAGEBUILDER_INTERACTION_WHILTE_SCROLL_ACTION_MOVE_WARNING'),
								],
								[
									'name' => 'scale',
									'title' => Text::_('COM_SPPAGEBUILDER_INTERACTION_WHILTE_SCROLL_ACTION_SCALE'),
									'property' => [
										'x' => '1',
										'y' => '1',
										'z' => '1'
									],
									'range' => [
										'max' => 2,
										'min' => 0,
										'step' => 0.1
									],
									'warning_message' => Text::_('COM_SPPAGEBUILDER_INTERACTION_WHILTE_SCROLL_ACTION_SCALE_WARNING'),
								],
								[
									'name' => 'rotate',
									'title' => Text::_('COM_SPPAGEBUILDER_INTERACTION_WHILTE_SCROLL_ACTION_ROTATE'),
									'property' => [
										'x' => '0',
										'y' => '0',
										'z' => '0'
									],
									'range' => [
										'max' => 180,
										'min' => -180,
										'step' => 1
									],
									'warning_message' => Text::_('COM_SPPAGEBUILDER_INTERACTION_WHILTE_SCROLL_ACTION_ROTATE_WARNING'),
								],
								[
									'name' => 'skew',
									'title' => Text::_('COM_SPPAGEBUILDER_INTERACTION_WHILTE_SCROLL_ACTION_SKEW'),
									'property' => [
										'x' => '0',
										'y' => '0'
									],
									'range' => [
										'max' => 80,
										'min' => -80,
										'step' => 1
									],
									'warning_message' => Text::_('COM_SPPAGEBUILDER_INTERACTION_WHILTE_SCROLL_ACTION_SKEW_WARNING'),
								],
								[
									'name' => 'opacity',
									'title' => Text::_('COM_SPPAGEBUILDER_INTERACTION_WHILTE_SCROLL_ACTION_OPACITY'),
									'property' => ['value' => '0'],
									'range' => [
										'max' => 1,
										'min' => 0,
										'step' => 0.1
									],
									'warning_message' => Text::_('COM_SPPAGEBUILDER_INTERACTION_WHILTE_SCROLL_ACTION_OPACITY_WARNING'),
								],
								[
									'name' => 'blur',
									'title' => Text::_('COM_SPPAGEBUILDER_INTERACTION_WHILTE_SCROLL_ACTION_BLUR'),
									'property' => ['value' => '0'],
									'range' => [
										'max' => 100,
										'min' => 0,
										'step' => 1
									],
									'warning_message' => Text::_('COM_SPPAGEBUILDER_INTERACTION_WHILTE_SCROLL_ACTION_BLUR_WARNING'),
								],
							],
						],

						'transition_origin_x' => [
							'type' => 'select',
							'title' => Text::_('COM_SPPAGEBUILDER_INTERACTION_WHILTE_SCROLL_TRANSITION_ANCHOR_X_TITLE'),
							'values' => [
								'left' => Text::_('COM_SPPAGEBUILDER_INTERACTION_WHILTE_SCROLL_TRANSITION_ANCHOR_X_LEFT'),
								'center' => Text::_('COM_SPPAGEBUILDER_GLOBAL_CENTER'),
								'right' => Text::_('COM_SPPAGEBUILDER_INTERACTION_WHILTE_SCROLL_TRANSITION_ANCHOR_X_RIGHT')
							],
							'std' => 'center',
							'depends' => [
								['enable_while_scroll_view', '=', 1]
							],
						],

						'transition_origin_y' => [
							'type' => 'select',
							'title' => Text::_('COM_SPPAGEBUILDER_INTERACTION_WHILTE_SCROLL_TRANSITION_ANCHOR_Y_TITLE'),
							'values' => [
								'top' => Text::_('COM_SPPAGEBUILDER_INTERACTION_WHILTE_SCROLL_TRANSITION_ANCHOR_Y_TOP'),
								'center' => Text::_('COM_SPPAGEBUILDER_GLOBAL_CENTER'),
								'bottom' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BOTTOM')
							],
							'std' => 'center',
							'depends' => [
								['enable_while_scroll_view', '=', 1]
							],
						],

						'enable_tablet' => [
							'type' => 'checkbox',
							'title' => Text::_('COM_SPPAGEBUILDER_INTERACTION_ENABLE_TABLET'),
							'desc' => Text::_('COM_SPPAGEBUILDER_INTERACTION_ENABLE_TABLET_DESC'),
							'depends' => [
								['enable_while_scroll_view', '=', 1]
							],
							'std' => 0,
						],

						'enable_mobile' => [
							'type' => 'checkbox',
							'title' => Text::_('COM_SPPAGEBUILDER_INTERACTION_ENABLE_MOBILE'),
							'desc' => Text::_('COM_SPPAGEBUILDER_INTERACTION_ENABLE_MOBILE_DESC'),
							'depends' => [['enable_while_scroll_view', '=', 1]],
							'std' => 0,
						],
					]
				],

			]

		],
		'on_mouse_movement_options' => [
			'title' => Text::_("COM_SPPAGEBUILDER_GLOBAL_MOVE_MOVEMENT"),
			'fields' => [
				'mouse_movement' => [
					'type' => 'interaction_view',
					'title' => Text::_('COM_SPPAGEBUILDER_INTERACTION_MOUSE_MOVEMENT'),
					"description" => Text::_('COM_SPPAGEBUILDER_INTERACTION_MOUSE_MOVEMENT_DESC'),
					'attr' => [
						'enable_tilt_effect' => [
							'type' => 'checkbox',
							'title' => Text::_('COM_SPPAGEBUILDER_INTERACTION_ENABLE_TILT_EFFECT_TITLE'),
							'desc' => Text::_('COM_SPPAGEBUILDER_INTERACTION_ENABLE_TILT_EFFECT_TITLE_DESC'),
							'std' => 0,
							'is_header' => 1
						],

						'mouse_tilt_direction' => [
							'type' => 'select',
							'title' => Text::_('COM_SPPAGEBUILDER_INTERACTION_ENABLE_TILT_EFFECT_DIRECTION_TITLE'),
							'values' => [
								'direct' => Text::_('COM_SPPAGEBUILDER_INTERACTION_ENABLE_TILT_EFFECT_DIRECTION_FORWARD'),
								'opposite' => Text::_('COM_SPPAGEBUILDER_INTERACTION_ENABLE_TILT_EFFECT_DIRECTION_OPPOSITE')
							],
							'std' => 'direct',
							'depends' => [
								['enable_tilt_effect', '=', 1]
							],
						],

						'mouse_tilt_speed' => [
							'type' => 'slider',
							'title' => Text::_('COM_SPPAGEBUILDER_INTERACTION_ENABLE_TILT_EFFECT_SPEED_TITLE'),
							'std' => '1',
							'min' => 1,
							'max' => 10,
							'step' => 0.5,
							'depends' => [
								['enable_tilt_effect', '=', 1]
							],
						],

						'mouse_tilt_max' => [
							'type' => 'slider',
							'title' => Text::_('COM_SPPAGEBUILDER_INTERACTION_ENABLE_TILT_EFFECT_MAX_TITLE'),
							'std' => '15',
							'min' => 5,
							'max' => 75,
							'step' => 5,
							'depends' => [
								['enable_tilt_effect', '=', 1]
							],
						],

						'enable_tablet' => [
							'type' => 'checkbox',
							'title' => Text::_('COM_SPPAGEBUILDER_INTERACTION_ENABLE_TABLET'),
							'desc' => Text::_('COM_SPPAGEBUILDER_INTERACTION_ENABLE_TABLET_DESC'),
							'depends' => [
								['enable_tilt_effect', '=', 1]
							],
							'std' => 0
						],

						'enable_mobile' => [
							'type' => 'checkbox',
							'title' => Text::_('COM_SPPAGEBUILDER_INTERACTION_ENABLE_MOBILE'),
							'desc' => Text::_('COM_SPPAGEBUILDER_INTERACTION_ENABLE_MOBILE_DESC'),
							'depends' => [
								['enable_tilt_effect', '=', 1]
							],
							'std' => 0
						],
					]
				]
			]
		]

	],
];
