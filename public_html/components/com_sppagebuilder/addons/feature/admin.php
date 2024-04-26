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
	'addon_name' => 'feature',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_FEATURE_BOX'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_FEATURE_BOX_DESC'),
	'category'   => 'Content',
	'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M29 26a1 1 0 01-1 1H4a1 1 0 110-2h24a1 1 0 011 1zM24 31a1 1 0 01-1 1H8a1 1 0 110-2h15a1 1 0 011 1z" fill="currentColor"/><path fill-rule="evenodd" clip-rule="evenodd" d="M16.458 0c.423 0 .81.24.996.62l2.63 5.327 5.882.86a1.111 1.111 0 01.614 1.895l-4.255 4.144 1.005 5.855a1.111 1.111 0 01-1.613 1.171l-5.259-2.765-5.26 2.765a1.111 1.111 0 01-1.611-1.17l1.004-5.856-4.255-4.144a1.111 1.111 0 01.614-1.895l5.882-.86L15.462.62c.187-.379.573-.619.996-.619zm0 3.621l-1.892 3.833a1.111 1.111 0 01-.836.608l-4.232.618 3.062 2.982c.262.255.382.623.32.984l-.723 4.211 3.784-1.99c.324-.17.71-.17 1.034 0l3.784 1.99-.723-4.21a1.11 1.11 0 01.32-.985l3.062-2.982-4.232-.618a1.111 1.111 0 01-.836-.608l-1.892-3.833z" fill="currentColor"/></svg>',
	'settings' => [
		'title' => [
			'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_TITLE'),
			'fields' => [
				'title' => [
					'type'  => 'text',
					'title' => Text::_('COM_SPPAGEBUILDER_ADDON_TITLE'),
					'desc'  => Text::_('COM_SPPAGEBUILDER_ADDON_TITLE_DESC'),
					'std'   => 'Feature Box',
				],

				'heading_selector' => [
					'type'   => 'headings',
					'title'  => Text::_('COM_SPPAGEBUILDER_ADDON_HEADINGS'),
					'desc'   => Text::_('COM_SPPAGEBUILDER_ADDON_HEADINGS_DESC'),
					'std'   => 'h3',
				],

				'title_typography' => [
					'type'      => 'typography',
					'title'  	=> Text::_('COM_SPPAGEBUILDER_GLOBAL_TYPOGRAPHY'),
					'fallbacks' => [
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

				'title_margin_top' => [
					'type'        => 'slider',
					'title'       => Text::_('COM_SPPAGEBUILDER_GLOBAL_MARGIN_TOP'),
					'max'         => 400,
					'responsive'  => true
				],

				'title_margin_bottom' => [
					'type'        => 'slider',
					'title'       => Text::_('COM_SPPAGEBUILDER_GLOBAL_MARGIN_BOTTOM'),
					'max'         => 400,
					'responsive'  => true
				],

				// Move some fields to advanced tab
				'alignment' => [
					'type'              => 'alignment',
					'title'  			=> Text::_('COM_SPPAGEBUILDER_GLOBAL_ALIGNMENT'),
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

		// Need to improve and separate fields
		'media' => [
			'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_MEDIA'),
			'fields' => [
				'title_position' => [
					'type'   => 'select',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_POSITION'),
					'desc'   => Text::_('COM_SPPAGEBUILDER_ADDON_FEATURE_BOX_ICON_IMAGE_POSITION_DESC'),
					'values' => [
						'after'  => Text::_('COM_SPPAGEBUILDER_ADDON_FEATURE_BOX_TITLE_POSITION_BEFORE_TITLE'),
						'before' => Text::_('COM_SPPAGEBUILDER_ADDON_FEATURE_BOX_TITLE_POSITION_AFTER_TITLE'),
						'left'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_LEFT'),
						'right'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_RIGHT'),
					],
					'inline'	=> true,
					'std' => 'after'
				],

				'feature_type' => [
					'type'   => 'radio',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_TYPE'),
					'desc'   => Text::_('COM_SPPAGEBUILDER_ADDON_FEATURE_BOX_LAYOUT_TYPE_DESC'),
					'values' => [
						'icon'  => Text::_('COM_SPPAGEBUILDER_ADDON_FEATURE_BOX_LAYOUT_TYPE_ICON'),
						'image' => Text::_('COM_SPPAGEBUILDER_ADDON_IMAGE'),
						'both'  => Text::_('COM_SPPAGEBUILDER_ADDON_FEATURE_BOX_LAYOUT_TYPE_BOTH'),
					],
					'std' => 'icon'
				],
			]
		],

		'icon' => [
			'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_ICON'),
			'depends'  => [['feature_type', '!=', 'image']],
			'fields' => [
				'icon_name' => [
					'type'      => 'icon',
					'title'     => Text::_('COM_SPPAGEBUILDER_GLOBAL_ICON_NAME'),
					'clearable' => true,
					'std'       => 'fas fa-trophy',
				],

				'icon_size' => [
					'type'        => 'slider',
					'title'       => Text::_('COM_SPPAGEBUILDER_GLOBAL_ICON_SIZE'),
					'std'         => ['xl' => 36, 'lg' => 36, 'md' => 36, 'sm' => 36, 'xs' => 36],
					'responsive'  => true,
					'max'         => 400,
				],

				'icon_padding' => [
					'type'       => 'padding',
					'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_PADDING'),
					'responsive' => true,
				],

				'icon_background' => [
					'type'    => 'color',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_COLOR'),
				],

				'icon_hover_bg' => [
					'type'  => 'color',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_HOVER_COLOR'),
				],

				'icon_border_width' => [
					'type'    => 'slider',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_WIDTH'),
					'responsive' => true,
					'max'        => 400,
				],

				'icon_border_color' => [
					'type'    => 'color',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_COLOR'),
					'depends'  => [['feature_type', '!=', 'image']],
				],

				'icon_boxshadow' => [
					'type'  => 'boxshadow',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BOXSHADOW'),
					'std'   => '0 0 0 0 #ffffff',
				],

				'icon_border_radius' => [
					'type'    => 'slider',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_RADIUS'),
					'responsive' => true,
					'max'        => 400,
				],

				'icon_margin_top' => [
					'type'       => 'slider',
					'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_MARGIN_TOP'),
					'responsive' => true,
					'min'        => -200,
					'max'        => 400,
				],

				'icon_margin_bottom' => [
					'type'       => 'slider',
					'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_MARGIN_BOTTOM'),
					'responsive' => true,
					'max'        => 400,
				],
			],
		],

		'image' => [
			'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_IMAGE'),
			'depends'  => [['feature_type', '!=', 'icon']],
			'fields' => [
				'feature_image' => [
					'type'  => 'media',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_IMAGE'),
					'std'   => ['src' => '', 'height' => '', 'width' => ''],
				],

				'feature_image_alt' => [
					'type' 	  => 'text',
					'title'   => Text::_('COM_SPPAGEBUILDER_ADDON_FEATURE_BOX_IMAGE_ALT'),
					'desc'	  => Text::_('COM_SPPAGEBUILDER_ADDON_FEATURE_BOX_IMAGE_ALT_DESC'),
					'std' 	  => '',
				],

				'feature_image_width' => [
					'type'       => 'slider',
					'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_WIDTH'),
					'responsive' => true,
					'min'        => 5,
					'max'        => 90,
					'std'        => ['xl' => 50],
					'depends' => [
						['title_position', '!=', 'before'],
						['title_position', '!=', 'after']
					]
				],

				'feature_image_margin' => [
					'type'       => 'margin',
					'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_MARGIN'),
					'responsive' => true,
				],
			],
		],

		'content' => [
			'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_CONTENT'),
			'fields' => [
				'text' => [
					'type'  => 'editor',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_CONTENT'),
					'std'   => 'The purpose of a feature box is to provide concise and relevant information, such as key points, quick facts, or important details, in a way that stands out from the surrounding content. This helps users easily identify and focus on the most important aspects of the content being presented.'
				],

				'content_typography' => [
					'type'      => 'typography',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_TYPOGRAPHY'),
					'fallbacks' => [
						'font'           => 'text_font_family',
						'size'           => 'text_fontsize',
						'line_height' 	 => 'text_lineheight',
						'weight'         => 'text_fontweight',
					],
				],

				'text_background' => [
					'type'  => 'color',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_COLOR'),
				],

				'text_padding' => [
					'type'       => 'padding',
					'title'      => Text::_('COM_SPPAGEBUILDER_GLOBAL_PADDING'),
					'responsive' => true,
				],
			],
		],

		'link' => [
			'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_LINK'),
			'fields' => [
				'title_url' => [
					'type'      => 'link',
					'title'     => Text::_('COM_SPPAGEBUILDER_ADDON_FEATURE_BOX_TITLE_IMAGE_URL'),
					'desc'      => Text::_('COM_SPPAGEBUILDER_ADDON_FEATURE_BOX_TITLE_IMAGE_URL_DESC'),
					'hideTitle' => true,
				],

				'url_appear' => [
					'type'   => 'select',
					'title'  => Text::_('COM_SPPAGEBUILDER_ADDON_FEATURE_BOX_URL_APEAR'),
					'desc'   => Text::_('COM_SPPAGEBUILDER_ADDON_FEATURE_BOX_URL_APEAR_DESC'),
					'values' => [
						'title' => Text::_('COM_SPPAGEBUILDER_ADDON_FEATURE_BOX_URL_APEAR_TITLE'),
						'icon'  => Text::_('COM_SPPAGEBUILDER_ADDON_FEATURE_BOX_URL_APEAR_ICON'),
						'both'  => Text::_('COM_SPPAGEBUILDER_ADDON_FEATURE_BOX_URL_APEAR_BOTH'),
					],
					'std' => 'title',
				],
			]
		],

		// Style
		'style' => [
			'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_STYLE'),
			'fields' => [
				'feature_style_state' => [
					'type'   => 'radio',
					'values' => [
						'normal' => Text::_('Normal'),
						'hover' => Text::_('Hover'),
					],
					'std' => 'normal',
				],

				'title_text_color' => [
					'type'   => 'color',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_TITLE_COLOR'),
					'depends' => [['feature_style_state', '=', 'normal']]
				],

				'addon_color' => [
					'type'    => 'color',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_TEXT_COLOR'),
					'depends' => [['feature_style_state', '=', 'normal']]
				],

				'icon_color' => [
					'type'    => 'color',
					'std'     => '#3366FF',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_ICON_COLOR'),
					'depends' => [
						['feature_style_state', '=', 'normal'],
						['feature_type', '!=', 'image']
					]
				],

				'background_color' => [
					'type'    => 'color',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND'),
					'depends' => [['feature_style_state', '=', 'normal']]
				],

				'title_hover_color' => [
					'type'    => 'color',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_TITLE_COLOR'),
					'depends' => [['feature_style_state', '=', 'hover']]
				],

				'addon_hover_color' => [
					'type'    => 'color',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_TEXT_COLOR'),
					'depends' => [['feature_style_state', '=', 'hover']]
				],

				'icon_hover_color' => [
					'type'    => 'color',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_ICON_COLOR'),
					'depends' => [
						['feature_type', '!=', 'image'],
						['feature_style_state', '=', 'hover']
					]
				],

				'addon_hover_bg' => [
					'type'    => 'color',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND'),
					'depends' => [['feature_style_state', '=', 'hover']]
				],

				'addon_hover_boxshadow' => [
					'type'  => 'boxshadow',
					'title' => Text::_('COM_SPPAGEBUILDER_ADDON_FEATURE_BOX_HOVER_BOXSHADOW'),
					'std'   => '0 0 0 0 #ffffff',
					'depends' => [['feature_style_state', '=', 'hover']]
				],
			],
		],

		// Button
		'button' => [
			'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON'),
			'fields' => [
				'btn_text' => [
					'type'  => 'text',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_LABEL'),
					'inline' => true,
				],

				'btn_url' => [
					'type'  => 'link',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_LINK'),
				],

				'btn_typography' => [
					'type'     => 'typography',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_TYPOGRAPHY'),
					'fallbacks' => [
						'font' => 'btn_font_family',
						'size' => 'btn_fontsize',
						'letter_spacing' => 'btn_letterspace',
						'weight' => 'btn_font_style.weight',
						'italic' => 'btn_font_style.italic',
						'underline' => 'btn_font_style.underline',
						'uppercase' => 'btn_font_style.uppercase',
					],
				],

				'btn_type' => [
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
					'inline' => true,
				],

				'button_padding' => [
					'type'    => 'padding',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_PADDING'),
					'responsive' => true,
					'std' => ['xl' => '8px 22px 10px 22px', 'lg' => '', 'md' => '', 'sm' => '', 'xs' => ''],
					'depends' => [['btn_size', '=', 'custom']]
				],

				'btn_appearance' => [
					'type'   => 'select',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_APPEARANCE'),
					'desc'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_APPEARANCE_DESC'),
					'values' => [
						''         => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_APPEARANCE_FLAT'),
						'gradient' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_APPEARANCE_GRADIENT'),
						'outline'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_APPEARANCE_OUTLINE'),
					],
					'std'   => '',
					'inline' => true,
					'depends' => [['btn_type', '!=', 'link']]
				],

				'btn_shape' => [
					'type'   => 'select',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SHAPE'),
					'desc'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SHAPE_DESC'),
					'values' => [
						'rounded' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SHAPE_ROUNDED'),
						'square'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SHAPE_SQUARE'),
						'round'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_SHAPE_ROUND'),
					],
					'std'   => 'rounded',
					'depends' => [['btn_type', '!=', 'link']]
				],

				'link_btn_padding_bottom' => [
					'type'    => 'slider',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_PADDING_BOTTOM'),
					'max'     => 100,
					'std'     => '',
					'depends' => [['btn_type', '=', 'link']]
				],

				'btn_size' => [
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
					'inline' => true,
					'std'   => '',
				],

				'btn_block' => [
					'type'   => 'radio',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_BLOCK'),
					'values' => [
						''               => Text::_('JNO'),
						'sppb-btn-block' => Text::_('JYES'),
					],
					'std'     => '',
					'depends' => [['btn_type', '!=', 'link']]
				],

				'button_margin' => [
					'type'        => 'margin',
					'title'       => Text::_('COM_SPPAGEBUILDER_GLOBAL_MARGIN'),
					'responsive'  => true,
					'std'         => ['xl' => '25px 0px 0px 0px', 'lg' => '', 'md' => '', 'sm' => '', 'xs' => ''],
				],
			]
		],

		'button_icon' => [
			'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_ICON'),
			'fields' => [
				'btn_icon' => [
					'type'  => 'icon',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_ICON'),
					'desc'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_ICON_DESC'),
				],

				'btn_icon_position' => [
					'type'   => 'radio',
					'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_ICON_POSITION'),
					'values' => [
						'left'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_LEFT'),
						'right' => Text::_('COM_SPPAGEBUILDER_GLOBAL_RIGHT'),
					],
					'std' => 'left',
				],
			],
		],

		// Button style
		'btn_style' => [
			'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_STYLE'),
			'depends' => [['btn_type', '=', 'custom']],
			'fields' => [
				'btn_style_state' => [
					'type'   => 'radio',
					'values' => [
						'normal' => Text::_('Normal'),
						'hover' => Text::_('Hover'),
					],
					'std' => 'normal',
				],

				'btn_color' => [
					'type' => 'color',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
					'std' => '#FFFFFF',
					'depends' => [['btn_style_state', '=', 'normal']],
				],

				'btn_color_hover' => [
					'type' => 'color',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
					'std' => '#FFFFFF',
					'depends' => [['btn_style_state', '=', 'hover']],
				],

				'btn_background_color' => [
					'type'    => 'color',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_COLOR'),
					'std'     => '#3366FF',
					'depends' => [
						['btn_style_state', '=', 'normal'],
						['btn_appearance', '!=', 'gradient'],
					],
				],

				'btn_background_color_hover' => [
					'type'    => 'color',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_COLOR'),
					'std'     => '#0037DD',
					'depends' => [
						['btn_style_state', '=', 'hover'],
						['btn_appearance', '!=', 'gradient'],
					],
				],

				'btn_background_gradient' => [
					'type' => 'gradient',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_COLOR'),
					'std' => [
						"color"  => "#3366FF",
						"color2" => "#0037DD",
						"deg" => "45",
						"type" => "linear"
					],
					'depends' => [
						['btn_style_state', '=', 'normal'],
						['btn_appearance', '=', 'gradient'],
					],
				],

				'btn_background_gradient_hover' => [
					'type' => 'gradient',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_COLOR'),
					'std' => [
						"color"  => "#0037DD",
						"color2" => "#3366FF",
						"deg" => "45",
						"type" => "linear"
					],
					'depends' => [
						['btn_style_state', '=', 'hover'],
						['btn_appearance', '=', 'gradient'],
					],
				],
			],
		],

		// Link type button
		'btn_link_type_style' => [
			'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_BUTTON_STYLE'),
			'depends' => [['btn_type', '=', 'link']],
			'fields' => [
				'link_btn_style_state' => [
					'type'   => 'radio',
					'values' => [
						'normal' => Text::_('Normal'),
						'hover' => Text::_('Hover'),
					],
					'std' => 'normal',
				],

				'link_btn_color' => [
					'type'    => 'color',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
					'depends' => [
						['link_btn_style_state', '=', 'normal'],
						['btn_type', '=', 'link'],
					],
				],

				'link_btn_border_width' => [
					'type'    => 'slider',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_WIDTH'),
					'max'     => 30,
					'depends' => [
						['link_btn_style_state', '=', 'normal'],
						['btn_type', '=', 'link'],
					],
				],

				'link_btn_border_color' => [
					'type'    => 'color',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_COLOR'),
					'std'     => '',
					'depends' => [
						['link_btn_style_state', '=', 'normal'],
						['btn_type', '=', 'link'],
					],
				],

				'link_btn_hover_color' => [
					'type'    => 'color',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
					'std'     => '',
					'depends' => [
						['link_btn_style_state', '=', 'hover'],
						['btn_type', '=', 'link'],
					],
				],

				'link_btn_border_hover_color' => [
					'type'    => 'color',
					'title'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_COLOR'),
					'std'     => '',
					'depends' => [
						['link_btn_style_state', '=', 'hover'],
						['btn_type', '=', 'link'],
					],
				],
			],
		],
	],
]);
