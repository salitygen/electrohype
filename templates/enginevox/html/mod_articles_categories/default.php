<?php
defined('_JEXEC') or die;
use Joomla\CMS\Helper\ModuleHelper;
if(!$list) return;
?>
<ul class="mod-articlescategories categories-module mod-list">
<?php require ModuleHelper::getLayoutPath('mod_articles_categories', $params->get('layout', 'default') . '_items'); ?>
</ul>
