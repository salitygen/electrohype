<?php
defined('_JEXEC') or die;
if (!$list) return;
return;
?>
<nav class="moduletable_list_cat selection">
	<ul>
		<?php foreach($list as &$label) : ?>
			<label class="select<?php echo ($label['selected']) ? ' selected' : ''?>">
				<span><?php echo $label['title'];?></span>
				<?php foreach($label['params'] as $option):?>
				<input type="checkbox" name="filter[<?php echo $option['key'];?>][]" value="<?php echo $option['value'];?>" <?php echo ($label['selected']) ? 'checked' : ''?>>
				<?php endforeach; ?>
			</label>
		<?php endforeach; ?>
	</ul>
</nav>