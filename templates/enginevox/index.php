<?php
defined('_JEXEC') or die;
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language;?>" dir="<?php echo $this->direction;?>">
	<?php include JPATH_ROOT.'/templates/'. $this->template .'/includes/header.php';?>
	<body>
		<div id="top" class="w100">
			<div class="center">
				<a class="addres button" target="_blank" rel="nofollow" href="<?php echo $addres;?>" title="г.Москва, метро Багратионовская, ТЦ «Горбушкин двор», Багратионовский проезд 7к1, павильон А1-14"><img src="/templates/<?php echo $this->template;?>/img/contactGeo.svg"><span>г.Москва, ТЦ «Горбушкин двор»</span></a>
				<a target="_blank" rel="nofollow" href="https://www.instagram.com/applemagic.store/" class="insta button"><img src="/templates/<?php echo $this->template;?>/img/instagram.svg"><span>AppleMagic.store</span></a>
				<div class="clock button"><img src="/templates/<?php echo $this->template;?>/img/contactClock.svg"><i>Режим работы: <b>Ежедневно, с 10:00 до 20:00</b></i></div>
				<?php if($h): ?>
					<div class="buttons top_menu">
					<?php foreach($h as &$module):?>
						<?php //echo JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
					<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<div class="buttons contact">
					<button data-name="Обратная связь" class="message"><img src="/templates/<?php echo $this->template;?>/img/contactMail.svg">Написать нам</button>
					<a href="tel:89299482244" class="phone button"><img src="/templates/<?php echo $this->template;?>/img/phone.svg">+7(929)948-22-44</a>
				</div>
			</div>
		</div>
		<div id="head" class="w100">
			<div class="center">
				<a class="logo" href="/<?php echo explode('-',$this->language)[0];?>/"><img src="/templates/<?php echo $this->template;?>/img/logo.svg"></a>
				<?php if($f): ?>
				<div class="buttons">
					<?php foreach($f as &$module) : ?>
						<?php echo JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
				<form class="search" method="GET" action="/search/">
					<input type="text" name="searchword" placeholder="Поиск по сайту">
				</form>
				<?php if($g): ?>
				<div class="buttons user">
					<?php foreach($g as &$module) : ?>
						<?php echo JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<?php if($e): ?>
		<div id="breadcrumbs" class="w100">
			<div class="center">
			<?php foreach($e as &$module):?>
				<?php echo JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
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
				<?php if($view === 'article' 
				|| ($view === 'category' && $id === 52) 
				|| ($view === 'category' && $id === 20)
				|| ($view === 'category' && $id === 69)):?>
					<jdoc:include type="component" />
				<?php endif; ?>
				<?php if($o):?>
					<div class="mobile_related">
					<?php foreach($o as &$module) : ?>
						<?php echo JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
					<?php endforeach; ?>
					</div>
				<?php endif;?>
				<?php if($l):?>
					<?php foreach($l as &$module) : ?>
						<?php echo JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
					<?php endforeach; ?>
				<?php endif;?>
				<?php if($a || $b):?>
				<div class="left">
					<?php if($a && $view !== 'article'):?>
						<?php foreach($a as &$module):?>
							<?php echo JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
						<?php endforeach; ?>
					<?php endif; ?>
					<?php if($b):?>
						<?php foreach($b as &$module):?>
							<?php echo JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
				<?php endif; ?>
				<?php if($a || $b):?>
				<div class="right">
					<?php if($m):?>
						<?php foreach($m as &$module):?>
							<?php echo JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
						<?php endforeach; ?>
					<?php endif; ?>
					<?php if($k):?>
						<?php foreach($k as &$module) : ?>
							<?php echo JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
						<?php endforeach; ?>
					<?php endif; ?>
					<?php if($view !== 'article'):?>
					<?php if(($view === 'category' && $id !== 24) || $option == 'com_tags'):?>
					<h1 class="hr catalog"><?php echo $app->getParams()->get('page_heading');?></h1>
					<?php endif; ?>
					<?php if($n && $view !== 'article'):?>
						<?php foreach($n as &$module):?>
							<?php echo JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
						<?php endforeach; ?>
					<?php endif; ?>
					<?php if($i && $view !== 'article'):?>
						<?php foreach(JModuleHelper::getModules('brand_menu') as $m) : ?>
							<?php echo JModuleHelper::renderModule($m, array('style' => 'xhtml')); ?>
						<?php endforeach; ?>
					<?php endif; ?>
					<jdoc:include type="component" />
					<?php endif; ?>
					<?php if($c):?>
						<?php foreach($c as &$module) : ?>
							<?php echo JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
						<?php endforeach; ?>
					<?php endif; ?>
					<?php if($d):?>
						<?php foreach($d as &$module) : ?>
							<?php echo JModuleHelper::renderModule($module, array('style' => 'xhtml')); ?>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<?php include JPATH_ROOT.'/templates/'. $this->template .'/includes/footer.php';?>
	</body>
</html>