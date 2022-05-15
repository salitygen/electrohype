<?php
defined('_JEXEC') or die('Restricted access');

class JKHelper {
	
	public function jklist(){
		
		$db = JFactory::getDbo();
		$data = new \stdClass();
		
		$query = $db->getQuery(true)
			->select('*')
			->from('#__jkreview_reviews')
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
	
	public function csrfToken(){
		
		$config = JFactory::getConfig();
		$sk = $config->get('secret');
		$rand = md5(md5(time()).''.rand(1,1000));
		$_SESSION['admtoken'] = md5(md5($rand.''.$sk));
		setcookie('admtoken',$rand);
		
	}
	
	public function jkEdit($id){
		
		$db = JFactory::getDbo();
		$data = new \stdClass();
		
		if($id){
			
			$query = $db->getQuery(true)
				->select('*')
				->from('#__jkreview_reviews')
				->where('id = '.$id);
			$db->setQuery($query);
			$data->review = $db->loadObject();
			
			$query = $db->getQuery(true)
				->select('*')
				->from('#__jkreview_assets')
				->where('review_id = '.$id);
			$db->setQuery($query);
			$data->assets = $db->loadObjectList();

			$query = $db->getQuery(true)
				->select('*')
				->from('#__jkreview_attachments')
				->where('review_id = '.$id);
			$db->setQuery($query);
			$data->attachments = $db->loadObjectList();
			
		}

		$query = $db->getQuery(true)
			->select('*')
			->from('#__users');
		$db->setQuery($query);
		$data->users = $db->loadObjectList('id');
		
		$query = $db->getQuery(true)
			->select('*')
			->from('#__jkreview_sources');
		$db->setQuery($query);
		$data->sources = $db->loadObjectList('id');
		
		$query = $db->getQuery(true)
			->select('*')
			->from('#__categories')
			->where('extension="com_content"');
		$db->setQuery($query);
		$data->categories = $db->loadObjectList('id');

		return $data;
		
	}
	
}

?>