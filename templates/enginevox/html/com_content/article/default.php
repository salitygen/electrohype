<?php
defined('_JEXEC') or die;
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
?>
<div class="item">
	<div class="overflowBox">
		<div class="imgScrollSlider">
			<ul class="imgSlider">
			<?php foreach(json_decode($this->item->jcfields[20]->rawvalue) as $slide):?>
				<li><a data-fancybox="gallery" href="<?php print $slide->field101->imagefile;?>"><img src="<?php print $slide->field100->imagefile;?>"></a></li>
			<?php endforeach; ?> 
			</ul> 
		</div>
	</div>
	<div class="image">
		<?php print '<a data-fancybox="gallery" href="'.json_decode($this->item->images)->image_fulltext.'"><img src="'.json_decode($this->item->images)->image_fulltext.'"></a>';?>
	</div>
	<div class="cartbox">
		<h1 class="headTitleNews hr"><?php print $this->item->title ?></h1>
		<ul>
			<?php foreach($this->item->jcfields as $field):?>
			<?php if($field->id == 14 || $field->id == 23 || $field->id == 24 || $field->id == 25 || $field->id == 26 || $field->id == 27 || $field->id == 28 || $field->id == 29 || $field->id == 30 || $field->id == 31):?>
			<?php if(!empty($field->value)):?>
			<li><b><?php print $field->title;?></b> <span><?php print $field->value;?></span></li>
			<?php endif;?>
			<?php endif;?>
			<?php endforeach; ?> 
		</ul>
		<div class="cart">
			<div class="price"><b><?php print number_format($this->item->jcfields[15]->value,0,'',' ');?> руб.</b></div>
			<button class="addToCart" data-id="<?php print $this->item->id;?>" data-key="<?php print md5(date("Y-m-d H:i:s").''.rand(0,10000));?>"><?php print JText::_('ADD_TO_CART');?></button>
			<div class="controls"><button class="plus">&nbsp;</button><input type="number" value="1" name="count"><button class="minus">&nbsp;</button></div>
		</div>
	</div>
</div>
<div class="text-block tabs">
	<input type="radio" name="tab-btn" id="tab-btn-1" value="" checked>
	<label for="tab-btn-1">Характеристики</label>
	<input type="radio" name="tab-btn" id="tab-btn-2" value="">
	<label for="tab-btn-2">Описание</label>
	<input type="radio" name="tab-btn" id="tab-btn-3" value="">
	<label for="tab-btn-3">Отзывы</label>
	<div id="content-1" class="cartbox">
		<ul>
		<?php foreach($this->item->jcfields as $field):
			if($field->title != 'Слайдер' 
			&& $field->title != 'Цена' 
			&& $field->title != 'Артикул' 
			&& $field->title != 'Старая цена' 
			&& $field->title != 'Наличие' 
			&& $field->title != 'Код производителя' 
			&& $field->title != 'Цена в магазине'): ?>
			<?php if(!empty($field->value)):?>
				<li><b><?php print $field->title;?></b> <span><?php print $field->value;?></span></li>
			<?php endif;?>
			<?php endif;?>
		<?php endforeach; ?> 
		</ul>
	</div>
	<div id="content-2">
		<?php print $this->item->text; ?>
	</div>
	<div id="content-3">

	</div>
</div>
<div class="complite"><div class="alert"><p><?php print JText::_('ADD_TO_CART_COMPLITE');?></p> <a class="goOrder" href="/ru/cart/">Перейти к оформлению заказа</a> <button type="button" class="remove"></button></div></div>