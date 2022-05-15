<?php

	defined('_JEXEC') or die('Access Denied');
	defined('SMSRU_API_TOKEN') or die('Access Denied');
	
	function message_to_smsru($phone){
		$ch = curl_init();
		curl_setopt_array(
			$ch,
			array(
				CURLOPT_URL => 'https://sms.ru/sms/send',
				CURLOPT_POST => TRUE,
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_TIMEOUT => 10,
				CURLOPT_POSTFIELDS => array(
					'api_id' => SMSRU_API_TOKEN,
					'to' => $phone,
					'json' => '1',
					'msg' => urlencode('Ваш код подтверждения: ').''.$session->get('verify_code')
				),
			)
		);
		$data = curl_exec($ch);
		return $data;
	}

?>