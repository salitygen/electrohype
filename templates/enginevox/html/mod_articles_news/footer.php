<?php
defined('_JEXEC') or die;
?>
<h3 class="hr">Статьи</h3>
<ul class="items">
<?php foreach($list as &$item) : ?>
	<li class="" itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
		<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language)); ?>" class="img" style="background-image:url(<?php echo json_decode($item->images)->image_intro;?>)"></a>
		<h3 itemprop="name">
			<a class="link" href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language)); ?>"><?php echo $item->title; ?></a>
		</h3>
		<p class="date"><?php echo date("d.m.Y h:i:s", strtotime($item->created));?></p>
		<div class="introtext">
			<?php echo $item->introtext;?> <a class="butt" href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language)); ?>"><?php echo JText::_('MORE');?></a>
		</div>
	</li>
<?php endforeach; ?>
</ul>






















