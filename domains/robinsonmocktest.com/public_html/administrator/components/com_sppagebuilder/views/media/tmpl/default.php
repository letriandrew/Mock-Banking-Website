<?php
/**
* @package SP Page Builder
* @author JoomShaper http://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2021 JoomShaper
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined('_JEXEC') or die ('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Uri\Uri;

$doc = Factory::getDocument();

SppagebuilderHelper::loadAssets('css');
$doc->addScriptdeclaration('var pagebuilder_base="' . JURI::root() . 'administrator/";');
HTMLHelper::_('jquery.framework');
SppagebuilderHelper::addScript('media.js');

Text::script('COM_SPPAGEBUILDER_MEDIA_MANAGER_CONFIRM_DELETE');
Text::script('COM_SPPAGEBUILDER_MEDIA_MANAGER_ENTER_DIRECTORY_NAME');
?>

<div id="sp-pagebuilder-media-manager" class="sp-pagebuilder-admin<?php echo (count((array) $this->items)) ? '': ' sp-pagebuilder-media-manager-empty'; ?>">
	
	<div class="sp-pagebuilder-main">
		<?php
		// sidebar
		echo LayoutHelper::render('sidebar');
		?>

		<div class="sp-pagebuilder-content">
			<div class="sp-pagebuilder-media-categories d-none">
				<ul id="sp-pagebuilder-media-types">
					<?php echo JLayoutHelper::render('media.categories', array( 'categories'=>$this->categories )); ?>
				</ul>
			</div>
			<div class="sp-pagebuilder-row">
				<div class="col-md-12">
					<div id="j-main-container" class="j-main-container">
						
						<div class="sp-pagebuilder-media-toolbar">
							<div>
								<div class="sp-pagebuilder-media-search">
									<i class="fas fa-search"></i>
									<input type="text" class="form-control" id="sp-pagebuilder-media-search-input" placeholder="<?php echo Text::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_SEARCH'); ?>">
									<a href="#" class="sp-pagebuilder-clear-search" style="display: none;"><i class="fas fa-times-circle"></i></a>
								</div>
							</div>

							<div>
								<select id="sp-pagebuilder-media-filter" class="form-control" data-type="browse">
									<option value=""><?php echo Text::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_MEDIA_ALL'); ?></option>
									<?php foreach ($this->filters as $key => $this->filter) : ?>
									<option value="<?php echo $this->filter->year . '-' . $this->filter->month; ?>"><?php echo JHtml::_('date', $this->filter->year . '-' . $this->filter->month, 'F Y'); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>

						<div class="sp-pagebuilder-media-list">
							<div class="sp-pagebuilder-media-empty">
								<div>
									<i class="fas fa-upload"></i>
									<h3 class="sp-pagebuilder-media-empty-title">
										<?php echo Text::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_DRAG_DROP_UPLOAD'); ?>
									</h3>
									<div>
										<a href="#" id="sp-pagebuilder-upload-media-empty" class="sp-pagebuilder-btn sp-pagebuilder-btn-primary sp-pagebuilder-btn-lg"><?php echo Text::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_OR_SELECT'); ?></a>
									</div>
								</div>
							</div>
							<div class="sp-pagebuilder-media-wrapper">
								<ul class="sp-pagebuilder-media">
									<?php
									foreach ($this->items as $key => $this->item) {
										echo  LayoutHelper::render('media.format', array('media'=>$this->item, 'support'=>'all'));
									}
									?>
								</ul>
								<?php if($this->total > ($this->limit + $this->start)) : ?>
								<div class="sp-pagebuilder-media-loadmore">
									<a id="sp-pagebuilder-media-loadmore" class="btn btn-primary btn-lg" href="#"><i class="fa fa-refresh"></i> <?php echo Text::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_LOAD_MORE'); ?></a>
								</div>
								<?php endif; ?>
							</div>
						</div>

						<input type="file" id="sp-pagebuilder-media-input-file" class="d-none" multiple="multiple">
						<?php echo LayoutHelper::render('footer'); ?>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
