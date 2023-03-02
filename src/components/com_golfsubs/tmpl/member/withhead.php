<?php

\defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

echo "<hr>Headertext with header.<hr>";

if ($this->item->params->get('show_memname')) {
	if ($this->params->get('show_member_memname_label')) {
		echo Text::_('COM_GOLFSUBS_MEMNAME') . $this->item->memname;
	} else {
		echo $this->item->memname;
		echo $this->item->mememail;
		echo $this->item->memphone;
	}
}

echo $this->item->event->afterDisplayTitle;
echo $this->item->event->beforeDisplayContent;
echo $this->item->event->afterDisplayContent;