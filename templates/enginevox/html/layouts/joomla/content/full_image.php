<?php
defined('JPATH_BASE') or die;
$params = $displayData->params;
?>
<?php $images = json_decode($displayData->images); ?>
<?php if (!empty($images->image_fulltext)):?>
	<img src="<?php echo htmlspecialchars($images->image_fulltext); ?>"> 
<?php endif; ?> 
