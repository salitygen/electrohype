<?php
defined('_JEXEC') or die;
JLoader::register('JKHelper', JPATH_BASE . '/components/com_jkreview/helpers/jkhelper.php');
//JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
use \Joomla\Component\Content\Site\Model\ArticleModel;
use \Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

$session = JFactory::getSession();
$article = new ArticleModel;
$count = 0;
$total = 0;
$option = 0;

$arrData = $session->get('favorites');
$fav = array();
foreach($arrData as $data){
	$fav[$data['id']] = $data['id'];
}

if(isset($_COOKIE['tax']) && $_COOKIE['tax'] != ''){
	$option = (int)$_COOKIE['tax'];
}

if(isset($_GET['check'])){
	$sess = $session->get('cart');
	if(!empty($sess)){
		foreach($sess as $s){
			$count = $count + (int)strip_data($s['count']);
		}
		echo $count;
	}else{
		echo 0;
	}
	die();
}

if(isset($_POST['update']) && $_POST['update'] !=''){
	
	$json = strip_data($_POST['update']);
	$arr = json_decode($json,true);
	
	if(is_array($arr[0]) && count($arr[0])){
		if(!empty($session->get('cart'))){
			if(strip_data($arr[0]['value']) !== 'remove'){
				$sess = $session->get('cart');
				if((int)preg_replace('/[^0-9]/','',strip_data($arr[0]['value'])) > 0 
				&& is_numeric((int)preg_replace('/[^0-9]/','',strip_data($arr[0]['value'])))){
					$sess[strip_data($arr[0]['id'])]['count'] = (int)preg_replace('/[^0-9]/','',strip_data($arr[0]['value']));
				}else{
					$sess[strip_data($arr[0]['id'])]['count'] = 1;
				}
				$session->set('cart',$sess);
			}else{
				$sess = $session->get('cart');
				unset($sess[strip_data($arr[0]['id'])]);
				if(!empty($sess)){
					$session->set('cart',$sess);
				}else{
					$session->clear('cart');
				}
			}	
		}
	}
}

$arr = array();
$arrData = $session->get('cart');

