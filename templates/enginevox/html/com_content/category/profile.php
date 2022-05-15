<?php
defined('_JEXEC') or die;
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
use Joomla\CMS\Factory;
$user = Factory::getUser();
?>
<div id="ajaxUpdate" class="wt profile">
	<div class="blog<?php echo $this->pageclass_sfx; ?>" itemscope itemtype="https://schema.org/Blog">
		<div class="left">
		<?php if(count(JModuleHelper::getModules('left_user_menu'))):?>
			<?php foreach(JModuleHelper::getModules('left_user_menu') as $module) : ?>
				<?php print JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
			<?php endforeach; ?>
		<?php endif; ?>
		</div>
		<div class="right">
		</div>
	</div>
</div>