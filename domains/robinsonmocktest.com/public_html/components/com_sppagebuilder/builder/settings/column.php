<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

use Joomla\CMS\Language\Text;

//no direct access
defined('_JEXEC') or die('Restricted access');

$column_settings = [
    'style' => [
        'color' => [
            'title' => Text::_("COM_SPPAGEBUILDER_GLOBAL_BASIC"),
            'fields' => [
                'width' => [
                    'type' => 'slider',
                    'title' => 'Width',
                    'min' => 0,
                    'max' => 100,
                    'responsive' => true,
                    'step' => 0.1,
                    'default_unit' => '%',
                    'unit' => true,
                ],
                'color' => [
                    'type' => 'color',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_TEXT_COLOR'),
                ],
            ]
        ],
        'spacing' => [
            'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_SPACING'),
            'fields' => [
                'padding' => [
                    'type' => 'padding',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_PADDING'),
                    'responsive' => true,
                ],

                'margin' => [
                    'type' => 'margin',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_MARGIN'),
                    'responsive' => true,
                ],
            ]
        ],
        'background_options' => [
            'title' => Text::_("COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_OPTIONS"),
            'fields' => [
                'background_type' => [
                    'type' => 'buttons',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_ENABLE_BACKGROUND_OPTIONS'),
                    'std' => 'none',
                    'values' => [
                        ['label' => 'None', 'value' => 'none'],
                        ['label' => 'Color', 'value' => 'color'],
                        ['label' => 'Image', 'value' => 'image'],
                        ['label' => 'Gradient', 'value' => 'gradient'],
                    ],
                ],

                'background' => [
                    'type' => 'color',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_COLOR'),
                    'depends' => [
                        ['background_type', '!=', 'none'],
                        ['background_type', '!=', 'video'],
                        ['background_type', '!=', 'gradient'],
                    ],
                ],

                'background_gradient' => [
                    'type' => 'gradient',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_GRADIENT'),
                    'std' => [
                        "color" => "#00c6fb",
                        "color2" => "#005bea",
                        "deg" => "45",
                        "type" => "linear",
                    ],
                    'depends' => [
                        ['background_type', '=', 'gradient'],
                    ],
                ],

                'background_image' => [
                    'type' => 'media',
                    'format' => 'image',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_IMAGE'),
                    'std' => [
                        'src' => '',
                    ],
                    'depends' => [
                        ['background_type', '=', 'image'],
                    ],
                ],

                'background_repeat' => [
                    'type' => 'select',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_REPEAT'),
                    'values' => [
                        'no-repeat' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_NO_REPEAT'),
                        'repeat' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_REPEAT_ALL'),
                        'repeat-x' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_REPEAT_HORIZONTALLY'),
                        'repeat-y' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_REPEAT_VERTICALLY'),
                        'inherit' => Text::_('COM_SPPAGEBUILDER_GLOBAL_INHERIT'),
                    ],
                    'std' => 'no-repeat',
                    'depends' => [
                        ['background_type', '=', 'image'],
                        ['background_image', '!=', ''],
                    ],
                ],

                'background_size' => [
                    'type' => 'select',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_SIZE'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_SIZE_DESC'),
                    'values' => [
                        'cover' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_SIZE_COVER'),
                        'contain' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_SIZE_CONTAIN'),
                        'inherit' => Text::_('COM_SPPAGEBUILDER_GLOBAL_INHERIT'),
                        'custom' => Text::_('COM_SPPAGEBUILDER_GLOBAL_CUSTOM'),
                    ],
                    'std' => 'cover',
                    'depends' => [
                        ['background_type', '=', 'image'],
                        ['background_image', '!=', ''],
                    ],
                ],

                'background_size_custom' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_BACKROUND_CUSTOM_SIZE'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_BACKROUND_CUSTOM_SIZE_DESC'),
                    'unit' => true,
                    'max' => 3000,
                    'min' => 0,
                    'depends' => [
                        ['background_size', '=', 'custom'],
                        ['background_image', '!=', ''],
                    ],
                    'responsive' => true,
                    'std' => ['unit' => 'px'],
                ],

                'background_attachment' => [
                    'type' => 'select',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_ATTACHMENT'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_ATTACHMENT_DESC'),
                    'values' => [
                        'fixed' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_ATTACHMENT_FIXED'),
                        'scroll' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_ATTACHMENT_SCROLL'),
                        'inherit' => Text::_('COM_SPPAGEBUILDER_GLOBAL_INHERIT'),
                    ],
                    'std' => 'scroll',
                    'depends' => [
                        ['background_type', '=', 'image'],
                        ['background_image', '!=', ''],
                    ],
                ],

                'background_position' => [
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
                        '100% 100%' => Text::_('COM_SPPAGEBUILDER_RIGHT_BOTTOM'),
                        'custom' => Text::_('COM_SPPAGEBUILDER_GLOBAL_CUSTOM'),
                    ],
                    'std' => '0 0',
                    'depends' => [
                        ['background_type', '=', 'image'],
                        ['background_image', '!=', ''],
                    ],
                ],

                'background_position_custom_x' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_BACKGROUND_CUSTOM_POSITION_X'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_BACKGROUND_CUSTOM_POSITION_X_DESC'),
                    'unit' => true,
                    'max' => 1000,
                    'min' => -1000,
                    'depends' => [
                        ['background_position', '=', 'custom'],
                        ['background_image', '!=', ''],
                    ],
                    'responsive' => true,
                    'std' => ['unit' => 'px'],
                ],

                'background_position_custom_y' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_BACKGROUND_CUSTOM_POSITION_Y'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_BACKGROUND_CUSTOM_POSITION_Y_DESC'),
                    'unit' => true,
                    'depends' => [
                        ['background_position', '=', 'custom'],
                        ['background_image', '!=', ''],
                    ],
                    'max' => 1000,
                    'min' => -1000,
                    'responsive' => true,
                    'std' => ['unit' => 'px'],
                ],
            ]
        ],
        'overlay_options' => [
            'title' => Text::_("COM_SPPAGEBUILDER_GLOBAL_OVERLAY_OPTIONS"),
            'fields' => [
                'overlay_type' => [
                    'type' => 'buttons',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_OVERLAY'),
                    'std' => 'overlay_color',
                    'values' => [
                        ['label' => 'None', 'value' => 'overlay_none'],
                        ['label' => 'Color', 'value' => 'overlay_color'],
                        ['label' => 'Gradient', 'value' => 'overlay_gradient'],
                        ['label' => 'Pattern', 'value' => 'overlay_pattern'],
                    ],
                    'depends' => [
                        ['background_type', '!=', 'none'],
                        ['background_type', '!=', 'color'],
                        ['background_type', '!=', 'gradient'],
                    ],
                ],

                'overlay' => [
                    'type' => 'color',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_OVERLAY'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_OVERLAY_DESC'),
                    'depends' => [
                        ['background_type', '=', 'image'],
                        ['background_image', '!=', ''],
                        ['overlay_type', '=', 'overlay_color'],
                    ],
                ],

                'gradient_overlay' => [
                    'type' => 'gradient',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_OVERLAY_GRADIENT'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_OVERLAY_GRADIENT_DESC'),
                    'std' => [
                        "color" => "rgba(127, 0, 255, 0.8)",
                        "color2" => "rgba(225, 0, 255, 0.7)",
                        "deg" => "45",
                        "type" => "linear",
                    ],
                    'depends' => [
                        ['background_type', '!=', 'none'],
                        ['background_type', '!=', 'color'],
                        ['background_type', '!=', 'gradient'],
                        ['overlay_type', '=', 'overlay_gradient'],
                        ['background_image', '!=', ''],
                    ],
                ],

                'pattern_overlay' => [
                    'type' => 'media',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_OVERLAY_PATTERN'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_OVERLAY_PATTERN_DESC'),
                    'std' => [
                        'src' => '',
                    ],
                    'depends' => [
                        ['background_type', '!=', 'none'],
                        ['background_type', '!=', 'color'],
                        ['background_type', '!=', 'gradient'],
                        ['overlay_type', '=', 'overlay_pattern'],
                        ['background_image', '!=', ''],
                    ],
                ],

                'overlay_pattern_color' => [
                    'type' => 'color',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_OVERLAY_PATTERN_COLOR'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_OVERLAY_PATTERN_COLOR_DESC'),
                    'std' => '',
                    'depends' => [
                        ['background_type', '!=', 'none'],
                        ['background_type', '!=', 'color'],
                        ['background_type', '!=', 'gradient'],
                        ['overlay_type', '=', 'overlay_pattern'],
                        ['pattern_overlay', '!=', ''],
                        ['background_image', '!=', ''],
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
                        'soft-light' => 'Soft Light',
                    ],
                    'std' => 'normal',
                    'depends' => [
                        ['background_type', '!=', 'none'],
                        ['background_type', '!=', 'color'],
                        ['background_type', '!=', 'gradient'],
                        ['background_type', '!=', 'video'],
                        ['overlay_type', '!=', 'overlay_none'],
                    ],
                ],
            ]
        ],
        'height_options' => [
            'title' => Text::_("COM_SPPAGEBUILDER_GLOBAL_HEIGHTS"),
            'fields' => [
                'label_height' => [
                    'type' => 'header',
                    'title' => Text::_('COM_SPPAGEBUILDER_COLUMN_HEIGHT_SETTIINGS'),
                    'group' => [
                        'column_height',
                        'column_min_height',
                        'column_max_height',
                    ],
                ],

                'column_height' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HEIGHT'),
                    'max' => 3000,
                    'responsive' => true,
                ],

                'column_min_height' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_MIN_HEIGHT'),
                    'max' => 3000,
                    'responsive' => true,
                ],

                'column_max_height' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_MAX_HEIGHT'),
                    'max' => 3000,
                    'responsive' => true,
                ],
            ]
        ],
        'border_options' => [
            'title' => Text::_("COM_SPPAGEBUILDER_GLOBAL_USE_BORDER"),
            'fields' => [
                'use_border' => [
                    'type' => 'checkbox',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER'),
                    'std' => 0,
                    'is_header' => 1
                ],

                'border_width' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_WIDTH'),
                    'std' => '',
                    'depends' => ['use_border' => 1],
                    'responsive' => true,
                ],

                'boder_style' => [
                    'type' => 'select',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE'),
                    'values' => [
                        'none' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE_NONE'),
                        'solid' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE_SOLID'),
                        'double' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE_DOUBLE'),
                        'dotted' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE_DOTTED'),
                        'dashed' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE_DASHED'),
                    ],
                    'depends' => ['use_border' => 1],
                ],

                'border_color' => [
                    'type' => 'color',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_COLOR'),
                    'depends' => ['use_border' => 1],
                ],
            ]
        ],
        'column_misc' => [
            'title' => Text::_("COM_SPPAGEBUILDER_GLOBAL_MISCELLANEOUS"),
            'fields' => [
                'border_radius' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_RADIUS'),
                    'max' => 500,
                    'responsive' => true,
                ],

                'boxshadow' => [
                    'type' => 'boxshadow',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BOXSHADOW'),
                    'std' => '0 0 0 0 #FFFFFF',
                ],

                'items_align_center' => [
                    'type' => 'checkbox',
                    'title' => Text::_('COM_SPPAGEBUILDER_ROW_COLUMNS_ALIGN_CENTER'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_ROW_COLUMNS_ALIGN_CENTER_DESC'),
                    'std' => 0,
                    'group' => [
                        'items_content_alignment',
                    ],
                ],

                'items_content_alignment' => [
                    'type' => 'alignment',
                    'title' => Text::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_CONTENT_ALIGNMENT'),
                    'hideTitle' => true,
                    'flex' => true,
                    'vertical' => true,
                    'std' => 'center',
                    'depends' => [
                        ['items_align_center', '!=', 0],
                    ],
                ],

                'class' => [
                    'type' => 'text',
                    'title' => Text::_('COM_SPPAGEBUILDER_CSS_CLASS'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_CSS_CLASS_DESC'),
                ],
            ]
        ],
    ],
    'responsive' => [
        'column_ordering' => [
            'title' => Text::_("COM_SPPAGEBUILDER_GLOBAL_COLUMN_ORDERING"),
            'fields' => [
                'tablet_order_landscape' => [
                    'type' => 'select',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLUMN_ORDER_TABLET_LANDSCAPE'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLUMN_ORDER_TABLET_LANDSCAPE_DESC'),
                    'values' => [
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        '6' => '6',
                        '7' => '7',
                        '8' => '8',
                        '9' => '9',
                        '10' => '10',
                        '11' => '11',
                        '12' => '12',
                    ],
                    'std' => '',
                ],
                'tablet_order' => [
                    'type' => 'select',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLUMN_ORDER_TABLET'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLUMN_ORDER_TABLET_DESC'),
                    'values' => [
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        '6' => '6',
                        '7' => '7',
                        '8' => '8',
                        '9' => '9',
                        '10' => '10',
                        '11' => '11',
                        '12' => '12',
                    ],
                    'std' => '',
                ],
                'mobile_order_landscape' => [
                    'type' => 'select',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLUMN_ORDER_MOBILE_LANDSCAPE'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLUMN_ORDER_MOBILE_LANDSCAPE_DESC'),
                    'values' => [
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        '6' => '6',
                        '7' => '7',
                        '8' => '8',
                        '9' => '9',
                        '10' => '10',
                        '11' => '11',
                        '12' => '12',
                    ],
                    'std' => '',
                ],
                'mobile_order' => [
                    'type' => 'select',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLUMN_ORDER_MOBILE'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLUMN_ORDER_MOBILE_DESC'),
                    'values' => [
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                        '6' => '6',
                        '7' => '7',
                        '8' => '8',
                        '9' => '9',
                        '10' => '10',
                        '11' => '11',
                        '12' => '12',
                    ],
                    'std' => '',
                ],
            ]
        ],
        'visibility_options' => [
            'title' => Text::_("COM_SPPAGEBUILDER_GLOBAL_VISIBILITY"),
            'fields' => [
                'hidden_xl' => [
                    'type' => 'checkbox',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_XL'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_XL_DESC'),
                    'std' => '',
                ],
                'hidden_lg' => [
                    'type' => 'checkbox',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_LG'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_LG_DESC'),
                    'std' => '',
                ],
                'hidden_md' => [
                    'type' => 'checkbox',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_MD'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_MD_DESC'),
                    'std' => '',
                ],
                'hidden_sm' => [
                    'type' => 'checkbox',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_SM'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_SM_DESC'),
                    'std' => '',
                ],
                'hidden_xs' => [
                    'type' => 'checkbox',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_XS'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_XS_DESC'),
                    'std' => '',
                ],
            ]
        ]
    ],
    'animation' => [
        'animation_options' => [
            'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_ANIMATION'),
            'fields' => [
                'enable_animation' => [
                    'type' => 'checkbox',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_ANIMATION'),
                    'std' => '1',
                    'is_header' => 1
                ],

                'animation' => [
                    'type' => 'animation',
                    'title' => Text::_('COM_SPPAGEBUILDER_ANIMATION'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_ANIMATION_DESC'),
                    'depends' => [['enable_animation', '!=', 0]],
                ],

                'animation_separator' => [
                    'type' => 'separator',
                    'depends' => [['enable_animation', '!=', 0], ['animation', '!=', '']],
                ],

                'animationduration' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_ANIMATION_DURATION'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_ANIMATION_DURATION_DESC'),
                    'min' => 0,
                    'max' => 10000,
                    'std' => '300',
                    'info' => 'ms',
                    'depends' => [['enable_animation', '!=', 0], ['animation', '!=', '']],
                ],

                'animationdelay' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_ANIMATION_DELAY'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_ANIMATION_DELAY_DESC'),
                    'std' => '0',
                    'min' => 0,
                    'max' => 10000,
                    'info' => 'ms',
                    'depends' => [['enable_animation', '!=', 0], ['animation', '!=', '']],
                ],
            ]
        ]
    ],
];
