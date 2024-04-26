<?php
/**
* @package SP Page Builder
* @author JoomShaper http://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2021 JoomShaper
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Multilanguage;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;

HTMLHelper::_('jquery.framework');
$doc = Factory::getDocument();

SppagebuilderHelper::loadAssets('css');

$app		= Factory::getApplication();
$user		= Factory::getUser();
$userId		= $user->get('id');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$saveOrder = $listOrder == 'a.ordering';

if (strpos($listOrder, 'modified') !== false)
{
	$orderingColumn = 'modified';
}
else
{
	$orderingColumn = 'created';
}

if ($saveOrder && !empty($this->items))
{
	if(JVERSION < 4)
	{
		$saveOrderingUrl = 'index.php?option=com_sppagebuilder&task=pages.saveOrderAjax&tmpl=component';
		HTMLHelper::_('sortablelist.sortable', 'pageList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
	}
	else
	{
		$saveOrderingUrl = 'index.php?option=com_sppagebuilder&task=pages.saveOrderAjax&tmpl=component&' . Session::getFormToken() . '=1';
		HTMLHelper::_('draggablelist.draggable');
	}
}
?>


<div class="sp-pagebuilder-admin">

	<div class="sp-pagebuilder-main">
		<?php
		// sidebar
		echo LayoutHelper::render('sidebar');
		?>

		<div class="sp-pagebuilder-content">
			<form action="<?php echo Route::_('index.php?option=com_sppagebuilder&view=pages');?>" method="post" name="adminForm" id="adminForm">
			
				<?php if($this->databaseIssue) : ?>
					<div class="alert alert-warning">
						<p style="font-size: 16px; margin-bottom: 10px;"><strong>SP Page Builder database update required</strong></p>
						<p style="margin-bottom: 20px;">SP Page Builder has been updated. To keep things running smoothly, we need to update your database. We strongly recommend you to take a backup in case anything goes wrong.</p>
						<a class="btn btn-primary" href="<?php echo Route::_('index.php?option=com_sppagebuilder&view=maintenance', false); ?>">Update SP Page Builder Database</a>
					</div>
				<?php endif; ?>

				<?php
				// Search tools bar
				echo LayoutHelper::render('joomla.searchtools.default', array('view' => $this));
				?>

				<div id="j-main-container">
					<div class="sp-pagebuilder-main-container-inner">

						<?php if (empty($this->items)) : ?>
							<div class="alert alert-info">
								<span class="fas fa-info-circle" aria-hidden="true"></span><span class="sr-only"><?php echo Text::_('INFO'); ?></span>
								<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
							</div>
						<?php else : ?>
							<div class="sp-pagebuilder-pages">
								<table class="sppb-list-table" id="pageList">
									<thead>
										<tr>
											<td class="w-1 text-center">
												<?php echo HTMLHelper::_('grid.checkall'); ?>
											</td>
											<th scope="col" class="w-1 text-center d-none d-md-table-cell">
												<?php echo HTMLHelper::_('searchtools.sort', '', 'a.ordering', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'fas fa-sort'); ?>
											</th>
											<th scope="col" class="w-1 text-center">
												<?php echo HTMLHelper::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
											</th>
											<th scope="col" style="min-width:100px">
												<?php echo HTMLHelper::_('searchtools.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
											</th>
											<th scope="col" class="w-10 d-none d-md-table-cell">
												<?php echo HTMLHelper::_('searchtools.sort',  'JAUTHOR', 'a.created_by', $listDirn, $listOrder); ?>
											</th>
											<th scope="col" class="w-10 d-none d-md-table-cell">
												<?php echo HTMLHelper::_('searchtools.sort',  'JGRID_HEADING_ACCESS', 'a.access', $listDirn, $listOrder); ?>
											</th>
											<?php if (Multilanguage::isEnabled()) : ?>
												<th scope="col" class="w-10 d-none d-md-table-cell">
													<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE', 'language', $listDirn, $listOrder); ?>
												</th>
											<?php endif; ?>
											<th scope="col" class="w-3 d-none d-lg-table-cell text-center">
												<?php echo HTMLHelper::_('searchtools.sort', 'JGLOBAL_HITS', 'a.hits', $listDirn, $listOrder); ?>
											</th>
											<th scope="col" class="w-3 d-none d-lg-table-cell">
												<?php echo HTMLHelper::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
											</th>
										</tr>
									</thead>

									<?php if(JVERSION < 4) :?>
									<tbody>
									<?php else: ?>
									<tbody <?php if ($saveOrder) :?> class="js-draggable" data-url="<?php echo $saveOrderingUrl; ?>" data-direction="<?php echo strtolower($listDirn); ?>" data-nested="false"<?php endif; ?>>
									<?php endif; ?>
										<?php foreach ($this->items as $i => $item) : ?>
											<?php
											$ordering   = ($listOrder == 'a.ordering');
											$canEdit    = $user->authorise('core.edit', 'com_sppagebuilder.page.' . $item->id);
											$canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $userId || is_null($item->checked_out);
											$canEditOwn = $user->authorise('core.edit.own', 'com_sppagebuilder.page.' . $item->id) && $item->created_by == $userId;
											$canChange  = $user->authorise('core.edit.state', 'com_sppagebuilder.page.' . $item->id) && $canCheckin;
											?>
											<?php if(JVERSION < 4) :?>
											<tr>
											<?php else: ?>
											<tr class="row<?php echo $i % 2; ?>" data-draggable-group="<?php echo $item->catid; ?>">
											<?php endif; ?>
												<td class="text-center">
													<?php echo HTMLHelper::_('grid.id', $i, $item->id, false, 'cid', 'cb', $item->title); ?>
												</td>

												<td class="text-center d-none d-md-table-cell">
													<?php
													$iconClass = '';
													if (!$canChange)
													{
														$iconClass = ' inactive';
													}
													elseif (!$saveOrder)
													{
														$iconClass = ' inactive" title="' . Text::_('JORDERINGDISABLED');
													}
													?>
													<span class="sortable-handler<?php echo $iconClass ?>">
														<span class="fas fa-ellipsis-v" aria-hidden="true"></span>
													</span>
													<?php if ($canChange && $saveOrder) : ?>
														<input type="text" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order hidden">
													<?php endif; ?>
												</td>

												<td class="page-status text-center">
													<?php echo HTMLHelper::_('jgrid.published', $item->published, $i, 'pages.', $canChange);?>
												</td>

												<th>
													<?php if ($item->checked_out) : ?>
														<?php echo HTMLHelper::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'pages.', $canCheckin); ?>
													<?php endif; ?>

													<?php if ($canEdit || $canEditOwn) : ?>
														<a class="sp-pagebuilder-page-title" href="<?php echo Route::_('index.php?option=com_sppagebuilder&task=page.edit&id='.$item->id); ?>" title="<?php echo Text::_('JACTION_EDIT'); ?> <?php echo $this->escape($item->title); ?>">
															<?php echo $this->escape($item->title); ?>
														</a>
													<?php else : ?>
														<span title="<?php echo Text::sprintf('JFIELD_ALIAS_LABEL', $this->escape($item->alias)); ?>"><?php echo $this->escape($item->title); ?></span>
													<?php endif; ?>

													<a class="btn btn-default btn-sm sp-pagebuilder-btn-preview-page" target="_blank" href="<?php echo $item->preview; ?>" style="margin: 0 5px;"><?php echo Text::_('COM_SPPAGEBUILDER_PREVIEW'); ?></a>
													<?php if ($canEdit || $canEditOwn) : ?>
														<a class="btn btn-primary btn-sm sp-pagebuilder-btn-frontend-editor" target="_blank" href="<?php echo $item->frontend_edit; ?>"><?php echo Text::_('COM_SPPAGEBUILDER_FRONTEND_EDITOR'); ?></a>
													<?php endif; ?>

													<?php if(isset($item->category_title) && $item->category_title): ?>
														<div class="small">
															<?php echo Text::_('JCATEGORY') . ": " . $this->escape($item->category_title); ?>
														</div>
													<?php endif; ?>
												</th>

												<td class="small d-none d-md-table-cell">
													<?php if ((int) $item->created_by != 0) : ?>
														<a href="<?php echo Route::_('index.php?option=com_users&task=user.edit&id=' . (int) $item->created_by); ?>">
															<?php echo $this->escape($item->author_name); ?>
														</a>
													<?php else : ?>
														<?php echo Text::_('JNONE'); ?>
													<?php endif; ?>
												</td>

												<td class="small d-none d-md-table-cell">
													<?php echo $this->escape($item->access_title); ?>
												</td>

												<?php if (Multilanguage::isEnabled()) : ?>
													<td class="small d-none d-md-table-cell">
														<?php if ($item->language == '*') : ?>
															<?php echo Text::alt('JALL', 'language'); ?>
														<?php else:?>
															<?php echo $item->language_title ? $this->escape($item->language_title) : Text::_('JUNDEFINED'); ?>
														<?php endif;?>
													</td>
												<?php endif; ?>

												<td class="d-none d-lg-table-cell text-center">
													<span class="badge badge-info">
														<?php echo (int) $item->hits; ?>
													</span>
												</td>
												
												<td class="d-none d-lg-table-cell">
													<?php echo (int) $item->id; ?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>

								<?php // load the pagination. ?>
								<?php echo $this->pagination->getListFooter(); ?>

							</div>
						<?php endif; ?>
					</div>

					<input type="hidden" name="task" value="">
					<input type="hidden" name="boxchecked" value="0">
					<?php echo HTMLHelper::_('form.token'); ?>
				</div>
			</form>

			<?php echo LayoutHelper::render('footer'); ?>
		</div>
	</div>

</div>
