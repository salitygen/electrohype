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
		<div id="header" class="w100">
			<div class="center">
				<a class="logo" href="/">
					<img src="/assets/img/logo.svg">
				</a>
				<?php //echo Render::view($main,'module','top_menu');?>
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