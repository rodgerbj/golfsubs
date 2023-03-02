<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_golfsubs
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace GolfsubsNamespace\Component\Golfsubs\Site\View\Category;
\defined('_JEXEC') or die;
use Joomla\CMS\MVC\View\CategoryView;
use GolfsubsNamespace\Component\Golfsubs\Site\Helper\RouteHelper;
/**
 * HTML View class for the Golfsubs component
 *
 * @since  __BUMP_VERSION__
 */
class HtmlView extends CategoryView
{
	/**
	 * @var    string  The name of the extension for the category
	 * @since  __BUMP_VERSION__
	 */
	protected $extension = 'com_golfsubs';
	/**
	 * @var    string  Default title to use for page title
	 * @since  __BUMP_VERSION__
	 */
	protected $defaultPageTitle = 'COM_GOLFSUBS_DEFAULT_PAGE_TITLE';
	/**
	 * @var    string  The name of the view to link individual items to
	 * @since  __BUMP_VERSION__
	 */
	protected $viewName = 'member';
	/**
	 * Run the standard Joomla plugins
	 *
	 * @var    boolean
	 * @since  __BUMP_VERSION__
	 */
	protected $runPlugins = true;
	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise an Error object.
	 */
	public function display($tpl = null)
	{
		parent::commonCategoryDisplay();
		$this->pagination->hideEmptyLimitstart = true;
		foreach ($this->items as $item) {
			$item->slug = $item->id;
			$temp = $item->params;
			$item->params = clone $this->params;
			$item->params->merge($temp);
		}
		return parent::display($tpl);
	}
	/**
	 * Prepares the document
	 *
	 * @return  void
	 */
	protected function prepareDocument()
	{
		parent::prepareDocument();
		$menu = $this->menu;
		$id = (int) @$menu->query['id'];
		if ($menu && (!isset($menu->query['option']) || $menu->query['option'] != $this->extension || $menu->query['view'] == $this->viewName
			|| $id != $this->category->id)) {
			$path = [['title' => $this->category->title, 'link' => '']];
			$category = $this->category->getParent();
			while ((!isset($menu->query['option']) || $menu->query['option'] !== 'com_golfsubs' || $menu->query['view'] === 'member'
				|| $id != $category->id) && $category->id > 1) {
				$path[] = ['title' => $category->title, 'link' => RouteHelper::getCategoryRoute($category->id, $category->language)];
				$category = $category->getParent();
			}
			$path = array_reverse($path);
			foreach ($path as $item) {
				$this->pathway->addItem($item['title'], $item['link']);
			}
		}
		parent::addFeed();
	}
}