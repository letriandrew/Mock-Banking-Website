<?php
/**
 * @package SP Page Builder
 * @author JoomShaper https://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;

SpAddonsConfig::addonConfig([
    'type' => 'content',
    'addon_name' => 'image',
    'title' => Text::_('COM_SPPAGEBUILDER_ADDON_IMAGE'),
    'desc' => Text::_('COM_SPPAGEBUILDER_ADDON_IMAGE_DESC'),
    'category' => 'Media',
    'icon' => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M31.288 17.393l-9.718 5.9a5 5 0 01-5.8-.435l-3.622-3.024a3 3 0 00-3.583-.197l-6.781 4.504-1.106-1.666 6.78-4.504a5 5 0 015.971.327l3.623 3.025a3 3 0 003.48.261l9.718-5.9 1.038 1.71z" fill="currentColor"/><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M19.077 10.154a2.077 2.077 0 100 4.154 2.077 2.077 0 000-4.154zM15 12.23a4.077 4.077 0 118.154 0 4.077 4.077 0 01-8.154 0z" fill="currentColor"/><path fill-rule="evenodd" clip-rule="evenodd" d="M29 4H3a1 1 0 00-1 1v22.308a1 1 0 001 1h26a1 1 0 001-1V5a1 1 0 00-1-1zM3 2a3 3 0 00-3 3v22.308a3 3 0 003 3h26a3 3 0 003-3V5a3 3 0 00-3-3H3z" fill="currentColor"/></svg>',
    'settings' => [
        'content' => [
            'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_CONTENT'),
            'fields' => [
                'image' => [
                    'type' => 'media',
                    'std' => [
                        'src' => 'https://sppagebuilder.com/addons/image/image1.jpg',
                        'height' => '',
                        'width' => '',
                    ],
                ],

                'image_2x' => [
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_IMAGE_2X_TEXT'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_IMAGE_2X_TEXT_DESC'),
                    'type' => 'media',
                    'hide_preview' => true,
                    'std' => [
                        'src' => '',
                        'height' => '',
                        'width' => '',
                    ],
                ],

                'alt_text' => [
                    'type' => 'text',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_ALT_TEXT'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_ALT_TEXT_DESC'),
                    'std' => 'Image',
                    'inline' => true,
                ],

                'position' => [
                    'type' => 'alignment',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_ALIGNMENT'),
                    'responsive' => true,
                    'available_options' => ['left', 'center', 'right'],
                ],
            ],
        ],

        'options' => [
            'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_OPTIONS'),
            'fields' => [
                'image_width' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_WIDTH'),
                    'max' => 2000,
                    'min' => 0,
                    'responsive' => true,
                ],

                'image_height' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_HEIGHT'),
                    'max' => 2000,
                    'min' => 0,
                    'responsive' => true,
                ],

                'border_radius' => [
                    'type' => 'advancedslider',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_RADIUS'),
                    'std' => 0,
                    'max' => 1200,
                ],

                'open_lightbox' => [
                    'type' => 'checkbox',
                    'title' => Text::_('COM_SPPAGEBUILDER_ADDON_IMAGE_OPEN_LIGHTBOX'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_ADDON_IMAGE_OPEN_LIGHTBOX_DESC'),
                    'std' => 0,
                ],

                'overlay_color' => [
                    'type' => 'color',
                    'title' => Text::_('COM_SPPAGEBUILDER_ADDON_IMAGE_OVERLAY'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_ADDON_IMAGE_OVERLAY_DESC'),
                    'std' => 'rgba(119, 219, 31, .5)',
                    'depends' => [['open_lightbox', '!=', 0]],
                ],

                'link' => [
                    'type' => 'link',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_LINK'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_GLOBAL_LINK_DESC'),
                    'std' => '',
                    'depends' => [['open_lightbox', '!=', 1]],
                ],
            ],
        ],

        'title' => [
            'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_TITLE'),
            'fields' => [
                'title' => [
                    'type' => 'text',
                    'title' => Text::_('COM_SPPAGEBUILDER_ADDON_TITLE'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_ADDON_TITLE_DESC'),
                ],

                'heading_selector' => [
                    'type' => 'headings',
                    'title' => Text::_('COM_SPPAGEBUILDER_ADDON_HEADINGS'),
                    'desc' => Text::_('COM_SPPAGEBUILDER_ADDON_HEADINGS_DESC'),
                    'std' => 'h3',
                ],

                'title_typography' => [
                    'type' => 'typography',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_TYPOGRAPHY'),
                    'fallbacks' => [
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
                    'type' => 'color',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
                ],

                'title_position' => [
                    'type' => 'select',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_POSITION'),
                    'values' => [
                        'top' => 'Top',
                        'bottom' => 'Bottom',
                    ],
                    'std' => 'top',
                ],

                'title_margin_top' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_MARGIN_TOP'),
                    'max' => 400,
                    'responsive' => true,
                ],

                'title_margin_bottom' => [
                    'type' => 'slider',
                    'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_MARGIN_BOTTOM'),
                    'max' => 400,
                    'responsive' => true,
                ],
            ],
        ],
    ],
]);