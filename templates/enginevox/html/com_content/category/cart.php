<?php
defined('_JEXEC') or die;
use Joomla\Module\ArticlesJkreview\Site\Helper\ArticlesJkreviewHelper;
use Joomla\Component\Content\Site\Model\ArticleModel;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

$user = &JFactory::getUser();
$article = new ArticleModel;
$count = 0;
$total = 0;

if(is_null($_SESSION['favorites'])){
	$arrFavorites = array();
}else{
	$arrFavorites  = $_SESSION['favorites'];
}

if(isset($_POST['update']) && !empty($_POST['update']) && $_POST['update'] !== NULL && is_string($_POST['update'])){
	
	$data = json_decode($_POST['update']);
	
	if(json_last_error() === 0){

		$db = &JFactory::getDbo();
		$data->count = strip_data($data->count);
		$data->id =	strip_data($data->id);
		
		if($data->count !== 'remove'){
			
			$query = $db->getQuery(true);
			$query->select(array('b.params'));
			$query->from($db->quoteName('#__content','a'));
			$query->join('LEFT', $db->quoteName('#__categories','b') .' ON '. $db->quoteName('a.catid') .' = '. $db->quoteName('b.id'));
			$query->where($db->quoteName('a.state') . ' = 1 AND '. $db->quoteName('a.id').' = '. $db->quote($data->id));
			$db->setQuery($query);
			$results = $db->loadObject();

			if($results !== NULL){
				
				if(isset(json_decode($results->params)->category_layout)){
					$layout = json_decode($results->params)->category_layout;
					if(in_array('produkts',explode(':',$layout))){
						$sess = $_SESSION['cart'];				
						$sess[$data->id]['id'] = $data->id;
						$sess[$data->id]['count'] = $data->count;
						$_SESSION['cart'] = $sess;
					}
				}
				
			}
			
		}else{
			$sess = $_SESSION['cart'];
			unset($sess[$data->id]);
			if(!empty($sess)){
				$_SESSION['cart'] = $sess;
			}else{
				unset($_SESSION['cart']);
			}
		}
		
		if($user->id !== 0){
			$field = new stdClass();
			$field->field_id = 109;
			$field->item_id = $user->id;
			$field->value = json_encode($_SESSION['cart']);
			$db->updateObject('#__fields_values',$field,array('field_id','item_id'),true); 
		}
		
	}

}

$arr = array();
$arrData = $_SESSION['cart'];

if(isset($arrData) && is_array($arrData) && count($arrData)){
	
	foreach($arrData as $k => &$data){
		$item = &$article->getItem((int)strip_data($data['id']));
		foreach(FieldsHelper::getFields('com_content.article',$item) as &$field) {
			$item->jcfields[$field->id] = $field;
		}
		$item->count = $data['count'];
		$arr[$k] = $item;
	}
	
}else{
	$arr = false; 
}

function strip_data($text){
	if($text !== 'remove'){
		$quotes = array("\x27", "\x22", "\x60", "\t", "\n", "\r", "*", "%", "<", ">", "?", "!");
		$goodquotes = array('-', '+', '#','"');
		$repquotes = array("\-", "\+", "\#","&quot;");
		$text = mb_substr($text,0,5);
		$text = htmlspecialchars($text);
		$text = stripslashes($text);
		$text = trim(strip_tags($text));
		$text = str_replace($quotes,'',$text);
		$text = str_replace($repquotes,$goodquotes,$text);
		$int = (int)preg_replace('/[^0-9]/','',$text);
		if ($int <= 0) $int = 1;
		if ($int > 9999) $int = 9999;
		return $int;
	}else{
		return 'remove';
	}
}

?>
<div class="blog cart<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="https://schema.org/Blog">
	<?php if(!empty($arr)):?>
	<form action="/order/" method="post" >
		<div class="left">
			<h1 class="hr"><?php echo $this->category->title;?></h1>
			<ul class="audio">
			<?php foreach($arr as $i => &$item):?>
				<li class="" itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
					<a href="<?php echo $link = JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->language)); ?>" class="img" style="background-image:url(<?php echo json_decode($item->images)->image_intro;?>)"></a>
					<div class="more">
						<a class="link" itemprop="name" href="<?php echo $link; ?>"><?php echo $item->title; ?></a>
						<p class="price"><i>Цена:</i><?php echo number_format($item->jcfields[15]->value,0,'',' ');?> руб.</p>
						<button type="button" class="addFavorites <?php echo ($isFavorite = in_array($item->id,$arrFavorites)) ? 'remove active' : ''?>"  data-id="<?php echo $item->id;?>">
							<span><?php echo ($isFavorite) ? 'В избранном' : 'В избранное';?></span>
						</button>
						<button class="cartButton remove" type="button" data-id="<?php echo $item->id;?>"><span>Удалить</span></button>
						<?php if($data = ArticlesJkreviewHelper::getTotalByItemId($item->id)):?>
						<div class="rating">
							<?php if($data->count !== 0):?>
							<input type="hidden" name="val" value="<?php echo (int)$data->sum;?>" tabindex="0">
							<i class="counter"><?php echo $data->count;?></i>
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
	<div class="noResult"><div class="cnt"><img src="/templates/enginevox/img/logo-min.svg" alt="Electrohype"><p><?php echo 'Вы не добавили товары в корзину';?></p><a href="/" class="goCatalog">Перейти в каталог</a></div></div>
	<?php endif; ?>
</div>