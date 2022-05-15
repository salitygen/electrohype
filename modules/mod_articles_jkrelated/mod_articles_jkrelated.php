<?php

defined('_JEXEC') or die;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\Module\ArticlesJkrelated\Site\Helper\ArticlesJkrelatedHelper;

$list = ArticlesJkrelatedHelper::getList($params);
require ModuleHelper::getLayoutPath('mod_articles_jkrelated',$params->get('layout','default'));

?>