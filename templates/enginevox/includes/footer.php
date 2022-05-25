<?php
defined('_JEXEC') or die;
?>
<div id="footer" class="w100">
	<div class="center">
		<div class="left">
			<a class="logo down" href="/"><img src="/templates/<?php echo $this->template;?>/img/logo-b.svg"></a>
			<ul>
				<li>
					<a href="tel:89299482244" class="phone">
						<img src="/templates/<?php echo $this->template;?>/img/phone.svg">
						<span>+7(929)948-22-44</span>
					</a>
				</li>
				<li>
					<a class="addres" target="_blank" rel="nofollow" href="<?php echo $addres;?>" title="г.Москва, метро Багратионовская, ТЦ «Горбушкин двор», Багратионовский проезд 7к1, павильон А1-14">
						<img src="/templates/<?php echo $this->template;?>/img/contactGeo.svg">
						<span>г.Москва, ТЦ «Горбушкин двор»</span>
					</a>
				</li>
				<li>
					<a target="_blank" rel="nofollow" href="https://www.instagram.com/applemagic.store/" class="insta"><img src="/templates/<?php echo $this->template;?>/img/instagram.svg">
						<span>AppleMagic.store</span>
					</a>
				</li>
				<li class="clock">
					<img src="/templates/<?php echo $this->template;?>/img/contactClock.svg">
					<i>Работаем ежедневно, с 10:00 до 20:00</i>
				</li>
			</ul>
			<div id="route">
				<div class="h3">Проложить маршрут</div>
				<a rel="nofollow" target="_blank" href="" class="yandex"><img src="/templates/<?php echo $this->template;?>/img/yandex-maps.svg" alt="Проложить маршрут в Яндекс Картах">Яндекс Карты</a>
				<a rel="nofollow" target="_blank" href="" class="google"><img src="/templates/<?php echo $this->template;?>/img/google-maps.svg" alt="Проложить маршрут в Google Maps">Google Maps</a>
			</div>
		</div>
		<div class="right">
			<?php if($j): ?>
				<div class="footerMenu">
				<?php foreach($j as &$module) : ?>
					<?php echo JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
				<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<div class="card">
				<div class="h3">Мы принимаем к оплате</div>
				<img src="/templates/<?php echo $this->template;?>/img/cards-white.svg" alt="Мы принимаем оплату картами">
			</div>
		</div>
		<p class="copyright">Компания Electrohype &copy; 2009 - <?php echo date('Y');?>. Администрация Сайта не несет ответственности за размещаемые Пользователями материалы (в т.ч. информацию и изображения), их содержание и качество.</p>
	</div>
</div>
<?php /*if(!isset($_COOKIE['cookie']) || $_COOKIE['cookie'] != 'yes'):?>
<div id="cookies" class="w100">
	<p><?php echo JText::_('COOKIES_POLICY');?></p>
</div>
<?php endif;*/?>
<form class="token">
<?php echo JHtml::_('form.token');?>
<input type="hidden" class="return" value="<?php echo base64_encode(JUri::getInstance()->toString(array('scheme','host')).'/profile/');?>">
</form>
<link href="/templates/<?php echo $this->template;?>/css/slick.css?6236232563" rel="stylesheet">
<link href="/templates/<?php echo $this->template;?>/css/star-rating-svg.css?6236232563" rel="stylesheet">
<link href="/templates/<?php echo $this->template;?>/css/jquery-ui.css?6236232563" rel="stylesheet">
<link href="/templates/<?php echo $this->template;?>/css/helveticaneue.css?6236232563" rel="stylesheet" >
<link href="/templates/<?php echo $this->template;?>/css/prata.css?6236232563" rel="stylesheet" >
<link href="/templates/<?php echo $this->template;?>/css/jquery.fancybox.min.css?6236232563" rel="stylesheet" >
<script src="/templates/<?php echo $this->template;?>/js/jquery-3.5.1.min.js?6236232563"></script>
<script src="/templates/<?php echo $this->template;?>/js/jquery.fancybox.min.js?6236232563"></script>
<script src="/templates/<?php echo $this->template;?>/js/jquery.ui.touch-punch.min.js?6236232563"></script>
<script src="/templates/<?php echo $this->template;?>/js/jquery-ui.min.js?6236232563"></script>
<script src="/templates/<?php echo $this->template;?>/js/jquery.maskedinput.min.js?6236232563"></script>
<script src="/templates/<?php echo $this->template;?>/js/jquery.star-rating-svg.js?6236232563"></script> 
<script src="/templates/<?php echo $this->template;?>/js/slick.min.js?6236232563"></script>
<script src="/templates/<?php echo $this->template;?>/js/main.js?6236232563"></script>