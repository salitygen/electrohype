<pre><?php
defined('_JEXEC') or die;
$app = JFactory::getApplication();
if($app->input->getCMD('option','') == 'com_users' && in_array($app->input->getCMD('view',''),array('login','reset'))){
	JFactory::getApplication()->redirect(JUri::base(), 'Данное действие запрещено', 'warning');
}



$this->_generator = '';
$doc = JFactory::getDocument();
$session = JFactory::getSession();
$sess = $session->get('cart');

$count = 0;
unset($this->_script['text/javascript']);
unset($doc->_scripts[JURI::root(true) . '/media/jui/js/jquery.min.js']);
unset($doc->_scripts[JURI::root(true) . '/media/jui/js/jquery-noconflict.js']);
unset($doc->_scripts[JURI::root(true) . '/media/jui/js/jquery-migrate.min.js']);
unset($doc->_scripts[JURI::root(true) . '/media/system/js/caption.js']);
unset($doc->_scripts[JURI::root(true) . '/media/system/js/validate.js']);
unset($doc->_scripts[JURI::root(true) . '/media/system/js/mootools-core.js']);
unset($doc->_scripts[JURI::root(true) . '/media/system/js/mootools-more.js']);
unset($doc->_scripts[JURI::root(true) . '/media/system/js/core.js']);
unset($doc->_scripts[JURI::root(true) . '/media/jui/js/bootstrap.min.js']);
use Joomla\CMS\Uri\Uri;

if(!empty($sess)){
	foreach($sess as $s){
		$count = $count + (int)strip_data2($s['count']);
	}
}

function strip_data2($text){
	$quotes = array("\x27", "\x22", "\x60", "\t", "\n", "\r", "*", "%", "<", ">", "?", "!");
	$goodquotes = array('-', '+', '#','"');
	$repquotes = array("\-", "\+", "\#","&quot;");
	$text = htmlspecialchars($text);
	$text = stripslashes($text);
	$text = trim(strip_tags($text));
	$text = str_replace($quotes,'',$text);
	$text = str_replace($repquotes,$goodquotes,$text);
	return $text;
}

$addres = 'https://yandex.ru/maps/213/moscow/?ll=37.501861%2C55.742250&mode=routes&rtext=~55.742250%2C37.501860&rtt=auto&ruri=~ymapsbm1%3A%2F%2Fgeo%3Fll%3D37.502%252C55.742%26spn%3D0.001%252C0.001%26text%3D%25D0%25A0%25D0%25BE%25D1%2581%25D1%2581%25D0%25B8%25D1%258F%252C%2520%25D0%259C%25D0%25BE%25D1%2581%25D0%25BA%25D0%25B2%25D0%25B0%252C%2520%25D0%2591%25D0%25B0%25D0%25B3%25D1%2580%25D0%25B0%25D1%2582%25D0%25B8%25D0%25BE%25D0%25BD%25D0%25BE%25D0%25B2%25D1%2581%25D0%25BA%25D0%25B8%25D0%25B9%2520%25D0%25BF%25D1%2580%25D0%25BE%25D0%25B5%25D0%25B7%25D0%25B4%252C%25207%25D0%25BA1&z=17';

