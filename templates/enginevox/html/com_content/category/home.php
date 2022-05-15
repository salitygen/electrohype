<?php
defined('_JEXEC') or die;
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
?>
<div id="ajaxUpdate" class="wt home">
	<div class="blog<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="https://schema.org/Blog">
		<div class="left">
		<?php if(count(JModuleHelper::getModules('cat_menu'))):?>
			<?php foreach(JModuleHelper::getModules('cat_menu') as $module) : ?>
				<?php print JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
			<?php endforeach; ?>
		<?php endif; ?>
		</div>
		<div class="right">
		<?php if(count(JModuleHelper::getModules('slider'))):?>
			<?php foreach(JModuleHelper::getModules('slider') as $module) : ?>
				<?php print JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if(count(JModuleHelper::getModules('sets'))):?>
			<?php foreach(JModuleHelper::getModules('sets') as $module) : ?>
				<?php print JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php /*if (!empty($this->lead_items)) : ?>
		<ul class="audio">
		<?php foreach ($this->lead_items as $item) : ?>
			<li class="" itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
				<a href="<?php print JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language)); ?>" class="img" style="background-image:url(<?php print json_decode($item->images)->image_intro;?>)"></a>
				<div class="more">
					<a class="link" itemprop="name" href="<?php print JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language)); ?>"><?php print $item->title; ?></a>
					<p class="price"><?php print number_format($item->jcfields[15]->value,0,'',' ');?> руб.</p>
					<p class="old price"><?php print number_format($item->jcfields[35]->value,0,'',' ');?> руб.</p>
				</div>
			</li>
		<?php endforeach; ?>
		</ul>
		<?php else:?>
		<p class="noResult"><?php print JText::_('NO_RESULT');?></p>
		<?php endif; ?>
		<?php if (($this->params->def('show_pagination', 1) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
			<div class="pagination">
				<?php if ($this->params->def('show_pagination_results', 1)):?>
				<p class="counter pull-right"> <?php echo $this->pagination->getPagesCounter(); ?> </p>
				<?php endif;?>
				<?php echo $this->pagination->getPagesLinks(); ?>
			</div>
		<?php endif; */?>
		</div>
	</div>
</div>