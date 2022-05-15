<?php
error_reporting(0);
define('_JEXEC', 1);
define('JPATH_BASE',$_SERVER['DOCUMENT_ROOT']);
define('TELEGRAM_TOKEN', '5085452130:AAHykhfFip65DO5UrY3ZCcoKQ3DRAzHBd_k');
define('TELEGRAM_CHATID', '-676626866');
//define('SMSRU_API_TOKEN', '73EBDB7A-9BD5-7BC0-F043-46239E34414A');
define('SMSRU_API_TOKEN', 'D5DB61B2-FA99-6647-A682-2C07C3A2AAAD');

require_once JPATH_BASE . '/includes/defines.php';
require_once JPATH_BASE . '/includes/framework.php';
require_once JPATH_BASE . '/jkmagazine/addUser/lib/sanitize.php';
require_once JPATH_BASE . '/jkmagazine/addUser/lib/phone_format.php';
require_once JPATH_BASE . '/jkmagazine/addUser/lib/get_user_by_name.php';
require_once JPATH_BASE . '/jkmagazine/addUser/lib/message_to_telegram.php';
require_once JPATH_BASE . '/jkmagazine/addUser/lib/message_to_smsru.php';

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

$antispam = true;
if(!empty($session->get('verify_is_ok')) 
	&& !empty($session->get('verify_code')) 
	&& isset($_POST['username']) 
	&& isset($_POST['password']) 
	&& isset($_POST['verify_code'])){
	if($session->get('verify_is_ok') == $_POST['username']){
		if(!isset($_POST['get_code'])){
			if(sanitize($_POST['verify_code']) == $session->get('verify_code')){
				$antispam = false;
			}
		}
	}
}

if($antispam){
	require_once JPATH_BASE . '/jkmagazine/addUser/lib/antispam.php';
	if($session->get('counter') < 10 && !$block){
		$session->set('counter',(int)$session->get('counter')+1);
	}else{
		die(json_encode(array('status'=>'Error','code'=>'0','annotation'=>'Spam detect')));
		exit();
	}
}

if(isset($_POST['get_code'])){
	
	if(isset($_POST['username']) && isset($_POST['name'])){
		
		$phone = (int)sanitize($_POST['username']);
		$name = sanitize($_POST['name']);
		$userId = getUserId((string)$phone);

		if(empty($userId) || $userId == 0){
			
			$session->set('verify_name',$name);
			$session->set('verify_phone',$phone);
			$session->set('verify_code',rand(100106,990990));
			

			// Messages to Telegram START
			/* $result = message_to_telegram($session->get('verify_code'));
			$result = json_decode($result);
			if(isset($result->ok)){
				if($result->ok){
					$result->status_code = '100';
				}else{
					$result->status_code = 'Error';
				}
			}else{
				$result->status_code = 'Error';
			} */
			// Messages to Telegram END
			
			
			// Messages to SMSRU START
			$result = message_to_smsru($phone,$session->get('verify_code'));
			$result = json_decode($result)->sms->$phone;
			// Messages to SMSRU END
			
			
			if($result->status_code == '100'){
				print json_encode(array('status'=>'ok','phone'=>phone_format($phone)));
			}else{
				print json_encode(array('status'=>'Error','code'=>'3','annotation'=>'Error send code','phone'=>phone_format($phone)));
			}
			
		}else{
			$session->set('verify_code',rand(100106,990990));
			print json_encode(array('status'=>'Error','code'=>'4','annotation'=>'Such an account already exists','phone'=>phone_format($phone)));
		}
		
	}else{
		print json_encode(array('status'=>'Error','code'=>'5','annotation'=>'Error filling in fields','phone'=>phone_format($phone)));
	}

}else{

	if(isset($_POST['check_code']) && !empty($session->get('verify_code'))){
		
		$check_code = sanitize($_POST['check_code']);
		if($check_code == $session->get('verify_code')){
			$session->set('verify_is_ok',phone_format($session->get('verify_phone')));
			print json_encode(array('status'=>'ok','phone'=>phone_format($session->get('verify_phone'))));
		}else{
			print json_encode(array('status'=>'Error','code'=>'6','annotation'=>'Verify code is invalid','phone'=>phone_format($session->get('verify_phone'))));
		}
		
	}else{
		
		if(isset($_POST['verify_code']) && !empty($session->get('verify_code'))){
			if(sanitize($_POST['verify_code']) == $session->get('verify_code')){
				if(isset($_POST['password']) && isset($_POST['confirm_password'])){
					if($_POST['password'] == $_POST['confirm_password']){
						
						$name = $session->get('verify_name');
						$phone = (string)$session->get('verify_phone');
						$userId = getUserId($phone);
						
						if(!empty($_POST['password'])){
							$mypassword = (string)$_POST['password'];
						}else{
							$mypassword = UserHelper::genRandomPassword(10);
						}
						
						$crypt = UserHelper::hashPassword($mypassword);
						$session->set('verify_code',rand(100106,990990));
						
						if(empty($userId) || $userId == 0){
							
							$user = new JUser;
							$user->sendEmail = 0;
							$user->requireReset = 0;
							$user->name = $name;
							$user->username = $phone;
							$user->password = $crypt;
							$user->email = $phone.'@sample.ru';
							$user->groups = array('2');
							$user->save();
							
							$credentials['username'] = $phone;
							$credentials['password'] = $mypassword;
							$result = $mainframe->login($credentials);
							
							if($result){
								header('Location: /profile/');
							}else{
								print json_encode(array('status'=>'Error','code'=>'7','annotation'=>'Error Autorization','phone'=>phone_format($session->get('verify_phone'))));
							}

						}else{
							header('Location: /profile/');
							die();
						}
						
					}else{
						print json_encode(array('status'=>'Error','code'=>'8','annotation'=>'Password confirm invalid','phone'=>phone_format($session->get('verify_phone'))));
					}
				}else{
					print json_encode(array('status'=>'Error','code'=>'9','annotation'=>'Password is empty','phone'=>phone_format($session->get('verify_phone'))));
				}
			}else{
				print json_encode(array('status'=>'Error','code'=>'6','annotation'=>'Verify code is invalid','phone'=>phone_format($session->get('verify_phone'))));
			}
		}else{
			print json_encode(array('status'=>'Error','code'=>'6','annotation'=>'Verify code is empty','phone'=>phone_format($session->get('verify_phone'))));
		}
	}
}

?>
