<?php

defined('_JEXEC') or die('Restricted access');

print '<h1>'.$this->title.'</h1>';
print '<div class="reviews">';

foreach($this->data->reviews as $i => $review){
	
	$date = JHtml::_('date', $review->public_date, JText::_('DATE_FORMAT_LC2'));
	
	foreach($this->data->sources as $source){
		if($review->source_id == $source->id){
			$source = ''; //= '<span>'. $source->name .'</span>';
		}
	}
	
	$counter = 0;
	$attachmentData = '<div class="attachments">';

	foreach($this->data->attachments as $attachment){
		
		if($review->id == $attachment->review_id){
			$counter = 1;
			if($attachment->type = 1){
				$attachmentData .= '<div class="img"><a data-fancybox="gallery'.$i.'" class="butt" target="_blank" href="'.$attachment->data.'"><img src="'.$attachment->mindata.'"></a><span data-id="'.$attachment->id.'" class="icon-unpublish"></span></div>';
			}else{
				$attachmentData .= '<div class="img"><a data-fancybox="gallery'.$i.'" class="butt" target="_blank" href="'.$attachment->data.'"><img src="'.$attachment->mindata.'"></a><span data-id="'.$attachment->id.'" class="icon-unpublish"></span></div>';
			}
		}
		
	}

	$attachmentData .= '</div>';
	
	print '<div class="review">';
	print '<div class="field_one"><div class="uname">'.$review->user_name.'</div><div class="stars" rating="'.$review->rating.'"></div></div>';
	
	if($counter != 0){
		print '<div class="text"><p>'. $review->text .'</p>'.$attachmentData.'</div>';
	}else{
		print '<div class="text"><p>'. $review->text .'</p></div>';
	}
	print '<div class="source">'.$source.'<i class="date">'.$date.'</i></div>';
	print '</div>';
	
}
	print '<h2>Оставляйте ваши отзывы, предложения и пожелания!</h2>';
	print '
	<form class="jkform" enctype="multipart/form-data" action="'.$this->data->document->base.'?task=apply" method="POST">
		<div class="headline">
		<input type="text" placeholder="Ведите имя" name="username" required="required">
		<div id="rating"></div>
		</div>
		<textarea required="required" name="comment" placeholder="Напишите отзыв..."></textarea>
		<input type="file" name="files[]" multiple>
		<input type="hidden" value="0" name="rating">
		<input type="hidden" value="'.md5(md5(time()).''.rand(1000,2000)).'" name="tоkеnсsrf">
		<button type="submit" class="addVoice">Оставить отзыв</button>
	</form>';
	print '</div>';

?>