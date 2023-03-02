<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_golfsubs
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace GolfsubsNamespace\Component\Golfsubs\Administrator\Service\HTML;

\defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use GolfsubsNamespace\Component\Golfsubs\Site\Helper\RouteHelper;
use Joomla\Registry\Registry;

/**
 * Content Component HTML Helper
 *
 * @since  __DEPLOY_VERSION__
 */
class Icon
{
	/**
	 * The application
	 *
	 * @var    CMSApplication
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	private $application;

	/**
	 * Service constructor
	 *
	 * @param   CMSApplication  $application  The application
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function __construct(CMSApplication $application)
	{
		$this->application = $application;
	}

	/**
	 * Method to generate a link to the create item page for the given category
	 *
	 * @param   object    $category  The category information
	 * @param   Registry  $params    The item parameters
	 * @param   array     $attribs   Optional attributes for the link
	 *
	 * @return  string  The HTML markup for the create item link
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public static function create($category, $params, $attribs = [])
	{
		$uri = Uri::getInstance();

		$url = 'index.php?option=com_golfsubs&task=member.add&return=' . base64_encode($uri) . '&id=0&catid=' . $category->id;

		$text = LayoutHelper::render('joomla.content.icons.create', ['params' => $params, 'legacy' => false]);

		// Add the button classes to the attribs array
		if (isset($attribs['class'])) {
			$attribs['class'] .= ' btn btn-primary';
		} else {
			$attribs['class'] = 'btn btn-primary';
		}

		$button = HTMLHelper::_('link', Route::_($url), $text, $attribs);

		$output = '<span class="hasTooltip" title="' . HTMLHelper::_('tooltipText', 'COM_GOLFSUBS_CREATE_MEMBER') . '">' . $button . '</span>';

		return $output;
	}

	/**
	 * Display an edit icon for the member.
	 *
	 * This icon will not display in a popup window, nor if the member is trashed.
	 * Edit access checks must be performed in the calling code.
	 *
	 * @param   object    $member  The member information
	 * @param   Registry  $params   The item parameters
	 * @param   array     $attribs  Optional attributes for the link
	 * @param   boolean   $legacy   True to use legacy images, false to use icomoon based graphic
	 *
	 * @return  string   The HTML for the member edit icon.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function edit($member, $params, $attribs = [], $legacy = false)
	{
		$user = Factory::getUser();
		$uri  = Uri::getInstance();

		// Ignore if in a popup window.
		if ($params && $params->get('popup')) {
			return '';
		}

		// Ignore if the state is negative (trashed).
		if ($member->published < 0) {
			return '';
		}

		// Set the link class
		$attribs['class'] = 'dropdown-item';

		// Show checked_out icon if the member is checked out by a different user
		if (property_exists($member, 'checked_out')
			&& property_exists($member, 'checked_out_time')
			&& $member->checked_out > 0
			&& $member->checked_out != $user->get('id')) {
			$checkoutUser = Factory::getUser($member->checked_out);
			$date         = HTMLHelper::_('date', $member->checked_out_time);
			$tooltip      = Text::_('JLIB_HTML_CHECKED_OUT') . ' :: ' . Text::sprintf('COM_GOLFSUBS_CHECKED_OUT_BY', $checkoutUser->name)
				. ' <br /> ' . $date;

			$text = LayoutHelper::render('joomla.content.icons.edit_lock', ['tooltip' => $tooltip, 'legacy' => $legacy]);

			$output = HTMLHelper::_('link', '#', $text, $attribs);

			return $output;
		}

		if (!isset($member->slug)) {
			$member->slug = "";
		}

		$memberUrl = RouteHelper::getMemberRoute($member->slug, $member->catid);
		$url        = $memberUrl . '&task=member.edit&id=' . $member->id . '&return=' . base64_encode($uri);

		if ($member->published == 0) {
			$overlib = Text::_('JUNPUBLISHED');
		} else {
			$overlib = Text::_('JPUBLISHED');
		}

		if (!isset($member->created)) {
			$date = HTMLHelper::_('date', 'now');
		} else {
			$date = HTMLHelper::_('date', $member->created);
		}

		if (!isset($created_by_alias) && !isset($member->created_by)) {
			$author = '';
		} else {
			$author = $member->created_by_alias ?: Factory::getUser($member->created_by)->name;
		}

		$overlib .= '&lt;br /&gt;';
		$overlib .= $date;
		$overlib .= '&lt;br /&gt;';
		$overlib .= Text::sprintf('COM_GOLFSUBS_WRITTEN_BY', htmlspecialchars($author, ENT_COMPAT, 'UTF-8'));

		$icon = $member->published ? 'edit' : 'eye-slash';

		if (strtotime($member->publish_up) > strtotime(Factory::getDate())
			|| ((strtotime($member->publish_down) < strtotime(Factory::getDate())) && $member->publish_down != Factory::getDbo()->getNullDate())) {
			$icon = 'eye-slash';
		}

		$text = '<span class="hasTooltip fa fa-' . $icon . '" title="'
			. HTMLHelper::tooltipText(Text::_('COM_GOLFSUBS_EDIT_MEMBER'), $overlib, 0, 0) . '"></span> ';
		$text .= Text::_('JGLOBAL_EDIT');

		$attribs['title'] = Text::_('COM_GOLFSUBS_EDIT_MEMBER');
		$output           = HTMLHelper::_('link', Route::_($url), $text, $attribs);

		return $output;
	}
}