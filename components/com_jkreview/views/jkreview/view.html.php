<?php

defined('_JEXEC') or die('Restricted access');

JLoader::register('JKHelper', JPATH_BASE . '/components/com_jkreview/helpers/jkhelper.php');
jimport('joomla.application.component.view');
JKHelper::csrfToken();
$doc = JFactory::getDocument();
$doc->addScript(JURI::base()."components/com_jkreview/views/jkrevew/tmpl/js/jquery.star-rating-svg.js");
$doc->addScript(JURI::base()."components/com_jkreview/views/jkrevew/tmpl/js/jkrevew.js");
$doc->addStyleSheet(JURI::base()."components/com_jkreview/views/jkrevew/tmpl/css/star-rating-svg.css");
$doc->addStyleSheet(JURI::base()."components/com_jkreview/views/jkrevew/tmpl/css/jkrevew.css");

class jkrevewViewjkrevew extends JViewLegacy
{
	function display($tpl = null)
	{
		
		$this->params = JFactory::getApplication()->getParams();
		$this->title = $this->params->get('page_heading', '');
		$this->data = JKHelper::jkPageReview();
		parent::display($tpl);
		
	}
	
}

?>