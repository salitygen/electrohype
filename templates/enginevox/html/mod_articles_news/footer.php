<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_news
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

?>
<h3 class="hr">Статьи</h3>
<ul class="items">
<?php foreach ($list as $item) : ?>
	<li class="" itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
		<a href="<?php print JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language)); ?>" class="img" style="background-image:url(<?php print json_decode($item->images)->image_intro;?>)"></a>
		<h3 itemprop="name">
			<a class="link" href="<?php print JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language)); ?>"><?php print $item->title; ?></a>
		</h3>
		<p class="date"><?php print date("d.m.Y h:i:s", strtotime($item->created));?></p>
		<div class="introtext">
			<?php print $item->introtext;?> <a class="butt" href="<?php print JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language)); ?>"><?php print JText::_('MORE');?></a>
		</div>
	</li>
<?php endforeach; ?>
</ul>






















