<?php

	defined('_JEXEC') or die('Access Denied');
	defined('TELEGRAM_TOKEN') or die('Access Denied');
	defined('TELEGRAM_CHATID') or die('Access Denied');

	function message_to_telegram($code){
		$ch = curl_init();
		curl_setopt_array(
			$ch,
			array(
				CURLOPT_URL => 'https://api.telegram.org/bot'.TELEGRAM_TOKEN.'/sendMessage',
				CURLOPT_POST => TRUE,
				CURLOPT_RETURNTRANSFER => TRUE,
				CURLOPT_TIMEOUT => 10,
				CURLOPT_POSTFIELDS => array(
					'chat_id' => TELEGRAM_CHATID,
					'text' => 'Ваш код подтверждения: '.$code
				),
			)
		);
		$data = curl_exec($ch);
		return $data;
	}

?>