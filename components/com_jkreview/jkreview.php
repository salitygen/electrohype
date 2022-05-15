<?php
defined('_JEXEC') or die('Restricted access'); 
header("X-Frame-Options:sameorigin");
jimport('joomla.application.component.view');
$param = JRequest::getVar('view','jkreview','','string');
$controller = JControllerLegacy::getInstance('jkreview');
$input = JFactory::getApplication()->input;
$controller->execute($input->getCmd('task'));
$controller->redirect();
?>