<?php
defined('_JEXEC') or die;
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
use Joomla\Module\ArticlesJkreview\Site\Helper\ArticlesJkreviewHelper;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use Joomla\Component\Content\Site\Model\ArticleModel;

$db = &JFactory::getDbo();
$user = &JFactory::getUser();
$tagsHelper = new TagsHelper;
$article = new ArticleModel;

$categoryByFields = new \stdClass;
$categoryByFields->id = $this->item->catid;
$categoryFields = &FieldsHelper::getFields('com_content.categories',$categoryByFields);

$variabilityIds = array();
$variabilityVariantsIds = array();
$variabilityValues = array();

if(is_null($_SESSION['favorites'])){
	$arrFavorites = array();
}else{
	$arrFavorites  = $_SESSION['favorites'];
}

if(is_null($_SESSION['compare'])){
	$arrCompare = array();
}else{
	$arrCompare = $_SESSION['compare'];
}

if(!empty($categoryFields)){
	
	foreach($categoryFields as &$cfield){
		
		if($cfield->name == 'variability-products'){
			if(!empty($cfield->rawvalue)){
				foreach($cfield->rawvalue as &$value){
					array_push($variabilityIds,(int)$value);
					if(!empty($this->item->jcfields[$value]->rawvalue)){
						if(is_array($this->item->jcfields[$value]->rawvalue)){
							foreach($this->item->jcfields[$value]->rawvalue as &$data){
								array_push($variabilityValues,"'".(string)$data ."'");
							}
						}else{
							array_push($variabilityValues,"'".(string)$this->item->jcfields[$value]->rawvalue ."'");
						}
					}
				}
			}
		}
		
		if($cfield->name == 'variability-products-variants'){
			if(!empty($cfield->rawvalue)){
				foreach($cfield->rawvalue as &$value){
					array_push($variabilityVariantsIds,(int)$value);
				}
			}
		}
		
	}
	
	if(!empty($variabilityValues) && !empty($variabilityIds)){
		
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('item_id')));
		$query->from($db->quoteName('#__fields_values'));
		$query->where($db->quoteName('field_id').' IN('.implode(',',array_unique($variabilityIds)).')');
		$query->where($db->quoteName('value').' IN('.implode(',',array_unique($variabilityValues)).')');
		$query->group($db->quoteName('item_id').' HAVING COUNT(item_id) >='.(count($variabilityValues)));
		$query->order('value ASC');
		$db->setQuery($query);
		$results = &$db->loadColumn();

		if(!empty($results) && $results !== NULL){
			foreach($results as $k => &$id){
				$variabilityArticles[$k] = $article->getItem($id);
				foreach(FieldsHelper::getFields('com_content.article',$variabilityArticles[$k]) as &$field){
					$variabilityArticles[$k]->jcfields[$field->id] = $field;
				}
			}
		}
		
		foreach($variabilityArticles as $n => &$articles){
			$variabilityArticles[$n]->reallink = &JRoute::_(ContentHelperRoute::getArticleRoute($articles->id, $articles->catid, $articles->language));
			foreach($variabilityVariantsIds as &$aid){
				foreach($variabilityVariantsIds as &$bid){
					if($this->item->jcfields[$aid]->name != $articles->jcfields[$bid]->name){
						$variability[$articles->jcfields[$bid]->name]['title'] = $articles->jcfields[$bid]->title;
						for($k=$i=0;$i<count($variabilityVariantsIds);$i++){
							if(is_array($articles->jcfields[$bid]->rawvalue)){
								$a = $this->item->jcfields[$variabilityVariantsIds[$i]]->rawvalue[0];
								$b = $articles->jcfields[$variabilityVariantsIds[$i]]->rawvalue[0];
								$c = $articles->jcfields[$bid]->rawvalue[0];
							}else{
								$a = $this->item->jcfields[$variabilityVariantsIds[$i]]->rawvalue;
								$b = $articles->jcfields[$variabilityVariantsIds[$i]]->rawvalue;
								$c = $articles->jcfields[$bid]->rawvalue;
							}
							if($a == $b && $b != $c){$k++;
								if($k == (count($variabilityVariantsIds)-1)){
									if(!isset($variability[$articles->jcfields[$bid]->name]['options'][$c])){
										$variability[$articles->jcfields[$bid]->name]['options'][$c]['link'] = $variabilityArticles[$n]->reallink;
										$variability[$articles->jcfields[$bid]->name]['options'][$c]['value'] = $articles->jcfields[$bid]->value;
										$variability[$articles->jcfields[$bid]->name]['options'][$c]['rawvalue'] = $c;
										$variability[$articles->jcfields[$bid]->name]['options'][$c]['comment'] = 'real';
									}
								}
							}
						}
					}
				}
			}
		}
		
		foreach($variabilityArticles as &$articles){
			foreach($variabilityVariantsIds as &$aid){
				foreach($variabilityVariantsIds as &$bid){
					if($this->item->jcfields[$aid]->name != $articles->jcfields[$bid]->name){
						if(is_array($articles->jcfields[$bid]->rawvalue)){
							if(!isset($variability[$articles->jcfields[$bid]->name]['options'][$articles->jcfields[$bid]->rawvalue[0]])){
								$variability[$articles->jcfields[$bid]->name]['options'][$articles->jcfields[$bid]->rawvalue[0]]['link'] = $articles->reallink;
								$variability[$articles->jcfields[$bid]->name]['options'][$articles->jcfields[$bid]->rawvalue[0]]['value'] = $articles->jcfields[$bid]->value;
								$variability[$articles->jcfields[$bid]->name]['options'][$articles->jcfields[$bid]->rawvalue[0]]['rawvalue'] = $articles->jcfields[$bid]->rawvalue[0];
								$variability[$articles->jcfields[$bid]->name]['options'][$articles->jcfields[$bid]->rawvalue[0]]['comment'] = 'Есть отличия';
							}
						}else{
							if(!isset($variability[$articles->jcfields[$bid]->name]['options'][$articles->jcfields[$bid]->rawvalue])){
								$variability[$articles->jcfields[$bid]->name]['options'][$articles->jcfields[$bid]->rawvalue]['link'] = $articles->reallink;
								$variability[$articles->jcfields[$bid]->name]['options'][$articles->jcfields[$bid]->rawvalue]['value'] = $articles->jcfields[$bid]->value;
								$variability[$articles->jcfields[$bid]->name]['options'][$articles->jcfields[$bid]->rawvalue]['rawvalue'] = $articles->jcfields[$bid]->rawvalue;
								$variability[$articles->jcfields[$bid]->name]['options'][$articles->jcfields[$bid]->rawvalue]['comment'] = 'Есть отличия';
							}
						}
					}
				}
			}
		}
		
		foreach($variabilityVariantsIds as &$id){
			if(is_array($this->item->jcfields[$id]->rawvalue)){
				$e = $this->item->jcfields[$id]->rawvalue[0];
			}else{
				$e = $this->item->jcfields[$id]->rawvalue;
			}
			$variability[$this->item->jcfields[$id]->name]['options'][$e]['link'] = 'this';
			$variability[$this->item->jcfields[$id]->name]['options'][$e]['value'] = $this->item->jcfields[$id]->value;
			$variability[$this->item->jcfields[$id]->name]['options'][$e]['rawvalue'] = $e;
			$variability[$this->item->jcfields[$id]->name]['options'][$e]['comment'] = 'real';
		}
		
		foreach($variability as $k => &$var){
			if(count($var['options']) == 1){
				unset($variability[$k]);
			}else{
				krsort($var['options']);
				$variability[$k]['options'] = array_values($var['options']);
			}
		}
		
		unset($query,$results,$articles,$variabilityArticles,$categoryFields);
		
	}
	
}

