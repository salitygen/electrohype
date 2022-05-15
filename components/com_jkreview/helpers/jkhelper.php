<?php
defined('_JEXEC') or die('Restricted access');

class JKHelper {
	
	public function jkPageReview(){
		
		$db = JFactory::getDbo();
		$data = new \stdClass();
		
		$query = $db->getQuery(true)
			->select('*')
			->from('#__jkreview_reviews, #__jkreview_asstets')
			->where('public_status=1 AND #__jkreview_reviews.id = #__jkreview_asstets.review_id')
			->order('id DESC');
		$db->setQuery($query);
		$data->reviews = $db->loadObjectList('id');
		
		$query = $db->getQuery(true)
			->select('*')
			->from('#__jkreview_sources');
		$db->setQuery($query);
		$data->sources = $db->loadObjectList('id');
		
		$query = $db->getQuery(true)
			->select('*')
			->from('#__jkreview_attachments');
		$db->setQuery($query);
		$data->attachments = $db->loadObjectList('id');
		
		$query = $db->getQuery(true)
			->select('*')
			->from('#__categories')
			->where('extension="com_content"');
		$db->setQuery($query);
		$data->categories = $db->loadObjectList('id');
		
		return $data;
		
	}
	
	public function getAllByItemId($id=false){
		
		if($id){
			$db = JFactory::getDbo();
			$data = new \stdClass();
			
			$query = $db->getQuery(true)
				->select('*')
				->from('#__jkreview_reviews AS a')
				->join('LEFT', '#__jkreview_assets AS b ON b.review_id = a.id')
				->join('LEFT', '#__jkreview_attachments AS c ON c.review_id = a.id')
				->where('a.public_status=1 AND b.item_id='.$id);
			$db->setQuery($query);
			$data->reviews = $db->loadObjectList();
		}else{
			$data = false;
		}

		return $data;
		
	}
	
	public function getTotalByItemId($id=false){
		
		if($id){
			$db = JFactory::getDbo();
			$data = new \stdClass();
			$query = $db->getQuery(true)
				->select('SUM(rating)/COUNT(rating) AS sum, COUNT(rating) AS count')
				->from('#__jkreview_reviews AS a, #__jkreview_assets AS b')
				->where('a.public_status=1 AND a.id = b.review_id AND b.item_id='.$id);
			$db->setQuery($query);
			$data = $db->loadObjectList()[0];
		}else{
			$data = false;
		}

		return $data;
		
	}
	
	public function csrfToken(){
		
		$config = JFactory::getConfig();
		$sk = $config->get('secret');
		$rand = md5(md5(time()).''.rand(1,1000));
		$_SESSION['tokencsrf'] = md5(md5($rand.''.$sk));
		setcookie('tokencsrf',$rand);
		
	}
	
}

?>