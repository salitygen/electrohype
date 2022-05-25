<?php
defined('_JEXEC') or die;
?>
<h3>Статьи</h3>
<div class="newsSliderContainer">
	<button class="arrow prev"></button>
	<button class="arrow next"></button>
	<ul class="newsTechnicSlider">   
		<?php foreach ($list as &$item) : ?>
		<li>
			<a href="<?php echo $item->link; ?>" class="img" style="background-image:url(<?php echo json_decode($item->images)->image_intro;?>)"></a>
			<span><?php echo date('d / m / Y', strtotime($item->modified)); ?></span>
			<a class="link" href="<?php echo $item->link; ?>"><h5><?php echo $item->title; ?></h5></a>
			<?php echo $item->introtext; ?>
			<a href="<?php echo $item->link; ?>">Читать полностью</a>
		</li>
		<?php endforeach;?>
	</ul>
</div>


















