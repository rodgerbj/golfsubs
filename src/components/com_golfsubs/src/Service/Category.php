<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_golfsubs
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace GolfsubsNamespace\Component\Golfsubs\Site\Service;
\defined('_JEXEC') or die;
use Joomla\CMS\Categories\Categories;
/**
 * Member Component Category Tree
 *
 * @since  __BUMP_VERSION__
 */
class Category extends Categories
{
	/**
	 * Class constructor
	 *
	 * @param   array  $options  Array of options
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function __construct($options = [])
	{
		$options['table']      = '#__members';
		$options['extension']  = 'com_golfsubs';
		$options['statefield'] = 'published';
		parent::__construct($options);
	}
}
