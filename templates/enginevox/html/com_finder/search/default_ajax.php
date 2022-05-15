<pre><?php
/**
 * @package     Joomla.Site
 * @subpackage  com_search
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
?>
<table class="search-results<?php echo $this->pageclass_sfx; ?>">

<?php foreach ($this->results as $result) : ?>
	<tr class="result-title" onclick="javascript:location.href='<?php echo JRoute::_($result->path);?>';" >
		<?php $image = json_decode($result->images); ?>
		<?php if($image != '' && $image->image_intro_caption != ''):?>
			<?php if ($result->path) : ?>
				<td><a class="s_image" href="<?php echo JRoute::_($result->path); ?>" target="_blank"><?php echo '<img src="'. $image->image_intro .'" alt="'. $image->image_intro_alt .'" />'; ?></a></td>
				<td><a class="s_text" href="<?php echo JRoute::_($result->path); ?>" target="_blank"><?php echo $image->image_intro_alt; ?></a></td>
			<?php endif;?>
		<?php else : ?>
			<a href="<?php echo JRoute::_($result->path); ?>" target="_blank"><?php echo $result->title; ?></a>
		<?php endif;?>
	</tr>
<?php endforeach; ?>
</table>
<div class="pagination">
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>
