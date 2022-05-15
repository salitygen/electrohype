<?php

if(!defined('_JEXEC')){
	define('_JEXEC',1);
	define('JPATH_BASE',$_SERVER['DOCUMENT_ROOT']);
	define('DS',DIRECTORY_SEPARATOR);
	require_once(JPATH_BASE . DS . 'includes' . DS . 'defines.php');
	require_once(JPATH_BASE . DS . 'includes' . DS . 'framework.php');
	$app = JFactory::getApplication('site')->initialise(); 
	$session = JFactory::getSession();
	$session->clear('cart');
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

if(isset($_GET['id'])){
	$id = strip_data($_GET['id']);
	if($id != ''){
		
		$db = JFactory::getDbo();
		$query = "SELECT * FROM #__orders WHERE order_hash='{$id}' AND order_status = 0";
		$db->setQuery($query);
		$result = $db->loadObjectList();
		
		if($result){
			
			$result = $result[0];

			$mrh_pass1 = "ijgUs3fQ4Y62aejvwT6z"; // Боевой
			//$mrh_pass1 = "r7xOuetsOn8ratYq70h1"; // Учебный
			$mrh_login = "ojobeauty";
			
			$inv_desc = "123";
			$in_curr = "";
			$culture = "ru";
			$shp_id = $result->order_user_email;
			
			$count = 0;
			$price = 0;
			
			// ЧЕКИ
			//osn – общая СН;
			//usn_income – упрощенная СН (доходы);
			//usn_income_outcome – упрощенная СН (доходы минус расходы);
			//envd – единый налог на вмененный доход;
			
			$cheque = array();
			$cheque['sno'] = 'osn';
			
			$products = json_decode($result->order_products);

			foreach($products as $k => $product){
				$cheque['items'][$k]['name'] = $product->product_name;
				$cheque['items'][$k]['quantity'] = $product->product_count;
				$cheque['items'][$k]['sum'] = (float)$product->product_price * (int)$product->product_count;
				$cheque['items'][$k]['payment_method'] = 'full_payment';
				$cheque['items'][$k]['payment_object'] = 'commodity';
				$cheque['items'][$k]['tax'] = 'none';
				$count = $count + (int)$product->product_count;
				$price = $price + (float)$product->product_price * (int)$product->product_count;
			}
			
			if($result->order_user_shipment_id != 0){
				
				$tax = 300;
				$price = $price + $tax;
				
				$p['name'] = 'Доставка';
				$p['quantity'] = '1';
				$p['sum'] = $tax;
				$p['payment_method'] = 'full_payment';
				$p['payment_object'] = 'service';
				$p['tax'] = 'none';

				array_push($cheque['items'],$p);
				
			}
			
			$out_summ = $price;
			$inv_id = $result->order_id;
			$user_email = $result->order_user_email;
			
			$receipt = urlencode(json_encode($cheque, JSON_UNESCAPED_UNICODE));
			$crc  = md5("$mrh_login:$out_summ:$inv_id:$receipt:$mrh_pass1:Shp_id=$shp_id");

			$form  = '<form id="payform" action="https://auth.robokassa.ru/Merchant/Index.aspx" method="POST">';
			$form .= '<input type="hidden" name="MerchantLogin" value="'.$mrh_login.'">';
			$form .= '<input type="hidden" name="OutSum" value="'.$out_summ.'">';
			$form .= '<input type="hidden" name="InvId" value="'.$inv_id.'">';
			$form .= '<input type="hidden" name="Description" value="'.$inv_desc.'">';
			$form .= '<input type="hidden" name="SignatureValue" value="'.$crc.'">';
			$form .= '<input type="hidden" name="Shp_id" value="'.$shp_id.'">';
			$form .= '<input type="hidden" name="IncCurrLabel" value="'.$in_curr.'">';
			$form .= '<input type="hidden" name="Culture" value="'.$culture.'">';
			$form .= '<input type="hidden" name="isTest" value="1">';
			$form .= '<textarea type="hidden" name="Receipt">'.$receipt.'</textarea>';
			$form .= '<input type="hidden" name="Email" value="'.$user_email.'">';
			$form .= '<input type="submit" value="Оплатить">';
			$form .= '</form>';
			
			print '<html><div style="display:none;">'.$form.'</div><script>function submitform(){document.getElementById("payform").submit();}window.onload = submitform;</script></html>';
			
		}else{
			
			$app->enqueueMessage('Заказ не найден или уже был оплачен', 'success');
			$app->redirect(JRoute::_('/'));
			
		}

	}
}

if(isset($_POST['order']) && is_array($_POST['order'])){
	
	if(isset($_POST['phone']) && isset($_POST['email'])){
		
		if(!isset($_POST['agree']) || $_POST['agree'] != 'on'){
			
			die('0');
			
		}else{
			
			print '<h3 style="width:170px;margin:0 auto;margin-top:40vh;">Перенаправление...</h3>';
			
			$db = JFactory::getDbo();
			$user = JFactory::getUser();
			$mrh_login = "ojobeauty";
			
			$mrh_pass1 = "ijgUs3fQ4Y62aejvwT6z"; // Боевой
			//$mrh_pass1 = "r7xOuetsOn8ratYq70h1"; // Учебный
			
			$inv_desc = "123";
			$in_curr = "";
			$culture = "ru";
			$shp_id = strip_data($_POST['email']);
			$user_name = strip_data($_POST['name']);
			$user_firstname = strip_data($_POST['last_name']);
			$user_country = strip_data($_POST['country']);
			$user_city = strip_data($_POST['city']);
			$user_address = strip_data($_POST['address']);
			$user_zipcode = strip_data($_POST['postcode']);
			$user_phone = strip_data($_POST['phone']);
			$user_email = strip_data($_POST['email']);
			$user_shipment_id = strip_data($_POST['shipping']);
			$user_additional = strip_data($_POST['additional']);

			$i = 0;
			$n = 0;
			$count = 0;
			$price = 0;
			
			foreach($_POST['order'] as $k => $order){
				$ids[$i] = strip_data($k);
				$arrOrder[$i]['product_id'] = $ids[$i];
				$arrOrder[$i]['product_count'] = (int)strip_data($order['count']);
				$i++;
			}
			
			$product_ids = implode(',',$ids);
			// Переписать запрос с учетом дефолтной цены
			$query = "SELECT * FROM #__fields_values, #__content WHERE #__fields_values.field_id = '15' AND #__fields_values.item_id = #__content.id AND #__fields_values.item_id IN({$product_ids})";
			$db->setQuery($query);
			$result = $db->loadObjectList();
			
			// ЧЕКИ
			//osn – общая СН;
			//usn_income – упрощенная СН (доходы);
			//usn_income_outcome – упрощенная СН (доходы минус расходы);
			//envd – единый налог на вмененный доход;
			
			$cheque = array();
			$cheque['sno'] = 'osn';

			foreach($result as $k => $product){
				foreach($arrOrder as $order){
					if($product->item_id == $order['product_id']){
						$cheque['items'][$n]['name'] = $product->title;
						$cheque['items'][$n]['quantity'] = $order['product_count'];
						$cheque['items'][$n]['sum'] = (float)$product->value * (int)$order['product_count'];
						$cheque['items'][$n]['payment_method'] = 'full_payment';
						$cheque['items'][$n]['payment_object'] = 'commodity';
						$cheque['items'][$n]['tax'] = 'none';
						$arr[$k]['product_id'] = $product->item_id;
						$arr[$k]['product_name'] = $product->title;
						$arr[$k]['product_count'] = $order['product_count'];
						$arr[$k]['product_price'] = $product->value;
						$count = $count + $order['product_count'];
						$price = $price + (float)$product->value * $order['product_count'];
						$n++;
					}
				}
			}
			
			$products = json_encode($arr, JSON_UNESCAPED_UNICODE);
			
			switch ($user_shipment_id) {
				case 1:$taxName = "В пункт выдачи заказов СДЭК"; break;
				case 2:$taxName = "Почта России (обычное отправление)"; break;
				case 3:$taxName = "Почта России (1 класс)"; break;
				default:$taxName = "Курьером по Краснодару";
			}
			
			if($user_shipment_id !=0 && $price < 3000){
				
				$tax = 300;
				$price = $price + $tax;
				
				$p['name'] = 'Доставка';
				$p['quantity'] = '1';
				$p['sum'] = $tax;
				$p['payment_method'] = 'full_payment';
				$p['payment_object'] = 'service';
				$p['tax'] = 'none';
	
				array_push($cheque['items'],$p);
				
			}else{
				
				if($user_shipment_id == 0 && $price < 1000){
					
					$tax = 300;
					$price = $price + $tax;
					
					$p['name'] = 'Доставка';
					$p['quantity'] = '1';
					$p['sum'] = $tax;
					$p['payment_method'] = 'full_payment';
					$p['payment_object'] = 'service';
					$p['tax'] = 'none';
		
					array_push($cheque['items'],$p);
					
				}else{
					
					$tax = 0;
					
				}
				
			}

			$order_tax = $tax;
			$out_summ = $price;
			
			$query = "SELECT * FROM #__orders WHERE order_user_email = '{$user_email}'";
			$db->setQuery($query);
			$result = $db->loadObjectList();
			
			if(empty($result)){	
				$inv_id = 1;
			}else{
				foreach($result as $inv){
					$inv_id = (int)$inv->order_id + 1;
				}
			}
			
			$order_hash = md5($inv_id.'qASCV45'.$user_email);

			$query = "INSERT INTO #__orders (order_status,order_summ,order_products,order_date_create,order_user_name,order_user_firstname,order_user_email,order_user_country, order_user_city,order_user_address,order_user_zipcode,order_user_phone,order_user_shipment_id,order_user_additional,order_tax,order_hash) VALUES (0,{$out_summ},'{$products}',NOW(),'{$user_name}','{$user_firstname}','{$user_email}','{$user_country}','{$user_city}','{$user_address}','{$user_zipcode}','{$user_phone}','{$user_shipment_id}','{$user_additional}',{$order_tax},'{$order_hash}')";
			$db->setQuery($query);
			$db->execute();
			
			$link = 'https://ojo-beauty.ru/payment/?id='.$order_hash;
			$paymentlink = '<a style="color: #fff;text-decoration: none;background-color: #0072cd;padding: 5px 10px;border-radius: 4px;" href="'.$link.'">Оплатить</a>';
			
			$receipt = urlencode(json_encode($cheque, JSON_UNESCAPED_UNICODE));
			$crc  = md5("$mrh_login:$out_summ:$inv_id:$receipt:$mrh_pass1:Shp_id=$shp_id");

			$form  = '<form id="payform" action="https://auth.robokassa.ru/Merchant/Index.aspx" method="POST">';
			$form .= '<input type="hidden" name="MerchantLogin" value="'.$mrh_login.'">';
			$form .= '<input type="hidden" name="OutSum" value="'.$out_summ.'">';
			$form .= '<input type="hidden" name="InvId" value="'.$inv_id.'">';
			$form .= '<input type="hidden" name="Description" value="'.$inv_desc.'">';
			$form .= '<input type="hidden" name="SignatureValue" value="'.$crc.'">';
			$form .= '<input type="hidden" name="Shp_id" value="'.$shp_id.'">';
			$form .= '<input type="hidden" name="IncCurrLabel" value="'.$in_curr.'">';
			$form .= '<input type="hidden" name="Culture" value="'.$culture.'">';
			//$form .= '<input type="hidden" name="isTest" value="1">';
			$form .= '<textarea type="hidden" name="Receipt">'.$receipt.'</textarea>';
			$form .= '<input type="hidden" name="Email" value="'.$user_email.'">';
			$form .= '<input type="submit" value="Оплатить">';
			$form .= '</form>';

			// Письмо клиенту
			
			$message = '<h3>Ваш заказ:</h3>';
			$table = '<table style="border-collapse:collapse;width:100%;">';
			$table .= '<thead><tr><th style="border:1px solid #ccc;padding:5px;">Наименование</th><th style="border:1px solid #ccc;padding:5px;">Цена</th><th style="border:1px solid #ccc;padding:5px;">Колличество</th><th style="border:1px solid #ccc;padding:5px;">Общая стоимость</th></tr></thead><tbody>';
			
			foreach($arr as $k => $position){
				$table .= '<tr>';
				$table .= '<td style="border:1px solid #ccc;padding:5px;">'.$position['product_name'].'</td>';
				$table .= '<td style="border:1px solid #ccc;padding:5px;text-align:center;">'. number_format((float)$position['product_price'],0,'','&nbsp;') .'&nbsp;₽</td>';
				$table .= '<td style="border:1px solid #ccc;padding:5px;text-align:center;">'.$position['product_count'].'&nbsp;шт.</td>';
				$table .= '<td style="border:1px solid #ccc;padding:5px;text-align:center;">'. number_format(((float)$position['product_price'] * (int)$position['product_count']),0,'','&nbsp;') .'&nbsp;₽</td>';
				$table .= '</tr>';
			}
			
			$table .= '<tr><td style="border:1px solid #ccc;padding:5px;" colspan="2">Доставка '.$taxName.'</td><td style="border:1px solid #ccc;padding:5px;text-align:center;">-</td><td style="border:1px solid #ccc;padding:5px;text-align:center;">'. number_format($tax,0,'','&nbsp;') .'&nbsp;₽</td></tr>';
			
			$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Итог</td><td style="border:1px solid #ccc;padding:5px;text-align:center;">'.$paymentlink.'</td><td style="border:1px solid #ccc;padding:5px;text-align:center;"><b>'.$count.'&nbsp;шт.</b></td><td style="border:1px solid #ccc;padding:5px;text-align:center;"><b>'. number_format($out_summ,0,'','&nbsp;') .'&nbsp;₽</b></td></tr>';
			$table .= '</tbody></table>';
			
			$message .= $table;
			$message .= '<h3>Информация для доставки</h3>';

			$table = '<table style="border-collapse:collapse;width:100%;">';
			$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Имя</td><td style="border:1px solid #ccc;padding:5px;">'.$user_name.'</td></tr>';
			$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Фамилия</td><td style="border:1px solid #ccc;padding:5px;">'.$user_firstname.'</td></tr>';
			$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Страна</td><td style="border:1px solid #ccc;padding:5px;">'.$user_country.'</td></tr>';
			$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Город</td><td style="border:1px solid #ccc;padding:5px;">'.$user_city.'</td></tr>';
			$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Адрес</td><td style="border:1px solid #ccc;padding:5px;">'.$user_address.'</td></tr>';
			$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Индекс</td><td style="border:1px solid #ccc;padding:5px;">'.$user_zipcode.'</td></tr>';
			$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Телефон</td><td style="border:1px solid #ccc;padding:5px;">'.$user_phone.'</td></tr>';
			$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Email</td><td style="border:1px solid #ccc;padding:5px;">'.$user_email.'</td></tr>';
			$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Способ доставки</td><td style="border:1px solid #ccc;padding:5px;">'.$taxName.'</td></tr>';
			$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Дополнительная информация</td><td style="border:1px solid #ccc;padding:5px;">'.$user_additional.'</td></tr>';

			$message .= $table;

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
			$mail->addAddress($user_email);
			$mail->isHTML(true); 
			$mail->Subject = 'Вы оформили заказ на сайте ojo-beauty.ru';
			$mail->Body = $message;
			$mail->send();
			
			print '<html><div style="display:none;">'.$form.'</div><script>function submitform(){document.getElementById("payform").submit();}window.onload = submitform;</script></html>';
	
		}
		
	}
	
}

if(isset($_POST["OutSum"]) && isset($_POST["InvId"]) && isset($_POST["Shp_id"]) && isset($_POST["SignatureValue"])){
	
	$mrh_pass2	= "wrv6m7SUwdiRTdS96i3O"; // Боевой
	//$mrh_pass2	= "BLTr83uxL7LOAV1Luh7I"; // Учебный
	$userId 	= $_POST["Shp_id"];
	$out_summ 	= $_POST["OutSum"];
	$inv_id 	= $_POST["InvId"];
	$crc		= strtoupper($_POST["SignatureValue"]);
	$my_crc		= strtoupper(md5("$out_summ:$inv_id:$mrh_pass2:Shp_id=$userId"));
	$userId 	= strip_data($userId);
	$out_summ 	= (int)strip_data($out_summ);
	
	if($my_crc === $crc){
		
		if($userId){
			
			$db = JFactory::getDbo();
			$query = "SELECT * FROM #__orders WHERE order_id={$inv_id} AND order_user_email='{$userId}' AND order_status = 0";
			$db->setQuery($query);
			$result = $db->loadObjectList();
			
			if($result){
				
				$count = 0;

				$query = "UPDATE #__orders SET order_status=1,order_date_done=NOW() WHERE order_id={$inv_id} AND order_user_email='{$userId}'"; // Меняем статус на оплачено
				$db->setQuery($query);
				$result = $db->execute();
				
				$query = "SELECT * FROM #__orders WHERE order_id={$inv_id} AND order_user_email='{$userId}'";
				$db->setQuery($query);
				$result = $db->loadObjectList()[0];
				
				// Письмо клиенту
				
				$message = '';
				$table = '<table style="border-collapse:collapse;width:100%;">';
				$table .= '<thead><tr><th style="border:1px solid #ccc;padding:5px;">Наименование</th><th style="border:1px solid #ccc;padding:5px;">Цена</th><th style="border:1px solid #ccc;padding:5px;">Колличество</th><th style="border:1px solid #ccc;padding:5px;">Общая стоимость</th></tr></thead><tbody>';
				
				$products = json_decode($result->order_products);
				
				foreach($products as $k => $product){
					$table .= '<tr>';
					$table .= '<td style="border:1px solid #ccc;padding:5px;">'. $product->product_name .'</td>';
					$table .= '<td style="border:1px solid #ccc;padding:5px;text-align:center;">'. number_format((float)$product->product_price,0,'','&nbsp;') .'&nbsp;₽</td>';
					$table .= '<td style="border:1px solid #ccc;padding:5px;text-align:center;">'. $product->product_count .'&nbsp;шт.</td>';
					$table .= '<td style="border:1px solid #ccc;padding:5px;text-align:center;">'. number_format(((float)$product->product_price * (int)$product->product_count),0,'','&nbsp;') .'&nbsp;₽</td>';
					$table .= '</tr>';
					$count = $count + (int)$product->product_count;
				}
				
				switch ($result->order_user_shipment_id){
					case 1:$taxName = "В пункт выдачи заказов СДЭК"; break;
					case 2:$taxName = "Почта России (обычное отправление)"; break;
					case 3:$taxName = "Почта России (1 класс)"; break;
					default:$taxName = "Курьером по Краснодару";
				}
				
				if($result->order_user_shipment_id !=0 && $price < 3000){
					
					$tax = 300;
					$price = $price + $tax;
					
				}else{
					
					if($result->order_user_shipment_id == 0 && $price < 1000){
						
						$tax = 300;
						$price = $price + $tax;
						
					}else{
						
						$tax = 0;
						
					}
					
				}
				
				$table .= '<tr><td style="border:1px solid #ccc;padding:5px;" colspan="2">Доставка '.$taxName.'</td><td style="border:1px solid #ccc;padding:5px;text-align:center;">-</td><td style="border:1px solid #ccc;padding:5px;text-align:center;">'. number_format($tax,0,'','&nbsp;') .'&nbsp;₽</td></tr>';
				
				$table .= '<tr><td style="border:1px solid #ccc;padding:5px;" colspan="2">Итог</td><td style="border:1px solid #ccc;padding:5px;text-align:center;"><b>'.$count.'&nbsp;шт.</b></td><td style="border:1px solid #ccc;padding:5px;text-align:center;"><b>'. number_format($result->order_summ,0,'','&nbsp;') .'&nbsp;₽</b></td></tr>';
				$table .= '</tbody></table>';
				
				$message .= $table;
				$message .= '<h3>Информация для доставки</h3>';

				$table = '<table style="border-collapse:collapse;width:100%;">';
				$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Имя</td><td style="border:1px solid #ccc;padding:5px;">'. $result->order_user_name .'</td></tr>';
				$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Фамилия</td><td style="border:1px solid #ccc;padding:5px;">'. $result->order_user_firstname .'</td></tr>';
				$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Страна</td><td style="border:1px solid #ccc;padding:5px;">'. $result->order_user_country .'</td></tr>';
				$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Город</td><td style="border:1px solid #ccc;padding:5px;">'. $result->order_user_city .'</td></tr>';
				$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Адрес</td><td style="border:1px solid #ccc;padding:5px;">'. $result->order_user_address .'</td></tr>';
				$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Индекс</td><td style="border:1px solid #ccc;padding:5px;">'. $result->order_user_zipcode .'</td></tr>';
				$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Телефон</td><td style="border:1px solid #ccc;padding:5px;">'. $result->order_user_phone .'</td></tr>';
				$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Email</td><td style="border:1px solid #ccc;padding:5px;">'. $result->order_user_email .'</td></tr>';
				$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Способ доставки</td><td style="border:1px solid #ccc;padding:5px;">'.$taxName.'</td></tr>';
				$table .= '<tr><td style="border:1px solid #ccc;padding:5px;">Дополнительная информация</td><td style="border:1px solid #ccc;padding:5px;">'. $result->order_user_additional .'</td></tr>';

				$message .= $table;
				
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
				$mail->addAddress($userId);
				$mail->isHTML(true); 
				$mail->Subject = 'Вы оплатили заказ на сайте ojo-beauty.ru';
				$mail->Body = '<h3>Ваш заказ успешно оплачен!</h3>'.$message;
				$mail->send();
				
				
				// Письмо менеджеру
				
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
				$mail->Subject = 'Оплачен заказ на сайте ojo-beauty.ru';
				$mail->Body = '<h3>Оплачен заказ: </h3>'.$message;
				$mail->send();
				
				print 'OK'.$inv_id;
				
			}

		}
		
	}

}

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


?>