<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_golfsubs
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace GolfsubsNamespace\Component\Golfsubs\Administrator\Service\HTML;

defined('JPATH_BASE') or die;

use Joomla\CMS\Factory;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\Utilities\ArrayHelper;

/**
 * Member HTML class.
 *
 * @since  __BUMP_VERSION__
 */
class AdministratorService
{
	
	/**
	 * Show the featured/not-featured icon.
	 *
	 * @param   integer  $value      The featured value.
	 * @param   integer  $i          Id of the item.
	 * @param   boolean  $canChange  Whether the value can be changed or not.
	 *
	 * @return  string	The anchor tag to toggle featured/unfeatured members.
	 *
	 * @since   __BUMP_VERSION__
	 */
	public function featured($value, $i, $canChange = true)
	{
		// Array of image, task, title, action
		$states = [
			0 => ['unfeatured', 'members.featured', 'COM_CONTACT_UNFEATURED', 'JGLOBAL_ITEM_FEATURE'],
			1 => ['featured', 'members.unfeatured', 'JFEATURED', 'JGLOBAL_ITEM_UNFEATURE'],
		];
		$state = ArrayHelper::getValue($states, (int) $value, $states[1]);
		$icon = $state[0] === 'featured' ? 'star featured' : 'star';

		if ($canChange) {
			$html = '<a href="#" onclick="return Joomla.listItemTask(\'cb' . $i . '\',\'' . $state[1] . '\')" class="tbody-icon'
				. ($value == 1 ? ' active' : '') . '" aria-labelledby="cb' . $i . '-desc">'
				. '<span class="fas fa-' . $icon . '" aria-hidden="true"></span></a>'
				. '<div role="tooltip" id="cb' . $i . '-desc">' . Text::_($state[3]);
		} else {
			$html = '<a class="tbody-icon disabled' . ($value == 1 ? ' active' : '')
				. '" title="' . Text::_($state[2]) . '"><span class="fas fa-' . $icon . '" aria-hidden="true"></span></a>';
		}

		return $html;
	}
}
