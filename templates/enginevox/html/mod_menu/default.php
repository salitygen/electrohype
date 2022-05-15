<?php
defined('_JEXEC') or die;
?>
<nav class="moduletable_menu">
	<?php if($module->showtitle):?>
	<div class="h4">
	<?php echo $module->title;?>
	</div>
	<?php endif;?>
	<ul class="nav menu<?php echo $class_sfx; ?> mod-list"<?php echo ($tagId = $params->get('tag_id', '')) ? ' id="'.$tagId.'"' : ''; ?>>
	<?php foreach ($list as $i => &$item):?>
		<li <?php echo (in_array($item->id, $path)) ? 'class="active"' : ''; ?>>
			<a href="<?php echo ($item->title == 'Акции') ? '/'.$item->alias : $item->link;?>" <?php echo ($item->anchor_title) ? 'title="'. $item->anchor_title .'"' : ''; ?> <?php echo ($item->anchor_css) ? 'class="'. $item->anchor_css .'"' : ''; ?>>
			<?php if($item->menu_image):?>
				<img src="<?php echo $item->menu_image;?>" alt="<?php echo $item->title;?>" <?php echo ($item->menu_image_css) ? 'class="'. $item->menu_image_css .'"' : ''; ?>>
			<?php endif;?>
			<?php echo ($item->title == 'Корзина') ? '<span class="cart_item_counter"></span>' : '<span>'. $item->title .'</span>'; ?></span>
			</a>
		</li>
	<?php endforeach;?>
	</ul>
</nav>