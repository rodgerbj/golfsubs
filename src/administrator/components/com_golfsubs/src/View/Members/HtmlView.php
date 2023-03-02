<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_golfsubs
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace GolfsubsNamespace\Component\Golfsubs\Administrator\View\Members;

\defined('_JEXEC') or die;

use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\GolfsubsDataException;

/**
 * View class for a list of members.
 *
 * @since  __BUMP_VERSION__
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * An array of items
	 *
	 * @var  array
	 */
	protected $items;
	
	/**
	 * The pagination object
	 *
	 * @var  \JPagination
	 */
	protected $pagination;
	
	/**
	 * The model state
	 *
	 * @var  \JObject
	 */
	protected $state;

	/**
	 * Form object for search filters
	 *
	 * @var  \JForm
	 */
	public $filterForm;

	/**
	 * The active search filters
	 *
	 * @var  array
	 */
	public $activeFilters;

	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  void
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function display($tpl = null): void
	{
		$this->items = $this->get('Items');

		$this->pagination = $this->get('Pagination');

		$this->filterForm = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');
		$this->state = $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new GolfsubsDataException(implode("\n", $errors), 500);
		}

		// Preprocess the list of items to find ordering divisions.
		// TODO: Complete the ordering stuff with nested sets
		foreach ($this->items as &$item) {
			$item->order_up = true;
			$item->order_dn = true;
		}

		if (!count($this->items) && $this->get('IsEmptyState')) {
			$this->setLayout('emptystate');
		}

		$this->addToolbar();

		parent::display($tpl);
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
		$this->sidebar = \JHtmlSidebar::render();

		$canDo = ContentHelper::getActions('com_golfsubs', 'category', $this->state->get('filter.category_id'));
		$user  = Factory::getUser();

		// Get the toolbar object instance
		$toolbar = Toolbar::getInstance('toolbar');

		ToolbarHelper::title(Text::_('COM_GOLFSUBS_MANAGER_MEMBERS'), 'address member');

		if ($canDo->get('core.create') || count($user->getAuthorisedCategories('com_golfsubs', 'core.create')) > 0) {
			$toolbar->addNew('member.add');
	}
		
	if ($canDo->get('core.edit.state')) {
		$dropdown = $toolbar->dropdownButton('status-group')
			->text('JTOOLBAR_CHANGE_STATUS')
			->toggleSplit(false)
			->icon('fa fa-ellipsis-h')
			->buttonClass('btn btn-action')
			->listCheck(true);
		$childBar = $dropdown->getChildToolbar();
		$childBar->publish('members.publish')->listCheck(true);
		$childBar->unpublish('members.unpublish')->listCheck(true);
	
		$childBar->standardButton('featured')
			->text('JFEATURE')
			->task('foos.featured')
			->listCheck(true);
		$childBar->standardButton('unfeatured')
			->text('JUNFEATURE')
			->task('foos.unfeatured')
			->listCheck(true);

		$childBar->archive('members.archive')->listCheck(true);

		if ($user->authorise('core.admin')) {
			$childBar->checkin('members.checkin')->listCheck(true);
		}

		if ($this->state->get('filter.published') != -2) {
			$childBar->trash('members.trash')->listCheck(true);
		}
		if ($this->state->get('filter.published') == -2 && $canDo->get('core.delete')) {
			$childBar->delete('members.delete')
				->text('JTOOLBAR_EMPTY_TRASH')
				->message('JGLOBAL_CONFIRM_DELETE')
				->listCheck(true);
		}

		// Add a batch button
		if ($user->authorise('core.create', 'com_golfsubs')
			&& $user->authorise('core.edit', 'com_golfsubs')
			&& $user->authorise('core.edit.state', 'com_golfsubs')) {
			$childBar->popupButton('batch')
				->text('JTOOLBAR_BATCH')
				->selector('collapseModal')
				->listCheck(true);
		}
	}

		if ($user->authorise('core.admin', 'com_golfsubs') || $user->authorise('core.options', 'com_golfsubs')) {
			$toolbar->preferences('com_golfsubs');
		}
		ToolbarHelper::divider();
		ToolbarHelper::help('', false, 'http://bernierodgers.com');
	}
}
