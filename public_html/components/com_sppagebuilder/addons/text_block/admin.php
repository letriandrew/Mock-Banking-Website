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

/**
 * Inline editor settings rules: 
 * 1. The inline array must have an attribute named `buttons`
 * 2. The buttons array contains all the editor buttons. The key of the array must be unique.
 * 3. Every button contains some attributes like-
 * 		a. action (string) (required) [The action will perform after clicking the button]
 * 		b. type (string) (required) [The type of the button. valid values are `placeholder`, `icon-text`]
 * 		c. placeholder (string) (optional) [If the button is dynamic and this cannot be expressed as icon/text.]
 * 		d. icon (string) (optional) [A valid icon name available in the pagebuilder]
 * 		e. text (string) (optional) [A text value to show as the button text]
 * 		f. icon_position (string) (optional) [`left` or `right` position of the icon to the text. Default `left`]
 * 		g. tooltip (string) (optional) [Tooltip text to show on the button hover.]
 * 		h. fieldset (array) (conditional) [An conditional array (which is required if action is dropdown) for representing the fieldset fields.
 * 			This is valid only if the action is `dropdown`.
 * 			The direct children of the fieldset array would be the tabs.
 * 			Inside the tabs name you should define the fields descriptions.
 * 			If there is only one fieldset child then that means no tabs]
 * 		i. options (array) (conditional) [This is a conditional field. This is required if the action is dropdown
 * 			but you need to show some options not fields.] 
 * 		j. default (mixed) (conditional) [This is required if there is the options key. This indicates the default value of the button from the options array.]
 */

SpAddonsConfig::addonConfig([
	'type'       => 'content',
	'addon_name' => 'text_block',
	'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_TEXT_BLOCK'),
	'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_TEXT_BLOCK_DESC'),
	'category'   => 'Content',
	'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M23.055 10.419c0-.885-.717-1.602-1.602-1.602H10.547a1.602 1.602 0 000 3.204h3.825v10.118a1.628 1.628 0 103.256 0V12.02h3.825c.885 0 1.602-.718 1.602-1.602z" fill="currentColor"/><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M5.18 3.42c-.95 0-1.721.758-1.721 1.693V8.5c0 .668-.55 1.21-1.23 1.21A1.22 1.22 0 011 8.5V5.113C1 2.84 2.872 1 5.18 1h2.951c.68 0 1.23.542 1.23 1.21 0 .668-.55 1.21-1.23 1.21h-2.95zM26.82 28.58c.95 0 1.721-.758 1.721-1.693V23.5c0-.668.55-1.21 1.23-1.21.678 0 1.229.542 1.229 1.21v3.387C31 29.16 29.128 31 26.82 31h-2.951a1.22 1.22 0 01-1.23-1.21c0-.668.55-1.21 1.23-1.21h2.95zM26.82 3.42c.95 0 1.721.758 1.721 1.693V8.5c0 .668.55 1.21 1.23 1.21A1.22 1.22 0 0031 8.5V5.113C31 2.84 29.128 1 26.82 1h-2.951c-.68 0-1.23.542-1.23 1.21 0 .668.55 1.21 1.23 1.21h2.95zM5.18 28.58c-.95 0-1.721-.758-1.721-1.693V23.5c0-.668-.55-1.21-1.23-1.21A1.22 1.22 0 001 23.5v3.387C1 29.16 2.872 31 5.18 31h2.951a1.22 1.22 0 001.23-1.21c0-.668-.55-1.21-1.23-1.21h-2.95z" fill="currentColor"/></svg>',
	'settings' => [
		'content' => [
			'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_CONTENT'),
			'fields' => [
				'text' => [
					'type' => 'editor',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_CONTENT'),
					'std'  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer adipiscing erat eget risus sollicitudin pellentesque et non erat. Maecenas nibh dolor, malesuada et bibendum a, sagittis accumsan ipsum. Pellentesque ultrices ultrices sapien, nec tincidunt nunc posuere ut. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam scelerisque tristique dolor vitae tincidunt. Aenean quis massa uada mi elementum elementum. Nec sapien convallis vulputate rhoncus vel dui.',
				],

				'text_typography' => [
					'type' => 'typography',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_TYPOGRAPHY'),
					'fallbacks'   => [
						'font' => 'text_font_family',
						'size' => 'text_fontsize',
						'line_height' => 'text_lineheight',
						'weight' => 'text_fontweight',
					],
				],

				'global_text_color' => [
					'type' => 'color',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
				],

				'alignment_separator' => [
					'type' => 'separator',
				],

				'alignment' => [
					'type' => 'alignment',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_ALIGNMENT'),
					'responsive' => true,
				],
			],
		],

		'dropcap' => [
			'title' => Text::_('COM_SPPAGEBUILDER_ADDON_DROPCAP'),
			'fields' => [
				'dropcap' => [
					'type' => 'checkbox',
					'title' => Text::_('COM_SPPAGEBUILDER_ADDON_DROPCAP'),
					'std' => 0,
					'is_header' => 1
				],

				'dropcap_font_size' => [
					'type' 	=> 'slider',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_FONT_SIZE'),
					'depends' => [['dropcap', '=', 1]],
					'min' 	=> 0,
					'max' 	=> 200,
					'responsive' => true,
				],

				'dropcap_color' => [
					'type' => 'color',
					'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_COLOR'),
					'depends' => [['dropcap', '=', 1]],
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
						'font' => 'font_family',
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
					'type'        => 'slider',
					'title'       => Text::_('COM_SPPAGEBUILDER_GLOBAL_MARGIN_TOP'),
					'placeholder' => '10',
					'max'         => 400,
					'responsive'  => true
				],

				'title_margin_bottom' => [
					'type'        => 'slider',
					'title'       => Text::_('COM_SPPAGEBUILDER_GLOBAL_MARGIN_BOTTOM'),
					'placeholder' => '10',
					'max'         => 400,
					'responsive'  => true
				],
			],
		],
	],
]);
