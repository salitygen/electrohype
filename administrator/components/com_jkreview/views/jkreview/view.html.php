<?php

defined('_JEXEC') or die('Restricted access');
JLoader::register('JKHelper', JPATH_ADMINISTRATOR . '/components/com_jkreview/helpers/jkhelper.php');
JKHelper::csrfToken();
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
$doc = JFactory::getDocument();
$doc->addScript(JURI::base()."components/com_jkreview/assets/js/jquery-3.3.1.min.js");
$doc->addScript(JURI::base()."components/com_jkreview/assets/js/jquery.star-rating-svg.js");
$doc->addScript(JURI::base()."components/com_jkreview/assets/js/jkreview.js");
$doc->addStyleSheet(JURI::base()."components/com_jkreview/assets/css/star-rating-svg.css");
$doc->addStyleSheet(JURI::base()."components/com_jkreview/assets/css/jkreview.css");

class jkreviewViewjkreview extends JViewLegacy
{
	function display($tpl = null)
	{
		
		JToolbarHelper::addNew('view.editreview');
		JToolbarHelper::publish('task.publish');
		JToolbarHelper::unpublish('task.unpublish');
		JToolBarHelper::deleteList('task.remove');

		$this->data = JKHelper::jklist();
		
		parent::display($tpl);
		
	}
	
}