<?php

defined('_JEXEC') or die;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

//define(MRH_PASS_1,'ijgUs3fQ4Y62aejvwT6z'); // Боевой
define(MRH_PASS_1,'r7xOuetsOn8ratYq70h1'); // Учебный

//define(MRH_PASS_2,'wrv6m7SUwdiRTdS96i3O'); // Боевой
define(MRH_PASS_2,'BLTr83uxL7LOAV1Luh7I'); // Учебный

define(MRH_LOGIN,'ojobeauty');
define(CULTURE,'ru');
define(IS_TEST,true);
define(SNO,'osn');

// ЧЕКИ
//osn – общая СН;
//usn_income – упрощенная СН (доходы);
//usn_income_outcome – упрощенная СН (доходы минус расходы);
//envd – единый налог на вмененный доход;

$uri 		= &JUri::getInstance(); 
$user 		= &JFactory::getUser();
$db 		= &JFactory::getDbo();
$input		= &JFactory::getApplication()->input;
$order		= new stdClass();
$totalPrice = 0;
$tax 		= 0;
$form 		= false;

$getOrder 				= $input->get('k','','STRING');
$order->order_products	= $input->get('order','','ARRAY');
$order->order_payment	= $input->get('payment','','STRING');
$order->order_delivery	= $input->get('delivery','','STRING');
$order->order_username 	= $input->get('name','','STRING');
$order->order_address 	= $input->get('address','','STRING');
$order->order_phone 	= strip_data($input->get('phone','','STRING'));
$order->order_zip 		= $input->get('zip','','STRING');
$order->order_status 	= 0;

