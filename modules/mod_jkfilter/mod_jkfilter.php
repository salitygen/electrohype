<?php

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\Module\JkFilter\Site\Helper\JkFilterHelper;

$list = JkFilterHelper::getList($params);
require ModuleHelper::getLayoutPath('mod_jkfilter',$params->get('layout','default'));

?>