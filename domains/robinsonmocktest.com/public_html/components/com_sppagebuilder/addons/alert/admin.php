<?php

/**
 * @package SP Page Builder
 * @author JoomShaper https://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license https://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;

SpAddonsConfig::addonConfig(
	[
		'type'       => 'content',
		'addon_name' => 'alert',
		'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_ALERT'),
		'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_ALERT_DESC'),
		'category'   => 'Content',
		'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M8.954 3.754a9.402 9.402 0 0116.05 6.648c0 4.753 1.017 7.736 1.978 9.497.482.884.955 1.47 1.292 1.826a4.631 4.631 0 00.48.443l.014.01a1 1 0 01-.564 1.826H3a1 1 0 01-.563-1.827l.013-.01a4.617 4.617 0 00.48-.442c.337-.356.81-.942 1.292-1.826.961-1.762 1.979-4.744 1.979-9.498 0-2.493.99-4.884 2.753-6.647zM5.27 22.004h20.664c-.23-.33-.47-.711-.708-1.147-1.14-2.089-2.222-5.407-2.222-10.455a7.402 7.402 0 00-14.803 0c0 5.048-1.083 8.366-2.223 10.455a12.14 12.14 0 01-.708 1.147z" fill="currentColor"/><path opacity=".5" fill-rule="evenodd" clip-rule="evenodd" d="M12.678 27.74a1 1 0 011.367.363 1.8 1.8 0 003.115 0 1 1 0 011.73 1.003 3.8 3.8 0 01-6.575 0 1 1 0 01.363-1.366z" fill="currentColor"/></svg>',
		'settings' => [
			'content' => [
				'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_CONTENT'),
				'fields' => [
					'text' => [
						'type' => 'editor',
						'title'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_CONTENT'),
						'std'  => 'An alert box is a common user interface element in web and mobile applications that displays a message to the user, typically used to convey important information or to ask for confirmation before performing a task. Unlike regular webpage content, the alert box usually requires the user\'s immediate attention.'
					],

					'alrt_type' => [
						'type'   => 'select',
						'title'  => Text::_('COM_SPPAGEBUILDER_ADDON_ALERT_TYPE'),
						'desc'   => Text::_('COM_SPPAGEBUILDER_ADDON_ALERT_TYPE_DESC'),
						'values' => [
							'primary' => Text::_('COM_SPPAGEBUILDER_GLOBAL_PRIMARY'),
							'success' => Text::_('COM_SPPAGEBUILDER_GLOBAL_SUCCESS'),
							'info'    => Text::_('COM_SPPAGEBUILDER_GLOBAL_INFO'),
							'warning' => Text::_('COM_SPPAGEBUILDER_GLOBAL_WARNING'),
							'danger'  => Text::_('COM_SPPAGEBUILDER_GLOBAL_DANGER'),
							'light'   => Text::_('COM_SPPAGEBUILDER_GLOBAL_LIGHT'),
							'dark'    => Text::_('COM_SPPAGEBUILDER_GLOBAL_DARK'),
						],
						'inline'	=> true,
						'std' => 'primary'
					],

					'content_typography' => [
						'type'  => 'typography',
						'title'       => Text::_('COM_SPPAGEBUILDER_GLOBAL_TYPOGRAPHY'),
						'fallbacks'   => [
							'font' => 'text_font_family'
						],
					],

					'close' => [
						'type'  => 'checkbox',
						'title' => Text::_('COM_SPPAGEBUILDER_ADDON_ALERT_CLOSE_BUTTON'),
						'desc'  => Text::_('COM_SPPAGEBUILDER_ADDON_ALERT_CLOSE_BUTTON_DESC'),
						'std'   => 1
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
						'title'       => Text::_('COM_SPPAGEBUILDER_GLOBAL_TYPOGRAPHY'),
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
						'inline' => true,
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
				],
			],
		],

		'attr' => [],
	]
);
