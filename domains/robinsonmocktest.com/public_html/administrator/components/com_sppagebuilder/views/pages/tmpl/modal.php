<?php

/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2020 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
 */
//no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;

HTMLHelper::_('jquery.framework');
$app = Factory::getApplication();
$user = Factory::getUser();

JLoader::register('SppagebuilderHelperRoute', JPATH_ROOT . '/components/com_sppagebuilder/helpers/route.php');

$function  	= $app->input->getCmd('function', 'jSelectPage');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

if (strpos($listOrder, 'modified') !== false)
{
	$orderingColumn = 'modified';
}
else
{
	$orderingColumn = 'created';
}
?>

<div class="container-popup">
	<form action="<?php echo Route::_('index.php?option=com_sppagebuilder&view=pages&layout=modal&tmpl=component&function=' . $function . '&' . Session::getFormToken() . '=1'); ?>" method="post" name="adminForm" id="adminForm">
		<?php echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

		<?php if (empty($this->items)) : ?>
			<div class="alert alert-info">
				<span class="fas fa-info-circle" aria-hidden="true"></span><span class="sr-only"><?php echo Text::_('INFO'); ?></span>
				<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
		<?php else : ?>
			<table class="table table-striped table-condensed" id="pageList">
				<thead>
					<tr>
						<th>
							<?php echo HTMLHelper::_('searchtools.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="nowrap hidden-phone">
							<?php echo HTMLHelper::_('searchtools.sort',  'JGRID_HEADING_ACCESS', 'a.access', $listDirn, $listOrder); ?>
						</th>
						<?php if (Multilanguage::isEnabled()) : ?>
							<th width="5%" class="nowrap hidden-phone">
								<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE', 'language', $listDirn, $listOrder); ?>
							</th>
						<?php endif; ?>
						<th width="1%" class="nowrap hidden-phone">
							<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="15">
							<?php echo $this->pagination->getListFooter(); ?>
						</td>
					</tr>
				</tfoot>
				<tbody>
					<?php foreach ($this->items as $i => $item) : ?>
						<tr>
							<td>
								<a href="javascript:void(0)" onclick="if (window.parent) window.parent.<?php echo $this->escape($function); ?>('<?php echo $item->id; ?>', '<?php echo $this->escape(addslashes($item->title)); ?>','<?php echo $this->escape(SppagebuilderHelperRoute::getPageRoute($item->id, $item->language)); ?>', null);">
									<?php echo $this->escape($item->title); ?>
								</a>
							</td>
							<td class="small hidden-phone">
								<?php echo $this->escape($item->access_title); ?>
							</td>
							<?php if (Multilanguage::isEnabled()) : ?>
								<td class="small nowrap hidden-phone">
									<?php if ($item->language == '*') : ?>
										<?php echo Text::alt('JALL', 'language'); ?>
									<?php else : ?>
										<?php echo $item->language_title ? $this->escape($item->language_title) : Text::_('JUNDEFINED'); ?>
									<?php endif; ?>
								</td>
							<?php endif; ?>

							<td class="center hidden-phone">
								<?php echo (int) $item->id; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>

		<div>
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
			<?php echo HTMLHelper::_('form.token'); ?>
		</div>
	</form>
</div>