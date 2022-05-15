<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_finder
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<ul class="itemsList">
	<?php $this->baseUrl = JUri::getInstance()->toString(array('scheme', 'host', 'port')); ?>
	<?php foreach ($this->results as $result) : ?>
		<?php $this->result = &$result; ?>
		<?php $layout = $this->getLayoutFile($this->result->layout); ?>
		<?php echo $this->loadTemplate($layout); ?>
	<?php endforeach; ?>
</ul>
<div class="pagination search">
	<p class="counter pull-right"> <?php echo $this->pagination->getPagesCounter(); ?></p>
	<?php echo $this->pagination->getPagesLinks(); ?> 
</div>


