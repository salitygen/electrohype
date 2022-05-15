<?php
defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper;

$input  = $app->input;
$option = $input->getCmd('option');
$view   = $input->getCmd('view');
$id     = $input->getInt('id');

?>
<nav class="moduletable_menu catalog">
	<?php if($module->showtitle):?>
	<div class="h4">
	<?php echo $module->title;?>
	</div>
	<?php endif;?>
	<ul class="nav menu mod-list">
		<?php foreach($list as $cat):?>
		<li<?php if ($id == $cat->id && in_array($view, array('category', 'categories')) && $option == 'com_content') echo ' class="active"'; ?>>
			<a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($cat->id)); ?>">
				<span><?php echo $cat->title;?></span>
			</a>
		</li>
		<?php endforeach;?>
		<li>
			<a href="/action">
				<span>Товары со скидкой</span>
			</a>
		</li>
	</ul>
</nav>