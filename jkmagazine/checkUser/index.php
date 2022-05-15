<?php
error_reporting(0);
define('_JEXEC',1);
define('DS',DIRECTORY_SEPARATOR);
define('JPATH_BASE',$_SERVER['DOCUMENT_ROOT']);
require_once(JPATH_BASE . DS .'includes'. DS .'defines.php');
require_once(JPATH_BASE . DS .'includes'. DS .'framework.php');
require_once(JPATH_BASE . DS .'jkmagazine'. DS .'checkUser'. DS .'lib'. DS .'sanitize.php');
require_once(JPATH_BASE . DS .'jkmagazine'. DS .'checkUser'. DS .'lib'. DS .'phone_format.php');
require_once(JPATH_BASE . DS .'jkmagazine'. DS .'checkUser'. DS .'lib'. DS .'get_user_by_name.php');

use \Joomla\CMS\Factory;
$container = \Joomla\CMS\Factory::getContainer();
$container->alias('session.web', 'session.web.site')
	->alias('session', 'session.web.site')
	->alias('JSession', 'session.web.site')
	->alias(\Joomla\CMS\Session\Session::class, 'session.web.site')
	->alias(\Joomla\Session\Session::class, 'session.web.site')
	->alias(\Joomla\Session\SessionInterface::class, 'session.web.site');
$app = $container->get(\Joomla\CMS\Application\SiteApplication::class);
\Joomla\CMS\Factory::$application = $app;
$user = &JFactory::getUser();

if(empty($_SESSION['counter'])){
	$_SESSION['counter'] = 0;
}

if(isset($_POST['username']) && isset($_POST['password'])){
	
	if(!empty($_POST['username']) && !empty($_POST['password'])){
		
		$phone = (int)sanitize($_POST['username']);
		$userId = getUserId($phone);
		
		if(!empty($userId) || $userId != 0){
		
			$credentials['username'] = $phone;
			$credentials['password'] = (string)$_POST['password'];
			$result = $app->login($credentials);
		
			if($result){
				$_SESSION['counter'] = 0;
				print json_encode(array('status'=>'ok','phone'=>phone_format($phone)));
			}else{
				require_once JPATH_BASE . '/jkmagazine/checkUser/lib/antispam.php';
				print json_encode(array('status'=>'Error','code'=>'2','annotation'=>'Error Password','phone'=>phone_format($phone)));
			}
			
		}else{
			
			print json_encode(array('status'=>'Error','code'=>'1','annotation'=>'Error User','phone'=>phone_format($phone)));
			
		}
		
	}
	
}

if($_SESSION['counter'] < 10 && !$block){
	$_SESSION['counter'] = (int)$_SESSION['counter']+1;
}else{
	die(json_encode(array('status'=>'Error','code'=>'0','annotation'=>'Spam detect')));
	exit();
}

?>
