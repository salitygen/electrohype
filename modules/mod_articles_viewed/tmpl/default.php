<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_viewed
 *
 * @copyright   (C) 2006 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;

if (!$list)
{
	return;
}

?>
<div class="mod-articlesviewed viewedflash">
	<?php foreach ($list as $item) : ?>
		<div class="mod-articlesviewed__item" itemscope itemtype="https://schema.org/Article">
			<?php require ModuleHelper::getLayoutPath('mod_articles_viewed', '_item'); ?>
		</div>
	<?php endforeach; ?>
</div>
