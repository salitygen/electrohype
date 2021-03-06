<?php
defined('_JEXEC') or die;
$app = &JFactory::getApplication();
$view = $app->input->getVar('view');
$option = $app->input->getVar('option');
$id = $app->input->getInt('id');
if($option == 'com_users' && in_array($view,array('login','reset'))){
	$app->redirect(JUri::base(), 'Данное действие запрещено', 'warning');
}
$this->_generator = '';
$doc = &JFactory::getDocument();

if(count($a = &JModuleHelper::getModules('filtres')) == 0)	  $a = false;
if(count($b = &JModuleHelper::getModules('cat_menu')) == 0)	  $b = false;
if(count($c = &JModuleHelper::getModules('sets')) == 0)		  $c = false;
if(count($d = &JModuleHelper::getModules('latest')) == 0)	  $d = false;
if(count($e = &JModuleHelper::getModules('breadcrumbs')) == 0)$e = false;
if(count($f = &JModuleHelper::getModules('head_menu')) == 0)  $f = false;
if(count($g = &JModuleHelper::getModules('user_menu')) == 0)  $g = false;
if(count($h = &JModuleHelper::getModules('top_menu')) == 0)   $h = false;
if(count($i = &JModuleHelper::getModules('brand_menu')) == 0) $i = false;
if(count($j = &JModuleHelper::getModules('down_menu')) == 0)  $j = false;
if(count($k = &JModuleHelper::getModules('slider')) == 0)	  $k = false;
if(count($l = &JModuleHelper::getModules('similar')) == 0)	  $l = false;
if(count($m = &JModuleHelper::getModules('reviews')) == 0)	  $m = false;
if(count($n = &JModuleHelper::getModules('selection')) == 0)  $n = false;
if(count($o = &JModuleHelper::getModules('related')) == 0)    $o = false;

unset($doc->_scripts[JURI::root(true) . '/media/jui/js/jquery.min.js']);
unset($doc->_scripts[JURI::root(true) . '/media/jui/js/jquery-noconflict.js']);
unset($doc->_scripts[JURI::root(true) . '/media/jui/js/jquery-migrate.min.js']);
unset($doc->_scripts[JURI::root(true) . '/media/system/js/caption.js']);
unset($doc->_scripts[JURI::root(true) . '/media/system/js/validate.js']);
unset($doc->_scripts[JURI::root(true) . '/media/system/js/mootools-core.js']);
unset($doc->_scripts[JURI::root(true) . '/media/system/js/mootools-more.js']);
unset($doc->_scripts[JURI::root(true) . '/media/system/js/core.js']);
unset($doc->_scripts[JURI::root(true) . '/media/jui/js/bootstrap.min.js']);
unset($this->_script['text/javascript']);

$addres = 'https://yandex.ru/maps/213/moscow/?ll=37.501861%2C55.742250&mode=routes&rtext=~55.742250%2C37.501860&rtt=auto&ruri=~ymapsbm1%3A%2F%2Fgeo%3Fll%3D37.502%252C55.742%26spn%3D0.001%252C0.001%26text%3D%25D0%25A0%25D0%25BE%25D1%2581%25D1%2581%25D0%25B8%25D1%258F%252C%2520%25D0%259C%25D0%25BE%25D1%2581%25D0%25BA%25D0%25B2%25D0%25B0%252C%2520%25D0%2591%25D0%25B0%25D0%25B3%25D1%2580%25D0%25B0%25D1%2582%25D0%25B8%25D0%25BE%25D0%25BD%25D0%25BE%25D0%25B2%25D1%2581%25D0%25BA%25D0%25B8%25D0%25B9%2520%25D0%25BF%25D1%2580%25D0%25BE%25D0%25B5%25D0%25B7%25D0%25B4%252C%25207%25D0%25BA1&z=17';
?>
<head>
	<link rel="apple-touch-icon" sizes="57x57" href="/templates/<?php echo $this->template;?>/favicon/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="/templates/<?php echo $this->template;?>/favicon/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/templates/<?php echo $this->template;?>/favicon/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/templates/<?php echo $this->template;?>/favicon/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/templates/<?php echo $this->template;?>/favicon/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/templates/<?php echo $this->template;?>/favicon/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="/templates/<?php echo $this->template;?>/favicon/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/templates/<?php echo $this->template;?>/favicon/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="/templates/<?php echo $this->template;?>/favicon/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="/templates/<?php echo $this->template;?>/favicon/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/templates/<?php echo $this->template;?>/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="/templates/<?php echo $this->template;?>/favicon/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/templates/<?php echo $this->template;?>/favicon/favicon-16x16.png">
	<link rel="manifest" href="/templates/<?php echo $this->template;?>/favicon/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/templates/<?php echo $this->template;?>/favicon/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=3.0, user-scalable=yes"/>
	<link href="/templates/<?php echo $this->template;?>/css/style.css?6236232563" rel="stylesheet">
	<link media="screen and (max-width: 1279px)" href="/templates/<?php echo $this->template;?>/css/style1024.css?6236232563" rel="stylesheet">
	<link media="screen and (max-width: 999px)" href="/templates/<?php echo $this->template;?>/css/style680.css?6236232563" rel="stylesheet">
	<link media="screen and (max-width: 671px)" href="/templates/<?php echo $this->template;?>/css/style480.css?6236232563" rel="stylesheet">
	<link media="screen and (max-width: 590px)" href="/templates/<?php echo $this->template;?>/css/style320.css?6236232563" rel="stylesheet">
	<jdoc:include type="head" />
</head>
