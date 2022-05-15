<?php

defined('_JEXEC') or die('Restricted access');

class plgContentJKreviews extends JPlugin {
	

	function onBeforeRender(){
		
		$input = JFactory::getApplication()->input; 
		
		if ($input->getVar('option') == 'com_content' 
		&& $input->getVar('layout') !== 'edit'
		//&& $input->getVar('view') == 'category'	//'article' 
		&& $input->getInt('id', 0) > 0){
			
			JLoader::register('JKHelper', JPATH_BASE . '/components/com_jkreview/helpers/jkhelper.php');
			JKHelper::csrfToken();
			
			$this->data = JKHelper::jkPageReview();
			$this->formPostReview = '';
		
			$doc = JFactory::getDocument();
			//$doc->addScript(JURI::base()."components/com_jkreview/views/jkreview/tmpl/js/jquery.star-rating-svg.js");
			//$doc->addScript(JURI::base()."components/com_jkreview/views/jkreview/tmpl/js/jkrevew.js");
			$doc->addStyleSheet(JURI::base()."components/com_jkreview/views/jkreview/tmpl/css/star-rating-svg.css");
			$doc->addStyleSheet(JURI::base()."components/com_jkreview/views/jkreview/tmpl/css/jkrevew.css");
			
			$product_id = $input->getInt('id', 0);
			$category_id = $input->getInt('catid',0);
			
			$showThis = false;

			$this->formPostReview .= '<div id="reviews">';
			$this->formPostReview .= '<div class="h3">Отзывы о нас</div>';
			$this->formPostReview .= '<div class="listReview">';

 			foreach($this->data->reviews as $i => $review){
				
				if($review->public_vm_prod_id == ''){
 					if($review->public_vm_cat_id != ''){
						if(stripos($review->public_vm_cat_id, ',')){
							$vmCid = explode(',',$review->public_vm_cat_id);
							foreach($vmCid as $cid){
								if($category_id == $cid){
									$showThis = true;
								}
							}
						}else{
							if($category_id == $review->public_vm_cat_id){
								$showThis = true;
							}else{
								$showThis = false;
							}
						}
					}else{
						$showThis = false;
					}
				}else{
 					if(stripos($review->public_vm_prod_id, ',')){
						$vmCid = explode(',',$review->public_vm_prod_id);
						foreach($review->public_vm_prod_id as $pid){
							if($product_id == $pid){
								$showThis = true;
							}
						}
					}else{
						if($product_id == $review->public_vm_prod_id){
							$showThis = true;
						}else{
							$showThis = false;
						}
					}
				} 

				//if($showThis){
					
					$date = JHtml::_('date', $review->public_date, JText::_('DATE_FORMAT_LC2'));
					
 					foreach($this->data->sources as $source){
						if($review->source_id == $source->id){
							$source = ''; //= '<span>'. $source->name .'</span>';
						}
					}
					
 					$counter = 0;
					
 					if(!empty($this->data->attachments)){
						$attachmentData = '<ul class="attachments">';
						foreach($this->data->attachments as $attachment){
							if($review->id == $attachment->review_id){
								$counter = 1;
								if($attachment->type = 1){
									$attachmentData .= '<li><a class="attach" data-fancybox="gallery'.$i.'" class="butt" target="_blank" href="'.$attachment->data .'" " style="background-image:url('.$attachment->mindata .');"><img alt="" src="'.$attachment->mindata .'"></a></li>';
								}else{
									$attachmentData .= '<li><a class="attach" data-fancybox="gallery'.$i.'" class="butt" target="_blank" href="'.$attachment->data .'" " style="background-image:url('.$attachment->mindata .');"><img alt="" src="'.$attachment->mindata .'"></a></li>';
								}
							}
						}
						$attachmentData .= '</ul>';
					}else{
						$attachmentData = '';
					}

					$this->formPostReview .= '<div class="review" itemscope itemtype="http://schema.org/Review">';
					$this->formPostReview .= '<a class="avatar" class="butt" target="_blank" href="/templates/smu21krd/img/noavatar.svg" style="background-image:url(/templates/smu21krd/img/noavatar.svg);"></a>';
					$this->formPostReview .= '<div class="h4" itemprop="author">'.$review->user_name.'</div>';
					$this->formPostReview .= '<div class="data" itemprop="datePublished" datetime="">'.$date.'</div>';
					$this->formPostReview .= '<div class="rating"><input type="hidden" name="val" value="'.$review->rating.'"></div>';
					$this->formPostReview .= '<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">';
					$this->formPostReview .= '<meta itemprop="worstRating" content = "1">';
					$this->formPostReview .= '<meta itemprop="ratingValue" content = "5">';
					$this->formPostReview .= '<meta itemprop="bestRating" content = "5">';
					$this->formPostReview .= '</div>';
					$this->formPostReview .= '<p itemprop="reviewBody">'. $review->text .'</p>';
					$this->formPostReview .= $attachmentData;
					$this->formPostReview .= '<div itemprop="itemReviewed" itemscope itemtype="https://schema.org/Organization">';
					$this->formPostReview .= '<meta itemprop="name" content="Отзыв о компании ООО «СМУ-21»">';
					$this->formPostReview .= '<meta itemprop="telephone" content="8 (988) 379-72-71">';
					$this->formPostReview .= '<link itemprop="url" href="https://smu21metall.ru/"/>';
					$this->formPostReview .= '<meta itemprop="email" content="smu21krd@mail.ru">';
					$this->formPostReview .= '<div itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">';
					$this->formPostReview .= '<meta itemprop="addressLocality" content="Краснодар">';
					$this->formPostReview .= '<meta itemprop="streetAddress" content="ул. Думенко, 2, оф. 15">';
					$this->formPostReview .= '</div>';
					$this->formPostReview .= '</div>';
					$this->formPostReview .= '</div>';

					
				//}
				
			}
			
			$this->formPostReview .= '</div></div>';
			
			$this->formPostReview .= '<div class="info" itemprop="aggregateRating" itemscope="true" itemtype="http://schema.org/AggregateRating">';
			$this->formPostReview .= '<meta itemprop="worstRating" content="1">';
			$this->formPostReview .= '<meta itemprop="bestRating" content="5">';
			$this->formPostReview .= '<meta itemprop="ratingCount" content="215">';
			$this->formPostReview .= '<button type="button" class="addVoice">Оставить отзыв</button>';
			$this->formPostReview .= '<div class="totalRating"><input type="hidden" name="val" value="4.7"></div>';
			$this->formPostReview .= '<div class="text">Общий рейтинг: <span class="rating-value" itemprop="ratingValue">4.9</span></div>';
			$this->formPostReview .= '<div itemprop="itemReviewed" itemscope itemtype="https://schema.org/Organization">';
			$this->formPostReview .= '<meta itemprop="name" content="Отзывы о компании ООО «СМУ-21»">';
			$this->formPostReview .= '<meta itemprop="telephone" content="8 (988) 379-72-71">';
			$this->formPostReview .= '<link itemprop="url" href="https://smu21metall.ru/"/>';
			$this->formPostReview .= '<meta itemprop="email" content="info@krdprint.com">';
			$this->formPostReview .= '<div itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">';
			$this->formPostReview .= '<meta itemprop="addressLocality" content="Краснодар">';
			$this->formPostReview .= '<meta itemprop="streetAddress" content="ул. Думенко, 2, оф. 15">';
			$this->formPostReview .= '</div>';
			$this->formPostReview .= '</div>';
			$this->formPostReview .= '</div>';
			
			
			/*$this->formPostReview .= '<div id="addReviews">';
			$this->formPostReview .= '<form class="jkform" enctype="multipart/form-data" action="/index.php?option=com_jkreview&task=apply" method="POST">';
			$this->formPostReview .= '<div class="rbl">';
			
			$this->formPostReview .= '<div class="bl">';
			$this->formPostReview .= '<label>Ваше имя</label>';
			$this->formPostReview .= '<input type="text" placeholder="Ведите имя" name="username" required="required">';
			$this->formPostReview .= '</div>';
			
			$this->formPostReview .= '<div class="bl">';
			$this->formPostReview .= '<label>Ваша оценка</label>';
			$this->formPostReview .= '<div id="rating"></div>';
			$this->formPostReview .= '</div>';
			
			$this->formPostReview .= '</div>';
			
			$this->formPostReview .= '<label>Напишите отзыв</label>';
			$this->formPostReview .= '<textarea required="required" name="comment" placeholder="Напишите отзыв..."></textarea>';
			
			//$this->formPostReview .= '<div class="bl">';
			//$this->formPostReview .= '<label>Прикрепить фото</label>';
			//$this->formPostReview .= '<input type="file" name="files[]" multiple>';
			//$this->formPostReview .= '</div>';
			
			$this->formPostReview .= '<input type="hidden" value="0" name="rating">';
			$this->formPostReview .= '<input type="hidden" value="'.$product_id.'" name="produkt_ids">';
			$this->formPostReview .= '<input type="hidden" value="'.md5(md5(time()).''.rand(1000,2000)).'" name="tоkеnсsrf">';
			$this->formPostReview .= '<button type="submit" class="addReview">Оставить отзыв</button>';
			
			$this->formPostReview .= '</form>';
			$this->formPostReview .= '</div>'; */

		}
	}
	
	function onAfterRender() {
		
		$input = JFactory::getApplication()->input; 
		
		if ($input->getVar('option') == 'com_content'
		&& $input->getVar('layout') !== 'edit'
		//&& $input->getVar('view') == 'category'
		&& $input->getInt('id', 0) > 0) {

			$buffer = JFactory::getApplication()->getBody();
			$buffer = str_replace('<div class="customer-reviews"></div>', $this->formPostReview , $buffer); 
			JFactory::getApplication()->setBody($buffer);
			
		}
		
	}
}

?>