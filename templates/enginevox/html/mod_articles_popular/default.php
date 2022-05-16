<?php
defined('_JEXEC') or die;
use Joomla\Module\ArticlesJkreview\Site\Helper\ArticlesJkreviewHelper;
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
use Joomla\Component\Fields\Administrator\Model\FieldModel;
use Joomla\CMS\Helper\TagsHelper;
$jfields = new FieldModel;
$jtags = new TagsHelper;

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

?>
<?php if(!empty($list)):?>
<nav class="moduletable_list_cat">
	<?php if($module->showtitle):?>
	<div class="h3"><?php echo $module->title;?></div>
	<?php endif;?>
	<ul class="audio">
		<?php foreach($list as &$item):?>
			<?php $item->jtags = $jtags->getItemTags('com_content.article',$item->id);?>
			<?php $item->jfields = $jfields->getFieldValues([15,35],$item->id);?>
			<li class="" itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
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
				<?php if(!empty($item->jtags)):?>
					<?php $flag = false;?>
					<?php foreach($item->jtags as &$tag):?>			
						<?php if($tag->parent_id == 3):?>
							<?php ($tag->id == 4) ? $flag = true : ''; // TODO - ID магазина подставить из coockies или сессии?>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
				<?php echo ($flag) ? '<i class="currentMarket yes">В наличии</i>' : '<i class="currentMarket no">Нет в наличии</i>';?>
				</div>
				<div class="more">
					<a class="link" itemprop="name" href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->language)); ?>"><?php echo $item->title; ?></a>
					<p class="price"><?php echo number_format($item->jfields[15],0,'',' ');?> руб.</p>
					<?php if(!empty($item->jcfields[35])):?>
					<p class="old price"><?php echo number_format($item->jfields[35],0,'',' ');?> руб.</p>
					<?php endif; ?>
					<button class="addToCart"  data-id="<?php echo $item->id;?>" data-key="<?php echo md5(date("Y-m-d H:i:s").''.rand(0,10000));?>"></button>
					<button class="addCompare <?php echo (in_array($item->id,$arrCompare)) ? 'remove active' : ''?>"  data-id="<?php echo $item->id;?>"></button>
					<button class="addFavorites <?php echo (in_array($item->id,$arrFavorites )) ? 'remove active' : ''?>"  data-id="<?php echo $item->id;?>"></button>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>
<?php else:?>
<?php return; ?>
<?php endif; ?>
