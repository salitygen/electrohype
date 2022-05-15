<?php
defined('_JEXEC') or die;
JLoader::register('JKHelper', JPATH_BASE . '/components/com_jkreview/helpers/jkhelper.php');
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
?>
<div id="ajaxUpdate" class="wt">
	<div class="blog products tags<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="https://schema.org/Blog">
		<div class="right">
		<h1 class="hr"><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
		<?php if (!empty($this->items)) : ?>
		<?php if(count(JModuleHelper::getModules('brand_menu'))):?>
			<?php foreach(JModuleHelper::getModules('brand_menu') as $module) : ?>
				<?php echo JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<ul class="audio">
		<?php foreach ($this->items as $item) : ?>
			<li class="" itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
				<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->language)); ?>" class="img" style="background-image:url(<?php echo json_decode($item->core_images)->image_intro;?>)"></a>
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
					<a class="link" itemprop="name" href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->language)); ?>"><?php echo $item->core_title; ?></a>
					<p class="price"><?php echo number_format($item->jcfields[15]->value,0,'',' ');?> руб.</p>
					<?php if(!empty($item->jcfields[35]->value)):?>
					<p class="old price"><?php echo number_format($item->jcfields[35]->value,0,'',' ');?> руб.</p>
					<?php endif; ?>
					<button class="addToCart"  data-id="<?php echo $item->id;?>" data-key="<?php echo md5(date("Y-m-d H:i:s").''.rand(0,10000));?>"></button>
					<button class="addCompare <?php echo (isset($com[$item->id])) ? 'remove active' : ''?>"  data-id="<?php echo $item->id;?>"></button>
					<button class="addFavorites <?php echo (isset($fav[$item->id])) ? 'remove active' : ''?>"  data-id="<?php echo $item->id;?>"></button>
				</div>
			</li>
		<?php endforeach; ?>
		</ul>
		<?php else:?>
		<div class="noResult"><div class="cnt"><img src="/templates/enginevox/img/logo-min.svg" alt="Electrohype"><p><?php print JText::_('NO_RESULT');?></p><a href="/" class="goCatalog">Сбросить фильтр</a></div></div>
		<?php endif; ?>
		<?php if (($this->params->def('show_pagination', 1) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
			<div class="pagination">
				<?php if ($this->params->def('show_pagination_results', 1)):?>
				<p class="counter pull-right"> <?php echo $this->pagination->getPagesCounter(); ?> </p>
				<?php endif;?>
				<?php echo $this->pagination->getPagesLinks(); ?>
			</div>
		<?php endif; ?>
		<?php if(count(JModuleHelper::getModules('sets'))):?>
			<?php foreach(JModuleHelper::getModules('sets') as $module) : ?>
				<?php echo JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
			<?php endforeach; ?>
		<?php endif; ?>
		</div>
		<div class="left">
		<?php if(count(JModuleHelper::getModules('filtres'))):?>
			<?php foreach(JModuleHelper::getModules('filtres') as $module) : ?>
				<?php echo JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if(count(JModuleHelper::getModules('cat_menu'))):?>
			<?php foreach(JModuleHelper::getModules('cat_menu') as $module) : ?>
				<?php echo JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
			<?php endforeach; ?>
		<?php endif; ?>
		</div>
	</div>
</div>