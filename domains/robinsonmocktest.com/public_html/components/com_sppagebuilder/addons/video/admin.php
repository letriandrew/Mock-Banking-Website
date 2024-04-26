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
    'addon_name' => 'video',
    'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_VIDEO'),
    'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_VIDEO_DESC'),
    'category'   => 'Media',
    'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><g opacity=".5" fill-rule="evenodd" clip-rule="evenodd" fill="currentColor"><path d="M30.1 22.1H1.9c-.5 0-.9.4-.9.9s.4 1 .9 1H8v6.1c0 .5.5.9 1 .9s.9-.4.9-.9V24h5.2v6.1c0 .5.4.9.9.9s.9-.4.9-.9V24h5.2v6.1c0 .5.4.9.9.9s.9-.4.9-.9V24H30c.5 0 .9-.4.9-.9.1-.6-.3-1-.8-1zM1.9 9.9H30c.6 0 1-.4 1-.9s-.4-1-.9-1H24V1.9c0-.5-.5-.9-1-.9s-.9.4-.9.9V8h-5.2V1.9c0-.5-.4-.9-.9-.9s-.9.4-.9.9V8H9.9V1.9c0-.5-.4-.9-.9-.9s-1 .4-1 .9V8H1.9c-.5 0-.9.5-.9 1s.4.9.9.9z"/></g><path fill-rule="evenodd" clip-rule="evenodd" d="M2.1 27.6c0 1.2 1 2.2 2.2 2.2h23.2c1.2 0 2.2-1 2.2-2.2V4.4c0-1.2-1-2.2-2.2-2.2H4.4c-1.2 0-2.2 1-2.2 2.2v23.2h-.1zm2.3 4.2c-2.3 0-4.2-1.9-4.2-4.2V4.4C.2 2 2 .2 4.4.2h23.2c2.3 0 4.2 1.9 4.2 4.2v23.2c0 2.3-1.9 4.2-4.2 4.2H4.4z" fill="currentColor"/><path d="M19.5 15c.6.5.6 1.5 0 1.9l-3.9 2.8c-.7.5-1.7 0-1.7-1V13c0-.9 1-1.5 1.7-1l3.9 3z" fill="currentColor"/></svg>',
    'settings' => [
        'content' => [
            'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_CONTENT'),
            'fields' => [
                // does not working
                'mp4_enable' => [
                    'type'   => 'radio',
                    'title'  => Text::_('COM_SPPAGEBUILDER_ADDON_VIDEO_SOURCE'),
                    'values' => [
                        0 => 'YouTube/Vimeo',
                        1 => 'MP4',
                    ],
                    'std' => 0,
                ],

                'url' => [
                    'type'    => 'text',
                    'title'   => Text::_('COM_SPPAGEBUILDER_ADDON_VIDEO_URL'),
                    'desc'    => Text::_('COM_SPPAGEBUILDER_ADDON_VIDEO_URL_DESC'),
                    'std'     => 'https://www.youtube.com/watch?v=BWLRMBrKH_c',
                    'depends' => [['mp4_enable', '=', 0]],
                ],

                'video_title' => [
                    'type'    => 'text',
                    'title'   => Text::_('COM_SPPAGEBUILDER_ADDON_VIDEO_TITLE'),
                    'desc'    => Text::_('COM_SPPAGEBUILDER_ADDON_VIDEO_TITLE_DESC'),
                    'std'     => '',
                    'depends' => [['mp4_enable', '=', 0]],
                ],

                'mp4_video' => [
                    'type'         => 'media',
                    'format'       => 'video',
                    'title'        => Text::_('COM_SPPAGEBUILDER_ADDON_VIDEO_MP4'),
                    'std'          => ['src' => 'https://www.joomshaper.com/media/videos/2017/11/10/pb-intro-video.mp4'],
                    'hide_preview' => true,
                    'depends'      => [['mp4_enable', '=', 1]],
                ],

                'ogv_video' => [
                    'type'         => 'media',
                    'format'       => 'video',
                    'title'        => Text::_('COM_SPPAGEBUILDER_ADDON_VIDEO_OGV'),
                    'hide_preview' => true,
                    'depends'      => [['mp4_enable', '=', 1]],
                ],

                'show_rel_video' => [
                    'type'    => 'checkbox',
                    'title'   => Text::_('COM_SPPAGEBUILDER_ADDON_VIDEO_OWN_CHANNEL_REL'),
                    'std'     => 0,
                    'depends' => [['mp4_enable', '=', 0]],
                ],

                'no_cookie' => [
                    'type'    => 'checkbox',
                    'title'   => Text::_('COM_SPPAGEBUILDER_ADDON_VIDEO_NO_COOKIE'),
                    'desc'    => Text::_('COM_SPPAGEBUILDER_ADDON_VIDEO_NO_COOKIE_DESC'),
                    'std'     => 0,
                    'depends' => [['mp4_enable', '=', 0]],
                ],

                'youtube_shorts' => [
                    'type'    => 'checkbox',
                    'title'   => Text::_('COM_SPPAGEBUILDER_ADDON_VIDEO_YOUTUBE_SHORTS'),
                    'desc'    => Text::_('COM_SPPAGEBUILDER_ADDON_VIDEO_YOUTUBE_SHORTS_DESC'),
                    'std'     => 0,
                    'depends' => [['mp4_enable', '=', 0]]
                ],

                'aspect_ratio' => [
                    'type'   => 'select',
                    'title'  => Text::_('COM_SPPAGEBUILDER_ADDON_VIDEO_YOUTUBE_ASPECT_RATIO'),
                    'values' => [
                        '1by1'  => '1:1',
                        '4by3'  => '4:3',
                        '9by16' => '9:16',
                        '16by9' => '16:9',
                    ],
                    'std'     => '9by16',
                    'depends' => [['youtube_shorts', '=', 1]]
                ],

                'vimeo_show_author' => [
                    'type'    => 'checkbox',
                    'title'   => Text::_('COM_SPPAGEBUILDER_ADDON_VIDEO_VIMEO_SHOW_AUTHOR'),
                    'desc'    => Text::_('COM_SPPAGEBUILDER_ADDON_VIDEO_VIMEO_SHOW_AUTHOR_DESC'),
                    'std'     => 0,
                    'depends' => [['mp4_enable', '=', 0]],
                ],

                'vimeo_mute_video' => [
                    'type'    => 'checkbox',
                    'title'   => Text::_('COM_SPPAGEBUILDER_ADDON_VIDEO_VIMEO_MUTE_VIDEO'),
                    'desc'    => Text::_('COM_SPPAGEBUILDER_ADDON_VIDEO_VIMEO_MUTE_VIDEO_DESC'),
                    'std'     => 1,
                    'depends' => [['mp4_enable', '=', 0]],
                ],

                'vimeo_show_author_profile' => [
                    'type'    => 'checkbox',
                    'title'   => Text::_('COM_SPPAGEBUILDER_ADDON_VIDEO_VIMEO_SHOW_AUTHOR_PROFILE'),
                    'desc'    => Text::_('COM_SPPAGEBUILDER_ADDON_VIDEO_VIMEO_SHOW_AUTHOR_PROFILE_DESC'),
                    'std'     => 0,
                    'depends' => [['mp4_enable', '=', 0]],
                ],

                'vimeo_show_video_title' => [
                    'type'    => 'checkbox',
                    'title'   => Text::_('COM_SPPAGEBUILDER_ADDON_VIDEO_VIMEO_SHOW_VIDEO_TITLE'),
                    'desc'    => Text::_('COM_SPPAGEBUILDER_ADDON_VIDEO_VIMEO_SHOW_VIDEO_TITLE_DESC'),
                    'std'     => 0,
                    'depends' => [['mp4_enable', '=', 0]],
                ],

                'show_control' => [
                    'type'    => 'checkbox',
                    'title'   => Text::_('COM_SPPAGEBUILDER_ADDON_VIDEO_CONTROL'),
                    'std'     => 1,
                    'depends' => [['mp4_enable', '=', 1]],
                ],

                'video_loop' => [
                    'type'    => 'checkbox',
                    'title'   => Text::_('COM_SPPAGEBUILDER_ADDON_VIDEO_LOOP'),
                    'std'     => 0,
                    'depends' => [['mp4_enable', '=', 1]],
                ],

                'video_mute' => [
                    'type'    => 'checkbox',
                    'title'   => Text::_('COM_SPPAGEBUILDER_ADDON_VIDEO_MUTE'),
                    'std'     => 1,
                    'depends' => [['mp4_enable', '=', 1]],
                ],

                'autoplay_video' => [
                    'type'    => 'checkbox',
                    'title'   => Text::_('COM_SPPAGEBUILDER_ADDON_VIDEO_AUTOPLAY'),
                    'std'     => 0,
                    'depends' => [['mp4_enable', '=', 1]],
                ],
            ],
        ],

        'poster' => [
            'title' => Text::_('COM_SPPAGEBUILDER_ADDON_VIDEO_POSTER'),
            'fields' => [
                'video_poster' => [
                    'type'       => 'media',
                    'std'        => ['src' => 'https://www.joomshaper.com/images/2017/11/10/real-time-frontend.jpg'],
                    'depends'    => [['mp4_enable', '=', 1]],
                ],

                'video_poster_message' => [
                    'type'       => 'alert',
                    'message'    => Text::_('COM_SPPAGEBUILDER_ADDON_VIDEO_POSTER_UNAVILABLE'),
                    'depends'    => [['mp4_enable', '=', 0]],
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
                    'type'   => 'typography',
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
