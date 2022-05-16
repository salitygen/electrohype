<?php
defined('_JEXEC') or die;
use Joomla\Module\ArticlesJkreview\Site\Helper\ArticlesJkreviewHelper;
use Joomla\CMS\Helper\TagsHelper;
$tags = new TagsHelper;

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
<div class="blog products tags<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="https://schema.org/Blog">
	<div id="ajaxUpdate">
	<?php if (!empty($this->items)):?>
		<ul class="audio producktList">
		<?php foreach($this->items as &$item) : ?>
			<?php $item->tags = &$tags->getItemTags('com_content.article',$item->id);?>
			<li class="" itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
				<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->language)); ?>" class="img" style="background-image:url(<?php echo json_decode($item->core_images)->image_intro;?>)"></a>
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
				<?php if(!empty($item->tags)):?>
					<?php $flag = false;?>
					<?php foreach($item->tags as &$tag):?>			
						<?php if($tag->parent_id == 3):?>
							<?php ($tag->id == 4) ? $flag = true : ''; // TODO - ID магазина подставить из coockies или сессии?>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
				<?php echo ($flag) ? '<i class="currentMarket yes">В наличии</i>' : '<i class="currentMarket no">Нет в наличии</i>';?>
				</div>
				<div class="more">
					<a class="link" itemprop="name" href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->language)); ?>"><?php echo $item->core_title; ?></a>
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
		<?php else:?>
		<div class="noResult"><div class="cnt"><img src="/templates/enginevox/img/logo-min.svg" alt="Electrohype"><p><?php echo JText::_('NO_RESULT');?></p><a href="/" class="goCatalog">Сбросить фильтр</a></div></div>
		<?php endif; ?>
		<?php if (($this->params->def('show_pagination', 1) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
		<div class="pagination">
			<?php if ($this->params->def('show_pagination_results', 1)):?>
			<p class="counter pull-right"> <?php echo $this->pagination->getPagesCounter(); ?> </p>
			<?php endif;?>
			<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
		<?php endif; ?>
	</div>
</div>