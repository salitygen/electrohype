<?php
defined('_JEXEC') or die;
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
include $_SERVER['DOCUMENT_ROOT'].'/templates/enginevox/mp3.php';
require_once("components/com_content/models/category.php");
JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_content/models');
?>
<div class="item page" data-id="<?php print $this->item->id;?>">
	<h1 class="headTitleNews"><?php print $this->item->title ?></h1>
	<?php print $this->item->text; ?>
	<?php if(!empty($this->item->jcfields)):?>
	<?php foreach($this->item->jcfields as $field):?>
		<?php if($field->name == 'model' && !empty($field->rawvalue)):?>
		<?php $n=0; foreach(json_decode($field->rawvalue,true) as $model):?>
			<?php $arr['car'][$n] = $model["Модель автомобиля"];?>
			<?php $arr['engine'][$n] = explode(',',$model["Модель двигателя (через запятую)"]);?>
		<?php $n++; endforeach;?>
		<?php endif;?>
	<?php endforeach;?>
	<p><?php print JText::_('CHOOSE_THE_SOUND_TEXT');?></p>
	<?php endif;?>
	<div class="complite"><div class="alert"><?php print JText::_('ADD_TO_CART_COMPLITE');?></div></div>
	<?php if(!empty($this->item->jcfields)):?>
	<table class="addCart">
		<tbody>
			<tr>
				<td><?php print JLayoutHelper::render('joomla.content.full_image',$this->item);?></td>
				<td><h2 class="headTitleNews"><?php print $this->item->title ?></h2></td>
				<td>
					<p><?php print '€'. number_format($this->item->jcfields[15]->value,0,'',' ');?></p>
					<button class="addToCart" data-key="<?php print md5(date("Y-m-d H:i:s").''.rand(0,10000));?>"><?php print JText::_('ADD_TO_CART');?></button>
					<p class="error chooseCar"><?php print JText::_('ERROR_CHOOSE_CAR');?></p>
					<p class="error chooseEngine"><?php print JText::_('ERROR_CHOOSE_ENGINE');?></p>
					<p class="error chooseSounds"><?php print JText::_('ERROR_CHOOSE_SOUNDS');?></p>
				</td>
			</tr>
		</tbody>
	</table>
	<?php endif;?>
</div>

