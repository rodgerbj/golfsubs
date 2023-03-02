<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_golfsubs
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace GolfsubsNamespace\Component\Golfsubs\Site\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Categories\CategoryNode;

/**
 * Golfsubs Component Route Helper
 *
 * @static
 * @package     Joomla.Site
 * @subpackage  com_golfsubs
 * @since       __DEPLOY_VERSION__
 */
abstract class RouteHelper
{
	/**
	 * Get the URL route for a members from a member ID, members category ID and language
	 *
	 * @param   integer  $id        The id of the members
	 * @param   integer  $catid     The id of the members's category
	 *
	 * @return  string  The link to the members
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getMembersRoute($id, $catid)
	{
		// Create the link
		$link = 'index.php?option=com_golfsubs&view=members&id=' . $id;

		if ($catid > 1) {
			$link .= '&catid=' . $catid;
		}

		return $link;
	}

	/**
	 * Get the URL route for a member from a member ID, members category ID and language
	 *
	 * @param   integer  $id        The id of the members
	 * @param   integer  $catid     The id of the members's category
	 * @param   mixed    $language  The id of the language being used.
	 *
	 * @return  string  The link to the members
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getMemberRoute($id, $catid)
	{
		// Create the link
		$link = 'index.php?option=com_golfsubs&view=member&id=' . $id;

		if ($catid > 1) {
			$link .= '&catid=' . $catid;
		}

		return $link;
	}

	/**
	 * Get the URL route for a members category from a members category ID and language
	 *
	 * @param   mixed  $catid     The id of the members's category either an integer id or an instance of CategoryNode
	 * @param   mixed  $language  The id of the language being used.
	 *
	 * @return  string  The link to the members
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getCategoryRoute($catid)
	{
		if ($catid instanceof CategoryNode) {
			$id = $catid->id;
		} else {
			$id = (int) $catid;
		}

		if ($id < 1) {
			$link = '';
		} else {
			// Create the link
			$link = 'index.php?option=com_golfsubs&view=category&id=' . $id;

		return $link;
		}
	}
}