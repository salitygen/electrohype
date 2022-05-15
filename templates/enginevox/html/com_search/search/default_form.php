<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_search
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::_('bootstrap.tooltip');
?>
<a href="/"><h1 class="headTitleNews">Поиск по сайту</h1></a>
<form id="searchForm" class="searchTechnicForm" action="<?php echo JRoute::_('index.php?option=com_search'); ?>" method="post">
	<img class="searchTechnicIcon" src="/templates/itmaster/img/searchTechnicForm.svg">
	<input type="text" required="required" autocomplete="off" name="searchword" title="<?php echo JText::_('COM_SEARCH_SEARCH_KEYWORD'); ?>" placeholder="Что нужно починить?" maxlength="<?php echo $upper_limit; ?>" value="<?php echo $this->escape($this->origkeyword); ?>" x-webkit-speech />
	<button name="Search" onclick="this.form.submit()">Найти</button>
	<input type="hidden" name="task" value="search" />
</form>
