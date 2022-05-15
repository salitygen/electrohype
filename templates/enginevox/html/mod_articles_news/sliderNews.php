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
<h3>Статьи</h3>
<div class="newsSliderContainer">
	<button class="arrow prev"></button>
	<button class="arrow next"></button>
	<ul class="newsTechnicSlider">   
		<?php foreach ($list as $item) : ?>
		<li>
			<a href="<?php print $item->link; ?>" class="img" style="background-image:url(<?php print json_decode($item->images)->image_intro;?>)"></a>
			<span><?php print date('d / m / Y', strtotime($item->modified)); ?></span>
			<a class="link" href="<?php print $item->link; ?>"><h5><?php print $item->title; ?></h5></a>
			<?php print $item->introtext; ?>
			<a href="<?php print $item->link; ?>">Читать полностью</a>
		</li>
		<?php endforeach;?>
	</ul>
</div>


















