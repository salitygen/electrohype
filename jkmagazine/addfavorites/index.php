<?php

define('_JEXEC', 1);
define('JPATH_BASE',$_SERVER['DOCUMENT_ROOT']);
require_once JPATH_BASE . '/includes/defines.php';
require_once JPATH_BASE . '/includes/framework.php';
$container = \Joomla\CMS\Factory::getContainer();
$container->alias('session.web', 'session.web.site')
	->alias('session', 'session.web.site')
	->alias('JSession', 'session.web.site')
	->alias(\Joomla\CMS\Session\Session::class, 'session.web.site')
	->alias(\Joomla\Session\Session::class, 'session.web.site')
	->alias(\Joomla\Session\SessionInterface::class, 'session.web.site');
$app = $container->get(\Joomla\CMS\Application\SiteApplication::class);
$session = $container->get(\Joomla\CMS\Session\Session::class);

$json = (string)file_get_contents("php://input");
$data = json_decode($json,true);

if(empty($session->get('favorites'))){
	$sess[$data['id']] = $data;
	$session->set('favorites',$sess);
}else{
	$sess = $session->get('favorites');
	if(isset($sess[$data['id']])){
		$count = $sess[$data['id']]['count'];
		$sess[$data['id']]['count'] = $count + $data['count'];
		$session->set('favorites',$sess);
	}else{
		$sess[$data['id']] = $data;
		$session->set('favorites',$sess);
	}
}

//print json_encode(array(md5(date("Y-m-d H:i:s").''.rand(0,10000))));
print json_encode(array('ok'));

?>