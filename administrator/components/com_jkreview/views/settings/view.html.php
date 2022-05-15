<?php

defined('_JEXEC') or die('Restricted access');
JLoader::register('JKHelper', JPATH_ADMINISTRATOR . '/components/com_jkreview/helpers/jkhelper.php');
JKHelper::csrfToken();

class jkreviewViewsettings extends JViewLegacy
{
	function display($tpl = null)
	{
		
		JToolbarHelper::addNew('review.add');
		JToolbarHelper::publish('review.publish');
		JToolbarHelper::unpublish('review.unpublish');
		
		$this->msg = 'Hello World';
		parent::display($tpl);
	}
	
}