<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\Module\ArticlesViewed\Site\Helper\ArticlesViewedHelper;

$session = Factory::getSession();
$params->latest = false;
$list = false;

if(!empty($session->get('latest'))){
	if(is_array($session->get('latest'))){
		if(count($session->get('latest')) > 2){
			$params->latest = $session->get('latest');
			$list = ArticlesViewedHelper::getList($params);
			require ModuleHelper::getLayoutPath('mod_articles_viewed', $params->get('layout','horizontal'));
		}
	}
}