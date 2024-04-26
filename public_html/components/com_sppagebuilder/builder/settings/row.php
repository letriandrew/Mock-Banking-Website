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

$row_settings = [
    'style' => [
        'basic_options' => [
            'title' => Text::_("COM_SPPAGEBUILDER_GLOBAL_BASIC"),
            'fields' => [
                'admin_label' => [
                    'type' => 'text',
                    'title' => Text::_('COM_SPPAGEBUILDER_ADMIN_LABEL'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_ADMIN_LABEL_DESC'),
                    'std' => ''
                ],
                'fit_columns' => [
                    'type' => 'checkbox',
                    'title' => Text::_("COM_SPPAGEBUILDER_GLOBAL_FILL_COLUMNS"),
                    'desc' => Text::_("COM_SPPAGEBUILDER_GLOBAL_FILL_COLUMNS_DESC"),
                    'std' => ['xl' => true, 'sm' => false],
                    'responsive' => true
                ],
                'color' => [
                    'type' => 'color',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_TEXT_COLOR'),
                ],
            ]
        ],

        'background_options' => [
            'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND'),
            'fields' => [
                'background_type' => [
                    'type' => 'buttons',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND'),
                    'std' => 'none',
                    'values' => [
                        ['label' => ['icon' => 'ban'], 'value' => 'none'],
                        ['label' => 'Color', 'value' => 'color'],
                        ['label' => 'Image', 'value' => 'image'],
                        ['label' => 'Gradient', 'value' => 'gradient'],
                        ['label' => 'Video', 'value' => 'video'],
                    ],
                ],

                'background_color' => [
                    'type' => 'color',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_COLOR'),
                    'depends' => [
                        ['background_type', '!=', 'none'],
                        ['background_type', '!=', 'image'],
                        ['background_type', '!=', 'video'],
                        ['background_type', '!=', 'gradient'],
                    ]
                ],

                'background_gradient' => [
                    'type' => 'gradient',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_GRADIENT'),
                    'std' => [
                        "color" => "#00c6fb",
                        "color2" => "#005bea",
                        "deg" => "45",
                        "type" => "linear"
                    ],
                    'depends' => [
                        ['background_type', '=', 'gradient']
                    ]
                ],

                'background_image' => [
                    'type' => 'media',
                    'format' => 'image',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_IMAGE'),
                    'std' => [
                        'src' => ''
                    ],
                    'show_input' => true,
                    'depends' => [
                        ['background_type', '=', 'image']
                    ]
                ],

                'background_parallax' => [
                    'type' => 'checkbox',
                    'title' => Text::_('COM_SPPAGEBUILDER_ROW_BACKGROUND_PARALLAX_ENABLE'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_ROW_BACKGROUND_PARALLAX_ENABLE_DESC'),
                    'std' => '0',
                    'depends' => [
                        ['background_type', '=', 'image']
                    ]
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
                        ['background_image', '!=', '']
                    ]
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
                        ['background_image', '!=', '']
                    ]
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
                        ['background_image', '!=', '']
                    ],
                    'responsive' => true,
                    'std' => ['unit' => 'px']
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
                    'std' => 'fixed',
                    'depends' => [
                        ['background_type', '=', 'image'],
                        ['background_image', '!=', '']
                    ]
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
                        ['background_image', '!=', '']
                    ]
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
                        ['background_image', '!=', '']
                    ],
                    'responsive' => true,
                    'std' => ['unit' => 'px']
                ],

                'background_position_custom_y' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_BACKGROUND_CUSTOM_POSITION_Y'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_BACKGROUND_CUSTOM_POSITION_Y_DESC'),
                    'unit' => true,
                    'depends' => [
                        ['background_position', '=', 'custom'],
                        ['background_image', '!=', '']
                    ],
                    'max' => 1000,
                    'min' => -1000,
                    'responsive' => true,
                    'std' => ['unit' => 'px']
                ],

                'external_background_video' => [
                    'type' => 'checkbox',
                    'title' => Text::_('COM_SPPAGEBUILDER_ROW_BACKGROUND_EXTERNAL_VIDEO_ENABLE'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_ROW_BACKGROUND_EXTERNAL_VIDEO_ENABLE_DESC'),
                    'std' => 0,
                    'depends' => [['background_type', '=', 'video']]
                ],

                'background_video_mp4' => [
                    'type' => 'media',
                    'format' => 'video',
                    'title' => Text::_('COM_SPPAGEBUILDER_ROW_BACKGROUND_VIDEO_MP4'),
                    'depends' => [
                        ['background_type', '=', 'video'],
                        ['external_background_video', '!=', 1]
                    ],
                ],

                'background_video_ogv' => [
                    'type' => 'media',
                    'format' => 'video',
                    'title' => Text::_('COM_SPPAGEBUILDER_ROW_BACKGROUND_VIDEO_OGV'),
                    'depends' => [
                        ['background_type', '=', 'video'],
                        ['external_background_video', '!=', 1]
                    ],
                    'std' => [
                        'src' => ''
                    ]
                ],

                'background_external_video' => [
                    'type' => 'text',
                    'title' => Text::_('COM_SPPAGEBUILDER_ROW_BACKGROUND_VIDEO_YOUTUBE_VIMEO'),
                    'depends' => [
                        ['background_type', '=', 'video'],
                        ['external_background_video', '=', 1]
                    ]
                ],

                'video_loop' => [
                    'type' => 'checkbox',
                    'title' => Text::_('COM_SPPAGEBUILDER_ROW_VIDEO_LOOP'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_ROW_VIDEO_LOOP_DESC'),
                    'std' => 1,
                    'depends' => [
                        ['background_type', '=', 'video'],
                        ['external_background_video', '!=', 1],
                    ]
                ],
            ]
        ],

        'row_spacing_options' => [
            'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_SPACING'),
            'fields' => [
                'padding' => [
                    'type' => 'padding',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_PADDING'),
                    'std' => ['xl' => '75px 0px 75px 0px', 'lg' => '', 'md' => '', 'sm' => '', 'xs' => ''],
                    'responsive' => true
                ],

                'margin' => [
                    'type' => 'margin',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_MARGIN'),
                    'std' => ['xl' => '0px 0px 0px 0px', 'lg' => '', 'md' => '', 'sm' => '', 'xs' => ''],
                    'responsive' => true
                ],
            ]
        ],

        'overlay_options' => [
            'title' => 'Overlay',
            'fields' => [
                'overlay_type' => [
                    'type' => 'buttons',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_OVERLAY'),
                    'hideTitle' => true,
                    'std' => 'overlay_none',
                    'values' => [
                        ['label' => 'None', 'value' => 'overlay_none'],
                        ['label' => 'Color', 'value' => 'overlay_color'],
                        ['label' => 'Gradient', 'value' => 'overlay_gradient'],
                        ['label' => 'Pattern', 'value' => 'overlay_pattern']
                    ],
                    'depends' => [
                        ['background_type', '!=', 'none'],
                        ['background_type', '!=', 'color'],
                        ['background_type', '!=', 'gradient'],
                    ],
                ],

                'overlay' => [
                    'type' => 'color',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_OVERLAY'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_OVERLAY_DESC'),
                    'depends' => [
                        ['background_type', '!=', 'none'],
                        ['background_type', '!=', 'color'],
                        ['background_type', '!=', 'gradient'],
                        ['overlay_type', '=', 'overlay_color'],
                    ]
                ],

                'gradient_overlay' => [
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
                        ['background_type', '!=', 'none'],
                        ['background_type', '!=', 'color'],
                        ['background_type', '!=', 'gradient'],
                        ['overlay_type', '=', 'overlay_gradient'],
                    ]
                ],

                'pattern_overlay' => [
                    'type' => 'media',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_OVERLAY_PATTERN'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_OVERLAY_PATTERN_DESC'),
                    'std' => '',
                    'depends' => [
                        ['background_type', '!=', 'none'],
                        ['background_type', '!=', 'color'],
                        ['background_type', '!=', 'gradient'],
                        ['overlay_type', '=', 'overlay_pattern'],
                    ]
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
                    ]
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
                        ['overlay_type', '!=', 'overlay_none']
                    ],
                ],
            ]
        ],

        'column_alignment_options' => [
            'title' => Text::_("COM_SPPAGEBUILDER_GLOBAL_COLUMN_ALIGNMENT"),
            'fields' => [
                'columns_align_center' => [
                    'type' => 'checkbox',
                    'title' => Text::_('COM_SPPAGEBUILDER_ROW_COLUMNS_ALIGN_CENTER'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_ROW_COLUMNS_ALIGN_CENTER_DESC'),
                    'std' => 0,
                    'is_header' => 1
                ],

                'columns_content_alignment' => [
                    'type' => 'alignment',
                    'title' => Text::_('COM_SPPAGEBUILDER_ADDON_GLOBAL_CONTENT_ALIGNMENT'),
                    'hideTitle' => true,
                    'std' => 'center',
                    'flex' => true,
                    'vertical' => true,
                    'depends' => [
                        ['columns_align_center', '!=', 0]
                    ],
                ],
            ]
        ],

        'screen_mode' => [
            'title' => Text::_('COM_SPPAGEBUILDER_FULLSCREEN'),
            'fields' => [
                'fullscreen' => [
                    'type' => 'checkbox',
                    'title' => Text::_('COM_SPPAGEBUILDER_FULLSCREEN'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_FULLSCREEN_DESC'),
                    'std' => 0,
                    'is_header' => 1
                ],

                'container_width' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_CONTAINER_WIDTH'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_CONTAINER_WIDTH_DESC'),
                    'max' => 1600,
                    'min' => 1200,
                    'depends' => [
                        ['fullscreen', '=', 0],
                    ],
                ],
            ]
        ],

        'gap_options' => [
            'title' => Text::_('COM_SPPAGEBUILDER_ROW_NO_GUTTER'),
            'fields' => [
                'no_gutter' => [
                    'type' => 'checkbox',
                    'title' => Text::_('COM_SPPAGEBUILDER_ROW_NO_GUTTER'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_ROW_NO_GUTTER_DESC'),
                    'std' => 0,
                    'is_header' => 1
                ],

                'columns_gap' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLUMNS_GAP'),
                    'max' => 100,
                    'min' => 0,
                    'unit' => true,
                    'responsive' => true,
                    'depends' => [
                        ['no_gutter', '=', 0],
                    ],
                ],
            ]
        ],

        'width_options' => [
            'title' => Text::_('COM_SPPAGEBUILDER_ROW_WIDTH_SETTINGS'),
            'fields' => [
                'row_width' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_WIDTH'),
                    'unit' => true,
                    'max' => 3000,
                    'min' => 0,
                    'responsive' => true,
                    'std' => ['unit' => 'px']
                ],

                'row_max_width' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_MAX_WIDTH'),
                    'unit' => true,
                    'max' => 3000,
                    'min' => 0,
                    'responsive' => true,
                    'std' => ['unit' => 'px']
                ],

                'row_min_width' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_MIN_WIDTH'),
                    'unit' => true,
                    'max' => 3000,
                    'min' => 0,
                    'responsive' => true,
                    'std' => ['unit' => 'px']
                ],
            ]
        ],

        'height_options' => [
            'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HEIGHT_OPTIONS'),
            'fields' => [
                'section_height_option' => [
                    'type' => 'select',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HEIGHT_OPTIONS'),
                    'values' => [
                        'win-height' => Text::_('COM_SPPAGEBUILDER_ROW_WIN_HEIGHT'),
                        'height' => Text::_('COM_SPPAGEBUILDER_ROW_HEIGHT'),
                    ],
                ],

                'section_height' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HEIGHT'),
                    'depends' => [
                        ['section_height_option', '=', 'height'],
                    ],
                    'max' => 3000,
                    'responsive' => true,
                ],

                'section_min_height' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_MIN_HEIGHT'),
                    'max' => 3000,
                    'responsive' => true,
                ],

                'section_max_height' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_MAX_HEIGHT'),
                    'max' => 3000,
                    'responsive' => true,
                ],
            ]
        ],

        'border_options' => [
            'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER'),
            'fields' => [
                'row_border' => [
                    'type' => 'checkbox',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER'),
                    'std' => 0,
                    'is_header' => 1
                ],

                'row_border_width' => [
                    'type' => 'margin',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_WIDTH'),
                    'responsive' => true,
                    'depends' => [['row_border', '=', 1]]
                ],

                'row_border_style' => [
                    'type' => 'select',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_STYLE'),
                    'values' => [
                        'none' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE_NONE'),
                        'solid' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE_SOLID'),
                        'double' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE_DOUBLE'),
                        'dotted' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE_DOTTED'),
                        'dashed' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE_DASHED'),
                    ],
                    'inline' => true,
                    'std' => 'solid',
                    'depends' => [['row_border', '=', 1]]
                ],

                'row_border_color' => [
                    'type' => 'color',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
                    'depends' => [['row_border', '=', 1]]
                ],
            ]
        ],

        'top_shape_options' => [
            'title' => Text::_('COM_SPPAGEBUILDER_ROW_TOP_SHAPE'),
            'fields' => [
                'show_top_shape' => [
                    'type' => 'checkbox',
                    'title' => Text::_('COM_SPPAGEBUILDER_ROW_TOP_SHAPE'),
                    'std' => '',
                    'is_header' => 1
                ],

                'shape_name' => [
                    'type' => 'select',
                    'title' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE'),
                    'values' => [
                        'bell' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_BELL'),
                        'brushed' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_BRUSHED'),
                        'clouds-flat' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_CLOUDS_FLAT'),
                        'clouds-opacity' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_CLOUDS_OPACITY'),
                        'drip' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_DRIP'),
                        'hill' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_HILL'),
                        'hill-wave' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_HILL_WAVE'),
                        'line-wave' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_LINE_WAVE'),
                        'paper-torn' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_PAPER_TORN'),
                        'pointy-wave' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_POINTY_WAVE'),
                        'rocky-mountain' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_ROCKY_MOUNTAIN'),
                        'shaggy' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_SHAGGY'),
                        'single-wave' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_SINGLE_WAVE'),
                        'slope-opacity' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_SLOPE_OPACITY'),
                        'slope' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_SLOPE'),
                        'swirl' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_SWIRL'),
                        'wavy-opacity' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_WAVY_OPACITY'),
                        'waves3-opacity' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_WAVES3_OPACITY'),
                        'turning-slope' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_TURNING_SLOPE'),
                        'zigzag-sharp' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_ZIGZAG_SHARP'),
                    ],
                    'std' => 'clouds-flat',
                    'depends' => [
                        ['show_top_shape', '=', 1]
                    ]
                ],

                'shape_color' => [
                    'type' => 'color',
                    'title' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_COLOR'),
                    'std' => '#e5e5e5',
                    'depends' => [
                        ['show_top_shape', '=', 1]
                    ]
                ],

                'shape_width' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_WIDTH'),
                    'std' => [
                        'md' => 100,
                        'sm' => 100,
                        'xs' => 100
                    ],
                    'max' => 600,
                    'min' => 100,
                    'responsive' => true,
                    'depends' => [
                        ['show_top_shape', '=', 1]
                    ]
                ],

                'shape_height' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_HEIGHT'),
                    'std' => '',
                    'max' => 600,
                    'responsive' => true,
                    'depends' => [
                        ['show_top_shape', '=', 1]
                    ]
                ],

                'shape_flip' => [
                    'type' => 'checkbox',
                    'title' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_FLIP'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_FLIP_DESC'),
                    'std' => false,
                    'depends' => [
                        ['show_top_shape', '=', 1],
                        ['shape_name', '!=', 'bell'],
                        ['shape_name', '!=', 'zigzag-sharp'],
                    ]
                ],

                'shape_invert' => [
                    'type' => 'checkbox',
                    'title' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_INVERT'),
                    'std' => false,
                    'depends' => [
                        ['show_top_shape', '=', 1],
                        ['shape_name', '!=', 'clouds-opacity'],
                        ['shape_name', '!=', 'slope-opacity'],
                        ['shape_name', '!=', 'waves3-opacity'],
                        ['shape_name', '!=', 'paper-torn'],
                        ['shape_name', '!=', 'hill-wave'],
                        ['shape_name', '!=', 'line-wave'],
                        ['shape_name', '!=', 'swirl'],
                        ['shape_name', '!=', 'wavy-opacity'],
                        ['shape_name', '!=', 'zigzag-sharp'],
                        ['shape_name', '!=', 'brushed'],
                    ]
                ],

                'shape_to_front' => [
                    'type' => 'checkbox',
                    'title' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_TO_FRONT'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_TO_FRONT_DESC'),
                    'std' => false,
                    'depends' => [
                        ['show_top_shape', '=', 1]
                    ]
                ],
            ]
        ],

        'bottom_shape_options' => [
            'title' => Text::_('COM_SPPAGEBUILDER_ROW_BOTTOM_SHAPE'),
            'fields' => [
                'show_bottom_shape' => [
                    'type' => 'checkbox',
                    'title' => Text::_('COM_SPPAGEBUILDER_ROW_BOTTOM_SHAPE'),
                    'std' => '',
                    'is_header' => 1
                ],

                'bottom_shape_name' => [
                    'type' => 'select',
                    'title' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE'),
                    'values' => [
                        'bell' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_BELL'),
                        'brushed' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_BRUSHED'),
                        'clouds-flat' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_CLOUDS_FLAT'),
                        'clouds-opacity' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_CLOUDS_OPACITY'),
                        'drip' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_DRIP'),
                        'hill' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_HILL'),
                        'hill-wave' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_HILL_WAVE'),
                        'line-wave' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_LINE_WAVE'),
                        'paper-torn' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_PAPER_TORN'),
                        'pointy-wave' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_POINTY_WAVE'),
                        'rocky-mountain' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_ROCKY_MOUNTAIN'),
                        'shaggy' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_SHAGGY'),
                        'single-wave' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_SINGLE_WAVE'),
                        'slope-opacity' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_SLOPE_OPACITY'),
                        'slope' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_SLOPE'),
                        'swirl' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_SWIRL'),
                        'wavy-opacity' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_WAVY_OPACITY'),
                        'waves3-opacity' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_WAVES3_OPACITY'),
                        'turning-slope' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_TURNING_SLOPE'),
                        'zigzag-sharp' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_ZIGZAG_SHARP'),
                    ],
                    'std' => 'clouds-opacity',
                    'depends' => [['show_bottom_shape', '=', 1]]
                ],

                'bottom_shape_color' => [
                    'type' => 'color',
                    'title' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_COLOR'),
                    'std' => '#e5e5e5',
                    'depends' => [['show_bottom_shape', '=', 1]]
                ],

                'bottom_shape_width' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_WIDTH'),
                    'std' => [
                        'xl' => 100
                    ],
                    'max' => 600,
                    'min' => 100,
                    'responsive' => true,
                    'depends' => [['show_bottom_shape', '=', 1]]
                ],

                'bottom_shape_height' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_HEIGHT'),
                    'std' => '',
                    'max' => 600,
                    'responsive' => true,
                    'depends' => [['show_bottom_shape', '=', 1]]
                ],

                'bottom_shape_flip' => [
                    'type' => 'checkbox',
                    'title' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_FLIP'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_FLIP_DESC'),
                    'std' => false,
                    'depends' => [
                        ['show_bottom_shape', '=', 1],
                        ['shape_name', '!=', 'bell'],
                        ['shape_name', '!=', 'zigzag-sharp'],
                    ]
                ],

                'bottom_shape_invert' => [
                    'type' => 'checkbox',
                    'title' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_INVERT'),
                    'std' => false,
                    'depends' => [
                        ['show_bottom_shape', '=', 1],
                        ['bottom_shape_name', '!=', 'clouds-opacity'],
                        ['bottom_shape_name', '!=', 'slope-opacity'],
                        ['bottom_shape_name', '!=', 'waves3-opacity'],
                        ['bottom_shape_name', '!=', 'paper-torn'],
                        ['bottom_shape_name', '!=', 'hill-wave'],
                        ['bottom_shape_name', '!=', 'line-wave'],
                        ['bottom_shape_name', '!=', 'swirl'],
                        ['shape_name', '!=', 'wavy-opacity'],
                        ['shape_name', '!=', 'zigzag-sharp'],
                        ['shape_name', '!=', 'brushed'],
                    ]
                ],

                'bottom_shape_to_front' => [
                    'type' => 'checkbox',
                    'title' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_TO_FRONT'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_ROW_SHAPE_TO_FRONT_DESC'),
                    'std' => false,
                    'depends' => [
                        ['show_bottom_shape', '=', 1]
                    ]
                ],
            ]
        ],

        'overflow_options' => [
            'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_OVERFLOW'),
            'fields' => [
                'section_overflow_x' => [
                    'type' => 'select',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_OVERFLOW_X'),
                    'values' => [
                        'auto' => 'Auto',
                        'hidden' => 'Hidden',
                        'initial' => 'Initial',
                        'scroll' => 'Scroll',
                        'visible' => 'Visible',
                    ],
                    'inline' => true
                ],

                'section_overflow_y' => [
                    'type' => 'select',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_OVERFLOW_Y'),
                    'values' => [
                        'auto' => 'Auto',
                        'hidden' => 'Hidden',
                        'initial' => 'Initial',
                        'scroll' => 'Scroll',
                        'visible' => 'Visible',
                    ],
                    'inline' => true
                ],
            ]
        ],

        'row_misc_options' => [
            'title' => Text::_("COM_SPPAGEBUILDER_GLOBAL_MISCELLANEOUS"),
            'fields' => [
                'row_border_radius' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_RADIUS'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_RADIUS_ROW_DESC'),
                    'max' => 500,
                    'responsive' => true
                ],

                'row_boxshadow' => [
                    'type' => 'boxshadow',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BOXSHADOW'),
                    'std' => '0 0 0 0 #FFFFFF'
                ],
                'id' => [
                    'type' => 'text',
                    'title' => Text::_('COM_SPPAGEBUILDER_SECTION_ID'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_SECTION_ID_DESC')
                ],

                'class' => [
                    'type' => 'text',
                    'title' => Text::_('COM_SPPAGEBUILDER_CSS_CLASS'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_CSS_CLASS_DESC')
                ],
            ]
        ],
    ],
    'title' => [
        'title_options' => [
            'title' => Text::_("COM_SPPAGEBUILDER_GLOBAL_TITLE_CONTENT"),
            'fields' => [
                'title' => [
                    'type' => 'textarea',
                    'title' => Text::_('COM_SPPAGEBUILDER_SECTION_TITLE'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_SECTION_TITLE_DESC'),
                    'css' => 'min-height: 80px;',
                ],

                'heading_selector' => [
                    'type' => 'headings',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HEADINGS'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HEADINGS_DESC'),
                    'std' => 'h3',
                    'headingsOnly' => true,
                    'depends' => [['title', '!=', '']],
                ],

                'title_fontsize' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_TITLE_FONT_SIZE'),
                    'std' => '',
                    'depends' => [['title', '!=', '']],
                    'responsive' => true,
                    'max' => 500
                ],

                'title_fontweight' => [
                    'type' => 'text',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_TITLE_FONT_WEIGHT'),
                    'std' => '',
                    'depends' => [['title', '!=', '']],
                ],

                'title_text_color' => [
                    'type' => 'color',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_TITLE_TEXT_COLOR'),
                    'depends' => [['title', '!=', '']],
                ],

                'title_margin_top' => [
                    'type' => 'number',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_MARGIN_TOP'),
                    'placeholder' => '10',
                    'depends' => [['title', '!=', '']],
                    'responsive' => true
                ],

                'title_margin_bottom' => [
                    'type' => 'number',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_MARGIN_BOTTOM'),
                    'placeholder' => '10',
                    'depends' => [['title', '!=', '']],
                    'responsive' => true
                ],

                'subtitle' => [
                    'type' => 'textarea',
                    'title' => Text::_('COM_SPPAGEBUILDER_SECTION_SUBTITLE'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_SECTION_SUBTITLE_DESC'),
                    'css' => 'min-height: 120px;',
                ],

                'subtitle_fontsize' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_SUB_TITLE_FONT_SIZE'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_SUB_TITLE_FONT_SIZE_DESC'),
                    'responsive' => true,
                    'depends' => [
                        ['subtitle', '!=', ''],
                    ],
                ],

                'title_position' => [
                    'type' => 'alignment',
                    'title' => Text::_('COM_SPPAGEBUILDER_TITLE_SUBTITLE_POSITION'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_TITLE_SUBTITLE_POSITION_DESC'),
                    'std' => 'center',
                ],
            ]
        ]
    ],
    'responsive' => [
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
                    'depends' => [['enable_animation', '!=', 0]]
                ],

                'animation_separator' => [
                    'type' => 'separator',
                    'depends' => [['enable_animation', '!=', 0], ['animation', '!=', '']]
                ],

                'animationduration' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_ANIMATION_DURATION'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_ANIMATION_DURATION_DESC'),
                    'min' => 0,
                    'max' => 10000,
                    'std' => '300',
                    'info'  => 'ms',
                    'depends' => [['enable_animation', '!=', 0], ['animation', '!=', '']]
                ],

                'animationdelay' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_ANIMATION_DELAY'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_ANIMATION_DELAY_DESC'),
                    'std' => '0',
                    'min' => 0,
                    'max' => 10000,
                    'info'  => 'ms',
                    'depends' => [['enable_animation', '!=', 0], ['animation', '!=', '']]
                ],
            ]
        ]
    ],
];
