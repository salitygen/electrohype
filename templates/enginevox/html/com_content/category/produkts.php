<?php
defined('_JEXEC') or die;
JLoader::register('JKHelper', JPATH_BASE . '/components/com_jkreview/helpers/jkhelper.php');
use Joomla\CMS\Helper\TagsHelper;
$tags = new TagsHelper;
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

/* $app = JFactory::getApplication();
use Joomla\Component\Content\Site\Model\CategoryModel;
$itemid = $app->input->getInt('id', 0).':'.$app->input->getInt('Itemid', 0);
$app->setUserState('com_content.category.filter.'.$itemid.'.tag','2,4');
$model = new CategoryModel;
$this->lead_items = $model->getItems(); */

?>
<div id="ajaxUpdate" class="wt">
	<div class="blog products<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="https://schema.org/Blog">
		<div class="right">
		<?php if (!empty($this->lead_items)) : ?>
		<?php if(count(JModuleHelper::getModules('brand_menu'))):?>
			<?php foreach(JModuleHelper::getModules('brand_menu') as $module) : ?>
				<?php print JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<h1 class="hr"><?php echo $this->category->title;?></h1>
		<ul class="audio producktList">
		<?php foreach ($this->lead_items as $item):?>
		<?php $item->tags = $tags->getItemTags('com_content.article',$item->id);?>
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
				<div class="availability">
				<?php if(!empty($item->tags)):?>
					<?php $flag = false;?>
					<?php foreach($item->tags as $tag):?>			
						<?php if($tag->parent_id == 3):?>
							<?php ($tag->id == 4) ? $flag = true : ''; // TODO - ID магазина подставить из coockies или сессии?>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
				<?php echo ($flag) ? '<i class="currentMarket yes">В наличии</i>' : '<i class="currentMarket no">Нет в наличии</i>';?>
				</div>
				<div class="more">
					<a class="link" itemprop="name" href="<?php print JRoute::_(ContentHelperRoute::getArticleRoute($item->id, $item->catid, $item->language)); ?>"><?php print $item->title; ?></a>
					<p class="price"><?php print number_format($item->jcfields[15]->value,0,'',' ');?> руб.</p>
					<?php if(!empty($item->jcfields[35]->value)):?>
					<p class="old price"><?php print number_format($item->jcfields[35]->value,0,'',' ');?> руб.</p>
					<?php endif; ?>
					<button class="addToCart"  data-id="<?php print $item->id;?>" data-key="<?php print md5(date("Y-m-d H:i:s").''.rand(0,10000));?>"></button>
					<button class="addCompare <?php echo (isset($com[$item->id])) ? 'remove active' : ''?>"  data-id="<?php print $item->id;?>"></button>
					<button class="addFavorites <?php echo (isset($fav[$item->id])) ? 'remove active' : ''?>"  data-id="<?php print $item->id;?>"></button>
				</div>
			</li>
		<?php endforeach; ?>
		</ul>
		<?php else:?>
		<div class="noResult"><div class="cnt"><img src="/templates/enginevox/img/logo-min.svg" alt="Electrohype"><p><?php print JText::_('NO_RESULT');?></p><a href="/" class="goCatalog">Перейти в каталог</a></div></div>
		<?php endif; ?>
		<?php if (($this->params->def('show_pagination', 1) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
			<button class="loadNext">Показать ещё</button>
			<div class="pagination">
				<?php if ($this->params->def('show_pagination_results', 1)):?>
				<p class="counter pull-right"> <?php echo $this->pagination->getPagesCounter(); ?> </p>
				<?php endif;?>
				<?php echo $this->pagination->getPagesLinks(); ?>
			</div>
		<?php endif; ?>
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