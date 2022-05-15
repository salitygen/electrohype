<?php

defined('_JEXEC') or die('Restricted access');
header("X-Frame-Options:sameorigin");

jimport('joomla.application.component.view');
JToolbarHelper::title(JText::_('Система отзывов'),true);

$app = JFactory::getApplication(); 
$param = $app->input->getCmd('view','jkreview');

$controller = JControllerLegacy::getInstance('jkreview');
$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));
$controller->redirect();

JToolBarHelper::preferences('com_jkreview', '500');
	
JHtmlSidebar::addEntry(
	JText::_('Список комментариев'),
	'index.php?option=com_jkreview',
	$param == 'jkreview' ? true:false
);

/* JSubMenuHelper::addEntry(
	JText::_('Настройки'),
	'index.php?option=com_jkreview&view=settings',
	$param == 'settings' ? true:false
); */

?>