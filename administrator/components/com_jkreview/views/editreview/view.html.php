<?php

defined('_JEXEC') or die('Restricted access');
JLoader::register('JKHelper', JPATH_ADMINISTRATOR . '/components/com_jkreview/helpers/jkhelper.php');
JKHelper::csrfToken();
$doc = JFactory::getDocument();
$doc->addScript(JURI::base()."components/com_jkreview/assets/js/jquery-3.3.1.min.js");
$doc->addStyleSheet(JURI::root()."/media/system/css/fields/calendar.css");
$doc->addScript(JURI::root()."/media/system/js/fields/calendar-locales/ru.js");
$doc->addScript(JURI::root()."/media/system/js/fields/calendar-locales/date/gregorian/date-helper.min.js");
$doc->addScript(JURI::root()."/media/system/js/fields/calendar.min.js");
$doc->addScript(JURI::base()."components/com_jkreview/assets/js/jquery.fancybox.min.js");
$doc->addScript(JURI::base()."components/com_jkreview/assets/js/jquery.star-rating-svg.js");
$doc->addScript(JURI::base()."components/com_jkreview/assets/js/editreview.js");
$doc->addStyleSheet(JURI::base()."components/com_jkreview/assets/css/jquery.fancybox.min.css");
$doc->addStyleSheet(JURI::base()."components/com_jkreview/assets/css/star-rating-svg.css");
$doc->addStyleSheet(JURI::base()."components/com_jkreview/assets/css/editreview.css");

class jkreviewVieweditreview extends JViewLegacy
{

