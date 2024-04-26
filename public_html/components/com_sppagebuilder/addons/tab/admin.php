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
    'addon_name' => 'tab',
    'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_TAB'),
    'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_TAB_DESC'),
    'category'   => 'Content',
    'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M30 2.6H2a.4.4 0 00-.4.4v3c0 .22.18.4.4.4h28a.4.4 0 00.4-.4V3a.4.4 0 00-.4-.4zM2 1a2 2 0 00-2 2v3a2 2 0 002 2h28a2 2 0 002-2V3a2 2 0 00-2-2H2z" fill="currentColor"/><path fill-rule="evenodd" clip-rule="evenodd" d="M7.6 8.6v20.8h17.8V8.6H7.6zM7 7a1 1 0 00-1 1v22a1 1 0 001 1h19a1 1 0 001-1V8a1 1 0 00-1-1H7z" fill="currentColor"/><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M22 14a1 1 0 01-1 1h-9a1 1 0 110-2h9a1 1 0 011 1zM22 19a1 1 0 01-1 1h-9a1 1 0 110-2h9a1 1 0 011 1zM17 24a1 1 0 01-1 1h-4a1 1 0 110-2h4a1 1 0 011 1z" fill="currentColor"/><path d="M11 2a1 1 0 011-1h9a1 1 0 011 1v6H11V2z" fill="currentColor"/></svg>',
    'settings' => [
        'content' => [
            'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_CONTENT'),
            'fields' => [
                'sp_tab_item' => [
                    'title' => Text::_('COM_SPPAGEBUILDER_ADDON_TAB_ITEMS'),
                    'type' => 'repeatable',
                    'attr'  => [
                        'title' => [
                            'type'  => 'text',
                            'title' => Text::_('COM_SPPAGEBUILDER_ADDON_TAB_ITEM_TITLE'),
                            'desc'  => Text::_('COM_SPPAGEBUILDER_ADDON_TAB_ITEM_TITLE_DESC'),
                            'std'   => 'Tab'
                        ],

                        'subtitle' => [
                            'type'  => 'text',
                            'title' => Text::_('COM_SPPAGEBUILDER_ADDON_TAB_ITEM_SUBTITLE'),
                            'desc'  => Text::_('COM_SPPAGEBUILDER_ADDON_TAB_ITEM_SUBTITLE_DESC'),
                        ],

                        'image_or_icon' => [
                            'type'   => 'list',
                            'title'  => Text::_('COM_SPPAGEBUILDER_ADDON_TAB_ITEM_MEDIA'),
                            'values' => [
                                'icon' => 'Icon',
                                'image' => 'Image',
                            ],
                            'std'    => 'icon',
                        ],

                        'icon' => [
                            'type'    => 'icon',
                            'title'   => Text::_('COM_SPPAGEBUILDER_ADDON_TAB_ITEM_ICON'),
                            'desc'    => Text::_('COM_SPPAGEBUILDER_ADDON_TAB_ITEM_ICON_DESC'),
                            'std'     => '',
                            'depends' => [['image_or_icon', '=', 'icon']]
                        ],

                        'image' => [
                            'type'    => 'media',
                            'title'   => Text::_('COM_SPPAGEBUILDER_ADDON_TAB_ITEM_IMAGE'),
                            'desc'    => Text::_('COM_SPPAGEBUILDER_ADDON_TAB_ITEM_IMAGE_DESC'),
                            'depends' => [['image_or_icon', '=', 'image']],
                        ],

                        'content' => [
                            'type'  => 'builder',
                            'title' => Text::_('COM_SPPAGEBUILDER_ADDON_TAB_ITEM_TEXT'),
                            'desc'  => Text::_('COM_SPPAGEBUILDER_ADDON_TAB_ITEM_TEXT_DESC'),
                            'std'   => 'Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et.'
                        ],
                    ],
                ],

                'style' => [
                    'type'   => 'select',
                    'title'  => Text::_('COM_SPPAGEBUILDER_ADDON_TAB_STYLE'),
                    'desc'   => Text::_('COM_SPPAGEBUILDER_ADDON_TAB_STYLE_DESC'),
                    'values' => [
                        'modern' => Text::_('COM_SPPAGEBUILDER_ADDON_TAB_STYLE_MODERN'),
                        'tabs'   => Text::_('COM_SPPAGEBUILDER_ADDON_TAB_STYLE_DEFAULT'),
                        'pills'  => Text::_('COM_SPPAGEBUILDER_ADDON_TAB_STYLE_PILLS'),
                        'lines'  => Text::_('COM_SPPAGEBUILDER_ADDON_TAB_STYLE_LINES'),
                        'custom' => Text::_('COM_SPPAGEBUILDER_GLOBAL_CUSTOM'),
                    ],
                    'std' => 'modern',
                    'inline' => true,
                ],
            ],
        ],

        'nav' => [
            'title' => Text::_('COM_SPPAGEBUILDER_ADDON_TAB_NAV'),
            'depends' => [['style', '=', 'custom']],
            'fields' => [
                'nav_position' => [
                    'type'    => 'select',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_POSITION'),
                    'values' => [
                        'nav-top'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_TOP'),
                        'nav-bottom'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BOTTOM'),
                        'nav-left'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_LEFT'),
                        'nav-right' => Text::_('COM_SPPAGEBUILDER_GLOBAL_RIGHT'),
                    ],
                    'responsive' => true,
                    'std' => ['xl' => 'nav-top', 'lg' => '', 'md' => '', 'sm' => '', 'xs' => ''],
                ],

                'nav_gutter' => [
                    'type'       => 'slider',
                    'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_TAB_NAV_GUTTER'),
                    'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_TAB_NAV_GUTTER_DESC'),
                    'responsive' => true,
                    'max'        => 100,
                    'info'       => 'px',
                ],

                'nav_width' => [
                    'type'       => 'slider',
                    'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_WIDTH'),
                    'desc'       => Text::_('COM_SPPAGEBUILDER_NAVIGATION_WIDTH'),
                    'responsive' => true,
                    'min'        => 10,
                    'max'        => 100,
                    'info'       => '%',
                    'std'        => ['xl' => 30],
                    'depends' => [['nav_position', '!=', 'nav-top'], ['nav_position', '!=', 'nav-bottom']],
                ],

                'nav_spacing_separator' => [
                    'type'       => 'separator',
                ],

                'nav_padding' => [
                    'type'       => 'padding',
                    'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_PADDING'),
                    'responsive' => true,
                ],

                'nav_margin' => [
                    'type'       => 'margin',
                    'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_MARGIN'),
                    'responsive' => true,
                ],

                'nav_justified_separator' => [
                    'type'       => 'separator',
                ],

                'nav_justified' => [
                    'type'       => 'checkbox',
                    'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_TAB_NAV_JUSTIFIED'),
                    'responsive' => true,
                    'std'        => ['xl' => 0],
                    'depends' => [['nav_position', '!=', 'nav-left'], ['nav_position', '!=', 'nav-right']],
                ],

                'nav_text_align' => [
                    'type'    => 'alignment',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_ALIGNMENT'),
                    'responsive' => true,
                    'std' => ['xl' => 'left', 'lg' => '', 'md' => '', 'sm' => '', 'xs' => ''],
                ],

                // Typography
                'nav_typography_separator' => [
                    'type'       => 'separator',
                ],

                'nav_typography' => [
                    'type'     => 'typography',
                    'title'    => Text::_('COM_SPPAGEBUILDER_GLOBAL_TYPOGRAPHY'),
                    'fallbacks'   => [
                        'font' => 'nav_font_family',
                        'size' => 'nav_fontsize',
                        'line_height' => 'nav_lineheight',
                        'uppercase' => 'nav_font_style.uppercase',
                        'italic' => 'nav_font_style.italic',
                        'underline' => 'nav_font_style.underline',
                        'weight' => 'nav_font_style.weight',
                    ],
                ],

                // Color
                'nav_color_separator' => [
                    'type'       => 'separator',
                ],

                'nav_color_tab' => [
                    'type'   => 'buttons',
                    'values' => [
                        ['label' => Text::_('COM_SPPAGEBUILDER_GLOBAL_NORMAL'), 'value' => 'normal'],
                        ['label' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HOVER'), 'value' => 'hover'],
                        ['label' => Text::_('COM_SPPAGEBUILDER_GLOBAL_ACTIVE'), 'value' => 'active'],
                    ],
                    'std'    => 'normal',
                    'tabs'    => true,
                ],

                'nav_color' => [
                    'type'    => 'color',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
                    'std'     => '#020B53',
                    'depends' => [['nav_color_tab', '=', 'normal']],
                ],

                'nav_bg_color' => [
                    'type'    => 'color',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND'),
                    'std'     => '#F7F7F9',
                    'depends' => [['nav_color_tab', '=', 'normal']],
                ],

                'icon_color' => [
                    'type'    => 'color',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_ICON'),
                    'depends' => [['nav_color_tab', '=', 'normal']],
                ],

                'nav_border_separator' => [
                    'type'    => 'separator',
                    'depends' => [['nav_color_tab', '=', 'normal']],
                ],

                'nav_border' => [
                    'type'    => 'margin',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_WIDTH'),
                    'depends' => [['nav_color_tab', '=', 'normal']],
                ],

                'nav_border_color' => [
                    'type'    => 'color',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_COLOR'),
                    'depends' => [['nav_color_tab', '=', 'normal']],
                ],

                'nav_border_radius' => [
                    'type'    => 'slider',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_RADIUS'),
                    'min'     => 0,
                    'max'     => 100,
                    'responsive' => true,
                    'depends' => [['nav_color_tab', '=', 'normal']],
                ],

                // Hover
                'hover_tab_color' => [
                    'type'    => 'color',
                    'std'     => '#FFFFFF',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
                    'depends' => [['nav_color_tab', '=', 'hover']],
                ],

                'hover_tab_bg' => [
                    'type'    => 'color',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND'),
                    'std'     => '#3366FF',
                    'depends' => [['nav_color_tab', '=', 'hover']],
                ],

                'icon_color_hover' => [
                    'type'    => 'color',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_ICON'),
                    'depends' => [['nav_color_tab', '=', 'hover']],
                ],

                'hover_nav_border_separator' => [
                    'type'    => 'separator',
                    'depends' => [['nav_color_tab', '=', 'hover']],
                ],

                'hover_tab_border_width' => [
                    'type'    => 'margin',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_WIDTH'),
                    'depends' => [['nav_color_tab', '=', 'hover']],
                    'std'     => '',
                ],

                'hover_tab_border_color' => [
                    'type'    => 'color',
                    'std'     => '',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_COLOR'),
                    'depends' => [['nav_color_tab', '=', 'hover']],
                ],

                // Active
                'active_tab_color' => [
                    'type'    => 'color',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
                    'std'     => '#333333',
                    'depends' => [['nav_color_tab', '=', 'active']],
                ],

                'active_tab_bg' => [
                    'type'    => 'color',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND'),
                    'std'     => '#e5e5e5',
                    'depends' => [['nav_color_tab', '=', 'active']],
                ],

                'icon_color_active' => [
                    'type'    => 'color',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_ICON'),
                    'depends' => [['nav_color_tab', '=', 'active']],
                ],

                'active_nav_border_separator' => [
                    'type'    => 'separator',
                    'depends' => [['nav_color_tab', '=', 'active']],
                ],

                'active_tab_border_width' => [
                    'type'    => 'margin',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_WIDTH'),
                    'std'     => '',
                    'depends' => [['nav_color_tab', '=', 'active']],
                ],

                'active_tab_border_color' => [
                    'type'    => 'color',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_COLOR'),
                    'depends' => [['nav_color_tab', '=', 'active']],
                ],

                // Nav Media
                'image_or_icon_style_separator' => [
                    'type' => 'separator',
                ],

                'image_or_icon_style' => [
                    'type'   => 'select',
                    'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_MEDIA'),
                    'values' => [
                        'icon_style' => Text::_('COM_SPPAGEBUILDER_GLOBAL_ICON'),
                        'image_style' => Text::_('COM_SPPAGEBUILDER_GLOBAL_IMAGE'),
                    ],
                    'std'    => 'icon_style',
                ],

                'nav_icon_postion' => [
                    'type'    => 'select',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_POSITION'),
                    'values' => [
                        'top'    => Text::_('COM_SPPAGEBUILDER_GLOBAL_TOP'),
                        'right'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_RIGHT'),
                        'bottom' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BOTTOM'),
                        'left'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_LEFT'),
                    ],
                    'std' => 'left',
                    'inline' => true,
                    'depends' => [['image_or_icon_style', '=', 'icon_style']],
                ],

                'icon_fontsize' => [
                    'type'       => 'slider',
                    'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_SIZE'),
                    'responsive' => true,
                    'max'        => 400,
                    'depends' => [['image_or_icon_style', '=', 'icon_style']],
                ],

                'icon_margin' => [
                    'type'       => 'margin',
                    'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_MARGIN'),
                    'responsive' => true,
                    'depends' => [['image_or_icon_style', '=', 'icon_style']],
                ],

                //Image Style
                'nav_image_postion' => [
                    'type'    => 'select',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_POSITION'),
                    'values' => [
                        'top'    => Text::_('COM_SPPAGEBUILDER_GLOBAL_TOP'),
                        'right'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_RIGHT'),
                        'bottom' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BOTTOM'),
                        'left'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_LEFT'),
                    ],
                    'std' => 'left',
                    'inline' => true,
                    'depends' => [['image_or_icon_style', '=', 'image_style']],
                ],

                'image_height' => [
                    'type'       => 'slider',
                    'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_HEIGHT'),
                    'responsive' => true,
                    'max'        => 200,
                    'std'        => ['xl' => 30],
                    'depends' => [['image_or_icon_style', '=', 'image_style']],
                ],

                'image_width' => [
                    'type'       => 'slider',
                    'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_WIDTH'),
                    'responsive' => true,
                    'max'        => 200,
                    'std'        => ['xl' => 30],
                    'depends' => [['image_or_icon_style', '=', 'image_style']],
                ],

                'image_margin' => [
                    'type'       => 'margin',
                    'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_MARGIN'),
                    'responsive' => true,
                    'depends' => [['image_or_icon_style', '=', 'image_style']],
                ],
            ],
        ],

        'tab_content' => [
            'title' => Text::_('COM_SPPAGEBUILDER_ADDON_TAB_CONTENT'),
            'depends' => [['style', '=', 'custom']],
            'fields' => [
                'content_typography' => [
                    'type'     => 'typography',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_TYPOGRAPHY'),
                    'fallbacks'   => [
                        'font' => 'content_font_family',
                        'size' => 'content_fontsize',
                        'line_height' => 'content_lineheight',
                        'uppercase' => 'content_font_style.uppercase',
                        'italic' => 'content_font_style.italic',
                        'underline' => 'content_font_style.underline',
                        'weight' => 'content_font_style.weight',
                    ],
                ],

                'content_color' => [
                    'type'    => 'color',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
                ],

                'content_backround' => [
                    'type'    => 'color',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND'),
                    'std'     => '#F7F7F9',
                ],

                'content_border_separator' => [
                    'type'    => 'separator',
                ],

                'content_border' => [
                    'type'    => 'slider',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_WIDTH'),
                    'min'     => 0,
                    'max'     => 10,
                    'info'    => 'px',
                    'std'     => '0',
                ],

                'content_border_color' => [
                    'type'    => 'color',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_COLOR'),
                ],

                'content_border_radius' => [
                    'type'    => 'slider',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_RADIUS'),
                    'min'     => 0,
                    'max'     => 100,
                ],

                // Dimensions
                'content_separator' => [
                    'type'    => 'separator',
                ],

                'content_width' => [
                    'type'       => 'slider',
                    'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_WIDTH'),
                    'responsive' => true,
                    'min'        => 10,
                    'max'        => 100,
                    'info'       => '%',
                    'std'        => ['xl' => 100],
                ],

                'content_padding' => [
                    'type'       => 'padding',
                    'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_PADDING'),
                    'responsive' => true,
                ],

                'content_margin' => [
                    'type'       => 'margin',
                    'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_MARGIN'),
                    'responsive' => true,
                ],

                'content_box_shadow_separator' => [
                    'type'    => 'separator',
                ],

                'show_boxshadow' => [
                    'type'    => 'checkbox',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BOX_SHADOW'),
                    'std'     => 1,
                ],

                'shadow_color' => [
                    'type'    => 'color',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
                    'std'     => '#000',
                    'depends' => [['show_boxshadow', '=', 1]],
                ],

                'shadow_horizontal' => [
                    'type'    => 'slider',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BOX_SHADOW_HORIZONTAL'),
                    'max'     => 100,
                    'depends' => [['show_boxshadow', '=', 1]],
                ],

                'shadow_vertical' => [
                    'type'    => 'slider',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BOX_SHADOW_VERTICAL'),
                    'max'     => 100,
                    'depends' => [['show_boxshadow', '=', 1]],
                ],

                'shadow_blur' => [
                    'type'    => 'slider',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BOX_SHADOW_BLUR'),
                    'max'     => 100,
                    'depends' => [['show_boxshadow', '=', 1]],
                ],

                'shadow_spread' => [
                    'type'    => 'slider',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BOX_SHADOW_SPREAD'),
                    'max'     => 100,
                    'depends' => [['show_boxshadow', '=', 1]],
                ],
            ],
        ],

        'title' => [
            'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_TITLE'),
            'fields' => [
                'title' => [
                    'type'  => 'text',
                    'title' => Text::_('COM_SPPAGEBUILDER_ADDON_TITLE'),
                    'desc'  => Text::_('COM_SPPAGEBUILDER_ADDON_TITLE_DESC'),
                ],

                'heading_selector' => [
                    'type'   => 'headings',
                    'title'  => Text::_('COM_SPPAGEBUILDER_ADDON_HEADINGS'),
                    'desc'   => Text::_('COM_SPPAGEBUILDER_ADDON_HEADINGS_DESC'),
                    'std'   => 'h3',
                ],

                'title_typography' => [
                    'type'     => 'typography',
                    'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_TYPOGRAPHY'),
                    'fallbacks'   => [
                        'font' => 'title_font_family',
                        'size' => 'title_fontsize',
                        'line_height' => 'title_lineheight',
                        'letter_spacing' => 'title_letterspace',
                        'uppercase' => 'title_font_style.uppercase',
                        'italic' => 'title_font_style.italic',
                        'underline' => 'title_font_style.underline',
                        'weight' => 'title_font_style.weight',
                    ],
                ],

                'title_text_color' => [
                    'type'   => 'color',
                    'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
                ],

                'title_margin_separator' => [
                    'type' => 'separator',
                ],

                'title_margin_top' => [
                    'type'       => 'slider',
                    'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_MARGIN_TOP'),
                    'max'        => 400,
                    'responsive' => true,
                ],

                'title_margin_bottom' => [
                    'type'       => 'slider',
                    'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_MARGIN_BOTTOM'),
                    'max'        => 400,
                    'responsive' => true,
                ],
            ],
        ],
    ],
]);
