<?php

defined('_JEXEC') or die('Restricted access');

print '<table class="table table-striped" id="articleList"><thead>';
print '<tr><td style="text-align:center;width: 30px;"><input name="ids" type="checkbox" value="0"></td><td style="text-align:center;"width: 80px;">Состояние</td><td>Комментарий</td><td style="text-align:center;">Оценка</td><td>Источник</td><td style="text-align:center;width: 80px;">Вложения</td><td style="text-align:center;">Категории</td><td style="text-align:center">Дата</td><td style="text-align:center" >ID</td></tr>';
print '</thead><tbody>';
	
foreach($this->data->reviews as $i => $review){
	
	print '<tr><td class="chbox" style="text-align:center;width: 30px"><input type="checkbox" name="id" value="'.$review->id.'"></td>';
	print '<td style="text-align:center;width: 80px;">';
	
	if($review->public_status == 1){
		print '<div class="btn-group">';
		print '<a onclick="Joomla.submitbutton(`task.unpublish.'.$review->id.'`)" class="btn btn-micro hasTooltip"><span class="icon-publish"></span></a>';
		/* if($review->featured){
			print '<a onclick="Joomla.submitbutton(`task.unfeatured.'.$review->id.'`)" class="btn btn-micro hasTooltip"><span class="icon-featured"></span></a>';
		}else{
			print '<a onclick="Joomla.submitbutton(`task.featured.'.$review->id.'`)" class="btn btn-micro hasTooltip"><span class="icon-unfeatured"></span></a>';
		} */
		print '<a onclick="Joomla.submitbutton(`task.remove.'.$review->id.'`)" class="btn btn-micro hasTooltip"><span class="icon-trash"></span></a>';
		print '</div>';
	}else{
		print '<div class="btn-group">';
		print'<a onclick="Joomla.submitbutton(`task.publish.'.$review->id.'`)" class="btn btn-micro hasTooltip"><span class="icon-unpublish"></span></a>';
		/*if($review->featured){
			print '<a onclick="Joomla.submitbutton(`task.unfeatured.'.$review->id.'`)" class="btn btn-micro hasTooltip"><span class="icon-featured"></span></a>';
		}else{
			print '<a onclick="Joomla.submitbutton(`task.featured.'.$review->id.'`)" class="btn btn-micro hasTooltip"><span class="icon-unfeatured"></span></a>';
		} */
		print '<a onclick="Joomla.submitbutton(`task.remove.'.$review->id.'`)" class="btn btn-micro hasTooltip"><span class="icon-trash"></span></a>';
		print '</div>';
	}

	print '</td>';
	print '<td onclick="Joomla.submitbutton(`view.editreview.'.$review->id.'`)"><a href="#1"><b>'.$review->user_name.'</b><br><span class="text">'. $review->text .'</span></a></td>';
	print '<td><div class="stars" rating="'.$review->rating.'"><div></td>';
	
	if(!empty($this->data->sources)){
		foreach($this->data->sources as $source){
			
			if($review->source_id == $source->id){
				print '<td style="text-align:center;width: 50px;">'. $source->name .'</td>';
			}
			
		}
	}else{
		print '<td style="text-align:center;width: 50px;">Admin Panel</td>';
	}
	
	
	$counter = 0;
	$countImg = 0;
	$countVideo = 0;

	foreach($this->data->attachments as $k => $attachment){
		
		if($review->id == $attachment->review_id){
			$counter = 1;
			if($attachment->type == 1){
				$countImg++;
			}else{
				$countVideo++;
			}
		}
		
	}
	
	if($countImg > 0 && $countVideo > 0){
		print '<td style="text-align:center;width: 80px;">Видео: '.$countVideo.'<br>Фото: '.$countImg.'</td>';
	}else if($countImg > 0 && $countVideo == 0){
		print '<td style="text-align:center;width: 80px;">Фото: '.$countImg.'</td>';
	}else if($countImg == 0 && $countVideo > 0){
		print '<td style="text-align:center;width: 80px;">Видео: '.$countVideo.'</td>';
	}else{
		print '<td style="text-align:center;width: 80px;">Нет</td>';
	}
	
	$vmcounter = 0;
	$categoryName = '<td style="text-align:center;width:300px;">';

	foreach($this->data->categories as $category){
		
		foreach(explode(',',$review->public_vm_cat_id) as $vm_cat_id){
			
			if($vm_cat_id == $category->id){
				$vmcounter = 1;
				$categoryName .= '<span class="cat_b">'.$category->title.'</span>';
			}
			
		}
		
	}
	
	$categoryName .= '</td>';
	
	if($vmcounter == 0){
		print '<td>Категории не выбраны</td>';
	}else{
		print $categoryName;
	}

	print '<td width="150px" style="text-align:center">'. JHtml::_('date', $review->public_date, JText::_('DATE_FORMAT_LC2')) .'</td>';
	print '<td width="50px" style="text-align:center">'. $review->id .'</td>';
	print '</tr>';
	
}

print '</tbody></table>';

?>