	function display($tpl = null)
	{
		
		if(isset($_GET['id']) && (int)$_GET['id'] > 0){
			JToolBarHelper::apply('task.editapply');
			JToolBarHelper::save('task.editapplyclose');
			$id = (int)$_GET['id'];
		}else{
			JToolBarHelper::apply('task.apply');
			JToolBarHelper::save('task.applyclose');
			$id = false;
		}
		
		if($id){
			$this->headName = "Редактирование отзыва ID ".$id;
		}else{
			$this->headName = "Новый отзыв";
		}
		
		JToolBarHelper::cancel('view.jkreview');
		$data = JKHelper::jkEdit($id);

		$this->HTMLreview = '<hr><form enctype="multipart/form-data" method="POST"><div class="hideselect">';
		$this->HTMLreview .= '<label>Имя</label>';
		$this->HTMLreview .= '<select>';
		
		if($id){
			if($data->review->system_user_id != 0){
				$this->HTMLreview .= '<option value="0">---- Не выбрано ----</option>';
			}else{
				$this->HTMLreview .= '<option value="0" selected>---- Не выбрано ----</option>';
			}
		}else{
			$this->HTMLreview .= '<option value="0" selected>---- Не выбрано ----</option>';
		}
		
		foreach($data->users as $user){
			if($id){
				if($data->review->system_user_id == $user->id){
					$selectedUser = $user->name;
					$selectedUserId = $user->id;
					$this->HTMLreview .= '<option value="'.$user->id.'" selected>'.$user->name.'</option>';
				}else{
					$this->HTMLreview .= '<option value="'.$user->id.'">'.$user->name.'</option>';
				}
			}else{
				$this->HTMLreview .= '<option value="'.$user->id.'">'.$user->name.'</option>';
			}
		}
		
		$this->HTMLreview .= '</select><i class="selectTo"></i>';
		
		if($id){
			if($data->review->system_user_id != 0){
				$this->HTMLreview .= '<input type="text" name="username" value="'.$selectedUser.'">';
			}else{
				$this->HTMLreview .= '<input type="text" name="username" value="'.$data->review->user_name.'">';
			}
		}else{
			$this->HTMLreview .= '<input type="text" name="username" value="">';
		}
		
		if($id){
			$this->HTMLreview .= '<input type="hidden" name="userid" value="'.$data->review->system_user_id.'">';
			$this->HTMLreview .= '<input type="hidden" name="checkuserid" value="'.$data->review->system_user_id.'">';
		}else{
			$this->HTMLreview .= '<input type="hidden" name="userid" value="">';
			$this->HTMLreview .= '<input type="hidden" name="checkuserid" value="">';
		}
		
		$this->HTMLreview .= '</div><fieldset class="public">';
		$this->HTMLreview .= '<label>Статус</label>';
		
		if($id){
			if($data->review->public_status == 1){
				$this->HTMLreview .= '<input type="radio" id="jform_featured0" name="status" value="1" checked="checked">';
				$this->HTMLreview .= '<label for="jform_featured0" class="btn active btn-success">Опубликованно</label>';
				$this->HTMLreview .= '<input type="radio" id="jform_featured1" name="status" value="0">';
				$this->HTMLreview .= '<label for="jform_featured1" class="btn">Не опубликованно</label>';
			}else{
				$this->HTMLreview .= '<input type="radio" id="jform_featured0" name="status" value="1">';
				$this->HTMLreview .= '<label for="jform_featured0" class="btn">Опубликованно</label>';
				$this->HTMLreview .= '<input type="radio" id="jform_featured1" name="status" value="0" checked="checked">';
				$this->HTMLreview .= '<label for="jform_featured1" class="btn active btn-danger">Не опубликованно</label>';
			}
		}else{
			$this->HTMLreview .= '<input type="radio" id="jform_featured0" name="status" value="1">';
			$this->HTMLreview .= '<label for="jform_featured0" class="btn">Опубликованно</label>';
			$this->HTMLreview .= '<input type="radio" id="jform_featured1" name="status" value="0" checked="checked">';
			$this->HTMLreview .= '<label for="jform_featured1" class="btn active btn-danger">Не опубликованно</label>';
		}
		
		$this->HTMLreview .= '</fieldset>';	
		
		
		$this->HTMLreview .= '<div class="field-calendar">';
		$this->HTMLreview .= '<label>Дата публикации</label>';
		$this->HTMLreview .= '<div class="input-append">';
		
		if($id){
			$date = date('d-m-Y H:i:s', strtotime($data->review->public_date));
		}else{
			$date = date('d-m-Y H:i:s');
		}
		
		$this->HTMLreview .= '<input type="text" id="jform_publish_up" name="publish_up" size="22" autocomplete="off" aria-invalid="false" 
		value="'.$date.'"  
		data-alt-value="'.$date.'" 
		data-local-value="'.$date.'">';
		
		$this->HTMLreview .= '<button type="button" class="btn btn-secondary" id="jform_publish_up_btn" data-inputfield="jform_publish_up" 
			data-dayformat="%d-%m-%Y %H:%M:%S" 
			data-button="jform_publish_up_btn" 
			data-firstday="1" 
			data-weekend="0,6" 
			data-today-btn="1" 
			data-week-numbers="1" 
			data-show-time="1" 
			data-show-others="1" 
			data-time-24="24" 
			data-only-months-nav="0" 
			title="Open the calendar">
			<span class="icon-calendar" aria-hidden="true"></span>
			</button>';
		
		$this->HTMLreview .= '</div>';
		$this->HTMLreview .= '</div><hr>';

		$this->HTMLreview .= '<div id="cat_label">';
		$this->HTMLreview .= '<label>Категории</label>';
		$this->HTMLreview .= '<select class="catselect" multiple name="cat[]">';

		$selectedCat = '';
		
		if(!empty($data->review)){
			$arr = explode(',',$data->review->public_vm_cat_id);
		}else{
			$arr = array();
		}

		foreach($data->categories as $category){
			
			$flag = 0;

			foreach($arr as $vm_cat_id){
				if($vm_cat_id == $category->id){
					$flag = 1;
					$selectedCat .= '<span class="cat_b" data-id="'.$category->id.'">'.$category->title.'<i class="icon-unpublish"></i></span>';
					$this->HTMLreview .= '<option value="'.$category->id.'" selected="selected">'.$category->title.'</option>';
				}
			}

			if($flag == 0){
				$this->HTMLreview .= '<option value="'.$category->id.'">'.$category->title.'</option>';
			}
			
		}
		
		if(empty($arr[0])){
			$selectedCat = '<span class="cat_b no" data-id="0">Нет выбранных категорий</span>';
			$this->HTMLreview .= '<option value="0" selected="selected">0</option>';
		}

		$this->HTMLreview .= '</select>';
		$this->HTMLreview .= '<div class="faike_input">'.$selectedCat.'</div></div>';
		$this->HTMLreview .= '<div id="prod_label">';
		$this->HTMLreview .= '<label>ID товаров (через запятую)</label>';
		if($id){
			$itemIds = array();
			foreach($data->assets as $itemId){
				$itemIds[] = $itemId->item_id;
			}
			$this->HTMLreview .= '<input type="text" name="produkt_ids" value="'.implode(',',$itemIds).'">';
		}else{
			$this->HTMLreview .= '<input type="text" name="produkt_ids" value="">';
		}
		$this->HTMLreview .= '<p>Внимание! ID товаров имеют больший приоритет!</p></div><hr>';
		$this->HTMLreview .= '<div id="comment"><label>Комментарий отзыва</label>';
		//$editor = JFactory::getEditor();
		//$editor = $editor->display('comment', $data->review->text, '100%', '400', '60', '20', false);
		//$this->HTMLreview .= '<div id="editor">'.$editor.'</div>';
		$this->HTMLreview .= '<div id="rating"><label>Оценка</label><div id="stars"></div></div>';
		$this->HTMLreview .= '<textarea type="text" name="comment">'.(!empty($data->review->text)? $data->review->text : "").'</textarea></div>';
		$this->HTMLreview .= '<input type="hidden" value="'.md5(md5(time()).''.rand(1000,2000)).'" name="tоkеnсsrf">';
		$this->HTMLreview .= '<hr><input type="file" name="files[]" multiple>';
		if($id){
			$this->HTMLreview .= '<input type="hidden" name="id" value="'.$id.'">';
			$this->HTMLreview .= '<input type="hidden" name="rating" is-new="0" value="'.$data->review->rating.'">';
		}else{
			$this->HTMLreview .= '<input type="hidden" name="rating" is-new="1" value="0">';
		}
		$this->HTMLreview .= '</form><hr>';
		
		$counter = 0;
		$attachmentData = '<div class="attachments">';

		if($id){
			foreach($data->attachments as $attachment){
				
				if($data->review->id == $attachment->review_id){
					$counter = 1;
					if($attachment->type = 1){
						$attachmentData .= '<div class="img"><a data-fancybox="gallery" class="butt" target="_blank" href="'.$attachment->data.'"><img src="'.$attachment->mindata.'"></a><span data-id="'.$attachment->id.'" class="icon-unpublish"></span></div>';
					}else{
						$attachmentData .= '<div class="img"><a data-fancybox="gallery" class="butt" target="_blank" href="'.$attachment->data.'"><img src="'.$attachment->mindata.'"></a><span data-id="'.$attachment->id.'" class="icon-unpublish"></span></div>';
					}
				}
				
			}
		}
			
		$attachmentData .= '</div>';
		
		if($counter == 0){
			$this->HTMLreview .= 'Нет вложений';
		}else{
			$this->HTMLreview .= $attachmentData;
		}
		
		parent::display($tpl);
		
	}
	
}