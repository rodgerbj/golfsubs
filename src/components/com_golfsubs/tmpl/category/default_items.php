<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_golfsubs
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
\defined('_JEXEC') or die;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use GolfsubsNamespace\Component\Golfsubs\Site\Helper\RouteHelper;
HTMLHelper::_('behavior.core');
?>
<div class="com-member-category__items">
	<form action="<?php echo htmlspecialchars(Uri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm">
		<?php if (empty($this->items)) : ?>
			<p>
				<?php echo Text::_('JGLOBAL_SELECT_NO_RESULTS_MATCH'); ?>
			</p>
		<?php else : ?>
			<ul class="com-member-category__list category">
				<?php foreach ($this->items as $i => $item) : ?>
					<?php if (in_array($item->access, $this->user->getAuthorisedViewLevels())) : ?>
						<li class="row cat-list-row" >
						<div class="list-title">
							<a href="<?php echo Route::_(RouteHelper::getMemberRoute($item->slug, $item->catid)); ?>">
							<?php echo $item->memname; ?></a>
							<?php echo $item->event->afterDisplayTitle; ?>
							<?php echo $item->event->beforeDisplayContent; ?>
						</div>
						<?php echo $item->event->afterDisplayContent; ?>
					</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
			<?php if ($this->params->get('show_pagination', 2)) : ?>
			<div class="com-member-category__counter">
				<?php if ($this->params->def('show_pagination_results', 1)) : ?>
					<p class="counter">
						<?php echo $this->pagination->getPagesCounter(); ?>
					</p>
				<?php endif; ?>
				<?php echo $this->pagination->getPagesLinks(); ?>
			</div>
			<?php endif; ?>
	</form>
</div>
