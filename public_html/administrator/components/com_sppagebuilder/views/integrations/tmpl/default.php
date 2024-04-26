<?php
/**
* @package SP Page Builder
* @author JoomShaper http://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2021 JoomShaper
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('Restricted access');

use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Uri\Uri;

SppagebuilderHelper::loadAssets('css');

require_once JPATH_ADMINISTRATOR . '/components/com_sppagebuilder/helpers/integrations.php';
$integrations = SppagebuilderHelperIntegrations::integrations();

?>

<div class="sp-pagebuilder-admin">
	<div class="sp-pagebuilder-main">
		<?php
		// sidebar
		echo LayoutHelper::render('sidebar');
		?>

		<div class="sp-pagebuilder-content">
			<div id="j-main-container" class="j-main-container">
				<div class="sp-pagebuilder-integrations">
						<div class="sp-pagebuilder-integrations-list">
						<?php foreach ($integrations as $key => $item) : ?>
							<?php
								$path = $item['group'] . '/' . $item['name'];
							?>
							<div class="sp-pagebuilder-integration-list-item">
								<div>
									<img class="sp-pagebuilder-integration-thumbnail" src="<?php echo Uri::root(true) .'/administrator/components/com_sppagebuilder/assets/img/integrations/'. $item['group'] . '.png'; ?>" alt="<?php echo $item['title']; ?>">
									<div class="sp-pagebuilder-integration-footer">
										<div class="sp-pagebuilder-integration-title">
											<?php echo $item['title']; ?>
										</div>
										<div class="sp-pagebuilder-integration-actions">		
											<a class="btn btn-primary btn-sm sp-pagebuilder-btn-toggle" href="https://www.joomshaper.com/page-builder" target="_blank">Buy Pro</a>
										</div>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
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
