<?php
defined('_JEXEC') or die;
?>
<table class="search-results<?php echo $this->pageclass_sfx; ?>">
<?php foreach($this->results as &$result) : ?>
	<tr class="result-title" onclick="javascript:location.href='<?php echo JRoute::_($result->href);?>';" >
		<?php $image = json_decode($result->images); ?>
		<?php if($image != '' && $image->image_intro_caption != ''):?>
			<?php if ($result->href) : ?>
				<td><a class="s_image" href="<?php echo JRoute::_($result->href); ?>" target="_blank"><?php echo '<img src="'. $image->image_intro .'" alt="'. $image->image_intro_alt .'" />'; ?></a></td>
				<td><a class="s_text" href="<?php echo JRoute::_($result->href); ?>" target="_blank"><?php echo $image->image_intro_alt; ?></a></td>
			<?php endif;?>
		<?php endif;?>
	</tr>
<?php endforeach; ?>
</table>
<div class="goToFullSearch">
<button type="submit">Показать все</button>
</div>
<?php /*?>
<div class="pagination">
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>
<?php */?>