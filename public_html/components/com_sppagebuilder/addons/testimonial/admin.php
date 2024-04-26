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
	'addon_name' => 'testimonial',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_TESTIMONIAL'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_TESTIMONIAL_DESC'),
	'category'   => 'Content',
	'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.455 4.455a4 4 0 014-4h20.181a4 4 0 014 4v17.272a4 4 0 01-4 4h-3.272v4.819a1 1 0 01-1.707.707l-5.526-5.526H9.515a4 4 0 01-2.828-1.171l-4.06-4.061a4 4 0 01-1.172-2.829V4.455zm4-2a2 2 0 00-2 2v13.211a2 2 0 00.585 1.415l4.061 4.06a2 2 0 001.414.586h6.03a1 1 0 01.708.293l4.11 4.111v-3.404a1 1 0 011-1h4.273a2 2 0 002-2V4.455a2 2 0 00-2-2H5.455z" fill="currentColor"/><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M10.91 9.455a1 1 0 011-1h7.272a1 1 0 110 2h-7.273a1 1 0 01-1-1zM10.91 16.727a1 1 0 011-1h7.272a1 1 0 110 2h-7.273a1 1 0 01-1-1z" fill="currentColor"/></svg>',
	'settings' => [

		'content' => [
			'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_CONTENT'),
			'fields' => [
				'name' => [
					'type'  => 'text',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_NAME'),
					'std'   => 'John Doe',
				],

				'url' => [
					'type'  => 'link',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_LINK'),
					'std'	=> ['url' => 'https://www.joomshaper.com'],
				],

				'name_typography' => [
					'type'     => 'typography',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_TYPOGRAPHY'),
					'fallbacks'   => [
						'font' => 'name_font_family',
						'size' => 'name_font_size',
						'line_height' => 'name_line_height',
						'uppercase' => 'name_font_style.uppercase',
						'italic' => 'name_font_style.italic',
						'underline' => 'name_font_style.underline',
						'weight' => 'name_font_style.weight',
					],
				],

				'name_color' => [
					'type'   => 'color',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
				],

				'name_margin' => [
					'type'       => 'margin',
					'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_MARGIN'),
					'responsive' => true,
				],

				'review_separator' => [
					'type'  => 'separator',
				],

				'review' => [
					'type'  => 'editor',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_CONTENT'),
					'std'   => 'Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch.'
				],

				'review_typography' => [
					'type'     => 'typography',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_TYPOGRAPHY'),
					'fallbacks'   => [
						'font' => 'review_font_family',
						'size' => 'review_size',
						'line_height' => 'review_line_height',
						'weight' => 'review_fontweight',
					],
				],

				'review_color' => [
					'type'   => 'color',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
				],

				'review_margin' => [
					'type'       => 'margin',
					'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_MARGIN'),
					'responsive' => true,
				],

				'company_sperator' => [
					'type'  => 'separator',
				],

				'company' => [
					'type'  => 'text',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_DESIGNATION'),
					'std'   => 'CEO, Company',
				],

				'designation_typography' => [
					'type'     => 'typography',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_TYPOGRAPHY'),
					'fallbacks'   => [
						'font' => 'company_font_family',
						'size' => 'company_font_size',
						'line_height' => 'company_line_height',
						'uppercase' => 'company_font_style.uppercase',
						'italic' => 'company_font_style.italic',
						'underline' => 'company_font_style.underline',
						'weight' => 'company_font_style.weight',
					],
				],

				'company_color' => [
					'type'   => 'color',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
				],

				'designation_position' => [
					'type'   => 'select',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_POSITION'),
					'values' => [
						'top'    => Text::_('COM_SPPAGEBUILDER_ADDON_OPTIN_POSITION_TOP'),
						'bottom' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BOTTOM'),
					],
					'std' => 'bottom'
				],

				'avatar_separator' => [
					'type'  => 'separator',
				],

				'avatar' => [
					'type'  => 'media',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_AVATAR'),
				],

				'avatar_width' => [
					'type'  => 'slider',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_WIDTH'),
					'std'   => 32,
					'min'   => 16,
					'max'   => 128
				],

				'avatar_shape' => [
					'type'   => 'select',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_SHAPE'),
					'values' => [
						'sppb-avatar-sqaure' => Text::_('COM_SPPAGEBUILDER_GLOBAL_SQUARE'),
						'sppb-avatar-round'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_ROUNDED'),
						'sppb-avatar-circle' => Text::_('COM_SPPAGEBUILDER_GLOBAL_CIRCLE'),
					],
					'std' => 'sppb-avatar-circle'
				],

				'avatar_dis_block' => [
					'type'  => 'checkbox',
					'title' => Text::_('COM_SPPAGEBUILDER_ADDON_TESTIMONIAL_CLIENT_AVATAR_BLOCK'),
					'std'   => 0,
				],

				'avatar_margin' => [
					'type'       => 'margin',
					'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_MARGIN'),
					'responsive' => true,
				],

				'alignment_separator' => [
					'type'  => 'separator',
				],

				'alignment' => [
					'type' => 'alignment',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_ALIGNMENT'),
					'available_options' => ['left', 'center', 'right'],
					'responsive'  => true,
					'std'		  => [
						'xl' => 'center',
						'lg' => '',
						'md' => '',
						'sm' => '',
						'xs' => '',
					]
				],
			],
		],

		'options' => [
			'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_OPTIONS'),
			'fields' => [
				'show_quote' => [
					'type'   => 'checkbox',
					'title'  => Text::_('COM_SPPAGEBUILDER_ADDON_TESTIMONIAL_SHOW_ICON'),
					'values' => [
						1 => Text::_('JYES'),
						0 => Text::_('JNO'),
					],
					'std' => 1,
				],

				'icon_size' => [
					'type'       => 'slider',
					'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_SIZE'),
					'std'        => ['xl' => 48, 'lg' => '', 'md' => '', 'sm' => '', 'xs' => ''],
					'min'        => 10,
					'max'        => 200,
					'responsive' => true,
					'depends'    => [['show_quote', '=', 1]],
				],

				'icon_color' => [
					'type'    => 'color',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
					'std'     => '#EDEEF2',
					'depends' => [['show_quote', '=', 1]],
				],

				'client_rating_separator_start' => [
					'type'  => 'separator',
				],

				'client_rating_enable' => [
					'type'  => 'checkbox',
					'title' => Text::_('COM_SPPAGEBUILDER_ADDON_TESTIMONIAL_CLIENT_RATING_ENABLE'),
					'std'   => 0
				],

				'client_rating' => [
					'type'    => 'slider',
					'title'   => Text::_('COM_SPPAGEBUILDER_ADDON_TESTIMONIAL_CLIENT_RATING'),
					'desc'    => Text::_('COM_SPPAGEBUILDER_ADDON_TESTIMONIAL_CLIENT_RATING_DESC'),
					'max' => 5,
					'min' => 1,
					'std' => 5,
					'depends' => [['client_rating_enable', '=', 1]],
				],

				'client_rating_color' => [
					'type'    => 'color',
					'title'   => Text::_('COM_SPPAGEBUILDER_ADDON_TESTIMONIAL_CLIENT_RATING_COLOR'),
					'depends' => [['client_rating_enable', '=', 1]],
				],

				'client_unrated_color' => [
					'type'    => 'color',
					'title'   => Text::_('COM_SPPAGEBUILDER_ADDON_TESTIMONIAL_CLIENT_UNRATED_COLOR'),
					'depends' => [['client_rating_enable', '=', 1]],
				],

				'client_rating_fontsize' => [
					'type'    => 'slider',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_FONT_SIZE'),
					'responsive' => true,
					'std'        => ['xl' => 16, 'lg' => '', 'md' => '', 'sm' => '', 'xs' => ''],
					'depends' => [['client_rating_enable', '=', 1]],
				],

				'client_rating_margin' => [
					'type'    => 'margin',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_MARGIN'),
					'responsive' => true,
					'std'        => ['xl' => '10px 5px 10px 5px', 'lg' => '', 'md' => '', 'sm' => '', 'xs' => ''],
					'depends' => [['client_rating_enable', '=', 1]],
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
			]
		]
	],
]);