if(!empty($order->order_products)){
	
	foreach($order->order_products as $k => &$val){
		$arr[] = (int)preg_replace('/[^0-9]/','',strip_data($k));
	}
	
	$query = $db->getQuery(true);
	$query->select(array('a.id', 'a.title', 'a.catid', 'a.language','a.images','b.params','c.value AS price'));
	$query->from($db->quoteName('#__content', 'a'));
	$query->join('LEFT', $db->quoteName('#__categories', 'b') . ' ON ' . $db->quoteName('a.catid') . ' = ' . $db->quoteName('b.id'));
	$query->join('LEFT', $db->quoteName('#__fields_values', 'c') . ' ON ' . $db->quoteName('a.id') . ' = ' . $db->quoteName('c.item_id'));
	$query->where($db->quoteName('a.state') . ' = 1 AND '. $db->quoteName('a.id').' IN('.implode(',',$arr).') AND '. $db->quoteName('c.field_id').' = 15');
	$query->order($db->quoteName('a.id') . ' ASC');
	$db->setQuery($query);
	$results = $db->loadObjectList();

	if(!empty($results)){
		
		foreach($results as $n => &$products){
			
			$results[$n]->images = json_decode($products->images)->image_intro; 
			$results[$n]->params = explode(':',json_decode($products->params)->category_layout)[1]; 
			$results[$n]->route = JRoute::_(ContentHelperRoute::getArticleRoute($products->id, $products->catid, $products->language));

			foreach(FieldsHelper::getFields('com_content.article',$products) as &$field){
				if($field->name == 'price'){
					$results[$n]->price = (int)$field->value;
				}
				if($field->name == 'artikul'){
					$results[$n]->artikul = (string)$field->value;
				}
			}
			
			foreach($order->order_products as $k => &$val){
				if($k == $products->id){
					if((int)preg_replace('/[^0-9]/','',$val['count']) == 0){
						$results[$n]->count = 1;
					}else{
						$results[$n]->count = (int)preg_replace('/[^0-9]/','',strip_data($val['count']));
					}
				}
			}

			$totalPrice = ($results[$n]->price * $results[$n]->count) + $totalPrice;
			
		}
		
		switch($order->order_delivery){
			case 'Курьером'	:	$tax = 500;break;
			case 'Почтой'	:	$tax = 500;break;
			case 'Самовывоз':	$tax = 0;break;
			default:$tax = 0;
		}
		
		$order->order_products = json_encode($results,JSON_UNESCAPED_UNICODE);
		$order->order_tax = $tax;
		$order->order_total = $totalPrice + $tax;
		$order->order_pass 	= gen_password();
		$order->order_create_date = date('Y-m-d');
		$input->set('order','');
		unset($_SESSION['cart']);
		
		if($user->id !== 0){
			$field = new stdClass();
			$field->field_id = 109;
			$field->item_id = $user->id;
			$field->value = 'null';
			JFactory::getDbo()->updateObject('#__fields_values',$field,array('field_id','item_id'),true); 
		}
		
		if($order->payment == 'Онлайн'){
			$order->order_status = 1;
		}
		
		$result = $db->insertObject('#__orders',$order);
		
		if($order->order_payment == 'Онлайн'){
			
			$query = $db->getQuery(true);
			$query->select(array('*'));
			$query->from($db->quoteName('#__orders'));
			$query->where($db->quoteName('order_pass') . ' = '. $db->quote(strip_data($order->order_pass)) .' AND '. $db->quoteName('order_phone').' = '. $db->quote(strip_data($order->order_username)));
			$db->setQuery($query);
			$results = $db->loadObject();

			$cheque = array();
			$cheque['sno'] = SNO;
			$mrh_login = MRH_LOGIN;
			$mrh_pass1 = MRH_PASS_1;
			$shp_id = $results->order_username;
			$inv_id = $results->order_id;
			$products = json_decode($results->order_products);
			$out_summ = $results->order_total;
			
			foreach($products as $k => &$product){
				$cheque['items'][$k]['name'] = $product->title;
				$cheque['items'][$k]['quantity'] = $product->count;
				$cheque['items'][$k]['sum'] = (float)$product->price * (int)$product->count;
				$cheque['items'][$k]['payment_method'] = 'full_payment';
				$cheque['items'][$k]['payment_object'] = 'commodity';
				$cheque['items'][$k]['tax'] = 'none';
			}
			
			if($results->order_delivery != 'Самовывоз'){
				$p['name'] = 'Доставка';
				$p['quantity'] = '1';
				$p['sum'] = $results->order_tax;
				$p['payment_method'] = 'full_payment';
				$p['payment_object'] = 'service';
				$p['tax'] = 'none';
				array_push($cheque['items'],$p);
			}
			
			$receipt = urlencode(json_encode($cheque, JSON_UNESCAPED_UNICODE));
			$crc  = md5("$mrh_login:$out_summ:$inv_id:$receipt:$mrh_pass1:Shp_id=$shp_id");

			$form  = '<form id="payform" action="https://auth.robokassa.ru/Merchant/Index.aspx" method="POST">';
			$form .= '<input type="hidden" name="MerchantLogin" value="'.$mrh_login.'">';
			$form .= '<input type="hidden" name="OutSum" value="'.$out_summ.'">';
			$form .= '<input type="hidden" name="InvId" value="'.$inv_id.'">';
			$form .= '<input type="hidden" name="Description" value="Оплата заказа">';
			$form .= '<input type="hidden" name="SignatureValue" value="'.$crc.'">';
			$form .= '<input type="hidden" name="Shp_id" value="'.$shp_id.'">';
			$form .= '<input type="hidden" name="IncCurrLabel" value="">';
			$form .= '<input type="hidden" name="Culture" value="'. CULTURE .'">';
			if(IS_TEST){
				$form .= '<input type="hidden" name="isTest" value="1">';
			}
			$form .= '<textarea type="hidden" name="Receipt">'.$receipt.'</textarea>';
			$form .= '<input type="hidden" name="Email" value="">';
			$form .= '</form>';
			
			echo '<html><div style="display:none;">'.$form.'</div><script>function submitform(){document.getElementById("payform").submit();}window.onload = submitform;</script></html>';
			
		}else{
			header('Location: /order/?k='.base64_encode($order->order_phone .' (ಠ_ಠ) '. $order->order_pass));
		}
		
	}else{
		unset($_SESSION['cart']);
		header('Location: /');
		exit();
	}
	
}else{
	
	if(empty($getOrder)){
		
		if(isset($_GET["Payed"]) && isset($_POST["OutSum"]) && isset($_POST["InvId"]) && isset($_POST["Shp_id"]) && isset($_POST["SignatureValue"])){
			
			$mrh_pass2	= MRH_PASS_2;
			$Shp_id 	= $_POST["Shp_id"];
			$out_summ 	= $_POST["OutSum"];
			$inv_id 	= $_POST["InvId"];
			$crc		= strtoupper($_POST["SignatureValue"]);
			$my_crc		= strtoupper(md5("$out_summ:$inv_id:$mrh_pass2:Shp_id=$userId"));
			$Shp_id 	= strip_data($Shp_id);
			
			if($my_crc === $crc){
				
				$query = $db->getQuery(true);
				$query->select(array('*'));
				$query->from($db->quoteName('#__orders'));
				$query->where($db->quoteName('order_id') . ' = '. $db->quote(strip_data($inv_id)) .' AND '. $db->quoteName('order_phone').' = '. $db->quote(strip_data($Shp_id)).' AND order_status = 0');
				$db->setQuery($query);
				$order = $db->loadObject();
				$order->order_status = 7;
				$order->order_payed_date = date('Y-m-d');
				$result = $db->updateObject('#__orders',$order,'order_id',true);
				header('Location: /order/?k='.base64_encode($order->order_phone .' (ಠ_ಠ) '. $order->order_pass));
				
			}else{
				unset($_SESSION['cart']);
				header('Location: /');
				exit();
			}
			
		}else{
			unset($_SESSION['cart']);
			header('Location: /');
			exit();
		}
		
	}else{
		
		require_once JPATH_BASE . '/libraries/php-qrcode/qrlib.php';
		
		$data = base64_decode($getOrder);
		$phone = explode('(',$data)[0];
		$pass = explode(')',$data)[1];
		
		if(!empty(strip_data($pass)) && !empty(strip_data($phone))){
			$query = $db->getQuery(true);
			$query->select(array('*'));
			$query->from($db->quoteName('#__orders'));
			$query->where($db->quoteName('order_pass') . ' = '. $db->quote(strip_data($pass)) .' AND '. $db->quoteName('order_phone').' = '. $db->quote(strip_data($phone)));
			$db->setQuery($query);
			$results = $db->loadObject();
		}
		
		switch($results->order_status){
			case 0:$status = 'В обработке';break;
			case 1:$status = 'Ожидает оплаты';break;
			case 2:$status = 'Ожидает в пункте выдачи';break;
			case 3:$status = 'Отправлен получателю';break;
			case 4:$status = 'Доставлен';break;
			case 5:$status = 'Отменен';break;
			case 6:$status = 'Отменен получателем';break;
			case 7:$status = 'Оплачен';break;
			default:$status = 'В обработке';
		}
		
		if($results->order_payment == 'Онлайн' && $results->order_status == 1){
			
			$cheque = array();
			$cheque['sno'] = SNO;
			$mrh_login = MRH_LOGIN;
			$mrh_pass1 = MRH_PASS_1;
			$shp_id = $results->order_username;
			$inv_id = $results->order_id;
			$products = json_decode($results->order_products);
			$out_summ = $results->order_total;
			
			foreach($products as $k => &$product){
				$cheque['items'][$k]['name'] = $product->title;
				$cheque['items'][$k]['quantity'] = $product->count;
				$cheque['items'][$k]['sum'] = (float)$product->price * (int)$product->count;
				$cheque['items'][$k]['payment_method'] = 'full_payment';
				$cheque['items'][$k]['payment_object'] = 'commodity';
				$cheque['items'][$k]['tax'] = 'none';
			}
			
			if($results->order_delivery != 'Самовывоз'){
				$p['name'] = 'Доставка';
				$p['quantity'] = '1';
				$p['sum'] = $results->order_tax;
				$p['payment_method'] = 'full_payment';
				$p['payment_object'] = 'service';
				$p['tax'] = 'none';
				array_push($cheque['items'],$p);
			}
			
			$receipt = urlencode(json_encode($cheque, JSON_UNESCAPED_UNICODE));
			$crc  = md5("$mrh_login:$out_summ:$inv_id:$receipt:$mrh_pass1:Shp_id=$shp_id");

			$form  = '<form id="payform" action="https://auth.robokassa.ru/Merchant/Index.aspx" method="POST">';
			$form .= '<input type="hidden" name="MerchantLogin" value="'.$mrh_login.'">';
			$form .= '<input type="hidden" name="OutSum" value="'.$out_summ.'">';
			$form .= '<input type="hidden" name="InvId" value="'.$inv_id.'">';
			$form .= '<input type="hidden" name="Description" value="Оплата заказа">';
			$form .= '<input type="hidden" name="SignatureValue" value="'.$crc.'">';
			$form .= '<input type="hidden" name="Shp_id" value="'.$shp_id.'">';
			$form .= '<input type="hidden" name="IncCurrLabel" value="">';
			$form .= '<input type="hidden" name="Culture" value="'. CULTURE .'">';
			if(IS_TEST){
				$form .= '<input type="hidden" name="isTest" value="1">';
			}
			$form .= '<textarea type="hidden" name="Receipt">'.$receipt.'</textarea>';
			$form .= '<input type="hidden" name="Email" value="">';
			$form .= '<button type="submit">Оплатить</button>';
			$form .= '</form>';
	
		}
		
	}
	
}

