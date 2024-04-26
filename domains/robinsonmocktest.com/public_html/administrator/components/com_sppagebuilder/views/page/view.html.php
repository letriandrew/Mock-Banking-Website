<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2022 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;

class SppagebuilderViewPage extends BaseHtmlView
{

	/**
	 * The \JForm object
	 *
	 * @var \Joomla\CMS\Form\Form
	 */
	protected $form;

	/**
	 * The active item
	 *
	 * @var  object
	 */
	protected $item;

	/**
	 * The model state
	 *
	 * @var  object
	 */
	protected $state;

	/**
	 * The actions the user is authorised to perform
	 *
	 * @var  \JObject
	 */
	protected $canDo;

	/**
	 * Pagebreak TOC alias
	 *
	 * @var  string
	 */
	protected $eName;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 *
	 * @throws \Exception
	 */
	public function display( $tpl = null )
	{
		$this->form  = $this->get('Form');
		$this->item  = $this->get('Item');
		$this->state = $this->get('State');
		$this->canDo = ContentHelper::getActions('com_sppagebuilder', 'page', $this->item->id);

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new GenericDataException(implode("\n", $errors), 500);
		}

		//Load Language
		$db = Factory::getDbo();
		$query = "SELECT template FROM #__template_styles WHERE client_id = 0 AND home = 1";
		$db->setQuery($query);
		$defaultemplate = $db->loadResult();

		$lang = Factory::getLanguage();
		$lang->load('tpl_' . $defaultemplate, JPATH_SITE, $lang->getName(), true);

		$this->addToolBar();
		parent::display($tpl);
	}


	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @throws \Exception
	 */
	protected function addToolbar()
	{
		Factory::getApplication()->input->set('hidemainmenu', true);
		$user       = Factory::getUser();
		$userId     = $user->id;
		$isNew      = ($this->item->id == 0);
		$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $userId);

		// Built the actions for new and existing records.
		$canDo = $this->canDo;
		$toolbar = Toolbar::getInstance();

		// Set the title
		ToolbarHelper::title( Text::_('COM_SPPAGEBUILDER') . ' - ' . ( $checkedOut ? 'View' : ($isNew ? Text::_('New') : Text::_('Edit')) ) . ' Page' , 'none pbfont pbfont-pagebuilder');

		if (JVERSION < 4)
		{
			// For new records, check the create permission.
			if ($isNew && (count($user->getAuthorisedCategories('com_sppagebuilder', 'core.create')) > 0))
			{
				ToolbarHelper::apply('page.apply');
				ToolbarHelper::save('page.save');
				ToolbarHelper::save2new('page.save2new');
				ToolbarHelper::cancel('page.cancel');
			}
			else
			{
				// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
				$itemEditable = $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId);

				// Can't save the record if it's checked out and editable
				if (!$checkedOut && $itemEditable)
				{
					ToolbarHelper::apply('page.apply');
					ToolbarHelper::save('page.save');

					// We can save this record, but check the create permission to see if we can return to make a new one.
					if ($canDo->get('core.create'))
					{
						ToolbarHelper::save2new('page.save2new');
					}
				}

				// If checked out, we can still save
				if ($canDo->get('core.create'))
				{
					ToolbarHelper::save2copy('page.save2copy');
				}

				ToolbarHelper::cancel('page.cancel', 'JTOOLBAR_CLOSE');
			}

		}
		else
		{
			// For new records, check the create permission.
			if ($isNew && (count($user->getAuthorisedCategories('com_sppagebuilder', 'core.create')) > 0))
			{
				$toolbar->apply('page.apply');

				$saveGroup = $toolbar->dropdownButton('save-group');

				$saveGroup->configure(
					function (Toolbar $childBar) use ($user)
					{
						$childBar->save('page.save');
						$childBar->save2new('page.save2new');
					}
				);

				$toolbar->cancel('page.cancel', 'JTOOLBAR_CLOSE');
			}
			else
			{
				// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
				$itemEditable = $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId);

				if (!$checkedOut && $itemEditable)
				{
					$toolbar->apply('page.apply');
				}

				$saveGroup = $toolbar->dropdownButton('save-group');

				$saveGroup->configure(
					function (Toolbar $childBar) use ($checkedOut, $itemEditable, $canDo, $user)
					{
						// Can't save the record if it's checked out and editable
						if (!$checkedOut && $itemEditable)
						{
							$childBar->save('page.save');

							// We can save this record, but check the create permission to see if we can return to make a new one.
							if ($canDo->get('core.create'))
							{
								$childBar->save2new('page.save2new');
							}
						}

						// If checked out, we can still save
						if ($canDo->get('core.create'))
						{
							$childBar->save2copy('page.save2copy');
						}
					}
				);

				$toolbar->cancel('page.cancel', 'JTOOLBAR_CLOSE');
			}
		}
	}
}