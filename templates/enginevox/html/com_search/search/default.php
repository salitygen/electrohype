<?php
defined('_JEXEC') or die;
JLoader::register('JKHelper', JPATH_BASE . '/components/com_jkreview/helpers/jkhelper.php');
use \Joomla\Component\Content\Site\Model\ArticleModel;
use \Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
$session = JFactory::getSession();
$arrData = $session->get('favorites');
$fav = array();
foreach($arrData as $data){
	$fav[$data['id']] = $data['id'];
}
$arrData = $session->get('compare');
$com = array();
foreach($arrData as $data){
	$com[$data['id']] = $data['id'];
}

if(isset($_GET['limit']) && $_GET['limit'] != ''){
	$limit = (int)$_GET['limit'];
}else{
	$limit = 30;
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
<?php else : ?>
<div id="ajaxUpdate" class="wt">
	<div class="blog search<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="https://schema.org/Blog">
		<div class="right">
		<?php if (!empty($this->results)) : ?>
		<?php if(count(JModuleHelper::getModules('brand_menu'))):?>
			<?php foreach(JModuleHelper::getModules('brand_menu') as $module) : ?>
				<?php print JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<h1 class="hr"><?php echo (!empty($this->results) ? 'Найдено: '.count($this->results).' товаров' : 'Результаты поиска');?></h1>
		<ul class="audio producktList">
		<?php foreach ($this->results as $item) : ?>
			<?php $item->jcfields = explode(',',$item->jcfields);?>
			<li class="" itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
				<a href="<?php print JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->language)); ?>" class="img" style="background-image:url(<?php print json_decode($item->images)->image_intro;?>)"></a>
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
				<div class="more">
					<a class="link" itemprop="name" href="<?php print JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->language)); ?>"><?php print $item->title; ?></a>
					<p class="price"><?php print number_format($item->jcfields[0],0,'',' ');?> руб.</p>
					<?php if(!empty($item->jcfields[3])):?>
					<p class="old price"><?php print number_format($item->jcfields[3],0,'',' ');?> руб.</p>
					<?php endif; ?>
					<button class="addToCart"  data-id="<?php print $item->id;?>" data-key="<?php print md5(date("Y-m-d H:i:s").''.rand(0,10000));?>"></button>
					<button class="addCompare <?php echo (isset($com[$item->id])) ? 'remove active' : ''?>"  data-id="<?php print $item->id;?>"></button>
					<button class="addFavorites <?php echo (isset($fav[$item->id])) ? 'remove active' : ''?>"  data-id="<?php print $item->id;?>"></button>
				</div>
			</li>
		<?php endforeach; ?>
		</ul>
		<?php else:?>
		<h1 class="hr">Результаты поиска</h1>
		<div class="noResult"><div class="cnt"><img src="/templates/enginevox/img/logo-min.svg" alt="Electrohype"><p><?php echo 'Ничего не найдено';?></p><a href="/" class="goCatalog">Перейти в каталог</a></div></div>
		<?php endif; ?>
		<?php if ($this->total > $limit) : ?>
		<button class="loadNext">Показать ещё</button>
		<div class="pagination search">
			<p class="counter pull-right"> <?php echo $this->pagination->getPagesCounter(); ?></p>
			<?php echo $this->pagination->getPagesLinks(); ?> 
		</div>
		<?php endif;?>
		<?php if(count(JModuleHelper::getModules('sets'))):?>
			<?php foreach(JModuleHelper::getModules('sets') as $module) : ?>
				<?php print JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
			<?php endforeach; ?>
		<?php endif; ?>
		</div>
		<div class="left">
		<?php if(count(JModuleHelper::getModules('filtres'))):?>
			<?php foreach(JModuleHelper::getModules('filtres') as $module) : ?>
				<?php print JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if(count(JModuleHelper::getModules('cat_menu'))):?>
			<?php foreach(JModuleHelper::getModules('cat_menu') as $module) : ?>
				<?php print JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
			<?php endforeach; ?>
		<?php endif; ?>
		</div>
	</div>
</div>
<?php endif; ?>