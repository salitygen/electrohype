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

if(empty($session->get('cart'))){
	if((int)preg_replace('/[^0-9]/','',$data['count']) == 0){
		$data['count'] = 1;
	}else{
		$data['count'] = (int)preg_replace('/[^0-9]/','',$data['count']);
	}
	$sess[$data['id']] = $data;
	$session->set('cart',$sess);
}else{
	$sess = $session->get('cart');
	if(isset($sess[$data['id']])){
		if((int)preg_replace('/[^0-9]/','',$data['count']) == 0){
			$sess[$data['id']]['count'] = (int)$sess[$data['id']]['count'] + 1;
		}else{
			$sess[$data['id']]['count'] = (int)$sess[$data['id']]['count'] + (int)preg_replace('/[^0-9]/','',$data['count']);
		}
		$session->set('cart',$sess);
	}else{
		$data['count'] = (int)preg_replace('/[^0-9]/','',$data['count']);
		$sess[$data['id']] = $data;
		$session->set('cart',$sess);
	}
}

print json_encode(array('ok'));

?>