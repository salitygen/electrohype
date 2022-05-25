<?php
defined('_JEXEC') or die;
use Joomla\Module\ArticlesJkreview\Site\Helper\ArticlesJkreviewHelper;
use Joomla\Component\Content\Site\Model\ArticleModel;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use Joomla\CMS\Helper\TagsHelper;

$user = &JFactory::getUser();
$article = new ArticleModel;
$tagsHelper = new TagsHelper;
$count = 0;
$total = 0;
$option = 0;

if(is_null($_SESSION['favorites'])){
	$arrFavorites = array();
}else{
	$arrFavorites  = $_SESSION['favorites'];
}

if(isset($_GET['check'])){
	$sess = $_SESSION['compare'];
	if(!empty($sess)){
		foreach($sess as $s){
			$count = $count + strip_data($s['count']);
		}
		echo $count;
	}else{
		echo 0;
	}
	exit();
}

if(isset($_POST['remove']) && !empty($_POST['remove']) && $_POST['remove'] !== NULL && is_string($_POST['remove'])){
	
	$data = json_decode($_POST['remove']);
	
	if(json_last_error() === 0){
		
		$db = &JFactory::getDbo();
		$data->id =	strip_data($data->id);
		
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
					
					$sess = array_values($_SESSION['compare']);
					
					if(array_search($data->id,$sess) !== false){
						unset($sess[array_search($data->id,$sess)]);
					}
					
					if(!empty($sess)){
						$sess = array_values($sess);
						$_SESSION['compare'] = $sess;
					}else{
						unset($_SESSION['compare']);
					}
					
					if($user->id !== 0){
						$field = new stdClass();
						$field->field_id = 111;
						$field->item_id = $user->id;
						$field->value = json_encode($sess);
						$db->updateObject('#__fields_values',$field,array('field_id','item_id'),true); 
					}
				}
				
			}
			
		}
		
	}
	
}

$arr = array();
$arrData = $_SESSION['compare'];

if(isset($arrData) && is_array($arrData) && count($arrData)){
	foreach($arrData as $k => &$data){
		$item = &$article->getItem((int)strip_data($data));
		foreach(FieldsHelper::getFields('com_content.article',$item) as &$field) {
			$item->jcfields[$field->id] = $field;
		}
		$arr[$k] = $item;
	}
}else{
	$arr = false; 
}

function strip_data($text){
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
}

?>
<div class="blog compare<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="https://schema.org/Blog">
	<h1 class="hr"><?php echo $this->category->title;?></h1>
	<?php if(!empty($arr)):?>
	<table class="audio <?php echo (count($arr) < 4) ? 'min-'.count($arr) : '';?>">
		<tr class="gatgets">
		<?php foreach($arr as $i => &$item):?>
			<?php foreach($item->jcfields as &$fields){
				if($fields->title != 'Слайдер' 
				&& $fields->title != 'Комплектация' 
				&& $fields->title != 'Цена' 
				&& $fields->title != 'Артикул' 
				&& $fields->title != 'Модель' 
				&& $fields->title != 'Производитель' 
				&& $fields->title != 'Старая цена' 
				&& $fields->title != 'Старая цена' 
				&& $fields->title != 'Наличие' 
				&& $fields->title != 'Код производителя' 
				&& $fields->title != 'Особенности' 
				&& $fields->title != 'Дата анонсирования' 
				&& $fields->title != 'Страна производства' 
				&& $fields->title != 'Дополнительная информация' 
				&& $fields->title != 'Цена в магазине'){
					$arrFields[$fields->name]['title'] = $fields->title;
					$arrFields[$fields->name]['products'][$item->id]['id'] = $item->id;
					if(!empty($fields->value)){
						$arrFields[$fields->name]['products'][$item->id]['value'] = $fields->value;
					}else{
						$arrFields[$fields->name]['products'][$item->id]['value'] = '—';
					}
				}
			} 
			?>
			<td class="item-<?php echo $item->id;?>" itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
				<div class="bg">
					<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->language)); ?>" class="img" style="background-image:url(<?php echo json_decode($item->images)->image_intro;?>)"></a>
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
					<div class="availability">
					<?php if(!empty($tags = $tagsHelper->getItemTags('com_content.article',$item->id))):?>
						<?php $flag = false;?>
						<?php foreach($tags as &$tag):?>		
							<?php if($tag->parent_id == 3):?>
								<?php ($tag->id == 4) ? $flag = true : ''; // TODO - ID магазина подставить из coockies или сессии?>
							<?php endif; ?>
						<?php endforeach; ?>
					<?php endif; ?>
					<?php echo ($flag) ? '<i class="currentMarket yes">В наличии</i>' : '<i class="currentMarket no">Нет в наличии</i>';?>
					</div>
					<div class="more">
						<a class="link" itemprop="name" href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->language)); ?>"><?php echo $item->title; ?></a>
						<p class="price"><?php echo number_format($item->jcfields[15]->value,0,'',' ');?> руб.</p>
						<?php if(!empty($item->jcfields[35]->value)):?>
						<p class="old price"><?php echo number_format($item->jcfields[35]->value,0,'',' ');?> руб.</p>
						<?php endif; ?>
						<button class="addToCart"  data-id="<?php echo $item->id;?>" data-key="<?php echo md5(date("Y-m-d H:i:s").''.rand(0,10000));?>"></button>
						<button class="addCompare com remove active"  data-id="<?php echo $item->id;?>"></button>
						<button class="addFavorites <?php echo (in_array($item->id,$arrFavorites)) ? 'remove active' : '';?>"  data-id="<?php echo $item->id;?>"></button>
					</div>
				</div>
			</td>
		<?php endforeach; ?>
		</tr>
		<?php foreach($arrFields as &$fields):?>
		<tr class="fields">
			<?php foreach($fields['products'] as &$products):?>
			<td class="item-<?php echo $products['id'];?>"><i><?php echo $fields['title'];?></i><b><?php echo $products['value'];?></b></td>
			<?php endforeach; ?>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php else:?>
	<div class="noResult"><div class="cnt"><img src="/templates/enginevox/img/logo-min.svg" alt="Electrohype"><p><?php echo 'Вы не добавили товары в сравнение';?></p><a href="/" class="goCatalog">Перейти в каталог</a></div></div>
	<?php endif; ?>
</div>