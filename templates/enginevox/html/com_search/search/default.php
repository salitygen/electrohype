<?php
defined('_JEXEC') or die;
use Joomla\Module\ArticlesJkreview\Site\Helper\ArticlesJkreviewHelper;
use \Joomla\Component\Content\Site\Model\ArticleModel;
use \Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use Joomla\CMS\Helper\TagsHelper;
$tags = new TagsHelper;

if(isset($_GET['limit']) && $_GET['limit'] != ''){
	$limit = (int)$_GET['limit'];
}else{
	$limit = 30;
}

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
<?php if(isset($_GET['tmpl']) && $_GET['tmpl'] === 'component'):?>
<div class="search<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->error == null && count($this->results) > 0) : ?>
		<?php echo $this->loadTemplate('ajax'); ?>
	<?php else : ?>
		<?php echo $this->loadTemplate('error'); ?>
	<?php endif; ?>
</div>
<?php else:?>
<div class="blog search<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="https://schema.org/Blog">
	<?php if(!empty($this->results)):?>
	<h1 class="hr"><?php echo (!empty($this->results) ? 'Найдено: '.count($this->results).' товаров' : 'Результаты поиска');?></h1>
	<ul class="audio producktList">
	<?php foreach($this->results as &$item) : ?>
		<?php foreach(FieldsHelper::getFields('com_content.article',$item) as &$field):?>
			<?php $item->jcfields[$field->id] = $field;?>
		<?php endforeach; ?>
		<?php $item->tags = &$tags->getItemTags('com_content.article',$item->id);?>
		<li class="" itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
			<a href="<?php print JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->language)); ?>" class="img" style="background-image:url(<?php print json_decode($item->images)->image_intro;?>)"></a>
			<?php if($data = ArticlesJkreviewHelper::getTotalByItemId($item->id)):?>
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
				<a class="link" itemprop="name" href="<?php print JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->language)); ?>"><?php print $item->title; ?></a>
				<p class="price"><?php print number_format($item->jcfields[15],0,'',' ');?> руб.</p>
				<?php if(!empty($item->jcfields[35])):?>
				<p class="old price"><?php print number_format($item->jcfields[35],0,'',' ');?> руб.</p>
				<?php endif; ?>
				<button class="addToCart"  data-id="<?php print $item->id;?>" data-key="<?php print md5(date("Y-m-d H:i:s").''.rand(0,10000));?>"></button>
				<button class="addCompare <?php echo (in_array($item->id,$arrCompare)) ? 'remove active' : ''?>"  data-id="<?php echo $item->id;?>"></button>
				<button class="addFavorites <?php echo (in_array($item->id,$arrFavorites)) ? 'remove active' : ''?>"  data-id="<?php echo $item->id;?>"></button>
			</div>
		</li>
	<?php endforeach; ?>
	</ul>
	<?php else:?>
	<h1 class="hr">Результаты поиска</h1>
	<div class="noResult"><div class="cnt"><img src="/templates/enginevox/img/logo-min.svg" alt="Electrohype"><p><?php echo 'Ничего не найдено';?></p><a href="/" class="goCatalog">Перейти в каталог</a></div></div>
	<?php endif; ?>
	<?php if($this->total > $limit):?>
	<button class="loadNext">Показать ещё</button>
	<div class="pagination search">
		<p class="counter pull-right"> <?php echo $this->pagination->getPagesCounter(); ?></p>
		<?php echo $this->pagination->getPagesLinks(); ?> 
	</div>
	<?php endif;?>
</div>
<?php endif; ?>