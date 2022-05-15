<?php

defined('_JEXEC') or die;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\Module\ArticlesJkselection\Site\Helper\ArticlesJkselectionHelper;

$list = ArticlesJkselectionHelper::getList($params);
require ModuleHelper::getLayoutPath('mod_articles_jkselection',$params->get('layout','default'));

?>