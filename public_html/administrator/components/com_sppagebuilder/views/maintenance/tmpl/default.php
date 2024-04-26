<?php
/**
* @package SP Page Builder
* @author JoomShaper http://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2021 JoomShaper
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/

// No Direct Access
defined('_JEXEC') or die('Resticted Aceess');

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Uri\Uri;

$doc = Factory::getDocument();
$input = Factory::getApplication()->input;

SppagebuilderHelper::loadAssets('css');
SppagebuilderHelper::addStylesheet('maintenance.css');
HTMLHelper::_('jquery.framework');
SppagebuilderHelper::addScript('maintenance.js');

$maintenancePath = JPATH_ADMINISTRATOR . '/components/com_sppagebuilder/helpers/maintenance.php';

if (!class_exists('Maintenance'))
{
	require_once $maintenancePath;
}

$maintenance = new Maintenance;
$maintenance->run();

$html = $maintenance->getBuffer('missing');
$errors = $maintenance->getErrors();

$data = [
	'base' => Uri::root() . 'administrator/index.php',
	'component' => $input->get('option'),
	'btnStatus' => empty($html) && empty($errors) ? 'disabled' : 'enabled'
];

$doc->addScriptOptions('config', $data);

Text::script('COM_SPPAGEBUILDER_MAINTENANCE_UNABLE_TO_FIX');
Text::script('COM_SPPAGEBUILDER_MAINTENANCE_ISSUE_MESSAGE');
Text::script('COM_SPPAGEBUILDER_MAINTENANCE_IS_UPTODATE');
Text::script('COM_SPPAGEBUILDER_MAINTENANCE_PROGRESS');
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
						<div class='maintenance-window'>
							<div class='maintenance-window-wrapper'>
								<?php if (!empty($html)): ?>
									<div>
										<div class="alert alert-info">
											<p style="font-size: 16px; margin-bottom: 10px;"><strong>Database update required</strong></p>
											SP Page Builder has been updated. To keep things running smoothly, we need to update your database. We strongly recommend you to take a backup in case anything goes wrong.
										</div>
										<?php echo implode("\n", $html); ?>
										<a class="btn btn-primary action-fix-sppagebuilder-database" href="#"><?php echo Text::_('COM_SPPAGEBUILDER_MAINTENANCE_UPADTE_DATABASE'); ?></a>
									</div>
								<?php else: ?>
									<div class="alert alert-success">
										<?php echo Text::_('COM_SPPAGEBUILDER_MAINTENANCE_IS_UPTODATE'); ?>
									</div>
								<?php endif ?>
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