function strip_data($text){
	$quotes = array("\x27", "\x22", "\x60", "\t", "\n", "\r", "*", "%", "<", ">", "?", "!",")","(","-","+"," ","'");
	$goodquotes = array('-', '#','"');
	$repquotes = array("\-", "\#","&quot;");
	$text = htmlspecialchars($text);
	$text = stripslashes($text);
	$text = trim(strip_tags($text));
	$text = str_replace($quotes,'',$text);
	$text = str_replace($repquotes,$goodquotes,$text);
	return trim($text);
}

function gen_password($length = 6){
	
	$password = '';
	
	$arr = array(
		'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 
		'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 
		'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 
		'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 
		'1', '2', '3', '4', '5', '6', '7', '8', '9', '0'
	);
 
	for ($i = 0; $i < $length; $i++) {
		$password .= $arr[random_int(0, count($arr) - 1)];
	}
	
	return $password;
	
}

function phone_format($phone){
	
	$phone = substr($phone,0,20);
	$phone = trim($phone);
	
	$res = preg_replace(
		array(
			'/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{3})[-|\s]?\)[-|\s]?(\d{3})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
			'/[\+]?([7|8])[-|\s]?(\d{3})[-|\s]?(\d{3})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
			'/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{4})[-|\s]?\)[-|\s]?(\d{2})[-|\s]?(\d{2})[-|\s]?(\d{2})/',
			'/[\+]?([7|8])[-|\s]?(\d{4})[-|\s]?(\d{2})[-|\s]?(\d{2})[-|\s]?(\d{2})/',	
			'/[\+]?([7|8])[-|\s]?\([-|\s]?(\d{4})[-|\s]?\)[-|\s]?(\d{3})[-|\s]?(\d{3})/',
			'/[\+]?([7|8])[-|\s]?(\d{4})[-|\s]?(\d{3})[-|\s]?(\d{3})/',					
		), 
		array(
			'+7($2)$3-$4-$5', 
			'+7($2)$3-$4-$5', 
			'+7($2)$3-$4-$5', 
			'+7($2)$3-$4-$5', 	
			'+7($2)$3-$4',
			'+7($2)$3-$4', 
		),
		$phone
	);
	
	return $res;
	
}

