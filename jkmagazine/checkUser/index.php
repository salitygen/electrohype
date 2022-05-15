<?php

	error_reporting(0);
	define('_JEXEC', 1);
	define('JPATH_BASE',$_SERVER['DOCUMENT_ROOT']);

	require_once JPATH_BASE . '/includes/defines.php';
	require_once JPATH_BASE . '/includes/framework.php';
	require_once JPATH_BASE . '/jkmagazine/checkUser/lib/sanitize.php';
	require_once JPATH_BASE . '/jkmagazine/checkUser/lib/phone_format.php';
	require_once JPATH_BASE . '/jkmagazine/checkUser/lib/get_user_by_name.php';
	require_once JPATH_BASE . '/jkmagazine/checkUser/lib/antispam.php';

	$container = \Joomla\CMS\Factory::getContainer();
	$container->alias('session.web', 'session.web.site')
		->alias('session', 'session.web.site')
		->alias('JSession', 'session.web.site')
		->alias(\Joomla\CMS\Session\Session::class, 'session.web.site')
		->alias(\Joomla\Session\Session::class, 'session.web.site')
		->alias(\Joomla\Session\SessionInterface::class, 'session.web.site');
	$app = $container->get(\Joomla\CMS\Application\SiteApplication::class);
	$session = $container->get(\Joomla\CMS\Session\Session::class);
	$mainframe = \Joomla\CMS\Factory::getApplication('site');
	jimport('joomla.plugin.helper');

	use Joomla\CMS\User;
	use Joomla\CMS\User\UserHelper;

	if(empty($session->get('counter'))){
		$session->set('counter','0');
	}

	if($session->get('counter') < 10 && !$block){
		$session->set('counter',(int)$session->get('counter')+1);
	}else{
		die(json_encode(array('status'=>'Error','code'=>'0','annotation'=>'Spam detect')));
		exit();
	}


	if(isset($_POST['username']) && isset($_POST['password'])){
		
		if(!empty($_POST['username']) && !empty($_POST['password'])){
			
			$phone = (int)sanitize($_POST['username']);
			$userId = getUserId($phone);
			
			if(!empty($userId) || $userId != 0){
			
				$credentials['username'] = $phone;
				$credentials['password'] = (string)$_POST['password'];
				$result = $mainframe->login($credentials);
			
				if($result){
					$session->set('counter','0');
					print json_encode(array('status'=>'ok','phone'=>phone_format($phone)));
				}else{
					print json_encode(array('status'=>'Error','code'=>'2','annotation'=>'Error Password','phone'=>phone_format($phone)));
				}
				
			}else{
				print json_encode(array('status'=>'Error','code'=>'1','annotation'=>'Error User','phone'=>phone_format($phone)));
			}
			
		}
		
	}


?>
