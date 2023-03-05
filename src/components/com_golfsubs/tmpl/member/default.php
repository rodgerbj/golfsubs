<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_golfsubs
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
\defined('_JEXEC') or die;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\HTML\HTMLHelper;

	$canDo   = ContentHelper::getActions('com_golfsubs', 'category', $this->item->catid);
	$canEdit = $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == Factory::getUser()->id);
	$tparams = $this->item->params;

if ($tparams->get('show_memname')) {
	if ($this->params->get('show_member_memname_label')) {
		echo Text::_('COM_GOLFSUBS_MEMNAME');
		}
	echo $this->item->memname;
	?><br><?php
	echo $this->item->mememail;
	?><br><?php
	echo $this->item->memphone;
}
?>

<?php if ($canEdit) : ?>
	<div class="icons">
		<div class="float-end">
			<div>
				<?php echo HTMLHelper::_('golfsubsicon.edit', $this->item, $tparams); ?>
			</div>
		</div>
	</div>
<?php endif; ?>

<!-- hp
echo $this->item->event->afterDisplayTitle;
echo $this->item->event->beforeDisplayContent;
echo $this->item->event->afterDisplayContent; -->
