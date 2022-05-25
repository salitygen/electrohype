<?php
defined('_JEXEC') or die;
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
?>
<h1 class="hr"><?php echo $this->category->title;?></h1>
<div id="ajaxUpdate wt">
	<div class="blog<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="https://schema.org/Blog">
		<?php if (!empty($this->lead_items)) : ?>
		<ul class="audio">
		<?php foreach ($this->lead_items as $item) : ?>
			<li class="" itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
				<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language)); ?>" class="img" style="background-image:url(<?php echo json_decode($item->images)->image_intro;?>)"></a>
				<div class="more">
					<a class="link" itemprop="name" href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language)); ?>"><?php echo $item->title; ?></a>
					<?php if(!empty($item->jcfields[35]->value)):?>
					<p class="old price"><?php echo number_format($item->jcfields[35]->value,0,'',' ');?> руб.</p>
					<?php endif; ?>
					<p class="old price"><?php echo number_format($item->jcfields[35]->value,0,'',' ');?> руб.</p>
				</div>
			</li>
		<?php endforeach; ?>
		</ul>
		<?php else:?>
		<p class="noResult"><?php echo JText::_('NO_RESULT');?></p>
		<?php endif; ?>
	</div>
	<?php if(($this->params->def('show_pagination', 1) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)):?>
		<div class="pagination">
			<?php if ($this->params->def('show_pagination_results', 1)):?>
			<p class="counter pull-right"> <?php echo $this->pagination->getPagesCounter(); ?> </p>
			<?php endif;?>
			<?php echo $this->pagination->getPagesLinks(); ?>
		</div>
	<?php endif; ?>
</div>