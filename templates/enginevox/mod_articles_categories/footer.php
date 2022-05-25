<?php
/**
* Делаем кастомный вывод категорий
*
*
*/
defined('_JEXEC') or die;
?>
<ul>   
	<?php foreach($list as $cat):?>
	<li>
		<a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($cat->id)); ?>"><?php print $cat->title;?></a>
	</li>
	<?php endforeach;?>
</ul>
