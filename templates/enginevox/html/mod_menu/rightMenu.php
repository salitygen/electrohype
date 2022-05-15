<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
defined('_JEXEC') or die;

?>
<i class="close"></i>
<table>
	<!--tr>
		<td colspan="2">
			<form class="search" action="<?php echo JRoute::_('index.php?option=com_search'); ?>" method="post">
				<input type="text" name="searchword" placeholder="Что нужно починить?" required="required" autocomplete="off" x-webkit-speech />
				<input type="hidden" name="task" value="search" />
				<img src="/templates/itmaster/img/searchTechnicForm.svg">
			</form>
		</td>
	</tr-->
	<tr>
		<td colspan="2"><h3>Ремонт</h3></td>
	</tr>
	<?php foreach ($list as $i => &$item): ?>
	<tr>
		<td class="MenuTdLeft"><a href="<?php print $item->link;?>"><img src="<?php print $item->params->get('menu_image') ?>"></a></td>  
		<td class="MenuTdRight"><a href="<?php print $item->link;?>"><?php print $item->title?></a></td>
	</tr>
	<?php endforeach; ?>
</table>