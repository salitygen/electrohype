<?php

	defined('_JEXEC') or die('Access Denied');
	$json = file_get_contents(JPATH_BASE . '/jkmagazine/checkUser/blocklist.json');
	$block = false;

    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
        $ip = sanitize($_SERVER['HTTP_CLIENT_IP']);
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ip = sanitize($_SERVER['HTTP_X_FORWARDED_FOR']);
    }else{
        $ip = sanitize($_SERVER['REMOTE_ADDR']);
    }
	
	if(empty($json)){
		
		$arr[0]['ip'] = $ip;
		$arr[0]['time'] = time();
		$arr[0]['count'] = 1;
		$arr[0]['status'] = 'allowed';
		$arr[0]['phone'] = sanitize($_POST['username']);
		$json = json_encode($arr);
		file_put_contents(JPATH_BASE . '/jkmagazine/checkUser/blocklist.json',$json);
		
	}else{
		
		$arr = json_decode($json,true);
		$notFind = true;
		
		foreach($arr as $k => $data){
			if($data['ip'] == $ip){
				$notFind = false;
				$newArr[$k]['ip'] = $data['ip'];
				$newArr[$k]['phone'] = $data['phone'];
				if($data['count'] > 9){
					$newArr[$k]['time'] = time();
					if(($data['time'] + 3600) > time()){
						$newArr[$k]['count'] = $data['count'] + 1;
						$newArr[$k]['status'] = 'blocked';
						$block = true;
					}else{
						$newArr[$k]['count'] = 1;
						$newArr[$k]['status'] = 'allowed';
					}
				}else{
					$newArr[$k]['time'] = time();
					$newArr[$k]['count'] = $data['count'] + 1;
					$newArr[$k]['status'] = 'allowed';
				}
			}else{
				$newArr[$k]['ip'] = $data['ip'];
				$newArr[$k]['time'] = $data['time'];
				$newArr[$k]['count'] = $data['count'];
				$newArr[$k]['status'] = $data['status'];
				$newArr[$k]['phone'] = $data['phone'];
			}
		}
		
		if($notFind){
			$newArr = array();
			$newArr['ip'] = $ip;
			$newArr['time'] = time();
			$newArr['count'] = 1;
			$newArr['status'] = 'allowed';
			$newArr['phone'] = sanitize($_POST['username']);
			array_push($arr,$newArr);
			$json = json_encode($arr);
			file_put_contents(JPATH_BASE . '/jkmagazine/checkUser/blocklist.json',$json);
		}else{
			$json = json_encode($newArr);
			file_put_contents(JPATH_BASE . '/jkmagazine/checkUser/blocklist.json',$json);
		}
		
	}

?>