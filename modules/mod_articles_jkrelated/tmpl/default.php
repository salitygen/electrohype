<?php
defined('_JEXEC') or die;
if (!$list) return;
use Joomla\Module\ArticlesJkreview\Site\Helper\ArticlesJkreviewHelper;
use Joomla\CMS\Helper\TagsHelper;
$tagsHelper = new TagsHelper;
?>
<nav class="moduletable_list_related">
	<div class="h3">С этим товаром покупают</div>
	<button class="arrow next"></button>
	<ul class="audio">
		<?php foreach($list as &$item) : ?>
			<li itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
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
				<?php echo ($flag) ? '<i class="currentMarket yes">Есть в наличии</i>' : '<i class="currentMarket no">Нет в наличии</i>';?>
				</div>
				<div class="more">
					<a class="link" itemprop="name" href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->language)); ?>"><?php echo $item->title; ?></a>
					<p class="price"><?php echo number_format($item->jcfields[15]->value,0,'',' ');?> руб.</p>
					<?php if(!empty($item->jcfields[35]->value)):?>
					<p class="old price"><?php echo number_format($item->jcfields[35]->value,0,'',' ');?> руб.</p>
					<?php endif; ?>
					<button class="addToCart"  data-id="<?php echo $item->id;?>" data-key="<?php echo md5(date("Y-m-d H:i:s").''.rand(0,10000));?>"></button>
					<button class="addCompare <?php echo (in_array($item->id,$arrCompare)) ? 'remove active' : ''?>"  data-id="<?php echo $item->id;?>"></button>
					<button class="addFavorites <?php echo (in_array($item->id,$arrFavorites)) ? 'remove active' : ''?>"  data-id="<?php echo $item->id;?>"></button>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
	<button class="arrow prev"></button>
</nav>