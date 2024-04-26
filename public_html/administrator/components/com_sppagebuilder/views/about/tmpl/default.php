<?php
/**
* @package SP Page Builder
* @author JoomShaper http://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2021 JoomShaper
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/

//no direct accees
defined ('_JEXEC') or die ('restricted access');

use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;

SppagebuilderHelper::loadAssets('css');
?>

<div class="sp-pagebuilder-admin">
	<div class="sp-pagebuilder-main">
		<?php
		// sidebar
		echo LayoutHelper::render('sidebar');
		?>

		<div class="sp-pagebuilder-content">
			<div class="sp-pagebuilder-row">
				<div class="col-md-12">
					<div id="j-main-container" class="j-main-container">
						<div class="sp-pagebuilder-about">
							<div class="sp-pagebuilder-logo-wrapper">
								<div class="sp-pagebuilder-logo">
									<svg width="60" height="70" viewBox="0 0 60 70" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path d="M50.8268 38.7726C52.6805 36.8435 55.9371 36.9169 57.8009 38.7726C59.7076 40.6793 59.6444 43.8197 57.8009 45.7467C55.8739 47.7594 53.9244 49.7518 51.8586 51.638C39.1931 63.2289 23.2709 70.1827 5.91715 69.6035C3.2417 69.5199 0.986328 67.4216 0.986328 64.6727C0.986328 62.0686 3.24986 59.6562 5.91715 59.7419C11.3863 59.9193 15.5055 59.5624 20.575 58.2757C22.5524 57.7715 24.5007 57.1595 26.4113 56.4424C26.8928 56.2623 27.3708 56.0726 27.8448 55.8735L28.477 55.588C29.2988 55.2107 30.1206 54.8192 30.9322 54.4093C34.1122 52.7795 37.1503 50.8866 40.0149 48.7505C40.2249 48.5935 40.4268 48.4344 40.6368 48.2876C40.6042 48.308 39.9639 48.8137 40.3942 48.4772C40.6899 48.2448 40.9855 48.0123 41.2792 47.7717C41.8502 47.3169 42.4069 46.8438 42.9554 46.3687C44.0199 45.451 45.0517 44.515 46.0754 43.5464C47.7068 42.0068 49.277 40.3958 50.8268 38.7726ZM22.9874 36.3378C24.5712 35.4926 26.0863 34.5244 27.5186 33.4421C27.6329 33.3552 27.7253 33.2427 27.7882 33.1136C27.8512 32.9845 27.883 32.8424 27.881 32.6988C27.879 32.5551 27.8433 32.414 27.7768 32.2867C27.7103 32.1594 27.6149 32.0494 27.4982 31.9657L16.1703 23.7987C16.033 23.6997 15.871 23.6407 15.7022 23.6282C15.5335 23.6156 15.3645 23.6499 15.214 23.7274C15.0636 23.8048 14.9374 23.9224 14.8496 24.067C14.7618 24.2117 14.7157 24.3778 14.7164 24.547L14.7572 38.171C14.7576 38.3111 14.79 38.4493 14.8521 38.5749C14.9142 38.7005 15.0041 38.8102 15.1152 38.8956C15.2262 38.981 15.3553 39.0399 15.4926 39.0677C15.6299 39.0956 15.7718 39.0916 15.9073 39.0561C18.3706 38.4462 20.7488 37.5331 22.9874 36.3378Z" fill="#2684FF"/>
										<path d="M44.1766 28.3522C33.7236 20.3544 23.2624 12.3566 12.7972 4.34856C11.333 3.22903 10.3012 2.43985 8.835 1.3244L1.69162 2.76817C0.205037 5.30699 1.31233 7.8866 3.44127 9.50981C13.8943 17.5056 24.3575 25.5156 34.8207 33.5114C36.2849 34.6289 37.749 35.7464 39.205 36.8638C41.3217 38.485 44.7252 37.1901 45.9467 35.0938C47.4333 32.5529 46.3056 29.9713 44.1766 28.3501V28.3522Z" fill="url(#paint0_linear)"/>
										<path d="M20.9252 51.585C24.0024 50.8794 27.0367 49.5621 29.8508 48.1815C35.7095 45.3266 41.1358 41.2176 45.1816 36.0951C46.0238 35.0327 46.6254 34.0314 46.6254 32.608C46.6254 31.3967 46.0871 29.953 45.1816 29.121C43.4014 27.4876 39.9123 26.9594 38.2075 29.121C36.6577 31.0955 34.9098 32.9063 32.9912 34.5249C32.6649 34.8002 32.3264 35.0755 32.0002 35.3487C31.1885 36.0115 32.7689 34.79 32.0002 35.3487C29.7448 36.9801 27.3956 38.4463 24.8874 39.6678C24.349 39.9329 23.8127 40.1735 23.2764 40.4162C22.9277 40.5732 22.1161 40.8159 23.6434 40.2694C23.3804 40.3632 23.1275 40.4794 22.8747 40.5854C22.0957 40.8913 21.3045 41.185 20.5031 41.448C19.7893 41.6805 19.0613 41.913 18.3211 42.0802C15.8047 42.6512 14.0877 45.6957 14.8769 48.1509C15.7211 50.7734 18.2273 52.1967 20.9252 51.585ZM8.98761 1.42843C8.12438 0.723725 7.04517 0.337111 5.93082 0.333374C3.25537 0.333374 1 2.58875 1 5.2642V64.5238C1 67.1911 3.26353 69.4567 5.93082 69.4567C8.60831 69.4567 10.8616 67.2013 10.8616 64.5238V12.6931V6.51831V2.862L8.98557 1.42843H8.98761Z" fill="#2684FF"/>
										<defs>
										<linearGradient id="paint0_linear" x1="10.845" y1="12.6931" x2="35.3163" y2="31.899" gradientUnits="userSpaceOnUse">
										<stop stop-color="#0052CC"/>
										<stop offset="1" stop-color="#2684FF"/>
										</linearGradient>
										</defs>
									</svg>
								</div>
							</div>

							<div class="sp-pagebuilder-information">
								<div class="sp-pagebuilder-title">
									<h2>SP Page Builder</h2> <span>Version: <?php echo SppagebuilderHelper::getVersion(); ?></span>
								</div>
								<div class="pagebuilder-social-links">
									<a href="https://www.facebook.com/joomshaper" target="_blank" title="Like us on FaceBook"><span class="fab fa-facebook-square" area-hidden="true"></span></a>
									<a href="https://twitter.com/joomshaper" target="_blank" title="Follow us on Twitter"><span class="fab fa-twitter-square" area-hidden="true"></span></a>
									<a href="https://www.youtube.com/user/joomshaper" target="_blank" title="Subscribe us on YouTube"><span class="fab fa-youtube" area-hidden="true"></span></a>
								</div>
							</div>

							<div class="sp-pagebuilder-intro">
								SP Page Builder is trusted by <strong>200,000+</strong> people worldwide. This Joomla page builder is an <br />extremely powerful drag & drop tool. Whether you're a beginner or a professional, it lets you <br />build a site independently!
							</div>

							<div class="sp-pagebuilder-ratings-bar">
								<div class="sp-pagebuilder-ratings-thumbs-up">
									<span class="fas fa-thumbs-up" area-hidden="true"></span>
								</div>
								<div class="sp-pagebuilder-ratings-content">
									<h3>Rate us on Joomla Extension Directory</h3>
									If you found this product useful for you then please rate this extension.
								</div>
								<div>
									<a class="btn btn-primary" href="https://extensions.joomla.org/extension/sp-page-builder/" target="_blank">Joomla Extension Directory</a>
								</div>
							</div>

							<div class="sp-pagebuilder-row">
								<div class="col-md-3">
									<div class="sp-pagebuilder-card">
										<div class="sp-pagebuilder-card-icon">
											<span class="fas fa-plus-square" area-hidden="true"></span>
										</div>

										<h3 class="sp-pagebuilder-card-title">
											Create a new page
										</h3>

										<div class="sp-pagebuilder-card-content">
											Writing hundreds of lines of code, design your website visually with 50+ addons.
										</div>
										<a class="btn btn-default btn-sm" href="<?php echo Route::_('index.php?option=com_sppagebuilder&view=page&layout=edit'); ?>">Create a Page</a>
									</div>
								</div>

								<div class="col-md-3">
									<div class="sp-pagebuilder-card">
										<div class="sp-pagebuilder-card-icon">
											<span class="fas fa-users" area-hidden="true"></span>
										</div>

										<h3 class="sp-pagebuilder-card-title">
											Community Support
										</h3>

										<div class="sp-pagebuilder-card-content">
											Writing hundreds of lines of code, design your website visually with 50+ addons.
										</div>
										<a class="btn btn-default btn-sm" href="https://www.facebook.com/groups/sppagebuilder">Join Group</a>
									</div>
								</div>

								<div class="col-md-3">
									<div class="sp-pagebuilder-card">
										<div class="sp-pagebuilder-card-icon">
											<span class="fas fa-file-alt" area-hidden="true"></span>
										</div>

										<h3 class="sp-pagebuilder-card-title">
											Documentation
										</h3>

										<div class="sp-pagebuilder-card-content">
											Writing hundreds of lines of code, design your website visually with 50+ addons.
										</div>
										<a class="btn btn-default btn-sm" href="https://www.joomshaper.com/documentation/sp-page-builder/sp-page-builder-3">Explore Documentation</a>
									</div>
								</div>

								<div class="col-md-3">
									<div class="sp-pagebuilder-card">
										<div class="sp-pagebuilder-card-icon">
											<span class="fas fa-life-ring" area-hidden="true"></span>
										</div>

										<h3 class="sp-pagebuilder-card-title">
											Support
										</h3>

										<div class="sp-pagebuilder-card-content">
											Writing hundreds of lines of code, design your website visually with 50+ addons.
										</div>
										<a class="btn btn-default btn-sm" href="https://www.joomshaper.com/forum">Get Support</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php echo LayoutHelper::render('footer'); ?>
		</div>
	</div>
</div>

<style>
	.subhead-collapse,
	.btn-subhead,
	.subhead {
		display: none !important;
	}
</style>
