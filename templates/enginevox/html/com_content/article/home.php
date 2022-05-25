<?php
defined('_JEXEC') or die;
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
include $_SERVER['DOCUMENT_ROOT'].'/templates/enginevox/mp3.php';
include $_SERVER['DOCUMENT_ROOT'].'/templates/enginevox/mimeTypeBase.php';
require_once("components/com_content/models/category.php");
JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_content/models');
?>
<div id="headSlider" class="w100">
<?php if(!empty($this->item->jcfields['20']->rawvalue)):?>
	<?php $dataSlider = json_decode($this->item->jcfields['20']->rawvalue,true);?>
	<ul class="topSlider">
	<?php foreach($dataSlider as $slide):?>
		<?php if(filter_var($link = $slide['Линк'], FILTER_VALIDATE_URL)):?>
			<?php if(strpos(parse_url($link,PHP_URL_HOST), 'youtube')):?>
				<?php $youtubeId = explode('/',$link);?>
				<?php $youtubeId = end($youtubeId);?>
				<li class="youtube" data-video-id="<?php print $youtubeId;?>" style="background-image:url('<?php print $slide['Превью'];?>')"></li>
			<?php else:?>
				<?php if(strpos($link,'.')):?>
					<?php $ext = explode('.',$link);?>
					<?php $mimeArr = explode('/',$mime = getMimeType(end($ext)));?>
					<?php if($mimeArr[0] == 'image'):?>
					<li style="background-image:url('<?php print $slide['Превью'];?>')"></li>
					<?php elseif($mimeArr[0] == 'video'):?>
					<li><video width="100%" controls="controls"><source src="<?php print $link;?>" type="<?php print $mime;?>"></video></li>
					<?php else:?>
					<li><p>NOT_VALID_TYPE_FILE</p></li>
					<?php endif;?>
				<?php else:?>
					<li><p>NOT_VALID_TYPE_FILE</p></li>
				<?php endif;?>
			<?php endif;?>
		<?php else:?>
			<?php if(strpos($link,'.')):?>
				<?php $ext = explode('.',$link);?>
				<?php $mimeArr = explode('/',$mime = getMimeType(end($ext)));?>
				<?php if($mimeArr[0] == 'image'):?>
				<li style="background-image:url('<?php print $slide['Превью'];?>')"></li>
				<?php elseif($mimeArr[0] == 'video'):?>
				<li><video width="100%" controls="controls"><source src="<?php print $link;?>" type="<?php print $mime;?>"></video></li>
				<?php else:?>
				<li><p>NOT_VALID_TYPE_FILE</p></li>
				<?php endif;?>
			<?php else:?>
				<li><p>NOT_VALID_TYPE_FILE</p></li>
			<?php endif;?>
		<?php endif;?>
	<?php endforeach;?>
	</ul>
<?php endif;?>
</div>
<div class="item" data-id="<?php print $this->item->id;?>">
	<h1 class="headTitleNews"><?php print $this->item->title ?></h1>
	<?php print $this->item->text; ?>
</div>

<?php $data = false;?>
<?php if(isset($this->item->jcfields['21'])):?>
<?php $data = $this->item->jcfields['21']->rawvalue;?>
<?php elseif(!empty($this->item->jcfields['22']->rawvalue)):?>
<?php $data = $this->item->jcfields['22']->rawvalue;?>
<?php endif;?>

<?php if($data):?>
<div class="homeTracks">
	<?php foreach($data as $fields):?>
		<?php $article = new ContentModelArticle;?>
		<?php $item = $article->getItem($fields);?>
		<?php $item->jcfields = FieldsHelper::getFields('com_content.article',$item,true);?>
			<div class="item tracks" data-id="<?php print $item->id;?>">
				<?php if(!empty($item->jcfields)):?>
				<div class="audio">
					<div class="oneTrack list" data-id="<?php print $item->category_alias; ?>" data-name="<?php print JText::_('AUDIO_'. str_replace('-','_',strtoupper($item->category_alias)));?>" data-item="<?php print $item->id; ?>">
						<?php print '<img src="'.json_decode($item->images)->image_intro.'">';?>
						<?php foreach($item->jcfields as $inField):?>
							<?php $mp3 = json_decode($inField->rawvalue,true);?>
						<?php endforeach;?>
						<?php $i=1; foreach($mp3 as $audio):?>
							<?php $mp3file = new MP3File($_SERVER['DOCUMENT_ROOT'].'/'.$audio["Аудиофайл"]);?>
							<?php $duration2 = $mp3file->getDuration();?>
							<?php $duration = MP3File::formatTime($duration2);?>
							<?php print '<button class="playAudio" data-duration="'. $duration .'" data-audio="/'.$audio["Аудиофайл"].'"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 17.5 21.2" style="enable-background:new 0 0 17.5 21.2;" xml:space="preserve"><path d="M0,0l17.5,10.9L0,21.2V0z"></path><rect width="6" height="21.2"></rect><rect x="11.5" width="6" height="21.2"></rect></svg><i>'.$i++.'</i> <b>'.$audio["Название"].'</b> <span class="fullTime">'.$duration.'</span></button>';?>
						<?php endforeach;?>
						<div class="audioTrack">
							<div class="track">
								<div class="currentTime"></div>
								<?php print "<div class=wave id='player_".$item->id."'></div>";?>
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
				<h3 class="headTitleNews"><?php print $item->title ?></h3>
				<?php print $item->introtext; ?>
			</div>
	<?php endforeach;?>
<?php endif;?>
</div>