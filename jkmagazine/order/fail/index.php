<?php

	if(!defined('_JEXEC')){
		define('_JEXEC',1);
		define('JPATH_BASE',$_SERVER['DOCUMENT_ROOT']);
		define('DS',DIRECTORY_SEPARATOR);
		require_once(JPATH_BASE . DS . 'includes' . DS . 'defines.php');
		require_once(JPATH_BASE . DS . 'includes' . DS . 'framework.php');
		$app = JFactory::getApplication('site')->initialise(); 
	}
	
	$app->enqueueMessage('НЕ удалось зачислить сумму в размере '.$_POST["OutSum"].' руб. на Ваш счет!', 'error');
	$app->redirect(JRoute::_('/'));
	
?>