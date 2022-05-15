<?php

defined('_JEXEC') or die;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\Module\ArticlesJksimilar\Site\Helper\ArticlesJksimilarHelper;

$list = ArticlesJksimilarHelper::getList($params);
require ModuleHelper::getLayoutPath('mod_articles_jksimilar',$params->get('layout','default'));

?>