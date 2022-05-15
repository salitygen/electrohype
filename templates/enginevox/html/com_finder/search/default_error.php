<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_search
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>
<?php if (($this->suggested && $this->params->get('show_suggested_query', 1)) || ($this->explained && $this->params->get('show_explained_query', 1))) : ?>
	<div id="search-query-explained">
			<?php // Replace the base query string with the suggested query string. ?>
			<?php $uri = JUri::getInstance($this->query->toUri()); ?>
			<?php $uri->setVar('q', $this->suggested); ?>
			<?php // Compile the suggested query link. ?>
			<?php $linkUrl = JRoute::_($uri->toString(array('path', 'query'))); ?>
			<?php $link = '<a href="' . $linkUrl . '">' . $this->escape($this->suggested) . '</a>'; ?>
			<?php echo JText::sprintf('COM_FINDER_SEARCH_SIMILAR', $link); ?>
	</div>
<?php else: ?>
<div id="centerForm">
	<div>
		<h3>Не нашли, <br>что искали?</h3>
		<p>Просто опишите вашу проблему и наши специалисты свяжутся с Вами в течение часа</p>
	</div>
	<form action="post" class="false">
		<input autocomplete="off" name="name" placeholder="Имя" required="required" type="text">
		<input type="tel" name="phone" required="required" autocomplete="off" placeholder="+7(___)___-__-__">
		<textarea autocomplete="off" rows="4" name="message" placeholder="Опишите вашу проблему"></textarea>
		<input type="hidden" name="formname" value="Скидка 10%<br>при заказе с сайта">
		<button type="submit" class="buttonClassic">Отправить</button>
	</form>
</div>
<?php endif; ?>