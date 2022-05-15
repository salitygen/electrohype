<?php

defined('_JEXEC') or die('Restricted access');

class jkreviewController extends JControllerLegacy
{
	
	public function apply()
	{
		
		$config = JFactory::getConfig();
		$sk = $config->get('secret');

		if($_SESSION['tokencsrf'] != md5(md5($_POST['tokencsrf'].''.$sk))){
			die('Restricted access');
		}else{
			unset($_SESSION['tokencsrf']);
		}
		
		jkreviewController::worker('new');
		$application = JFactory::getApplication();
		$application->enqueueMessage(JText::_('Изменения успешно сохранены!'), 'message');
		if((int)$_POST['produkt_ids'] > 0){
			$this->setRedirect(JRoute::_('index.php?option=com_content&view=category&layout=blog&id='.(int)$_POST['produkt_ids'] , false));
		}else{
			$this->setRedirect(JRoute::_('index.php?option=com_jkreview', false));
		}
		
	}
	
/* 	public function editapply()
	{
		jkreviewController::worker('edit');
		$application = JFactory::getApplication();
		$application->enqueueMessage(JText::_('Изменения успешно сохранены!'), 'message');
		$this->setRedirect(JRoute::_('index.php?option=com_jkreview&view=jkreview&id='.(int)$_POST['id'], false));
		
	} */
	
	public function worker($a)
	{
		if($a == 'edit'){
			if(isset($_POST['id']) && (int)$_POST['id'] > 0){
				$id = (int)$_POST['id'];
			}else{
				die('Bad ID');
			}
		}
		
		$db = JFactory::getDbo();
		
		if(stripos($_POST['produkt_ids'], ',')){
			
			$produktStringIds = '';
			$produkt_ids = array_unique(explode(',',$_POST['produkt_ids']));
			
			$k = 0;
			foreach($produkt_ids as $pid){
				if($pid > 0){
					$sanitaiseArrProdukt[$k] = $pid;
					$k++;
				}
			}
			
			foreach($sanitaiseArrProdukt as $pid){
				if(end($sanitaiseArrProdukt) != $pid){
					$produktStringIds .= (int)$pid.',';
				}else{
					$produktStringIds .= (int)$pid;
				}
			}
			
		}else{
			if($_POST['produkt_ids'] != ''){
				$produktStringIds = (int)$_POST['produkt_ids'];
			}else{
				$produktStringIds = '';
			}
		}

		$categories = '';
		$arrayCat = array_unique($_POST['cat']);
		
		$k = 0;
		foreach($arrayCat as $cid){
			if($cid > 0){
				$sanitaiseArrCat[$k] = $cid;
				$k++;
			}
		}
		
		foreach($sanitaiseArrCat as $cid){
			if(end($sanitaiseArrCat) != $cid){
				$categories .= (int)$cid.',';
			}else{
				$categories .= (int)$cid;
			}
		}
		
		function validateDate($date, $format = 'd-m-Y H:i:s')
		{
			$d = DateTime::createFromFormat($format, $date);
			return $d && $d->format($format) == $date;
		}
		
		if(validateDate($_POST['publish_up'])){
			$date = date('Y-m-d H:i:s', strtotime($_POST['publish_up']));
		}else{
			$date = date('Y-m-d H:i:s');
		}

		function strip_data($text){
			
			$quotes = array ("\x27", "\x22", "\x60", "\t", "\n", "\r", "*", "%", "<", ">","//","www","http","https","href","&","id=","option=","view=","task=");
			$goodquotes = array("-", "+", "#" );
			$repquotes = array("\-", "\+", "\#" );
			$text = htmlspecialchars($text);
			$text = stripslashes($text);
			$text = trim(strip_tags($text));
			$text = str_replace($quotes,'',$text);
			$text = str_replace($goodquotes,$repquotes,$text);
			$text = mb_substr($text, 0, 6000, 'UTF-8') . '';
			return $text;
			
		}
		
		$userName = strip_data($_POST['username']);
		$comment = strip_data($_POST['comment']);
		$userId = (int)$_POST['userid'];
		$rating = (int)$_POST['rating'];
		$checkId = (int)$_POST['checkuserid'];
		$this->params = JFactory::getApplication()->getParams();
		$status = (int)$this->params->get('moderation');
		$sourceId = 1;//(int)$_POST['source_id']; // ============== !!!!!!!!!
		
		if($userId != $checkId){
			$userId = 0;
		}
		
		if($a == 'edit'){
			$query = $db->getQuery(true)
			->update("#__jkreview_reviews")
			->set("system_user_id=".$userId.",user_name='".$userName."',text='".$comment."', public_status=".$status.",public_vm_prod_id='".$produktStringIds."',public_vm_cat_id='".$categories."',public_date='".$date."',rating=".$rating)
			->where("id=".$id);
		}else{
			$query = "
			INSERT INTO #__jkreview_reviews(text,public_status,public_vm_prod_id,public_vm_cat_id,source_id,system_user_id,user_name,public_date,rating) 
			VALUES ('".$comment."',".$status.",'".$produktStringIds."','".$categories."',".$sourceId.",".$userId.",'".$userName."','".$date."',".$rating.")";
		}

		$db->setQuery($query);
		$db->loadObjectList();
		
		if($a == 'new'){
			$query = "SELECT max(id) FROM `#__jkreview_reviews`";
			$db->setQuery($query);
			$id = (int)$db->loadAssoc()['max(id)'];
		}
		
		if(isset($_FILES['files'])){
			
			$pathFolder = '/images/jkreview/';
			$pathRoot = $_SERVER['DOCUMENT_ROOT'];
			$filePathUpload = array();
			
			foreach($_FILES['files']['name'] as $k => $fileName){
				$filePathUpload[$k]['name'] = $fileName;
			}
			foreach($_FILES['files']['tmp_name'] as $k => $fileTmpName){
				$filePathUpload[$k]['tmp_name'] = $fileTmpName;
			}
			foreach($_FILES['files']['type'] as $k => $fileType){
				$filePathUpload[$k]['type'] = $fileType;
			}
			foreach($_FILES['files']['size'] as $k => $fileSize){
				$filePathUpload[$k]['size'] = $fileSize;
			}
			
			$pathKey = 0;
			foreach($filePathUpload as $file){
				if(explode('/', $file['type'])[0] == 'image'){
					$ext = end(explode('.',$file['name']));
					if($ext != 'svg'){ // svg очень опасный!
						$name = md5(md5(time()).''.rand(1,1000));
						$path = $pathFolder.''.$name.'.'.$ext;
						$minpath = $pathFolder.'min/'.$name.'.'.$ext;
						$pathKey++;
						move_uploaded_file($file['tmp_name'], $pathRoot.''.$path);
						$sizestring = getimagesize($pathRoot.''.$path)[3];
						$size = explode(" ",$sizestring);
						if($size != ''){
							$nextStep = true;
							foreach($size as $s){
								$chkSize = (int)str_replace('"','',explode('=',$s)[1]);
								if($chkSize <= 0 || $chkSize > 10000){
									$nextStep = false;
								}
							}
							if($nextStep){
								$image = new Imagick($pathRoot.''.$path);
								$image->thumbnailImage(250,0);
								$image->writeImage($pathRoot.''.$minpath);
								$image->destroy();
								$query = "INSERT INTO #__jkreview_attachments(type,review_id,data,mindata) VALUES (1,".$id.",'".$path."','".$minpath."')";					
								$db->setQuery($query);
								$db->query();
							}
						}
					}
				}
			}
		}
		 
		if($a == 'new'){
			return $id;
		}
		
	}
	
}

?>