if(!empty($latest = $_SESSION['latest'])){
	if(is_array($latest)){
		if(!in_array($this->item->id,$latest)){
			array_push($latest,$this->item->id);
		}
	}
}else{
	$latest = array($this->item->id);
}

$latest = array_unique($latest);

if(count($latest) > 12){
	$_SESSION['latest'] = array_slice($latest,0,12);
}else{
	$_SESSION['latest'] = $latest;
}

if($user->id !== 0){
	$field = new stdClass();
	$field->field_id = 112;
	$field->item_id = $user->id;
	$field->value = json_encode($_SESSION['latest']);
	$db->updateObject('#__fields_values',$field,array('field_id','item_id'),true); 
}

?>
<h1 class="headTitleNews hr"><?php echo $this->item->title ?></h1>
<div class="item">
	<div class="overflowBox desctop">
		<button class="arrow up"></button>
		<div class="imgScrollSlider">
			<ul class="imgSlider">
			<?php foreach(json_decode($this->item->jcfields[20]->rawvalue) as &$slide):?>
				<li>
					<a data-fancybox="gallery" href="<?php echo $slide->field101->imagefile;?>" style="background-image: url(<?php echo $slide->field100->imagefile;?>);">
						<img src="<?php echo $slide->field100->imagefile;?>">
					</a>
				</li>
			<?php endforeach; ?> 
			</ul> 
		</div>
		<button class="arrow down"></button>
	</div>
	<div class="image">
		<?php $imgFull = json_decode($this->item->images)->image_fulltext;?>
		<?php echo '<a data-fancybox="gallery" href="'.$imgFull.'"><img src="'.$imgFull.'"></a>';?>
	</div>
	<div class="overflowBox mobile">
		<button class="arrow up"></button>
		<div class="imgScrollSlider">
			<ul class="imgSlider">
			<?php foreach(json_decode($this->item->jcfields[20]->rawvalue) as &$slide):?>
				<li>
					<a data-fancybox="gallery" href="<?php echo $slide->field101->imagefile;?>" style="background-image: url(<?php echo $slide->field100->imagefile;?>);">
						<img src="<?php echo $slide->field100->imagefile;?>">
					</a>
				</li>
			<?php endforeach; ?> 
			</ul> 
		</div>
		<button class="arrow down"></button>
	</div>
	<div class="cartbox<?php echo (count($variability) < 2) ? ' max' : ' min';?>">
		<div class="availability top">
		<?php if(!empty($tags = $tagsHelper->getItemTags('com_content.article',$this->item->id))):?>
			<?php $flag = false;?>
			<?php foreach($tags as &$tag):?>		
				<?php if($tag->parent_id == 3):?>
					<?php ($tag->id == 4) ? $flag = true : ''; // TODO - ID магазина подставить из coockies или сессии?>
				<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php echo ($flag) ? '<i class="currentMarket yes">Есть в наличии</i>' : '<i class="currentMarket no">Нет в наличии</i>';?>
		</div>
		<div class="more">
			<?php if(!empty($this->item->jcfields[38]->rawvalue)):?>
			<p class="articul"><b>Артикул:</b><i><?php echo $this->item->jcfields[38]->rawvalue;?></i></p>
			<?php endif; ?>
			<?php if($data = ArticlesJkreviewHelper::getTotalByItemId($this->item->id)):?>
			<a class="rating<?php echo ($data->count == 0) ? ' off' : ''?>" href="#reviews">
				<?php if($data->count !== 0):?>
				<input type="hidden" name="val" value="<?php echo (int)$data->sum;?>" tabindex="0">
				<i class="counter"><?php echo $data->count;?></i>
				<?php else: ?>
				<i class="counter emty">Нет отзывов</i>
				<input type="hidden" name="val" value="0" tabindex="0">
				<?php endif; ?>
			</a>
			<?php endif; ?>
			<button class="addCompare <?php echo ($isComapre = in_array($this->item->id,$arrCompare)) ? 'remove active' : ''?>"  data-id="<?php echo $this->item->id;?>">
				<span><?php echo ($isComapre) ? 'В сравнении' : 'В сравнение';?></span>
			</button>
			<button class="addFavorites <?php echo ($isFavorite = in_array($this->item->id,$arrFavorites)) ? 'remove active' : ''?>"  data-id="<?php echo $this->item->id;?>">
				<span><?php echo ($isFavorite) ? 'В избранном' : 'В избранное';?></span>
			</button>
		</div>
		<?php if(!empty($variability)):?>
		<div class="variability">
		<?php foreach($variability as $k => &$variant):?>
			<label><?php echo $variant['title']?></label>
			<ul class="<?php echo $k;?>">
			<?php foreach($variant['options'] as $n => &$option):?>
				<?php if($k == 'tsvet'):?>
				<li>
					<input id="<?php echo $k.'-'.$n;?>" type="radio" name="<?php echo $k;?>" value="<?php echo $option['link'];?>" <?php echo ($option['link'] == 'this') ? 'checked=checked' : '';?>>
					<label <?php echo ($option['comment'] != 'real') ? 'class="off"':'';?> for="<?php echo $k.'-'.$n;?>"><span style="background:<?php echo $option['rawvalue'];?>">&nbsp;</span></label>
				</li>
				<?php else: ?>
				<li>
					<input id="<?php echo $k.'-'.$n;?>" type="radio" name="<?php echo $k;?>" value="<?php echo $option['link'];?>" <?php echo ($option['link'] == 'this') ? 'checked=checked' : '';?>>
					<label  <?php echo ($option['comment'] != 'real') ? 'class="off"':'';?> for="<?php echo $k.'-'.$n;?>"><span><?php echo $option['value'];?></span></label>
				</li>
				<?php endif; ?>
			<?php endforeach; ?> 
			</ul>
		<?php endforeach; ?> 
		</div>
		<?php endif;?>
		<?php if(count($o = &JModuleHelper::getModules('related'))):?>
			<?php foreach($o as &$module) : ?>
				<?php echo JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
			<?php endforeach; ?>
		<?php endif;?>
		<div class="cart">
			<div class="price"><b><?php echo number_format($this->item->jcfields[15]->value,0,'',' ');?> руб.</b></div>
			<button class="addToCart" data-id="<?php echo $this->item->id;?>" data-key="<?php echo md5(date("Y-m-d H:i:s").''.rand(0,10000));?>"><?php echo JText::_('ADD_TO_CART');?></button>
			<div class="controls"><button class="plus">&nbsp;</button><input type="number" value="1" name="count"><button class="minus">&nbsp;</button></div>
		</div>
	</div>
