<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_golfsubs
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;

use Joomla\CMS\HTML\HTMLHelper;

use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\LayoutHelper;

HTMLHelper::_('behavior.keepalive');
HTMLHelper::_('behavior.formvalidator');
HTMLHelper::_('script', 'com_golfsubs/admin-members-letter.js', ['version' => 'auto', 'relative' => true]);
HTMLHelper::_('script', 'com_golfsubs/admin-members-name.js', ['version' => 'auto', 'relative' => true]);
HTMLHelper::_('script', 'com_golfsubs/admin-members-email.js', ['version' => 'auto', 'relative' => true]);

$this->tab_name  = 'com-members-form';
$this->ignore_fieldsets = ['details'];
$this->useCoreUI = true;
?>
<form action="<?php echo Route::_('index.php?option=com_golfsubs&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-vertical">
	<fieldset>
		<?php echo HTMLHelper::_('uitab.startTabSet', $this->tab_name, ['active' => 'details']); ?>
		<?php echo HTMLHelper::_('uitab.addTab', $this->tab_name, 'details', empty($this->item->id) ? Text::_('COM_GOLFSUBS_NEW_MEMBER') : Text::_('COM_GOLFSUBS_EDIT_MEMBER')); ?>
		<?php echo $this->form->renderField('memname'); ?>
		<?php echo $this->form->renderField('mememail'); ?>
		<?php echo $this->form->renderField('memphone'); ?>
		<?php echo $this->form->renderField('catid'); ?>

		<?php if (is_null($this->item->id)) : ?>
			<?php echo $this->form->renderField('alias'); ?>
		<?php endif; ?>
		<?php echo $this->form->renderFieldset('details'); ?>
		<?php echo HTMLHelper::_('uitab.endTab'); ?>
		
		<?php echo LayoutHelper::render('joomla.edit.params', $this); ?>
		<?php echo HTMLHelper::_('uitab.endTabSet'); ?>

		<input type="hidden" name="task" value=""/>
		<input type="hidden" name="return" value="<?php echo $this->return_page; ?>"/>
		<?php echo HTMLHelper::_('form.token'); ?>
	</fieldset>
	<div class="mb-2">
		<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('member.save')">
			<span class="fas fa-check" aria-hidden="true"></span>
			<?php echo Text::_('JSAVE'); ?>
		</button>
		<button type="button" class="btn btn-danger" onclick="Joomla.submitbutton('member.cancel')">
			<span class="fas fa-times-cancel" aria-hidden="true"></span>
			<?php echo Text::_('JCANCEL'); ?>
		</button>
	</div>
</form>