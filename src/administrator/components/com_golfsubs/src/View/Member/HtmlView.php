<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_golfsubs
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace GolfsubsNamespace\Component\Golfsubs\Administrator\View\Member;

\defined('_JEXEC') or die;

use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * View to edit a member.
 *
 * @since  __BUMP_VERSION__
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * The \JForm object
	 *
	 * @var  \JForm
	 */
	protected $form;

	/**
	 * The active item
	 *
	 * @var  object
	 */
	protected $item;

	/**
	 * Display the view.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{
		$this->form  = $this->get('Form');
		$this->item = $this->get('Item');

		$this->addToolbar();

		return parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   __BUMP_VERSION__
	 */
	protected function addToolbar()
	{
		Factory::getApplication()->input->set('hidemainmenu', true);
		$user = Factory::getUser();
		$userId = $user->id;
		$isNew = ($this->item->id == 0);

		ToolbarHelper::title($isNew ? Text::_('COM_GOLFSUBS_MANAGER_MEMBER_NEW') : Text::_('COM_GOLFSUBS_MANAGER_MEMBER_EDIT'), 'address member');

		// Since we don't track these assets at the item level, use the category id.
		$canDo = ContentHelper::getActions('com_golfsubs', 'category', $this->item->catid);

		// Build the actions for new and existing records.
		if ($isNew) {
			// For new records, check the create permission.
			if ($isNew && (count($user->getAuthorisedCategories('com_golfsubs', 'core.create')) > 0)) {
				ToolbarHelper::apply('member.apply');
				ToolbarHelper::saveGroup(
					[
						['save', 'member.save'],
						['save2new', 'member.save2new']
					],
					'btn-success'
				);
			}

			ToolbarHelper::cancel('member.cancel');
		} else {
			// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
			$itemEditable = $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId);
			$toolbarButtons = [];

			// Can't save the record if it's not editable
			if ($itemEditable) {
				ToolbarHelper::apply('member.apply');
				$toolbarButtons[] = ['save', 'member.save'];

				// We can save this record, but check the create permission to see if we can return to make a new one.
				if ($canDo->get('core.create')) {
					$toolbarButtons[] = ['save2new', 'member.save2new'];
				}
			}

			// If checked out, we can still save
			if ($canDo->get('core.create')) {
				$toolbarButtons[] = ['save2copy', 'member.save2copy'];
			}

			ToolbarHelper::saveGroup(
				$toolbarButtons,
				'btn-success'
			);

			
			ToolbarHelper::cancel('member.cancel', 'JTOOLBAR_CLOSE');
		}
		
		ToolbarHelper::divider();
		ToolbarHelper::inlinehelp();
		ToolbarHelper::help('', false, 'http://bernierodgers.com');
	}
}
