<?php
error_reporting(0);

if(!defined('_JEXEC')){
	define('_JEXEC',1);
	define('JPATH_BASE',$_SERVER['DOCUMENT_ROOT']);
	define('DS',DIRECTORY_SEPARATOR);
	require_once(JPATH_BASE . DS . 'includes' . DS . 'defines.php');
	require_once(JPATH_BASE . DS . 'includes' . DS . 'framework.php');
	$app = JFactory::getApplication('site')->initialise(); 
	$session = JFactory::getSession();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

function strip_data($text){
	
	$quotes = array("\x27", "\x22", "\x60", "\t", "\n", "\r", "*", "%", "<", ">", "?", "!");
	$goodquotes = array('-', '+', '#','"');
	$repquotes = array("\-", "\+", "\#","&quot;");
	$text = htmlspecialchars($text);
	$text = stripslashes($text);
	$text = trim(strip_tags($text));
	$text = str_replace($quotes,'',$text);
	$text = str_replace($repquotes,$goodquotes,$text);
	return $text;
	
}

// ORDER START

if(isset($_POST['order']) && is_array($_POST['order'])){
	
	if(isset($_POST['phone']) && isset($_POST['name'])){
		
		if(!isset($_POST['agree']) || $_POST['agree'] != 'on'){
			die('0');
		}

		$price = $count = 0;

		$message = '<h3>Список заказов:</h3>';
		$table = '<table style="border-collapse:collapse;width:100%;">';
		$table .= '<thead><tr><th style="border:1px solid #ccc;padding:5px;">Наименование</th><th style="border:1px solid #ccc;padding:5px;">Цена</th><th style="border:1px solid #ccc;padding:5px;">Колличество</th><th style="border:1px solid #ccc;padding:5px;">Общая стоимость</th></tr></thead><tbody>';
		
		foreach($_POST['order'] as $k => $position){
			
			$table .= '<tr>';
			$table .= '<td style="border:1px solid #ccc;padding:5px;">'.strip_data($position['car']).'</td>';
			$table .= '<td style="border:1px solid #ccc;padding:5px;text-align:center;">'. number_format((int)strip_data($position['price']),0,'','&nbsp;') .'&nbsp;₽</td>';
			$table .= '<td style="border:1px solid #ccc;padding:5px;text-align:center;">'.strip_data($position['count']).'&nbsp;шт.</td>';
			$table .= '<td style="border:1px solid #ccc;padding:5px;text-align:center;">'. number_format(((int)strip_data($position['price']) * (int)strip_data($position['count'])),0,'','&nbsp;') .'&nbsp;₽</td>';
			$table .= '</tr>';
			
			$count = $count + (int)strip_data($position['count']);
			$price = $price + ((int)strip_data($position['price']) * (int)strip_data($position['count']));
			
		}
		
		if(strip_data($_POST['shipping']) != 0){
			$tax = 300;
			$taxName = '';
			$price = $price + $tax;
		}else{
			$tax = 0;
			$taxName = '(Курьером по Краснодару)';
		}
		
		$table .= '<tr><td style="border:1px solid #ccc;padding:5px;" colspan="2">Доставка '.$taxName.'</td><td style="border:1px solid #ccc;padding:5px;text-align:center;">-</td><td style="border:1px solid #ccc;padding:5px;text-align:center;">'. number_format($tax,0,'','&nbsp;') .'&nbsp;₽</td></tr>';
		
		$table .= '<tr><td style="border:1px solid #ccc;padding:5px;" colspan="2">Итог</td><td style="border:1px solid #ccc;padding:5px;text-align:center;"><b>'.$count.'&nbsp;шт.</b></td><td style="border:1px solid #ccc;padding:5px;text-align:center;"><b>'. number_format($price,0,'','&nbsp;') .'&nbsp;₽</b></td></tr>';
		$table .= '</tbody></table>';
		
		$message .= $table;
		$message .= '<h3>Информация для доставки</h3>';

		$table = '<table style="border-collapse:collapse;width:100%;">';
		$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Имя</td><td style="border:1px solid #ccc;padding:5px;">'.strip_data($_POST['name']).'</td></tr>';
		$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Фамилия</td><td style="border:1px solid #ccc;padding:5px;">'.strip_data($_POST['last_name']).'</td></tr>';
		$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Страна</td><td style="border:1px solid #ccc;padding:5px;">'.strip_data($_POST['country']).'</td></tr>';
		$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Город</td><td style="border:1px solid #ccc;padding:5px;">'.strip_data($_POST['city']).'</td></tr>';
		$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Адрес</td><td style="border:1px solid #ccc;padding:5px;">'.strip_data($_POST['address']).'</td></tr>';
		$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Индекс</td><td style="border:1px solid #ccc;padding:5px;">'.strip_data($_POST['postcode']).'</td></tr>';
		$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Телефон</td><td style="border:1px solid #ccc;padding:5px;">'.strip_data($_POST['phone']).'</td></tr>';
		$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Email</td><td style="border:1px solid #ccc;padding:5px;">'.strip_data($_POST['email']).'</td></tr>';
		$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Способ доставки</td><td style="border:1px solid #ccc;padding:5px;">'.strip_data($_POST['shipping']).'</td></tr>';
		$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Дополнительная информация</td><td style="border:1px solid #ccc;padding:5px;">'.strip_data($_POST['additional']).'</td></tr>';

		$message .= $table;
		$session->clear('cart');

		$mail = new PHPMailer;
		$mail->CharSet = 'UTF-8';
		$mail->isSMTP(); 
		$mail->Host = 'smtp.yandex.ru'; 
		$mail->SMTPAuth = true; 
		$mail->Username = 'no-reply@ojo-beauty.ru';
		$mail->Password = '!12345Qaaaaa';
		$mail->SMTPSecure = 'ssl'; 
		$mail->Port = 465;
		$mail->setFrom('no-reply@ojo-beauty.ru','Почтовый робот');
		$mail->addAddress('info@ojo-beauty.ru');
		$mail->addAddress('salitygen@yandex.ru');
		$mail->isHTML(true); 
		$mail->Subject = 'Сообщение с сайта ojo-beauty.ru';
		$mail->Body = '<h3>Сообщение с сайта: </h3>'.$message;
		$mail->send();
		
		if(isset($_POST['lang'])){
			$lang = strip_data($_POST['lang']);
		}

		if($lang == 'ru'){
			$app->enqueueMessage(JText::_('ORDER_DONE_RU'), 'success');
			$app->redirect(JRoute::_('/ru/'));
		}else{
			$app->enqueueMessage(JText::_('ORDER_DONE'), 'success');
			$app->redirect(JRoute::_('/'));
		}

	}else{
		
		print 1;
		
	}

}else{
// ORDER END

	if(isset($_POST['form']) && $_POST['form'] != ''){
		
		$formName 	= strip_data($_POST['form']);
		$message 	= '<h3>'.$formName.'</h3><table style="border-collapse:collapse;width:100%;"><tbody>';
		
		foreach($_POST as $k => $data){
			
			$message .= '<tr>';
			
			switch($k){
				case 'model': 
					$message .= '<td style="border:1px solid #ccc;padding:5px;">Модель</td>';
					$message .= '<td style="border:1px solid #ccc;padding:5px;">'. strip_data($data) .'<td>';
				break;
				case 'engine': 
					$message .= '<td style="border:1px solid #ccc;padding:5px;">Двигатель</td>';
					$message .= '<td style="border:1px solid #ccc;padding:5px;">'. strip_data($data) .'<td>';
				break;
				case 'details':
					$message .= '<td style="border:1px solid #ccc;padding:5px;">Подробности</td>';
					$message .= '<td style="border:1px solid #ccc;padding:5px;">'. strip_data($data) .'<td>';
				break;
				case 'email': 
					$message .= '<td style="border:1px solid #ccc;padding:5px;">Email</td>';
					$message .= '<td style="border:1px solid #ccc;padding:5px;">'. strip_data($data) .'<td>';
				break;
				case 'contact_name':
					$message .= '<td style="border:1px solid #ccc;padding:5px;">Имя</td>';
					$message .= '<td style="border:1px solid #ccc;padding:5px;">'. strip_data($data) .'<td>';
				break;
				case 'message':
					$message .= '<td style="border:1px solid #ccc;padding:5px;">Сообщение</td>';
					$message .= '<td style="border:1px solid #ccc;padding:5px;">'. strip_data($data) .'<td>';
				break;
				case 'company_name':
					$message .= '<td style="border:1px solid #ccc;padding:5px;">Название компании</td>';
					$message .= '<td style="border:1px solid #ccc;padding:5px;">'. strip_data($data) .'<td>';
				break;
				case 'country':
					$message .= '<td style="border:1px solid #ccc;padding:5px;">Страна</td>';
					$message .= '<td style="border:1px solid #ccc;padding:5px;">'. strip_data($data) .'<td>';
				break;
			}
			
			$message .= '</tr>';
			
		}
		
		$message .= '</tbody></table>';
		
		$mail = new PHPMailer;
		$mail->CharSet = 'UTF-8';
		$mail->isSMTP(); 
		$mail->Host = 'smtp.yandex.ru'; 
		$mail->SMTPAuth = true; 
		$mail->Username = 'no-reply@ojo-beauty.ru';
		$mail->Password = '!12345Qaaaaa';
		$mail->SMTPSecure = 'ssl'; 
		$mail->Port = 465;
		$mail->setFrom('no-reply@ojo-beauty.ru','Почтовый робот');
		$mail->addAddress('info@ojo-beauty.ru');
		$mail->addAddress('salitygen@yandex.ru');
		$mail->isHTML(true); 
		$mail->Subject = 'Сообщение с сайта ojo-beauty.ru';
		$mail->Body = '<h3>Сообщение с сайта: </h3>'.$message;
		$mail->send();

		$app->enqueueMessage(JText::_('MESSAGE_DONE_RU'), 'success');
		$app->redirect(JRoute::_(strip_data($_SERVER['HTTP_REFERER'])));
		
	}else{
		die('0');
	}

}