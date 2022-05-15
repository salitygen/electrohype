<?php
defined('_JEXEC') or die;
JLoader::register('JKHelper', JPATH_BASE . '/components/com_jkreview/helpers/jkhelper.php');
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
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
<?php if (!empty($list)) : ?>
<nav class="moduletable_list_cat">
	<?php if($module->showtitle):?>
	<div class="h3"><?php echo $module->title;?></div>
	<?php endif;?>
	<ul class="audio">
		<?php foreach ($list as $item) : ?>
			<?php foreach(FieldsHelper::getFields('com_content.article',$item,true) as $field):?>
				<?php $item->jcfields[$field->id] = $field;?>
			<?php endforeach; ?>
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
</nav>
<?php else:?>
<?php return; ?>
<?php endif; ?>
