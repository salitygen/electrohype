<?php
defined('_JEXEC') or die;
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
require_once("components/com_content/models/category.php");
JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_content/models');
?>
<?php foreach($list as &$item):?>
<div class="item" data-id="<?php echo $item->id;?>">
	<h1 class="headTitleNews"><?php echo $item->title ?></h1>
	<?php if(!empty($item->jcfields)):?>
	<div class="audio">
		<div class="oneTrack list" data-id="<?php echo $item->category_alias; ?>" data-name="<?php echo JText::_('AUDIO_'. str_replace('-','_',strtoupper($item->category_alias)));?>" data-item="<?php echo $item->id; ?>">
			<?php echo '<img src="'.json_decode($item->images)->image_intro.'">';?>
			<?php foreach($item->jcfields as &$inField):?>
				<?php $mp3 = json_decode($inField->rawvalue,true);?>
			<?php endforeach;?>
			<?php $i=1; foreach($mp3 as &$audio):?>
				<?php $mp3file = new MP3File($_SERVER['DOCUMENT_ROOT'].'/'.$audio["Аудиофайл"]);?>
				<?php $duration2 = $mp3file->getDuration();?>
				<?php $duration = MP3File::formatTime($duration2);?>
				<?php echo '<button class="playAudio" data-duration="'. $duration .'" data-audio="/'.$audio["Аудиофайл"].'"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 17.5 21.2" style="enable-background:new 0 0 17.5 21.2;" xml:space="preserve"><path d="M0,0l17.5,10.9L0,21.2V0z"></path><rect width="6" height="21.2"></rect><rect x="11.5" width="6" height="21.2"></rect></svg><i>'.$i++.'</i> <b>'.$audio["Название"].'</b> <span class="fullTime">'.$duration.'</span></button>';?>
			<?php endforeach;?>
			<div class="audioTrack">
				<div class="track">
					<div class="currentTime"></div>
					<?php echo "<div class=wave id='player_".$item->id."'></div>";?>
					<div class="fullTime"></div>
				</div>
				<div class="buttons">
					<button class="prew"><img src="/templates/enginevox/img/prewPlay.svg"></button>
					<button class="play"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 17.5 21.2" style="enable-background:new 0 0 17.5 21.2;" xml:space="preserve"><path d="M0,0l17.5,10.9L0,21.2V0z"></path><rect width="6" height="21.2"></rect><rect x="11.5" width="6" height="21.2"></rect></svg></button>
					<button class="next"><img src="/templates/enginevox/img/nextPlay.svg"></button>
				</div>
			</div>
		</div>
	</div>
	<?php endif;?>
	<?php echo $item->text; ?>
</div>
<?php endforeach; ?>