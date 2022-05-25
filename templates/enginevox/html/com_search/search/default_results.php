<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_search
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
if(isset($_GET['limit']) && $_GET['limit'] != ''){
	$limit = (int)$_GET['limit'];
}else{
	$limit = 30;
}
?>
<ul class="itemsList">
<?php foreach ($this->results as $item) : ?>
	<?php $img = json_decode($item->image);?>
	<?php if($img != '' && $img->image_intro_caption != ''):?>
	<li>
		<a title="<?php print $img->image_intro_caption;?>" href="<?php print JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language)); ?>"><img  itemprop="thumbnailUrl" src="<?php print $img->image_intro;?>" title="<?php print $img->image_intro_caption;?>" alt="<?php print $img->image_intro_alt;?>"></a>
		<a title="<?php print $img->image_intro_caption;?>" class="link" href="<?php print JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language)); ?>"><h5 itemprop="name"><?php print $img->image_intro_alt;?></h5></a>
	</li>
	<?php endif;?>
<?php endforeach; ?>
</ul>
<?php if ($this->total > $limit) : ?>
<div class="pagination search">
	<p class="counter pull-right"> <?php echo $this->pagination->getPagesCounter(); ?></p>
	<?php echo $this->pagination->getPagesLinks(); ?> 
</div>
<?php endif;?>
