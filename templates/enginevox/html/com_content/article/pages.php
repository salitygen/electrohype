<?php
defined('_JEXEC') or die;
$db = JFactory::getDbo();
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
require_once("components/com_content/models/category.php");
JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_content/models');
$article = new ContentModelArticle;
$type = $color = '';

if(isset($this->item->jcfields[13]) && !empty($this->item->jcfields[13]->value)){
	$type = $this->item->jcfields[13]->value;
}
if(isset($this->item->jcfields[14]) && !empty($this->item->jcfields[14]->value)){
	$color = $this->item->jcfields[14]->value;
}

$query = "SELECT DISTINCT yrnsv_fields_values.item_id FROM yrnsv_fields_values WHERE yrnsv_fields_values.value IN('{$color}','{$type}')";
$db->setQuery($query);
$result = $db->loadObjectList();
$i = $n = 0;

if(!empty($result)){
	foreach($result as $data){
		$itemData = $article->getItem($data->item_id);
		$itemData->jcfields	= FieldsHelper::getFields('com_content.article',$itemData,true);
		if($itemData->jcfields[0]->value == $type){
			if(!empty($itemData->jcfields[2]->value)){
				$arr[$type][$i]['id']		= $itemData->id;
				$arr[$type][$i]['catid']	= $itemData->catid;
				$arr[$type][$i]['language']	= $itemData->language;
				$arr[$type][$i]['title']	= $itemData->title;
				$arr[$type][$i]['type']		= $itemData->jcfields[0]->value;
				$arr[$type][$i]['price']	= $itemData->jcfields[2]->value;
				$arr[$type][$i]['images']	= json_decode($itemData->images)->image_intro;
				$i++;
			}
		}
		if(!empty($itemData->jcfields[1]->value)){	
			if($itemData->jcfields[1]->value == $color){
				$arr[$color][$n]['id']		= $itemData->id;
				$arr[$color][$n]['catid']	= $itemData->catid;
				$arr[$color][$n]['language']= $itemData->language;
				$arr[$color][$n]['title']	= $itemData->title;
				$arr[$color][$n]['type']	= $itemData->jcfields[0]->value;
				$arr[$color][$n]['price']	= $itemData->jcfields[2]->value;
				$arr[$color][$n]['images']	= json_decode($itemData->images)->image_intro;
				$n++;
			}
		}
	}
}

?>
<div class="item" data-id="<?php print $this->item->id;?>">
	<h1 class="headTitleNews hr"><?php print $this->item->title ?></h1>
	<div class="text-block">
		<?php if(!empty(json_decode($this->item->images)->image_fulltext)):?>
		<div class="image">
		<img src="/<?php print json_decode($this->item->images)->image_fulltext;?>">
		</div>
		<?php endif;?>
		<?php print $this->item->text; ?>
	</div>
</div>
<?php if(!empty($arr)):?>
	<?php if(!empty($arr[$type]) && !empty($type) && count($arr[$type]) > 1):?>
	<p></p>
	<div class="colors">
		<ul>
		<?php foreach($arr[$type] as $article):?>
			<li>
				<a href="<?php print JRoute::_(ContentHelperRoute::getArticleRoute($article['id'], $article['catid'], $article['language'])); ?>"><h4><?php print $article['title'];?> <br><span><?php print $article['type'];?></span></h4><img src="<?php print $article['images'];?>"><span class="price"><?php print $article['price'];?>&nbsp;₽</span></a>
			</li>
		<?php endforeach; ?> 
		</ul>
	</div>
	<?php endif;?>
<?php endif;?>