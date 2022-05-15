<?php
/**
* Делаем кастомный вывод категорий
*
*
*/
defined('_JEXEC') or die;
$input  = JFactory::getApplication()->input;
$option = $input->getCmd('option');
$view   = $input->getCmd('view');
$id     = $input->getInt('id');
?>
<table>   
	<?php foreach($list as $cat):?>
	<tr <?php if ($id == $cat->id && in_array($view, array('category', 'categories')) && $option == 'com_content') echo ' class="active"'; ?>><td><a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($cat->id)); ?>"><img src="<?php print json_decode($cat->params)->image;?>"></a></td><td><a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($cat->id)); ?>"><span><?php print $cat->title;?></span></a></td></tr>
	<?php endforeach;?>
</table> 