</div>
<div class="right params">
	<?php if(!empty($this->item->text) && $this->item->text !== ' '): ?>
	<div id="description" class="content">
		<div class="h2">Описание</div>
		<?php echo $this->item->text; ?>
	</div>
	<?php endif;?>
	<div id="specifications" class="content cartbox">
		<div class="h2">Характеристики</div>
		<ul>
		<?php $paramOne = $paramSeveral = '';?>
		<?php foreach($this->item->jcfields as &$field):
			if($field->title != 'Слайдер' 
			&& $field->title != 'Цена' 
			&& $field->title != 'Артикул' 
			&& $field->title != 'Старая цена' 
			&& $field->title != 'Наличие' 
			&& $field->title != 'Код производителя' 
			&& $field->title != 'Дата анонсирования' 
			&& $field->title != 'Модель' 
			&& $field->title != 'Страна производства' 
			&& $field->title != 'Производитель' 
			&& $field->title != 'Срок гарантии (Мес)' 
			&& $field->title != 'Цена в магазине'): ?>
			<?php if(!empty($field->value)):?>
				<?php if(is_array($field->rawvalue) && count($field->rawvalue) > 1):?>
					<?php $paramSeveral .= '<li class="headers"><h2>'. $field->title .'</h2></li>';?> 
					<?php foreach($field->rawvalue as &$v):?>
					<?php $paramSeveral .= '<li><b>'.$v.'</b> <span>Есть</span></li>';?> 
					<?php endforeach; ?> 
				<?php else:?>
					<?php $paramOne .= '<li><b>'. $field->title .'</b> <span>'. $field->value .'</span></li>';?> 
				<?php endif;?>
			<?php endif;?>
		<?php endif;?>
		<?php endforeach; ?> 
		<?php echo $paramOne .''. $paramSeveral;?>
		</ul>
		<button type="button" class="openSpecifications">Показать все</button>
	</div>
</div>
