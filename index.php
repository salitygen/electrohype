<?php
	define('EXEC',1);
	session_start();
	include_once($_SERVER['DOCUMENT_ROOT'].'/config.php');
	include_once($main->root.'/models/App.php');
?>
<!DOCTYPE html>
<html lang="ru-RU">
	<head>
		<title><?php echo $main->title;?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=3.0, user-scalable=yes">
		<link href="/assets/css/style.css?6236232563" rel="stylesheet">
		<link media="screen and (max-width: 1170px)" href="/assets/css/style1024.css?6236232563" rel="stylesheet">
		<link media="screen and (max-width: 890px)" href="/assets/css/style680.css?6236232563" rel="stylesheet">
		<link media="screen and (max-width: 670px)" href="/assets/css/style480.css?6236232563" rel="stylesheet">
		<link media="screen and (max-width: 520px)" href="/assets/css/style320.css?6236232563" rel="stylesheet">
	</head>
	<body>
		<div id="top" class="w100">
			<div class="center">
				<a class="addres button" target="_blank" rel="nofollow" href="https://yandex.ru/maps/213/moscow/?ll=37.501861%2C55.742250&amp;mode=routes&amp;rtext=~55.742250%2C37.501860&amp;rtt=auto&amp;ruri=~ymapsbm1%3A%2F%2Fgeo%3Fll%3D37.502%252C55.742%26spn%3D0.001%252C0.001%26text%3D%25D0%25A0%25D0%25BE%25D1%2581%25D1%2581%25D0%25B8%25D1%258F%252C%2520%25D0%259C%25D0%25BE%25D1%2581%25D0%25BA%25D0%25B2%25D0%25B0%252C%2520%25D0%2591%25D0%25B0%25D0%25B3%25D1%2580%25D0%25B0%25D1%2582%25D0%25B8%25D0%25BE%25D0%25BD%25D0%25BE%25D0%25B2%25D1%2581%25D0%25BA%25D0%25B8%25D0%25B9%2520%25D0%25BF%25D1%2580%25D0%25BE%25D0%25B5%25D0%25B7%25D0%25B4%252C%25207%25D0%25BA1&amp;z=17" title="г.Москва, метро Багратионовская, ТЦ «Горбушкин двор», Багратионовский проезд 7к1, павильон А1-14"><img src="/assets/img/contactGeo.svg">
					<span>г.Москва, ТЦ «Горбушкин двор»</span>
				</a>
				<a target="_blank" rel="nofollow" href="https://www.instagram.com/applemagic.store/" class="insta button">
					<img src="/assets/img/instagram.svg">
					<span>AppleMagic.store</span>
				</a>
				<div class="clock button">
					<img src="/assets/img/contactClock.svg">
					<i>Режим работы: <b>Ежедневно, с 10:00 до 20:00</b></i>
				</div>
				<div class="buttons contact">
					<button data-name="Обратная связь" class="message"><img src="/assets/img/contactMail.svg">Написать нам</button>
					<a href="tel:89299482244" class="phone button"><img src="/assets/img/phone.svg">+7(929)948-22-44</a>
				</div>
			</div>
		</div>
		<div id="head" class="w100">
			<div class="center">
				<a class="logo" href="/ru/"><img src="/assets/img/logo.svg"></a>
				<div class="buttons">
					<nav class="moduletable_menu">
						<ul class="nav menu mod-list">
							<li class="active">
								<a href="/" class="button catalog">
									<span>Каталог</span>
								</a>
							</li>
						</ul>
					</nav>									
				</div>
				<form class="search" method="GET" action="/search/">
					<input type="text" name="searchword" placeholder="Поиск по сайту">
					<div class="ajaxResults"></div>
					<button type="button" class="close"></button>
				</form>
				<div class="buttons user">
					<nav class="moduletable_menu">
						<ul class="nav menu mod-list">
							<li>
								<a href="/action" class="button">
									<img src="/assets/img/sale.svg" alt="Акции">
									<span>Акции</span>
								</a>
							</li>
								<li>
								<a href="/compare" class="button">
									<img src="/assets/img/compare.svg" alt="Сравнить">
									<span>Сравнить</span>
								</a>
							</li>
							<li>
								<a href="/favorites" class="button">
									<img src="/assets/img/heart.svg" alt="Избранное">
									<span>Избранное</span>
								</a>
							</li>
							<li>
								<a href="/" class="button login">
									<img src="/assets/img/profile.svg" alt="Профиль">
									<span>Профиль</span>
								</a>
							</li>
							<li>
								<a href="/cart" class="cart btn">
									<img src="/assets/img/cart.svg" alt="Корзина">
									<span class="cart_item_counter">0</span>
								</a>
							</li>
						</ul>
					</nav>
				</div>
			</div>
		</div>
		<div id="content" class="w100">
			<div class="center">
				<div class="left">
					<?php //echo Render::view($main,'module','left_menu');?>
					<?php //echo Render::view($main,'module','copyright');?>
				</div>
				<div class="right">
					<?php //echo Render::view($main,'page',$view);?>
				</div>
			</div>
		</div>
		<div id="footer" class="w100">
			<div class="center">
				
			</div>
		</div>
		<link href="/assets/css/slick.css?6236232563" rel="stylesheet">
		<link href="/assets/css/helveticaneue.css?6236232563" rel="stylesheet">
		<link href="/assets/css/jquery.fancybox.min.css?6236232563" rel="stylesheet">
		<script src="/assets/js/jquery-3.5.1.min.js?6236232563"></script>
		<script src="/assets/js/jquery.fancybox.min.js?6236232563"></script>
		<script src="/assets/js/jquery.ui.touch-punch.min.js?6236232563"></script>
		<script src="/assets/js/jquery.maskedinput.min.js?6236232563"></script>
		<script src="/assets/js/slick.min.js?6236232563"></script>
		<script src="/assets/js/main.js?6236232563"></script>
	</body>
</html>