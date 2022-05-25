<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_search
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<?php if(isset($_GET['tmpl']) && $_GET['tmpl'] === 'component'):?>
<div class="search<?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->error == null && count($this->results) > 0) : ?>
		<?php echo $this->loadTemplate('ajax'); ?>
	<?php else : ?>
		<?php echo $this->loadTemplate('error'); ?>
	<?php endif; ?>
</div>
<?php else : ?>
<div id="content" class="w100">
	<div class="center">
		<?php if ($this->params->get('show_page_heading')) : ?>
		<h1 class="page-title">
			<?php if ($this->escape($this->params->get('page_heading'))) : ?>
				<?php echo $this->escape($this->params->get('page_heading')); ?>
			<?php else : ?>
				<?php echo $this->escape($this->params->get('page_title')); ?>
			<?php endif; ?>
		</h1>
		<?php endif; ?>
		<div id="services" class="search">
		<?php echo $this->loadTemplate('form'); ?>
		<?php if ($this->error == null && count($this->results) > 0) : ?>
			<?php echo $this->loadTemplate('results'); ?>
		<?php else : ?>
			<?php echo $this->loadTemplate('error'); ?>
		<?php endif; ?>
		</div>
	</div>
</div>
<?php endif; ?>