?> 
<!DOCTYPE html>
<html lang="<?php print $this->language;?>" dir="<?php print $this->direction;?>">
	<head>
		<link rel="apple-touch-icon" sizes="57x57" href="/templates/<?php print $this->template;?>/favicon/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="/templates/<?php print $this->template;?>/favicon/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="/templates/<?php print $this->template;?>/favicon/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="/templates/<?php print $this->template;?>/favicon/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="/templates/<?php print $this->template;?>/favicon/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="/templates/<?php print $this->template;?>/favicon/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="/templates/<?php print $this->template;?>/favicon/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="/templates/<?php print $this->template;?>/favicon/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="/templates/<?php print $this->template;?>/favicon/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="/templates/<?php print $this->template;?>/favicon/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/templates/<?php print $this->template;?>/favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="/templates/<?php print $this->template;?>/favicon/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/templates/<?php print $this->template;?>/favicon/favicon-16x16.png">
		<link rel="manifest" href="/templates/<?php print $this->template;?>/favicon/manifest.json">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="/templates/<?php print $this->template;?>/favicon/ms-icon-144x144.png">
		<meta name="theme-color" content="#ffffff">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=3.0, user-scalable=yes"/>
		<link href="/templates/<?php print $this->template;?>/css/style.css?6236232563" rel="stylesheet">
		<link media="screen and (max-width: 1170px)" href="/templates/<?php print $this->template;?>/css/style1024.css?6236232563" rel="stylesheet">
		<link media="screen and (max-width: 890px)" href="/templates/<?php print $this->template;?>/css/style680.css?6236232563" rel="stylesheet">
		<link media="screen and (max-width: 670px)" href="/templates/<?php print $this->template;?>/css/style480.css?6236232563" rel="stylesheet">
		<link media="screen and (max-width: 520px)" href="/templates/<?php print $this->template;?>/css/style320.css?6236232563" rel="stylesheet">
		<jdoc:include type="head" />
	</head>
	<body>
		<div id="top" class="w100">
			<div class="center">
				<a class="addres button" target="_blank" rel="nofollow" href="<?php echo $addres;?>" title="г.Москва, метро Багратионовская, ТЦ «Горбушкин двор», Багратионовский проезд 7к1, павильон А1-14"><img src="/templates/<?php print $this->template;?>/img/contactGeo.svg"><span>г.Москва, ТЦ «Горбушкин двор»</span></a>
				<a target="_blank" rel="nofollow" href="https://www.instagram.com/applemagic.store/" class="insta button"><img src="/templates/<?php print $this->template;?>/img/instagram.svg"><span>AppleMagic.store</span></a>
				<div class="clock button"><img src="/templates/<?php print $this->template;?>/img/contactClock.svg"><i>Режим работы: <b>Ежедневно, с 10:00 до 20:00</b></i></div>
				<?php if($this->countModules('top_menu')): ?>
					<div class="buttons top_menu">
					<?php foreach(JModuleHelper::getModules('top_menu') as $module) : ?>
						<?php //print JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
					<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<div class="buttons contact">
					<button data-name="Обратная связь" class="message"><img src="/templates/<?php print $this->template;?>/img/contactMail.svg">Написать нам</button>
					<a href="tel:89299482244" class="phone button"><img src="/templates/<?php print $this->template;?>/img/phone.svg">+7(929)948-22-44</a>
				</div>
			</div>
		</div>
		<div id="head" class="w100">
			<div class="center">
				<a class="logo" href="/<?php print explode('-',$this->language)[0];?>/"><img src="/templates/<?php print $this->template;?>/img/logo.svg"></a>
				<?php if($this->countModules('head_menu')): ?>
				<div class="buttons">
					<?php foreach(JModuleHelper::getModules('head_menu') as $module) : ?>
						<?php print JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
				<form class="search" method="GET" action="/search/">
					<input type="text" name="searchword" placeholder="Поиск по сайту">
				</form>
				<div class="buttons user">
					<?php foreach(JModuleHelper::getModules('user_menu') as $module) : ?>
						<?php print JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php if($this->countModules('breadcrumbs_top')): ?>
		<div id="breadcrumbs" class="w100">
			<div class="center">
			<?php foreach(JModuleHelper::getModules('breadcrumbs_top') as $module) : ?>
				<?php print JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
			<?php endforeach; ?>
			</div>
		</div>
		<?php endif; ?>
		<div id="message" class="w100">
			<div class="center">
			<jdoc:include type="message" />
			</div>
		</div>
		<div id="content" class="w100">
			<div class="center">
				<jdoc:include type="component" />
			</div>
		</div>
		<?php /*if($this->countModules('blog')): ?>
			<div id="home_player" class="w100">
				<div class="center">
				<?php foreach(JModuleHelper::getModules('blog') as $module) : ?>
					<?php print JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
				<?php endforeach; ?>
				</div>
			</div>
		<?php endif; */?>
		<div id="footer" class="w100">
			<div class="center">
				<div class="left">
					<a class="logo down" href="/"><img src="/templates/<?php print $this->template;?>/img/logo-b.svg"></a>
					<ul>
						<li>
							<a href="tel:89299482244" class="phone">
								<img src="/templates/<?php print $this->template;?>/img/phone.svg">
								<span>+7(929)948-22-44</span>
							</a>
						</li>
						<li>
							<a class="addres" target="_blank" rel="nofollow" href="<?php echo $addres;?>" title="г.Москва, метро Багратионовская, ТЦ «Горбушкин двор», Багратионовский проезд 7к1, павильон А1-14">
								<img src="/templates/<?php print $this->template;?>/img/contactGeo.svg">
								<span>г.Москва, ТЦ «Горбушкин двор»</span>
							</a>
						</li>
						<li>
							<a target="_blank" rel="nofollow" href="https://www.instagram.com/applemagic.store/" class="insta"><img src="/templates/<?php print $this->template;?>/img/instagram.svg">
								<span>AppleMagic.store</span>
							</a>
						</li>
						<li class="clock">
							<img src="/templates/<?php print $this->template;?>/img/contactClock.svg">
							<i>Работаем ежедневно, с 10:00 до 20:00</i>
						</li>
					</ul>
					<div id="route">
						<div class="h3">Проложить маршрут</div>
						<a rel="nofollow" target="_blank" href="" class="yandex"><img src="/templates/<?php print $this->template;?>/img/yandex-maps.svg" alt="Проложить маршрут в Яндекс Картах">Яндекс Карты</a>
						<a rel="nofollow" target="_blank" href="" class="google"><img src="/templates/<?php print $this->template;?>/img/google-maps.svg" alt="Проложить маршрут в Google Maps">Google Maps</a>
					</div>
				</div>
				<div class="right">
					<?php if($this->countModules('down_menu')): ?>
						<div class="footerMenu">
						<?php foreach(JModuleHelper::getModules('down_menu') as $module) : ?>
							<?php print JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
						<?php endforeach; ?>
						</div>
					<?php endif; ?>
					<div class="card">
						<div class="h3">Мы принимаем к оплате</div>
						<img src="/templates/<?php print $this->template;?>/img/cards-white.svg" alt="Мы принимаем оплату картами">
					</div>
				</div>
				<p class="copyright">Компания Electrohype &copy; 2009 - <?php print date('Y');?>. Администрация Сайта не несет ответственности за размещаемые Пользователями материалы (в т.ч. информацию и изображения), их содержание и качество.</p>
			</div>
		</div>
		<?php /*if(!isset($_COOKIE['cookie']) || $_COOKIE['cookie'] != 'yes'):?>
		<div id="cookies" class="w100">
			<p><?php print JText::_('COOKIES_POLICY');?></p>
		</div>
		<?php endif;*/?>
		<form class="token">
		<?php echo JHtml::_('form.token');?>
		<input type="hidden" class="return" value="<?php echo base64_encode(Uri::getInstance()->toString(array('scheme','host')).'/profile/');?>">
		</form>
		<link href="/templates/<?php print $this->template;?>/css/slick.css?6236232563" rel="stylesheet">
		<link href="/templates/<?php print $this->template;?>/css/star-rating-svg.css?6236232563" rel="stylesheet">
		<link href="/templates/<?php print $this->template;?>/css/jquery-ui.css?6236232563" rel="stylesheet">
		<link href="/templates/<?php print $this->template;?>/css/helveticaneue.css?6236232563" rel="stylesheet" >
		<link href="/templates/<?php print $this->template;?>/css/prata.css?6236232563" rel="stylesheet" >
		<link href="/templates/<?php print $this->template;?>/css/jquery.fancybox.min.css?6236232563" rel="stylesheet" >
		<script src="/templates/<?php print $this->template;?>/js/jquery-3.5.1.min.js?6236232563"></script>
		<script src="/templates/<?php print $this->template;?>/js/jquery.fancybox.min.js?6236232563"></script>
		<script src="/templates/<?php print $this->template;?>/js/jquery.ui.touch-punch.min.js?6236232563"></script>
		<script src="/templates/<?php print $this->template;?>/js/jquery-ui.min.js?6236232563"></script>
		<script src="/templates/<?php print $this->template;?>/js/jquery.maskedinput.min.js?6236232563"></script>
		<script src="/templates/<?php print $this->template;?>/js/jquery.star-rating-svg.js?6236232563"></script> 
		<script src="/templates/<?php print $this->template;?>/js/slick.min.js?6236232563"></script>
		<script src="/templates/<?php print $this->template;?>/js/main.js?6236232563"></script>
	</body>
</html>