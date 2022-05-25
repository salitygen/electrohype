<?php
defined('_JEXEC') or die;
?>
<div class="sets">
	<ul class="setsList">
		<?php foreach($list as $cat):?>
		<?php $src = json_decode($cat->params)->image;?>
		<li style="background-image:url(<?php print $src;?>);"><a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($cat->id)); ?>"><span><?php print $cat->title;?></span></a></li>
		<?php endforeach;?>
	</ul>
</div>