?>
<?php if(!empty($getOrder)):?>
<?php $order_products = json_decode($results->order_products);?>
<div class="order">
	<h1>Заказ №<?php echo $results->order_id;?> (<?php echo mb_strtolower($status);?>) <?php echo ($form) ? $form : '';?></h1>
	<?php if($results->order_delivery !== 'Самовывоз'):?>
	<?php endif;?>
	<table>
		<tr>
			<th>Дата создания</th><th>Дата оплаты</th><th>Дата отправки</th><th>Дата вручения</th><th>Номер отслеживания</th>
		</tr>
		<tr>
			<td><?php echo date('d.m.Y', strtotime($results->order_create_date));?></td>
			<?php if(!empty($results->order_payed_date)):?>
				<td><?php echo date('d.m.Y', strtotime($results->order_payed_date));?></td>
			<?php else:?>
				<td>—</td>
			<?php endif;?>
			<?php if(!empty($results->order_send_date)):?>
				<td><?php echo date('d.m.Y', strtotime($results->order_send_date));?></td>
			<?php else:?>
				<td>—</td>
			<?php endif;?>
			<?php if(!empty($results->order_delivery_date)):?>
				<td><?php echo date('d.m.Y', strtotime($results->order_delivery_date));?></td>
			<?php else:?>
				<td>—</td>
			<?php endif;?>
			<?php if(!empty($results->order_track_id)):?>
				<td><?php echo $results->order_track_id;?></td>
			<?php else:?>
				<td>—</td>
			<?php endif;?>
		</tr>
	</table>
	<table class="t_left">
		<tr>
			<td>Получатель:</td>
			<td><?php echo $results->order_username;?></td>
			<td style="text-align:center;background-color: #ffffff;" rowspan="4">
				<?php QRcode::svg($uri->toString(array('scheme','host','path')).'?k='.$getOrder);?>
				<i style="clear:both;display:block;">Открыть на мобильном</i>
			</td>
		</tr>
		<tr>
			<td>Контактный телефон:</td><td><?php echo phone_format($results->order_phone);?></td>
		</tr>
		<tr>
			<td>Адрес доставки:</td><td><?php echo $results->order_address;?></td>
		</tr>
		<tr>
			<td>Индекс:</td><td><?php echo $results->order_zip;?></td>
		</tr>
	</table>
	<table>
		<tr>
			<th colspan="2" style="text-align:left;">Наименование</th>
			<th>Артикул</th>
			<th>Количество</th>
			<th style="text-align:right;">Цена</th>
			<th style="text-align:right;">Итог</th>
		</tr>
		<?php foreach($order_products as &$product):?>
		<tr>
			<td><?php echo '<a href="'.$product->route .'"><img src="'. $product->images .'" alt="'. $product->title .'"></a>';?></td>
			<td style="text-align:left;"><?php echo '<a href="'. $product->route .'" title="'. $product->title .'">'.$product->title .'</a>';?></td>
			<td><?php echo '<p><b>'.$product->artikul .'</b></p>';?></td>
			<td><?php echo '<p><b>'.$product->count .'</b></p>';?></td>
			<td style="text-align:right;"><?php echo '<p class="product_price"><b>'. number_format($product->price,0,'',' ') .' руб.</b></p>';?></td>
			<td style="text-align:right;"><?php echo '<p class="total_price"><b>'. number_format($product->price * $product->count,0,'',' ') .' руб.</b></p>';?></td>
		</tr>
		<?php endforeach;?>
		<tr>
			<td style="text-align:left;background-color: #ffffff;"><p>Способ получения:</p></td>
			<td colspan="3" style="text-align:left;background-color: #ffffff;"><p><b><?php echo $results->order_delivery;?></b></p></td>
			<?php if($results->order_delivery !== 'Самовывоз'):?>
			<td style="text-align:right;background-color: #ffffff;"><p><b><?php echo $results->order_tax;?> руб.</b></p></td>
			<td style="text-align:right;background-color: #ffffff;"><p><b><?php echo $results->order_tax;?> руб.</b></p></td>
			<?php else:?>
			<td style="text-align:right;background-color: #ffffff;">—</td>
			<td style="text-align:right;background-color: #ffffff;">—</td>
			<?php endif;?>
		</tr>
		<tr>
			<td style="text-align:left;background-color: #ffffff;"><p>Способ оплаты:</p></td>
			<td colspan="3" style="text-align:left;background-color: #ffffff;"><p><b><?php echo $results->order_payment;?></b></p></td>
			<td style="text-align:right;background-color: #ffffff;">—</td>
			<td style="text-align:right;background-color: #ffffff;">—</td>
		</tr>
		<tr>
			<td colspan="5" style="text-align:right;background-color: #ffffff;">Итог:</td>
			<td style="text-align:right;background-color: #ffffff;"><p><b><?php echo number_format($results->order_total,0,'',' ');?> руб.</b></p></td>
		</tr>
	</table>
</div>
<?php endif;?>