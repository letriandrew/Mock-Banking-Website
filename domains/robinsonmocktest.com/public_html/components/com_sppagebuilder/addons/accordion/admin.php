<?php

/**
 * @author JoomShaper https://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;

SpAddonsConfig::addonConfig([
    'type'       => 'repeatable',
    'addon_name' => 'accordion',
    'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_ACCORDION'),
    'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_ACCORDION_DESC'),
    'category'   => 'Content',
    'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><g opacity=".5" fill-rule="evenodd" clip-rule="evenodd" fill="currentColor"><path d="M32 3c0 .552-.428 1-.956 1H12.956C12.428 4 12 3.552 12 3s.428-1 .956-1h18.088c.528 0 .956.448.956 1zM32 8c0 .552-.428 1-.956 1H12.956C12.428 9 12 8.552 12 8s.428-1 .956-1h18.088c.528 0 .956.448.956 1zM24 13c0 .552-.436 1-.975 1h-10.05A.988.988 0 0112 13c0-.552.437-1 .975-1h10.05c.539 0 .975.448.975 1zM32 24c0 .552-.428 1-.956 1H12.956c-.528 0-.956-.448-.956-1s.428-1 .956-1h18.088c.528 0 .956.448.956 1zM24 29c0 .552-.436 1-.975 1h-10.05A.988.988 0 0112 29c0-.552.437-1 .975-1h10.05c.539 0 .975.448.975 1z"/></g><path d="M.54 2h6.92c.48 0 .72.51.381.81L4.381 5.86a.59.59 0 01-.761 0L.159 2.81c-.34-.3-.1-.81.38-.81zM.54 23h6.92c.48 0 .72.51.381.81l-3.46 3.051a.59.59 0 01-.761 0L.159 23.81c-.34-.3-.1-.81.38-.81z" fill="currentColor"/></svg>',
    'settings' => [
        'content' => [
            'title' => Text::_('COM_SPPAGEBUILDER_ADDON_ACCORDION_ITEMS'),
            'fields' => [
                'sp_accordion_item' => [
                    'type'  => 'repeatable',
                    'title' => Text::_('COM_SPPAGEBUILDER_ADDON_ACCORDION_ITEMS'),
                    'attr'  => [
                        'title' => [
                            'type'  => 'text',
                            'title' => Text::_('COM_SPPAGEBUILDER_ADDON_ACCORDION_TITLE'),
                            'desc'  => Text::_('COM_SPPAGEBUILDER_ADDON_ACCORDION_TITLE_DESC'),
                            'std'   => 'Accordion Title',
                        ],

                        'icon' => [
                            'type'  => 'icon',
                            'title' => Text::_('COM_SPPAGEBUILDER_ADDON_ACCORDION_ICON'),
                            'desc'  => Text::_('COM_SPPAGEBUILDER_ADDON_ACCORDION_ICON_DESC'),
                        ],

                        'content' => [
                            'type'  => 'builder',
                            'title' => Text::_('COM_SPPAGEBUILDER_ADDON_ACCORDION_CONTENT'),
                            'desc'  => Text::_('COM_SPPAGEBUILDER_ADDON_ACCORDION_CONTENT_DESC'),
                            'std'   => 'Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et.',
                        ],
                    ],
                ],
            ],
        ],

        'options' => [
            'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_OPTIONS'),
            'fields' => [
                'openitem' => [
                    'type'   => 'select',
                    'title'  => Text::_('COM_SPPAGEBUILDER_ADDON_ACCORDION_OPEN_ITEM'),
                    'desc'   => Text::_('COM_SPPAGEBUILDER_ADDON_ACCORDION_OPEN_ITEM_DESC'),
                    'values' => [
                        ''     => Text::_('COM_SPPAGEBUILDER_ADDON_ACCORDION_OPEN_FIRST_ITEM'),
                        'show' => Text::_('COM_SPPAGEBUILDER_ADDON_ACCORDION_OPEN_ALL_ITEM'),
                        'hide' => Text::_('COM_SPPAGEBUILDER_ADDON_ACCORDION_CLOSE_ALL_ITEM'),
                    ],
                ],

                'style' => [
                    'type'   => 'select',
                    'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_STYLE'),
                    'desc'   => Text::_('COM_SPPAGEBUILDER_ADDON_ACCORDION_STYLE_DESC'),
                    'values' => [
                        'panel-modern'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_MODERN'),
                        'panel-default' => Text::_('COM_SPPAGEBUILDER_GLOBAL_DEFAULT'),
                        'panel-primary' => Text::_('COM_SPPAGEBUILDER_GLOBAL_PRIMARY'),
                        'panel-success' => Text::_('COM_SPPAGEBUILDER_GLOBAL_SUCCESS'),
                        'panel-info'    => Text::_('COM_SPPAGEBUILDER_GLOBAL_INFO'),
                        'panel-warning' => Text::_('COM_SPPAGEBUILDER_GLOBAL_WARNING'),
                        'panel-danger'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_DANGER'),
                        'panel-faq'     => Text::_('COM_SPPAGEBUILDER_ADDON_ACCORDION_STYLE_FAQ'),
                        'panel-custom'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_CUSTOM'),
                    ],
                    'std' => 'panel-custom'
                ],
            ],
        ],

        'accordion' => [
            'title' => Text::_('COM_SPPAGEBUILDER_ADDON_ACCORDION'),
            'depends' => [['style', '=', 'panel-custom']],
            'fields' => [
                'item_bg' => [
                    'type'    => 'color',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_COLOR'),
                    'std'     => '#FFFFFF',
                    'depends' => [['style', '=', 'panel-custom']],
                ],

                'item_margin' => [
                    'type' => 'margin',
                    'title' => Text::_('COM_SPPAGEBUILDER_ADDON_ACCORDION_ITEM_MARGIN'),
                    'responsive' => true,
                    'depends' => [['style', '=', 'panel-custom']],
                ],

                'item_padding' => [
                    'type' => 'padding',
                    'title' => Text::_('COM_SPPAGEBUILDER_ADDON_ACCORDION_ITEM_PADDING'),
                    'responsive' => true,
                    'depends' => [['style', '=', 'panel-custom']],
                ],

                'item_border_separator' => [
                    'type'    => 'separator',
                    'depends' => [['style', '=', 'panel-custom']],
                ],

                'item_border_width' => [
                    'type'    => 'slider',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_WIDTH'),
                    'min'     => 0,
                    'max'     => 10,
                    'info'    => 'px',
                    'std'     => '1',
                    'depends' => [['style', '=', 'panel-custom']],
                ],

                'item_border_color' => [
                    'type'    => 'color',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_COLOR'),
                    'std'     => '#D5D7E0',
                    'depends' => [['style', '=', 'panel-custom']],
                ],

                'item_border_radius' => [
                    'type'    => 'slider',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_RADIUS'),
                    'min'     => 1,
                    'max'     => 100,
                    'responsive' => true,
                    'std'     => ['xl' => 4],
                    'depends' => [['style', '=', 'panel-custom']],
                ],

                'item_spacing_separator' => [
                    'type'    => 'separator',
                    'depends' => [['style', '=', 'panel-custom']],
                ],

                'item_spacing' => [
                    'type'    => 'slider',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_SPACING'),
                    'responsive' => true,
                    'std'     => ['xl' => 10],
                    'depends' => [['style', '=', 'panel-custom']],
                ],
            ],
        ],

        'header' => [
            'title' => Text::_('COM_SPPAGEBUILDER_ADDON_ACCORDION_HEADER'),
            'depends' => [['style', '=', 'panel-custom']],
            'fields' => [
                'item_title_padding' => [
                    'type'    => 'padding',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_PADDING'),
                    'responsive' => true,
                    'depends' => [['style', '=', 'panel-custom']],
                ],

                'icon_position' => [
                    'type'   => 'radio',
                    'title'  => Text::_('COM_SPPAGEBUILDER_ADDON_LINK_LIST_ICON_POSITION'),
                    'values' => [
                        'left'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_LEFT'),
                        'right' => Text::_('COM_SPPAGEBUILDER_GLOBAL_RIGHT'),
                    ],
                    'depends' => [['style', '=', 'panel-custom']],
                ],

                'icon_fontsize' => [
                    'type'       => 'slider',
                    'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_ICON_SIZE'),
                    'responsive' => true,
                    'max'        => 400,
                    'depends' => [['style', '=', 'panel-custom']],
                ],

                'icon_margin' => [
                    'type'        => 'margin',
                    'title'       => Text::_('COM_SPPAGEBUILDER_GLOBAL_ICON_MARGIN'),
                    'responsive'  => true,
                    'max'         => 400,
                    'depends' => [['style', '=', 'panel-custom']],
                ],

                'active_icon_rotate' => [
                    'type'    => 'slider',
                    'title'   => Text::_('COM_SPPAGEBUILDER_ADDON_ACCORDION_ICON_ROTATION'), // active
                    'max'     => 360,
                    'info'    => 'deg',
                    'std'     => 0,
                    'depends' => [['style', '=', 'panel-custom']],
                ],

                'item_title_typography' => [
                    'type'     => 'typography',
                    'title'       => Text::_('COM_SPPAGEBUILDER_GLOBAL_TYPOGRAPHY'),
                    'fallbacks'   => [
                        'font'           => 'item_title_font_family',
                        'size'           => 'item_title_fontsize',
                        'line_height'    => 'item_title_lineheight',
                        'letter_spacing' => 'item_title_letterspace',
                        'uppercase'      => 'item_title_font_style.uppercase',
                        'italic'         => 'item_title_font_style.italic',
                        'underline'      => 'item_title_font_style.underline',
                        'weight'         => 'item_title_font_style.weight',
                    ],
                    'depends' => [['style', '=', 'panel-custom']],
                ],

                'item_title_padding' => [
                    'type'    => 'padding',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_PADDING'),
                    'responsive' => true,
                    'depends' => [['style', '=', 'panel-custom']],
                ],
            ],
        ],

        'header_colors_options' => [
            'title' => Text::_('COM_SPPAGEBUILDER_ADDON_ACCORDION_HEADER_COLORS_TITLE'),
            'depends' => [['style', '=', 'panel-custom']],
            'fields' => [
                'header_style_tab' => [
                    'type'   => 'buttons',
                    'values' => [
                        ['label' => Text::_('COM_SPPAGEBUILDER_GLOBAL_NORMAL'), 'value' => 'normal'],
                        ['label' => Text::_('COM_SPPAGEBUILDER_GLOBAL_ACTIVE'), 'value' => 'active'],
                    ],
                    'std'    => 'normal',
                    'tabs'    => true,
                ],

                'item_title_text_color' => [
                    'type'    => 'color',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
                    'depends' => [['header_style_tab', '=', 'normal']],
                ],

                'item_title_bg_color' => [
                    'type'    => 'color',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND'),
                    'depends' => [['header_style_tab', '=', 'normal']],
                ],

                'icon_text_color' => [
                    'type'    => 'color',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_ICON_COLOR'),
                    'depends' => [['header_style_tab', '=', 'normal']],
                ],

                'active_title_text_color' => [
                    'type'    => 'color',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
                    'depends' => [['header_style_tab', '=', 'active']],
                ],

                'active_title_bg_color' => [
                    'type'    => 'color',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND'),
                    'depends' => [['header_style_tab', '=', 'active']],
                ],

                'active_icon_color' => [
                    'type'    => 'color',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_ICON_COLOR'),
                    'depends' => [['header_style_tab', '=', 'active']],
                ],
            ]
        ],

        'content_style' => [
            'title' => Text::_('COM_SPPAGEBUILDER_ADDON_ACCORDION_CONTENT'),
            'depends' => [['style', '=', 'panel-custom']],
            'fields' => [
                'item_content_padding' => [
                    'type'    => 'padding',
                    'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_PADDING'),
                    'responsive' => true,
                    'depends' => [['style', '=', 'panel-custom']],
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
                    'type'      => 'typography',
                    'title'     => Text::_('COM_SPPAGEBUILDER_GLOBAL_TYPOGRAPHY'),
                    'fallbacks'   => [
                        'font'           => 'title_font_family',
                        'size'           => 'title_fontsize',
                        'line_height'    => 'title_lineheight',
                        'letter_spacing' => 'title_letterspace',
                        'uppercase'      => 'title_font_style.uppercase',
                        'italic'         => 'title_font_style.italic',
                        'underline'      => 'title_font_style.underline',
                        'weight'         => 'title_font_style.weight',
                    ],
                ],

                'title_text_color' => [
                    'type'   => 'color',
                    'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR')
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
