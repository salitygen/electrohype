<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<?php if (count($this->children[$this->category->id]) > 0) : ?>
<ul class="categoryList">
	<?php foreach ($this->children[$this->category->id] as $id => $child) : ?>
		<li><a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($child->id)); ?>"><?php echo $this->escape($child->title); ?></a></li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>
