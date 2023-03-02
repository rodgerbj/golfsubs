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

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\Database\ParameterType;
use Joomla\Utilities\ArrayHelper;

/**
 * Item Model for a Member.
 *
 * @since  __BUMP_VERSION__
 */
class MemberModel extends AdminModel
{
	/**
	 * The type alias for this content type.
	 *
	 * @var    string
	 * @since  __BUMP_VERSION__
	 */
	public $typeAlias = 'com_golfsubs.member';
	
	/**
	 * Batch copy/move command. If set to false, the batch copy/move command is not supported
	 *
	 * @var  string
	 */
	protected $batch_copymove = 'category_id';

	/**
	 * Allowed batch commands
	 *
	 * @var array
	 */
	protected $batch_commands = [
		'assetgroup_id' => 'batchAccess',
		'user_id'       => 'batchUser',
	];
	
	/**
	 * Method to get the row form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  \JForm|boolean  A \JForm object on success, false on failure
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function getForm($data = [], $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm($this->typeAlias, 'member', ['control' => 'jform', 'load_data' => $loadData]);

		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   __BUMP_VERSION__
	 */
	protected function loadFormData()
	{
		$app = Factory::getApplication();

		// Check the session for previously entered form data.
		$data = $app->getUserState('com_golfsubs.edit.member.data', []);

		if (empty($data)) {
		$data = $this->getItem();

		// Prime some default values.
		if ($this->getState('member.id') == 0) {
			$data->set('catid', $app->input->get('catid', $app->getUserState('com_golfsubs.members.filter.category_id'), 'int'));
		}
		}

		$this->preprocessData($this->typeAlias, $data);

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed  Object on success, false on failure.
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function getItem($pk = null)
	{
		$item = parent::getItem($pk);

		return $item;
	}

	/**
	 * Method to toggle the featured setting of members.
	 *
	 * @param   array    $pks    The ids of the items to toggle.
	 * @param   integer  $value  The value to toggle to.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function featured($pks, $value = 0)
	{
		// Sanitize the ids.
		$pks = ArrayHelper::toInteger((array) $pks);

		if (empty($pks)) {
			$this->setError(Text::_('COM_GOLFSUBS_NO_ITEM_SELECTED'));

			return false;
		}

		$table = $this->getTable();

		try {
			$db = $this->getDbo();

			$query = $db->getQuery(true);
			$query->update($db->quoteName('#__members'));
			$query->set($db->quoteName('featured') . ' = :featured');
			$query->whereIn($db->quoteName('id'), $pks);
			$query->bind(':featured', $value, ParameterType::INTEGER);

			$db->setQuery($query);

			$db->execute();
		} catch (\Exception $e) {
			$this->setError($e->getMessage());

			return false;
		}

		$table->reorder();

		// Clean component's cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Preprocess the form.
	 *
	 * @param   \JForm  $form   Form object.
	 * @param   object  $data   Data object.
	 * @param   string  $group  Group name.
	 *
	 * @return  void
	 *
	 * @since   __BUMP_VERSION__
	 */
	protected function preprocessForm(\JForm $form, $data, $group = 'content')
	{
		
		parent::preprocessForm($form, $data, $group);
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @param   \Joomla\CMS\Table\Table  $table  The Table object
	 *
	 * @return  void
	 *
	 * @since   __BUMP_VERSION__
	 */
	protected function prepareTable($table)
	{
		$table->generateAlias();
	}
}
