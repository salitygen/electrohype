<?php

defined('_JEXEC') or die('Restricted access');

class jkreviewController extends JControllerLegacy
{
	
	public function unpublish()
	{
		
		$config = JFactory::getConfig();
		$sk = $config->get('secret');

		if($_SESSION['admtoken'] != md5(md5($_GET['csr'].''.$sk))){
			die('Restricted access');
		}else{
			unset($_SESSION['admtoken']);
		}
		
		jkreviewController::condition('unpublic');
		$application = JFactory::getApplication();
		$application->enqueueMessage(JText::_('Изменения успешно сохранены!'), 'message');
		$this->setRedirect(JRoute::_('index.php?option=com_jkreview&view=jkreview', false));
	}
	
	public function publish()
	{
		
		$config = JFactory::getConfig();
		$sk = $config->get('secret');

		if($_SESSION['admtoken'] != md5(md5($_GET['csr'].''.$sk))){
			die('Restricted access');
		}else{
			unset($_SESSION['admtoken']);
		}
		
		jkreviewController::condition('public');
		$application = JFactory::getApplication();
		$application->enqueueMessage(JText::_('Изменения успешно сохранены!'), 'message');
		$this->setRedirect(JRoute::_('index.php?option=com_jkreview&view=jkreview', false));
	}
	
	public function remove()
	{
		
		$config = JFactory::getConfig();
		$sk = $config->get('secret');

		if($_SESSION['admtoken'] != md5(md5($_GET['csr'].''.$sk))){
			die('Restricted access');
		}else{
			unset($_SESSION['admtoken']);
		}
		
		jkreviewController::condition('remove');
		$application = JFactory::getApplication();
		$application->enqueueMessage(JText::_('Изменения успешно сохранены!'), 'message');
		$this->setRedirect(JRoute::_('index.php?option=com_jkreview&view=jkreview', false));
	}
	
	public function featured()
	{
		
		$config = JFactory::getConfig();
		$sk = $config->get('secret');

		if($_SESSION['admtoken'] != md5(md5($_GET['csr'].''.$sk))){
			die('Restricted access');
		}else{
			unset($_SESSION['admtoken']);
		}
		
		jkreviewController::condition('featured');
		$application = JFactory::getApplication();
		$application->enqueueMessage(JText::_('Изменения успешно сохранены!'), 'message');
		$this->setRedirect(JRoute::_('index.php?option=com_jkreview&view=jkreview', false));
	}
	
	public function unfeatured()
	{
		
		$config = JFactory::getConfig();
		$sk = $config->get('secret');

		if($_SESSION['admtoken'] != md5(md5($_GET['csr'].''.$sk))){
			die('Restricted access');
		}else{
			unset($_SESSION['admtoken']);
		}
		
		jkreviewController::condition('unfeatured');
		$application = JFactory::getApplication();
		$application->enqueueMessage(JText::_('Изменения успешно сохранены!'), 'message');
		$this->setRedirect(JRoute::_('index.php?option=com_jkreview&view=jkreview', false));
	}
	
	public function apply()
	{
		
		$config = JFactory::getConfig();
		$sk = $config->get('secret');

		if($_SESSION['admtoken'] != md5(md5($_POST['tokencsrf'].''.$sk))){
			die('Restricted access');
		}else{
			unset($_SESSION['admtoken']);
		}
		
		$id = jkreviewController::worker('new');
		$application = JFactory::getApplication();
		$application->enqueueMessage(JText::_('Изменения успешно сохранены!'), 'message');
		$this->setRedirect(JRoute::_('index.php?option=com_jkreview&view=editreview&id='.$id, false));
	}
	
	public function applyclose()
	{
		
		$config = JFactory::getConfig();
		$sk = $config->get('secret');

		if($_SESSION['admtoken'] != md5(md5($_POST['tokencsrf'].''.$sk))){
			die('Restricted access');
		}else{
			unset($_SESSION['admtoken']);
		}
		
		jkreviewController::worker('new');
		$application = JFactory::getApplication();
		$application->enqueueMessage(JText::_('Изменения успешно сохранены!'), 'message');
		$this->setRedirect(JRoute::_('index.php?option=com_jkreview&view=jkreview', false));
	}
	
	public function editapply()
	{
		
		$config = JFactory::getConfig();
		$sk = $config->get('secret');

		if($_SESSION['admtoken'] != md5(md5($_POST['tokencsrf'].''.$sk))){
			die('Restricted access');
		}else{
			unset($_SESSION['admtoken']);
		}
		
		jkreviewController::worker('edit');
		$application = JFactory::getApplication();
		$application->enqueueMessage(JText::_('Изменения успешно сохранены!'), 'message');
		$this->setRedirect(JRoute::_('index.php?option=com_jkreview&view=editreview&id='.(int)$_POST['id'], false));
	}
	
	public function editapplyclose()
	{
		
		$config = JFactory::getConfig();
		$sk = $config->get('secret');

		if($_SESSION['admtoken'] != md5(md5($_POST['tokencsrf'].''.$sk))){
			die('Restricted access');
		}else{
			unset($_SESSION['admtoken']);
		}
		
		jkreviewController::worker('edit');
		$application = JFactory::getApplication();
		$application->enqueueMessage(JText::_('Изменения успешно сохранены!'), 'message');
		$this->setRedirect(JRoute::_('index.php?option=com_jkreview', false));
	}
	