if(isset($arrData) && is_array($arrData) && count($arrData)){
	
	foreach($arrData as $k => $data){
		$item = $article->getItem((int)strip_data($data['id']));
		foreach(FieldsHelper::getFields('com_content.article',$item,true) as $field) {
			$item->jcfields[$field->id] = $field;
		}
		$item->count = $data['count'];
		$arr[$k] = $item;
	}
	
}else{
	$arr = false; 
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
<div id="ajaxUpdate" class="wt">
	<div class="blog cart<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="https://schema.org/Blog">
		<?php if(!empty($arr)):?>
		<form action="/order/" method="post" >
			<div class="left">
				<h1 class="hr"><?php echo $this->category->title;?></h1>
				<ul class="audio">
				<?php foreach ($arr as $i => $item):?>
					<li class="" itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
						<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->language)); ?>" class="img" style="background-image:url(<?php echo json_decode($item->images)->image_intro;?>)"></a>
						<div class="more">
							<a class="link" itemprop="name" href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->language)); ?>"><?php echo $item->title; ?></a>
							<p class="price"><i>Цена:</i><?php echo number_format($item->jcfields[15]->value,0,'',' ');?> руб.</p>
							<button class="addFavorites <?php echo (isset($fav[$item->id])) ? 'remove active' : '';?>" type="button" data-id="<?php echo $item->id;?>"><span><?php echo (isset($fav[$item->id])) ? 'В избранном' : 'В избранное';?></span></button>
							<button class="cartButton remove" type="button" data-id="<?php echo $item->id;?>"><span>Удалить</span></button>
							<?php if($data = JKHelper::getTotalByItemId($item->id)):?>
							<div class="rating">
								<?php if($data->count !== 0):?>
								<input type="hidden" name="val" value="<?php print (int)$data->sum;?>" tabindex="0">
								<i class="counter"><?php print $data->count;?></i>
								<?php else: ?>
								<i class="counter emty">Нет отзывов</i>
								<input type="hidden" name="val" value="0" tabindex="0">
								<?php endif; ?>
							</div>
							<?php endif; ?>
						</div>
						<p class="articul"><b>Артикул:</b><i><?php echo $item->jcfields[38]->value;?></i></p>
						<p class="countPrice"><?php echo number_format($item->jcfields[15]->value * $item->count,0,'',' ');?> руб.</p>
						<div class="calculate" data-id="<?php echo $item->id;?>">
							<button type="button" class="minus">&nbsp;</button>
							<input required="required" type="number" name="order[<?php echo $i;?>][count]" value="<?php echo $item->count;?>" autocomplete="off" readonly >
							<button type="button" class="plus">&nbsp;</button>
							<input type="hidden" name="order[<?php echo $i;?>][car]" value="<?php echo $item->title; ?>">
							<input type="hidden" name="order[<?php echo $i;?>][price]" value="<?php echo $item->jcfields[15]->value;?>">
						</div>
					</li>
					<?php $total = $total + ((int)$item->jcfields[15]->value * (int)$item->count);?>
				<?php endforeach; ?>
				</ul>
			</div>
			<div class="right">
				<div class="total">
					<p class="totalOrder"><i>Итого:</i>&nbsp;<b><?php echo number_format($total,0,'',' ');?></b> руб.</p>
				</div>
				<div class="order">
					<div class="shipment">
						<h3>Выберите способ получения:</h3>
						<p>Укажите предпочитаемый способ получения заказа и нажмите «далее»</p>
						<label><input type="radio" name="delivery" value="Курьером" checked="checked"><span></span><i>Доставка курьером (+500 руб.)</i></label>
						<label><input type="radio" name="delivery" value="Почтой"><span></span><i>Доставка почтой (+500 руб.)</i></label>
						<label><input type="radio" name="delivery" value="Самовывоз"><span></span><i>Самовывоз</i></label>
						<div class="buttonsOrder">
							<button type="button" class="nextOrderStep">Далее</button>
						</div>
					</div>
					<div class="delivery">
						<h3>Укажите информацию для доставки:</h3>
						<div class="rbl">
							<label>Адрес для доставки:</label>
							<input type="text" name="address" placeholder="г.Москва, ул.Пушкина, дом 96, корпус А, кв. 123" required="required" value="">
						</div>
						<div class="rbl">
							<div class="w50">
								<label>Контактный телефон:</label>
								<input type="tel" name="phone" placeholder="+7(___)___-__-__" required="required" value="">
							</div>
							<div class="w50 zip">
								<label>Индекс:</label>
								<input type="text" name="zip" placeholder="352700" required="required" value="">
							</div>
						</div>
						<div class="rbl">
							<label>Получатель:</label>
							<input type="text" name="name" placeholder="Иванов Иван Иваныч" required="required" value="">
						</div>
						<div class="buttonsOrder">
							<button type="button" class="prevOrderStep">Назад</button>
							<button type="button" class="nextOrderStep">Далее</button>
						</div>
					</div>
					<div class="payment">
						<h3>Выберите способ оплаты:</h3>
						<p>Укажите предпочитаемый способ оплаты заказа и нажмите «оформить заказ»</p>
						<label><input type="radio" name="payment" value="Онлайн" checked="checked"><span></span><i>Оплатить онлайн</i></label>
						<label><input type="radio" name="payment" value="Наличными"><span></span><i>Наличными при получении</i></label>
						<div class="buttonsOrder">
							<button type="button" class="prevOrderStep">Назад</button>
							<button type="submit" class="submitOrder">Оформить заказ</button>
						</div>
					</div>
				</div>
			</div>
		</form>
		<?php else:?>
		<h1 class="hr"><?php echo $this->category->title;?></h1>
		<div class="noResult"><div class="cnt"><img src="/templates/enginevox/img/logo-min.svg" alt="Electrohype"><p><?php print 'Вы не добавили товары в корзину';?></p><a href="/" class="goCatalog">Перейти в каталог</a></div></div>
		<?php endif; ?>
	</div>
</div>