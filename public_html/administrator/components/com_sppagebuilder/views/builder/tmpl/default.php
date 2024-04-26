<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2023 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */

//no direct access
defined('_JEXEC') or die('restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Language\Text;

SppagebuilderHelper::addStylesheet('sppagebuilder.css');

// Don't show this page next time (checkbox)
$doc = Factory::getDocument();
$doc->addScriptDeclaration('
window.addEventListener( "DOMContentLoaded", function()
    {
        document.getElementById("sp-pagebuilder-input-check").onclick = function() {  
			let cookie_name  = "sppb_no_defalt_page";
			let cookie_value = (this.checked == true) ? "enable" : "";
			let exdays 		 =  7;
				
			const date 		 = new Date();
  			date.setTime(date.getTime() + (exdays*24*60*60*1000));
				
  			let expires 	 = (this.checked == true) ? "expires="+ date : "expires=0";

			document.cookie  = cookie_name + "=" + cookie_value + ";" + expires + ";path=/";	
		}
    }
);
');	

$dashboardLink = Uri::root(true) . '/administrator/index.php?option=com_sppagebuilder&task=page.redirectSite&landing=dashboard';
$createPageLink = Uri::root(true) . '/administrator/index.php?option=com_sppagebuilder&task=page.redirectSite&landing=create';
$settingsLink 	= Uri::root(true) . '/administrator/index.php?option=com_sppagebuilder&task=page.redirectSite&landing=settings';
$assetUrl 		= Uri::root(true) . '/administrator/components/com_sppagebuilder/assets';

// check if user directly want to go to the dashboard page.
if (Factory::getApplication()->input->cookie->get('sppb_no_defalt_page'))
{
	Factory::getApplication()->redirect($dashboardLink);
}
?>

<div class="sp-pagebuilder-admin">
	<div class="sp-pagebuilder-logo">
		<svg width="50" height="58" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M41.738 32.156c1.55-1.614 4.275-1.553 5.834 0 1.595 1.595 1.542 4.222 0 5.834-1.612 1.683-3.243 3.35-4.971 4.928-10.595 9.696-23.915 15.513-38.432 15.029-2.238-.07-4.125-1.825-4.125-4.125 0-2.178 1.894-4.196 4.125-4.125 4.575.149 8.021-.15 12.262-1.226a46.799 46.799 0 0 0 4.882-1.534c.403-.15.803-.31 1.2-.476l.528-.239a61.162 61.162 0 0 0 2.054-.986 53.636 53.636 0 0 0 7.598-4.733c.176-.132.345-.265.52-.388a46.872 46.872 0 0 0 .537-.432c.478-.38.944-.775 1.403-1.173a75.72 75.72 0 0 0 2.61-2.36c1.365-1.289 2.678-2.636 3.975-3.994Zm-23.289-2.037a28.698 28.698 0 0 0 3.79-2.422.768.768 0 0 0-.017-1.236l-9.476-6.832a.768.768 0 0 0-1.216.626l.034 11.397a.767.767 0 0 0 .962.74 25.67 25.67 0 0 0 5.923-2.273Z" fill="#2684FF" />
			<path d="M36.175 23.439c-8.744-6.69-17.496-13.381-26.25-20.08C8.7 2.423 7.837 1.762 6.61.83L.635 2.037C-.61 4.161.317 6.319 2.098 7.677c8.745 6.688 17.498 13.39 26.25 20.078 1.225.935 2.45 1.87 3.668 2.804 1.77 1.357 4.618.273 5.64-1.48 1.243-2.126.3-4.285-1.481-5.642v.002Z" fill="url(#a)" />
			<path d="M16.721 42.874c2.574-.59 5.113-1.692 7.467-2.847 4.9-2.388 9.44-5.826 12.825-10.11.704-.89 1.207-1.727 1.207-2.918 0-1.013-.45-2.221-1.207-2.917-1.49-1.367-4.408-1.808-5.835 0a30.774 30.774 0 0 1-4.363 4.52c-.273.23-.556.461-.83.69-.678.554.644-.468 0 0-1.886 1.364-3.851 2.59-5.95 3.613-.45.221-.898.423-1.347.626-.292.131-.97.334.307-.123-.22.078-.432.176-.643.264-.652.256-1.314.502-1.984.722-.597.195-1.206.389-1.825.529C12.438 35.4 11 37.947 11.66 40c.707 2.194 2.803 3.385 5.06 2.873ZM6.735.916A4.065 4.065 0 0 0 4.178 0C1.94 0 .053 1.887.053 4.125v49.573c0 2.231 1.893 4.126 4.125 4.126 2.24 0 4.125-1.886 4.125-4.126V2.115L6.733.916h.002Z" fill="#2684FF" />
			<defs>
				<linearGradient id="a" x1="8.29177" y1="10.3396" x2="28.7629" y2="26.4061" gradientUnits="userSpaceOnUse">
					<stop stop-color="#0052CC" />
					<stop offset="1" stop-color="#2684FF" />
				</linearGradient>
			</defs>
		</svg>
	</div>

	<div class="sp-pagebuilder-title">
		<?php echo Text::_('COM_SPPAGEBUILDER'); ?>
	</div>

	<div class="sp-pagebuilder-subtitle">
		<?php echo Text::_("COM_SPPAGEBUILDER_DASHBOARD_LANDING_SUBTITLE") ?>
	</div>

	<div class="sp-pagebuilder-text-center sp-pagebuilder-show-checkbox">
		<input type="checkbox" class="sp-pagebuilder-input-check" id="sp-pagebuilder-input-check" />
		<span>
			<?php echo Text::_("COM_SPPAGEBUILDER_DASHBOARD_LANDING_DONOT_SHOW_THIS_PAGE") ?>
		</span>
	</div>

	<div class="sp-pagebuilder-text-center">
		<a href="<?php echo $dashboardLink; ?>" target="_blank" rel="nofollow noreferrer noopener" class="sp-pagebuilder-button sp-pagebuilder-button-fw"><?php echo Text::_("COM_SPPAGEBUILDER_DASHBOARD_LANDING_GET_STARTED") ?></a>
	</div>

	<div class="sp-pagebuilder-card sp-pagebuilder-quickstart">
		<div class="sp-pagebuilder-quickstart-image">
			<img src="<?php echo $assetUrl . '/images/quickstart.jpg'; ?>" srcset="<?php echo $assetUrl . '/images/quickstart-2x.jpg'; ?> 2x" alt="SP Page Builder">
		</div>

		<div class="sp-pagebuilder-quickstart-actions">
			<div class="sp-pagebuilder-quickstart-list-group">
				<div class="sp-pagebuilder-quickstart-list-item">
					<div class="sp-pagebuilder-quickstart-list-item-icon">
						<svg width="48" height="48" fill="none" xmlns="http://www.w3.org/2000/svg">
							<circle opacity="0.1" cx="24" cy="24" r="24" fill="#36F" />
							<path d="M20.25 25.25h7.5-7.5ZM24 21.5V29v-7.5Zm6.25 13.75h-12.5a2.5 2.5 0 0 1-2.5-2.5v-17.5a2.5 2.5 0 0 1 2.5-2.5h6.983c.331 0 .649.132.883.366l6.768 6.768c.234.234.366.552.366.883V32.75a2.5 2.5 0 0 1-2.5 2.5Z" stroke="#36F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</div>
					<div class="sp-pagebuilder-quickstart-list-item-content">
						<div class="sp-pagebuilder-quickstart-list-item-title">
							<a href="<?php echo $createPageLink; ?>" target="_blank" rel="nofollow noreferrer noopener"><?php echo Text::_("COM_SPPAGEBUILDER_DASHBOARD_LANDING_CREATE_NEW_PAGE") ?></a>
						</div>
						<div class="sp-pagebuilder-quickstart-list-item-subtitle">
							<?php echo Text::_("COM_SPPAGEBUILDER_DASHBOARD_LANDING_MADE_EASIER") ?>
						</div>
					</div>
					<span class="sp-pagebuilder-list-caret">
						<svg width="32" height="32" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M12 6.667 21.333 16 12 25.333" stroke="#B3B6CB" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</span>
				</div>

				<div class="sp-pagebuilder-quickstart-list-item">
					<div class="sp-pagebuilder-quickstart-list-item-icon">
						<svg width="48" height="48" fill="none" xmlns="http://www.w3.org/2000/svg">
							<circle opacity="0.1" cx="24" cy="24" r="24" fill="#36F" />
							<path d="M21.906 14.396c.533-2.195 3.655-2.195 4.188 0a2.154 2.154 0 0 0 3.216 1.333c1.929-1.175 4.137 1.032 2.963 2.962a2.155 2.155 0 0 0 1.33 3.215c2.196.533 2.196 3.655 0 4.188a2.154 2.154 0 0 0-1.332 3.216c1.175 1.929-1.032 4.137-2.962 2.963a2.155 2.155 0 0 0-3.215 1.33c-.533 2.196-3.655 2.196-4.188 0a2.154 2.154 0 0 0-3.216-1.332c-1.929 1.175-4.138-1.032-2.963-2.962a2.155 2.155 0 0 0-1.33-3.215c-2.196-.533-2.196-3.655 0-4.188a2.154 2.154 0 0 0 1.332-3.216c-1.175-1.929 1.032-4.138 2.962-2.963 1.245.76 2.87.088 3.215-1.33Z" stroke="#36F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
							<path d="M27.75 24a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0v0Z" stroke="#36F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</div>
					<div class="sp-pagebuilder-quickstart-list-item-content">
						<div class="sp-pagebuilder-quickstart-list-item-title">
							<a target="_blank" href="<?php echo $settingsLink; ?>" target="_blank" rel="nofollow noreferrer noopener"><?php echo Text::_("COM_SPPAGEBUILDER_DASHBOARD_LANDING_SETTINGS") ?></a>
						</div>
						<div class="sp-pagebuilder-quickstart-list-item-subtitle">
							<?php echo Text::_("COM_SPPAGEBUILDER_DASHBOARD_LANDING_MANAGE_SETTINGS") ?>
						</div>
					</div>
					<span class="sp-pagebuilder-list-caret">
						<svg width="32" height="32" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M12 6.667 21.333 16 12 25.333" stroke="#B3B6CB" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</span>
				</div>

				<div class="sp-pagebuilder-quickstart-list-item">
					<div class="sp-pagebuilder-quickstart-list-item-icon">
						<svg width="48" height="48" fill="none" xmlns="http://www.w3.org/2000/svg">
							<circle opacity="0.1" cx="24" cy="24" r="24" fill="#36F" />
							<path d="M20.25 23h7.5-7.5Zm0 5h7.5-7.5Zm10 6.25h-12.5a2.5 2.5 0 0 1-2.5-2.5v-17.5a2.5 2.5 0 0 1 2.5-2.5h6.983c.331 0 .649.132.883.366l6.768 6.768c.234.234.366.552.366.883V31.75a2.5 2.5 0 0 1-2.5 2.5Z" stroke="#36F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</div>
					<div class="sp-pagebuilder-quickstart-list-item-content">
						<div class="sp-pagebuilder-quickstart-list-item-title">
							<a target="_blank" href="https://www.joomshaper.com/documentation/sp-page-builder"><?php echo Text::_("COM_SPPAGEBUILDER_DOCUMENTATION") ?></a>
						</div>
						<div class="sp-pagebuilder-quickstart-list-item-subtitle">
							<?php echo Text::_("COM_SPPAGEBUILDER_DASHBOARD_LANDING_GUIDED_DOC") ?>
						</div>
					</div>
					<span class="sp-pagebuilder-list-caret">
						<svg width="32" height="32" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M12 6.667 21.333 16 12 25.333" stroke="#B3B6CB" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</span>
				</div>

				<div class="sp-pagebuilder-quickstart-list-item">
					<div class="sp-pagebuilder-quickstart-list-item-icon">
						<svg width="48" height="48" fill="none" xmlns="http://www.w3.org/2000/svg">
							<circle opacity="0.1" cx="24" cy="24" r="24" fill="#36F" />
							<path d="m31.955 16.045-4.42 4.42 4.42-4.42Zm-4.42 11.49 4.42 4.42-4.42-4.42Zm-7.07-7.07-4.42-4.42 4.42 4.42Zm0 7.07-4.42 4.42 4.42-4.42ZM35.25 24a11.25 11.25 0 1 1-22.499 0 11.25 11.25 0 0 1 22.499 0ZM29 24a5 5 0 1 1-10 0 5 5 0 0 1 10 0v0Z" stroke="#36F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</div>
					<div class="sp-pagebuilder-quickstart-list-item-content">
						<div class="sp-pagebuilder-quickstart-list-item-title">
							<a target="_blank" href="https://www.joomshaper.com/forum/category/page-builder"><?php echo Text::_("COM_SPPAGEBUILDER_DASHBOARD_LANDING_SUPPORT") ?></a>
						</div>
						<div class="sp-pagebuilder-quickstart-list-item-subtitle">
							<?php echo Text::_("COM_SPPAGEBUILDER_DASHBOARD_LANDING_FAST_EXPERTS") ?>
						</div>
					</div>
					<span class="sp-pagebuilder-list-caret">
						<svg width="32" height="32" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M12 6.667 21.333 16 12 25.333" stroke="#B3B6CB" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
					</span>
				</div>
			</div>
		</div>
	</div>

	<div class="sp-pagebuilder-card sp-pagebuilder-rating">
		<div>
			<div class="sp-pagebuilder-rating-icon">
				<svg width="84" height="84" fill="none" xmlns="http://www.w3.org/2000/svg">
					<circle opacity="0.1" cx="42" cy="42" r="42" fill="#36F" />
					<path d="m41.188 26.16-4.133 8.276-9.348 1.307c-1.653.25-2.289 2.24-1.08 3.423l6.676 6.41-1.59 9.023c-.254 1.619 1.526 2.863 2.989 2.116l8.33-4.294 8.266 4.294c1.462.747 3.243-.498 2.989-2.116l-1.59-9.023 6.676-6.41c1.209-1.182.573-3.174-1.08-3.423l-9.284-1.307-4.197-8.276c-.7-1.432-2.861-1.494-3.624 0Z" fill="#36F" />
				</svg>
			</div>

			<div class="sp-pagebuilder-rating-content">
				<div class="sp-pagebuilder-rating-title">
					<?php echo Text::_("COM_SPPAGEBUILDER_DASHBOARD_LANDING_RATE_US_JOOMLA") ?>
				</div>
				<div class="sp-pagebuilder-rating-subtitle">
					<?php echo Text::_("COM_SPPAGEBUILDER_DASHBOARD_LANDING_USEFUL_PRODUCT") ?>
				</div>
			</div>
		</div>

		<div>
			<a target="_blank" href="https://extensions.joomla.org/extension/sp-page-builder" class="sp-pagebuilder-button-outline"><svg width="18" height="18" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M8.04.62 5.853 5.292l-4.948.738C.029 6.172-.308 7.297.332 7.964l3.534 3.62-.841 5.096c-.135.913.808 1.616 1.582 1.195l4.41-2.425 4.376 2.425c.774.421 1.717-.282 1.582-1.195l-.841-5.096 3.534-3.62c.64-.667.303-1.792-.572-1.933l-4.915-.738L9.96.62a1.053 1.053 0 0 0-1.918 0Z" fill="#36F" />
				</svg><?php echo Text::_("COM_SPPAGEBUILDER_DASHBOARD_LANDING_RATE_US") ?></a>
		</div>
	</div>

	<div class="text-center sp-pagebuilder-version">
		<?php echo Text::_("COM_SPPAGEBUILDER_DASHBOARD_PAGES_LANGUAGE_COLUMN_VERSION") . " " . SppagebuilderHelper::getVersion(); ?>
	</div>
</div>

<style>
	.subhead-collapse,
	.btn-subhead,
	.subhead {
		display: none !important;
	}
</style>