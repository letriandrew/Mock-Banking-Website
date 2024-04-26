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

SpAddonsConfig::addonConfig(
	[
		'type'       => 'content',
		'addon_name' => 'module',
		'title'      => Text::_('COM_SPPAGEBUILDER_ADDON_MODULE'),
		'desc'       => Text::_('COM_SPPAGEBUILDER_ADDON_MODULE_DESC'),
		'category'   => 'General',
		'icon'       => '<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M5.005 1C6.821.994 8.391 2.176 8.849 3.923c.065.25.136.315.407.218 1.234-.443 2.449-.289 3.638.212.917.386 1.712.97 2.461 1.62.117.102.13.16.013.275-.846.803-1.686 1.613-2.52 2.429-.135.135-.22.096-.348 0-.375-.27-.789-.482-1.267-.514-1.124-.077-2.255.79-2.565 1.972-.265 1.015.097 1.87.724 2.64.51.623 1.13 1.144 1.7 1.709a402.759 402.759 0 004.115 4.015c.245.238.22.353-.013.572-.808.77-1.596 1.554-2.384 2.344-.155.16-.246.2-.427.013-2.358-2.364-4.742-4.709-7.094-7.085-1.189-1.208-2.01-3.335-.943-5.236.168-.308-.11-.25-.24-.283-1-.244-1.815-.77-2.383-1.625-.885-1.336-.956-2.724-.22-4.13.66-1.285 2.01-2.063 3.502-2.069z" fill="currentColor"/><path opacity=".5" d="M28.129 10.732c0 1.337-.524 2.493-1.28 3.553-.239.327-.51.63-.762.95-.116.148-.194.135-.323 0a207.68 207.68 0 00-2.352-2.415c-.155-.154-.168-.263-.039-.456.485-.707.672-1.471.24-2.261-.479-.887-1.222-1.465-2.262-1.548-1.085-.084-1.919.462-2.636 1.194-1.693 1.748-3.36 3.514-5.046 5.262-.31.32-.226.347-.581.006-.834-.803-1.66-1.612-2.5-2.409-.156-.148-.175-.238-.007-.398 2.351-2.326 4.677-4.69 7.055-6.99 1.286-1.24 3.36-1.895 5.188-.905.226.122.252.025.304-.167.413-1.632 1.44-2.666 3.095-3.039 2.203-.494 4.432 1.067 4.736 3.283.284 2.081-.912 3.88-2.966 4.445-.226.058-.24.129-.168.328.097.25.155.507.22.764.077.27.09.534.084.803zM11.13 27.936a3.725 3.725 0 01-1.861-.43c-.252-.136-.297-.046-.349.173a3.93 3.93 0 01-3.67 2.98c-1.906.052-3.514-1.04-4.044-2.755-.627-2.03.626-4.195 2.778-4.754.29-.077.317-.148.213-.418-.484-1.297-.245-2.537.394-3.726.375-.694.847-1.31 1.376-1.888.143-.154.233-.16.388-.013.788.79 1.583 1.567 2.371 2.351.09.09.149.154.052.296-.924 1.432-.53 2.723 1.04 3.43 1.163.52 2.203.186 3.108-.572.788-.662 1.499-1.407 2.235-2.12 1.183-1.15 2.365-2.3 3.54-3.456.169-.167.272-.199.46-.013.82.842 1.66 1.67 2.5 2.5.142.14.162.23 0 .378-2.41 2.236-4.8 4.49-7.23 6.713a4.933 4.933 0 01-3.301 1.324z" fill="currentColor"/><path d="M27.01 31a3.803 3.803 0 01-3.682-2.884c-.084-.322-.18-.328-.465-.238-1.39.417-2.694.103-3.935-.559-.691-.373-1.305-.86-1.893-1.381-.148-.135-.18-.219-.02-.373.789-.77 1.57-1.548 2.346-2.338.162-.167.252-.135.414-.013.62.475 1.292.765 2.093.501.86-.282 1.667-1.387 1.712-2.3.045-1.021-.4-1.843-1.085-2.543-1.622-1.664-3.263-3.315-4.891-4.966-.22-.219-.575-.437-.595-.681-.02-.25.381-.443.601-.655.64-.63 1.292-1.253 1.932-1.89.149-.153.233-.14.375.007 2.274 2.326 4.574 4.638 6.829 6.977 1.221 1.265 1.893 3.417.93 5.203-.122.225-.013.264.168.308 1.628.424 2.598 1.478 2.946 3.09.524 2.429-1.35 4.754-3.78 4.735z" fill="currentColor"/></svg>',
		'settings' => [
			'content' => [
				'title' => Text::_('COM_SPPAGEBUILDER_GLOBAL_CONTENT'),
				'fields' => [
					'module_type' => [
						'type'   => 'select',
						'title'  => Text::_('COM_SPPAGEBUILDER_ADDON_MODULE_TYPE'),
						'desc'   => Text::_('COM_SPPAGEBUILDER_ADDON_MODULE_TYPE_DESC'),
						'values' => [
							'module'   => Text::_('COM_SPPAGEBUILDER_ADDON_MODULE_TYPE_MODULE'),
							'position' => Text::_('COM_SPPAGEBUILDER_ADDON_MODULE_POSITION')
						],
						'std' => 'module',
					],

					'id' => [
						'type'    => 'module',
						'module'  => 'module',
						'title'   => Text::_('COM_SPPAGEBUILDER_ADDON_MODULE_SELECT'),
						'desc'    => Text::_('COM_SPPAGEBUILDER_ADDON_MODULE_SELECT_DESC'),
						'depends' => [['module_type', '=', 'module']],
					],

					'position' => [
						'type'    => 'module',
						'module'  => 'position',
						'title'   => Text::_('COM_SPPAGEBUILDER_ADDON_MODULE_POSITION'),
						'desc'    => Text::_('COM_SPPAGEBUILDER_ADDON_MODULE_POSITION_DESC'),
						'depends' => [['module_type', '=', 'position']]
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
				]
			]
		],

		'attr' => [],
	]
);
