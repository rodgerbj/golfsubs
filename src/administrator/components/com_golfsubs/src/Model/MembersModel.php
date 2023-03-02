<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_golfsubs
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace GolfsubsNamespace\Component\Golfsubs\Administrator\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;

use Joomla\CMS\Factory;
use Joomla\Utilities\ArrayHelper;

/**
 * Methods supporting a list of member records.
 *
 * @since  __BUMP_VERSION__
 */
class MembersModel extends ListModel
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     \JControllerLegacy
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function __construct($config = [])
	{

		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = [
				'id', 'a.id',
				'memname', 'a.memname',
				'mememail', 'a.mememail',
				'memphone', 'a.memphone',
				'user_id', 'a.user_id',
				'catid', 'a.catid', 'category_id', 'category_title',
				'published', 'a.published',
				'access', 'a.access', 'access_level',
				'ordering', 'a.ordering',
				'featured', 'a.featured',
				'publish_up', 'a.publish_up',
				'publish_down', 'a.publish_down',
			];

		}

		parent::__construct($config);
	}
	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  \JDatabaseQuery
	 *
	 * @since   __BUMP_VERSION__
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$db->quoteName(
				explode(
					', ',
					$this->getState(
						'list.select',
						'a.id, a.memname, a.catid' .
						', a.mememail, a.memphone' .
						', a.user_id' .
						', a.access' .
						', a.checked_out' .
						', a.checked_out_time' .
						', a.ordering' .
						', a.featured' .
						', a.state' .
						', a.published' .
						', a.publish_up, a.publish_down'
					)
				)
				)
		);

		$query->from($db->quoteName('#__members', 'a'));

		// Join over the asset groups.
		$query->select($db->quoteName('ag.title', 'access_level'))
			->join(
				'LEFT',
				$db->quoteName('#__viewlevels', 'ag') . ' ON ' . $db->quoteName('ag.id') . ' = ' . $db->quoteName('a.access')
			);

		// Join over the categories.
		$query->select($db->quoteName('c.title', 'category_title'))
		->join(
			'LEFT',
			$db->quoteName('#__categories', 'c') . ' ON ' . $db->quoteName('c.id') . ' = ' . $db->quoteName('a.catid')
		);

		// Filter by access level.
		if ($access = $this->getState('filter.access')) {
			$query->where($db->quoteName('a.access') . ' = ' . (int) $access);
		}

		// Filter by published state
		$published = (string) $this->getState('filter.published');

		if (is_numeric($published)) {
			$query->where($db->quoteName('a.published') . ' = ' . (int) $published);
		} else if ($published === '') {
			$query->where('(' . $db->quoteName('a.published') . ' = 0 OR ' . $db->quoteName('a.published') . ' = 1)');
		}

		// Filter by a single or group of categories.
		$categoryId = $this->getState('filter.category_id');

		if (is_numeric($categoryId)) {
			$query->where($db->quoteName('a.catid') . ' = ' . (int) $categoryId);
		} else if (is_array($categoryId)) {
			$query->where($db->quoteName('a.catid') . ' IN (' . implode(',', ArrayHelper::toInteger($categoryId)) . ')');
		}

		// Join over the users for the checked out user.
		$query->select($db->quoteName('uc.name', 'editor'))
		->join(
			'LEFT',
			$db->quoteName('#__users', 'uc') . ' ON ' . $db->quoteName('uc.id') . ' = ' . $db->quoteName('a.checked_out')
		);

		// Filter by search in memname.
		$search = $this->getState('filter.search');

		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = ' . (int) substr($search, 3));
			} else {
				$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
				$query->where(
					'(' . $db->quoteName('a.memname') . ' LIKE ' . $search . ')'
				);
			}
		}

		// Filter by search in mememail.
		$search = $this->getState('filter.search');

				if (!empty($search)) {
					if (stripos($search, 'id:') === 0) {
						$query->where('a.id = ' . (int) substr($search, 3));
					} else {
						$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
						$query->where(
							'(' . $db->quoteName('a.mememail') . ' LIKE ' . $search . ')'
						);
					}
				}
		
		// Filter by search in memphone.
		$search = $this->getState('filter.search');

				if (!empty($search)) {
					if (stripos($search, 'id:') === 0) {
						$query->where('a.id = ' . (int) substr($search, 3));
					} else {
						$search = $db->quote('%' . str_replace(' ', '%', $db->escape(trim($search), true) . '%'));
						$query->where(
							'(' . $db->quoteName('a.memphone') . ' LIKE ' . $search . ')'
						);
					}
				}
		
		// Filter by featured.
		$featured = (string) $this->getState('filter.featured');

		if (in_array($featured, ['0','1'])) {
			$query->where($db->quoteName('a.featured') . ' = ' . (int) $featured);
			}
	
		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'a.memname');
		$orderDirn = $this->state->get('list.direction', 'asc');

		if ($orderCol == 'a.ordering' || $orderCol == 'category_title') {
			$orderCol = $db->quoteName('c.title') . ' ' . $orderDirn . ', ' . $db->quoteName('a.ordering');
		}

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   __BUMP_VERSION__
	 */
	protected function populateState($ordering = 'a.memname', $direction = 'asc')
	{
		$app = Factory::getApplication();
		$forcedLanguage = $app->input->get('forcedLanguage', '', 'cmd');

		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout')) {
			$this->context .= '.' . $layout;
		}

		// Adjust the context to support forced languages.
		if ($forcedLanguage) {
			$this->context .= '.' . $forcedLanguage;
		}

		// List state information.
		parent::populateState($ordering, $direction);

		// Force a language.
		if (!empty($forcedLanguage)) {
			$this->setState('filter.language', $forcedLanguage);
		}
	}
}
