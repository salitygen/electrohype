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
<div class="sets">
	<ul class="setsList">
		<?php foreach($list as $cat):?>
		<?php $img = json_decode($cat->params);?>
		<li<?php if ($id == $cat->id && in_array($view, array('category', 'categories')) && $option == 'com_content') echo ' class="active"'; ?>>
			<a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($cat->id)); ?>" style="background-image:url(<?php print $img->image;?>);">
				<span><?php print $img->image_alt;?></span>
			</a>
		</li>
		<?php endforeach;?>
	</ul>
</div>