	public function condition($a){
		
		if(isset($_GET['id'])){
			$ids = array();
			if(stripos($_GET['id'], ',')){
				$ids = array_unique(explode(',',$_GET['id']));
				$k = 0;
				foreach($ids as $id){
					if($id > 0){
						$sanitaiseId[$k] = (int)$id;
						$k++;
					}
				}
				$sanitaiseId = array_unique($sanitaiseId);
				foreach($sanitaiseId as $id){
					if(end($sanitaiseId) != $id){
						$stringIds .= $id.',';
					}else{
						$stringIds .= $id;
					}
				}
			}else{
				if((int)$_GET['id'] > 0){
					$stringIds = (int)$_GET['id'];
				}else{
					$stringIds = 0;
					$this->setRedirect(JRoute::_('index.php?option=com_jkreview&view=jkreview', false));
				}
			}
		}else{
			$stringIds = 0;
			$this->setRedirect(JRoute::_('index.php?option=com_jkreview&view=jkreview', false));
		}
		
		$db = JFactory::getDbo();
		if($a == 'public'){
			$query = "UPDATE #__jkreview_reviews SET public_status=1 WHERE id IN (".$stringIds.")";
			$db->setQuery($query);
			$db->execute();
		}else if($a == 'featured'){
			$query = "UPDATE #__jkreview_reviews SET featured=1 WHERE id IN (".$stringIds.")";
			$db->setQuery($query);
			$db->execute();
		}else if($a == 'unfeatured'){
			$query = "UPDATE #__jkreview_reviews SET featured=0 WHERE id IN (".$stringIds.")";
			$db->setQuery($query);
			$db->execute();
		}else if($a == 'unpublic'){
			$query = "UPDATE #__jkreview_reviews SET public_status=0 WHERE id IN (".$stringIds.")";
			$db->setQuery($query);
			$db->execute();
		}else if($a == 'remove'){
			$query = "DELETE FROM #__jkreview_reviews WHERE id IN (".$stringIds.")";
			$db->setQuery($query);
			$db->execute();
			$query = "DELETE FROM #__jkreview_attachments WHERE review_id IN (".$stringIds.")";
			$db->setQuery($query);
			$db->execute();
		}
	}
	
	public function worker($a)
	{
		if($a == 'edit'){
			if(isset($_POST['id']) && (int)$_POST['id'] > 0){
				$id = (int)$_POST['id'];
			}else{
				die('Bad ID');
			}
		}
		
		if(stripos($_POST['produkt_ids'], ',')){
			
			$produktStringIds = '';
			$produkt_ids = array_unique(explode(',',$_POST['produkt_ids']));
			
			$k = 0;
			foreach($produkt_ids as $pid){
				if((int)$pid > 0){
					$sanitaiseArrProdukt[$k] = (int)$pid;
					$k++;
				}
			}
			
			$sanitaiseArrProdukt = array_unique($sanitaiseArrProdukt);
			foreach($sanitaiseArrProdukt as $pid){
				if(end($sanitaiseArrProdukt) != $pid){
					$produktStringIds .= $pid.',';
				}else{
					$produktStringIds .= $pid;
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
		$status = (int)$_POST['status'];
		$sourceId = 1;//(int)$_POST['source_id']; // ============== !!!!!!!!!
		
		if($userId != $checkId){
			$userId = 0;
		}
		
		$db = JFactory::getDbo();
		
		if($a == 'edit'){
			
			$updateReview = new \stdClass;
			$updateReview->id				= $id;
			$updateReview->system_user_id	= $userId;
			$updateReview->user_name		= $userName;
			$updateReview->text				= $comment;
			$updateReview->public_status	= $status;
			$updateReview->public_vm_cat_id	= $categories;
			$updateReview->public_date		= $date;
			$updateReview->rating			= $rating;

			$result = $db->updateObject('#__jkreview_reviews',$updateReview,'id');
			$rewiewID = $id;
			
			$query = $db->getQuery(true)
				->delete()
				->from('#__jkreview_assets')
				->where('review_id ='.$rewiewID);
			$db->setQuery($query);
			$db->execute();

		}else{
			
			$newReview = new \stdClass;
			$newReview->text 				= $comment;
			$newReview->public_status		= $status;
			$newReview->public_vm_cat_id	= $categories;
			$newReview->source_id			= $sourceId;
			$newReview->system_user_id		= $userId;
			$newReview->user_name			= $userName;
			$newReview->public_date			= $date;
			$newReview->rating				= $rating;
			
			$db->insertObject('#__jkreview_reviews',$newReview);
			$rewiewID = $db->insertid();
			
		}
		
		if(!empty($produktStringIds)){
			$newObj = new \stdClass;
			$newObj->review_id = $rewiewID;
			foreach (explode(',',$produktStringIds) as $id){
				$newObj->item_id = $id;
				$db->insertObject('#__jkreview_assets', $newObj);
			}
		}

		if(isset($_FILES['files'])){
			
			$db = JFactory::getDbo();
			
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
								$query = "INSERT INTO #__jkreview_attachments(type,review_id,data,mindata) VALUES (1,".$rewiewID.",'".$path."','".$minpath."')";					
								$db->setQuery($query);
								$db->execute();
							}
						}
					}
				}
			}
			
		}
		 
		if($a == 'new'){
			return $rewiewID;
		}
		
	}
	
	public function delattachment(){
		
		$config = JFactory::getConfig();
		$sk = $config->get('secret');

		/* if($_SESSION['admtoken'] != md5(md5($_POST['tokencsrf'].''.$sk))){
			die('Restricted access');
		} */
		
		$id = (int)$_POST['id'];
		if($id > 0){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->delete()
				->from('#__jkreview_attachments')
				->where('id = '.$id);
			$db->setQuery($query);
			$db->execute();
		}
		
	}
	
}

?>