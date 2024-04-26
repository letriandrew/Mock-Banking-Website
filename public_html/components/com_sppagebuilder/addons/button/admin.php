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
    'type'       => 'general',
    'addon_name' => 'button',
    'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_BUTTON'),
    'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_BUTTON_DESC'),
    'category'   => 'Content',
    'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M0 8a4 4 0 014-4h24a4 4 0 014 4v9a4 4 0 01-4 4h-.5a1 1 0 110-2h.5a2 2 0 002-2V8a2 2 0 00-2-2H4a2 2 0 00-2 2v9a2 2 0 002 2h9a1 1 0 110 2H4a4 4 0 01-4-4V8z" fill="currentColor"/><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M16.004 12.669l1.526 9.46c.05.408.508.611.864.408l2.645-1.882 3.612 5.137c.508.661 2.034-.407 1.577-1.068l-3.612-5.188 2.696-1.832c.305-.254.305-.762-.05-.966l-8.393-4.68a.604.604 0 00-.865.611z" fill="currentColor"/></svg>',
    'settings' => [
        'button' => [
            'title' => Text::_('COM_SPPAGEBUILDER_ADDON_BUTTON'),
            'fields' => [
                'text' => [
                    'type' => 'text',
                    'title' => JText::_('COM_SPPAGEBUILDER_GLOBAL_TEXT'),
                    'desc' => JText::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_TEXT_DESC'),
                    'inline' => true,
                    'std'  => 'Button'
                ],

                'url' => [
                    'type'  => 'link',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_LINK'),
                    'mediaType' => 'attachment'
                ],

                'typography' => [
                    'type'      => 'typography',
                    'title'     => Text::_('COM_SPPAGEBUILDER_GLOBAL_TYPOGRAPHY'),
                    'fallbacks' => [
                        'font'           => 'font_family',
                        'size'           => 'fontsize',
                        'letter_spacing' => 'letterspace',
                        'uppercase'      => 'font_style.uppercase',
                        'italic'         => 'font_style.italic',
                        'underline'      => 'font_style.underline',
                        'weight'         => 'font_style.weight',
                    ],
                ],

                'type' => [
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
                    'std'    => 'custom',
                ],

                'link_button_padding_bottom' => [
                    'type'    => 'slider',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_PADDING_BOTTOM'),
                    'max'     => 100,
                    'depends' => [['type', '=', 'link']],
                ],

                'appearance' => [
                    'type'   => 'select',
                    'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_APPEARANCE'),
                    'desc'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_APPEARANCE_DESC'),
                    'values' => [
                        ''         => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_APPEARANCE_FLAT'),
                        'gradient' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_APPEARANCE_GRADIENT'),
                        'outline'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_APPEARANCE_OUTLINE'),
                    ],
                    'std'     => '',
                    'depends' => [['type', '!=', 'link']],
                ],

                'shape' => [
                    'type'   => 'select',
                    'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SHAPE'),
                    'desc'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SHAPE_DESC'),
                    'values' => [
                        'rounded' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SHAPE_ROUNDED'),
                        'square'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SHAPE_SQUARE'),
                        'round'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SHAPE_ROUND'),
                    ],
                    'std'   => 'rounded',
                    'depends' => [['type', '!=', 'link']],
                ],

                'size' => [
                    'type'   => 'select',
                    'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SIZE'),
                    'desc'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SIZE_DESC'),
                    'values' => [
                        ''       => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SIZE_DEFAULT'),
                        'lg'     => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SIZE_LARGE'),
                        'xlg'    => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SIZE_XLARGE'),
                        'sm'     => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SIZE_SMALL'),
                        'xs'     => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SIZE_EXTRA_SAMLL'),
                        'custom' => Text::_('COM_SPPAGEBUILDER_GLOBAL_CUSTOM'),
                    ],
                ],

                'button_padding' => [
                    'type'       => 'padding',
                    'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_PADDING'),
                    'std'        => '',
                    'responsive' => true,
                    'depends'    => [['size', '=', 'custom']],
                ],

                'block' => [
                    'type'   => 'select',
                    'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_BLOCK'),
                    'desc'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_BLOCK_DESC'),
                    'values' => [
                        ''               => Text::_('JNO'),
                        'sppb-btn-block' => Text::_('JYES'),
                    ],
                    'depends' => [['type', '!=', 'link']],
                ],

                'alignment' => [
                    'type'              => 'alignment',
                    'title'             => Text::_('COM_SPPAGEBUILDER_GLOBAL_ALIGNMENT'),
                    'responsive'        => true,
                    'available_options' => ['left', 'center', 'right'],
                    'std'               => [

                        'xl' => 'center',
                        'lg' => '',
                        'md' => '',
                        'sm' => '',
                        'xs' => '',
                    ]
                ]
            ],
        ],

        'icon' => [
            'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_ICON'),
            'fields' => [
                'icon' => [
                    'type'  => 'icon',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_ICON'),
                ],

                'icon_position' => [
                    'type'   => 'select',
                    'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_ICON_POSITION'),
                    'values' => [
                        'left'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_LEFT'),
                        'right' => Text::_('COM_SPPAGEBUILDER_GLOBAL_RIGHT'),
                    ],
                    'std' => 'left',
                ],

                'icon_margin' => [
                    'type'       => 'margin',
                    'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_ICON_MARGIN'),
                    'responsive' => true,
                    'std'        => ['xl' => '0px 0px 0px 0px', 'lg' => '', 'md' => '', 'sm' => '', 'xs' => ''],
                ],
            ]
        ],

        'style' => [
            'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_STYLE'),
            'depends' => [['type', '=', 'custom']],
            'fields' => [
                'button_style_state' => [
                    'type'   => 'radio',
                    'values' => [
                        'normal' => Text::_('COM_SPPAGEBUILDER_GLOBAL_NORMAL'),
                        'hover' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HOVER'),
                    ],
                    'std' => 'normal',
                ],

                'color' => [
                    'type'   => 'color',
                    'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
                    'std'    => '#FFFFFF',
                    'depends' => [['button_style_state', '=', 'normal']],
                ],

                'color_hover' => [
                    'type'   => 'color',
                    'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
                    'std'    => '#FFFFFF',
                    'depends' => [['button_style_state', '=', 'hover']],
                ],

                // Background
                'background_color' => [
                    'type'   => 'color',
                    'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_COLOR'),
                    'std'    => '#3366FF',
                    'depends' => [
                        ['button_style_state', '=', 'normal'],
                        ['appearance', '!=', 'gradient'],
                    ],
                ],

                'background_color_hover' => [
                    'type'    => 'color',
                    'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_COLOR'),
                    'std'     => '#0037DD',
                    'depends' => [
                        ['button_style_state', '=', 'hover'],
                        ['appearance', '!=', 'gradient'],
                    ],
                ],

                // Gradient Background
                'background_gradient' => [
                    'type' => 'gradient',
                    'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_COLOR'),
                    'std'  => [
                        "color"  => "#3366FF",
                        "color2" => "#0037DD",
                        "deg"    => "45",
                        "type"   => "linear"
                    ],
                    'depends' => [
                        ['button_style_state', '=', 'normal'],
                        ['appearance', '=', 'gradient'],
                    ],
                ],

                'background_gradient_hover' => [
                    'type'  => 'gradient',
                    'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_COLOR'),
                    'std'   => [
                        "color"  => "#0037DD",
                        "color2" => "#3366FF",
                        "deg"    => "45",
                        "type"   => "linear"
                    ],
                    'depends' => [
                        ['button_style_state', '=', 'hover'],
                        ['appearance', '=', 'gradient'],
                    ],
                ],
            ],
        ],

        'link_type_style' => [
            'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_STYLE'),
            'depends' => [['type', '=', 'link']],
            'fields' => [
                'button_link_style_state' => [
                    'type'   => 'radio',
                    'values' => [
                        'normal' => Text::_('Normal'),
                        'hover' => Text::_('Hover'),
                    ],
                    'std' => 'normal',
                ],

                'link_button_color' => [
                    'type'   => 'color',
                    'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
                    'std'    => '#3366FF',
                    'depends' => [
                        ['button_link_style_state', '=', 'normal'],
                        ['type', '=', 'link'],
                    ],
                ],

                'link_button_border_width' => [
                    'type'    => 'slider',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_WIDTH'),
                    'max'     => 10,
                    'std'     => 1,
                    'depends' => [
                        ['button_link_style_state', '=', 'normal'],
                        ['type', '=', 'link'],
                    ],
                ],

                'link_border_color' => [
                    'type'   => 'color',
                    'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_COLOR'),
                    'std'    => '#3366FF',
                    'depends' => [
                        ['button_link_style_state', '=', 'normal'],
                        ['type', '=', 'link'],
                    ],
                ],

                'link_button_hover_color' => [
                    'type'   => 'color',
                    'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
                    'std'    => '#0037DD',
                    'depends' => [
                        ['button_link_style_state', '=', 'hover'],
                        ['type', '=', 'link'],
                    ],
                ],

                'link_button_border_hover_color' => [
                    'type'   => 'color',
                    'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_COLOR'),
                    'std'    => '#0037DD',
                    'depends' => [
                        ['button_link_style_state', '=', 'hover'],
                        ['type', '=', 'link'],
                    ],
                ],
            ],
        ],
    ],
]);
