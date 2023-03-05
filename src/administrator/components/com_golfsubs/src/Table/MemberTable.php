<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_golfsubs
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace GolfsubsNamespace\Component\Golfsubs\Administrator\Table;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;

/**
 * Members Table class.
 *
 * @since  __BUMP_VERSION__
 */
class MemberTable extends Table
{
	/**
	 * Constructor
	 *
	 * @param   DatabaseDriver  $db  Database connector object
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function __construct(DatabaseDriver $db)
	{
		$this->typeAlias = 'com_golfsubs.member';

		parent::__construct('#__members', 'id', $db);
	}

	/**
	 * Generate a valid alias from memname / date.
	 * Remains public to be able to check for duplicated alias before saving
	 *
	 * @return  string
	 */
	public function generateAlias()
	{
		if (empty($this->alias)) {
			$this->alias = $this->memname;
		}

		$this->alias = ApplicationHelper::stringURLSafe($this->alias);

		if (trim(str_replace('-', '', $this->alias)) == '') {
			$this->alias = Factory::getDate()->format('Y-m-d-H-i-s');
		}

		return $this->alias;
	}
	/**
	 * Overloaded check function
	 *
	 * @return  boolean
	 *
	 * @see     Table::check
	 * @since   __BUMP_VERSION__
	 */
	public function check()
	{
		try {
			parent::check();
		} catch (\Exception $e) {
			$this->setError($e->getMessage());

			return false;
		}

		// Check the publish down date is not earlier than publish up.
		if ($this->publish_down > $this->_db->getNullDate() && $this->publish_down < $this->publish_up) {
			$this->setError(Text::_('JGLOBAL_START_PUBLISH_AFTER_FINISH'));

			return false;
		}

		// Set publish_up, publish_down to null if not set
		if (!$this->publish_up) {
			$this->publish_up = null;
		}

		if (!$this->publish_down) {
			$this->publish_down = null;
		}


		// Convert all digits to desired format
		// 			$cleanvalue = preg_replace('/[+. \-(\)]/', '', $value);
		// 			$regex = '/^[0-9]{7,15}?$/';
		// if (preg_match($regex, $cleanvalue) == true)
		if (isset($this->memphone)) {
			if ($this->memphone !== '') {
				$vartext = $this->memphone;
				$cleanvalue = preg_replace('/[+. \-(\)]/', '', $vartext);

				$sp = str_split($cleanvalue);
				$areacode = "(" . $sp[0] .$sp[1] . $sp[2] . ") ";
				$phoneformat = $sp[3] .$sp[4] . $sp[5] . "-" . $sp[6] . $sp[7] . $sp[8] . $sp[9];
				$this->memphone = $areacode . $phoneformat;
			}
		}
		return true;
	}

	/**
	 * Stores a member.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success, false on failure.
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function store($updateNulls = true)
	{
		// Transform the params field
		if (is_array($this->params)) {
			$registry = new Registry($this->params);
			$this->params = (string) $registry;
		}

		return parent::store($updateNulls);
	}
}
